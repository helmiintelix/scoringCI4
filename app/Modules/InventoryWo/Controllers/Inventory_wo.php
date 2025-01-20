<?php 
namespace App\Modules\InventoryWo\Controllers;
use App\Modules\InventoryWo\Models\Inventory_wo_model;
use CodeIgniter\Cookie\Cookie;


class Inventory_wo extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Inventory_wo_model = new Inventory_wo_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\InventoryWo\Views\Wo_view', $data);
	}
	function get_wo_list(){
		$cache = session()->get('USER_ID').'_wo_list';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Inventory_wo_model->get_wo_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
}