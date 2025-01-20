<?php 
namespace App\Modules\AgencyManagement\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\AgencyManagement\Models\Agency_management_temp_model;


class Agency_management_temp extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Agency_management_temp_model = new Agency_management_temp_model;
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\AgencyManagement\Views\Settings_am_list_view_temp', $data);
	}
	function settings_am_list_temp(){
		$cache = session()->get('USER_ID').'_settings_am_list_temp';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Agency_management_temp_model->get_settings_am_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function save_note_approve_am(){
		$id = $this->input->getGet('id');
		$return = $this->Agency_management_temp_model->approve_agency($id);
		if($return){
			$cache = session()->get('USER_ID').'_settings_am_list_temp';
			$cachemaker = session()->get('USER_ID').'_settings_am_list';

			$this->cache->delete($cache);
			$this->cache->delete($cachemaker);
			
			$rs = array('success' => true, 'message' => 'Success to approve data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function save_note_reject_am(){
		$id = $this->input->getGet('id');
		$return = $this->Agency_management_temp_model->delete_agency($id);
		if($return){
			$cache = session()->get('USER_ID').'_settings_am_list_temp';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to reject data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}

	}

}