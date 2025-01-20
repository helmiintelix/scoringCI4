<?php 
namespace App\Modules\SetupFieldcollAreaMapping\Controllers;
use App\Modules\SetupFieldcollAreaMapping\Models\Setup_fieldcoll_area_mapping_maker_model;
use App\Modules\SetupFieldcollAreaMapping\Models\Setup_fieldcoll_area_mapping_temp_model;
use CodeIgniter\Cookie\Cookie;

class Setup_fieldcoll_area_mapping_maker extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_fieldcoll_area_mapping_maker_model = new Setup_fieldcoll_area_mapping_maker_model();
		$this->Setup_fieldcoll_area_mapping_temp_model = new Setup_fieldcoll_area_mapping_temp_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\SetupFieldcollAreaMapping\Views\Fieldcoll_area_mapping_list_view', $data);
	}
	function fieldcoll_area_mapping_list(){
		$cache = session()->get('USER_ID').'_fieldcoll_area_mapping_list';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_fieldcoll_area_mapping_maker_model->get_fieldcoll_area_mapping_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function fieldcoll_area_mapping_add_form() {
		$data["collector"] = array("" => "SELECT COLLECTOR") +  $this->Common_model->get_record_list("id AS value, name AS item", "cc_user", "is_active = '1' AND group_id='AGENT_FIELD_COLLECTOR'", "item");
		$data["sub_area"] = $this->Common_model->get_record_list("sub_area_id AS value, sub_area_name AS item", "cms_zipcode_area_mapping", "is_active = '1'", "item");
		return view('App\Modules\SetupFieldcollAreaMapping\Views\Fieldcoll_area_mapping_add_form_view', $data);
	}
	function save_fieldcoll_area_mapping_add(){
		$sub_area_id = $this->input->getPost("txt-sub_area-name") == null ? '' : implode(",", $this->input->getPost("txt-sub_area-name"));
		$data['id'] = uuid();
		$data['collector_id'] = $this->input->getPost('txt-collector-name');
		$data['sub_area_id'] = $sub_area_id;
		$data['is_active'] = $this->input->getPost("opt-active-flag");
		$data['flag'] = '1';
		$data['created_by'] = session()->get('USER_ID');
		$data['created_time'] = date('Y-m-d H:i:s');
		// $data['flag'] = $this->input->getPost('txt-id');
		$return = $this->Setup_fieldcoll_area_mapping_maker_model->save_fieldcoll_area_mapping_add($data);
		if ($return) {
			$cache = session()->get('USER_ID').'_fieldcoll_area_mapping_list_temp';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function fieldcoll_area_mapping_edit_form(){
		$id= $this->input->getGet('id');
		$data["collector"] = array("" => "SELECT COLLECTOR") +  $this->Common_model->get_record_list("id AS value, name AS item", "cc_user", "is_active = '1' AND group_id='AGENT_FIELD_COLLECTOR'", "item");
		$data["sub_area"] = $this->Common_model->get_record_list("sub_area_id AS value, sub_area_name AS item", "cms_zipcode_area_mapping", "is_active = '1'", "item");
		$data["data"] = $this->Common_model->get_record_values("*", "cms_fieldcoll_area_mapping", "id = '" . $id. "'");
		$data['sub_area_id'] = explode(',', $data["data"]['sub_area_id']);
		return view('App\Modules\SetupFieldcollAreaMapping\Views\Fieldcoll_area_mapping_edit_form_view', $data);
	}
	function save_fieldcoll_area_mapping_edit(){
		$is_exist = $this->Setup_fieldcoll_area_mapping_maker_model->isExistfieldcoll_area_mappingId($this->input->getPost('id'));
		if (!$is_exist) {
			$sub_area_id = $this->input->getPost("txt-sub_area-name") == null ? '' : implode(",", $this->input->getPost("txt-sub_area-name"));
			$data['id'] = $this->input->getPost('txt-id');
			$data['collector_id'] = $this->input->getPost('txt-collector-name');
			$data['sub_area_id'] = $sub_area_id;
			$data['is_active'] = $this->input->getPost("opt-active-flag");
			$data['flag'] = '2';
			$data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			$return = $this->Setup_fieldcoll_area_mapping_maker_model->save_fieldcoll_area_mapping_edit($data);
			if ($return) {
				$cache = session()->get('USER_ID').'_fieldcoll_area_mapping_list_temp';
				$this->cache->delete($cache);
				$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
		} else {
			$rs = array('success' => false, 'message' => 'Please approve/reject the data first', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

}