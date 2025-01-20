<?php
namespace App\Modules\ReportUserLastLogin\models;
use CodeIgniter\Model;

Class Report_user_last_login_model Extends Model 
{
    function get_report_last_login_list(){
        $builder = $this->db->table('aav_configuration');
        $builder->select('value');
        $builder->where('id', 'REPORT_LAST_LOGIN');
        $value = $builder->get()->getRow();
        $max_login = $value ? $value->value : null;

        $this->builder = $this->db->table('cc_user a');
        $select = array(
            '"" AS list_number',
            'DATE_FORMAT(a.last_login, "%d-%b-%Y") AS last_login',
            'DATEDIFF(CURDATE(), a.last_login) AS days_last_login',
            'DATE_FORMAT(a.created_time, "%d-%b-%Y") AS created_time',
            'DATE_FORMAT(a.updated_time, "%d-%b-%Y") AS updated_time',
            'a.id',
            'a.flag',
            'a.name',
            'd.name AS group_id',
            'IF(a.is_active = "1", 
                "ENABLE", 
                "DISABLE"
            ) AS is_active',
            'IF(a.login_status = "1", 
                "Logged in", 
                "Logged out"
            ) AS login_status',
            'IF(a.flag_status = "1", 
                "Approve", 
                IF(a.flag_status = "0", 
                    "No Status", 
                    "Reject"
                )
            ) AS is_status'
        );
        $this->builder->join('cc_user_group d', 'a.group_id=d.id');
        $this->builder->orderBy('a.last_login', 'DESC');
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->where('a.is_active <>', '2');
		$this->builder->where("datediff(curdate(),a.last_login) >", $max_login);
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