<?php
namespace App\Modules\ReportGenerateEmailWaSms\models;
use CodeIgniter\Model;

Class Report_generate_email_wa_sms_model Extends Model 
{
    function get_record_value($fieldName, $tableName, $criteria)
	{
	
		$builder = $this->db->table($tableName);
		$builder->select($fieldName)->where($criteria);
		
		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			$data = $query->getRowArray();
			
			return array_pop($data);
		}

		return null;
	}
    function get_report_generate_email($data){
        $this->builder = $this->db->table('cms_email_sms_template a');
        $select = array(
			'b.created_time',
            'a.sent_by','b.template_id','a.template_name',
            'sent_status',
            'a.product',
            'b.message' ,
            'b.card_no',
            'b.nama_debitur',
            'concat(a.sent_by," - ",a.template_id) id'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cc_email_log b', 'a.template_id = b.template_id');
        $this->builder->join('cms_cpcrd_new_history c', 'date(c.generate_date)=date(b.sent_time)');
        if ($data['tgl_from']) {
            $this->builder->where('date(b.created_time) >=', $data['tgl_from']);
        }
        if ($data['tgl_to']) {
            $this->builder->where('date(b.created_time) <=', $data['tgl_to']);
        }
        if ($data['sent_by']) {
            $this->builder->where('a.sent_by', $data['sent_by']);
        }
        if ($data['product']) {
            $product = str_replace(',','","',$data['product']);
            $this->builder->where('c.cm_type in ("'.$product.'")');
        }
        $this->builder->orderBy('b.created_time', 'DESC');
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
    function get_report_generate_sms($data){
        $this->builder = $this->db->table('cms_email_sms_template a');
        $select = array(
			'b.created_time',
            'a.sent_by',
            'b.template_id',
            'a.template_name',
            'b.created_by',
            'if(b.response="00","Success",b.response) response',
            'status as sent_status',
            'a.product',
            'b.content as message',
            'b.account_no as card_no',
            'c.CR_NAME_1 as nama_debitur',
            'concat(a.sent_by," - ",a.template_id) id'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cc_sms_log b','a.template_id = b.template_id');
		$this->builder->join('cms_cpcrd_new_history c' , 'date(c.generate_date)=date(b.created_time)');
        if ($data['tgl_from']) {
            $this->builder->where('date(b.created_time) >=', $data['tgl_from']);
        }
        if ($data['tgl_to']) {
            $this->builder->where('date(b.created_time) <=', $data['tgl_to']);
        }
        if ($data['sent_by']) {
            $this->builder->where('a.sent_by', $data['sent_by']);
        }
        if ($data['product']) {
            $product = str_replace(',','","',$data['product']);
            $this->builder->where('c.cm_type in ("'.$product.'")');
        }
        $this->builder->orderBy('b.created_time', 'DESC');
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
    function get_report_generate_wa($data){
        $this->builder = $this->db->table('cc_wa_log a');
        $select = array(
			'a.id',
            'a.phone_no phone_number',
            'a.message',
            'null device_id',
            'null sender_number',
            'a.sent_status status',
            'a.sent_time created_time'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->join('cms_cpcrd_new_history c' , 'date(c.generate_date)=date(a.sent_time)');
        if ($data['tgl_from']) {
            $this->builder->where('date(a.created_time) >=', $data['tgl_from']);
        }
        if ($data['tgl_to']) {
            $this->builder->where('date(a.created_time) <=', $data['tgl_to']);
        }
        // if ($data['sent_by']) {
        //     $this->builder->where('a.sent_by', $data['sent_by']);
        // }
        if ($data['product']) {
            $product = str_replace(',','","',$data['product']);
            $this->builder->where('c.cm_type in ("'.$product.'")');
        }
        $this->builder->orderBy('a.created_time', 'DESC');
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