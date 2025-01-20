<?php 
namespace App\Modules\SetupDeviationReference\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\SetupDeviationReference\Models\Setup_deviation_reference_temp_model;


class Setup_deviation_reference_temp extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_deviation_reference_temp_model = new Setup_deviation_reference_temp_model;
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\SetupDeviationReference\Views\Deviation_reference_list_view_temp', $data);
	}
	function deviation_reference_list_temp(){
		$cache = session()->get('USER_ID').'_deviation_reference_list_temp';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_deviation_reference_temp_model->get_deviation_reference_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function save_deviation_reference_edit_temp(){
		$data['id'] = $this->input->getGet('id');
		$data['deviation_reference_id'] = $this->input->getGet('deviation_id');
		$return = $this->Setup_deviation_reference_temp_model->save_deviation_reference_edit_temp($data);
		if($return){
			$cache = session()->get('USER_ID').'_deviation_reference_list_temp';
			$cachemaker = session()->get('USER_ID').'_deviation_reference_list';

			$this->cache->delete($cache);
			$this->cache->delete($cachemaker);
			$rs = array('success' => true, 'message' => 'Success to approve data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function save_note_reject_deviation_reference(){
		$id = $this->input->getGet('id');
		$return = $this->Setup_deviation_reference_temp_model->reject_deviation_reference($id);
		if($return){
			$cache = session()->get('USER_ID').'_deviation_reference_list_temp';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to reject data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}

	}

}