<?php 
namespace App\Modules\PhoneTagging\Controllers;
use App\Modules\PhoneTagging\Models\Phone_tagging_list_model;
// use App\Modules\PhoneTagging\models\Phone_tagging_ref_model;
use CodeIgniter\Cookie\Cookie;

class Phone_tagging_list extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Phone_tagging_list_model = new Phone_tagging_list_model();
		// $this->Phone_tagging_ref_model = new Phone_tagging_ref_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\PhoneTagging\Views\Setup_phone_tagging_list_view', $data);
	}
	function get_phone_tagging_list(){
		$cache = session()->get('USER_ID').'_phone_tagging_list';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Phone_tagging_list_model->get_phone_tagging_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function phone_tagging_add_form(){
		$id= $this->input->getGet('id');
		$data['data'] = $this->Common_model->get_record_values("*", "cms_phonetag_list", "id='" . $id . "'", "");
		return view('App\Modules\PhoneTagging\Views\Phone_tagging_approval', $data);
	}
	function save_phone_tagging_approval(){
		$data['id'] = $this->input->getPost('id');
		$data['status'] = $this->input->getPost('status');
		$data['reason'] = $this->input->getPost('reason');
		$data['remarks'] = $this->input->getPost('reason');
		$data['approved_by'] = session()->get('USER_ID');
		$data['approval_time'] = date('Y-m-d H:i:s');
		$data['created_by'] = session()->get('USER_ID');
		$data['created_time'] = date('Y-m-d H:i:s');
		$return = $this->Phone_tagging_list_model->save_phone_tagging($data);
		if ($return) {
			$cache = session()->get('USER_ID').'_phone_tagging_list';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to save aproved', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

}