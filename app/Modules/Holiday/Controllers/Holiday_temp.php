<?php 
namespace App\Modules\Holiday\Controllers;
// namespace App\Modules\VisitRadius\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\Holiday\Models\Holiday_temp_model;


class Holiday_temp extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Holiday_temp_model = new Holiday_temp_model;
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\Holiday\Views\holiday_temp_view', $data);
	}
	function holiday_list_temp(){
		$cache = session()->get('USER_ID').'_holiday_list_temp';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Holiday_temp_model->get_holiday_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function save_note_approve_holiday(){
		$id = $this->input->getGet('id');
		$return = $this->Holiday_temp_model->approve_request_holiday($id);
		if($return){
			$cache = session()->get('USER_ID').'_holiday_list_temp';
			$cachemaker = session()->get('USER_ID').'_holiday_list';

			$this->cache->delete($cache);
			$this->cache->delete($cachemaker);


			// Update cache dengan data terbaru
			$data = $this->Holiday_temp_model->get_holiday_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1'));
			
			$rs = array('success' => true, 'message' => 'Success to approve data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function save_note_reject_holiday(){
		$id = $this->input->getGet('id');
		$return = $this->Holiday_temp_model->delete_data_request($id);
		if($return){
			$cache = session()->get('USER_ID').'_holiday_list_temp';

			$this->cache->delete($cache);
			$data = $this->Holiday_temp_model->get_holiday_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1'));
			
			$rs = array('success' => true, 'message' => 'Success to reject data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}

	}

}