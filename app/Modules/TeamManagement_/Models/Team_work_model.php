<?php	if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Team_work_model Extends CI_model 
{

	function get_team_work_list()
	{
		// $file_id = $this->input->get_post('file_id');
		$iDisplayStart = $this->input->get_post('page', true);
        $iDisplayLength = $this->input->get_post('rows', true);
        $iSortCol_0 = $this->input->get_post('sidx', true);
        $iSortingCols = $this->input->get_post('sord', true);
        $sSearch = $this->input->get_post('_search', true);
        $sEcho = $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);

		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($iDisplayLength, ($iDisplayStart-1) * $iDisplayLength);
		}

		
		if ($sSearch == 'true') {
			$classifications = $this->input->get_post('filters', true);
			if($classifications){
				//echo "xxx".$classifications."vsdv";
				$classifications = json_decode($classifications);
				$where = '';
				$whereArray = array();
				$rules = $classifications->rules;
				$groupOperation = $classifications->groupOp;
				foreach($rules as $rule) {
		
					$fieldName = $rule->field;
					$fieldData = $rule->data;
					
					$fieldOperation = $fieldName.' LIKE "%'.$fieldData.'%"';
		
					if($fieldOperation != '') $whereArray[] = $fieldOperation;
				}
				if (count($whereArray)>0) {
					$where .= join(' '.$groupOperation.' ', $whereArray);
				} else {
					$where = '';
				}
			}else{
		
				$ops = array(
				//'eq' => '=', //equal
				'eq' => 'LIKE', // contains
				'ne' => '<>',//not equal
				'lt' => '<', //less than
				'le' => '<=',//less than or equal
				'gt' => '>', //greater than
				'ge' => '>=',//greater than or equal
				'bw' => 'LIKE', //begins with
				'bn' => 'NOT LIKE', //doesn't begin with
				'in' => 'LIKE', //is in
				'ni' => 'NOT LIKE', //is not in
				'ew' => 'LIKE', //ends with
				'en' => 'NOT LIKE', //doesn't end with
				'cn' => 'LIKE', // contains
				'nc' => 'NOT LIKE'	//doesn't contain
				);
		
				foreach ($ops as $key=>$value){
					if ($searchOper==$key) {
						$ops = $value;
					}
				}
				//if($searchOper == 'eq' ) $searchString = $searchString;
				if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
				if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
				if($searchOper == 'eq' || $searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';
		
				$where = "$searchField $ops '$searchString' ";
			}
		}


		$select = array('cms_team.*, cc.name as supervisor_name,CONCAT(cc_user.name, " (", cms_team.team_leader,")") AS team_leader, IF(cms_team.is_active = "1", "<span class=\"label label-sm label-success\">Active</span>", "<span class=\"label label-sm label-danger\">Not Active</span>") AS is_active');
								
		$this->db->from('cms_team');
		$this->db->join('cc_user', 'cc_user.id=cms_team.team_leader','left');
		$this->db->join('cc_user cc', 'cc.id=cms_team.supervisor','left');


				if(!empty($where))
					$this->db->where($where);

		// Ordering
		if(isset($iSortCol_0))
		{
			$this->db->protect_identifiers=false;
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		switch($this->session->userdata('LEVEL_GROUP')){
			case "SUPERVISOR" :
				$this->db->where('supervisor',$this->session->userdata('USER_ID'));
			break;
			
			case "TEAM_LEADER" :
				$this->db->where('team_leader',$this->session->userdata('USER_ID'));
			break;



		}

		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		
        $rResult = $this->db->get();
        // echo $this->db->last_query();
		// Data set length after classificationing
        $this->db->select('FOUND_ROWS() AS found_rows');
		
        $iclassificationedTotal = $this->db->get()->row()->found_rows;
        // Total data set length	
		$iTotal = $rResult->num_rows();
        if( $iTotal > 0 ) {
            $total_pages = 1;
        } else {
            $total_pages = 0;
        }
		
        // Output
		$output = array();

		$list = array();
        foreach($rResult->result_array() as $aRow)
        {
			
			$aRow['agent_assignment_list'] = '';
			
			foreach(explode('|', $aRow['agent_list']) as $value){
					
					if($value){
						$name = $this->common_model->get_record_value('name','cc_user','id="'.$value.'"');
						if($name != ''){
							$aRow['agent_assignment_list'] .= '<p><li>'.$value.'-'.$name.'</li></p>';
						}
						// $aRow['agent_assignment_list'] .= '<p><li>'.$this->common_model->get_record_value('name', 'cc_user', 'id="'.$value.'"').'</li></p>';
					}else{
						$aRow['agent_assignment_list'] .= '';
					}
					
					
					// print_r($value);
			}
			
			$list[] = array(
						"id" => $aRow['team_id'], 
						"cell" => $aRow
					);
        }		
        $output = array(
            'page' => $iDisplayStart,
            'total' => $total_pages,
            'rows' => $list,
            'records' => $iclassificationedTotal
        );
		
        echo json_encode($output);				
	}
	
	function save_team()
	{
		
		$team_name 	 = $this->input->post('team_name', true);
		$coord_list  = $this->input->post('coord_list', true);
		$tom_agent 	 = $this->input->post('tom_agent_fc', true);
		$tom_agent_tele 	 = $this->input->post('tom_agent', true);
		$description = $this->input->post('description', true);
		$flag_team	 = $this->input->post('opt_coll_type',true);
		$spv  = $this->input->post('spv_list', true);
		
			
		if(empty($team_name)){
			return array('success'=>false, 'message' => 'team name is empty');
		}
		
		$is_exist = $this->common_model->get_record_value('count(*)', 'cms_team', 'team_name like "%'.$team_name.'%"');
		
		if($is_exist > 0){
			/* 
				team already exist
			*/
			return array('success'=>false, 'message' => 'Team already exist');
		}else{
			
			$data = array(
							'team_id' => uuid(false),
							'team_name' => $team_name,
							'team_leader' => $coord_list,
							'supervisor' => $spv,
							'description' => $description,
							'created_by'  => $this->session->userdata('USER_ID'),
							'created_time'  => date('Y-m-d H:i:s'),
							'flag_team' => $flag_team,
					);
			/* 
				Jika agent sudah di assignment
			*/
			
			
			
			if(!empty($tom_agent)){
				
				foreach($tom_agent as $val){
					$agent_exist = $this->common_model->get_record_value('count(*)', 'cms_team', 'agent_list like "%'.$val.'%"');
					if($agent_exist > 0){
						return array('success'=>false, 'message' => 'agent '.$this->common_model->get_record_value('name', 'cc_user',"id = '".$val."'",'').' already to assignment');
						break;
					}
				}
				foreach($tom_agent as $val){
					$updateData = [
					   'team_id' => $data['team_id'],
					];
					$this->db->where('id', $val);
					$this->db->update('cc_user', $updateData); 
					
					
				}
				$data['agent_list'] = implode('|', $tom_agent);
			}
			
			if(!empty($tom_agent_tele)){
				foreach($tom_agent_tele as $val){
					$agent_exist = $this->common_model->get_record_value('count(*)', 'cms_team', 'agent_list like "%'.$val.'%"');
					if($agent_exist > 0){
						return array('success'=>false, 'message' => 'agent '.$this->common_model->get_record_value('name', 'cc_user',"id = '".$val."'",'').' already to assignment');
						break;
					}
				}
				foreach($tom_agent_tele as $val){
					$updateData = [
					   'team_id' => $data['team_id'],
					];
					$this->db->where('id', $val);
					$this->db->update('cc_user', $updateData); 

					$this->db->delete('acs_agent_assignment',array('user_id' => $val)); 

					$data_acs_agent_assignment['id'] 			= uuid(false);
					$data_acs_agent_assignment['user_id'] 		= $val;
					$data_acs_agent_assignment['team'] 			= $data['team_id'];
					$data_acs_agent_assignment['created_by'] 	= $this->session->userdata('USER_ID');
					$data_acs_agent_assignment['created_time'] 	= date('Y-m-d H:i:s');

					$this->db->insert('acs_agent_assignment',$data_acs_agent_assignment);
					
				}
				$data['agent_list'] = implode('|', $tom_agent_tele);
			}
			
			
			
			if($this->db->insert('cms_team', $data)){
				return array('success'=>true, 'message' => 'data has been saved');
			}else{
				return array('success'=>false, 'message' => 'data failed to saved');
			}
			
			
		}
		
		
		
	}
	
	function edit_team()
	{
		$team_id 	 = $this->input->post('team_id', true);
		$team_name 	 = $this->input->post('team_name', true);
		$coord_list  = $this->input->post('coord_list', true);
		$spv  = $this->input->post('spv_list', true);
		$tom_agent 	 = $this->input->post('tom_agent_fc', true);
		$tom_agent_tele 	 = $this->input->post('tom_agent', true);
		$description = $this->input->post('description', true);
		$flag_team = $this->input->post('opt_coll_type',true);
		
		
		if(isset($team_id) && $team_id!= ''){
			
			$data = array(
					'team_name' => $team_name,
					'team_leader' => $coord_list,
					'supervisor' => $spv,
					'description' => $description,
					'flag_team' => $flag_team,
			);
			
			$data['agent_list'] = null;

			if(!empty($tom_agent)){
				
					$updateData = [
					   'team_id' => null,
					];
					$this->db->where('team_id', $team_id);
					$this->db->update('cc_user', $updateData); 


				foreach($tom_agent as $val){
					$updateData = [
					   'team_id' => $team_id,
					];
					$this->db->where('id', $val);
					$this->db->update('cc_user', $updateData); 

				
					
				}

				$data['agent_list'] = implode('|', $tom_agent);
			}
			
			
			if(!empty($tom_agent_tele)){
				
					$updateData = [
					   'team_id' => null,
					];
					$this->db->where('team_id', $team_id);
					$this->db->update('cc_user', $updateData); 
				foreach($tom_agent_tele as $val){
					$updateData = [
					   'team_id' => $team_id,
					];
					$this->db->where('id', $val);
					$this->db->update('cc_user', $updateData); 

					$this->db->delete('acs_agent_assignment',array('user_id' => $val)); 

					$data_acs_agent_assignment['id'] 			= uuid(false);
					$data_acs_agent_assignment['user_id'] 		= $val;
					$data_acs_agent_assignment['team'] 			= $team_id ;
					$data_acs_agent_assignment['updated_by'] 	= $this->session->userdata('USER_ID');
					$data_acs_agent_assignment['updated_time'] 	= date('Y-m-d H:i:s');

					$this->db->insert('acs_agent_assignment',$data_acs_agent_assignment);
					
				}
				$data['agent_list'] = implode('|', $tom_agent_tele);
			}

			// var_dump($data['agent_list']);
			// die;
			
			
			
			
			$this->db->where('team_id', $team_id);
			$return = $this->db->update('cms_team', $data);
			
			if($return){
				return array('success'=>true, 'message' => 'data has been saved');
			}else{
				return array('success'=>false, 'message' => 'data failed to saved');
			}
			
		}else{
			return array('success'=>false, 'message' => 'id not found');
		}
		
		
		
	}
	
	function del_team()
	{
		$team_id 	 = $this->input->get_post('team_id', true);
		$is_active=$this->common_model->get_record_value('is_active','cms_team','team_id="'.$team_id.'"');
		$agent_exist = $this->common_model->get_record_value('count(*)', 'cms_team', 'team_id="'.$team_id.'" AND agent_list IS NOT NULL');
		
		if($agent_exist > 0){
			return array('success'=>false, 'message' => 'team not delete. please unassigned all agent on team');
		}else{
			// var_dump($is_active);die;
			
			// $delete = "delete from cms_team where team_id = '".$team_id."'";
			// $return = $this->db->query($delete);
			if($is_active=="0"){
				$delete = "update cms_team set is_active='1' where team_id = '".$team_id."'";
				$response = array('success'=>true, 'message' => 'Team has been activated');
			}else{
				$delete = "update cms_team set is_active='0' where team_id = '".$team_id."'";
				$response = array('success'=>true, 'message' => 'team has been deactivated');
			}
			$return = $this->db->query($delete);

			

			if($return){
				return $response;
			}else{
				return array('success'=>false, 'message' => 'data failed to disable');
			}
		}
		
		
	}
	
	function get_team_setup($team_id){

		$bind	= array("team_id"=>$team_id);
		$sql	= "select * from cms_team where team_id=?";
		$data 	= $this->db->query($sql,$bind);
		$result = $data->result_array();
	
		return $result;
	}
	
}