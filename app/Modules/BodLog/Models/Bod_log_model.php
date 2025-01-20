<?php
namespace App\Modules\BodLog\models;
use CodeIgniter\Model;

Class Bod_log_model Extends Model 
{
    function get_bod_log_list(){
        $this->builder = $this->db->table('bod_log');
        $select = array(
            'status_time', 'description', 'status_type'
        );
        $this->builder->orderBy('status_time', 'DESC');
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

}