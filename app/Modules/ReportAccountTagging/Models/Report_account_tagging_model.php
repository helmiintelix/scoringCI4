<?php
namespace App\Modules\ReportAccountTagging\models;
use CodeIgniter\Model;

Class Report_account_tagging_model Extends Model 
{
    function get_account_tagging_list(){
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
            'a.CM_CUSTOMER_NMBR', 
            'a.CR_NAME_1', 
            'a.CM_CARD_NMBR', 
            'a.ACCOUNT_TAGGING', 
            'DATE_FORMAT(account_tagging_time,"%d-%M-%Y") account_tagging_time', 
            'a.CM_STATUS_DESC', 
            'a.CM_TYPE', 
            'a.CM_DOMICILE_BRANCH', 
            'a.CM_CYCLE', 
            'DATE_FORMAT(a.CM_DTE_PYMT_DUE,"%d-%M-%Y")CM_DTE_PYMT_DUE', 
            'a.CM_BUCKET', 
            'a.CM_TOT_BALANCE', 
            'a.CM_BLOCK_CODE', 
            'DATE_FORMAT(a.CM_DTE_BLOCK_CODE,"%d-%M-%Y")CM_DTE_BLOCK_CODE', 'a.CLASS'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cms_account_last_status b', 'b.account_no = a.CM_CUSTOMER_NMBR', 'left');
		$this->builder->where('a.ACCOUNT_TAGGING is not null');
        $this->builder->orderBy('a.CR_NAME_1', 'ASC');
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
        // return $return;
    }

}