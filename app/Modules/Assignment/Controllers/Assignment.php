<?php 
namespace App\Modules\Assignment\Controllers;
use App\Modules\Assignment\Models\Assignment_model;
use CodeIgniter\Cookie\Cookie;

class Assignment extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Assignment_model = new Assignment_model();
	}

	function unassign_dc_list(){
		$data['classification'] = $this->input->getPost('classification');
		$data['user_id'] = $this->input->getGet('userid');
		return view('\App\Modules\Assignment\Views\Unassign_dc_list_view', $data);
	}
	function account_unassignment_to_dc_list(){
		$user_id = $this->input->getGet('user_id');
        $cache = session()->get('USER_ID').'_account_unassignment_to_dc_list';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Assignment_model->get_account_unassignment_to_dc_list($user_id);
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
    }
	function visit_dc_list(){
		$data['classification'] = $this->input->getPost('classification');
		$data['user_id'] = $this->input->getGet('userid');
		return view('\App\Modules\Assignment\Views\Visit_dc_list_view', $data);
	}
	function account_visit_to_dc_list(){
		$user_id = $this->input->getGet('user_id');
		$cache = session()->get('USER_ID').'_account_visit_to_dc_list';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Assignment_model->get_account_visit_to_dc_list($user_id);
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
	
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function ptp_dc_list(){
		$data['classification'] = $this->input->getPost('classification');
		$data['user_id'] = $this->input->getGet('userid');
		return view('\App\Modules\Assignment\Views\Ptp_dc_list_view', $data);
	}
	function account_ptp_to_dc_list(){
		$user_id = $this->input->getGet('user_id');
		$cache = session()->get('USER_ID').'_account_ptp_to_dc_list';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Assignment_model->get_account_ptp_to_dc_list($user_id);
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
	
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function ptp_amount_dc_list(){
		$data['classification'] = $this->input->getPost('classification');
		$data['user_id'] = $this->input->getGet('userid');
		return view('\App\Modules\Assignment\Views\Ptp_amount_dc_list_view', $data);
	}
	function account_ptp_amount_to_dc_list(){
		$user_id = $this->input->getGet('user_id');
		$cache = session()->get('USER_ID').'_account_ptp_amount_to_dc_list';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Assignment_model->get_account_ptp_amount_to_dc_list($user_id);
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
	
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function outbound_class_work_assignment(){
		$builder = $this->db->table('cms_classification');
		$builder->select('*');
		$builder->where('account_handling like "%telecoll%" ');
		$builder->orderBy('class_priority','asc');
		$data['class'] =$builder->get()->getResultArray();

		$builder = $this->db->table('cms_team');
		$builder->select('*');
		$builder->where('type_collection like "%TeleColl%" ');
		$builder->orderBy('team_name','asc');
		$data['team'] = $builder->get()->getResultArray();

		
		$builder = $this->db->table('acs_class_work_assignment');
		$builder->select('outbound_team, class_mst_id');
		$assignment = $builder->get()->getResultArray();

		$asign = array();
		foreach ($assignment as $key => $value) {
			$asign[$value['class_mst_id']] = $value['outbound_team'];
		}

		$data['assignment'] = $asign;

		return view("\App\Modules\Assignment\Views\OutboundClassWorkAssignmentView",$data);
	}

	function editAssignment(){
		$data=array();
		$data['id'] = $this->input->getPost('id');
		return view("\App\Modules\Assignment\Views\OutboundClassWorkAssignmentFormView",$data);
	}

	function get_class_information(){
		$class_id = $this->input->getPost("class_id");
		
		$sql ="SELECT classification_id class_id , classification_name name from cms_classification where classification_id = ?";
		
		$result = $this->db->query($sql,[$class_id]);
		$data = $result->getRowArray();

		$return = array("success"=>true,"data"=>$data);
		return $this->response->setStatusCode(200)->setJSON($return);
	}
	function get_class_work_assignment_by(){
		$class_id = $this->input->getPost("class_id");
		$data = $this->Assignment_model->get_class_work_assignment_by($class_id);
		
		if (count($data)>0) {
			$return = array("success"=>true,"data"=>$data);
		} else {
			$return = array("success"=>false,"data"=>"null");
		}
		
		return $this->response->setStatusCode(200)->setJSON($return);
	}

	function get_outbound_team_class_work_assignment() {
		$class_id = $this->input->getPost('class_id');
		$res = $this->Assignment_model->get_outbound_team_not_in_outbound($class_id);
		$return = array();
	
		if (count($res)>0) {
					 $return = array("success"=>true,"data"=>$res);
		}
		else {
				 $return = array("success"=>false,"data"=>"null");
		} 

		return $this->response->setStatusCode(200)->setJSON($return);
	}

	function update_class_work_assignment() {
		$post = array();
		$NOW = $date = date('Y-m-d H:i:s');
		
		foreach ($_POST as $key => $value) {
			$post[$key] = $this->input->getPost($key);
		}
			

		$class_id 		= $post['row'][0]['value'];
		$data = array();
		$num_array = count($post['row']);	
		$work_shift = $post['row'][$num_array - 1]['value'];
		
		if($work_shift == "")
			$work_shift = 1;
		
		for ($i=1;$i< ($num_array - 1);$i++) {
			if (!empty($post['row'][$i]['value'])) {
				 $data[$i] = array(
									"outbound_team"=>$post['row'][$i]['value'],
									"class_mst_id"=>$class_id,
									"created_by"=> session()->get('USER_ID'),
									"created_time"=>date('Y-m-d H:i:s')
						);
				// Update Group Telephony
				// $this->Coordinatormain_Model->update_group_telephony($post['row'][$i]['value'], $class_id, $group_telephony);
			}
		}
			
		if ($post['row'][0]['name']=='class_id') {
			
			//remove dulu
			$this->db->table('acs_class_work_assignment')->where(array('class_mst_id' => $class_id))->delete(); 
				
			$start_date = date("Y-m")."-01";
			$end_date   = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
			
			// $this->db->query("CALL PTPStatus('".$start_date."', '".$end_date."')");	
				
			// insert baru
			if (count($data)<>0) {
				
				$this->db->table('acs_class_work_assignment')->insertBatch($data); 
				
				// $team_id = $this->Common_Model->get_record_value('outbound_team','acs_class_work_assignment','class_mst_id="'.$class_id.'"');
				// $agent_id = $this->Common_Model->get_record_value('agent_list','cms_team','team_id="'.$team_id.'"');
				
				// $sql_class = "UPDATE cms_classification SET assigned_agent ='".$agent_id."' WHERE classification_id ='".$class_id."'";
				// $this->db->query($sql_class);
				
				//tambahan cycle history
				$sql = "select * from acs_class_cycle where class_id = ? AND end_time IS NULL";
				$query = $this->db->query($sql,[$class_id]);
				$num_rows1 = $query->getNumRows();
				// echo $num_rows1;
				if($num_rows1 == 0){
					$sql = "select * from acs_class_cycle where class_id = ?";
					$query = $this->db->query($sql,[$class_id]);
					$num_rows2 = $query->getNumRows();
									
					$dial_mode = $this->Common_model->get_record_value("dialing_mode_id", "acs_dialing_mode_call_status", "class_id='".$class_id."'");
					
					if($num_rows2 == 0){
						
						$sql = "INSERT INTO acs_class_cycle SET class_id = ? , cycle='0', start_time = ? , dialing_mode = ? ";
						$this->db->query($sql,[$class_id,$NOW ,$dial_mode]);
						
						$next_cycle = '0';
					}
					else {
						$sql = "INSERT INTO acs_class_cycle SET class_id= ? , cycle = ?, start_time=?, dialing_mode = ? ";
						$this->db->query($sql,[$class_id,$num_rows2,$NOW ,$dial_mode]);
						
						$next_cycle = $num_rows2;
					}
					$sql = "UPDATE acs_predictive_queue SET status= 0, cycle= ? WHERE contract_number 
									IN (SELECT contract_number FROM acs_customer_appointment WHERE (status =10 OR status=3) 
									AND destination_type = 0 AND class_id =?)";
					//echo $sql;
					$rs = $this->db->query($sql,[$next_cycle,$class_id]);
					if($rs) {
						$sql = "DELETE FROM acs_customer_appointment WHERE (status = 10 OR status=3) 
										AND destination_type = 0 AND class_id = ? ";
						$this->db->query($sql,[$class_id]);
						//echo $sql;
					}
					
					$sql = "UPDATE acs_predictive_queue SET cycle= ? WHERE contract_number
									IN (SELECT contract_number FROM acs_customer_appointment WHERE status = 10
									AND destination_type = 1 AND class_id = ?)";
					//echo $sql;
					$this->db->query($sql,[$next_cycle,$class_id]);

					$sql = "UPDATE acs_predictive_queue SET cycle= ? WHERE status = 0 AND class_id = ?";
					$this->db->query($sql,[$next_cycle,$class_id]);
					
					//update work shift
					$sql = "UPDATE acs_predictive_queue SET status='0' WHERE status = 20 and class_id = ? AND work_shift= ?";
					$this->db->query($sql,[$class_id,$work_shift]);
					/*
					$sql = "UPDATE acs_predictive_queue SET status='20' WHERE status = 0 AND class_id ='".$class_id."' AND work_shift !='".$work_shift."'";
					$this->db->query($sql);
					*/
					//Repetisi semua FOL
					// $sql = "UPDATE acs_predictive_queue SET `status` = 0, current_priority = 0, attempt_count = 0, cycle='".$next_cycle."' WHERE (status = 2 OR status = 5) AND (group_number = '' OR group_number IS NULL) AND contract_number class_id ='".$class_id."'";
					//$sql = "UPDATE acs_predictive_queue SET `status` = 0, cycle='".$next_cycle."' WHERE status = 2 AND group_number = 'FOL' AND class_id ='".$class_id."'";
	
					//======================================old=========================================
					// $sql = "UPDATE acs_predictive_queue SET status = '0', current_priority = '0', attempt_count = '0' where contract_number IN (
								// SELECT bb.contract_number FROM (
								 // select contract_number, max(call_time) as call_time 
								  // FROM acs_call_history_daily 
								   // where date(call_time) = curdate() GROUP BY contract_number
								// ) as aa JOIN acs_call_history_daily bb ON aa.contract_number = bb.contract_number AND aa.call_time = bb.call_time
								// JOIN acs_predictive_queue as cc ON aa.contract_number = cc.contract_number
								// WHERE (bb.call_result = 'BDPH' OR bb.call_result = 'UTC' OR cc.status = '5') AND cc.class_id = '".$class_id."'
							// )
					// ";
					// $this->db->query($sql);	
					//======================================old=========================================
			
				 			
					//Update balik semua yg sedang di-handle agent
					$sql = "UPDATE acs_predictive_queue SET `status`= 2 WHERE contract_number 
									IN (SELECT contract_number_handling FROM acs_user WHERE contract_number_handling <> '')";
					$this->db->query($sql);
				 	
						
					
					
					
					$responce = array("success"=>true,"message"=>"Update berhasil.");
				}
			}
			else {		
				$sql = "UPDATE acs_customer_appointment SET status=3 WHERE status=10 AND destination_type = 0 and class_id =? ";
				$this->db->query($sql,[$class_id]);
								
				//tambahan cycle history
				$sql = "SELECT * FROM acs_class_cycle WHERE class_id= ? ";
				$query = $this->db->query($sql,[$class_id]);
				$current_cycle = $query->getNumRows();
				
				$sql = "UPDATE acs_class_cycle SET end_time=? WHERE class_id= ? AND cycle= ?";
				$current_cycle = $current_cycle-1;
				$resxx = $this->db->query($sql,[$NOW,$class_id,$current_cycle]);
				
				$responce = array("success"=>true,"message"=>"Update berhasil.");
			}
		} 
		
		// $this->assignment_model->class_agent_assignment_form_save();
		$responce = array("success"=>true,"message"=>"Update berhasil.");
		return $this->response->setStatusCode(200)->setJSON($responce);
	}

	function loadAssignment(){
		$builder = $this->db->table('acs_class_work_assignment');
		$builder->select('outbound_team, class_mst_id');
		$assignment = $builder->get()->getResultArray();

		$asign = array();
		foreach ($assignment as $key => $value) {
			$asign[$value['class_mst_id']] = $value['outbound_team'];
		}


		$responce = array("success"=>true,"message"=>"get Data.","data"=> $asign);
		return $this->response->setStatusCode(200)->setJSON($responce);
	}
}