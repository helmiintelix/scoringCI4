<?php 
namespace App\Modules\CaseEscalationToTeamLeaderDanFormApproval\Controllers;
use App\Modules\CaseEscalationToTeamLeaderDanFormApproval\Models\Case_escalation_to_team_leader_dan_form_approval_model;
use CodeIgniter\Cookie\Cookie;

class Case_escalation_to_team_leader_dan_form_approval extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Case_escalation_to_team_leader_dan_form_approval_model = new Case_escalation_to_team_leader_dan_form_approval_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		$data['username'] = session()->get('USER_ID');
		$data['coordinator_id'] = session()->get('USER_ID');
		$data['info'] = $this->Case_escalation_to_team_leader_dan_form_approval_model->coordinator_information(session()->get('USER_ID'));
		$data['result'] = $this->Case_escalation_to_team_leader_dan_form_approval_model->get_coordinator_task($data['coordinator_id']);
		// dd($data);
		return view('\App\Modules\CaseEscalationToTeamLeaderDanFormApproval\Views\Coordinator_approval_main_view', $data);
	}
	function get_data_coordinator(){
		$data = $this->Case_escalation_to_team_leader_dan_form_approval_model->get_coordinator_task(session()->get('USER_ID'));
		$result = json_encode($data);
		$rs = ['success' => true, 'message' => '', 'data' => $result];
		return $this->response->setStatusCode(200)->setJSON($rs);

	}
	function view_subgrid_coordinator_performance(){
		$data['id_call_result'] = $this->input->getGet('id_call_result');
		$data['coordinator_id'] = session()->get('USER_ID');
		$data = $this->Case_escalation_to_team_leader_dan_form_approval_model->get_subgrid_team_performance($data);
		
	    if ($data) {
			$cacheKey = session()->get('USER_ID') . '_subgrid_team_performance';
			$this->cache->delete($cacheKey);
			$this->cache->save($cacheKey, json_encode($data), env('TIMECACHE_1'));
	
			$rs = ['success' => true, 'message' => 'Success to apply filter', 'data' => $data];
		} else {
			$rs = ['success' => false, 'message' => 'failed', 'data' => null];
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function new_phone_address_approval(){
		$data['contract_number'] = $this->input->getGet("contract_no");
		$data['coll_id'] = $this->input->getGet("coll_id");
		$data['type'] = $this->input->getGet("type");
		$data['old']= $this->Common_model->get_record_values('cr_name_1,cm_card_nmbr ,CR_HANDPHONE,CR_HANDPHONE2,CR_HANDPHONE3,CR_HOME_PHONE,CR_CO_HOME_PHONE,CR_OFFICE_PHONE,CR_CO_OFFICE_PHONE , cr_city , cr_addr_1 , cr_zip_code','cpcrd_new','cm_card_nmbr= "'.$data['contract_number'].'" ','');
		$data['new']=  $this->Common_model->get_record_values('b.id ,b.name, borrower_hp1,borrower_hp2,borrower_hp3,borrower_alamat,borrower_zip,borrower_provinsi,borrower_kota,borrower_kecamatan,borrower_kelurahan, a.created_time','acs_temp_phone a , cc_user b','a.created_by=b.id and a.contract_number= "'.$data['contract_number'].'" and a.collection_result="'.$data['type'].'" and a.collection_history_id ="'.$data['coll_id'].'" ','');
		// dd($data);
		return view('App\Modules\CaseEscalationToTeamLeaderDanFormApproval\Views\New_phone_address_approve_view', $data);
	}
	function save_approval_request() {
		$type = $this->input->getGet('type');
		$status = $this->input->getGet('status');
		$id = $this->input->getGet('id');

		$USER_ID = session()->get('USER_ID');
		$TIME = date('Y-m-d H:i:s');
		if($type=='NPH'){
			$builder = $this->db->table("acs_temp_phone");
			$builder->set('status',$status);
			$builder->set('updated_by',$USER_ID);
			$builder->set('updated_time',$TIME);
			$builder->where('collection_history_id',$id);
			$builder->update();

			$builder2 = $this->db->table("acs_coordinator_task");
			$builder2->set('status','COMPLETED');
			$builder2->set('update_by',$USER_ID);
			$builder2->set('updated_time',$TIME);
			$builder2->where('collection_history_id',$id);
			$builder2->update();

			$cacheKey = session()->get('USER_ID') . '_subgrid_team_performance';
			$this->cache->delete($cacheKey);
			$rs = array('success'=>true , 'message'=>'berhasil');
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
		else if($type=='NAD'){
			$builder = $this->db->table("acs_temp_phone");
			$builder->set('status',$status);
			$builder->set('updated_by',$USER_ID);
			$builder->set('updated_time',$TIME);
			$builder->where('collection_history_id',$id);
			$builder->update();
			
			$builder2 = $this->db->table("acs_coordinator_task");
			$builder2->set('status','COMPLETED');
			$builder2->set('update_by',$USER_ID);
			$builder2->set('updated_time',$TIME);
			$builder2->where('collection_history_id',$id);
			$builder2->update();


			$cacheKey = session()->get('USER_ID') . '_subgrid_team_performance';
			$this->cache->delete($cacheKey);
			$rs = array('success'=>true , 'message'=>'berhasil');
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

}