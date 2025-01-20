<?php 
namespace App\Modules\CaseBaseReportEod\Controllers;
use App\Modules\CaseBaseReportEod\Models\Case_base_report_eod_model;
use CodeIgniter\Cookie\Cookie;
use DateTime;

class Case_base_report_eod extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Case_base_report_eod_model = new Case_base_report_eod_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		$data['product'] 	=  array("" => "[ALL]") + $this->Common_model->get_record_list("CM_TYPE AS item, CM_TYPE AS value", "cpcrd_new", "CM_TYPE IS NOT NULL", "CM_TYPE");
		return view('\App\Modules\CaseBaseReportEod\Views\Report_case_base_view', $data);
	}
	function case_base_data(){
		$dataInput['start_date'] = $this->input->getGet('start_date');
		$dataInput['end_date'] = $this->input->getGet('end_date');
		$dataInput['sub_product'] = $this->input->getGet('sub_product');
		$dataInput['search_by'] = $this->input->getGet('search_by');
		$dataInput['keyword'] = $this->input->getGet('keyword');
		// print_r($dataInput);
		// exit();
		$data = $this->Case_base_report_eod_model->get_case_base_data($dataInput);
	    if ($data) {
			$cacheKey = session()->get('USER_ID') . '_case_base_data';
			$this->cache->delete($cacheKey);
			$this->cache->save($cacheKey, json_encode($data), env('TIMECACHE_1'));
	
			$rs = ['success' => true, 'message' => 'Success to apply filter', 'data' => $data];
		} else {
			$rs = ['success' => false, 'message' => 'failed', 'data' => null];
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
}