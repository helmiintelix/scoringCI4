<?php
namespace App\Modules\FieldcollAndAgencyReassignment\models;
use CodeIgniter\Model;

Class Fieldcoll_and_agency_reassignment_model Extends Model 
{
    function get_reassignment_list(){
        $this->builder = $this->db->table('cms_contact_history');
        $this->builder->select('account_no');
		$this->builder->where('ptp_date >= curdate()');
		$this->builder->like('lov3', 'PTP', 'both');
		$this->builder->groupBy('account_no');
		$PTP_account = $this->builder->get()->getResultArray();

		$arr = array_column($PTP_account, 'account_no');
		$strArr = explode(",", implode(",", $arr));
		// print_r($strArr);
		// exit();

		$this->builder = $this->db->table('cpcrd_new a');
		$select = array(
            'concat(AGENT_ID,"-",name) AGENT_ID', 
			'AGENCY_ID', 
			'c.remarks', 
			'c.assigned_fc assigned_fc', 
			'c.collector_type collector_type', 
			'CM_CUSTOMER_NMBR', 
			'CR_NAME_1', 
			'CM_CARD_NMBR', 
			'CM_STATUS_DESC', 
			'CM_TYPE', 
			'CM_DOMICILE_BRANCH', 
			'CM_CYCLE', 
			'DATE_FORMAT(CM_DTE_PYMT_DUE,"%d-%M-%Y") CM_DTE_PYMT_DUE', 
			'CM_BUCKET', 
			'CM_TOT_BALANCE', 
			'CM_BLOCK_CODE', 
			'DATE_FORMAT(CM_DTE_BLOCK_CODE,"%d-%M-%Y") CM_DTE_BLOCK_CODE', 
			'CLASS', '
			DATE_FORMAT(assign_tmp_from,"%d-%M-%Y") assign_tmp_from', 
			'DATE_FORMAT(assign_tmp_until,"%d-%M-%Y") assign_tmp_until'
		);
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->join('cc_user b', 'a.AGENT_ID=b.id', 'LEFT');
		$this->builder->join('cms_assignment c', 'a.CM_CARD_NMBR=c.no_rekening', 'LEFT');
		$this->builder->whereNotIn('a.CM_CARD_NMBR', $strArr);
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
    function save_account_reassignment_request($data){
        $this->builder = $this->db->table('cms_reassignment_temp');
        $return = $this->builder->insert($data);
        return $return;
    }
}