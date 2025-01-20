<?php
namespace App\Modules\LaporanAuditPerdebitur\models;
use CodeIgniter\Model;

Class Laporan_audit_perdebitur_model Extends Model 
{
    function get_report_audit_perdebitur_list($loan_number){
        $this->builder = $this->db->table('cms_contact_history a');
        $select = array(
			'a.id,
            a.input_source,
            cm_card_nmbr,
            DATE(b.created_time) AS collection_date,
            TIME(b.created_time) AS collection_time,
            b.name AS handling_by,
            (select category_name from cms_lov_registration where id = lov1) as lov1,
            (select category_name from cms_lov_registration where id = lov2) as lov2,
            (select category_name from cms_lov_registration where id = lov3) as lov3,
            (select category_name from cms_lov_registration where id = lov4) as lov4,
            ptp_date,
            ptp_amount,
            a.notes'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cc_user b', 'a.created_by = b.id');
		$this->builder->join('cpcrd_new g', 'a.account_no = g.CM_CARD_NMBR');
		$this->builder->where("input_source in ('MOBCOLL','PHONE')");
        if ($loan_number) {
            $this->builder->where('cm_card_nmbr', $loan_number);
        }
        $this->builder->orderBy('cm_card_nmbr', 'DESC');
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