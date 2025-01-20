<?php 
namespace App\Modules\Bucket\Controllers;
// namespace App\Modules\VisitRadius\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\Bucket\models\Bucket_temp_model;


class Bucket_temp extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Bucket_temp_model = new Bucket_temp_model;
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\Bucket\Views\Bucket_temp_view', $data);
	}
	function bucket_list_temp(){
		$cache = session()->get('USER_ID').'_bucket_list_temp';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Bucket_temp_model->get_bucket_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function save_note_approve_bucket(){
		$id = $this->input->getGet('id');
		$return = $this->Bucket_temp_model->approve_request_bucket($id);
		if($return){
			$cache = session()->get('USER_ID').'_bucket_list_temp';
			$cachemaker = session()->get('USER_ID').'_bucket_list';

			$this->cache->delete($cache);
			$this->cache->delete($cachemaker);

			$rs = array('success' => true, 'message' => 'Success to approve data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function save_note_reject_bucket(){
		$id = $this->input->getGet('id');
		$return = $this->Bucket_temp_model->delete_data_request($id);
		if($return){
			$cache = session()->get('USER_ID').'_bucket_list_temp';
			$this->cache->delete($cache);
			
			$rs = array('success' => true, 'message' => 'Success to reject data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}

	}

}