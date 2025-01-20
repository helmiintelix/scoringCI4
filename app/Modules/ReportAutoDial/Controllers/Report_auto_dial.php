<?php 
namespace App\Modules\ReportAutoDial\Controllers;
use App\Modules\ReportAutoDial\Models\Report_auto_dial_model;
use CodeIgniter\Cookie\Cookie;


class Report_auto_dial extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Report_auto_dial_model = new Report_auto_dial_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\ReportAutoDial\Views\Report_auto_dial_view', $data);
	}
	function get_report_auto_dial(){
		$cache = session()->get('USER_ID').'_report_auto_dial';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Report_auto_dial_model->get_report_auto_dial();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
}