<?php
namespace App\Modules\ReportPengajuanDiskon\models;
use CodeIgniter\Model;

Class Report_pengajuan_diskon_model Extends Model 
{
    function get_report_pengajuan_diskon(){
        $this->builder = $this->db->table('cms_pengajuan_diskon a');

        $this->builder->select([
            "a.id",
			"a.cm_card_nmbr",
			"b.CR_NAME_1",
			"a.product_id",
			"FORMAT(b.CM_AMOUNT_DUE,0) outstanding_awal",
			"FORMAT(a.principle_balance,0) outstanding_pengajuan",
			"a.`status`",
			"date(a.created_time) request_date",
			"c.name request_by",
			"if(a.deviation_flag='1','YES','NO') deviation_flag",
			"d.deviation_name",
			"a.ptp_date",
			"FORMAT(a.amount_ptp,0) amount_ptp",
			"'' amt_diskon_tunggakan_pokok",
			"'' diskon_tunggakan_pokok",
			"'' amt_diskon_saldo_pokok",
			"'' diskon_saldo_pokok",
			"FORMAT(a.amount_interest_discount,0) amt_diskon_saldo_bunga",
			"a.discount_amount_interest diskon_saldo_bunga",
			"'' total_outstanding",
			"'' disc_total_outstanding",
			"(select bb.name from cms_call_verification aa join cc_user bb on aa.created_by=bb.id where aa.id_pengajuan=a.id  order by aa.created_time desc limit 1) verif_by",
			"(select date(aa.created_time) from cms_call_verification aa where aa.id_pengajuan=a.id  order by aa.created_time desc limit 1) verif_date",
			"(select bb.name from cms_upload_document_history aa join cc_user bb on aa.created_by=bb.id where aa.id_pengajuan=a.id  order by aa.created_time desc limit 1) check_by",
			"(select date(aa.created_time) from cms_call_verification aa where aa.id_pengajuan=a.id  order by aa.created_time desc limit 1) check_date",
			"ee.created_by approval1by",
			"ee.created_time approval1date",
			"ff.created_by approval2by",
			"ff.created_time approval2date",
			"gg.created_by approval3by",
			"gg.created_time approval3date",
			"hh.created_by approval4by",
			"hh.created_time approval4date",
			"ii.created_by approval5by",
			"ii.created_time approval5date",
			"'' payment_amount"
        ]);

        $this->builder->join('cpcrd_new b', 'b.CM_CARD_NMBR=a.cm_card_nmbr');
		$this->builder->join('cc_user c', 'a.created_by=c.id');
		$this->builder->join('cms_deviation_reference d', 'd.deviation_id=a.deviation_reason', 'left');
		$this->builder->join('cms_approval ee', 'ee.id_pengajuan=a.id and ee.approval_level="1"', 'left');
		$this->builder->join('cms_approval ff', 'ff.id_pengajuan=a.id and ff.approval_level="2"', 'left');
		$this->builder->join('cms_approval gg', 'gg.id_pengajuan=a.id and gg.approval_level="3"', 'left');
		$this->builder->join('cms_approval hh', 'hh.id_pengajuan=a.id and hh.approval_level="4"', 'left');
		$this->builder->join('cms_approval ii', 'ii.id_pengajuan=a.id and ii.approval_level="5"', 'left');
        $this->builder->orderBy('a.created_time', 'desc');

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