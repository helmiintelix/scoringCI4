<?php 
namespace App\Modules\ZipcodeAreaMapping\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\ZipcodeAreaMapping\Models\Zipcode_area_mapping_temp_model;


class Zipcode_area_mapping_temp extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Zipcode_area_mapping_temp_model = new Zipcode_area_mapping_temp_model;
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\ZipcodeAreaMapping\Views\Zipcode_area_mapping_list_view_temp', $data);
	}
	function zipcode_area_mapping_list_temp(){
		$cache = session()->get('USER_ID').'_zipcode_area_mapping_list_temp';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Zipcode_area_mapping_temp_model->get_zipcode_area_mapping_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function save_zipcode_area_mapping_edit_temp(){
		$data['id'] = $this->input->getGet('id');
		$data['sub_area_id'] = $this->input->getGet('sub_area_id');
		$return = $this->Zipcode_area_mapping_temp_model->save_zipcode_area_mapping_edit_temp($data);
		if($return){
			$cache = session()->get('USER_ID').'_zipcode_area_mapping_list_temp';
			$cachemaker = session()->get('USER_ID').'_zipcode_area_mapping_list';

			$this->cache->delete($cache);
			$this->cache->delete($cachemaker);

			$rs = array('success' => true, 'message' => 'Success to approve data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function save_note_reject_zipcode_area_mapping(){
		$id = $this->input->getGet('id');
		$return = $this->Zipcode_area_mapping_temp_model->reject_zipcode_area_mapping($id);
		if($return){
			$cache = session()->get('USER_ID').'_zipcode_area_mapping_list_temp';
			$this->cache->delete($cache);
			
			$rs = array('success' => true, 'message' => 'Success to reject data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}

	}

}