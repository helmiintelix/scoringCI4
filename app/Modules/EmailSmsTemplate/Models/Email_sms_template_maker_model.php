<?php
namespace App\Modules\EmailSmsTemplate\models;
use CodeIgniter\Model;

Class Email_sms_template_maker_model Extends Model 
{
    function get_email_sms_list(){
        $DBDRIVER = $this->db->DBDriver;
     
        if ($DBDRIVER === 'SQLSRV') {
            // SQL Server
            $isActive = "CASE 
                            WHEN a.is_active = '1' THEN 'ENABLE' 
                            ELSE 'REJECTED' 
                        END  ";
            $flag = "CASE 
                            WHEN a.flag = '1' THEN 'APPROVED' 
                            ELSE 'REJECTED' 
                        END  ";
          
        } elseif ($DBDRIVER === 'Postgre') {
            // PostgreSQL
            $isActive = "CASE 
                            WHEN a.is_active = '1' THEN 'ENABLE' 
                            ELSE 'DISABLE' 
                        END  ";
            $flag = "CASE 
                            WHEN a.flag = '1' THEN 'APPROVED' 
                            ELSE 'REJECTED' 
                        END  ";
        } else {
            // MySQL
            $isActive = "IF(a.is_active = '1', 'ENABLE', 'DISABLE') ";
            $flag = "IF(a.flag = '1', 'APPROVED', 'REJECTED') ";
        }

        $this->builder = $this->db->table('cms_email_sms_template a');
        $select = array(
            'a.template_id',
            'a.template_name',
            'a.sent_by',
            'a.recipient',
            'a.template_design',
            'a.select_mechanism',
            'a.select_time',
            'a.updated_by',
            'a.created_by',
            'a.created_time',
            $isActive.' AS is_active',
			 $flag.' AS flag', 

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
    function isExistemail_templateUpdateId($id)
	{
        $this->builder = $this->db->table('cms_email_sms_template_temp');
        $this->builder->where(array(
            'template_id' => $id
        ));
        $query = $this->builder->get();
        if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
	}
    function save_email_add($data){
        $this->builder = $this->db->table('cms_email_sms_template_temp');
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_email_edit($data){
        $this->builder = $this->db->table('cms_email_sms_template_temp');
        $return = $this->builder->insert($data);
        return $return;
    }
}