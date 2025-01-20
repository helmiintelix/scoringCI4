<?php
namespace App\Modules\EmailSmsTemplate\models;
use CodeIgniter\Model;

Class Email_sms_template_temp_model Extends Model 
{
    function get_email_sms_list_temp(){
        $this->builder = $this->db->table('cms_email_sms_template_temp a');
        $DBDRIVER = $this->db->DBDriver;

        if ($DBDRIVER === 'SQLSRV') {
            // SQL Server
            $isActive = "CASE 
                            WHEN a.is_active = '1' THEN 'WAITING APPROVAL' 
                            ELSE 'REJECTED' 
                        END  ";
            $flag = "CASE 
                            WHEN a.flag = '1' THEN 'ADD' 
                            ELSE 'EDIT' 
                        END  ";
          
        } elseif ($DBDRIVER === 'Postgre') {
            // PostgreSQL
            $isActive = "CASE 
                            WHEN a.is_active = '1' THEN 'WAITING APPROVAL' 
                            ELSE 'REJECTED' 
                        END  ";
            $flag = "CASE 
                            WHEN a.flag = '1' THEN 'ADD' 
                            ELSE 'EDIT' 
                        END  ";
        } else {
            // MySQL
            $isActive = "IF(a.is_active = '1', 'WAITING APPROVAL', 'REJECTED') ";
            $flag = "IF(a.flag = '1', 'ADD', 'EDIT') ";
        }
        $select = array(
            'a.template_id',
            'a.template_name',
            'a.sent_by',
            'a.subject',
            'a.template_relation',
            'a.recipient',
            'a.product',
            'a.bucket_list',
            'a.flag_vip_cust',
            'a.product_code',
            'a.template_design',
            'a.send_mechanism',
            'a.select_mechanism',
            'a.weekly_day',
            'a.rules',
            'a.input_value',
            'a.select_time',
            'a.updated_by',
            'a.updated_time',
            'a.created_by',
            'a.created_time',
             $isActive.' AS status_approval',
            $flag.' AS jenis_request'
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
    }
    function save_email_edit_temp($id){
        $this->builder = $this->db->table('cms_email_sms_template_temp');
        $this->builder->whereIn('template_id', [$id]);
        $query = $this->builder->get();
        $data = $query->getResultArray();

        $this->db->table('cms_email_sms_template')->delete(['template_id' => $id]); // Delete data from temporary table

        $this->db->table('cms_email_sms_template')->insertBatch($data); // Insert data to main table

        $this->db->table('cms_email_sms_template_temp')->delete(['template_id' => $id]); // Delete data from temporary table

        $builder = $this->db->table('cms_email_sms_template');
        $builder->set('flag', '1');
        $builder->set('updated_time', date('Y-m-d H:i:s'));
        $builder->set('updated_by', session()->get('USER_ID'));
        $return = $builder->update();

        return $return;
    }
    function delete_email_edit_temp($id){
        $this->builder = $this->db->table('cms_email_sms_template_temp');
        $return = $this->builder->where('template_id', $id)->delete();
        return $return;
    }
}