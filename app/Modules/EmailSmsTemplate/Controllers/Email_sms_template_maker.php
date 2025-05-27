<?php 
namespace App\Modules\EmailSmsTemplate\Controllers;
use App\Modules\EmailSmsTemplate\Models\Email_sms_template_maker_model;
use App\Modules\EmailSmsTemplate\Models\Email_sms_template_temp_model;
use CodeIgniter\Cookie\Cookie;

class Email_sms_template_maker extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Email_sms_template_maker_model = new Email_sms_template_maker_model();
		$this->Email_sms_template_temp_model = new Email_sms_template_temp_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\EmailSmsTemplate\Views\Email_list_maker_view', $data);
	}
	function email_sms_list(){
		$cache = session()->get('USER_ID').'_email_sms_list';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Email_sms_template_maker_model->get_email_sms_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function email_sms_add_form() {
		// $builder = $this->db->table('cms_product');
		// $builder->select('description','product_id');
		// $builder->groupBy('product_id');
		// $builder->groupBy('description');
		$data['product'] = [];
		$data['bucket'] = $this->Common_model->get_record_list("bucket_id value, bucket_label AS item", "cms_bucket", "is_active='1'", "bucket_label");

		return view('App\Modules\EmailSmsTemplate\Views\Email_sms_add_form_view', $data);
	}
	function save_email_sms_add(){

		$product = $this->input->getPost("opt-product") == null ? '' : implode(",", $this->input->getPost("opt-product"));
		$bucket_list = $this->input->getPost("opt-bucket") == null ? '' : implode(",", $this->input->getPost("opt-bucket"));
		$rules_list = $this->input->getPost("opt-rules") == null ? '' : implode(",", $this->input->getPost("opt-rules"));
		$flag_vip_cust = $this->input->getPost("opt-flag-vip") == null ? '' : implode(",", $this->input->getPost("opt-flag-vip"));
		$product_code = $this->input->getPost("opt-product-code") == null ? '' : implode(",", $this->input->getPost("opt-product-code"));
		$select_time = $this->input->getPost("txt-template-input-times") == null ? '' : implode(",", $this->input->getPost("txt-template-input-times"));

		// if ($this->input->getPost('opt-sentby') == 'Email') {
		// 	$id = 'E-'. uuid();
		// } else {
		// 	$id = 'S-'. uuid();
		// }
		// $data['id'] = $id;
		$data['template_id'] = $this->input->getPost('txt-template-id');
		$data['template_name'] = $this->input->getPost('txt-template-name');
		$data['sent_by'] = $this->input->getPost('opt-sentby');
		$data['template_relation'] = $this->input->getPost('opt-template-relation');
		$data['recipient'] = $this->input->getPost('opt-recipient');
		$data['product'] = $product;
		$data['product_code'] = $product_code;
		$data['template_design'] =$this->input->getPost("content");
		$data['bucket_list'] = $bucket_list;
		$data['flag_vip_cust'] = $flag_vip_cust;
		$data['select_mechanism'] = $this->input->getPost("opt-schedule");
		if (!empty($this->input->getPost("opt-week"))) {
			$email_data["weekly_day"] = implode(",", $this->input->getPost("opt-week"));
		}

		$schedule_detail = array();
		if (!$this->input->getPost("months-checkbox")) {
			$months = 'false';
		} else {
			$months = $this->input->getPost("months-checkbox");
		}
		if (!$this->input->getPost("month-option")) {
			$options = 'false';
		} else {
			$options = $this->input->getPost("month-option");
		}
		if (!$this->input->getPost("weekday-checkbox")) {
			$weekday = 'false';
		} else {
			$weekday = $this->input->getPost("weekday-checkbox");
		}
		if (!$this->input->getPost("weeks-checkbox")) {
			$weeks = 'false';
		} else {
			$weeks = $this->input->getPost("weeks-checkbox");
		}
		if ($this->input->getPost("day-checkbox") == null) {
			$day = 'FALSE';
		} else {
			$day = $this->input->getPost("day-checkbox");
		}

		$schedule_detail["months"] =  $months;
		$schedule_detail["options"] = $options;
		$schedule_detail["weekday"] = $weekday;
		$schedule_detail["weeks"] = $weeks;
		$schedule_detail["day"] = $day;
		$data["schedule_detail"] = json_encode($schedule_detail);

		$data["rules"] = $rules_list;
		$data["input_value"] = $this->input->getPost("txt-template-input-value");
		$data["select_time"] = $select_time;

		$data['is_active'] = $this->input->getPost('opt-active-flag');
		$data['flag'] = '1';
		$data['created_by'] = session()->get('USER_ID');
		$data['created_time'] = date('Y-m-d H:i:s');
		// print_r($data);
		// exit();
		// $data['flag'] = $this->input->getPost('txt-id');
		$return = $this->Email_sms_template_maker_model->save_email_add($data);
		if ($return) {
			$cache = session()->get('USER_ID').'_email_sms_list_temp';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function emailSmsEditForm(){
		$id= $this->input->getGet('id');
		$data["data"] = $this->Common_model->get_record_values("*", "cms_email_sms_template", "template_id = '".$id."'");
		$data['bucket'] = $this->Common_model->get_record_list("bucket_id value, bucket_label AS item", "cms_bucket", "is_active='1'", "bucket_label");
		$data['bucket_list'] = explode(',', $data["data"]['bucket_list']);
		$data['rules_list'] = explode(',', $data["data"]['rules']);
		$data['product'] = explode(',', $data["data"]['product']);
		$data['product_code'] = explode(',', $data["data"]['product_code']);
		$data['flag_vip_cust'] = explode(',', $data["data"]['flag_vip_cust']);
		$data['select_time'] = $data['data']['select_time'];
		$data['is_active'] = $data['data']['is_active'];
		$data['template_design'] = $data['data']['template_design'];
		// $builder = $this->db->table('cms_product');
		// $builder->select('description','product_id');
		// $builder->groupBy('product_id');
		// $builder->groupBy('description');
		$data["product_code_master"] = [];
		$param = $data['product'];
		$where = '';
		foreach ($param as $i => $rows) {
			if ($i > 0) {
				$where .= 'or ';
			}
			$where .= "PRODUCT_ID='" . $rows . "' ";
		}
		$data["product_code_data"] = $this->Common_model->get_record_list("DISTINCT(CM_TYPE) value, CM_TYPE item", "cpcrd_new", $where, "CM_TYPE");
		// print_r($data);
		// exit();
		return view('App\Modules\EmailSmsTemplate\Views\Email_sms_edit_form_view', $data);
	}
	function save_email_sms_edit(){
		$id = $this->input->getPost('txt-template-id');
		$is_exist = $this->Email_sms_template_maker_model->isExistemail_templateUpdateId($id);
		if (!$is_exist) {
			$product = $this->input->getPost("opt-product") == null ? '' : implode(",", $this->input->getPost("opt-product"));
			$bucket_list = $this->input->getPost("opt-bucket") == null ? '' : implode(",", $this->input->getPost("opt-bucket"));
			$rules_list = $this->input->getPost("opt-rules") == null ? '' : implode(",", $this->input->getPost("opt-rules"));
			$flag_vip_cust = $this->input->getPost("opt-flag-vip") == null ? '' : implode(",", $this->input->getPost("opt-flag-vip"));
			$product_code = $this->input->getPost("opt-product-code") == null ? '' : implode(",", $this->input->getPost("opt-product-code"));
			$select_time = $this->input->getPost("txt-template-input-times") == null ? '' : implode(",", $this->input->getPost("txt-template-input-times"));

			// if ($this->input->getPost('opt-sentby') == 'Email') {
			// 	$id = 'E-'. uuid();
			// } else {
			// 	$id = 'S-'. uuid();
			// }
			// $data['id'] = $id;
			$data['template_id'] = $id;
			$data['template_name'] = $this->input->getPost('txt-template-name');
			$data['sent_by'] = $this->input->getPost('opt-sentby');
			$data['template_relation'] = $this->input->getPost('opt-template-relation');
			$data['recipient'] = $this->input->getPost('opt-recipient');
			$data['product'] = $product;
			$data['product_code'] = $product_code;
			$data['template_design'] =$this->input->getPost("content");
			$data['bucket_list'] = $bucket_list;
			$data['flag_vip_cust'] = $flag_vip_cust;
			$data['select_mechanism'] = $this->input->getPost("opt-schedule");
			if (!empty($this->input->getPost("opt-week"))) {
				$email_data["weekly_day"] = implode(",", $this->input->getPost("opt-week"));
			}

			$schedule_detail = array();
			if (!$this->input->getPost("months-checkbox")) {
				$months = 'false';
			} else {
				$months = $this->input->getPost("months-checkbox");
			}
			if (!$this->input->getPost("month-option")) {
				$options = 'false';
			} else {
				$options = $this->input->getPost("month-option");
			}
			if (!$this->input->getPost("weekday-checkbox")) {
				$weekday = 'false';
			} else {
				$weekday = $this->input->getPost("weekday-checkbox");
			}
			if (!$this->input->getPost("weeks-checkbox")) {
				$weeks = 'false';
			} else {
				$weeks = $this->input->getPost("weeks-checkbox");
			}
			if ($this->input->getPost("day-checkbox") == null) {
				$day = 'FALSE';
			} else {
				$day = $this->input->getPost("day-checkbox");
			}

			$schedule_detail["months"] =  $months;
			$schedule_detail["options"] = $options;
			$schedule_detail["weekday"] = $weekday;
			$schedule_detail["weeks"] = $weeks;
			$schedule_detail["day"] = $day;
			$data["schedule_detail"] = json_encode($schedule_detail);

			$data["rules"] = $rules_list;
			$data["input_value"] = $this->input->getPost("txt-template-input-value");
			$data["select_time"] = $select_time;

			$data['is_active'] = $this->input->getPost('opt-active-flag');
			$data['flag'] = '1';
			$data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			// $data['flag'] = $this->input->getPost('txt-id');
			$return = $this->Email_sms_template_maker_model->save_email_edit($data);
			if ($return) {
				$cache = session()->get('USER_ID').'_email_sms_list_temp';
				$this->cache->delete($cache);
				$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
		} else {
			$rs = array('success' => false, 'message' => 'Please approve/reject the data first', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

}