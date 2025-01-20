<?php
namespace App\Modules\TeamManagement\Controllers;
// use App\Modules\Produk\Models\ProdukModel;

class TeamWork extends \App\Controllers\BaseController
{
	function index()
	{
		return view('\App\Modules\TeamManagement\Views\team_work_view');
	}


	function get_team_work_list()
	{
		$data = $this->team_work_model->get_team_work_list();
		echo $data;
	}
	
	function add_team_form()
	{
		
		// $agent_not = $this->common_model->get_record_value("GROUP_CONCAT(agent_list SEPARATOR '|') as agent_not_in", "cms_team", "team_id != '' or agent_list != ''", "");
		$leader_not = $this->common_model->get_record_value("GROUP_CONCAT(team_leader SEPARATOR '|') as leader_not_in", "cms_team", "team_id != ''", "name");
		$not_agent = $this->common_model->get_record_list(" team_id as value, agent_list as item", "cms_team", " team_id != '' ", "");
		$agent_not = implode('|', $not_agent);
		
		
		
		
//		$data['agent_list'] = $this->common_model->get_record_list("id AS value, name AS item", "cc_user", "group_id = 'AGENT' AND id NOT IN ('".str_replace("|", "','", $agent_not)."')", "name");
//		$data['coord_list'] = $this->common_model->get_ref_master("id AS value, name AS item", "cc_user", "group_id = 'TEAM_LEADER' AND id NOT IN ('".str_replace("|", "','", $leader_not)."')", "name");
//		$data['spv_list'] = $this->common_model->get_ref_master("id AS value, name AS item", "cc_user", "group_id = 'SUPERVISOR' ", "name");
		$data['agent_list'] = $this->common_model->get_record_list("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on (b.id = a.group_id)", "level_group in( 'TELECOLL','COLLECTOR','AGENT_FIELD_COLLECTOR') AND a.id NOT IN ('".str_replace("|", "','", $agent_not)."') and a.is_active='1'", "a.name");
		
		$data['fildcoll_list'] = $this->common_model->get_record_list("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on (b.id = a.group_id)", "level_group in('FIELD_COLL') AND a.id NOT IN ('".str_replace("|", "','", $agent_not)."') and a.is_active='1'", "a.name");
		//$data['coord_list'] = $this->common_model->get_record_list("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on (b.id = a.group_id)", "level_group = 'TEAM_LEADER' AND a.id NOT IN ('".str_replace("|", "','", $leader_not)."')", "a.name");
		$sql = "SELECT a.id as value, a.name as item FROM cc_user a JOIN cc_user_group b ON (b.id = a.group_id) 
		WHERE level_group = 'TEAM_LEADER' 
		AND b.id NOT IN ('".str_replace("|", "','", $leader_not)."') and a.is_active = '1'";
		//$data[]
		$data['coord_list'] = $this->db->query($sql)->result_array();
		//var_dump($data['coord_list']);
		//var_dump($this->db->query($sql)->result_array());
		
		//die;
		//$data['coord_list'] = $this->db->query('select cc_user.id value, cc_user.name item from cc_user join cc_user_group on cc_user.group_id=cc_user_group.id where cc_user_group.level_group="TEAM_LEADER"')->result_array();
		if($this->session->userdata("LEVEL_GROUP") == "SUPERVISOR"){
			$data['spv_list'] = $this->common_model->get_ref_master("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on (b.id = a.group_id)", "level_group = 'SUPERVISOR' AND a.id = '".$this->session->userdata("USER_ID")."'", "a.name");
		} else {
			$data['spv_list'] = $this->common_model->get_ref_master("a.id AS value, a.name AS item", "cc_user a JOIN cc_user_group b ON (b.id = a.group_id) JOIN aav_configuration aa ON b.level_group=aa.name", "aa.parameter='LEVEL_GROUP' AND aa.VALUE > '3'", "a.name");
		}
		$this->load->view('add_team_form_view', $data);
	}
	
