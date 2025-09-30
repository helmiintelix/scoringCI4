<?php
namespace App\Modules\UserManagement\models;
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
            $createdTime = "FORMAT(a.created_time, 'dd-MMM-yyyy') AS created_time";
            $updatedTime = "FORMAT(a.updated_time, 'dd-MMM-yyyy') AS updated_time";
        } elseif ($DBDRIVER === 'Postgre') {
            // PostgreSQL
            $isActive = "CASE WHEN a.is_active = '1' THEN 'ENABLE' ELSE 'DISABLE' END AS is_active";
            $loginStatus = "CASE WHEN a.login_status = '1' THEN 'Logged in' ELSE 'Logged out' END AS login_status";
            $createdTime = "TO_CHAR(a.created_time, 'DD-Mon-YYYY') AS created_time";
            $updatedTime = "TO_CHAR(a.updated_time, 'DD-Mon-YYYY') AS updated_time";
        } else {
            // MySQL
            $isActive = "IF(a.is_active = '1', 'ENABLE', 'DISABLE') AS is_active";
            $loginStatus = "IF(a.login_status = '1', 'Logged in', 'Logged out') AS login_status";
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
            'a.email',
            $createdTime,
            $updatedTime,
        ], false);

        $this->builder->join('cc_user_group d', 'a.group_id=d.id', 'left');
        
   
        $where = 'a.is_active != "2" and a.group_id != "ROOT" ';

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


    function save_user_add($user_data){
        $builder = $this->db->table('cc_user');
        $return  = $builder->insert($user_data);

        return $return;
    }

    function save_user_edit($user_data){

	    $builder = $this->db->table('cc_user');
        $return = $builder->where('id', $user_data['id'])->update($user_data);
        return $return;
    }

    function check_user_status($id_user)
    {
        $sql  = "SELECT id FROM cc_user WHERE id = ? AND is_active = '1'";
        $data = $this->db->query($sql, [$id_user])->getResultArray();

        return count($data) > 0;
    }

  
}
