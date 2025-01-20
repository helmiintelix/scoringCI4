<?php 
namespace App\Modules\SetupHistParameter\Controllers;
use App\Modules\SetupHistParameter\Models\Setup_hist_parameter_temp_model;
use CodeIgniter\Cookie\Cookie;

class Setup_hist_parameter_temp extends \App\Controllers\BaseController
{
	function __construct(){
		$this->Setup_hist_parameter_temp_model = new Setup_hist_parameter_temp_model;
	}
	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\SetupHistParameter\Views\Setup_angsuran_temp_view', $data);
	}
	function get_setup_angsuran_temp(){
		$cache = session()->get('USER_ID').'_setup_angsuran_temp';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_hist_parameter_temp_model->get_setup_angsuran_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function approve_system_setting(){
		$data['id'] = $this->input->getGet('id');
		$data["value"]	= $this->input->getGet("value");
		$data["add_field1"]	= "APPROVED";
		$data["created_by"] = session()->get('USER_ID');
		$data["created_time"] = date('Y-m-d H:i:s');
		$return = $this->Setup_hist_parameter_temp_model->approve_system_setting($data);
		if($return){
			$cache = session()->get('USER_ID').'_setup_angsuran_temp';
			$this->Common_model->data_logging('SETTING', $data["id"], 'APPROVAL SUCCESS', 'Set ' . $data["id"] . ' = ' . $data["value"]);
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to approve data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$this->Common_model->data_logging('SETTING', $data["id"], 'APPROVAL FAILED', 'Set ' . $data["id"] . ' = ' . $data["value"]);
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function reject_system_setting(){
		$data['id'] = $this->input->getGet('id');
		$data["value"]	= $this->input->getGet("value");
		$data["add_field1"]	= "APPROVED";
		$data["created_by"] = session()->get('USER_ID');
		$data["created_time"] = date('Y-m-d H:i:s');
		$return = $this->Setup_hist_parameter_temp_model->reject_system_setting($data);
		if($return){
			$this->Common_model->data_logging('SETTING', $data["id"], 'APPROVAL SUCCESS', 'Set ' . $data["id"] . ' = ' . $data["value"]);
			$cache = session()->get('USER_ID').'_setup_angsuran_temp';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to reject data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$this->Common_model->data_logging('SETTING', $data["id"], 'APPROVAL FAILED', 'Set ' . $data["id"] . ' = ' . $data["value"]);
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}

	}

}