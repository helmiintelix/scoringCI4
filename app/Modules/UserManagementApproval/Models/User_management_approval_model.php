<?php
namespace App\Modules\UserManagementApproval\models;
use CodeIgniter\Model;

Class User_management_approval_model Extends Model 
{
   

    function get_user_management_list_temp()
    {
        $DBDRIVER = $this->db->DBDriver;
        $this->builder = $this->db->table('cc_user_tmp a');
        if ($DBDRIVER === 'SQLSRV') {
            $select = array(
                "a.id", 
                "a.name", 
                "d.name AS group_id",
                "CASE WHEN a.is_active = '1' THEN 'ENABLE' ELSE 'DISABLE' END AS is_active",
                "CASE WHEN a.login_status = '1' THEN 'Logged in' ELSE 'Logged out' END AS login_status",
                "CASE WHEN a.notes = 'Add' THEN 'NEW' ELSE 'UPDATE' END AS flagNotes",
                "CASE 
                    WHEN a.flag_status = '1' THEN 'Approve' 
                    WHEN a.flag_status = '0' THEN 'No Status' 
                    ELSE 'Reject' 
                END AS is_status",
                "a.notes",
                "a.email",
                "a.handphone",
                "a.supervisor_name",
                "a.type_collection",
                "b.name AS created_by_name",
                "c.name AS updated_by_name",
                "a.created_time AS created_time",
                "a.updated_time AS updated_time",
                "a.*"
            );
        }elseif ($DBDRIVER === 'Postgre') {
            $select = array(
                        "a.id", 
                        "a.name", 
                        "d.name AS group_id",
                        "CASE WHEN a.is_active = '1' THEN 'ENABLE' ELSE 'DISABLE' END AS is_active",
                        "CASE WHEN a.login_status = '1' THEN 'Logged in' ELSE 'Logged out' END AS login_status",
                        "CASE WHEN a.notes = 'Add' THEN 'NEW' ELSE 'UPDATE' END AS flagNotes",
                        "CASE 
                            WHEN a.flag_status = '1' THEN 'Approve' 
                            WHEN a.flag_status = '0' THEN 'No Status' 
                            ELSE 'Reject' 
                        END AS is_status",
                        "a.notes",
                        "a.email",
                        "a.handphone",
                        "a.supervisor_name",
                        "a.type_collection",
                        "b.name AS created_by_name",
                        "c.name AS updated_by_name",
                        "a.created_time AS created_time",
                        "a.updated_time AS updated_time",
                        "a.*"
            );
        }else{
            $select = array(
                'a.id', 'a.name',
                'd.name as group_id',
                'IF(a.is_active = "1", "ENABLE", "DISABLE") AS is_active',
                'IF(a.login_status = "1", "Logged in", "Logged out") AS login_status',
                'IF(a.notes = "Add", "NEW", "UPDATE") AS flagNotes',
                'IF(a.flag_status = "1", "Approve", IF(a.flag_status = "0", "No Status", "Reject")) AS is_status',
                'a.notes',
                'a.email',
                'a.handphone',
                'a.supervisor_name',
                'a.type_collection',
                'b.name created_by_name',
                'c.name updated_by_name',
                'a.created_time created_time',
                'a.updated_time updated_time',
                'a.*'
    
            );
        }
       

        

        $this->builder->select($select, false);
        $this->builder->join('cc_user b','a.created_by=b.id','left');
        $this->builder->join('cc_user c','a.updated_by=c.id','left');
        $this->builder->join('cc_user_group d','a.group_id=d.id');
        $this->builder->where('a.is_active !=','2');
        $this->builder->orderBy('a.updated_time', 'DESC');
  

        // if (!empty($where))
        //     $this->builder->where($where);

        $rResult = $this->builder->get();

        $return = $rResult->getResultArray();

        $result = array();

        if ($rResult->getNumRows() > 0) {
            foreach ($rResult->getResultArray()[0] as $key => $value) {
                $result['header'][] = array('field' => $key);
            }
            foreach ($return as $key => $value) {
                if ($value['image']=='') {
                    $return[$key]['image'] = base_url().'/assets/profilePicture/person-circle.svg';
                } else {
                    if(file_exists('./uploads/user/'.$value['image'])){
                        $return[$key]['image'] = base_url().'/uploads/user/'.$value['image'];
                    }else{
                        $return[$key]['image'] = base_url().'/assets/profilePicture/person-circle.svg';
                    }
                    
                }
            }
            $result['data'] = $return;

            $rs =  $result;
            return $rs;
        } else {
            $rs =  $result;
            return $rs;
        }
        
    }

    function save_user_edit_temp($user_data,$status)
    {
        $this->builder = $this->db->table('cc_user');
        $this->builder->where("id", $user_data["id"]);
        $this->builder->delete();

        $this->builder = $this->db->table('cc_user_tmp');
        $this->builder->where("id", $user_data["id"]);
        $hasil = $this->builder->get();

        if ($hasil->getNumRows() > 0) {
            $this->builder = $this->db->table("cc_user_tmp");
            $this->builder->where('id', $user_data["id"]);
            $this->builder->set("flag_status", "1");
            $this->builder->update();
            
            
            $this->builder = $this->db->table("cc_user_tmp");
            $this->builder->where('id', $user_data["id"]);
            $user_get = $this->builder->get();
            $user = $user_get->getRowArray();
            
            // $user = $user_get->result_array();
            $this->builder = $this->db->table("cc_user");
            $this->builder->insert($user);

            $user['status'] = $status;
            $user['insert_by'] = session()->get('USER_ID');
            $user['insert_time'] = date('Y-m-d H:i:s');
            // $user[0] = $user[0] + array('status'=>$status , 'insert_by'=>session()->get('USER_ID'),'insert_time'=>date('Y-m-d H:i:s'));
            $this->builder = $this->db->table("cc_user_tmp_history");
            $this->builder->insert($user);

            $this->builder = $this->db->table("cc_user_tmp");
            $this->builder->where('id', $user_data["id"]);
            $return = $this->builder->delete();
            return true;
        } else {
            return false;
        }
        
    }
    function delete_user($user_data)
	{
        $this->builder = $this->db->table('cc_user_tmp');
        $this->builder->where('id', $user_data["id"]);
        $hasil = $this->builder->get();
		
        if ($hasil->getNumRows() > 0) {
            $this->builder = $this->db->table('cc_user_tmp');
            $this->builder->where('id', $user_data["id"]);
            $this->builder->delete();
            return true;
        } else {
            return false;
        }
	}

  
}