<?php 
namespace App\Modules\BodLog\Controllers;
use App\Modules\BodLog\Models\Bod_log_model;
use CodeIgniter\Cookie\Cookie;


class Bod_log extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Bod_log_model = new Bod_log_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\BodLog\Views\Report_bod_log_view', $data);
	}
	function get_bod_log_list(){
		$cache = session()->get('USER_ID').'_bod_log_list';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Bod_log_model->get_bod_log_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
}