<?php 
namespace App\Modules\SetupAccountTagging\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\SetupAccountTagging\Models\Setup_account_tagging_temp_model;


class Setup_account_tagging_temp extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_account_tagging_temp_model = new Setup_account_tagging_temp_model;
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		$data['account_tagging'] = $this->Common_model->get_record_list("id as value, description AS item", "cms_phonetag_ref", "type = 'ACCOUNT TAGGING' and status='1'", "description");
		return view('\App\Modules\SetupAccountTagging\Views\setup_account_tagging_list_temp_view', $data);
	}
	function get_collection_list_temp(){
		$cache = session()->get('USER_ID').'_collection_list_temp';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_account_tagging_temp_model->get_collection_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function set_account_tagging_approve(){
		$account_no = $this->input->getGet('account_no');
		$return = $this->Setup_account_tagging_temp_model->set_account_tagging_approve($account_no);
		if($return){
			$cache = session()->get('USER_ID').'_collection_list_temp';
			$cachemaker = session()->get('USER_ID').'_collection_list';

			$this->cache->delete($cache);
			$this->cache->delete($cachemaker);

			$rs = array('success' => true, 'message' => 'Success to approve data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function set_account_tagging_reject(){
		$account_no = $this->input->getGet('account_no');
		$return = $this->Setup_account_tagging_temp_model->set_account_tagging_reject($account_no);
		if($return){
			$cache = session()->get('USER_ID').'_collection_list_temp';
			$cachemaker = session()->get('USER_ID').'_collection_list';

			$this->cache->delete($cache);
			$this->cache->delete($cachemaker);
			
			$rs = array('success' => true, 'message' => 'Success to reject data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}

	}

}