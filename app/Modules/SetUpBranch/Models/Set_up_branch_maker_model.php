<?php
namespace App\Modules\SetUpBranch\models;
use CodeIgniter\Model;

Class Set_up_branch_maker_model Extends Model 
{
    function get_branch_list(){
        $this->builder = $this->db->table('cms_branch a');

        $DBDRIVER = $this->db->DBDriver;
        if ($DBDRIVER === 'SQLSRV') {
            $is_active = "CASE 
                                WHEN a.is_active = '1' THEN 'ENABLE'
                                ELSE 'DISABLE'
                            END";
        }elseif ($DBDRIVER === 'Postgre') {
            $is_active = "CASE 
                                    WHEN a.is_active = '1' THEN 'ENABLE'
                                    ELSE 'DISABLE'
                                END";
        }else{
            $is_active = 'IF(a.is_active = "1", "ENABLE", "DISABLE")';
        }

        $select = array(
            'a.id',
            'a.branch_id branchId',
            'a.branch_name branchName',
            'a.branch_prov branchProv',
            'a.branch_city branchCity',
            'a.branch_kec branchKec',
            'a.branch_kel branchKel',
            'a.branch_address branchAddress',
            'a.branch_zipcode branchZipcode',
            'a.branch_no_telp branchNoTelp',
            'a.branch_manager branchManager',
            'a.created_by createdBy',
            'a.created_time createdTime',
            $is_active.' AS isActive'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
      
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
            foreach ($rResult->getResultArray()[0] as $key => $value) {
                $result['header'][] = array('field' => $key);
            }
            $result['data'] = $return;
        } else {
            $result['header'] = array();
            $result['data'] = array();
        }
        
        $rs =  $result;
        return $rs;
        // return $return;
    }
    function isExist($id){
        $this->builder = $this->db->table('cms_branch a');
        $this->builder->where(array(
            'branch_id' => $id
        ));
        $query = $this->builder->get();
        if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
    }
    function save_branch_add($data){
        $this->builder = $this->db->table('cms_branch_temp');
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_branch_edit($data){
        $this->builder = $this->db->table('cms_branch_temp');
        $return = $this->builder->insert($data);
        return $return;
    }
}