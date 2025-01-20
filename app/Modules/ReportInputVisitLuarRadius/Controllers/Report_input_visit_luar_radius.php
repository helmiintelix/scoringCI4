<?php 
namespace App\Modules\ReportInputVisitLuarRadius\Controllers;
use App\Modules\ReportInputVisitLuarRadius\Models\Report_input_visit_luar_radius_model;
use CodeIgniter\Cookie\Cookie;


class Report_input_visit_luar_radius extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Report_input_visit_luar_radius_model = new Report_input_visit_luar_radius_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\ReportInputVisitLuarRadius\Views\Report_visit_luar_radius_view', $data);
	}
	function get_report_visit_luar_radius(){
		$cache = session()->get('USER_ID').'_report_visit_luar_radius';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Report_input_visit_luar_radius_model->get_report_visit_luar_radius();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function tracking_history_visit(){
		$dataInput['user_id'] = $this->input->getGet('user_id');
		$dataInput['id'] = $this->input->getGet('id');
		$dataInput['card'] = $this->input->getGet('card');
		$dataInput['addr_type'] = $this->input->getGet('addr_type');

		$data['gmapApiKey'] = getenv('gmap_apikey');
		$data["history"] = $this->Report_input_visit_luar_radius_model->tracking_field_visit($dataInput);
		// print_r($data["gmapApiKey"]);
		// exit();
		return view('\App\Modules\ReportInputVisitLuarRadius\Views\Tracking_history_visit_view', $data);

	}
}