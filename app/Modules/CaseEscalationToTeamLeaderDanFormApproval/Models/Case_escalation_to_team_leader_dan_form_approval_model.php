<?php
namespace App\Modules\CaseEscalationToTeamLeaderDanFormApproval\models;
use App\Models\Common_model;
use CodeIgniter\Model;

Class Case_escalation_to_team_leader_dan_form_approval_model Extends Model 
{
	protected $Common_model;
    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new Common_model;
    }
	function coordinator_information($user_login){
		$this->builder = $this->db->table("acs_outbound_team as a");
		$this->builder->select(
							"a.id,
							a.outbound_team_name,
							a.coordinator,
							c.class_id,
							c.name"
						);
		$this->builder->join("acs_class_work_assignment as b", "a.id=b.outbound_team", "left");
		$this->builder->join("acs_class as c", "b.class_mst_id=c.class_id", "left");
		$this->builder->where("a.coordinator", $user_login);
		$data = $this->builder->get()->getRowArray();
		return $data;
	}
	function get_coordinator_task($coordinator_id){
		$group_id = session()->get('GROUP_ID');
		if ($group_id=='COORDINATOR' || $group_id=='TEAM_LEADER') {
			$this->builder = $this->db->table("acs_reference AS x");
			$this->builder->select(
				"x.value AS collection_result,
				(
					SELECT f.description 
					FROM acs_reference AS f 
					WHERE REFERENCE = 'COORDINATOR_TASK' 
					AND VALUE = x.value
				) AS description,
				(
					SELECT COUNT(*) 
					FROM acs_coordinator_task a 
					JOIN cms_team b ON a.outbound_team_id = b.team_id 
					WHERE a.status = 'OPEN' 
					AND b.team_leader = 'ADMIN' 
					AND a.collection_result = x.value
				) AS open"
			);
			$this->builder->where("x.reference", "COORDINATOR_TASK");
			$this->builder->where("x.status", "1");
			$this->builder->whereIn("x.value", ['PTC', 'NPH', 'NAD', 'STF']);
			$rs1 = $this->builder->get()->getResultArray();

			$this->builder = $this->db->table("acs_coordinator_task a");
			$this->builder->select(
				"'ESKALASI' AS collection_result, 
				'Escalate' AS description,
				(
					SELECT COUNT(*) 
					FROM acs_coordinator_task aa 
					JOIN cms_team oo ON aa.outbound_team_id = oo.team_id 
					WHERE aa.collection_result NOT IN ('PTC', 'NPH', 'NAD', 'STF') 
					AND aa.status = 'OPEN'
				) AS open"
			);
			$this->builder->join("cms_team o", "a.outbound_team_id = o.team_id");
			$this->builder->whereNotIn("a.collection_result", ['PTC', 'NPH', 'NAD', 'STF']);
			$this->builder->where("a.team_leader", $coordinator_id);
			$rs2 = $this->builder->get()->getResultArray();

			$result =  array_merge($rs1, $rs2);
			return $result;
		} else if($group_id=='SUPERVISOR'){
			$this->builder = $this->db->table("acs_reference AS x");
			$this->builder->select(
				"x.value AS collection_result,
					(
						SELECT f.description 
					FROM acs_reference AS f 
					WHERE 
						REFERENCE = 'COORDINATOR_TASK' AND 
						VALUE = x.value
				) AS description,
				IFNULL(y.open,0) AS open"
			);
			$this->builder->join("(SELECT
					c.collection_result,
				(SELECT f.description FROM acs_reference AS f WHERE REFERENCE = 'COORDINATOR_TASK' AND VALUE = c.collection_result) AS DESCRIPTION,
				COUNT(CASE WHEN c.status = 'open' THEN c.collection_result ELSE NULL END) AS OPEN
				FROM (
					SELECT 
					a.collection_result,
					a.status 
				FROM acs_coordinator_task AS a 
				JOIN acs_outbound_team AS o ON a.outbound_team_id = o.id AND o.spv_id = '$coordinator_id'
				LEFT JOIN acs_call_history_daily b ON a.collection_history_id = b.id
			) AS c
				GROUP BY c.collection_result
			) AS y", "x.value=y.collection_result", "left");
			$this->builder->where('x.reference', 'COORDINATOR_TASK');
			$this->builder->where('x.status', '1');
			$this->builder->whereIn('x.value', ['PTC', 'NPH', 'NAD', 'STF']);
			$rs1 = $this->builder->get()->getResultArray();

			$this->builder = $this->db->table("acs_coordinator_task AS a");
			$this->builder->select(
				"'ESKALASI' collection_result , 'Escalate' description ,
				(SELECT  COUNT(*) total FROM acs_coordinator_task AS aa 
				JOIN acs_outbound_team AS oo ON aa.outbound_team_id = oo.id AND oo.spv_id = '".$coordinator_id."'
				WHERE aa.collection_result NOT IN ('PTC','NPH','NAD','STF') AND aa.status='open'
				GROUP BY oo.coordinator) AS open"
			);
			$this->builder->join("acs_outbound_team AS o", "a.outbound_team_id = o.id AND o.spv_id = '".$coordinator_id."'");
			$this->builder->whereNotIn("a.collection_result", ['PTC','NPH','NAD','STF']);
			$this->builder->groupBy("o.spv_id");
			$rs2 = $this->builder->get()->getResultArray();

			$result = array_merge($rs1, $rs2);
			return $result;
		} else if($group_id=='ADMIN' || $group_id=='ROOT'|| $group_id=='SUPERADMIN'){
			$this->builder = $this->db->table("acs_reference AS x");
			$this->builder->select(
				"x.value AS collection_result,
				(
					SELECT f.description 
					FROM acs_reference AS f 
					WHERE 
						REFERENCE = 'COORDINATOR_TASK' AND 
						VALUE = x.value
				) AS description,
				(
					SELECT COUNT(*) 
					FROM acs_coordinator_task a 
					WHERE a.status = 'OPEN' AND a.collection_result = x.value
				) AS open
				");
			$this->builder->where("x.reference", "COORDINATOR_TASK");
			$this->builder->where("x.status", "1");
			$this->builder->whereIn("x.value", ['PTC', 'NPH', 'NAD', 'STF']);
			$rs1 = $this->builder->get()->getResultArray();

			$this->builder = $this->db->table("acs_coordinator_task AS a");
			$this->builder->select(
				"'ESKALASI' AS collection_result, 'Escalate' AS description,
				(SELECT COUNT(*) AS total 
				FROM acs_coordinator_task AS aa 
				JOIN cms_team AS oo ON aa.outbound_team_id = oo.team_id 
				WHERE aa.collection_result NOT IN ('PTC', 'NPH', 'NAD', 'STF') AND aa.status = 'OPEN'
				) AS open"
				);
			$this->builder->join("cms_team AS o", "a.outbound_team_id = o.team_id");
			$this->builder->whereNotIn("a.collection_result", ['PTC', 'NPH', 'NAD', 'STF']);
			$rs2 = $this->builder->get()->getResultArray();
			
			$result =  array_merge($rs1, $rs2);
			return $result;
			
		}
	}
    function get_subgrid_team_performance($data){
		$group_id = session()->get('GROUP_ID');
		$coordinator_id = $data['coordinator_id'];
		$id_call = $data['id_call_result'];
		
		$this->builder = $this->db->table("acs_coordinator_task AS c");
		if ($id_call == "SMS") {
			$this->builder->select(
				"'' as action,
				c.id id_performance, 
				c.collection_history_id,
				c.contract_number,
				c.user_id,
				c.collection_result,
				c.status,
				c.created_time,
				e.cr_name_1 borrower_name,
				e.cr_addr_1 borrower_home_address1,
				e.cr_handphone borrower_handphone1,
				e.cr_home_phone borrower_home_phone1"
			);
			$this->builder->join("cpcrd_new AS e", "e.cm_card_nmbr=c.contract_number");
			$this->builder->join("cms_team as o", "c.outbound_team_id = o.team_id AND o.team_leader = '".$coordinator_id."'");
			$this->builder->join("cms_contact_history b", "c.collection_history_id = b.id", "left");
			$this->builder->where("c.collection_result", $id_call);
			$this->builder->where("c.status", "OPEN");
			$this->builder->orderBy("c.created_time", "asc");
			$rResult = $this->builder->get();
			$return = $rResult->getResultArray();
			$i=0;
			if ($rResult->getNumRows() > 0) {
				foreach ($return as &$row) {
					$id = $i+1;
					$row['action'] = '<button class="btn btn-sm btn-success" onClick="showDataDebitur(\''.$row['collection_history_id'].'\',\''.$row['contract_number'].'\',\''.$row['id_performance'].'\',\''.$row['collection_result'].'\','.$id.')" >view</button>';
					$i++;
				}
				unset($row);
				foreach ($rResult->getResultArray()[0] as $key => $value) {
					$result['header'][] = array('field' => $key);
				}
				$result['data'] = $return;
			} else {
				$result['header'] = array();
				$result['data'] = array();
			}
			$rs =  $result;
			return $rs;
		} else if ($id_call=='NPH' || $id_call=='PTC' || $id_call=='NAD' || $id_call=='NMAIL' || $id_call=='NEC') {
			if ($group_id=='ADMIN' || $group_id=='ROOT' || $group_id=='SUPERADMIN') {
				// $this->builder = $this->db->table("acs_coordinator_task AS c");
				$this->builder->select(
					"'' as action,
					b.created_by agent_id,
					f.name agent_name,
					(select category_name from cms_lov_registration where id = b.lov2 limit 1) contact_person,
					b.lov3 call_result,
					b.ptp_date,
					FORMAT(b.ptp_amount,0) ptp_amount, 
					b.notes notepad1, 
					c.id id_performance, 
					c.collection_history_id,
					c.contract_number,
					c.user_id,
					c.collection_result,
					c.status,
					c.created_time,
					e.cr_name_1 borrower_name,
					e.cr_addr_1 borrower_home_address1,
					e.cr_handphone borrower_handphone1,
					e.cr_home_phone borrower_home_phone1"
				);
				$this->builder->join("cpcrd_new AS e", "e.cm_card_nmbr=c.contract_number");
				$this->builder->join("cms_team as o", "c.outbound_team_id = o.team_id", "left");
				$this->builder->join("cms_contact_history b", "c.collection_history_id = b.id", "left");
				$this->builder->join("cc_user f", "f.id=c.user_id");
				$this->builder->where("c.collection_result", $id_call);
				$this->builder->where("c.status", "OPEN");
				$this->builder->orderBy("c.created_time", "asc");
				$rResult = $this->builder->get();
				$return = $rResult->getResultArray();
				$i=0;
				if ($rResult->getNumRows() > 0) {
					foreach ($return as &$row) {
						$id = $i+1;
						$row['action'] = '<button class="btn btn-sm btn-success" onClick="showDataDebitur(\''.$row['collection_history_id'].'\',\''.$row['contract_number'].'\',\''.$row['id_performance'].'\',\''.$row['collection_result'].'\','.$id.')" >view</button>';
						$i++;
					}
					unset($row);
					foreach ($rResult->getResultArray()[0] as $key => $value) {
						$result['header'][] = array('field' => $key);
					}
					$result['data'] = $return;
				} else {
					$result['header'] = array();
					$result['data'] = array();
				}
				$rs =  $result;
				return $rs;
			} else if ($group_id=='TEAM_LEADER' || $group_id=='COORDINATOR') {
				// $this->builder = $this->db->table("acs_coordinator_task AS c");
				$this->builder->select(
					"'' as action,
					b.created_by agent_id,
					f.name agent_name,
					(select category_name from cms_lov_registration where id = b.lov2 limit 1) contact_person,
					b.lov3 call_result,
					b.ptp_date,
					FORMAT(b.ptp_amount,0) ptp_amount, 
					b.notes notepad1, 
					c.id id_performance, 
					c.collection_history_id,
					c.contract_number,
					c.user_id,
					c.collection_result,
					c.status,
					c.created_time,
					e.cr_name_1 borrower_name,
					e.cr_addr_1 borrower_home_address1,
					e.cr_handphone borrower_handphone1,
					e.cr_home_phone borrower_home_phone1"
				);
				$this->builder->join("cpcrd_new AS e", "e.cm_card_nmbr=c.contract_number");
				$this->builder->join("cms_team as o", "c.outbound_team_id = o.team_id AND o.team_leader = '".$coordinator_id."'");
				$this->builder->join("cms_contact_history b", "c.collection_history_id = b.id", "left");
				$this->builder->join("cc_user f", "f.id=c.user_id");
				$this->builder->where("c.collection_result", $id_call);
				$this->builder->where("c.status", "OPEN");
				$this->builder->orderBy("c.created_time", "asc");
				$rResult = $this->builder->get();
				$return = $rResult->getResultArray();
				$i=0;
				if ($rResult->getNumRows() > 0) {
					foreach ($return as &$row) {
						$id = $i+1;
						$row['action'] = '<button class="btn btn-sm btn-success" onClick="showDataDebitur(\''.$row['collection_history_id'].'\',\''.$row['contract_number'].'\',\''.$row['id_performance'].'\',\''.$row['collection_result'].'\','.$id.')" >view</button>';
						$i++;
					}
					unset($row);
					foreach ($rResult->getResultArray()[0] as $key => $value) {
						$result['header'][] = array('field' => $key);
					}
					$result['data'] = $return;
				} else {
					$result['header'] = array();
					$result['data'] = array();
				}
				$rs =  $result;
				return $rs;
			}
		} else if ($id_call == "ESKALASI") {
			if ($group_id=='ADMIN' || $group_id=='ROOT' || $group_id=='SUPERADMIN') {
				// $this->builder = $this->db->table("acs_coordinator_task AS c");
				$this->builder->select(
					"'' as action,
					b.created_by agent_id,
					f.name agent_name,
					(select category_name from cms_lov_registration where id = b.lov2 limit 1) contact_person,
					b.lov3 call_result,
					b.ptp_date,
					FORMAT(b.ptp_amount,0) ptp_amount, 
					b.notes notepad1, 
					c.id id_performance, 
					c.collection_history_id,
					c.contract_number,
					c.user_id,
					c.collection_result,
					c.status,
					c.created_time,
					e.cr_name_1 borrower_name,
					e.cr_addr_1 borrower_home_address1,
					e.cr_handphone borrower_handphone1,
					e.cr_home_phone borrower_home_phone1"
				);
				$this->builder->join("cpcrd_new AS e", "e.cm_card_nmbr=c.contract_number");
				$this->builder->join("cms_team as o", "c.outbound_team_id = o.team_id");
				$this->builder->join("cms_contact_history b", "c.collection_history_id = b.id");
				$this->builder->join("cc_user f", "f.id=b.created_by");
				$this->builder->whereNotIn("c.collection_result", ['NPH','PTC','NAD']);
				$this->builder->where("c.status", "OPEN");
				$this->builder->orderBy("c.created_time", "asc");
				$rResult = $this->builder->get();
				$return = $rResult->getResultArray();
				$i=0;
				if ($rResult->getNumRows() > 0) {
					foreach ($return as &$row) {
						$id = $i+1;
						$row['action'] = '<button class="btn btn-sm btn-success" onClick="showDataDebitur(\''.$row['collection_history_id'].'\',\''.$row['contract_number'].'\',\''.$row['id_performance'].'\',\''.$row['collection_result'].'\','.$id.')" >view</button>';
						$i++;
					}
					unset($row);
					foreach ($rResult->getResultArray()[0] as $key => $value) {
						$result['header'][] = array('field' => $key);
					}
					$result['data'] = $return;
				} else {
					$result['header'] = array();
					$result['data'] = array();
				}
				$rs =  $result;
				return $rs;
			} else {
				// $this->builder = $this->db->table("acs_coordinator_task AS c");
				$this->builder->select(
					"'' as action,
					b.created_by agent_id,
					f.name agent_name,
					(select category_name from cms_lov_registration where id = b.lov2 limit 1) contact_person,
					b.lov3 call_result,
					b.ptp_date,
					FORMAT(b.ptp_amount,0) ptp_amount, 
					b.notes notepad1, 
					c.id id_performance, 
					c.collection_history_id,
					c.contract_number,
					c.user_id,
					c.collection_result,
					c.status,
					c.created_time,
					e.cr_name_1 borrower_name,
					e.cr_addr_1 borrower_home_address1,
					e.cr_handphone borrower_handphone1,
					e.cr_home_phone borrower_home_phone1"
				);
				$this->builder->join("cpcrd_new AS e", "e.cm_card_nmbr=c.contract_number");
				$this->builder->join("cms_team as o", "c.outbound_team_id = o.team_id AND o.team_leader = '".$coordinator_id."'");
				$this->builder->join("cms_contact_history b", "c.collection_history_id = b.id");
				$this->builder->join("cc_user f", "f.id=b.created_by");
				$this->builder->whereNotIn("c.collection_result", ['NPH','PTC','NAD']);
				$this->builder->where("c.status", "OPEN");
				$this->builder->orderBy("c.created_time", "asc");
				$rResult = $this->builder->get();
				$return = $rResult->getResultArray();
				$i=0;
				if ($rResult->getNumRows() > 0) {
					foreach ($return as &$row) {
						$id = $i+1;
						$row['action'] = '<button class="btn btn-sm btn-success" onClick="showDataDebitur(\''.$row['collection_history_id'].'\',\''.$row['contract_number'].'\',\''.$row['id_performance'].'\',\''.$row['collection_result'].'\','.$id.')" >view</button>';
						$i++;
					}
					unset($row);
					foreach ($rResult->getResultArray()[0] as $key => $value) {
						$result['header'][] = array('field' => $key);
					}
					$result['data'] = $return;
				} else {
					$result['header'] = array();
					$result['data'] = array();
				}
				$rs =  $result;
				return $rs;
			}
		} else if($id_call == "STF"){
			$this->builder->select(
				"'' as action,
				b.created_by agent_id,
				f.name agent_name,
				(select category_name from cms_lov_registration where id = b.lov2 limit 1) contact_person,
				b.lov3 call_result,
				b.ptp_date,
				FORMAT(b.ptp_amount,0) ptp_amount, 
				b.notes notepad1, 
				c.id id_performance, 
				c.collection_history_id,
				c.contract_number,
				c.user_id,
				c.collection_result,
				c.status,
				c.created_time,
				e.cr_name_1 borrower_name,
				e.cr_addr_1 borrower_home_address1,
				e.cr_handphone borrower_handphone1,
				e.cr_home_phone borrower_home_phone1"
			);
			$this->builder->join("cpcrd_new AS e", "e.cm_card_nmbr=c.contract_number");
			$this->builder->join("cms_team as o", "c.outbound_team_id = o.team_id");
			$this->builder->join("cms_contact_history b", "c.collection_history_id = b.id");
			$this->builder->join("cc_user f", "f.id=b.created_by");
			$this->builder->whereIn("c.collection_result", ['STF']);
			$this->builder->where("c.status", "OPEN");
			$this->builder->orderBy("c.created_time", "asc");
			$rResult = $this->builder->get();
			$return = $rResult->getResultArray();
			$i=0;
			if ($rResult->getNumRows() > 0) {
				foreach ($return as &$row) {
					$id = $i+1;
					$row['action'] = '<button class="btn btn-sm btn-success" onClick="showDataDebitur(\''.$row['collection_history_id'].'\',\''.$row['contract_number'].'\',\''.$row['id_performance'].'\',\''.$row['collection_result'].'\','.$id.')" >view</button>';
					$i++;
				}
				unset($row);
				foreach ($rResult->getResultArray()[0] as $key => $value) {
					$result['header'][] = array('field' => $key);
				}
				$result['data'] = $return;
			} else {
				$result['header'] = array();
				$result['data'] = array();
			}
			$rs =  $result;
			return $rs;
		}
    }
}