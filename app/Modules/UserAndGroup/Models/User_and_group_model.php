<?php
namespace App\Modules\UserAndGroup\models;
use CodeIgniter\Model;

Class User_and_group_model Extends Model 
{
   

    function get_user_management_list()
    {
      
        $DBDRIVER = $this->db->DBDriver;
        $this->builder = $this->db->table('cc_user a');

        if ($DBDRIVER === 'SQLSRV') {
            // SQL Server
            $isActive = "CASE WHEN a.is_active = '1' THEN 'ENABLE' ELSE 'DISABLE' END AS is_active";
            $loginStatus = "CASE WHEN a.login_status = '1' THEN 'Logged in' ELSE 'Logged out' END AS login_status";
            $flagStatus = "CASE WHEN a.flag_status = '1' THEN 'Approve' WHEN a.flag_status = '0' THEN 'No Status' ELSE 'Reject' END AS is_status";
            $createdTime = "FORMAT(a.created_time, 'dd-MMM-yyyy') AS created_time";
            $updatedTime = "FORMAT(a.updated_time, 'dd-MMM-yyyy') AS updated_time";
        } elseif ($DBDRIVER === 'Postgre') {
            // PostgreSQL
            $isActive = "CASE WHEN a.is_active = '1' THEN 'ENABLE' ELSE 'DISABLE' END AS is_active";
            $loginStatus = "CASE WHEN a.login_status = '1' THEN 'Logged in' ELSE 'Logged out' END AS login_status";
            $flagStatus = "CASE WHEN a.flag_status = '1' THEN 'Approve' WHEN a.flag_status = '0' THEN 'No Status' ELSE 'Reject' END AS is_status";
            $createdTime = "TO_CHAR(a.created_time, 'DD-Mon-YYYY') AS created_time";
            $updatedTime = "TO_CHAR(a.updated_time, 'DD-Mon-YYYY') AS updated_time";
        } else {
            // MySQL
            $isActive = "IF(a.is_active = '1', 'ENABLE', 'DISABLE') AS is_active";
            $loginStatus = "IF(a.login_status = '1', 'Logged in', 'Logged out') AS login_status";
            $flagStatus = "IF(a.flag_status = '1', 'Approve', IF(a.flag_status = '0', 'No Status', 'Reject')) AS is_status";
            $createdTime = "DATE_FORMAT(a.created_time, '%d-%b-%Y') AS created_time";
            $updatedTime = "DATE_FORMAT(a.updated_time, '%d-%b-%Y') AS updated_time";
        }
        
        // Build SELECT
        $this->builder->select([
            'a.id',
            'a.name',
            'd.name AS group_id',
            $isActive,
            $loginStatus,
            $flagStatus,
            'a.supervisor_name',
            'a.type_collection',
            'a.email',
            'a.handphone',
            'spv.name AS spv_name',
            $createdTime,
            $updatedTime,
        ], false);

        $this->builder->join('cc_user spv', 'a.supervisor_name=spv.id', 'left');
        $this->builder->join('cc_user rto', 'a.report_to=rto.id', 'left');
        $this->builder->join('cc_user_group d', 'a.group_id=d.id', 'left');
        
   
        

        if (!empty($where))
            $this->builder->where($where);

        $rResult = $this->builder->get();

        $return = $rResult->getResultArray();

        $result = array();

        if ($rResult->getNumRows() > 0) {
            foreach ($rResult->getResultArray()[0] as $key => $value) {
                $result['header'][] = array('field' => $key);
            }
            $result['data'] = $return;

            $rs =  $result;
            return $rs;
        } else {
            $rs =  $result;
            return $rs;
        }
        
    }

    function get_user_group_management_list_temp()
    {
        $DBDRIVER = $this->db->DBDriver;
     
        if ($DBDRIVER === 'SQLSRV') {
            // SQL Server
            $isActive = "CASE 
                            WHEN a.is_active = '1' THEN 'ENABLE' 
                            ELSE 'DISABLE' 
                        END AS status";
            $createdTime = "FORMAT(a.created_time, 'dd-MMM-yyyy') AS createdTime";
            $updatedTime = "FORMAT(a.updated_time, 'dd-MMM-yyyy') AS updatedTime";
          
        } elseif ($DBDRIVER === 'Postgre') {
            // PostgreSQL
            $isActive = "CASE 
                            WHEN a.is_active = '1' THEN 'ENABLE' 
                            ELSE 'DISABLE' 
                        END AS status";
            $createdTime = "TO_CHAR(a.created_time, 'DD-Mon-YYYY') AS createdTime";
            $updatedTime = "TO_CHAR(a.updated_time, 'DD-Mon-YYYY') AS updatedTime";
        } else {
            // MySQL
            $isActive = "IF(a.is_active = '1', 'ENABLE', 'DISABLE') AS status";
            $createdTime = "DATE_FORMAT(a.created_time, '%d-%b-%Y') AS createdTime";
            $updatedTime = "DATE_FORMAT(a.updated_time, '%d-%b-%Y') AS updatedTime";
        }

        $select = array(
        'a.id groupId', 
        'a.name groupName', 
        'a.description', 
        'b.level_name as level', 
        $isActive, 
        'a.type_collection typeCollection', 
        'c.name createdBy',
        $createdTime
        );

        $this->builder = $this->db->table('cc_user_group_tmp a');
        $this->builder->join('cc_user_level b','b.id=a.level');
        $this->builder->join('cc_user c','c.id=a.created_by','left');

        $this->builder->select($select, false);

        $this->builder->where('a.id !=','ROOT');
        
        if (!empty($where))
            $this->builder->where($where);

        $rResult = $this->builder->get();

        $return = $rResult->getResultArray();


        $result = array();

        foreach ($rResult->getFieldNames() as $key => $value) {
            $result['header'][] = array('field' => $value);
        }
        if($rResult->getNumRows()>0){
            $result['data'] = $return;
        }else{
            $result['data'] = array();
        }
        return  $result;
    }

    function isExist($id){
        $this->builder = $this->db->table('cc_user a');
        $this->builder->where(array(
			'id' => $id
		));
        
		$query = $this->builder->get();

		if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
    }
    function isExistGroup($id){
        $this->builder = $this->db->table('cc_user_group a');
        $this->builder->where(array(
			'id' => $id
		));
        
		$query = $this->builder->get();

		if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
    }
    function isExistGroupTmp($id){
        $this->builder = $this->db->table('cc_user_group_tmp a');
        $this->builder->where(array(
			'id' => $id
		));
        
		$query = $this->builder->get();
 
		if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
    }

    function save_user_add($user_data)
	{
        $this->builder = $this->db->table('cc_user_tmp');
		$return = $this->builder->insert($user_data);

		return $return;
	}
    function save_user_group_edit($user_data)
	{

         $this->builder = $this->db->table('cc_user_group');
        $this->builder->where('id', $user_data["id"]);
        $return = $this->builder->update($user_data);

		return $return;
	}

    function get_user_group_management_list()
    {
        $DBDRIVER = $this->db->DBDriver;

        if ($DBDRIVER === 'SQLSRV') {
            // SQL Server
            $createdTime = "FORMAT(created_time, 'dd-MMM-yyyy')";
        } elseif ($DBDRIVER === 'Postgre') {
            // PostgreSQL
            $createdTime = "TO_CHAR(created_time, 'DD-Mon-YYYY')";
        } else {
            // MySQL
            $createdTime = "DATE_FORMAT(created_time, '%d-%b-%Y')";
        }

        $select = [
            "id",
            "name AS GroupName",
            "description",
            "created_by AS CreatedBy",
             $createdTime." AS CreatedTime"
        ];
        
        // Select Data
        $builder = $this->db->table('cc_user_group');
        $builder->select($select, false);
        $builder->where('id !=','ROOT');
        
        // Execute the query
        $query = $builder->get();

        $return = $query->getResultArray();

        $result = array();

        foreach ($query->getFieldNames() as $key => $value) {
            $result['header'][] = array('field' => $value);
        }
        $result['data'] = $return;

        return $result;
    }

    function getUserGroupById($id)
    {
        $builder = $this->db->table('cc_user_group_tmp');
        $builder->where('id', $id);
        $query = $builder->get();

        return $query->getResultArray();
    }

    function getUserGroupIdBy($id)
    {
        $builder = $this->db->table('cc_user_group');
        $builder->where('id', $id);
        $query = $builder->get();

        return $query->getResultArray();
    }

    function delete_user_group($user_data)
	{
	
        $return = $this->db->table('cc_user_group')->where('id', $user_data["id"])->delete();

		return $return;
	}


  
}
