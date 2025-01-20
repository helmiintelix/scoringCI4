<?php 
namespace App\Modules\ReportPembayaranDetail\Controllers;
use App\Modules\ReportPembayaranDetail\Models\Report_pmbayaran_detail_model;
use CodeIgniter\Cookie\Cookie;


class Report_pmbayaran_detail extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Report_pmbayaran_detail_model = new Report_pmbayaran_detail_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\ReportPembayaranDetail\Views\Report_pembayaran_detail_view', $data);
	}
	function get_pembayaran_detail(){
		$cache = session()->get('USER_ID').'_pembayaran_detail';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Report_pmbayaran_detail_model->get_data_pembayaran_detail();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
}