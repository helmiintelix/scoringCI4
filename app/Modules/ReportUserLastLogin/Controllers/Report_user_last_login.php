<?php 
namespace App\Modules\ReportUserLastLogin\Controllers;
use App\Modules\ReportUserLastLogin\Models\Report_user_last_login_model;
use CodeIgniter\Cookie\Cookie;


class Report_user_last_login extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Report_user_last_login_model = new Report_user_last_login_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\ReportUserLastLogin\Views\User_last_login_view', $data);
	}
	function get_report_last_login_list(){
		$cache = session()->get('USER_ID').'_report_last_login_list';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Report_user_last_login_model->get_report_last_login_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		$this->Common_model->data_logging('User Management', "REPORT LAST LOGIN", 'SUCCESS', '');
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
}