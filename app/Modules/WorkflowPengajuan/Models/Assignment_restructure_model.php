<?php
namespace App\Modules\WorkflowPengajuan\models;

use Config\Database;
use CodeIgniter\Model;

Class Assignment_restructure_model Extends Model 
{
    function workflow_pengajuan_restructure_list($status){
        $this->builder = $this->db->table('cms_pengajuan_restructure');
        $select = array(
            "*"
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        if ($status == 'APPROVAL') {
            $this->builder->whereIn('status', ["APPROVE","REJECT","APPROVAL"]);
        } else {
            $this->builder->where('status', $status);
        }
        
        $this->builder->orderBy('created_time', 'DESC');
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

}