<?php 
namespace App\Modules\SetupDeviationApproval\Controllers;
use App\Modules\SetupDeviationApproval\Models\Setup_deviation_approval_maker_model;
use CodeIgniter\Cookie\Cookie;

class Setup_deviation_approval_maker extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_deviation_approval_maker_model = new Setup_deviation_approval_maker_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\SetupDeviationApproval\Views\Deviation_approval_list_view', $data);
	}
	function deviation_approval_list(){
		$cache = session()->get('USER_ID').'_deviation_approval_list';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_deviation_approval_maker_model->get_deviation_approval_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function deviation_approval_add_form() {
		$results = array();
		$builder = $this->db->table('cc_user a');
		$builder->select('a.id AS value, a.name AS item');
		$builder->join('cc_user_group b', 'a.group_id = b.id');
		$builder->where('a.is_active', '1');
		$builder->groupStart()
				->where('b.level_group', 'ADMIN')
				->orWhere('b.level_group', 'MANAGER')
				->groupEnd();
		$builder->orderBy('a.id');
		$query = $builder->get();
		if ($query->getNumRows())
		{
			foreach ($query->getResult() as $row)
			{
				$results[$row->value] = $row->item;
			}
		}
		$data["deviation_reference"] = $this->Common_model->get_record_list("deviation_id AS value, deviation_name AS item", "cms_deviation_reference", "is_active = '1'", "id");
		$data["userlist"] = array("" => "--PILIH--") +  $results;
		return view('App\Modules\SetupDeviationApproval\Views\Deviation_approval_add_form_view', $data);
	}
	function save_deviation_approval_add(){
		$dev_app_id = $this->input->getPost('txt-deviation_approval-dev_app_id');
		$is_exist = $this->Setup_deviation_approval_maker_model->isExistdeviation_approvalId($dev_app_id);
		if (!$is_exist) {
			$dev_ref_id = $this->input->getPost("opt-deviation_approval-dev_ref_id") == null ? '' : implode(",", $this->input->getPost("opt-deviation_approval-dev_ref_id"));
			$data["id"] = UUID();
			$data["dev_app_id"] = $dev_app_id;
			$data["dev_app_name"] = $this->input->getPost("txt-deviation_approval-dev_app_name");
			$data["dev_ref_id"] = $dev_ref_id;
			$data["app_1_user_1"] = $this->input->getPost("opt-deviation_approval-app_1_user_1");
			$data["app_1_user_2"] = $this->input->getPost("opt-deviation_approval-app_1_user_2");
			$data["app_1_user_3"] = $this->input->getPost("opt-deviation_approval-app_1_user_3");
			$data["app_2_user_1"] = $this->input->getPost("opt-deviation_approval-app_2_user_1");
			$data["app_2_user_2"] = $this->input->getPost("opt-deviation_approval-app_2_user_2");
			$data["app_2_user_3"] = $this->input->getPost("opt-deviation_approval-app_2_user_3");
			$data["app_3_user_1"] = $this->input->getPost("opt-deviation_approval-app_3_user_1");
			$data["app_3_user_2"] = $this->input->getPost("opt-deviation_approval-app_3_user_2");
			$data["app_3_user_3"] = $this->input->getPost("opt-deviation_approval-app_3_user_3");
			$data["app_4_user_1"] = $this->input->getPost("opt-deviation_approval-app_4_user_1");
			$data["app_4_user_2"] = $this->input->getPost("opt-deviation_approval-app_4_user_2");
			$data["app_4_user_3"] = $this->input->getPost("opt-deviation_approval-app_4_user_3");
			$data["is_active"]	= '1';
			$data['flag'] = '1';
			$data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			$return = $this->Setup_deviation_approval_maker_model->save_deviation_approval_add($data);
			if ($return) {
				$this->Common_model->data_logging('DEVIATION APPROVAL', $data["dev_app_name"], 'SUCCESS', 'Set ' . $data["dev_app_id"] . ' = ' . $data["dev_app_name"]);
				$cache = session()->get('USER_ID').'_deviation_approval_list_temp';
				$this->cache->delete($cache);
				$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$this->Common_model->data_logging('DEVIATION APPROVAL', $data["dev_app_name"], 'FAILED', 'Set ' . $data["dev_app_id"] . ' = ' . $data["dev_app_name"]);
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
		} else {
			$rs = array('success' => false, 'message' => 'DEVIATION APPROVAL ID Already Registered. Please insert another ID.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
		
	}
	function deviation_approvalEditForm(){
		$id= $this->input->getGet('id');
		$results = array();
		$builder = $this->db->table('cc_user a');
		$builder->select('a.id AS value, a.name AS item');
		$builder->join('cc_user_group b', 'a.group_id = b.id');
		$builder->where('a.is_active', '1');
		$builder->groupStart()
				->where('b.level_group', 'ADMIN')
				->orWhere('b.level_group', 'MANAGER')
				->groupEnd();
		$builder->orderBy('a.id');
		$query = $builder->get();
		if ($query->getNumRows())
		{
			foreach ($query->getResult() as $row)
			{
				$results[$row->value] = $row->item;
			}
		}
		$data["deviation_reference"] = $this->Common_model->get_record_list("deviation_id AS value, deviation_name AS item", "cms_deviation_reference", "is_active = '1'", "id");
		$data["userlist"] = array("" => "--PILIH--") +  $results;
		$data["data"] = $this->Common_model->get_record_values("*", "cms_deviation_approval", "id = '" . $id . "'");
		$data['dev_ref'] = explode(',', $data["data"]['dev_ref_id']);
		return view('App\Modules\SetupDeviationApproval\Views\Deviation_approval_edit_form_view', $data);
	}
	function save_deviation_approval_edit(){
		$id = $this->input->getPost('txt-id');
		$is_exist = $this->Setup_deviation_approval_maker_model->isExistDeviationApprovalId($id);
		if (!$is_exist) {
			$dev_ref_id = $this->input->getPost("opt-deviation_approval-dev_ref_id") == null ? '' : implode(",", $this->input->getPost("opt-deviation_approval-dev_ref_id"));
			$data["id"] = $id;
			$data["dev_app_id"] = $this->input->getPost("txt-deviation_approval-dev_app_id");
			$data["dev_app_name"] = $this->input->getPost("txt-deviation_approval-dev_app_name");
			$data["dev_ref_id"] = $dev_ref_id;
			$data["app_1_user_1"] = $this->input->getPost("opt-deviation_approval-app_1_user_1");
			$data["app_1_user_2"] = $this->input->getPost("opt-deviation_approval-app_1_user_2");
			$data["app_1_user_3"] = $this->input->getPost("opt-deviation_approval-app_1_user_3");
			$data["app_2_user_1"] = $this->input->getPost("opt-deviation_approval-app_2_user_1");
			$data["app_2_user_2"] = $this->input->getPost("opt-deviation_approval-app_2_user_2");
			$data["app_2_user_3"] = $this->input->getPost("opt-deviation_approval-app_2_user_3");
			$data["app_3_user_1"] = $this->input->getPost("opt-deviation_approval-app_3_user_1");
			$data["app_3_user_2"] = $this->input->getPost("opt-deviation_approval-app_3_user_2");
			$data["app_3_user_3"] = $this->input->getPost("opt-deviation_approval-app_3_user_3");
			$data["app_4_user_1"] = $this->input->getPost("opt-deviation_approval-app_4_user_1");
			$data["app_4_user_2"] = $this->input->getPost("opt-deviation_approval-app_4_user_2");
			$data["app_4_user_3"] = $this->input->getPost("opt-deviation_approval-app_4_user_3");
			$data["is_active"]	= '1';
			$data['flag'] = '2';
			$data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			$return = $this->Setup_deviation_approval_maker_model->save_deviation_approval_edit($data);
			if ($return) {
				$cache = session()->get('USER_ID').'_deviation_approval_list_temp';
				$this->cache->delete($cache);
				$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
		} else {
			$rs = array('success' => false, 'message' => 'DEVIATION APPROVAL ID Already Registered. Please insert another ID.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function delete_deviation_approval(){
		$id= $this->input->getPost('dev_app_id');
		$return = $this->Setup_deviation_approval_maker_model->delete_deviation_approval($id);
		if ($return) {
			$newCsrfToken = csrf_hash();
			$cache = session()->get('USER_ID').'_deviation_approval_list';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to delete data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON(array_merge($rs, ['newCsrfToken' => $newCsrfToken]));
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	
}