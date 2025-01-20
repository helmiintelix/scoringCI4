<?php 
namespace App\Modules\PhoneTagging\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\PhoneTagging\Models\Phone_tagging_ref_model;


class Phone_tagging_ref extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Phone_tagging_ref_model = new Phone_tagging_ref_model;
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\PhoneTagging\Views\Setup_phone_tagging_ref_view', $data);
	}
	function get_phone_tagging_ref(){
		$cache = session()->get('USER_ID').'_phone_tagging_ref';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Phone_tagging_ref_model->get_phone_tagging_ref();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function phone_tagging_ref_add_form(){
		return view('\App\Modules\PhoneTagging\Views\Phone_tagging_ref_add_view');
	}
	function save_phone_tagging_add(){
		$data["id"]				= $this->input->getpost('id');
		$data["status"]			= $this->input->getpost('status');
		$data["description"]			= $this->input->getpost('reason'); 
		// $data["type"]			= $this->input->getpost('type');
		$data["created_by"]		= session()->get('USER_ID');
		$data["created_time"]	= date('Y-m-d h:i:S');
		$data["updated_by"]		= session()->get('USER_ID');
		$data["updated_time"]	= date('Y-m-d h:i:S');
		$return = $this->Phone_tagging_ref_model->save_phone_tagging_ref($data);
		if ($return) {
			$cache = session()->get('USER_ID').'_phone_tagging_ref';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to add data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}

	}
	function phone_tagging_ref_edit_form(){
		$id = $this->input->getGet('id');
		$data['data'] = $this->Common_model->get_record_values("*", "cms_phonetag_ref", "id='" . $id . "'", "");
		return view('\App\Modules\PhoneTagging\Views\Phone_tagging_ref_edit_view', $data);

	}
	function save_phone_tagging_edit(){
		$data["id"]				= $this->input->getpost('id');
		$data["status"]			= $this->input->getpost('status');
		$data["desc"]			= $this->input->getpost('reason');
		// $data["type"]			= $this->input->getpost('type');
		$data["updated_by"]		= session()->get('USER_ID');
		$data["updated_time"]	= date('Y-m-d h:i:S');
		$return = $this->Phone_tagging_ref_model->save_phone_tagging_ref_edit($data);
		if ($return) {
			$cache = session()->get('USER_ID').'_phone_tagging_ref';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to update data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

}