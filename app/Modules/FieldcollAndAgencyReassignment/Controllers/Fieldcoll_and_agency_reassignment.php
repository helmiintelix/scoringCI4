<?php 
namespace App\Modules\FieldcollAndAgencyReassignment\Controllers;
use App\Modules\FieldcollAndAgencyReassignment\Models\Fieldcoll_and_agency_reassignment_model;
use CodeIgniter\Cookie\Cookie;

class Fieldcoll_and_agency_reassignment extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Fieldcoll_and_agency_reassignment_model = new Fieldcoll_and_agency_reassignment_model();
	}

	function index(){
		$data = $this->get_search_list();
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\FieldcollAndAgencyReassignment\Views\Reassignment_list_view', $data);
	}
	function get_search_list(){
		switch(session()->get('GROUP_LEVEL')){
			case "SUPERVISOR":
			case "COORDINATOR":
			case "AGENT":
			case "FC":
				$data["group_kcu"] = array(""=>"--PILIH--")+ $this->Common_model->get_record_list("kcu_id AS value, concat(kcu_id,' - ',kcu_name) AS item", "cms_kcu", "flag = '1' AND flag_tmp = '1' and bisnis_unit='".session()->get("PRODUK_GROUP") ."' and kcu_id='".session()->get('KCU')."'", "kcu_name");			
				$data["kcu"] = array(""=>"--PILIH--");
				$data["area"] = array(""=>"--PILIH--");
				$data['petugas'] = array(""=>"--PILIH--");
			break;
			default:
				$data["group_kcu"] = array(""=>"--PILIH--")+ $this->Common_model->get_record_list("kcu_id AS value, concat(kcu_id,' - ',kcu_name) AS item", "cms_kcu", "flag = '1' AND flag_tmp = '1' and bisnis_unit='".session()->get("PRODUK_GROUP") ."'", "kcu_name" );			
				$data["kcu"] = array(""=>"--PILIH--");
				$data["area"] = array(""=>"--PILIH--");
				$data['petugas'] = array(""=>"--PILIH--");
			break;
		}

		return $data;		
	}
	function get_reassignment_list(){
		$cache = session()->get('USER_ID').'_reassignment_list';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Fieldcoll_and_agency_reassignment_model->get_reassignment_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function account_reassignment_form() {
		$data['cd_customers'] = $this->input->getGet('customer_id');
		$cd_customers = str_replace("|", "' ,'", $data["cd_customers"]);
		$cd_customers_arr = explode(",", $cd_customers);
		$arr_data = array();
		$builder = $this->db->table('cpcrd_new');;
		$builder->select('CM_CARD_NMBR AS value, CM_CARD_NMBR AS item');
		$builder->whereIn('CM_CARD_NMBR', $cd_customers_arr);
		$builder->orderBy('CM_CARD_NMBR');
		$query = $builder->get();
		if ($query->getNumRows()) {
			foreach ($query->getResult() as $row)
			{
				$arr_data[$row->value] = $row->item;
			}
		}

		$data["cd_customer_list"]	= $arr_data;
		$data["agency_list"]	= $this->Common_model->get_record_list("agency_id AS value, concat(agency_id,' - ',agency_name,'  (',count(*),' account assigned)') AS item", "cms_agency a left join cms_assignment on (assigned_fc = agency_id and collector_type='AGENCY') group by agency_id", "", "agency_name ");
		$data["team_list"] = $this->Common_model->get_record_list("b.team_leader as value, b.team_name as item","cms_team b","","b.team_id");
		$data["collector_field_list"]=$this->Common_model->get_record_list("a.id AS value, concat(a.id,' - ',a.name) AS item", "cc_user a join cc_user_group b on (a.group_id = b.id and b.type_collection='FieldColl' )", "a.is_active='1'", "a.name");
		// dd($data);
		return view('App\Modules\FieldcollAndAgencyReassignment\Views\Account_reassignment_form_view', $data);
	}
	function reassignment_request(){
		$customers		= $this->input->getPost('txt-cd-customers');
		$data['customers'] = str_replace(",", "|", $customers);
		$data['group_assignment']	= $this->input->getPost("optGroupAssignment");
		$data['id_coll']			= $this->input->getPost("opt-id-coll");
		$data['id_field_coll']		= $this->input->getPost("opt_id_field_coll");
		$data['id_agency']			= $this->input->getPost("opt_id_agency");
		$data['assignment_type']		= $this->input->getPost("assignmentType");
		$data['to_date']				= $this->input->getPost("toDate");
		$data['from_date']				= $this->input->getPost("fromDate");
		$data['created_time']			= date('Y-m-d h:i:s');
		$data['is_active']='1';
		$data['flag']='1';
		$return = $this->Fieldcoll_and_agency_reassignment_model->save_account_reassignment_request($data);
		if ($return) {
			$cache = session()->get('USER_ID').'_approval_reassignment_temp';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Request Reassigned Success', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'Request Reassigned Failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
	}

}