<?php
namespace App\Modules\UploadAccountData\models;
use CodeIgniter\Model;

Class UploadAccountData_model Extends Model 
{
    function get_upload_data(){
        $select = [
            'a.id', 
            'a.fileName', 
            'a.createdBy', 
            'a.createdTime', 
            'a.approvedBy', 
            'a.approvedTime', 
        ];

        $this->builder = $this->db->table('upload_account_data a');
        $this->builder->select($select);
       
        $this->builder->orderBy('createdBy', 'DESC');
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
    
    function get_view_data($uploadId){
        $select = [
            "CM_CUSTOMER_NMBR", 
            "CM_CARD_NMBR", 
            "CM_TYPE", 
            "CM_CRLIMIT", 
            "CM_CREDIT_LINE", 
            "CM_DTE_OPENED", 
            "CM_INTEREST", 
            "CM_TENOR", 
            "FLD_DATE_5", 
            "MOB", 
            "CM_DTE_LIQUIDATE", 
            "CM_CURRENCY_CODE", 
            "CM_HOLD_AMOUNT", 
            "CM_AO_CODE", 
            "CM_OFFICER_NAME", 
            "CM_SECTOR_CODE", 
            "CM_SECTOR_DESC", 
            "CM_DTE_PK", 
            "CM_CARD_EXPIR_DTE", 
            "CM_INSTALLMENT_AMOUNT", 
            "CM_INSTL_BAL", 
            "CM_INTR_PER_DIEM", 
            "CM_INSTL_LIMIT", 
            "CM_AMNT_OUTST_INSTL", 
            "CM_TOTALDUE", 
            "CM_CYCLE", 
            "CM_INSTALLMENT_NO", 
            "CM_DTE_PYMT_DUE", 
            "DPD", 
            "CM_BUCKET_PROGRAM", 
            "FLD_CHAR_2", 
            "CM_STATUS", 
            "CM_COLLECTIBILITY", 
            "CM_BLOCK_CODE", 
            "CM_DTE_BLOCK_CODE", 
            "CM_OS_BALANCE", 
            "CM_OS_PRINCIPAL", 
            "CM_OS_INTEREST", 
            "CM_RTL_MISC_FEES", 
            "CM_TOTAL_OS_AR", 
            "CM_CHGOFF_STATUS_FLAG", 
            "CM_DTE_CHGOFF_STAT_CHANGE", 
            "SUM_WO_BALANCE", 
            "CM_CHGOFF_PRICIPLE", 
            "CM_ZIP_REC", 
            "CM_DELQ_COUNTER1", 
            "CM_DELQ_COUNTER2", 
            "CM_DELQ_COUNTER3", 
            "CM_DELQ_COUNTER4", 
            "CM_DELQ_COUNTER5", 
            "CM_DELQ_COUNTER6", 
            "CM_DELQ_COUNTER7", 
            "CM_DELQ_COUNTER8", 
            "CM_DELQ_COUNTER9", 
            "CM_CURR_DUE", 
            "CM_PAST_DUE", 
            "CM_30DAYS_DELQ", 
            "CM_60DAYS_DELQ", 
            "CM_90DAYS_DELQ", 
            "CM_120DAYS_DELQ", 
            "CM_150DAYS_DELQ", 
            "CM_180DAYS_DELQ", 
            "CM_210DAYS_DELQ", 
            "CM_RESTRUCTURE_FLAG", 
            "CM_DTE_RESTRUCTURE", 
            "CM_DTE_LST_PYMT", 
            "CM_STATUS_DESC", 
            "CM_LST_PYMT_AMNT", 
            "CM_PAID_PRICIPAL", 
            "CM_PAID_INTEREST", 
            "CM_PAID_CHARGE", 
            "CM_SOURCE_CODE", 
            "CR_ACCT_NBR", 
            'file_upload_id'
        ];

        $this->builder = $this->db->table('cpcrd_new_upload_temp a');
        $this->builder->select($select);
        $this->builder->where("file_upload_id",$uploadId);
       
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

    function get_upload_data_approval(){
        $select = [
            'a.id', 
            'a.fileName', 
            'a.createdBy', 
            'a.createdTime', 
            'a.approvedBy', 
            'a.approvedTime', 
        ];

        $this->builder = $this->db->table('upload_account_data a');
        $this->builder->select($select);
        $this->builder->where("approvedBy is null");

       
        $this->builder->orderBy('createdTime', 'DESC');
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
}