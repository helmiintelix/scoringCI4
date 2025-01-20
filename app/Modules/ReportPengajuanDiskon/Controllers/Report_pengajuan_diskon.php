<?php 
namespace App\Modules\ReportPengajuanDiskon\Controllers;
use App\Modules\ReportPengajuanDiskon\Models\Report_pengajuan_diskon_model;
use CodeIgniter\Config\Services;
use CodeIgniter\Cookie\Cookie;


class Report_pengajuan_diskon extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Report_pengajuan_diskon_model = new Report_pengajuan_diskon_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\ReportPengajuanDiskon\Views\Report_pengajuan_diskon_view', $data);
	}
	function get_report_pengajuan_diskon(){
		$cache = session()->get('USER_ID').'_report_pengajuan_diskon';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Report_pengajuan_diskon_model->get_report_pengajuan_diskon();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function view_detail_diskon_lunas()
	{
		$security = Services::security();
		$data['card_no'] =  $security->sanitizeFilename($this->input->getGet('account_id'));
		$data['id_pengajuan'] =  $this->input->getGet('id');
		$for =  $this->input->getGet('for');
		$id =  $security->sanitizeFilename($this->input->getGet('id'));


		$product_id	= $this->Common_model->get_record_value("PRODUCT_ID", "cpcrd_new", "CM_CARD_NMBR='" . $data['card_no'] . "'");
		// $data["customer_data"]	= $this->Common_model->get_record_values("*", "cms_account_last_status", "account_no='".$data['card_no']."'");
		$data["credit_data"]	= $this->Common_model->get_record_values("*", "cpcrd_new", "CM_CARD_NMBR='" . $data['card_no'] . "'");
		$data["TL_ID"] = "";
		if (!empty($data["credit_data"]['AGENT_ID'])) {
			$data["TL_ID"] =   $this->Common_model->get_record_value("team_leader", "cms_team", " agent_list LIKE '%" .  $data["credit_data"]['AGENT_ID'] . "%' ORDER BY created_time desc limit 1");
		}
		//$data["discount_data"]	= $this->Common_model->get_record_values("*", "cms_discount_request", "CARD_NMBR='".$data['card_no']."' AND STATUS='OPEN'");
		$data["reason_list"] = array("" => "[select reason]") + $this->Common_model->get_record_list("value, description AS item", "cms_reference", "flag_tmp = '1' AND flag = '1' AND reference = 'DISCOUNT_REASON'", "order_num");
		$data["xpac_list"] = array("" => "[select new XPAC]") + $this->Common_model->get_record_list("value, description AS item", "cms_reference", "flag_tmp = '1' AND flag = '1' AND reference = 'BLOCK_CODE'", "order_num");
		$data["screen_level"] = "EDIT";

		$data["pengajuan_data"]	= $this->Common_model->get_record_values("*", "cms_pengajuan_diskon", "id='" . $data['id_pengajuan'] . "'");
		$data["parameter_diskon"]	= $this->Common_model->get_record_values("*", "cms_discount_parameter_tmp", "id_pengajuan='" . $data['id_pengajuan'] . "'");



		$data['kondisi_khusus_list'] = $this->Common_model->get_ref_master('value , CONCAT( value , " - " , description) item', 'cms_reference', 'reference="KONDISI_KHUSUS_' . $product_id . '" and flag="1" and flag_tmp="1" ', 'order_num asc');


		$data["contact_result"] = array("" => "[select contact result]") + $this->Common_model->get_record_list("value, description AS item", "cms_reference", "flag_tmp = '1' AND flag = '1' AND reference = 'CONTACT_RESULT_VERIFICATION'", "order_num");
		$data["result_call"] = array("" => "[select call result]") + $this->Common_model->get_record_list("value, description AS item", "cms_reference", "flag_tmp = '1' AND flag = '1' AND reference = 'RESULT_CALL_VERIFICATION'", "order_num");


		$flag_verif_call	= $this->Common_model->get_record_value("call_verification_status", "cms_pengajuan_diskon", "id='" . $data['id_pengajuan'] . "'");
		$flag_verif_upload	= $this->Common_model->get_record_value("document_upload_status", "cms_pengajuan_diskon", "id='" . $data['id_pengajuan'] . "'");

		$data["flag_verif_call"] = $flag_verif_call;
		$data["flag_verif_upload"] = $flag_verif_upload;

		$data["call_history_data"] = '';
		if ($flag_verif_call == 'FINISH') {
			$data["call_history_data"]	= $this->Common_model->get_record_values("*", "cms_call_verification", "agreement_no='" . $data['card_no'] . "'");
		}

		$data["checker_call_confirm"] =  $this->Common_model->get_record_list("value, description AS item", "cms_reference", "flag_tmp = '1' AND flag = '1' AND reference = 'CHECKER_CALL_CONFIRM'", "order_num");
		$data["checker_doc_confirm"] =  $this->Common_model->get_record_list("value, description AS item", "cms_reference", "flag_tmp = '1' AND flag = '1' AND reference = 'CHECKER_DOC_CONFIRM'", "order_num");
		$data["checker_doc_confirm2"] =  $this->Common_model->get_record_list("value, description AS item", "cms_reference", "flag_tmp = '1' AND flag = '1' AND reference = 'CHECKER_DOC_CONFIRM2'", "order_num");

		$data["upload_call_verification"]	= $this->Common_model->get_record_values("*", "cms_call_verification", "id_pengajuan='" . $data['id_pengajuan'] . "' and status='FINISH'");

		$data["upload_document_verification"]	= $this->Common_model->get_record_values("*", "cms_upload_document", "id_pengajuan='" . $data['id_pengajuan'] . "' ");

		$verif_call_checker =  $this->Common_model->get_record_value("call_verification_status", "cms_pengajuan_diskon", " id='" . $data['id_pengajuan'] . "' ");
		$data["flag_call_checker"] = '1'; //sudah di verif oleh checker

		if ($verif_call_checker == 'FINISH') {
			$data["check_call_checket"]	= $this->Common_model->get_record_values("*", "cms_upload_document_history", " tipe_document='CALL' AND file_document='CALL' and id_pengajuan='" . $data['id_pengajuan'] . "'  ");
			$data["flag_call_checker"] = '0'; //belum di verif oleh checker
		}


		$data["last_file_fotokopi_ktp"] = $this->Common_model->get_record_value("created_time", "cms_upload_document", "id_upload='file-fotokopi-ktp' and id_pengajuan='" . $data['id_pengajuan'] . "' ");
		$data["last_file_other_doc"] = $this->Common_model->get_record_value("created_time", "cms_upload_document", "id_upload='file-other-doc' and id_pengajuan='" . $data['id_pengajuan'] . "' ");
		$data["last_file_sid_slik"] = $this->Common_model->get_record_value("created_time", "cms_upload_document", "id_upload='file-sid-slik' and id_pengajuan='" . $data['id_pengajuan'] . "' ");
		$data["last_file_surat_permohonan_bermaterai"] = $this->Common_model->get_record_value("created_time", "cms_upload_document", "id_upload='file-surat-permohonan-bermaterai' and id_pengajuan='" . $data['id_pengajuan'] . "' ");
		$data["last_file_iias_print_out"] = $this->Common_model->get_record_value("created_time", "cms_upload_document", "id_upload='file-iias-print-out' and id_pengajuan='" . $data['id_pengajuan'] . "' ");
		$data["last_file_jadwal_pembayaran"] = $this->Common_model->get_record_value("created_time", "cms_upload_document", "id_upload='file-jadwal-pembayaran' and id_pengajuan='" . $data['id_pengajuan'] . "' ");

		$data["status_file_fotokopi_ktp"] = $this->Common_model->get_record_value("description", "cms_upload_document_history a join cms_reference b on a.value=b.value and b.reference = 'CHECKER_DOC_CONFIRM'", "file_document='file-fotokopi-ktp' and id_pengajuan='" . $data['id_pengajuan'] . "' ");
		$data["status_file_other_doc"] = $this->Common_model->get_record_value("description", "cms_upload_document_history a join cms_reference b on a.value=b.value and b.reference = 'CHECKER_DOC_CONFIRM'", "file_document='file-other-doc' and id_pengajuan='" . $data['id_pengajuan'] . "' ");
		$data["status_file_sid_slik"] = $this->Common_model->get_record_value("description", "cms_upload_document_history a join cms_reference b on a.value=b.value and b.reference = 'CHECKER_DOC_CONFIRM'", "file_document='file-sid-slik' and id_pengajuan='" . $data['id_pengajuan'] . "' ");
		$data["status_file_surat_permohonan_bermaterai"] = $this->Common_model->get_record_value("description", "cms_upload_document_history a join cms_reference b on a.value=b.value and b.reference = 'CHECKER_DOC_CONFIRM'", "file_document='file-surat-permohonan-bermaterai' and id_pengajuan='" . $data['id_pengajuan'] . "' ");
		$data["status_file_iias_print_out"] = $this->Common_model->get_record_value("description", "cms_upload_document_history a join cms_reference b on a.value=b.value and b.reference = 'CHECKER_DOC_CONFIRM'", "file_document='file-iias-print-out' and id_pengajuan='" . $data['id_pengajuan'] . "' ");
		$data["status_file_jadwal_pembayaran"] = $this->Common_model->get_record_value("description", "cms_upload_document_history a join cms_reference b on a.value=b.value and b.reference = 'CHECKER_DOC_CONFIRM'", "file_document='file-jadwal-pembayaran' and id_pengajuan='" . $data['id_pengajuan'] . "' ");


		$data['deviation_reason'] = $this->Common_model->get_ref_master('deviation_id value , deviation_name item', 'cms_deviation_reference', 'is_active="1" and product like "%PL%" ', 'deviation_name asc');



		$sql = "select a.approval_level, 
				if(a.`action`='REQUEST','Request By',
					if(a.`action`='VERIFICATION','Verified By',
						if(a.`action`='CHECKER','Checker By',
							if(a.`action`='APPROVAL',CONCAT('Aproval ',a.approval_level),'')
						)
					)
				)as action,
				a.`status`, b.name, date(a.created_time)  created_time
				from cms_approval a
				join cc_user b on a.created_by=b.id
				where a.id_pengajuan = ?
				and a.`status` ='APPROVE'
				order by a.approval_level asc ,a.created_time asc 
				";
		$data['arr_approval']  = $this->db->query($sql, array($data['id_pengajuan']))->result_array();

		$data['USER_ID'] = session()->get('USER_ID');

		if ($for == 'print') {
			return view('\App\Modules\ReportPengajuanDiskon\Views\detail_pengajuan_diskon_pelunasan_view', $data);
			
			// $this->generate_pdf($html);
		} else {
			return view('\App\Modules\ReportPengajuanDiskon\Views\detail_pengajuan_diskon_pelunasan_view', $data);
		}
	}

}