<?php
namespace App\Modules\LaporanVisitFc\models;
use CodeIgniter\Model;

Class LaporanVisitFc_model Extends Model 
{
    function get_report_visit_fc($loan_number){
        $select = [
            'a.id', 
            'b.name as userId', 
            'a.notes',
            'IFNULL(c.description, a.action_code) as actionCode',
            'contact_code',
            '(SELECT category_name FROM cms_lov_registration WHERE id = lov1) as lov1',
            '(SELECT category_name FROM cms_lov_registration WHERE id = lov2) as lov2',
            '(SELECT category_name FROM cms_lov_registration WHERE id = lov3) as lov3',
            '(SELECT category_name FROM cms_lov_registration WHERE id = lov4) as lov4',
            'phone_type as phoneType',
            'COALESCE(a.phone_no, (SELECT bb.borrower_phone FROM cms_temp_phone bb WHERE bb.source="mobcoll" AND bb.contract_number=a.account_no AND bb.created_by=a.created_by limit 1)) AS phone',
            'call_status as callStatus', 'ptp_date as ptpDate', 'ptp_amount as ptpAmount', 'ptp_status as ptpStatus',
            'a.input_source as inputSource', 'a.created_time as CreatedTime', 'a.phone_no as phoneNo',
            'IFNULL(d.description, a.place_code) as placeCode',
            'IFNULL(d.description, a.next_action) as nextAction',
            'IFNULL(e.description, a.reason) as reason',
            'CR_NAME_1 as customerName', 'account_no as accountNo'
        ];

        $this->builder = $this->db->table('cms_contact_history a');
        $this->builder->select($select);
        $this->builder->join('cc_user b', 'a.user_id = b.id');
        $this->builder->join('cms_reference c', 'a.action_code = c.value AND c.reference = "ACTION_CODE"', 'left');
        $this->builder->join('cms_reference d', 'a.next_action = d.value AND d.reference = "NEXT_ACTION"', 'left');
        $this->builder->join('cms_reference e', 'a.reason = e.value AND e.reference = "REASON_CODE"', 'left');
        $this->builder->join('cms_reference f', 'a.reason = f.value AND f.reference = "PLACE_CODE"', 'left');
        $this->builder->join('cpcrd_new g', 'a.account_no = g.CM_CARD_NMBR');
        $this->builder->where('input_source', 'mobcoll');

        if ($loan_number) {
            $this->builder->where('g.cm_card_nmbr', $loan_number);
        }
        $this->builder->orderBy('g.cm_card_nmbr', 'DESC');
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