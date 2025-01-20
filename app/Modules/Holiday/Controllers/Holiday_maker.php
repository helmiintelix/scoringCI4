<?php 
namespace App\Modules\Holiday\Controllers;
use App\Modules\Holiday\Models\Holiday_maker_model;
use App\Modules\Holiday\Models\Holiday_temp_model;
use CodeIgniter\Cookie\Cookie;

class Holiday_maker extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Holiday_maker_model = new Holiday_maker_model();
		$this->Holiday_temp_model = new Holiday_temp_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\Holiday\Views\Holiday_maker_view', $data);
	}
	function holiday_list(){
		$cache = session()->get('USER_ID').'_holiday_list';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Holiday_maker_model->get_holiday_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function holiday_add_form() {
		return view('App\Modules\Holiday\Views\Holiday_add_form_view');
	}
	function save_holiday_add(){
		$data['id'] = uuid();
		$data['holiday_name'] = $this->input->getPost('txt-holiday-name');
		$data['holiday_date'] = $this->input->getPost('txt-holiday-date');
		$data['remark'] = $this->input->getPost('txt-remark');
		$data['is_active'] = '1';
		$data['flag'] = '1';
		$data['created_by'] = session()->get('USER_ID');
		$data['created_time'] = date('Y-m-d H:i:s');
		// $data['flag'] = $this->input->getPost('txt-id');
		$return = $this->Holiday_maker_model->save_holiday_add($data);
		if ($return) {
			$cache = session()->get('USER_ID').'_holiday_list_temp';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function holiday_edit_form(){
		$id= $this->input->getGet('id');
		$data["data"] = $this->Common_model->get_record_values("*", "cc_holiday", "id = '".$id."'");
		return view('App\Modules\Holiday\Views\holiday_edit_form_view', $data);
	}
	function save_holiday_edit(){
		$data['id'] = $this->input->getPost('txt-id');
		$data['holiday_name'] = $this->input->getPost('txt-holiday-name');
		$data['holiday_date'] = $this->input->getPost('txt-holiday-date');
		$data['remark'] = $this->input->getPost('txt-remark');
		$data['is_active'] = '1';
		$data['flag'] = '2';
		$data['created_by'] = session()->get('USER_ID');
		$data['created_time'] = date('Y-m-d H:i:s');
		$return = $this->Holiday_maker_model->save_holiday_edit($data);
		if ($return) {
			$cache = session()->get('USER_ID').'_holiday_list_temp';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function delete_holiday(){
		$id = $this->input->getPost('id');
		$return = $this->Holiday_maker_model->delete_holiday($id);
		if ($return) {
			$cache = session()->get('USER_ID').'_holiday_list';
			$this->cache->delete($cache);
			$data = $this->Holiday_maker_model->get_holiday_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1'));
			$rs = array('success' => true, 'message' => 'Success to delete data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

}