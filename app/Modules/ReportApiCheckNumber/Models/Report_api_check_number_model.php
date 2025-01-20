<?php
namespace App\Modules\ReportApiCheckNumber\models;
use CodeIgniter\Model;

Class Report_api_check_number_model Extends Model 
{
    function get_telesign_list(){
        $this->builder = $this->db->table('report_telesign_api a');
        $select = array(
            'id', 'cm_card_nmbr', 'handphone', 'type_phone', 'operator', 'result', 'created_time'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
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