	function edit_team_form()
	{
		
		$data['team_id'] = $this->input->get_post('team_id');
		
		// $agent_not = $this->common_model->get_record_value("GROUP_CONCAT(agent_list SEPARATOR '|') as agent_not_in", "cms_team", "team_id != '".$data['team_id']."'", "name");
		$leader_not = $this->common_model->get_record_value("GROUP_CONCAT(team_leader SEPARATOR '|') as leader_not_in", "cms_team", "team_id != '".$data['team_id']."'", "name");
		$not_agent = $this->common_model->get_record_list(" team_id as value, agent_list as item", "cms_team", " team_id != '".$data['team_id']."' ", "");
		$agent_not = implode('|', $not_agent??'');
		
//		$data['agent_list'] = $this->common_model->get_record_list("id AS value, name AS item", "cc_user", "group_id = 'AGENT' AND id NOT IN ('".str_replace("|", "','", $agent_not)."')", "name");
//		$data['coord_list'] = $this->common_model->get_ref_master("id AS value, name AS item", "cc_user", "group_id = 'TEAM_LEADER' AND id NOT IN ('".str_replace("|", "','", $leader_not)."')", "name");
//		$data['spv_list'] = $this->common_model->get_ref_master("id AS value, name AS item", "cc_user", "group_id = 'SUPERVISOR' ", "name");
		$data['fildcoll_list'] = $this->common_model->get_record_list("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on (b.id = a.group_id)", "level_group in('FIELD_COLL') AND a.id NOT IN ('".str_replace("|", "','", $agent_not)."') and a.is_active='1'", "a.name");

		$data['agent_list'] = $this->common_model->get_record_list("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on (b.id = a.group_id)", "level_group  in ('TELECOLL','COLLECTOR','AGENT_FIELD_COLLECTOR') AND a.id NOT IN ('".str_replace("|", "','", $agent_not)."') and a.is_active='1'", "a.name");
		

		//$data['coord_list'] = $this->common_model->get_ref_master("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on (b.id = a.group_id)", "level_group = 'TEAM_LEADER' AND a.id NOT IN ('".str_replace("|", "','", $leader_not)."')", "a.name");
		$sql = "SELECT a.id as value, a.name as item FROM cc_user a JOIN cc_user_group b ON (b.id = a.group_id) 
		WHERE level_group = 'TEAM_LEADER' 
		AND b.id NOT IN ('".str_replace("|", "','", $leader_not)."') and a.is_active = '1'";
		//$data[]
		$data['coord_list'] = $this->db->query($sql)->result_array();
		if($this->session->userdata("LEVEL_GROUP") == "SUPERVISOR"){
			$data['spv_list'] = $this->common_model->get_ref_master("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on (b.id = a.group_id)", "level_group = 'SUPERVISOR' AND a.id = '".$this->session->userdata("USER_ID")."'", "a.name");
		} else {
			$data['spv_list'] = $this->common_model->get_ref_master("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on (b.id = a.group_id)", "level_group = 'ADMIN' ", "a.name");
		}
		$data["data_team"] = $this->common_model->get_record_values("*", "cms_team", "team_id= '".$data['team_id']."'", "");
	
		// echo "<pre>";
		// print_r($data);
		$this->load->view('edit_team_form_view', $data); 
	}
	
	function get_team_setup()
	{
		$team_id = $this->input->get_post('team_id');
		
		
		$data = $this->team_work_model->get_team_setup($team_id);
	
		
		if (count($data)>0) {
			$return = array("success"=>true,"data"=>$data);
		}
		else {
			$return = array("success"=>false,"data"=>$data);
		} 

		echo json_encode($return);
	}
	
	function save_team()
	{
		$return = $this->team_work_model->save_team();
		
		echo json_encode($return);
	}
	
	function edit_team()
	{
		$return = $this->team_work_model->edit_team();
		
		echo json_encode($return);
	}
	
	function del_team()
	{
		$return = $this->team_work_model->del_team();
		
		echo json_encode($return);
	}

	function get_list_team(){

		$this->bulder = $this->db->table('cms_team');
		$select = array('cms_team.*, cc.name as supervisor_name,CONCAT(cc_user.name, " (", cms_team.team_leader,")") AS team_leader, IF(cms_team.is_active = "1", "<span class=\"label label-sm label-success\">Active</span>", "<span class=\"label label-sm label-danger\">Not Active</span>") AS is_active');
								
		$this->bulder->join('cc_user', 'cc_user.id=cms_team.team_leader','left');
		$this->bulder->join('cc_user cc', 'cc.id=cms_team.supervisor','left');


				if(!empty($where))
				$this->bulder->where($where);

		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->bulder->order_by($iSortCol_0, $iSortingCols);
		}

		switch(session()->get('LEVEL_GROUP')){
			case "SUPERVISOR" :
				$this->bulder->where('supervisor',session()->get('USER_ID'));
			break;
			
			case "TEAM_LEADER" :
				$this->bulder->where('team_leader',session()->get('USER_ID'));
			break;



		}

		$this->bulder->select(' '.str_replace(' , ', ' ', implode(', ', $select)), false);
		
        $rResult = $this->bulder->get();

		$return = $rResult->getResult();

		$result = array();
		
		if($rResult->getNumRows()>0){
			foreach ($rResult->getResult()[0] as $key => $value) {
				$result['header'][] = array('field'=> $key);
			}
			$result['data'] = $return;
			
			$rs = array('success'=>true , 'message'=>'','data'=>$result);
			echo json_encode($rs);
		}
		
	}

}