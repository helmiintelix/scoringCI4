<?php 
namespace App\Modules\Inventory90\Controllers;
use App\Modules\Inventory90\Models\Inventory_90_model;
use CodeIgniter\Cookie\Cookie;


class Inventory_90 extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Inventory_90_model = new Inventory_90_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\Inventory90\Views\Recovery_view', $data);
	}
	function get_recovery_list(){
		$cache = session()->get('USER_ID').'_recovery_list';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Inventory_90_model->get_recovery_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
}