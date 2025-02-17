<?php 
namespace App\Modules\FieldcollAndAgencyClassAssignment\Controllers;
use App\Modules\FieldcollAndAgencyClassAssignment\Models\Fieldcoll_and_agency_class_assignment_model;
use CodeIgniter\Cookie\Cookie;

class Fieldcoll_and_agency_class_assignment extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Fieldcoll_and_agency_class_assignment_model = new Fieldcoll_and_agency_class_assignment_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\FieldcollAndAgencyClassAssignment\Views\Assignment_class_agent_view', $data);
	}
	function get_classification_list_assignment(){
		$cache = session()->get('USER_ID').'_classification_list_assignment';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Fieldcoll_and_agency_class_assignment_model->get_classification_list_assignment();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function class_agent_assignment_form() {
		$class_id = $this->input->getGet('id');
		$data["class_id"] = $class_id;
		$data["assigned_agent"] = $this->Common_model->get_record_values("assigned_agent,assignment_weight,distribution_method", "cms_classification", "classification_id = '".$class_id."'");

		$data["all_assigned_agent"] = $this->Common_model->get_record_values("assigned_agent", "cms_classification", "classification_id = '".$class_id."'");

		$data["agency_list"]	= $this->Common_model->get_record_list("agency_id AS value, agency_name AS item", "cms_agency", "flag_tmp = '1'", "agency_name");

		$data["team_list"] = $this->Common_model->get_record_list("b.team_id as value, b.team_name as item","cms_team b","flag_team='2'","b.team_id");

		$tmp = explode(",",$data["assigned_agent"]["assigned_agent"] ?? '');
		if(count($tmp)>0){
			if(empty($tmp[0])){
				$shift = array_shift($tmp);
				$data["assigned_agent"]["assigned_agent"] = implode(",",$tmp);
			}
  		}
		$ag_list = str_replace("|","','",$data["assigned_agent"]["assigned_agent"]);
		$ag_list = trim($ag_list);
		$ag_list = str_replace("'", "", $ag_list);
		$ag_list_array = explode(',', $ag_list);

		$builder = $this->db->table('cc_user');
		$builder->select('id, name, nik, group_id, "" as weight');
		$builder->whereIn('id', $ag_list_array);
		$query = $builder->get()->getResultArray();
		
		$builder = $this->db->table('cms_agency');
		$builder->select('agency_id id,agency_name name, "" as nik, "" as group_id, "" as weight');
		$builder->whereIn('agency_id', $ag_list_array);
		$query2 = $builder->get()->getResultArray();

		$ar_ag = explode(",",$data["assigned_agent"]["assigned_agent"]);
		$ar_weight = explode("|",$data["assigned_agent"]["assignment_weight"]);
		$ar_ag_weight = array();

		foreach($ar_ag as $key=>$val){
			$ar_ag_weight[$val] = @$ar_weight[$key]; 
		}

		$res = array_merge($query, $query2);
		$weight = 0;
		foreach($res as $key=>$val){
			if(empty($ar_ag_weight[$res[$key]["id"]])){
				$res[$key]["weight"] =$weight;	
			}else{
				$res[$key]["weight"] =@$ar_ag_weight[$res[$key]["id"]]; 
			}
		}
		$data["assigned_agent_detail"] = json_encode($res);
		// var_dump($data);die;

		return view('App\Modules\FieldcollAndAgencyClassAssignment\Views\Assignment_class_agent_form_view', $data);
	}
	function getCollectorListFieldColl(){
		$class_id = $this->input->getGet('id');
		$all_assigned_agent = $this->Common_model->get_record_values("assigned_agent", "cms_classification", "classification_id = '" . $class_id . "'");

		$data = $this->Common_model->get_record_list("a.id AS value, concat(a.id,' - ',a.name) AS item", "cc_user a join cc_user_group b on (a.group_id = b.id and b.type_collection='FieldColl' )", "group_id = 'AGENT_FIELD_COLLECTOR' and a.id not in('" . str_replace(",", "','", $all_assigned_agent["assigned_agent"]) . "') and a.is_active='1'", "a.name");
		//$data=$this->Common_model->get_record_list("a.id AS value, concat(a.id,' - ',a.name) AS item", "cc_user a join cc_user_group b on (a.group_id = b.id and b.type_collection='FieldColl' )", " a.id not in('".str_replace(",","','",$all_assigned_agent["assigned_agent"]) ."') and a.is_active='1'", "a.name");
		$result = [];
		foreach ($data as $key => $row) {
			$result[] = [
				'value' => $key,
				'item' => $row
			];
		}
		
		return $this->response->setStatusCode(200)->setJSON($result);
	}
	function get_agent_detail(){
		$agency_id= $this->input->getGet('agent_id');
		$agency_data = $this->Common_model->get_record_values("id as id,name as name,  group_id", "cc_user", "id = '".$agency_id."'");
		
		return $this->response->setStatusCode(200)->setJSON($agency_data);
		
	}
	function get_list_agent(){
		$team_id= $this->input->getGet('team_id');
		$builder = $this->db->table('cms_team');
		$builder->select('agent_list');
		$builder->where('team_id', $team_id);
		$rows = $builder->get()->getResultArray();
		$agent_list=str_replace('|',"','",$rows[0]['agent_list']??'');
		$agent_list = str_replace("'", "", $agent_list);
		$agent_list_array = explode(',', $agent_list);

		$builder2 = $this->db->table('cc_user e');
		$builder2->select("e.id as value, concat(e.id ,' - ', e.name) as item");
		$builder2->whereIn('e.id', $agent_list_array);
		$res1 = $builder2->get()->getResultArray();
		$agent=array();
		foreach($res1 as $key=>$value){
			$agent[$value['value']] = $value['item'];
		}
		return $this->response->setStatusCode(200)->setJSON($agent);
		
	}
	function class_agent_assignment_form_save_waiting_approval(){
		$data['class_id'] = $this->input->getPost('classification_id');
		$data['assigned_agent'] = $this->input->getPost('param_list');
		$data['dm'] = $this->input->getPost('opt-ac-agent');
		$data['assignment_weight'] = $this->input->getPost('weight_list');
		$data['assigned_time'] = date('Y-m-d H:i:s');
		$data['assigned_by'] = session()->get('USER_ID');
		$data['created_by'] = session()->get('USER_ID');
		$data['created_time'] = date('Y-m-d H:i:s');

		$return = $this->Fieldcoll_and_agency_class_assignment_model->class_agent_assignment_form_save_waiting_approval($data);
		if ($return) {
			$cache = session()->get('USER_ID').'_classification_list_assignment';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Class Assigned to '.$data['assigned_agent'], 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function classification_test_form(){
		$data['classification'] = $this->input->getPost('classification');
		$classification_id = $this->input->getGet('id');
		$field_list = '[
			{ "id":"CM_CUSTOMER_NMBR", "label" :"Account Number", "type" : "string"},
			{ "id":"AGENT_ID", "label" :"CURRENT AGENT", "type" : "string"},
			{ "id":"CLASS", "label" :"CURRENT CLASS", "type" : "string"},
			{ "id":"BILL_BAL", "label" :"Bill Balance", "type" : "integer"},
			{ "id":"CM_BUCKET", "label" :"BUCKET", "type" : "string"},
			{ "id":"CM_CARD_NMBR", "label" :"Loan Number", "type" : "string"},
			{ "id":"CM_DTE_PYMT_DUE", "label" :"Due Date", "type" : "date"},
			{ "id":"DPD", "label" :"DPD", "type" : "integer"},
			{ "id":"CM_CARD_EXPIR_DTE", "label" :"Original Maturity Date", "type" : "date"},
			{ "id":"CM_DTE_LST_PYMT", "label" :"Last Payment Date", "type" : "date"},
			{ "id":"CM_TOT_BALANCE", "label" :"Baki Debet", "type" : "integer"},
			{ "id":"CM_CYCLE", "label" :"Cycle", "type" : "string"},
			{ "id":"AGENT_ID", "label" :"User ID", "type" : "string"},
			{ "id":"CM_CRLIMIT", "label" :"Limit", "type" : "integer"},
			{ "id":"CM_TENOR", "label" :"Tenor", "type" : "integer"},
			{ "id":"CM_INSTALLMENT_AMOUNT", "label" :"Installment Amount", "type" : "integer"},
			{ "id":"CM_OS_PRINCIPLE", "label" :"Outstanding Principle", "type" : "integer"},
			{ "id":"CM_COLLECTIBILITY", "label" :"Collectability", "type" : "string"},
			{ "id":"CM_CHGOFF_STATUS_FLAG", "label" :"Charge Off Status", "type" : "string"},
			{ "id":"CM_INSTALLMENT_NO", "label" :"Installment No.", "type" : "string"},
			{ "id":"CR_OCCUPATION", "label" :"Occupation", "type" : "string"},
			{ "id":"CM_DOMICILE_BRANCH", "label" :"Customer Branch ID", "type" : "string"}
			]
			';
		$field_array = json_decode($field_list, true);
		$data["str_header"] = "";
		$data["str_field"] = "";
		$i = 0;
		foreach ($field_array as $field) {
			//$arr_header[] = $field["label"];
			if ($i == 0) {
				$data["str_header"] .= $field["label"];
			} else {
				$data["str_header"] .= "','" . $field["label"];
			}

			$data["str_field"] .= "{name:'" . $field["id"] . "', index:'" . $field["id"] . "'," . ($field["type"] == "integer" ? 'formatter:"integer",align: "right",' : '')   . " width:100},";
			$i++;
		}
		$data["classification_id"] = $classification_id;

		return view('App\Modules\FieldcollAndAgencyClassAssignment\Views\Classification_test_form_view', $data);

	}
	function get_classification_test(){
		$classification_id = $this->input->getGet('id');
		$cache = session()->get('USER_ID').'_classification_test';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Fieldcoll_and_agency_class_assignment_model->get_classification_test_current($classification_id);
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
}