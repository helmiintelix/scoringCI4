<?php 
namespace App\Modules\MyAccount\Controllers;
use App\Modules\MyAccount\Models\My_account_model;
use CodeIgniter\Cookie\Cookie;

class My_account extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->My_account_model = new My_account_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		$builder = $this->db->table('acs_dialing_mode_call_status a');
		if (session()->get('LEVEL_GROUP') == 'TEAM_LEADER') {
			$builder->select('a.dialing_mode_id , d.team_id , d.team_name, a.class_id');
			$builder->join('cms_classification b', 'b.classification_id=a.class_id');
			$builder->join('acs_class_work_assignment c', 'c.class_mst_id=b.classification_id');
			$builder->join('cms_team d', 'd.team_id=c.outbound_team');
			$builder->like('d.team_leader', session()->get('USER_ID'), 'both');
		} else if (session()->get('LEVEL_GROUP') == 'TELECOLL') {
			$builder->select('a.dialing_mode_id , null team_id , null team_name, a.class_id');
			$builder->join('cms_classification b', 'b.classification_id=a.class_id');
			$builder->like('b.assigned_agent', session()->get('USER_ID'), 'both');
		} else {
			$builder->select('a.dialing_mode_id , b.class_mst_id team_id, a.class_id');
			$builder->join('acs_class_work_assignment b', 'a.class_id=b.class_mst_id');
		}
		$data['team_id'] = @$builder->get()->getResultArray()[0]['team_id'];

		$builder2 = $this->db->table('acs_dialing_mode_call_status a');
		if ($data['team_id'] == '') {
			$builder2->select('a.dialing_mode_id , d.team team_id , e.team_name team_name, a.class_id');
			$builder2->join('cms_classification b', 'b.classification_id=a.class_id');
			$builder2->join('acs_class_work_assignment c', 'c.class_mst_id=b.classification_id');
			$builder2->join('acs_agent_assignment d', 'd.team=c.outbound_team');
			$builder2->join('cms_team e', 'e.team_id=d.team');
			$builder2->where('d.user_id', session()->get('USER_ID'));
		}
		$data['team_id'] = @$builder2->get()->getResultArray()[0]['team_id'];
		$data['LEVEL_GROUP'] = session()->get('LEVEL_GROUP');

		$builder3 = $this->db->table('acs_dialing_mode_call_status a');
		$builder3->select('a.dialing_mode_id , d.team_id , d.team_name, a.class_id');
		$builder3->join('cms_classification b', 'b.classification_id=a.class_id');
		$builder3->join('acs_class_work_assignment c', 'c.class_mst_id=b.classification_id');
		$builder3->join('cms_team d', 'd.team_id=c.outbound_team');
		$rs = $builder3->get()->getResultArray();
		$list = array();
		foreach ($rs as $key => $value) {
			$list[$value['team_id']] = $value['team_name'];
		}

		$dialing_mode_list = array();
		foreach ($rs as $key => $value) {
			$dialing_mode_list[$value['team_id']] = $value['dialing_mode_id'];
		}
		$list = array();
		foreach ($rs as $key => $value) {
			$list[$value['team_id']] = $value['team_name'];
		}
		$data['list_team'] =array(''=>'[SELECT DATA]')+$list;
		$data['dialing_mode_list'] = json_encode($dialing_mode_list);

		$data['dialing_mode'] = @$builder3->get()->getResultArray()[0]['dialing_mode_id'];
		$data['class_id'] = @$builder3->get()->getResultArray()[0]['class_id'];
		return view('\App\Modules\MyAccount\Views\Assigned_account_view', $data);
	}
	function get_assigned_account(){
		$cache = session()->get('USER_ID').'_assigned_account';
		// if($this->cache->get($cache)){  
		// 	$data = json_decode($this->cache->get($cache));
		// 	$rs = array('success' => true, 'message' => '', 'data' => $data);
		// }else{
			$data = $this->My_account_model->get_assigned_account();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		// }
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function zipcode_assign_mappingAddForm() {
		$data['id'] = $this->input->getGet('id');
		$builder = $this->db->table('acs_cms_product');
		$builder->select('productcode');
		$data['product'] = $builder->get()->getResultArray();
		$data['branch_area_tagih'] = $this->Common_model->get_record_list("area_tagih_name AS value, area_tagih_name AS item", "cms_area_tagih", "is_active=1", "area_tagih_id");
		return view('App\Modules\MyAccount\Views\Assign_area_add_view', $data);
	}
	function save_zipcode_area_mapping_assign(){
		$is_exist = $this->My_account_model->isExistzipcode_area_mappingId($this->input->getPost('txt-zipcode_area_assign-sub_area_id'));
		print_r($is_exist);
		if (!$is_exist) {
			$product=$this->input->getPost("opt-zipcode_area_assign-product")==null?'':implode(",",$this->input->getPost("opt-zipcode_area_assign-product"));
			$data["id"] = UUID();
			$data["sub_area_id"] = $this->input->getPost("txt-zipcode_area_assign-sub_area_id");
			$data["sub_area_name"] = $this->input->getPost("txt-zipcode_area_assign-sub_area_name");
			$data["area_tagih"] = $this->input->getPost("opt-zipcode_area_assign-area_tagih");
			$data['product'] = $product;
			$data["zip_code"] = $this->input->getPost("txt-id");
			$data['is_active'] = '1';
			$data['flag'] = '1';
			$data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			$return = $this->My_account_model->save_zipcode_area_mapping_assign($data);
			if ($return) {
				$this->Common_model->data_logging('zipcode_area_mapping', $data["sub_area_name"], 'SUCCESS', 'Set '.$data["sub_area_id"].' = '.$data["sub_area_name"]);
				$cache = session()->get('USER_ID').'_get_assigned_account';
				$this->cache->delete($cache);
				$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$this->Common_model->data_logging('zipcode_area_mapping', $data["sub_area_name"], 'FAILED', 'Set '.$data["sub_area_id"].' = '.$data["sub_area_name"]);
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
		} else {
			$rs = array('success' => false, 'message' => 'zipcode_area_mapping ID Already Registered. Please insert another ID.', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
	}

}