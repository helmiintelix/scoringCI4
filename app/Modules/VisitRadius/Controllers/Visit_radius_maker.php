<?php 
namespace App\Modules\VisitRadius\Controllers;
// namespace App\Modules\VisitRadius\Controllers;
use App\Modules\VisitRadius\Models\Visit_radius_temp_model;
use CodeIgniter\Cookie\Cookie;
// use App\Modules\VisitRadius\models\User_and_group_model;
use App\Modules\VisitRadius\Models\Visit_radius_maker_model;


class Visit_radius_maker extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Visit_radius_maker_model = new Visit_radius_maker_model();
		$this->Visit_radius_temp_model = new Visit_radius_temp_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\VisitRadius\Views\Visit_radius_maker_view', $data);
	}
	function visit_radius_all_list(){
		$cache = session()->get('USER_ID').'_visit_radius_all_list';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Visit_radius_maker_model->get_visit_radius_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function visit_radius_add_form(){
		$cache = session()->get('USER_ID').'_user_add_form';

		$data["list_fc"] = array("" => "[select data]")+$this->Common_model->get_record_list("id as value, concat(id,' - ',name) AS item", "cc_user", "group_id='AGENT_FIELD_COLLECTOR' and is_active='1'", "id")	;
		// $data["spv_list"] = array("" => "[select data]") +  $this->Common_model->get_record_list("c.id AS value, c.name AS item", "cc_user c join cc_user_group g on (c.group_id=g.id and g.level_group in('TEAM_LEADER','SUPERVISOR','MANAGER','ADMIN'))", "c.is_active = '1'", "c.id");

		return view('\App\Modules\VisitRadius\Views\Visit_radius_maker_add_form_view', $data);

	}
	function save_visit_radius_add(){
		$is_exist = $this->Visit_radius_maker_model->isExist($this->input->getPost('txt-id'));
		if (!$is_exist) {
			$data['id'] = $this->input->getPost('txt-id');
			$data['label'] = $this->input->getPost('txt-label');
			$data['fc_name'] = $this->input->getPost('txt-fc-name');
			$data['radius'] = $this->input->getPost('txt-radius');
			$data['is_active'] = $this->input->getPost('opt-active-flag');
			$data['created_by'] = session()->get('USER_ID');
			$data['created_date'] = date('Y-m-d');
			$return = $this->Visit_radius_maker_model->save_visit_radius_add($data);
			// dd($is_exist);
			if($return){
				$cache = session()->get('USER_ID').'_visit_radius_list_temp';
				$this->cache->delete($cache);

				// Update cache dengan data terbaru
				$data = $this->Visit_radius_temp_model->get_visit_radius_list_temp();
				$this->cache->save($cache, json_encode($data), env('TIMECACHE_1'));
				
				$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
			
		} else{
			$rs = array('success' => false, 'message' => 'User ID Already Registered. Please insert another ID.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function visit_radius_edit_form(){
		
		$data['id_data'] = $this->input->getGet('id');
		$data['visit_radius_maker'] = $this->Common_model->get_record_values("id, label, fc_name, radius, is_active, created_by, created_date", "cms_visit_radius", "id = '" . $this->db->escapeString($data["id_data"]) . "'");
		$data["list_fc"] = array("" => "[select data]")+$this->Common_model->get_record_list("id as value, concat(id,' - ',name) AS item", "cc_user", "group_id='AGENT_FIELD_COLLECTOR' and is_active='1'", "id")	;

		// dd($data);
		if (empty($data['visit_radius_maker'])) {
			echo "NOT FOUND";
		} else {
			return view('\App\Modules\VisitRadius\Views\Visit_radius_maker_edit_form_view', $data);
		}

	}
	function save_visit_radius_edit(){
		$data['id'] = $this->input->getPost('txt-id');
		$data['label'] = $this->input->getPost('txt-label');
		$data['fc_name'] = $this->input->getPost('txt-fc-name');
		$data['radius'] = $this->input->getPost('txt-radius');
		$data['is_active'] = $this->input->getPost('opt-active-flag');
		$data['created_by'] = session()->get('USER_ID');
		$data['created_date'] = date('Y-m-d');
		$return = $this->Visit_radius_maker_model->save_visit_radius_add($data);
		// dd($is_exist);
		if($return){
			$cache = session()->get('USER_ID').'_visit_radius_list_temp';
			$this->cache->delete($cache);

			// Update cache dengan data terbaru
			$data = $this->Visit_radius_temp_model->get_visit_radius_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1'));
			
			$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
	}

}