<?php 
namespace App\Modules\DetailAccount\Controllers;
use App\Modules\DetailAccount\Models\Detail_account_model;
use CodeIgniter\Cookie\Cookie;
use Config\Database;

class Detail_account extends \App\Controllers\BaseController
{
	protected $cti;
	function __construct()
	{
		$this->Detail_account_model = new Detail_account_model();
        $this->cti = Database::connect('cti');

	}

	function index(){
		$account_id = $this->input->getGet('account_id');
		$cm_card_nmbr = $this->input->getGet('cm_card_nmbr');
		$history_id = $this->input->getGet('history_id');
		$approval = $this->input->getGet('approval');

		// dd($account_id);

		if($account_id == "preview"){
			$account_id = $this->Common_model->get_record_value("CM_CARD_NMBR","cpcrd_new","AGENT_ID='".$cm_card_nmbr."'");
		}
		
		$cache = date('Ymd').'_'.$account_id;
		$data = array();

		if ($output = cache($cache)) {
			
			$data = $output ;
		}else{
			$builder = $this->db->table('cpcrd_new');
			$builder->select("CM_ORG_NMBR, CM_TYPE, cpcrd_new.CM_CARD_NMBR, CM_SHORT_NAME, CM_CUSTOMER_ORG, CM_CUSTOMER_NMBR,
			CM_ACH_RT_NMBR, CM_SAVINGS_ACCT, CM_DOMICILE_BRANCH, CR_ZIP_CODE, CM_OLC_REASON, CM_STATUS,
			date_format(CM_CARD_EXPIR_DTE, '%d-%m-%Y') AS CM_CARD_EXPIR_DTE,
			CM_DELQ_COUNTER1, CM_DELQ_COUNTER2, CM_DELQ_COUNTER3, CM_DELQ_COUNTER4, CM_DELQ_COUNTER5, CM_DELQ_COUNTER6, CM_DELQ_COUNTER7,
			CM_DELQ_COUNTER8,
			format(CM_CURR_DUE, 0) AS CM_CURR_DUE,
			format(CM_PAST_DUE, 0) AS CM_PAST_DUE,
			format(CM_30DAYS_DELQ, 0) AS CM_30DAYS_DELQ,
			format(CM_60DAYS_DELQ, 0) AS CM_60DAYS_DELQ,
			format(CM_90DAYS_DELQ, 0) AS CM_90DAYS_DELQ,
			format(CM_120DAYS_DELQ, 0) AS CM_120DAYS_DELQ,
			format(CM_150DAYS_DELQ, 0) AS CM_150DAYS_DELQ,
			format(CM_180DAYS_DELQ, 0) AS CM_180DAYS_DELQ,
			format(CM_210DAYS_DELQ, 0) AS CM_210DAYS_DELQ,
			format(CM_AMOUNT_DUE, 0) AS CM_AMOUNT_DUE,
			format(CM_RTL_BEG_BALANCE, 0) AS CM_RTL_BEG_BALANCE,
			format(CM_CASH_BEG_BALANCE, 0) AS CM_CASH_BEG_BALANCE,
			format(CM_TOT_BEG_BALANCE, 0) AS CM_TOT_BEG_BALANCE,
			format(CM_CRLIMIT, 0) AS CM_CRLIMIT,
			format(CM_OL_AMT_DUE, 0) AS CM_OL_AMT_DUE,
			format(CM_RTL_BALANCE, 0) AS CM_RTL_BALANCE,
			format(CM_CASH_BALANCE, 0) AS CM_CASH_BALANCE,
			format(CM_TOT_BALANCE, 0) AS CM_TOT_BALANCE,
			date_format(CM_DTE_LST_STMT, '%d-%m-%Y') AS CM_DTE_LST_STMT,
			format(CM_RTL_AMNT_CHARGE_OFF, 0) AS CM_RTL_AMNT_CHARGE_OFF,
			format(CM_CASH_AMNT_CHARGE_OFF, 0) AS CM_CASH_AMNT_CHARGE_OFF,
			format(CM_TOT_AMNT_CHARGE_OFF, 0) AS CM_TOT_AMNT_CHARGE_OFF,
			date_format(CM_DTE_CHGOFF_STAT_CHANGE, '%d-%m-%Y') AS CM_DTE_CHGOFF_STAT_CHANGE,
			CM_CHGOFF_RSN_CD_1,
			CM_CHGOFF_RSN_CD_2, CM_CHGOFF_STATUS_FLAG, CM_WRITE_OFF_DAYS,
			CM_BLOCK_CODE, CM_DTE_BLOCK_CODE, CM_ALT_BLOCK_CODE, CM_DTE_ALT_BLOCK_CODE,
			CR_EU_CUSTOMER_CLASS, CR_NAME_1, CR_NAME_2, CR_NAME_3, CM_EMBOSSER_NAME_1,
			CR_FOREIGN_COUNTRY_IND, if(CR_EU_SEX = 'F', 'P', 'L') AS CR_EU_SEX, CM_DTE_LST_PYMT, CM_ACH_DB_NMBR, CM_CYCLE,
			date_format(CM_DTE_INTO_COLLECTION, '%d-%m-%Y') AS CM_DTE_INTO_COLLECTION, CR_BUSINESS_CODE,
			date_format(CM_DTE_LST_PURCHASE, '%d-%m-%Y') AS CM_DTE_LST_PURCHASE,
			format(CM_AMNT_LST_PURCH, 0) AS CM_AMNT_LST_PURCH,
			date_format(CM_DTE_LST_ADVANCE, '%d-%m-%Y') AS CM_DTE_LST_ADVANCE,
			format(CM_AMNT_LST_ADV, 0) AS CM_AMNT_LST_ADV,
			CM_REAGE_REQUEST, 'CM_BLOCKED_REASON',
			date_format(CM_DTE_LST_REAGE, '%d-%m-%Y') AS CM_DTE_LST_REAGE, CR_APPL_ID,
			date_format(CM_DTE_OPENED, '%d-%m-%Y') AS CM_DTE_OPENED,
			format(CM_CASH_IBNP, 0) AS CM_CASH_IBNP,
			format(CM_CASH_SVC_BNP, 0) AS CM_CASH_SVC_BNP,
			format(CM_RTL_IBNP, 0) AS CM_RTL_IBNP,
			format(CM_RTL_SVC_BNP, 0) AS CM_RTL_SVC_BNP,
			format(CM_RTL_MISC_FEES, 0) AS CM_RTL_MISC_FEES,
			format(CM_RTL_INSUR_BNP, 0) AS CM_RTL_INSUR_BNP,
			format(CM_RTL_MEMBER_BNP, 0) AS CM_RTL_MEMBER_BNP,
			format(CM_TOT_RTL_PRINCIPAL, 0) AS CM_TOT_RTL_PRINCIPAL,
			format(CM_TOT_CASH_PRINCIPAL, 0) AS CM_TOT_CASH_PRINCIPAL,
			format(CM_TOT_PRINCIPAL, 0) AS CM_TOT_PRINCIPAL,
			format(CM_AVAIL_CREDIT, 0) AS CM_AVAIL_CREDIT,
			format(CM_HI_BALANCE, 0) AS CM_HI_BALANCE, FLD_CHAR_15,
			date_format(CM_DTE_HI_BALANCE, '%d-%m-%Y') AS CM_DTE_HI_BALANCE, CM_POSTING_FLAG, CM_POSTING_ACCT_ORGN,
			CM_POSTING_ACCT_TYPE, CM_POSTING_ACCT_NMBR, CM_USER_CODE_2, CM_OLC_REASON_DESC,
			CM_CHGOFF_STATUS_FLAG_DESC, CM_STATEMENT_FLAG_DESC, CM_STATUS_DESC, CM_BUCKET,
			CR_ORG_NBR, CR_ACCT_NBR, CR_ADDR_1, CR_ADDR_2, CR_ADDL_ADDR_1, CR_ADDL_ADDR_2, CR_ADDL_ADDR_3, CR_ADDL_USAGES1,
			CR_HOME_PHONE, CR_OFFICE_PHONE, CR_HANDPHONE, cpcrd_new.CR_HANDPHONE2, CR_GUARANTOR_PHONE, b.CM_SPOUSE_PHONE, b.CR_EC_PHONE, CR_CO_OWNER, CR_CO_HOME_PHONE, CR_CO_OFFICE_PHONE, CR_CITY,
			date_format(CM_DTE_PYMT_DUE, '%d-%m-%Y') AS CM_DTE_PYMT_DUE,
			date_format(CR_DTE_BIRTH, '%d-%m-%Y') AS CR_DTE_BIRTH,
			CR_CO_EMPLOYER, CR_EMPLOYER, CR_EU_SURN, CREDIT_SCORING,
			CR_MEMO_1, CR_MEMO_2, CR_ADDL_EMAIL, CR_PLACE_OF_BIRTH, CR_TAX_ID_NMBR, CR_ADDL_ZIP_EXP, if(CR_ADDL_USAGES2 = 0, 'Rumah', 'Kantor') AS CR_ADDL_USAGES2,
			if(CR_ADDL_USAGES3 = 0, 'Rumah', 'Kantor') AS CR_ADDL_USAGES3, if(CR_ADDL_USAGES4 = 0, 'Rumah', 'Kantor') AS CR_ADDL_USAGES4, if(CR_ADDL_USAGES5 = 0, 'Rumah', 'Kantor') AS CR_ADDL_USAGES5, CR_ADDL_USAGES6, CR_ADDL_USAGES7, CR_ADDL_USAGES8, CR_ADDL_USAGES9,
			CR_ADDL_USAGES10, CR_ADDL_USAGES11, CR_ADDL_USAGES12, '' AS CR_C_ADDR_L1, '' AS CR_C_ADDR_L2, '' AS CR_C_ADDR_L3, '' AS CR_C_ADDR_L4, '' AS CR_C_ADDR_L5, CR_C_ZIP_CODE, cpcrd_new.CR_MOTHER_NM, BILLING_ADDR_CODE, CF_AGENCY_STATUS_ID,
			'' AS IC_NO, '' AS CUST_CIF, '' AS fin_account,
			if(CR_ADDL_USAGES1 = 0, 'Rumah', 'Kantor') AS CR_ADDL_USAGES1_DESC, DATEDIFF(CURDATE(), CM_DTE_PYMT_DUE) AS DPD, cpcrd_new.ACCOUNT_TAGGING, last_notes, hot_notes, AGENT_ID, CF_ACCOUNT_GROUP, status_pengajuan, date_format(tgl_pengajuan, '%d-%M-%Y') AS tgl_pengajuan");
			$builder->join('cms_account_last_status', 'cpcrd_new.CM_CARD_NMBR=cms_account_last_status.account_no ', 'left');
			$builder->join('cpcrd_ext b', 'cpcrd_new.CM_CARD_NMBR = b.CM_CARD_NMBR', 'left');
			$builder->where('cpcrd_new.CM_CARD_NMBR', $account_id);
			
			$data = $builder->get()->getRowArray();
			cache()->save($cache,$data,env('TIMECACHE_2'));
		}
	
		$data['approval'] = $approval;
		$data['history_id'] = $history_id;

		$data["ageing_obligor"] = $data['CM_BUCKET'];
		$data["username"] = session()->get('USER_NAME');
		$data["group"] = session()->get('GROUP_ID');
		
		$data['lov1_detail'] =  $this->Common_model->get_record_values("lov1_label_name label, lov1_category ", "cms_lov_relation", "type_collection='TeleColl' and is_active='1' order by created_time desc limit 1 ");
		
		$lov1_category = str_replace(',',"','",$data['lov1_detail']['lov1_category']) ;
		$data["lov1"] = $this->Common_model->get_record_list("id value, category_name AS item", "cms_lov_registration", " id in ('".$lov1_category."') and is_active='1'", "category_name");

		$data['lov1_status'] = 0;
		if(trim($data['lov1_detail']['label'])!=''){
			$data['lov1_status'] = 1;
		}

		$data['lov2_detail']=  $this->Common_model->get_record_values("lov2_label_name label , lov2_category ", "cms_lov_relation", "type_collection='TeleColl' and is_active='1' order by created_time desc limit 1 ");
		$lov2_category = str_replace(',',"','",$data['lov2_detail']['lov2_category']) ;
		$data["lov2"] = $this->Common_model->get_record_list("id value, category_name AS item", "cms_lov_registration", " id in ('".$lov2_category."') and is_active='1'", "category_name");
		$data['lov2_status'] = 0;
		if(trim($data['lov2_detail']['label'])!=''){
			$data['lov2_status'] = 1;
		}

		$data['lov3_detail']=  $this->Common_model->get_record_values("lov3_label_name label , lov3_category ", "cms_lov_relation", "type_collection='TeleColl' and is_active='1' order by created_time desc limit 1 ");
		$lov3_category = str_replace(',',"','",$data['lov3_detail']['lov3_category']) ;
		$data["lov3"] = $this->Common_model->get_record_list("id value, category_name AS item", "cms_lov_registration", " id in ('".$lov3_category."') and is_active='1'", "category_name");
		$data['lov3_status'] = 0;
		if(trim($data['lov3_detail']['label'])!=''){
			$data['lov3_status'] = 1;
		}

		$data['lov4_detail']=  $this->Common_model->get_record_values("lov4_label_name label , lov4_category ", "cms_lov_relation", "type_collection='TeleColl' and is_active='1' order by created_time desc limit 1 ");
		
		$lov4_category = str_replace(',',"','",$data['lov4_detail']['lov4_category']) ;
		$data["lov4"] = $this->Common_model->get_record_list("id value, category_name AS item", "cms_lov_registration", " id in ('".$lov4_category."') and is_active='1'", "category_name");
		$data['lov4_status'] = 0;
		if(trim($data['lov4_detail']['label'])!=''){
			$data['lov4_status'] = 1;
		}

		$data['lov5_detail']=  $this->Common_model->get_record_values("lov5_label_name label , lov5_category ", "cms_lov_relation", "type_collection='TeleColl' and is_active='1' order by created_time desc limit 1 ");
		$lov5_category = str_replace(',',"','",$data['lov5_detail']['lov5_category']) ;
		$data["lov5"] = $this->Common_model->get_record_list("id value, category_name AS item", "cms_lov_registration", " id in ('".$lov5_category."') and is_active='1'", "category_name");
		$data['lov5_status'] = 0;
		if(trim($data['lov5_detail']['label'])!=''){
			$data['lov5_status'] = 1;
		}
		$data["CUS_TOTAL_OS"] =   $this->Common_model->get_record_value("format(sum(CM_TOT_AMNT_CHARGE_OFF - CM_FIXED_PYMT_AMNT),0)", "cpcrd_new", " CM_CUSTOMER_NMBR='".$data["CM_CUSTOMER_NMBR"]."' group by CM_CUSTOMER_NMBR");
		$data["TL_ID"] = "";
		$data["SPV_ID"] = "";
		// $CM_BUCKET = $this->Common_model->get_record_value("CM_BUCKET","cpcrd_new","CM_CARD_NMBR='".$data["CM_CARD_NMBR"]."'");
		// $product_id = $this->Common_model->get_record_value("PRODUCT_ID","cpcrd_new","CM_CARD_NMBR='".$data["CM_CARD_NMBR"]."'");

		$data["template_email"] = array(""=>"--PILIH--") + $this->Common_model->get_record_list("template_id value, template_name  AS item", "cms_email_sms_template", "is_active=1 AND sent_by = 'Email'","template_name");
		$data["template_sms"] = array(""=>"--PILIH--") + $this->Common_model->get_record_list("template_id value, template_name AS item", "cms_email_sms_template", "is_active=1 AND sent_by = 'SMS' ", "template_name");
		$data["template_wa"] = array(""=>"--PILIH--") + $this->Common_model->get_record_list("template_id value, template_name AS item", "cms_wa_template", "is_active=1", "template_name");
		$data["AGENT_ID"] = $this->Common_model->get_record_value("assigned_agent","cms_account_last_status","account_no='".$data["CM_CARD_NMBR"]."'");

		$result2 = array();
		$builder = $this->db->table('cc_user a');
		$builder->select('a.name');
		$builder->join('cms_team b', 'a.id = b.team_leader');
		$builder->like('b.agent_list', session()->get('USER_ID'), 'both');
		$builder->orderBy('b.created_time', 'DESC');
		$builder->limit(1);
		$rs = $builder->get();

		if ($rs->getNumRows()) {
			foreach ($rs->getResult() as $row) {
				foreach ($row as $key => $value) {
					$result2[$key] = $value;
				}
			}
		}

		if (empty($result2)) {
			$data["TL"] = '<span style="color:red">NOT FOUND</span>';
		} else {
			$data["TL"] = $result2;
		}
		// dd($data["TL"]);
		if(!empty($data["AGENT_ID"])){
			// $data["TL_ID"] =   $this->common_model->get_record_value("team_leader", "cms_team", " agent_list LIKE '%".  $data["AGENT_ID"]."%' ORDER BY created_time desc limit 1");
			$data["SPV_ID"] =   $this->Common_model->get_record_value("supervisor", "cms_team", " agent_list LIKE '%".  $data["AGENT_ID"]."%' ORDER BY created_time desc limit 1");
			$data["AGENT_ID"] =   $this->Common_model->get_record_value("name", "cc_user", " id ='".  $data["AGENT_ID"]."' ");

		}

		$data["account_status"] = $this->Common_model->get_record_list("id as value, short_description AS item", "cms_user_defined_status", "", "priority");
		$data['other_phone'] = $this->Common_model->get_record_value('other_phone', 'cms_customer_data_update', "customer_id = '".$data["CM_CUSTOMER_NMBR"]."'");

		session()->set('CURR_CUSTOMER_ID',$data["CM_CUSTOMER_NMBR"]);
		$data["join_program"] = array(
			"NONE" 	=> "[NONE]",
			"DP"	=> "Pengajuan Diskon Pelunasan",
			"RSTR"	=> "Pengajuan Restructure",
			"RSCH"	=> "Pengajuan Reschedule"
		);

		$data["reason_code"] = array("" => "[select reason]") + $this->Common_model->get_record_list("value, description AS item", "cms_reference", "flag_tmp = '1' AND flag = '1' AND reference = 'REASON_CODE'", "description");
		$data['cm_card_nmbr'] = $account_id;

		$builder2 = $this->db->table('cc_user');
		$builder2->where('id', session()->get('USER_ID'));
		$builder2->update(['contract_number_handling' => $cm_card_nmbr]);

		$builder3 = $this->db->table('acs_agent_assignment a');
		$builder3->select('a.user_id, b.outbound_team as team_id, c.dialing_mode_id');
		$builder3->join('acs_class_work_assignment b', 'a.team=b.outbound_team');
		$builder3->join('acs_dialing_mode_call_status c', 'c.class_id=b.class_mst_id');
		if (session()->get('LEVEL_GROUP') == 'TEAM_LEADER') {
			$builder3->join('cms_team d', 'd.team_id=b.outbound_team');
			$builder3->where('d.team_leader', session()->get('USER_ID'));
		} else if (session()->get('LEVEL_GROUP') == 'TELECOLL') {
			$builder3->where('a.user_id', session()->get('USER_ID'));
		}
		$query = $builder3->get();
		$result = $query->getResultArray();

		$data['dialing_mode'] = @$result[0]['dialing_mode_id'];
		$data['team_id'] = @$result[0]['team_id'];

		$data["phonetag_ref"] = array(''=>'[select data]')+$this->Common_model->get_record_list("a.id value, a.description AS item", "cms_phonetag_ref a", " status ='1' ", " a.description");

		$builder4 = $this->db->table('alt_new_phone');
		$builder4->select('phone_nmbr, phone_type');
		$builder4->where('cm_card_nmbr', $cm_card_nmbr);
		$data['new_phone'] = $builder4->get()->getResultArray();

		$builder5 = $this->db->table('cpcrd_new c');
		$builder5->select('b.STATUS,b.phone_no, b.phone_type');
		$builder5->join('alt_new_phone a', 'a.cm_card_nmbr=c.CM_CARD_NMBR', 'left');
		$builder5->join('cms_phonetag_list b', 'b.cm_card_nmbr=c.cm_card_nmbr', 'left');
		$builder5->where('c.cm_card_nmbr', $cm_card_nmbr);
		$data["list_phone_no"] = $builder5->get()->getResultArray();

		// dd($data["list_phone_no"]);

		$builder6 = $this->db->table('cms_predictive_phone');
		$builder6->select('content phone_number, phone_type, priority , created_time');
		$builder6->where('cm_card_nmbr', $cm_card_nmbr);
		$builder6->where('created_time is not null');
		$builder6->groupBy('content');
		$data['predictive_phone'] = json_encode($builder6->get()->getResultArray());
		
		$data["CUS_TOTAL_OS"] =   $this->Common_model->get_record_value("format(sum(CM_TOT_AMNT_CHARGE_OFF - CM_FIXED_PYMT_AMNT),0)", "cpcrd_new", " CM_CUSTOMER_NMBR='".$data["CM_CUSTOMER_NMBR"]."' group by CM_CUSTOMER_NMBR");

		$data["data_blast"] = $this->Common_model->get_record_values(
		    "to_number, ref_id, template_name, message_id,message message_blast, created_time,
		    IF(is_approved = 'NEED APPROVAL', 
		        '<span class=\"metadata\" style=\"float: left;\"><span class=\"time\"><i class=\"bi bi-clock-history tooltip_all\" id=\"tooltip_all0\"></i></span></span>', 
		        IF(status = 'SENT' and status_data is null, '<span class=\"metadata\" style=\"float: left;\"><span class=\"time\"><i class=\"bi bi-check2 tooltip_all\" id=\"tooltip_all0\"></i></span></span>', '<span class=\"metadata\" style=\"float: left;\"><span class=\"time\"><i class=\"bi bi-check2-all tooltip_all\" id=\"tooltip_all0\"></i></span></span>')) AS approval_status", 
		    "wa_outbound", 
		    "ref_id = '".$account_id."' AND created_time >= CONCAT(CURDATE(), ' 00:00:00')"
		);

		if (empty($data["data_blast"])) {
			$data["data_blast"]["to_number"]="The template blast";
			$data["data_blast"]["ref_id"]="";
			$data["data_blast"]["template_name"]="has not been sent yet";
			$data["data_blast"]["message_id"]="";
			$data["data_blast"]["message_blast"]="";
			$data["data_blast"]["created_time"]="";
			$data["data_blast"]["approval_status"]="";
		}

		$data["data_inb"] =   $this->Common_model->get_record_values("messageId,ticket_id,messageText,receivedAt created_time,pickup_by,pickup_time", "wa_inbox", " ref_id='".$account_id."' and receivedAt >= concat(curdate(),' 00:00:00')");
		if (empty($data["data_inb"])) {
			$data["data_inb"]["messageId"]="";
			$data["data_inb"]["ticket_id"]="";
			$data["data_inb"]["messageText"]="";
			$data["data_inb"]["created_time"]="";
		}

		$builderWaConv = $this->db->table('wa_conversation_details');
		$builderWaConv->select("
			callbackData,
			pairedMessageId,
			if(
				direction = 'OUTB',concat('".base_url()."file_upload/wa_blast_conversation/',pairedMessageId),concat('https://democoll74.ecentrix.net/webhook_cms_ci4/api/file_upload/',pairedMessageId)
			)  link_attachment,
		    messageType,
		    messageText,
		    direction,
		    receivedAt AS created_time,
		    is_approved,
		    status_message,
		    status_json,
		    IF(direction = 'OUTB', ROW_NUMBER() OVER (PARTITION BY direction ORDER BY insert_time), NULL) AS row_num,
		    IF(
		        is_approved = 'NEED APPROVAL' AND direction = 'OUTB', 
		        CONCAT('<span class=\"metadata\" style=\"float: left;\"><span class=\"time\"><i class=\"bi bi-clock-history tooltip_all\" id=\"tooltip_all', ROW_NUMBER() OVER (PARTITION BY direction ORDER BY insert_time), '\" ></i></span></span>'), 
		        IF(
		            status_message = 'SENT' AND status_json IS NOT NULL AND direction = 'OUTB',
		            CONCAT('<span class=\"metadata\" style=\"float: left;\"><span class=\"time\"><i class=\"bi bi-check2-all tooltip_all\" id=\"tooltip_all', ROW_NUMBER() OVER (PARTITION BY direction ORDER BY insert_time), '\" ></i></span></span>'),
		            ''
		        )
		    ) AS approval_status
		");


		$builderWaConv->where('inbound_message_id', $data["data_inb"]['messageId']);
		$builderWaConv->where('insert_time >= concat(curdate() ," 00:00:00")');
		$builderWaConv->orderBy('insert_time','asc');
		$data["data_convertation"] = $builderWaConv->get()->getResultArray();

		$data["list_quick_template"] = array(''=>'[select data]')+$this->Common_model->get_record_list("a.id value, a.template_name AS item", "wa_quick_reply a", " is_active ='Y' ", " a.template_name");
		// $data['test'] = $this->Detail_account_model->get_contract_data_new($data['customer_id'],$data['account_id']);
		 //dd($data);
		/*echo "<pre>";
		print_r($data["data_inb"]);
		print_r($data["data_convertation"]);*/
		// exit();

		return view('\App\Modules\DetailAccount\Views\Detail_account_view', $data);
	}
	function contract_detail_new()
    {
		
		$data['customer_id'] = $this->input->getGet("customer_id");
		$data['account_id'] = $this->input->getGet("account_no");
		$data['history_id'] = $this->input->getGet("history_id");
		$data['approval'] = $this->input->getGet("approval");

		$cm_customer_nmbr = $this->Common_model->get_record_value("CM_CUSTOMER_NMBR","cpcrd_new","CM_CARD_NMBR = '".$data['account_id']."' ");
		
		$cache = date('Ymd').'_'.$cm_customer_nmbr.'_contract_detail_new';
		
		if ($output = cache($cache)) {
			
			return $output ;
		}
		$CPCRD_NEW = $this->Common_model->get_record_values("CM_CUSTOMER_NMBR ,
															cm_type,
															cm_block_code,
															CR_NAME_1 ,
															ACCOUNT_TAGGING ,
															format(sum(IFNULL(CM_TOT_AMNT_CHARGE_OFF,0) - IFNULL(CM_FIXED_PYMT_AMNT,0)),0) AS CUS_TOTAL_OS,
															fin_account",
															"cpcrd_new",
															"CM_CARD_NMBR = '".$data['account_id']."' ");
		
		$product_id = $CPCRD_NEW['cm_type'];

		$CHECK = $this->Common_model->get_record_value("product_id","cc_detail_account_mapping","product_id='".$product_id."' ");
		if (empty($CHECK)) {
			$builder = $this->db->table('cc_detail_account_mapping');
			$builder->select('*');
			$builder->where('product_id', 'DEFAULT');
			$builder->where('section', 'account_data');
			$builder->orderBy('order_no', 'ASC');
			$data['data_contract'] = $builder->get()->getResultArray();
			
			$builder = $this->db->table('cc_detail_account_mapping');
			$builder->select('*');
			$builder->where('product_id', 'DEFAULT');
			$builder->where('section', 'customer_data');
			$builder->orderBy('order_no', 'ASC');
			$data['data_profile'] = $builder->get()->getResultArray();
			
			$builder = $this->db->table('cc_detail_account_mapping');
			$builder->select('*');
			$builder->where('product_id', 'DEFAULT');
			$builder->where('section', 'loan_data');
			$builder->orderBy('order_no', 'ASC');
			$data['loan_data'] = $builder->get()->getResultArray();
			
			$builder = $this->db->table('cc_detail_account_mapping');
			$builder->select('*');
			$builder->where('product_id', 'DEFAULT');
			$builder->where('section', 'contact');
			$builder->orderBy('order_no', 'ASC');
			$data['contact_data'] = $builder->get()->getResultArray();
		} else {
			$builder = $this->db->table('cc_detail_account_mapping');
			$builder->select('*');
			$builder->where('product_id', $product_id);
			$builder->where('section', 'account_data');
			$builder->orderBy('order_no', 'ASC');
			$data['data_contract'] = $builder->get()->getResultArray();
			
			$builder = $this->db->table('cc_detail_account_mapping');
			$builder->select('*');
			$builder->where('product_id', $product_id);
			$builder->where('section', 'customer_data');
			$builder->orderBy('order_no', 'ASC');
			$data['data_profile'] = $builder->get()->getResultArray();
			
			$builder = $this->db->table('cc_detail_account_mapping');
			$builder->select('*');
			$builder->where('product_id', $product_id);
			$builder->where('section', 'loan_data');
			$builder->orderBy('order_no', 'ASC');
			$data['loan_data'] = $builder->get()->getResultArray();
			
			$builder = $this->db->table('cc_detail_account_mapping');
			$builder->select('*');
			$builder->where('product_id', $product_id);
			$builder->where('section', 'contact');
			$builder->orderBy('order_no', 'ASC');
			$data['contact_data'] = $builder->get()->getResultArray();
		}
		$data['history_contact'] =  $this->Common_model->get_record_values("*", "cms_contact_history", " id='".  $data['history_id'] ."' ");
		
		// $builder = $this->db->table('cpcrd_new');
		// $builder->select('format(CM_CURR_DUE,0)CM_CURR_DUE , format(CM_PAST_DUE,0) CM_PAST_DUE, format(CM_30DAYS_DELQ,0) CM_30DAYS_DELQ, format(CM_60DAYS_DELQ,0)CM_60DAYS_DELQ, format(CM_90DAYS_DELQ,0)CM_90DAYS_DELQ , format(CM_120DAYS_DELQ,0)CM_120DAYS_DELQ , format(CM_150DAYS_DELQ,0)CM_150DAYS_DELQ , format(CM_180DAYS_DELQ,0)CM_180DAYS_DELQ');
		// $builder->where('cm_card_nmbr', $data['account_id']);
		// $data['data_aging_minimum_payment'] = $builder->get()->getResultArray();
		
		// $builder = $this->db->table('cpcrd_new');
		// $builder->select('CM_DELQ_COUNTER1 ,CM_DELQ_COUNTER2 ,CM_DELQ_COUNTER3 ,CM_DELQ_COUNTER4 ,CM_DELQ_COUNTER5 ,CM_DELQ_COUNTER6 ,CM_DELQ_COUNTER7, CM_DELQ_COUNTER8 , CM_DELQ_COUNTER9');
		// $builder->where('cm_card_nmbr', $data['account_id']);
		// $data['data_aging_delq'] = $builder->get()->getResultArray();
		
		// $builder = $this->db->table('cpcrd_new');
		// $builder->select('CR_NAME_1 , fin_account , CM_CARD_EXPIR_DTE , CM_CRLIMIT');
		// $builder->where('cm_customer_nmbr', $cm_customer_nmbr);
		// $data['supplement_card'] = $builder->get()->getResultArray();


		$data['contracts'] = $this->Detail_account_model->get_contract_data_new($data['customer_id'],$data['account_id']);
        $data["contact_history"] = $this->Detail_account_model->get_contact_history_data($data['account_id']);

		
		
		$data['cm_block_code'] = $CPCRD_NEW['cm_block_code'];
		$data["CR_NAME_1"] = $CPCRD_NEW['CR_NAME_1'];
		$data["ACCOUNT_TAGGING"] =   $CPCRD_NEW['ACCOUNT_TAGGING']; 
		$data["CUS_TOTAL_OS"] =  $CPCRD_NEW['CUS_TOTAL_OS']; 
		$data["CARD_NO"] =   $CPCRD_NEW['fin_account']; 
		$data["TOTAL_PAYMENT"] =   $this->Common_model->get_record_value("format(sum(IFNULL(pay_amount,0)),0)", "cms_payment_history", " CM_CARD_NMBR='".  $data['CARD_NO'] ."'");
		$data["LAST_PAYMENT_AMT"] =   $this->Common_model->get_record_value("IFNULL(pay_amount,0)", "cms_payment_history", " CM_CARD_NMBR='".  $data['CARD_NO'] ."' ORDER BY posting_date DESC LIMIT 1");
		$data["LAST_PAYMENT_DATE"] =   $this->Common_model->get_record_value("posting_date", "cms_payment_history", " CM_CARD_NMBR='".  $data['CARD_NO'] ."' ORDER BY posting_date DESC LIMIT 1");
		$data["acc_status_desc"] = "";
		$data['lov1_detail'] =  $this->Common_model->get_record_values("lov1_label_name label, lov1_category ", "cms_lov_relation", "type_collection='TeleColl' and is_active='1' order by created_time desc limit 1 ");

		$lov1_category = str_replace(',',"','",$data['lov1_detail']['lov1_category']) ;
			$data["lov1"] = $this->Common_model->get_record_list("id value, category_name AS item", "cms_lov_registration", " id in ('".$lov1_category."') and is_active='1'", "category_name");
			
			$data['lov1_status'] = 0;
			if(trim($data['lov1_detail']['label'])!=''){
				$data['lov1_status'] = 1;
			}
			

			$data['lov2_detail']=  $this->Common_model->get_record_values("lov2_label_name label , lov2_category ", "cms_lov_relation", "type_collection='TeleColl' and is_active='1' order by created_time desc limit 1 ");
			$lov2_category = str_replace(',',"','",$data['lov2_detail']['lov2_category']) ;
			$data["lov2"] = $this->Common_model->get_record_list("id value, category_name AS item", "cms_lov_registration", " id in ('".$lov2_category."') and is_active='1'", "category_name");
			$data['lov2_status'] = 0;
			if(trim($data['lov2_detail']['label'])!=''){
				$data['lov2_status'] = 1;
			}
			
			$data['lov3_detail']=  $this->Common_model->get_record_values("lov3_label_name label , lov3_category ", "cms_lov_relation", "type_collection='TeleColl' and is_active='1' order by created_time desc limit 1 ");
			$lov3_category = str_replace(',',"','",$data['lov3_detail']['lov3_category']) ;
			$data["lov3"] = $this->Common_model->get_record_list("id value, category_name AS item", "cms_lov_registration", " id in ('".$lov3_category."') and is_active='1'", "category_name");
			$data['lov3_status'] = 0;
			if(trim($data['lov3_detail']['label'])!=''){
				$data['lov3_status'] = 1;
			}
			
			$data['lov4_detail']=  $this->Common_model->get_record_values("lov4_label_name label , lov4_category ", "cms_lov_relation", "type_collection='TeleColl' and is_active='1' order by created_time desc limit 1 ");
			
			$lov4_category = str_replace(',',"','",$data['lov4_detail']['lov4_category']) ;
			$data["lov4"] = $this->Common_model->get_record_list("id value, category_name AS item", "cms_lov_registration", " id in ('".$lov4_category."') and is_active='1'", "category_name");
			$data['lov4_status'] = 0;
			if(trim($data['lov4_detail']['label'])!=''){
				$data['lov4_status'] = 1;
			}
			
			$data['lov5_detail']=  $this->Common_model->get_record_values("lov5_label_name label , lov5_category ", "cms_lov_relation", "type_collection='TeleColl' and is_active='1' order by created_time desc limit 1 ");
			$lov5_category = str_replace(',',"','",$data['lov5_detail']['lov5_category']) ;
			$data["lov5"] = $this->Common_model->get_record_list("id value, category_name AS item", "cms_lov_registration", " id in ('".$lov5_category."') and is_active='1'", "category_name");
			$data['lov5_status'] = 0;
			if(trim($data['lov5_detail']['label'])!=''){
				$data['lov5_status'] = 1;
			}
			$builder = $this->db->table('cms_collateral');
			$builder->select('*');
			$builder->where('cif', $data['customer_id']);
			$data['collaterals'] = $builder->get()->getResultArray();

			$dpd_delq_hist = array();
			foreach ($data['contracts'] as $key => $value) {
				$builder = $this->db->table('cms_account_no_history');
				$builder->select("REPLACE(GROUP_CONCAT(dpd SEPARATOR '|'), ',', '|') as dpd"); // Menggunakan SEPARATOR untuk GROUP_CONCAT
				$builder->where('account_no', $value['CM_CARD_NMBR']);
				$builder->orderBy('insert_date', 'desc');
				$builder->limit(5);
				$query = $builder->get();
				$dpd_history = $query->getRowArray()['dpd'];
				
				$builder = $this->db->table('cms_account_no_history');
				$builder->select("REPLACE(GROUP_CONCAT(delq SEPARATOR '|'), ',', '|') as delq"); // Menggunakan SEPARATOR untuk GROUP_CONCAT
				$builder->where('account_no', $value['CM_CARD_NMBR']);
				$builder->orderBy('insert_date', 'desc');
				$builder->limit(5);
				$query = $builder->get();
				$delq_history = $query->getRowArray()['delq'];

				$dpd_delq_hist[$value['CM_CARD_NMBR']]['dpd']  = $dpd_history;
				$dpd_delq_hist[$value['CM_CARD_NMBR']]['delq']  = $delq_history;

			}
			$data['dpd_delq_hist'] = $dpd_delq_hist;
			$builder = $this->db->table('alt_new_phone');
			$builder->select('phone_nmbr, phone_type');
			$builder->where('cm_card_nmbr', $data['account_id']);
			$builder->orderBy('phone_type', 'asc');
			$data["new_phone"] = $builder->get()->getResultArray();

        $output =  view('\App\Modules\DetailAccount\Views\Contract_detail_view_v2', $data);
		cache()->save($cache,$output,env('TIMECACHE_2'));

		return $output;
    }
	function contract_detail_new2()
    {
		$data['customer_id'] = $this->input->getGet("customer_id");
		$data['account_id'] = $this->input->getGet("account_no");

		$cache = date('Ymd').'_'.$data['customer_id'].'contract_detail_new2';

		if ($output = cache($cache)) {
		
			return $output ;
		}

		$data['contracts'] = $this->Detail_account_model->get_contract_data_new($data['customer_id'],$data['account_id']);
		

        $output = view('\App\Modules\DetailAccount\Views\Contract_detail_view', $data);
		cache()->save($cache,$output,env('TIMECACHE_2'));
		return $output;
    }
	function connected_result(){
		$data['customer_id'] = $this->input->getGet('customer_id');
		$account_id = $this->input->getGet('account_no');
		$bucket = $this->Common_model->get_record_value("CM_BUCKET","cpcrd_new","CM_CARD_NMBR='".$account_id."'");
		$minAmountLbl = $this->Common_model->get_record_value("min_amount_acceptable_promise","cms_bucket","bucket_id='".$bucket."'");
		if($minAmountLbl=='MP'){
			$data['min_payment'] = $this->Common_model->get_record_value("FORMAT(CM_PAST_DUE,0)","cpcrd_new","CM_CARD_NMBR='".$account_id."'");
		}
		else if($minAmountLbl=='M1'){
			$data['min_payment'] = $this->Common_model->get_record_value("FORMAT(CM_30DAYS_DELQ,0)","cpcrd_new","CM_CARD_NMBR='".$account_id."'");
		}
		else if($minAmountLbl=='M2'){
			$data['min_payment'] = $this->Common_model->get_record_value("FORMAT(CM_60DAYS_DELQ,0)","cpcrd_new","CM_CARD_NMBR='".$account_id."'");
		}
		else if($minAmountLbl=='M3'){
			$data['min_payment'] = $this->Common_model->get_record_value("FORMAT(CM_90DAYS_DELQ,0)","cpcrd_new","CM_CARD_NMBR='".$account_id."'");
		}
		else if($minAmountLbl=='M4'){
			$data['min_payment'] = $this->Common_model->get_record_value("FORMAT(CM_120DAYS_DELQ,0)","cpcrd_new","CM_CARD_NMBR='".$account_id."'");
		}
		else if($minAmountLbl=='M5'){
			$data['min_payment'] = $this->Common_model->get_record_value("FORMAT(CM_150DAYS_DELQ,0)","cpcrd_new","CM_CARD_NMBR='".$account_id."'");
		}
		else if($minAmountLbl=='M6'){
			$data['min_payment'] = $this->Common_model->get_record_value("FORMAT(CM_180DAYS_DELQ,0)","cpcrd_new","CM_CARD_NMBR='".$account_id."'");
		}
		else if($minAmountLbl=='M7'){
			$data['min_payment'] = $this->Common_model->get_record_value("FORMAT(CM_210DAYS_DELQ,0)","cpcrd_new","CM_CARD_NMBR='".$account_id."'");
		}
		else {
			$data['min_payment'] = $this->Common_model->get_record_value("FORMAT(any_amount_acceptable_promise,0)","cms_bucket","bucket_id='".$bucket."'");
		}
		$data['min_payment_label'] = $minAmountLbl;
		$bucket = $this->Common_model->get_record_value("CM_BUCKET", "cpcrd_new", "CM_CARD_NMBR='". $account_id."'", "");

		$data['contracts'] = $this->Detail_account_model->get_all_contracts_join($account_id);

		$data["action_code_ct"] = array("" => "[select call result]") + $this->Common_model->get_record_list("value, description AS item", "cms_reference", "flag_tmp = '1' AND flag = '1' AND reference = 'ACTION_CODE'", "order_num");
		$data["next_action_code"] = array("" => "[select next Action]")+ $this->Common_model->get_record_list("value, description AS item", "cms_reference", "flag_tmp = '1' AND flag = '1' AND reference = 'NEXT_ACTION' ", "order_num");
		$data["cara_bayar"] = array("" => "[select cara bayar]") +  $this->Common_model->get_record_list("value, description AS item", "cms_reference", "flag_tmp = '1' AND flag = '1' AND reference = 'CARA_BAYAR'", "order_num");
		$data["grace_period"] = $this->get_tanggal_ptp_grace($bucket);

		return view('\App\Modules\DetailAccount\Views\Connected_result_view', $data);
	}
	function appointment_result(){
		$data['customer_id'] = $this->input->getGet('customer_id');
		$account_id = $this->input->getGet('account_no');
		$data['contracts'] = $this->Detail_account_model->get_all_contracts_join($account_id);
		return view('\App\Modules\DetailAccount\Views\Appointment_result_view', $data);
	}
	function get_tanggal_ptp_grace($bucket){
		$ptp_grace =  $this->Common_model->get_record_value("ptp_grace_period", "cms_bucket", "  bucket_id = '".$bucket ."' ");
		$join_program =  $this->Common_model->get_record_value("join_program", "cms_bucket", "  bucket_id = '".$bucket ."' ");
		if ($join_program=='1') {
			if($ptp_grace==''){
				$ptp_grace = 0;
			}
			$builder = $this->db->table('');
			$builder->select("curdate() + interval ".$ptp_grace." day as tanggal");
			$result = $builder->get()->getResultArray();
			$tanggal = $result['tanggal'];

			$builder = $this->db->table('cc_holiday');
			$builder->select('count(*) jumlah');
			$builder->where('holiday_date between curdate()');
			$builder->where("date('".$tanggal."')");
			$result = $builder->get()->getResultArray();
			$jumlah = $result['jumlah'];
			
			$ptp_grace = $ptp_grace + $jumlah;

			$sql = "SELECT MONTH(CURRENT_DATE() + INTERVAL :ptp_grace DAY) as bulan";
			$query = $this->db->query($sql, ['ptp_grace' => $ptp_grace]);
			$bulan_ptp = (int) $query->getRow()->bulan;
			
			$query = $this->db->query("SELECT MONTH(CURRENT_DATE()) as bulan");
			$result = $query->getRowArray();
			$bulan_sekarang = (int) $result['bulan'];

			
			$interval = 0;
			if ($bulan_ptp === $bulan_sekarang) {
				$interval = $ptp_grace;
			} else if ($bulan_ptp > $bulan_sekarang) {
				$query = $this->db->query("SELECT DAY(LAST_DAY(CURRENT_DATE())) - DAY(CURRENT_DATE()) AS grace_period");
				$result = $query->getRowArray();
				$grace_period = $result['grace_period'];

				$interval = $grace_period;
			}
			$query = $this->db->query("SELECT CURRENT_DATE() + INTERVAL :interval DAY AS tanggal", ['interval' => $interval]);
			$result = $query->getRowArray();
			$tanggal = $result['tanggal'];
			return $tanggal;
		} else {
			$query = $this->db->query("SELECT LAST_DAY(CURRENT_DATE()) AS tanggal");
			$result = $query->getRowArray();
			$tanggal = $result['tanggal'];
			return $tanggal;
		}
	}
	function action_code_history(){
		$data['customer_id'] = $this->input->getGet('customer_id');
		$data['card_no'] = $this->input->getGet('card_no');
		return view('\App\Modules\DetailAccount\Views\Action_code_history_view', $data);
	}
	function action_code_history_data(){
		$dataInput['customer_id'] = $this->input->getGet('customer_id');
		$dataInput['card_no'] = $this->input->getGet('card_no');
		$dataInput['query_filter'] = $this->input->getGet('query_filter');
		$data = $this->Detail_account_model->action_code_history_data_sp($dataInput);
	    if ($data) {
			$cacheKey = session()->get('USER_ID') . '_action_code_history_data';
			$this->cache->delete($cacheKey);
			$this->cache->save($cacheKey, json_encode($data), env('TIMECACHE_1'));
	
			$rs = ['success' => true, 'message' => 'Success to apply filter', 'data' => $data];
		} else {
			$rs = ['success' => false, 'message' => 'failed', 'data' => null];
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function call_result_history(){
		$data['customer_id'] = $this->input->getGet('customer_id');
		$data['card_no'] = $this->input->getGet('card_no');
		return view('\App\Modules\DetailAccount\Views\Call_result_history_view', $data);
	}
	function call_result_history_data(){
		$dataInput['customer_id'] = $this->input->getGet('customer_id');
		$dataInput['card_no'] = $this->input->getGet('card_no');
		$dataInput['query_filter'] = $this->input->getGet('query_filter');
		$data = $this->Detail_account_model->call_result_history_data($dataInput);
	    if ($data) {
			$cacheKey = session()->get('USER_ID') . '_call_result_history_data';
			$this->cache->delete($cacheKey);
			$this->cache->save($cacheKey, json_encode($data), env('TIMECACHE_1'));
	
			$rs = ['success' => true, 'message' => 'Success to apply filter', 'data' => $data];
		} else {
			$rs = ['success' => false, 'message' => 'failed', 'data' => null];
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function sms_history_data(){
		$card_no = $this->input->getGet('card_no');
		$cache = session()->get('USER_ID').'_sms_history_data';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Detail_account_model->sms_history_data($card_no);
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function email_history_data(){
		$card_no = $this->input->getGet('card_no');
		$cache = session()->get('USER_ID').'_email_history_data';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Detail_account_model->email_history_data($card_no);
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function br_history_data(){
		$card_no = $this->input->getGet('card_no');
		$cache = session()->get('USER_ID').'_br_history_data';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Detail_account_model->br_history_data($card_no);
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function payment_history(){
		$data['customer_id'] = $this->input->getGet('customer_id');
		$data['card_no'] = $this->input->getGet('card_no');
		return view('\App\Modules\DetailAccount\Views\Payment_history_view', $data);
	}
	function payment_history_data(){
		$card_no = $this->input->getGet('card_no');
		$cache = session()->get('USER_ID').'_payment_history_data';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Detail_account_model->payment_history_data($card_no);
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function note_history(){
		$data['customer_id'] = $this->input->getGet('customer_id');
		$data['card_no'] = $this->input->getGet('card_no');
		return view('\App\Modules\DetailAccount\Views\Note_history_view', $data);
	}
	function note_history_data(){
		$card_no = $this->input->getGet('card_no');
		$cache = session()->get('USER_ID').'_note_history_data';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Detail_account_model->note_history_data($card_no);
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function ptp_history(){
		$data['fin_account'] = $this->input->getGet('fin_account');
		$data['card_no'] = $this->input->getGet('card_no');
		return view('\App\Modules\DetailAccount\Views\Ptp_history_view', $data);
	}
	function ptp_history_list(){
		$fin_account = $this->input->getGet('fin_account');
		$cache = session()->get('USER_ID').'_ptp_history_list';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Detail_account_model->ptp_history_list($fin_account);
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function payment_schedule(){
		$data['customer_id'] = $this->input->getGet('customer_id');
		$data['card_no'] = $this->input->getGet('card_no');
		return view('\App\Modules\DetailAccount\Views\Payment_schedule_view', $data);
	}
	function get_payment_schedule_data(){
		$card_no = $this->input->getGet('card_no');
		$cache = session()->get('USER_ID').'_payment_schedule_data';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Detail_account_model->get_payment_schedule_data($card_no);
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function agent_script_guide(){
		$data['current_contract_no'] = $this->input->getGet('current_contract_no');
		$class_id = $this->Common_model->get_record_value("class","cpcrd_new a","cm_card_nmbr = '".$data['current_contract_no']."'");
		$jumlah = $this->Common_model->get_record_value("count(*) jml","cms_classification a","a.classification_id = '".$class_id."' and a.account_handling like '%Telecoll%' ");
		if ($jumlah > 0) {
			$jumlah = $this->Common_model->get_record_value("count(*) jml","acs_class_agent_script a","a.class_id = '".$class_id."' ");
			if ($jumlah > 0) {
				$agent_script_id = $this->Common_model->get_record_value("agent_script_id","acs_class_agent_script a","a.class_id = '".$class_id."' ");
				$script = $this->Common_model->get_record_value("script","acs_agent_script a","a.id = '".$agent_script_id."' ");
				$cache = session()->get('USER_ID').'_agent_script_guide';
				if($this->cache->get($cache)){  
					$data = json_decode($this->cache->get($script));
					$rs = array('success' => true, 'message' => 'agent script ready', 'data' => $data);
				}else{
					$data = $script;
					$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
		
					$rs = array('success' => true, 'message' => 'agent script ready', 'data' => $data);
				}
			} else {
				$rs = array('success' => false, 'message' => 'agent script not found', 'data' => null);
				}
		} else {
			$rs = array('success' => false, 'message' => 'No. Pinjaman bukan di tangani telecoll', 'data' => null);
			
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function dpd_history(){
		$data['card_no'] = $this->input->getGet('card_no');
		$builder = $this->db->table('cms_bucket_dpd_history');
		$builder->select("*");
		$builder->where("cm_card_nmbr", $data['card_no']);
		$builder->orderBy("data_date", "DESC");
		$res = $builder->get()->getResultArray();
		$data['data'] = $res;
		return view("\App\Modules\DetailAccount\Views\Dpd_history_view", $data);
	}
	function collateral_detail(){
		$data['card_no'] = $this->input->getGet('card_no');
		$data['customer_id'] = $this->input->getGet('customer_id');

		$builder = $this->db->table("cms_collateral");
		$builder->select("*");
		$builder->where("loan_no", $data['card_no']);
		$res = $builder->get()->getResultArray();
		$data['collaterals'] = $res;
		return view("\App\Modules\DetailAccount\Views\Collateral_detail_view", $data);
	}
	function messaging_preview(){
		$data["type"] = $this->input->getGet('type');
		$data["account_id"] = $this->input->getGet('account_id');

		switch($data["type"]){
			case "sms":
				$data["to"] = $this->input->getGet('phone');
				$data_customer = $this->Common_model->get_record_values("CM_CUSTOMER_NMBR as no_cif,concat('XXX', RIGHT(CM_CARD_NMBR,4))CM_CARD_NMBR,CM_BUCKET,REPLACE(format(CURRMTH_BAL_X,0),',','.') AS OS ,date_format(CM_DTE_PYMT_DUE,'%d-%m-%Y' )due_date,DPD as dpd, date_format(CM_DTE_LST_PYMT,'%d-%m-%Y' )  last_payment_date,CR_NAME_1 first_name,CR_CO_HOME_PHONE mobile_phone,CR_HOME_PHONE home_phone,CR_EMPLOYER company_name, CR_CO_OFFICE_PHONE office_phone ,concat(CR_ADDR_1,' ',CR_ADDR_2,' ',CR_ADDR_3) cust_address_1,CR_ADDR_4 cust_address_2, CR_CITY city,REPLACE(FORMAT((CM_CURR_DUE+CM_30DAYS_DELQ+CM_60DAYS_DELQ+CM_90DAYS_DELQ+CM_120DAYS_DELQ+CM_150DAYS_DELQ+CM_180DAYS_DELQ+CM_210DAYS_DELQ+CM_240DAYS_DELQ),0),',','.') min_payment","cpcrd_new a","CM_CARD_NMBR='".$data["account_id"]."'");
				$data["bucket"] = $data_customer["CM_BUCKET"];
				$data["from"] = "CMS";
				$data["template"] =  $this->Common_model->get_record_value("template_design","cms_email_sms_template","template_id='".$this->input->getGet('template')."'");
				foreach($data_customer as $key=>$value){
							$data["template"] = str_replace("[[".$key."]]",$value??'',$data["template"] );
				}
			break;
			case "email":
				$data_customer = $this->Common_model->get_record_values("CM_CUSTOMER_NMBR as no_cif,concat('XXX', RIGHT(CM_CARD_NMBR,4))CM_CARD_NMBR,CM_BUCKET,date_format(CM_DTE_PYMT_DUE,'%d-%m-%Y' )due_date,DPD as dpd, date_format(CM_DTE_LST_PYMT,'%d-%m-%Y' )  last_payment_date,REPLACE(format(CURRMTH_BAL_X,0),',','.') AS OS ,CR_NAME_1 first_name,CR_CO_HOME_PHONE mobile_phone,CR_HOME_PHONE home_phone,CR_EMPLOYER company_name, CR_CO_OFFICE_PHONE office_phone ,concat(CR_ADDR_1,' ',CR_ADDR_2,' ',CR_ADDR_3) cust_address_1,CR_ADDR_4 cust_address_2,REPLACE(FORMAT((CM_CURR_DUE+CM_30DAYS_DELQ+CM_60DAYS_DELQ+CM_90DAYS_DELQ+CM_120DAYS_DELQ+CM_150DAYS_DELQ+CM_180DAYS_DELQ+CM_210DAYS_DELQ+CM_240DAYS_DELQ),0),',','.') min_payment,CR_ADDL_EMAIL email","cpcrd_new a join cms_account_last_status b on (a.CM_CARD_NMBR=b.account_no)","CM_CARD_NMBR='".$data["account_id"]."'");
				$data["bucket"] = $data_customer["CM_BUCKET"];
				$data["to"] = $data_customer["email"];
				
				$data["from"] = "CMS";
				$data["template"] =  $this->Common_model->get_record_value("template_design","cms_email_sms_template","template_id='".$this->input->getGet('template')."'");
				foreach($data_customer as $key=>$value){
							$data["template"] = str_replace("[[".$key."]]",$value ??'',$data["template"] );
				}
				$data["template"] = str_replace("[[tanggal]]",date('d-m-Y') ??'' ,$data["template"] );
			break;
			case "wa":
				$data["to"]=$this->input->getGet('phone');
				$data["template_id"]=$this->input->getGet('template');
				if (!empty($this->input->getGet('phone'))) {
					if (substr($this->input->getGet('phone'), 0, 2) === '08') {
				        // Ubah nomor menjadi format internasional dengan awalan "62"
				        $hape = '62' . substr($this->input->getGet('phone'), 1);
						$data["to"] = $hape;
				    }
				}
				$data["from"] = "CMS";
				$builder = $this->db->table("cms_wa_template");
				$builder->select("*");
				$builder->where("template_id", $this->input->getGet('template'));
				$rResult = $builder->get()->getResultArray();
				$params=array();
				$params1=array();
				$params_arr=array();
				foreach($rResult as $aRow){
					$parameter = explode('|',$aRow['parameter']);
					$params_arr=[];
					foreach ($parameter as $row) {
						$params['type']='text';
						$params['text']=$row;
						array_push($params1,$params);
						array_push($params_arr,$row);
						
					}
					$aRow['parameter'] = json_encode($params1);
					$aRow['parameter_arr'] = json_encode($params_arr);
					$aRow['card_no'] = $this->input->getGet('account_id');
		            $aRow["message"] = $aRow['template_replace'];
					$html='';
					//var_dump($aRow['parameter_arr']);
		            $html = $this->replaceValue($aRow,'wa');
					//var_dump($html['param']);die;
		            $data["template"] = $html['html'];
		            $data["param"] = $html['param'];
		        }
			break;
		}
		return view("\App\Modules\DetailAccount\Views\Messaging_preview_view", $data);
	}
	function send_message(){
		$data["type"] = $this->input->getPost('type');
		$data["from"] = $this->input->getPost('from');
		$data["to"] = $this->input->getPost('to');
		$data["account_no"] = $this->input->getPost('account_no');
		$data["message"] = $this->input->getPost('message');
		$data["template_id"] = $this->input->getPost('template_id');
		$return = $this->Detail_account_model->send_message($data);
		if ($return) {
			$rs = array('success' => true, 'message' => 'Success', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function add_new_phone_form(){
		$data['contract_number'] = $this->input->getGet('contract_number');
		$data['collection_history_id'] = $this->input->getGet('collection_history_id');
		$count = $this->Common_model->get_record_value('count(*)','cms_contact_history' ,'id = "'.$data['collection_history_id'].'" ');
		
		$data['is_save'] = '0';
		$data['acs_temp_phone'] = array();
		if($count=='' || $count == 0){
			$count = $this->Common_model->get_record_value('count(*)','acs_temp_phone' ,'collection_history_id = "'.$data['collection_history_id'].'"  and collection_result = "NPH" ');
			if($count>0){
				$builder = $this->db->table("acs_temp_phone");
				$builder->select("*");
				$builder->where("collection_history_id", $data['collection_history_id']);
				$rs = $builder->get()->getResultArray();
				
				$data['acs_temp_phone'] = $rs;
				$data['is_save'] = '1';
			}
		}
		// dd($data);
		return view("\App\Modules\DetailAccount\Views\Add_new_phone_view", $data);
	}
	function save_new_phone(){
		$data['id']					   = uuid();
		$data['collection_history_id'] = $this->input->getPost('callId');
		$data['collection_result']	   = 'NPH';
		$data['contract_number'] 	   = $this->input->getPost('contract_number');
		$data['borrower_hp1']		   = $this->input->getPost('newhp1');
		$data['borrower_hp2']		   = $this->input->getPost('newhp2');
		$data['borrower_hp3']		   = $this->input->getPost('newhp3');
		$data['status']				   = 'OPEN';
		$data['created_by']			   = session()->get('USER_ID');
		$data['created_time']		   = date('Y-m-d H:i:s');
		$builder = $this->db->table("acs_temp_phone");
		$rs = $builder->insert($data);

		$data_task_coordinator['id']					   = uuid();
		$data_task_coordinator['contract_number']		   = $this->input->getPost('contract_number');
		$data_task_coordinator['collection_history_id']	   = $this->input->getPost('callId');
		$data_task_coordinator['status']				   = 'OPEN';
		$data_task_coordinator['collection_result']		   = 'NPH';
		$data_task_coordinator['user_id']				   = session()->get('USER_ID');
		$data_task_coordinator['created_time']			   = date('Y-m-d H:i:s');

		$team_id = $this->Common_model->get_record_value('team','acs_agent_assignment','user_id="'.$data_task_coordinator['user_id'].'"');
		if($team_id==''){
			$data_task_coordinator['outbound_team_id'] = null;
		}else{
			$data_task_coordinator['outbound_team_id'] = $team_id;
		}
		$builder = $this->db->table("acs_coordinator_task");
		$builder->insert($data_task_coordinator);

		if ($rs) {
			$result = array('success' => true, 'message' => 'Update data phone berhasil', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($result);
		}else{
			$result = array('success' => false, 'message' => 'Update data phone gagal', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($result);
		}
	}
	function add_new_address_form(){
		$data['contract_number'] = $this->input->getGet('contract_number');
		$data['collection_history_id'] = $this->input->getGet('collection_history_id');

		$builder = $this->db->table("cms_zip_reg");
		$builder->distinct();
		$builder->select("provinsi");
		$builder->orderBy("provinsi", "ASC");
		$data['provinsi'] = $builder->get()->getResultArray();

		$count = $this->Common_model->get_record_value('count(*)','cms_contact_history' ,'id = "'.$data['collection_history_id'].'" ');
		
		$data['is_save'] = '0';
		if($count=='' || $count == 0){
			$count = $this->Common_model->get_record_value('count(*)','acs_temp_phone' ,'collection_history_id = "'.$data['collection_history_id'].'" and collection_result = "NAD" ');
			if($count>0){
				$builder = $this->db->table("acs_temp_phone");
				$builder->select("*");
				$builder->where("collection_history_id", $data['collection_history_id']);
				$rs = $builder->get()->getResultArray();
				
				$data['acs_temp_phone'] = $rs;
				$data['is_save'] = '1';
			}
		}

		return view("\App\Modules\DetailAccount\Views\Add_new_address_view",$data);
	}

	function add_new_ec_form(){
		$data['contract_number'] = $this->input->getGet('contract_number');
		$data['collection_history_id'] = $this->input->getGet('collection_history_id');
		
		$count = $this->Common_model->get_record_value('count(*)','cms_contact_history' ,'id = "'.$data['collection_history_id'].'" ');
		
		$data['is_save'] = '0';
		if($count=='' || $count == 0){
			$count = $this->Common_model->get_record_value('count(*)','acs_temp_phone' ,'collection_history_id = "'.$data['collection_history_id'].'"  and collection_result = "NEC" ');
			if($count>0){
				$builder = $this->db->table("acs_temp_phone");
				$builder->select("*");
				$builder->where("collection_history_id", $data['collection_history_id']);
				$rs = $builder->get()->getResultArray();
				
				$data['acs_temp_phone'] = $rs; 
				$data['is_save'] = '1';
			}
		}
		return view("\App\Modules\DetailAccount\Views\Add_new_ec_view", $data);
	}

	function save_new_ec(){
		$data['id']					   = uuid();
		$data['collection_history_id'] = $this->input->getPost('callId');
		$data['collection_result']	   = 'NEC'; 
		$data['contract_number'] 	   = $this->input->getPost('contract_number');
		$data['borrower_ecName']		   = $this->input->getPost('ecName');
		$data['borrower_ecPhone']		   = $this->input->getPost('ecPhone');
		$data['borrower_ecAddress']		   = $this->input->getPost('ecAddress');
		$data['status']				   = 'OPEN';
		$data['created_by']			   = session()->get('USER_ID');
		$data['created_time']		   = date('Y-m-d H:i:s');

		$builder = $this->db->table("acs_temp_phone");
		$rs = $builder->insert($data);


		$data_task_coordinator['id']					   = uuid();
		$data_task_coordinator['contract_number']		   = $this->input->getPost('contract_number');
		$data_task_coordinator['collection_history_id']	   = $this->input->getPost('callId');
		$data_task_coordinator['status']				   = 'OPEN';
		$data_task_coordinator['collection_result']		   = 'NEC';
		$data_task_coordinator['user_id']				   = session()->get('USER_ID');
		$data_task_coordinator['created_time']			   = date('Y-m-d H:i:s');

		$team_id = $this->Common_model->get_record_value('team','acs_agent_assignment','user_id="'.$data_task_coordinator['user_id'].'"');
		if($team_id==''){
			$data_task_coordinator['outbound_team_id'] = null;
		}else{
			$data_task_coordinator['outbound_team_id'] = $team_id;
		}
		$builder2 = $this->db->table("acs_coordinator_task");
		$builder2->insert($data_task_coordinator);
		if ($rs) {
			$result = array('success' => true, 'message' => 'Update data phone berhasil', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($result);
		}else{
			$result = array('success' => false, 'message' => 'Update data phone gagal', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($result);
		}
	}

	function add_new_email_form(){
		$data['contract_number'] = $this->input->getGet('contract_number');
		$data['collection_history_id'] = $this->input->getGet('collection_history_id');
		
		$count = $this->Common_model->get_record_value('count(*)','cms_contact_history' ,'id = "'.$data['collection_history_id'].'" ');
		
		$data['is_save'] = '0';
		if($count=='' || $count == 0){
			$count = $this->Common_model->get_record_value('count(*)','acs_temp_phone' ,'collection_history_id = "'.$data['collection_history_id'].'"  and collection_result = "NMAIL" ');
			if($count>0){
				$builder = $this->db->table("acs_temp_phone");
				$builder->select("*");
				$builder->where("collection_history_id", $data['collection_history_id']);
				$rs = $builder->get()->getResultArray();
				
				$data['acs_temp_phone'] = $rs; 
				$data['is_save'] = '1';
			}
		}
		return view("\App\Modules\DetailAccount\Views\Add_new_email_view", $data);
	}

	function save_new_mail(){
		$data['id']					   = uuid();
		$data['collection_history_id'] = $this->input->getPost('callId');
		$data['collection_result']	   = 'NMAIL';
		$data['contract_number'] 	   = $this->input->getPost('contract_number');
		$data['borrower_mail1']		   = $this->input->getPost('newMail1');
		$data['borrower_mail2']		   = $this->input->getPost('newMail2');
		$data['borrower_mail3']		   = $this->input->getPost('newMail3');
		$data['status']				   = 'OPEN';
		$data['created_by']			   = session()->get('USER_ID');
		$data['created_time']		   = date('Y-m-d H:i:s');

		$builder = $this->db->table("acs_temp_phone");
		$rs = $builder->insert($data);


		$data_task_coordinator['id']					   = uuid();
		$data_task_coordinator['contract_number']		   = $this->input->getPost('contract_number');
		$data_task_coordinator['collection_history_id']	   = $this->input->getPost('callId');
		$data_task_coordinator['status']				   = 'OPEN';
		$data_task_coordinator['collection_result']		   = 'NMAIL';
		$data_task_coordinator['user_id']				   = session()->get('USER_ID');
		$data_task_coordinator['created_time']			   = date('Y-m-d H:i:s');

		$team_id = $this->Common_model->get_record_value('team','acs_agent_assignment','user_id="'.$data_task_coordinator['user_id'].'"');
		if($team_id==''){
			$data_task_coordinator['outbound_team_id'] = null;
		}else{
			$data_task_coordinator['outbound_team_id'] = $team_id;
		}

		$builder2 = $this->db->table("acs_coordinator_task");
		$builder2->insert($data_task_coordinator);


		if ($rs) {
			$result = array('success' => true, 'message' => 'Update data phone berhasil', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($result);
		}else{
			$result = array('success' => false, 'message' => 'Update data phone gagal', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($result);
		}
	}

	function get_city(){
		$provinsi = $this->input->getGet('provinsi');
		$builder = $this->db->table("cms_zip_reg");
		$builder->distinct();
		$builder->select("kabupaten as des");
		$builder->where("provinsi", $provinsi);
		$builder->orderBy("kabupaten");
		$rs = $builder->get()->getResultArray();
		$result = array('success' => true, 'message' => '', 'data' => $rs);
		return $this->response->setStatusCode(200)->setJSON($result);
	}

	function get_district(){
		$provinsi = $this->input->getGet('provinsi');
		$city = $this->input->getGet('city');

		$builder = $this->db->table("cms_zip_reg");
		$builder->distinct();
		$builder->select("kecamatan as des");
		$builder->where("provinsi", $provinsi);
		$builder->where("kabupaten", $city);
		$builder->orderBy("kecamatan");
		$rs = $builder->get()->getResultArray();
		$result = array('success' => true, 'message' => '', 'data' => $rs);
		return $this->response->setStatusCode(200)->setJSON($result);
	}

	function get_sub_district(){
		$provinsi = $this->input->getGet('provinsi');
		$city = $this->input->getGet('city');
		$district = $this->input->getGet('district');

		$builder = $this->db->table("cms_zip_reg");
		$builder->distinct();
		$builder->select("kelurahan as des");
		$builder->where("provinsi", $provinsi);
		$builder->where("kabupaten", $city);
		$builder->where("kecamatan", $district);
		$builder->orderBy("kelurahan");
		$rs = $builder->get()->getResultArray();

		$result = array('success' => true, 'message' => '', 'data' => $rs);
		return $this->response->setStatusCode(200)->setJSON($result);
	}

	function get_zipcode(){
		$provinsi = $this->input->getGet('provinsi');
		$city = $this->input->getGet('city');
		$district = $this->input->getGet('district');
		$subDistrict = $this->input->getGet('subDistrict');

		$builder = $this->db->table("cms_zip_reg");
		$builder->distinct();
		$builder->select("kodepos as des");
		$builder->where("provinsi", $provinsi);
		$builder->where("kabupaten", $city);
		$builder->where("kecamatan", $district);
		$builder->where("kelurahan", $subDistrict);
		$builder->orderBy("kodepos");
		$rs = $builder->get()->getResultArray();

		$result = array('success' => true, 'message' => '', 'data' => $rs);
		return $this->response->setStatusCode(200)->setJSON($result);
	}
	function save_new_address(){
		$data['id']					   = uuid();
		$data['collection_history_id'] = $this->input->getPost('callId');
		$data['collection_result']	   = 'NAD';
		$data['contract_number'] 	   = $this->input->getPost('contract_number');
		$data['borrower_provinsi']	   = $this->input->getPost('provinsi');
		$data['borrower_kota']		   = $this->input->getPost('city');
		$data['borrower_kecamatan']	   = $this->input->getPost('district');
		$data['borrower_kelurahan']	   = $this->input->getPost('subdistrict');
		$data['borrower_zip']		   = $this->input->getPost('zipcode');
		$data['borrower_alamat']	   = $this->input->getPost('address');
		$data['status']				   = 'OPEN';
		$data['created_by']			   = session()->get('USER_ID');
		$data['created_time']		   = date('Y-m-d H:i:s');
		$builder = $this->db->table("acs_temp_phone");
		$rs = $builder->insert($data);

		$data_task_coordinator['id']					   = uuid();
		$data_task_coordinator['contract_number']		   = $this->input->getPost('contract_number');
		$data_task_coordinator['collection_history_id']	   = $this->input->getPost('callId');
		$data_task_coordinator['status']				   = 'OPEN';
		$data_task_coordinator['collection_result']		   = 'NAD';
		$data_task_coordinator['user_id']				   = session()->get('USER_ID');
		$data_task_coordinator['created_time']			   = date('Y-m-d H:i:s');

		$team_id = $this->Common_model->get_record_value('team','acs_agent_assignment','user_id="'.$data_task_coordinator['user_id'].'"');
		if($team_id==''){
			$data_task_coordinator['outbound_team_id'] = null;
		}else{
			$data_task_coordinator['outbound_team_id'] = $team_id;
		}
		$builder = $this->db->table("acs_coordinator_task");
		$builder->insert($data_task_coordinator);

		if ($rs) {
			$result = array('success' => true, 'message' => 'Update data address berhasil', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($result);
		}else{
			$result = array('success' => false, 'message' => 'Update data address gagal', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($result);
		}
	}

	function save_followup(){
		$id = $this->input->getPost('CallID');
		$customer_no = $this->input->getPost('txt_customer_no');
		$card_numbers = $this->input->getPost('cm_card_nmbr');
		$phone_no = $this->input->getPost('other_phone');
		$phone_type = $this->input->getPost('DialedPhoneType');
		$lov1 = $this->input->getPost('lov1');
		$lov2 = $this->input->getPost('lov2');
		$lov3 = $this->input->getPost('lov3');
		$lov4 = $this->input->getPost('lov4');
		$lov5 = $this->input->getPost('lov5');
		$call_status = $this->Common_model->get_record_value("category", "acs_lov_mapping", "lov_id_cms ='".$lov3."'");
		$agent_notepad_not_connected = $this->input->getPost('txt_agent_notepad_not_connected');
		$hot_note = $this->input->getPost('txt_hot_note');
		$ptp_amounts = $this->input->getPost('txt_ptp_amount');
		$ptp_dates = $this->input->getPost('txt_ptp_date');
		$agent_notepads = $this->input->getPost('txt_agent_notepad');
		$cara_bayar = $this->input->getPost('select_cara_bayar');
		$join_program = $this->input->getPost('select_join_program');
		$reason = $this->input->getPost('select_reason');
		$TELEPHONY_CALLER_ID = $this->input->getPost('txt_TELEPHONY_CALLER_ID');
		$TELEPHONY_RECORDING_ID = $this->input->getPost('txt_TELEPHONY_RECORDING_ID');
		$escalate_to_tl = $this->input->getPost('select_escalate_to_tl');
		$opt_request_phone_tag = $this->input->getPost('opt_request_phone_tag');
		$opt_reason_phone_tag = $this->input->getPost('opt_reason_phone_tag');
		
		if($opt_request_phone_tag=='1'){
			$phonetag['cm_card_nmbr'] = $card_numbers;
			$phonetag['cm_customer_nmbr'] = $customer_no;
			$phonetag['status'] = 'OPEN';
			$phonetag['phone_no'] = $phone_no;
			$phonetag['phone_type'] = $phone_type;
			$phonetag['reason'] = $opt_reason_phone_tag;
			$phonetag['remarks'] = $agent_notepads;
			$phonetag['created_by'] = session()->get('USER_ID');
			$phonetag['created_time'] = date('Y-m-d H:i:s');
			$builder = $this->db->table("cms_phonetag_list");
			$builder->insert($phonetag);
		}
		
		$status_escalate = $this->input->getPost('status_escalate');
		$history_id = $this->input->getPost('history_id');

		if($status_escalate=='1'){
			$USER_ID = session()->get('USER_ID');
			$builder = $this->db->table("acs_coordinator_task");
			$builder->where('collection_history_id',$history_id);
			$builder->set('status','completed');
			$builder->set('update_by',$USER_ID);
			$builder->set('updated_time',date('Y-m-d H:i:s'));
			$builder->update();
		}
		$card_no = $card_numbers;
		$account_status = $this->Common_model->get_record_value("CF_AGENCY_STATUS_ID", "cms_account_last_status", "account_no ='".$card_no."'");
		
		$dpd = $this->Common_model->get_record_value("dpd", "cpcrd_new", "cm_card_nmbr ='".$card_no."'");
		$due_date = $this->Common_model->get_record_value("cm_dte_pymt_due", "cpcrd_new", "cm_card_nmbr ='".$card_no."'");
		$class_id = $this->Common_model->get_record_value("class", "cpcrd_new", "cm_card_nmbr ='".$card_no."'");
		$CM_BUCKET = $this->Common_model->get_record_value("CM_BUCKET", "cpcrd_new", "cm_card_nmbr ='".$card_no."'");
		
		$status_agency = $account_status;
		$call_result_data["id"] 			= $id;
		$call_result_data["account_no"]		= $card_numbers;
		$call_result_data["customer_no"]	= $customer_no;
		$call_result_data["user_id"] 		= session()->get('USER_ID');
		$call_result_data["input_source"] 	= "PHONE";
		$call_result_data["phone_no"] 		= $phone_no;
		$call_result_data["fin_acc"] 		= $this->Common_model->get_record_value("fin_account","cpcrd_new","CM_CARD_NMBR= '".$card_no."'");
		$call_result_data["phone_type"] 	= $phone_type;
		$call_result_data["call_status"] 	= $call_status;
		$call_result_data["lov1"] 			= $lov1;
		$call_result_data["lov2"] 			= $lov2;
		$call_result_data["lov3"] 			= $lov3;
		$call_result_data["lov4"] 			= $lov4;
		$call_result_data["lov5"] 			= $lov5;
		$call_result_data["next_action"] 	= $join_program;
		$call_result_data["reason"] 		= $reason;
		$call_result_data["action_code"] 	= $lov3;
		$call_result_data["notes"] 			= @$agent_notepads;
		$call_result_data["ptp_date"] 		= @$ptp_dates;
		$call_result_data["ptp_amount"] 	= @$ptp_amounts;
		$call_result_data["cara_bayar"] 	= @$cara_bayar !== '' ? substr(@$cara_bayar??'' , 1) : null;
		$call_result_data["created_by"] 	= session()->get('USER_ID');
		$call_result_data["created_time"] 	= date("Y-m-d H:i:s");
		$call_result_data["dpd"] 			= $dpd;
		$call_result_data["due_date"] 		= $due_date;
		$call_result_data["class_id"] 		= $class_id;
		$call_result_data["bucket"] 		= $CM_BUCKET ;
		$call_result_data["caller_id"] 		= $TELEPHONY_CALLER_ID;
		$call_result_data["request_phone_tag"] 		=$opt_request_phone_tag;
		$call_result_data["reason_phone_tag"] 		=$opt_reason_phone_tag;

		if($escalate_to_tl=='1'){
			$call_result_data["escalation"] = 'TL';
			$this->escalate_to_tl($call_result_data);
		}
		// $cti = $this->cti->table("session_log");
		// $cti->select("*");
		// $cti->where("call_id", $TELEPHONY_CALLER_ID);
		// $ecentrix_session_log = $cti->get()->getResultArray();
		// // $ecentrix_session_log = $this->Common_model->get_record_values_cti('*','session_log','call_id="'.$TELEPHONY_CALLER_ID.'"');
		// if(!empty($ecentrix_session_log)){
		// 	$builder = $this->db->table("tmp_ecentrix_session_log_report");
		// 	$builder->where("call_id", $TELEPHONY_CALLER_ID);
		// 	$builder->delete();
		// 	$builder->insert($ecentrix_session_log);
		// 	// $this->db->delete('tmp_ecentrix_session_log_report',array('call_id'=>$TELEPHONY_CALLER_ID));
		// 	// $this->db->insert('tmp_ecentrix_session_log_report',$ecentrix_session_log);

		// 	$call_result_data["duration_call"] 				= $ecentrix_session_log['duration'];
		// 	$call_result_data["start_time_session_log"] 	= $ecentrix_session_log['start_time'];
		// 	$call_result_data["end_time_session_log"] 		= $ecentrix_session_log['end_time'];
		
		// }
		// $cti = $this->cti->table("agent_track a");
		// $cti->select("a.*");
		// $cti->join("session_log b", "a.agent_log_id=b.user_log_id");
		// $cti->where("b.call_id", $TELEPHONY_CALLER_ID);
		// $ecentrix_agent_track = $cti->get()->getResultArray();
		// // $ecentrix_agent_track = $this->Common_model->get_record_values_cti('a.*','agent_track a	JOIN session_log b ON a.agent_log_id=b.user_log_id','b.call_id="'.$TELEPHONY_CALLER_ID.'"');
		// if(!empty($ecentrix_agent_track)){
		// 	// $insert_query = $this->db->insert_string('tmp_ecentrix_agent_track_report',$ecentrix_agent_track);
		// 	// $insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
		// 	// $this->db->query($insert_query);
		// 	$this->db->table('tmp_ecentrix_agent_track_report')->ignore(true)->insert($ecentrix_agent_track);
		// }


		// var_dump($call_result_data);die;

		//save lov wa (jika ada percakapan wa)
		$message_id=$this->Common_model->get_record_value("messageId","wa_inbox","ref_id= '".$card_no."' and receivedAt >= concat(curdate(), ' 00:00:00') ");
		if (!empty($message_id)) {

			$id = $message_id;
        	$check=$this->Common_model->get_record_value("count(*)","wa_queue","id= '".$id."' and pickup_by='".session()->get('USER_ID')."'");
     
	        if ($check==0) {

	        	$this->builder = $this->db->table('wa_inbox');
				$this->builder->select(
					"messageId,
					fromNumber,
					messageText,
					pickup_by,
					ref_id,
					unit"
				);

				$this->builder->where('messageId', $id);
				$this->builder->where('pickup_by', session()->get('USER_ID'));
				$query = $this->builder->get();
				$data_wa = $query->getResultArray();
				$num = count($data_wa);

	            if ($num==1) {

	            	foreach ($data_wa as $key => $value) {
	            		$ticket = $this->Common_model->generateCode();

	                    $data_wa_queue = array(
	                    	"id" => $value['messageId'],
							"ticket_id" => $ticket,
							"customer_id" => $value['ref_id'],
							"inbound_source_id" => $value['messageId'],
							"source_id" => "WA",
							"name" => $value['fromNumber'],
							"message" => $value['messageText'],
							"is_ticket" => "2",
							"pickup_by" => $value['pickup_by'],
							"modules" => 'Whatsapp/get_detail_chat?id='.$value['messageId'],
							"site" =>$value['unit'],
							"created_time" => date("Y-m-d H:i:s")
	                    ); 
						$this->builder = $this->db->table('wa_queue');
						$this->builder->insert($data_wa_queue);

						$this->builder = $this->db->table('wa_inbox');
				        $this->builder->where('messageId', $value['messageId']);
			        	$this->builder->set('ticket_id', $ticket);
			        	$this->builder->set('is_ticket', 'Y');
			        	$this->builder->set('created_ticket_by', session()->get('USER_ID'));
			        	$this->builder->set('created_ticket_time', date("Y-m-d H:i:s"));
			        	$this->builder->update();

			        	$this->builder = $this->db->table('wa_conversation_details');
				        $this->builder->where('is_ticket', 'N');
				        $this->builder->where('fromNumber', $value['fromNumber']);

			        	$this->builder->set('ticket_id', $ticket);
			        	$this->builder->set('is_ticket', 'Y');
			        	$this->builder->set('created_ticket_by', session()->get('USER_ID'));
			        	$this->builder->set('created_ticket_time', date("Y-m-d H:i:s"));
			        	$this->builder->update();
	                    
	                    $call_result_data["input_source"] 	= "WA";
	                    $call_result_data["other_address"] 	= $ticket;
	                    
		                $result_wa = array('status'=>true,'id'=>$id,'ticket_id'=>$ticket,'created_time'=>date("Y-m-d H:i:s"));
	            	}
	            	
	            }else{
	            	$result_wa = array('status'=>false,'id'=>$id,'ticket_id'=>$ticket);
	            	
	            }
	        }
		}

		$return = $this->Detail_account_model->insert_contact_result($call_result_data, $hot_note, $status_agency);
		
		$data_account_last_status['call_result'] = $call_result_data["action_code"];
		$data_account_last_status['call_date'] = date("Y-m-d");
		$data_account_last_status['last_contact_time'] = date("Y-m-d H:i:s");
		$data_account_last_status['last_notes'] =  @$agent_notepads;
		if($lov3=='PTP'){
			$data_account_last_status['ptp_date'] = @$ptp_dates;
			$data_account_last_status['ptp_amount'] = @$ptp_amounts;
		}
		$builder = $this->db->table("cms_account_last_status");
		$builder->where('account_no',$call_result_data["account_no"]);
		$builder->update($data_account_last_status);
		
		if($call_result_data["lov3"] == "APP"){
			$app = $this->input->getPost('appt');
			$data_app['contract_number'] = $call_result_data["account_no"];
			$data_app['agent_id'] = $call_result_data["created_by"];
			$data_app['agent_group_id'] = null;
			$data_app['appointment_datetime'] = date('Y-m-d').' '.$app ;
			$data_app['appointment_destination'] = null; 
			$data_app['destination_type'] = null ;
			$data_app['attempt_count'] = null;
			$data_app['status'] = 0;
			$data_app['last_update_datetime'] = date('Y-m-d H:i:s');
			$data_app['created_datetime'] =  date('Y-m-d H:i:s');;
			$data_app['class_id'] = $class_id;
			$data_app['cycle'] = null;
			$builder = $this->db->table("acs_customer_appointment");
			$builder->insert($data_app);
		}
		if ($return){
			$builder = $this->db->table("cc_user");

			$builder->where('id',session()->get('USER_ID'));
			$data_current_handling['contract_number_handling'] = '';
			$builder->update($data_current_handling);

			$data	= array("success" => true, "message" => "Success");
		}
		else {
			//$this->Common_model->data_logging('Approval', $checking_data["approval_status"], 'FAILED', 'No. registrasi: '.$checking_data["no_registrasi"]);
			$data	= array("success" => false, "message" => "Failed");
		}

		// $agent_id = session()->get('USER_ID');
		// $this->update_agent_as_of_today($agent_id);
		// $this->update_bucket_as_of_today();
		// $this->update_class_as_of_today();
		// $this->update_team_as_of_today();

		return $this->response->setStatusCode(200)->setJSON($data);
	}
	function replaceValue($data, $source, $preview=false){
		$builder = $this->db->table("cpcrd_new a");
        $builder->select('a.*, b.*, a.cm_card_nmbr as cm_card_nmbr');
        $builder->join('cpcrd_ext b', 'a.cm_card_nmbr=b.cm_card_nmbr','left');
		$builder->where('a.cm_card_nmbr', $data['card_no']);
        $builder->limit(1);
		$rResult = $builder->get()->getResultArray();
        
		$builder = $this->db->table("cms_field_template_mapping");
        $builder->select('*');
		$builder->where('is_active', 'Y');
		$builder->where('replace_with is not null');
        $rField= $builder->get()->getResultArray();
        
        // var_dump($rResult);die;
        $html_content = "";
        $wa_params = "";
        $wa_params_arr = "";
        $content=[];
		foreach($rResult as $aRow){
			
            $html_content .= $data['message'];
            $wa_params .= $data['parameter']??'';
            $wa_params_arr .= $data['parameter_arr']??'';
			
			if($source=='sms'){
				$html_content = str_replace("</p>", "", str_replace("<p>","",$html_content));
			}

            foreach($rField as $aField){
                if($aField['value']=='[[CURDATE]]'){
                    $html_content = str_replace((string) $aField['value'], date("Y-m-d") , $html_content);
                    $wa_params = str_replace((string) $aField['value'], date("Y-m-d") , $wa_params);
                    $wa_params_arr = str_replace((string) $aField['value'], date("Y-m-d") , $wa_params_arr);
                }else{
                    if($aField['is_currency']=='Y'){
                        $html_content = str_replace($aField['value'], number_format((int) $aRow[$aField['replace_with']]??'') , $html_content);
                        $wa_params = str_replace($aField['value'], number_format((int) $aRow[$aField['replace_with']]??'') , $wa_params);
                        $wa_params_arr = str_replace($aField['value'], number_format((int) $aRow[$aField['replace_with']]??'') , $wa_params_arr);
                    }
                    if($aField['is_date']=='Y'){
                        $tgl = $this->Common_model->MonthIndo((string) $aRow[$aField['replace_with']] ?? '');
                        $html_content = str_replace($aField['value'], $tgl, $html_content);
                        $wa_params = str_replace($aField['value'], $tgl, $wa_params);
                        $wa_params_arr = str_replace($aField['value'], $tgl, $wa_params_arr);
                    }

                    $str=@trim($aRow[$aField['replace_with']]);
                    $html_content = str_replace((string) $aField['value'],  $str ?? '' , $html_content);
                    $wa_params = str_replace((string) $aField['value'], $str ?? '' , $wa_params);
                    $wa_params_arr = str_replace((string) $aField['value'], $str ?? '' , $wa_params_arr);
                }  
            }
        }
       	$content['html']=$html_content;
       	$content['param']=implode("|",json_decode($wa_params_arr));
        return $content;
    }
	function escalate_to_tl($datax){
		$teamId = $this->Common_model->get_record_value('team_id','cms_team','agent_list like "%'.$datax['created_by'].'%" AND flag_team="1" ');

		$data['id'] = uuid(false);
		$data['contract_number'] = $datax['account_no'];
		$data['collection_history_id'] = $datax['id'];
		$data['status'] = 'OPEN';
		$data['collection_result'] = 'ESCL';
		$data['next_action'] = '';
		$data['notes'] = $datax['notes'];;
		$data['outbound_team_id'] = $teamId;
		$data['user_id'] = $datax['created_by'];
		$data['created_time'] = date('Y-m-d H:i:s');
		$builder = $this->db->table("acs_coordinator_task");
		$rs = $builder->insert($data);
		return $rs;
	}
	function update_account_handling(){
		$contract_number = $this->input->getGet('contract_number');
		$builder = $this->db->table("cc_user");
		$builder->set("contract_number_handling", $contract_number);
		$builder->where("id", session()->get('USER_ID'));
		$builder->update();
	}
	function reply_wa(){
		date_default_timezone_set('Asia/Jakarta');
		$message_reply=$this->input->getPost('txt_wa');
		$to_number=$this->input->getPost('to_number');
		$cm_card_nmbr=$this->input->getPost('cm_card_nmbr');
		$inbound_message_id=$this->input->getPost('inbound_message_id');
		$file = $this->input->getFile('file');
		$now = date('Y-m-d H:i:s');

		$builder = $this->db->table("wa_filter_word a");
        $builder->select('a.word');
		$builder->where('a.is_active', '1');
		$builder->where('a.is_approved', 'APPROVED');        
		$listWord = $builder->get()->getResultArray();

		$arrlistWord=array();
		foreach ($listWord as $key => $value) {
			$arrlistWord[]=strtoupper($value['word']);
		}


		$arrMessage=explode(" ",$message_reply);
		foreach ($arrMessage as $key2 => $value2) {
			$arrMessage[$key2]=strtoupper($value2);
		}

		$result = array_intersect($arrMessage,$arrlistWord);

		if(empty($result)){

			//pengecekan ada file attchment tidak
			if (!empty($file)) { 
				$validationRule = [
		            'file' => [
		                'label' => 'File',
		                'rules' => 'uploaded[file]|ext_in[file,jpg,jpeg,png,mp4,webm,mp3,wav,txt,docx,doc,pdf,xlsx,xls,pptx,ppt]|max_size[file,2048]',
		            ],
		        ];

		        if (!$this->validate($validationRule)) {
					$rs = array('success' => false, 'message' => 'File must be of type jpg or jpeg or png or mp4 or webm or mp3 or wav or txt or docx or doc or pdf or xlsx or xls or pptx or ppt', 'data' => null);
					return $this->response->setStatusCode(200)->setJSON($rs);
		        } else {

		        	$originalName = $file->getClientName();
		        	$contentType =$file->getMimeType();
					$uploadPath = ROOTPATH . 'file_upload/wa_blast_conversation/';  // pastikan direktori ini ada dan memiliki izin tulis

					// Cek apakah direktori sudah ada, jika tidak, buat direktori
					if (!is_dir($uploadPath)) {
					    mkdir($uploadPath, 0755, true); // 0755 adalah izin untuk direktori, true untuk membuat direktori secara rekursif
					}

					$filePath = $uploadPath . $originalName;
					$file->move($uploadPath, $originalName);

					if (DIRECTORY_SEPARATOR == '\\') {
					    $filePath = str_replace('/', '\\', $filePath);
					}
					
					//print_r($file['originalMimeType']) ;
					switch ($contentType) {
					    case 'image/jpeg':
					        $ext = 'IMAGE';
					        break;
					    case 'image/png':
					        $ext = 'IMAGE';
					        break;
					    case 'application/pdf':
					        $ext = 'DOCUMENT';
					        break;
					    case 'application/msword':
					        $ext = 'DOCUMENT';
					        break;
					    case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
					        $ext = 'DOCUMENT';
					        break;
					    case 'application/vnd.ms-excel':
					        $ext = 'DOCUMENT';
					        break;
					    case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
					        $ext = 'DOCUMENT';
					        break;
					    case 'application/vnd.ms-powerpoint':
					        $ext = 'DOCUMENT';
					        break;
					    case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
					        $ext = 'DOCUMENT';
					        break;
					    case 'text/plain':
					        $ext = 'DOCUMENT';
					        break;
					    case 'video/mp4':
					        $ext = 'VIDEO';
					        break;
					    case 'video/x-matroska':
					        $ext = 'VIDEO';
					        break;
					    case 'audio/mpeg':
					        $ext = 'AUDIO';
					        break;
					    case 'audio/wav':
					        $ext = 'AUDIO';
					        break;
					    case 'audio/ogg':
					        $ext = 'AUDIO';
					        break;
					    default:
					    	$ext = 'TEXT';
					        //echo "Unsupported content type.\n";
					        exit;
					}

					$data = array(
						'messageId' => uuid(),
						'receivedAt' => $now,
						'fromNumber' => session()->get('USER_ID'),
						'toNumber' => $to_number,
						'messageType' => $ext,
						'pairedMessageId' => $originalName,
						'callbackData' => $contentType,
						'messageText' => $message_reply,
						'data_json' => json_encode($_REQUEST),
						'is_ticket' => 'N',
						'direction' => 'OUTB',
						'status_message' => 'QUEUE',
						'insert_time' => $now,
						'unit' => 'ecentrix',
						'inbound_message_id' => $inbound_message_id,
						//'is_approved' => 'NEED APPROVAL' //jika mau dikasih cheker maker
						'is_approved' => 'APPROVED'
					);

				}
			} else{ //untuk TEXT

				$data = array(
					'messageId' => uuid(),
					'receivedAt' => $now,
					'fromNumber' => session()->get('USER_ID'),
					'toNumber' => $to_number,
					'messageType' => 'TEXT',
					'pairedMessageId' => null,
					'callbackData' => null,
					'messageText' => $message_reply,
					'data_json' => json_encode($_REQUEST),
					'is_ticket' => 'N',
					'direction' => 'OUTB',
					'status_message' => 'QUEUE',
					'insert_time' => $now,
					'unit' => 'ecentrix',
					'inbound_message_id' => $inbound_message_id,
					//'is_approved' => 'NEED APPROVAL' //jika mau dikasih cheker maker
					'is_approved' => 'APPROVED'
				);
			}
			
			$return = $this->Detail_account_model->send_reply_wa($data);
			
			$data["message"] = $message_reply;
			$data['created_time'] = $now;
			$data['name']=csrf_token();
			$data['value']=csrf_hash();
		}else{
			$data['name']=csrf_token();
			$data['value']=csrf_hash();
			$rs = array('success' => false, 'message' => 'pesan mengandung kata-kata kurang pantas!', 'data' => $data);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
		if ($return) {
			$rs = array('success' => true, 'message' => 'Success', 'data' => $data);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

	function blast_template_by_agent(){
		date_default_timezone_set('Asia/Jakarta');
		$optTemplate=$this->input->getPost('template_id');
		$account_number=$this->input->getPost('account_no');
		$phone=$this->input->getPost('to');
		$templateType='TEXT';
		$templateAttchment='';
		$arr=explode("|",$this->input->getPost('param_value'));
		if ($templateType=='TEXT') {
	        $content=array(
		        'templateName' =>$optTemplate,
		        'templateData' => array(
		         	'body'=> array(
		            	"placeholders" => $arr
		        	)
		        ),
		        'language' => 'id'
	        );	
	        
        }else if($templateType=='IMAGE' ||$templateType=='VIDEO'){
        	$content=array(
		        'templateName' =>$optTemplate,
		        'templateData' => array(
		         	'body'=> array(
		            	"placeholders" => $arr
		        	),
		        	'header'=> array(
		            	"type" => $templateType,
		            	"mediaUrl" => base_url().'uploads/whatsapp/attachment/'.$templateAttchment
		        	)
		        ),
		        'language' => 'id'
	        );	
        } else if($templateType=='DOCUMENT'){
        	$content=array(
		        'templateName' =>$optTemplate,
		        'templateData' => array(
		         	'body'=> array(
		            	"placeholders" => $arr
		        	),
		        	'header'=> array(
		            	"type" => $templateType,
		            	"mediaUrl" => base_url().'uploads/whatsapp/attachment/'.$templateAttchment,
		            	"filename" => $templateAttchment
		        	)
		        ),
		        'language' => 'id'
	        );	
        }
        
        $data = array( 
        	'messages'	=> array(
        		array(
	        		"from" => $this->Common_model->get_record_value("phone", 'wa_devices', 'id="Ecentrix_demo"'),
	            	"to" => $phone,
	            	"messageId" => uuid(),
	            	"content" => $content
	        	)
        	)		
        );

        $data_template = json_encode($data);		


		$data = array(
			'id' => uuid(false), 
			'ref_id' => $account_number,
			'to_number' => $phone, 
			'template_name' => $optTemplate, 
			'template_data' => $data_template, 
			'json_data' => json_encode($_REQUEST), 
			'message' => $this->input->getPost('message'), 
			'created_by' => session()->get('USER_ID'),
			'created_time' => date('Y-m-d H:i:s'), 
			'is_approved' => 'NEED APPROVAL', 
			'tenant' => 'ecentrix'
		);

		$return = $this->Detail_account_model->blast_template_by_agent($data);
		if ($return) {
			$rs = array('success' => true, 'message' => 'Success', 'data' => $data);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

	function get_quick_reply(){
		$optTemplate=$this->input->getGet('template_id');
		$data = $this->Common_model->get_record_value('message','wa_quick_reply','id="'.$optTemplate.'" ');
		$rs = array('success' => true, 'message' => 'Success', 'data' => $data);
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function get_token(){
		$data['name']=csrf_token();
		$data['value']=csrf_hash();
		$rs = array('success' => true, 'message' => 'Success', 'data' => $data);
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
}