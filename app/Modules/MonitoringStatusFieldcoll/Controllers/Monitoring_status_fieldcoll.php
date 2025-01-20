<?php 
namespace App\Modules\MonitoringStatusFieldcoll\Controllers;
use App\Modules\MonitoringStatusFieldcoll\Models\Monitoring_status_fieldcoll_model;
use CodeIgniter\Cookie\Cookie;


class Monitoring_status_fieldcoll extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Monitoring_status_fieldcoll_model = new Monitoring_status_fieldcoll_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\MonitoringStatusFieldcoll\Views\Monitor_field_coll_view', $data);
	}
	function monitor_field_coll_list(){
		$cache = session()->get('USER_ID').'_monitor_field_coll_list';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Monitoring_status_fieldcoll_model->get_monitor_field_coll_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function tracking_history(){
		$dataInput['user_id'] = $this->input->getGet('user_id');
		$data['gmapApiKey'] = getenv('gmap_apikey');
		$data["history"] = $this->Monitoring_status_fieldcoll_model->tracking_history($dataInput);
		return view('\App\Modules\MonitoringStatusFieldcoll\Views\Tracking_history_visit_view', $data);

	}
}