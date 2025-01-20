<?php 
namespace App\Modules\EmailSmsTemplate\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\EmailSmsTemplate\Models\Email_sms_template_temp_model;


class Email_sms_template_temp extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Email_sms_template_temp_model = new Email_sms_template_temp_model;
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\EmailSmsTemplate\Views\Email_list_temp_view', $data);
	}
	function email_sms_list_temp(){
		$cache = session()->get('USER_ID').'_email_sms_list_temp';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Email_sms_template_temp_model->get_email_sms_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function save_email_sms_edit_temp(){
		$id = $this->input->getGet('id');
		$return = $this->Email_sms_template_temp_model->save_email_edit_temp($id);
		if($return){
			$cache = session()->get('USER_ID').'_email_sms_list_temp';
			$cachemaker = session()->get('USER_ID').'_email_sms_list';

			$this->cache->delete($cache);
			$this->cache->delete($cachemaker);

			$rs = array('success' => true, 'message' => 'Success to approve data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function delete_email_sms_edit_temp(){
		$id = $this->input->getGet('id');
		$return = $this->Email_sms_template_temp_model->delete_email_edit_temp($id);
		if($return){
			$cache = session()->get('USER_ID').'_email_sms_list_temp';

			$this->cache->delete($cache);
			
			$rs = array('success' => true, 'message' => 'Success to reject data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}

	}

}