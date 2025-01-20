<?php 
namespace App\Modules\MonitoringFieldcoll\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\MonitoringFieldcoll\Models\Monitoring_fieldcoll_model;


class Monitoring_fieldcoll extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Monitoring_fieldcoll_model = new Monitoring_fieldcoll_model();
	}

	function index()
	{
		
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\MonitoringFieldcoll\Views\Monitoring_petugas_list_view',$data);
	}

	function get_fc_monitoring_list()
	{
		$cache = session()->get('USER_ID').'_fc_monitoring_list';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Monitoring_fieldcoll_model->get_fc_monitoring_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function tracking_history(){
		$data["user_id"]	= $this->input->getGet("user_id");
		$data['gmapApiKey'] = getenv('gmap_apikey');
		$data["history"] = $this->Monitoring_fieldcoll_model->tracking_history($data);
		

		return view('\App\Modules\MonitoringFieldcoll\Views\Tracking_history_view',$data);
		
	}
	
}