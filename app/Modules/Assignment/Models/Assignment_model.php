<?php
namespace App\Modules\Assignment\models;
use CodeIgniter\Model;

Class Assignment_model Extends Model 
{

    function get_account_unassignment_to_dc_list($user_id){
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
            '"" AS list_number',
			'CM_DOMICILE_BRANCH',
			'CM_CUSTOMER_NMBR',
			'CR_NAME_1',
			'CM_CARD_NMBR',
			'"" as CM_SUB_PRDK_CTG',//'CM_SUB_PRDK_CTG',
			'CM_DTE_LIQUIDATE',
			'CM_DTE_PYMT_DUE',
			'CM_BUCKET',
			'"" as CR_LIMIT',//'CR_LIMIT',
			'CM_OS_BALANCE',
			'DPD',
			'CM_COLLECTIBILITY',
			'CM_STATUS',
			'CM_RESTRUCTURE_FLAG',
			"CONCAT(CR_ADDR_1,'',CR_ADDR_3,'',CR_ADDR_4,'',CR_ADDR_5) as CR_ADDR",
			'CR_ZIP_CODE',
			'"" as CM_OFFICE_NAME'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->where("agent_id", $user_id);
		
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
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
    function get_account_visit_to_dc_list($user_id){
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
            '"" AS list_number',
			'CM_DOMICILE_BRANCH',
			'CM_CUSTOMER_NMBR',
			'CR_NAME_1',
			'CM_CARD_NMBR',
			'"" as CM_SUB_PRDK_CTG',//'CM_SUB_PRDK_CTG',
			'CM_DTE_LIQUIDATE',
			'CM_DTE_PYMT_DUE',
			'CM_BUCKET',
			'"" as CR_LIMIT',//'CR_LIMIT',
			'CM_OS_BALANCE',
			'a.DPD',
			'CM_COLLECTIBILITY',
			'CM_STATUS',
			'CM_RESTRUCTURE_FLAG',
			"CONCAT(CR_ADDR_1,'',CR_ADDR_3,'',CR_ADDR_4,'',CR_ADDR_5) as CR_ADDR",
			'CR_ZIP_CODE',
			'"" as CM_OFFICE_NAME',//CM_OFFICE_NAM
			'lov1',
			'lov2',
			'lov3',
			'lov4',
			'lov5',
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->join('cms_contact_history as b','a.CM_CARD_NMBR = b.account_no');
		$this->builder->where("agent_id", $user_id);
		$this->builder->where("input_source", 'MOBCOLL');
		$this->builder->where("lov1 != ''");
		$this->builder->where("date(created_time) = CURDATE()");
		$this->builder->groupBy("CM_CARD_NMBR");
		
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
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
    function get_account_ptp_to_dc_list($user_id){
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
            '"" AS list_number',
			'CM_DOMICILE_BRANCH',
			'CM_CUSTOMER_NMBR',
			'CR_NAME_1',
			'CM_CARD_NMBR',
			'"" as CM_SUB_PRDK_CTG',//'CM_SUB_PRDK_CTG',
			'CM_DTE_LIQUIDATE',
			'CM_DTE_PYMT_DUE',
			'CM_BUCKET',
			'"" as CR_LIMIT',//'CR_LIMIT',
			'CM_OS_BALANCE',
			'a.DPD',
			'CM_COLLECTIBILITY',
			'CM_STATUS',
			'CM_RESTRUCTURE_FLAG',
			"CONCAT(CR_ADDR_1,'',CR_ADDR_3,'',CR_ADDR_4,'',CR_ADDR_5) as CR_ADDR",
			'CR_ZIP_CODE',
			'"" as CM_OFFICE_NAME',//CM_OFFICE_NAM
			'lov1',
			'lov2',
			'lov3',
			'lov4',
			'lov5',
			'ptp_date',
			'ptp_amount'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->join('cms_contact_history as b','a.CM_CARD_NMBR = b.account_no');
		$this->builder->where("agent_id", $user_id);
		$this->builder->where("input_source", 'MOBCOLL');
		$this->builder->where("date(created_time) = CURDATE()");
		$this->builder->where("ptp_date >= CURDATE()");
		$this->builder->groupBy("CM_CARD_NMBR");
		
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
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
    function get_account_ptp_amount_to_dc_list($user_id){
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
            '"" AS list_number',
			'CM_DOMICILE_BRANCH',
			'CM_CUSTOMER_NMBR',
			'CR_NAME_1',
			'CM_CARD_NMBR',
			'"" as CM_SUB_PRDK_CTG',//'CM_SUB_PRDK_CTG',
			'CM_DTE_LIQUIDATE',
			'CM_DTE_PYMT_DUE',
			'CM_BUCKET',
			'"" as CR_LIMIT',//'CR_LIMIT',
			'CM_OS_BALANCE',
			'a.DPD',
			'CM_COLLECTIBILITY',
			'CM_STATUS',
			'CM_RESTRUCTURE_FLAG',
			"CONCAT(CR_ADDR_1,'',CR_ADDR_3,'',CR_ADDR_4,'',CR_ADDR_5) as CR_ADDR",
			'CR_ZIP_CODE',
			'"" as CM_OFFICE_NAME',//CM_OFFICE_NAM
			'lov1',
			'lov2',
			'lov3',
			'lov4',
			'lov5',
			'ptp_date',
			'ptp_amount'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->join('cms_contact_history as b','a.CM_CARD_NMBR = b.account_no');
		$this->builder->where("agent_id", $user_id);
		$this->builder->where("input_source", 'MOBCOLL');
		$this->builder->where("lov3 = 'PAY VIA FC'");
		$this->builder->where("date(created_time) = CURDATE()");
		$this->builder->where("ptp_date >= CURDATE()");
		$this->builder->groupBy("CM_CARD_NMBR");
		
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
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

	function get_class_work_assignment_by($class_id){
		
		$sql = "SELECT 
							a.class_mst_id,
							b.classification_name name,
							a.outbound_team 
						FROM acs_class_work_assignment as a 
						LEFT JOIN cms_classification as b ON a.class_mst_id=b.classification_id
						WHERE a.class_mst_id= ? 
						ORDER BY b.classification_name ASC";
		$row	= $this->db->query($sql, [$class_id]);
		$data	= $row->getResultArray();

		return $data;
	}


	function get_outbound_team_not_in_outbound($class_id) {
		$sql	= "SELECT team_id id , team_name outbound_team_name FROM cms_team WHERE team_id NOT IN (select outbound_team from acs_class_work_assignment WHERE class_mst_id <> ?) order by team_name ASC"; 
		
		$row	= $this->db->query($sql,[$class_id]);
		$data	= $row->getResultArray();

		return $data;
	}

    
}