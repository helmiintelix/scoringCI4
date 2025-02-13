<?php 
namespace App\Modules\MonitoringDebitur\Controllers;
use App\Modules\MonitoringDebitur\Models\Monitoring_debitur_model;
use CodeIgniter\Cookie\Cookie;
use PhpParser\Node\Stmt\Switch_;

class Monitoring_debitur extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Monitoring_debitur_model = new Monitoring_debitur_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\MonitoringDebitur\Views\Debitur_view', $data);
	}
	function customer_list(){
		// $cache = session()->get('USER_ID').'_customer_list';
		// if($this->cache->get($cache)){
		// 	$data = json_decode($this->cache->get($cache));
		// 	$rs = array('success' => true, 'message' => '', 'data' => $data);
		// }else{
		// 	$data = $this->Monitoring_debitur_model->get_customer_list();
		// 	$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
		// 	$rs = array('success' => true, 'message' => '', 'data' => $data);
		// }
		$data = $this->Monitoring_debitur_model->get_customer_list();
		$rs = array('success' => true, 'message' => '', 'data' => $data);
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function tracking(){
		$dataInput['user_id'] = $this->input->getGet('user_id');
		$dataInput['id'] = $this->input->getGet('id');
		$dataInput['card'] = $this->input->getGet('card');
		$dataInput['addr_type'] = $this->input->getGet('addr_type');

		$data['gmapApiKey'] = getenv('gmap_apikey');
		$data["history"] = $this->Monitoring_debitur_model->tracking_field_visit($dataInput);
		// print_r($data["history"]);
		// exit();
		return view('\App\Modules\MonitoringDebitur\Views\Tracking_history_view', $data);

	}

	function petugas(){
		return view('\App\Modules\MonitoringDebitur\Views\Petugas_view');
	}

	function petugas_list(){
		// $cache = session()->get('USER_ID').'_customer_list';
		// if($this->cache->get($cache)){
		// 	$data = json_decode($this->cache->get($cache));
		// 	$rs = array('success' => true, 'message' => '', 'data' => $data);
		// }else{
		// 	$data = $this->Monitoring_debitur_model->get_customer_list();
		// 	$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
		// 	$rs = array('success' => true, 'message' => '', 'data' => $data);
		// }
		$data = $this->Monitoring_debitur_model->get_petugas_list();
		$rs = array('success' => true, 'message' => '', 'data' => $data);
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function popup(){
		$data['userId'] = $this->input->getGet('userid');
		$data['kategori'] = $this->input->getGet('kategori');

		return view('\App\Modules\MonitoringDebitur\Views\Popup', $data);

	}

	function popup_list(){
		$userId = $this->input->getGet('userid');
		$kategori = $this->input->getGet('kategori');
		

		switch ($kategori) {
			case 'jumlahAssignment':
				# code...
				$data = $this->Monitoring_debitur_model->get_jumlahAssignment_list($userId);
				break;
			case 'totalVisit':
				# code...
				$data = $this->Monitoring_debitur_model->get_totalVisit_list($userId);
				break;
			case 'totalPtp':
				# code...
				$data = $this->Monitoring_debitur_model->get_totalPtp_list($userId);
				break;
			case 'totalPayment':
				# code...
				$data = $this->Monitoring_debitur_model->get_totalPayment_list($userId);
				break;
			
			default:
				# code...
				break;
		}
		// $cache = session()->get('USER_ID').'_customer_list';
		// if($this->cache->get($cache)){
		// 	$data = json_decode($this->cache->get($cache));
		// 	$rs = array('success' => true, 'message' => '', 'data' => $data);
		// }else{
		// 	$data = $this->Monitoring_debitur_model->get_customer_list();
		// 	$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
		// 	$rs = array('success' => true, 'message' => '', 'data' => $data);
		// }
		$rs = array('success' => true, 'message' => '', 'data' => $data);
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

}