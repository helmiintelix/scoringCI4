<?php
namespace App\Modules\SetUpBranch\models;
use CodeIgniter\Model;

Class Set_up_branch_temp_model Extends Model 
{
    function get_branch_list_temp(){
        $this->builder = $this->db->table('cms_branch_temp a');
        $DBDRIVER = $this->db->DBDriver;
        if ($DBDRIVER === 'SQLSRV') {
            $status_approval = "CASE 
                                WHEN a.is_active = '1' THEN 'WAITING APPROVAL'
                                ELSE 'REJECTED'
                            END";

            $jenis_request = "CASE 
                                WHEN a.flag = '1' THEN 'ADD'
                                ELSE 'EDIT'
                            END";
        }elseif ($DBDRIVER === 'Postgre') {
            $status_approval = "CASE 
                                WHEN a.is_active = '1' THEN 'WAITING APPROVAL'
                                ELSE 'REJECTED'
                            END";

            $jenis_request = "CASE 
                                    WHEN a.flag = '1' THEN 'ADD'
                                    ELSE 'EDIT'
                            END";
        }else{
            $status_approval = 'IF(a.is_active = "1", "WAITING APPROVAL", "REJECTED")';
            $jenis_request = 'IF(a.flag = "1", "ADD", "EDIT")';
        }
        $select = array(
            'a.id',
            'a.branch_id',
            'a.branch_name',
            'a.branch_prov',
            'a.branch_city',
            'a.branch_kec',
            'a.branch_kel',
            'a.branch_address',
            'a.branch_no_telp',
            'a.branch_manager',
            'a.created_time',
            $status_approval.' AS status_approval',
            $jenis_request.' AS jenis_request'
        );

       
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->orderBy('a.created_time', 'desc');
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
    }
    function save_branch_edit_temp($id){
        $this->builder = $this->db->table('cms_branch_temp');
        $this->builder->whereIn('id', [$id]);
        $query = $this->builder->get();
        $data = $query->getResultArray();

        $this->db->table('cms_branch')->delete(['id' => $id]); // Delete data from temporary table

        $this->db->table('cms_branch')->insertBatch($data); // Insert data to main table

        $this->db->table('cms_branch_temp')->delete(['id' => $id]); // Delete data from temporary table

        return $data;
    }
    function reject_branch($id){
        $this->builder = $this->db->table('cms_branch_temp');
        $return = $this->builder->where('id', $id)->delete();
        return $return;
    }
}