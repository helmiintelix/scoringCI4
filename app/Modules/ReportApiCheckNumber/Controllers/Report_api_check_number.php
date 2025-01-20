<?php 
namespace App\Modules\ReportApiCheckNumber\Controllers;
use App\Modules\ReportApiCheckNumber\Models\Report_api_check_number_model;
use CodeIgniter\Cookie\Cookie;


class Report_api_check_number extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Report_api_check_number_model = new Report_api_check_number_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\ReportApiCheckNumber\Views\Report_telesign_view', $data);
	}
	function get_telesign_list(){
		$cache = session()->get('USER_ID').'_telesign_list';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Report_api_check_number_model->get_telesign_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		$this->Common_model->data_logging('User Management', "REPORT LAST LOGIN", 'SUCCESS', '');
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
}