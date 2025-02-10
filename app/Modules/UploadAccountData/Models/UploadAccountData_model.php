<?php
namespace App\Modules\UploadAccountData\models;
use CodeIgniter\Model;

Class UploadAccountData_model Extends Model 
{
    function get_upload_data(){
        $select = [
            'a.id', 
            'a.fileName', 
            'a.createdBy', 
            'a.createdTime', 
            'a.approvedBy', 
            'a.approvedTime', 
        ];

        $this->builder = $this->db->table('upload_account_data a');
        $this->builder->select($select);
       
        $this->builder->orderBy('createdBy', 'DESC');
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
    
   
}