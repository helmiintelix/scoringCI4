<?php 
namespace App\Modules\VisitRadius\Controllers;
// namespace App\Modules\VisitRadius\Controllers;
use CodeIgniter\Cookie\Cookie;
// use App\Modules\VisitRadius\models\User_and_group_model;
use App\Modules\VisitRadius\models\Visit_radius_temp_model;


class Visit_radius_temp extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Visit_radius_temp_model = new Visit_radius_temp_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\VisitRadius\Views\Visit_radius_temp_view', $data);
	}
	function visit_radius_list_temp(){
		$cache = session()->get('USER_ID').'_visit_radius_list_temp';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Visit_radius_temp_model->get_visit_radius_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function save_visit_radius_approve(){
		$id = $this->input->getGet('id');
		
		$return = $this->Visit_radius_temp_model->approve_request_visit_radius($id);
		// $data['data'] = json_encode($return);
		// return $this->response->setStatusCode(200)->setJSON($data['data']);
		if($return){
			$cache = session()->get('USER_ID').'_visit_radius_list_temp';
			$cachemaker = session()->get('USER_ID').'_visit_radius_all_list';

			$this->cache->delete($cache);
			$this->cache->delete($cachemaker);


			// Update cache dengan data terbaru
			$data = $this->Visit_radius_temp_model->get_visit_radius_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1'));
			
			$rs = array('success' => true, 'message' => 'Success to approve data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function save_visit_radius_reject(){
		$id = $this->input->getGet('id');
		$return = $this->Visit_radius_temp_model->approve_request_visit_radius($id);
		// $data['data'] = json_encode($return);
		// return $this->response->setStatusCode(200)->setJSON($data['data']);
		if($return){
			$cache = session()->get('USER_ID').'_visit_radius_list_temp';
			$this->cache->delete($cache);

			// Update cache dengan data terbaru
			$data = $this->Visit_radius_temp_model->get_visit_radius_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1'));
			
			$rs = array('success' => true, 'message' => 'Success to reject data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

}