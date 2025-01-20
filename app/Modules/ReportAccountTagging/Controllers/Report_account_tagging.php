<?php 
namespace App\Modules\ReportAccountTagging\Controllers;
use App\Modules\ReportAccountTagging\Models\Report_account_tagging_model;
use CodeIgniter\Cookie\Cookie;


class Report_account_tagging extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Report_account_tagging_model = new Report_account_tagging_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\ReportAccountTagging\Views\Report_account_tagging_view', $data);
	}
	function get_account_tagging_list(){
		$cache = session()->get('USER_ID').'_account_tagging_list';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Report_account_tagging_model->get_account_tagging_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
}