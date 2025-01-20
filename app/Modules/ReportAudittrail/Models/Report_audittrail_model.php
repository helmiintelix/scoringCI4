<?php
namespace App\Modules\ReportAudittrail\models;
use CodeIgniter\Model;

Class Report_audittrail_model Extends Model 
{
    function get_application_log_list(){
        $this->builder = $this->db->table('cc_app_log');
        $select = array(
            'id',
			'created_time',
			'ip_address',
			'created_by',
			'module',
			'action',
			'result',
			'description'
        );
        $this->builder->orderBy('created_time', 'DESC');
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