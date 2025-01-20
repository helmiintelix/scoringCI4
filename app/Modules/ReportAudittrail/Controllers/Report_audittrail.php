<?php 
namespace App\Modules\ReportAudittrail\Controllers;
use App\Modules\ReportAudittrail\Models\Report_audittrail_model;
use CodeIgniter\Cookie\Cookie;


class Report_audittrail extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Report_audittrail_model = new Report_audittrail_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\ReportAudittrail\Views\Application_log_view', $data);
	}
	function application_log_list(){
		$cache = session()->get('USER_ID').'_application_log_list';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Report_audittrail_model->get_application_log_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
}