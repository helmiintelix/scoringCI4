<?php
namespace App\Modules\ClassificationManagement\models;

use App\Models\Common_model;
use CodeIgniter\Model;

Class Classification_management_model Extends Model 
{
	protected $Common_model;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new Common_model;
    }
    function get_classification_list(){
        $this->builder = $this->db->table('cms_classification a');
        $select = array(
            'a.classification_id',
            'a.classification_name',
            'a.description',
            'a.effective_date',
            'a.class_expired_date',
            'a.class_priority',
            'a.classification_detail',
            'a.assigned_agent',
            'a.class_category',
            // 'IF(a.is_active = "1", "ENABLE", "DISABLE") AS is_active',
            'a.updated_by',
            'a.updated_time',
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        
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
    function isExistclassification($name){
        $this->builder = $this->db->table('cms_classification');
        $this->builder->where('classification_name', $name);
        $query = $this->builder->get()->getNumRows();
        if ($query > 0) {
            return true;
        } else {
            return false;
        }
    }
    function save_classification_add($data){
        $sep = "\n";
        $tmp = "";
        $line = strtok($data["classification_detail"], $sep);
        while ($line !== false) {
			if (strpos($line, "IN('") !== false) {
				//hilangkan '
				$tmp2 = str_replace("'", "", $line);
				//tambah ',';
				$tmp2 = str_replace(",", "','", $tmp2);
				$tmp2 = str_replace("(", "('", $tmp2);
				$tmp2 = str_replace(")", "')", $tmp2);
				$tmp .= $tmp2 . "\r\n";
			} else {
				$tmp .= $line . "\r\n";
			}
			$line = strtok($sep);
		}
        $data["classification_detail"] = $tmp;
        $this->builder = $this->db->table('cms_classification');
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_classification_filter($data){
        $this->builder = $this->db->table('cms_classification_filter');
        $return = $this->builder->insert($data);
        return $return;
    }
    function edit_classification_filter($filter, $class_id){
        $this->builder = $this->db->table('cms_classification_filter');
        $this->builder->where('class_id', $class_id);
        $this->builder->delete();

        $this->builder->insert($filter);
    }
    function save_classification_edit($data){
        $sep = "\r\n";
		$tmp = "";

		$line = strtok($data["classification_detail"], $sep);
		while ($line !== false) {
			if (strpos($line, "IN('") !== false) {
				//hilangkan '
				$tmp2 = str_replace("'", "", $line);
				//tambah ',';
				$tmp2 = str_replace(",", "','", $tmp2);
				$tmp2 = str_replace("(", "('", $tmp2);
				$tmp2 = str_replace(")", "')", $tmp2);
				$tmp .= $tmp2 . "\r\n";
			} else {
				$tmp .= $line . "\r\n";
			}
			$line = strtok($sep);
		}
		$data["classification_detail"] = $tmp;
        $this->builder = $this->db->table('cms_classification');
        $this->builder->set('classification_detail', $data["classification_detail"]);
		$this->builder->set('class_priority', $data["class_priority"]);
		$this->builder->set('check_number', $data["check_number"]);

		$this->builder->set('classification_name', $data["classification_name"]);
		$this->builder->set('classification_json', $data["classification_json"]);
		$this->builder->set('update_detail', $data["update_detail"]);
		$this->builder->set('description', $data["description"]);
		$this->builder->set('update_detail_json', $data["update_detail_json"]);
		$this->builder->set('job_schedule', $data["job_schedule"]);
		$this->builder->set('weekly_day', $data["weekly_day"]);
		$this->builder->set('effective_date', $data["effective_date"]);
		$this->builder->set('order_by_detail', $data["order_by_detail"]);
		$this->builder->set('spesific_date', $data["spesific_date"]);
		$this->builder->set('allocation_type', $data["allocation_type"]);
		$this->builder->set('class_category', $data["class_category"]);
		$this->builder->set('start_date', $data["start_date"]);
		$this->builder->set('end_date', $data["end_date"]);
		// $this->builder->set('is_reset_allocation', $data["is_reset_allocation"]);
		$this->builder->set('updated_time', date('Y-m-d H:i:s'));
		$this->builder->set('updated_by', session()->get('USER_ID'));
		$this->builder->set("assignment_duration", $data["assignment_duration"]);
		$this->builder->set("expired_time", $data["expired_time"]);
		// $this->builder->set("distribution_method", $data["distribution_method"]);
		$this->builder->set("class_expired_date", $data["class_expired_date"]);
		$this->builder->set("schedule_detail", $data["schedule_detail"]);
		$this->builder->set('account_handling', $data["account_handling"]);
		$this->builder->set('sms_template', $data["sms_template"]);
		$this->builder->set('email_template', $data["email_template"]);
		$this->builder->where('classification_id', $data["classification_id_old"]);
		// $this->builder->set('wa_template', $data["wa_template"]);
		$this->builder->set('class_type', $data["class_type"]);
        $return = $this->builder->update();
        return $return;
    }
    function delete_classification($id){
        $this->builder = $this->db->table('cms_classification');
        $return = $this->builder->where('classification_id', $id)->delete();
        return $return;
    }
    function get_classification_test_current($classification_id){
        $builder = $this->db->table('cms_classification');
        $builder->select('classification_name,classification_detail,classification_id,classification_json');
        $builder->where('classification_id', $classification_id);
        $query = $builder->get();
        if ($query->getNumRows())
        {
            foreach ($query->getResult() as $row)
            {
                foreach ($row as $key => $value){
                $data_class[$key] = $value;  
            }
            }
        }

        $sql_detail = $data_class["classification_detail"];
        $sql_detail = str_replace("\\", '', $sql_detail);
        $sql_detail = str_replace("''", "'", $sql_detail);
        $sql_detail = str_ireplace("CLASS", "a.CLASS", $sql_detail);
        $data_class["classification_detail"] = $sql_detail; 
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
            'a.CM_CUSTOMER_NMBR,
            a.AGENT_ID,
            a.CLASS,
            a.BILL_BAL,
            a.CM_BUCKET,
            a.CM_CARD_NMBR,
            a.CM_DTE_PYMT_DUE,
            a.DPD,
            a.CM_CARD_EXPIR_DTE,
            a.CM_DTE_LST_PYMT,
            a.CM_TOT_BALANCE,
            a.CM_CYCLE,
            a.AGENT_ID,
            a.CM_CRLIMIT,
            a.CM_TENOR,
            a.CM_INSTALLMENT_AMOUNT,
            a.CM_OS_PRINCIPLE,
            a.CM_COLLECTIBILITY,
            a.CM_CHGOFF_STATUS_FLAG,
            a.CM_INSTALLMENT_NO,
            a.CR_OCCUPATION,
            a.CM_DOMICILE_BRANCH,
            b.ptp_date,b.CLASS_RECALL'
        );
        $this->builder->join('cms_account_last_status b', 'a.CM_CARD_NMBR=b.account_no');


        $this->builder->where($data_class["classification_detail"]);
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
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
    function apply_classification($id){
		$data = $this->Common_model->get_record_values("classification_name,classification_detail, update_detail, update_detail_json", "cms_classification", "classification_id='$id'", "");
        $sql_detail = $data["classification_detail"];
		$sql_detail = str_replace("\\", '', $sql_detail);
		$sql_detail = str_replace("''", "'", $sql_detail);

        $builder2 = $this->db->table('cpcrd_new');
        if (!empty($data['update_detail'])) {
            $builder2->set('cpcrd_new.CLASS', $id);
            $builder2->set($data["update_detail"]);
            $builder2->join('cms_account_last_status', 'cpcrd_new.CM_CARD_NMBR=cms_account_last_status.account_no');
            $builder2->where($sql_detail);
        } else {
            $builder2->set('cpcrd_new.CLASS', $id);
            $builder2->join('cms_account_last_status', 'cpcrd_new.CM_CARD_NMBR=cms_account_last_status.account_no');
            $builder2->where($sql_detail);
        }
        $return = $builder2->update();
        $sql_drop = "DROP TABLE IF EXISTS tmp_customer";
		$this->db->query($sql_drop);
        $sql_create = "CREATE TABLE tmp_customer 
							(PRIMARY KEY (CM_CARD_NMBR),
						INDEX CM_CUSTOMER_NMBR (CM_CUSTOMER_NMBR),
						INDEX CM_TOT_AMNT_CHARGE_OFF (CM_TOT_AMNT_CHARGE_OFF),
						INDEX AGENT_ID (AGENT_ID),
						INDEX CM_KANWIL_CODE (CM_KANWIL_CODE),
						INDEX CM_ZIP_REC (CM_ZIP_REC),
						INDEX CR_ACCT_NBR (CR_ACCT_NBR),
						INDEX SUM_WO_BALANCE (SUM_WO_BALANCE),
						INDEX CIF_REGIONAL (CIF_REGIONAL),
						INDEX CIF_WO_DATE (CIF_WO_DATE),
						INDEX CIF_ZIP_REC (CIF_ZIP_REC),
						INDEX account_no (account_no),
						INDEX class (class),
						INDEX last_response (last_response),
						INDEX cif_number (cif_number),
						INDEX fin_acc (fin_acc),
						INDEX last_agent1 (last_agent1),
						INDEX last_agent2 (last_agent2),
						INDEX last_agent3 (last_agent3),
						INDEX class_time (class_time),
						INDEX CF_COUNT_BR (CF_COUNT_BR),
						INDEX CF_PAYMENTFLAG (CF_PAYMENTFLAG),
						INDEX CF_KEEP_EXTEND (CF_KEEP_EXTEND),
						INDEX CF_COUNT_MDO (CF_COUNT_MDO),
						INDEX CF_REQUEST_EXTEND (CF_REQUEST_EXTEND),
						INDEX CF_SCORING (CF_SCORING),
						INDEX CF_ACCOUNT_GROUP (CF_ACCOUNT_GROUP),
						INDEX CF_ACCOUNT_STAY (CF_ACCOUNT_STAY),
						INDEX CF_EXTEND_VALUE (CF_EXTEND_VALUE),
						INDEX CF_ACCOUNT_STATUS (CF_ACCOUNT_STATUS),
						INDEX CF_ACCOUNT_REQUEST (CF_ACCOUNT_REQUEST),
						INDEX CF_USER_REQUEST (CF_USER_REQUEST),
						INDEX CF_USER_ACCOUNT_GROUP (CF_USER_ACCOUNT_GROUP))
						ENGINE=MyISAM
						SELECT 
								CM_ORG_NMBR,
								CM_TYPE,
								CM_CARD_NMBR,
								CM_SHORT_NAME,
								CM_CUSTOMER_ORG,
								CM_CUSTOMER_NMBR,
								CM_ACH_RT_NMBR,
								CM_SAVINGS_ACCT,
								CM_DOMICILE_BRANCH,
								CM_OLC_REASON,
								CM_STATUS,
								CM_DTE_PYMT_DUE,
								CM_AMOUNT_DUE,
								CM_RTL_BEG_BALANCE,
								CM_CASH_BEG_BALANCE,
								CM_TOT_BEG_BALANCE,
								CM_CRLIMIT,
								CM_OL_AMT_DUE,
								CM_RTL_BALANCE,
								CM_CASH_BALANCE,
								CM_TOT_BALANCE,
								CM_DTE_LST_STMT
								CM_RTL_AMNT_CHARGE_OFF,
								CM_CASH_AMNT_CHARGE_OFF,
								CM_TOT_AMNT_CHARGE_OFF,
								CM_DTE_CHGOFF_STAT_CHANGE,
								CM_CHGOFF_RSN_CD_1,
								CM_CHGOFF_RSN_CD_2,
								CM_CHGOFF_STATUS_FLAG,
								CM_WRITE_OFF_DAYS,
								CM_BLOCK_CODE,
								CM_DTE_BLOCK_CODE,
								CM_ALT_BLOCK_CODE,
								CM_DTE_ALT_BLOCK_CODE,
								CR_EU_CUSTOMER_CLASS,
								CR_NAME_1,
								CR_NAME_2,
								CR_NAME_3,
								CM_EMBOSSER_NAME_1,
								CR_FOREIGN_COUNTRY_IND,
								CR_EU_SEX,
								CM_DTE_LST_PYMT,
								CM_ACH_DB_NMBR,
								CM_CYCLE,
								CM_DTE_INTO_COLLECTION,
								CR_BUSINESS_CODE,
								CM_DTE_LST_PURCHASE,
								CM_AMNT_LST_PURCH,
								CM_DTE_LST_ADVANCE,
								CM_AMNT_LST_ADV,
								CM_REAGE_REQUEST,
								CM_DTE_LST_REAGE,
								CR_APPL_ID,
								CM_DTE_OPENED,
								CM_CASH_IBNP,
								CM_CASH_SVC_BNP,
								CM_RTL_IBNP,
								CM_RTL_SVC_BNP,
								CM_RTL_MISC_FEES,
								CM_RTL_INSUR_BNP,
								CM_RTL_MEMBER_BNP,
								CM_TOT_RTL_PRINCIPAL,
								CM_TOT_CASH_PRINCIPAL,
								CM_TOT_PRINCIPAL,
								CM_AVAIL_CREDIT,
								CM_HI_BALANCE,
								CM_DTE_HI_BALANCE,
								CM_POSTING_FLAG,
								CM_POSTING_ACCT_ORGN,
								CM_POSTING_ACCT_TYPE,
								CM_POSTING_ACCT_NMBR,
								CM_USER_CODE_2,
								CM_OLC_REASON_DESC,
								CM_CHGOFF_STATUS_FLAG_DESC,
								CM_STATEMENT_FLAG_DESC,
								CM_STATUS_DESC,
								CM_BUCKET,
								AGENCY_ID,
								cms_account_last_status.assigned_agent AGENT_ID,
								CM_INTR_PER_DIEM,
								CREDIT_SCORING,
								CM_CASH_ACCRUED_INTR,
								CM_RTL_ACCRUED_INTR,
								CM_INSTL_BAL,
								CM_INSTL_LIMIT,
								CM_AMNT_OUTST_INSTL,
								CM_AVAIL_INSTL,
								CM_LST_PYMT_AMNT,
								CM_USAGE,
								CM_KANWIL_CODE,
								CM_COLLECTION_CENTER,
								CM_ZIP_REC,
								CM_REAGE_LANCAR,
								AGENCY_START_DATE,
								AGENCY_END_DATE,
								CM_FIXED_PYMT_AMNT,
								fin_account,
								IC_NO,
								CUST_CIF,
								BILLING_ADDR_CODE1,
								ACCT_NBR,
								FLD_CHAR_2,
								FLD_CHAR_15,
								ANNIV_DATE,
								FLD_CHAR_15_1,
								INTRODUCER,
								INTRODUCER_NAME,
								BILLING_ADDR_CODE,
								FLD_DATE_5,
								FLD_DATE_6,
								FLD_DATE_7,
								LEGAL_DATE,
								FRAUD_DATE,
								WS_FLD_DATE_4,
								REINSTATE_DATE,
								FLD_DATE_2,
								NEW_EXPIRY,
								NEW_PLASTIC_CODE,
								NEW_PLASTIC_CODE_DATE,
								TC_LAST_PERFORMED,
								FLD_DATE_1,
								TC_LAST_SYST_GENERATED_TRX,
								DATE_LAST_SYST_GENERATED_TRX,
								PAY_DD_FLAG,
								DD_BANK,
								DD_ACC,
								CASA_ACCT_NAME,
								FLD_DEC_1,
								CURMTH_PAYDUE_DATE,
								CURRMTH_BAL_X,
								DPD,
								OPS_DEF_BAL,
								PRICING_PROFILE_CODE,
								RISK_PROFILE_CODE,
								EXCEED_LIMIT_ALLOWED,
								CREDIT_UTILIZATION,
								BILL_BAL,
								RISK_SCODE,
								FPD,
								RESERVED_ADDITIONAL_FIELD,
								RESERVED_ADDITIONAL_FIELD2,
								ADDITIONAL_RULE_1,
								ADDITIONAL_RULE_2,
								ADDITIONAL_RULE_3,
								ADDITIONAL_RULE_4,
								ADDITIONAL_RULE_5,
								CUSTOM_CIF,
								CR_ORG_NBR,
								CR_ACCT_NBR,
								CR_ADDR_1,
								CR_ADDR_2,
								CR_ADDR_3,
								CR_ADDR_4,
								CR_ADDR_5,
								CR_ADDL_ADDR_1,
								CR_ADDL_ADDR_2,
								CR_ADDL_ADDR_3,
								CR_ADDL_USAGES1,
								CR_HOME_PHONE,
								CR_OFFICE_PHONE,
								CR_CO_OWNER,
								CR_CO_HOME_PHONE,
								CR_CO_OFFICE_PHONE,
								CR_CITY,
								CR_DTE_BIRTH,
								CR_CO_EMPLOYER,
								CR_EMPLOYER,
								CR_EU_SURN,
								CR_MEMO_1,
								CR_MEMO_2,
								CR_MEMO_3,
								CR_MEMO_4,
								CR_MEMO_5,
								CR_MEMO_6,
								CR_ADDL_EMAIL,
								CR_PLACE_OF_BIRTH,
								CR_TAX_ID_NMBR,
								CR_ZIP_CODE,
								CR_ADDL_ZIP_EXP,
								CR_ADDL_USAGES2,
								CR_ADDL_USAGES3,
								CR_ADDL_USAGES4,
								CR_ADDL_USAGES5,
								CR_ADDL_USAGES6,
								CR_ADDL_USAGES7,
								CR_ADDL_USAGES8,
								CR_ADDL_USAGES9,
								CR_ADDL_USAGES10,
								CR_ADDL_USAGES11,
								CR_ADDL_USAGES12,
								CR_MOTHER_NM,
								CR_C_ADDR_L1,
								CR_C_ADDR_L2,
								CR_C_ADDR_L3,
								CR_C_ADDR_L4,
								CR_C_ADDR_L5,
								CR_C_CITY,
								CR_C_ZIP_CODE,
								CR_C_FAX,
								CUST_GLOCAL_NAME,
								CUST_GRLNSHIP,
								IND_CARD_INDIVIDUAL,
								CIF_FLAG,
								SUM_WO_BALANCE,
								CIF_REGIONAL,
								CIF_WO_DATE,
								CIF_ZIP_REC,
								cms_account_last_status.class LAST_CLASS,
								cms_account_last_status.assigned_agent LAST_AGENT_ID,
								assignment_end_date as last_assignment_end_date,
								cms_account_last_status.*
								FROM cpcrd_new JOIN cms_account_last_status
								WHERE cpcrd_new.CM_CARD_NMBR=cms_account_last_status.account_no and cpcrd_new.CLASS = '$id';";
		$res_create	= $this->db->query($sql_create);

        $builder3 = $this->db->table('cms_classification');
        $builder3->select('classification_id,classification_name,classification_detail,assigned_agent,update_detail,job_schedule,weekly_day,is_reset_allocation,effective_date,class_expired_date,start_date,end_date,spesific_date,allocation_type,assignment_duration,expired_time,distribution_method,schedule_detail,order_by_detail,assignment_weight');
        $builder3->where('classification_id', $id);
        $rResult = $builder3->get()->getResultArray();
        $seq_br = 0;
        foreach ($rResult as $aRow) {
			$this->doBR_by_account($aRow, $seq_br);
		}
		$custom_field = $this->Common_model->get_record_list("field_name value, field_name AS item", "cc_custom_fields", "field_name is not null", "field_name");
		//TODO : jika assignment type temporer simpan assigned awalnya dan tempelkan agent temporernya
        if ($aRow['allocation_type'] == 'TEMPORER') {
			$set = 'cpcrd_new.AGENT_ID = tmp_customer.temp_assigned_agent, 
			cpcrd_new.CLASS = tmp_customer.CLASS, 
			cpcrd_new.LAST_CLASS = tmp_customer.LAST_CLASS, 
			cpcrd_new.CM_OLC_REASON_DESC = tmp_customer.CM_OLC_REASON_DESC, 
			cms_account_last_status.class = tmp_customer.CLASS, 
			cms_account_last_status.class_time = tmp_customer.class_time, 
			cms_account_last_status.priority_br = tmp_customer.priority_br, cms_account_last_status.last_agent1 = tmp_customer.last_agent1, cms_account_last_status.last_agent2 = tmp_customer.last_agent2, cms_account_last_status.last_agent3 = tmp_customer.last_agent3 ,cms_account_last_status.temp_assigned_agent = tmp_customer.temp_assigned_agent,cms_account_last_status.temp_assignment_date_end = tmp_customer.temp_assignment_date_end,
			cms_account_last_status.assignment_start_date = tmp_customer.assignment_start_date,cms_account_last_status.assignment_end_date = tmp_customer.assignment_end_date,
			,cms_account_last_status.CLASS_TMP = tmp_customer.CLASS_TMP,
			,cms_account_last_status.CLASS_RECALL = tmp_customer.CLASS_RECALL';
		} else {
			$set = 'cpcrd_new.AGENT_ID = tmp_customer.AGENT_ID,
			cms_account_last_status.last_agent1 = tmp_customer.AGENT_ID, 
			cpcrd_new.CLASS = tmp_customer.CLASS, 
			cpcrd_new.LAST_CLASS = tmp_customer.LAST_CLASS, 
			cpcrd_new.CM_OLC_REASON_DESC = tmp_customer.CM_OLC_REASON_DESC, 
			cms_account_last_status.class = tmp_customer.CLASS, 
			cms_account_last_status.class_time = tmp_customer.class_time, 
			cms_account_last_status.priority_br = tmp_customer.priority_br, 
			cms_account_last_status.last_agent1 = tmp_customer.last_agent1, 
			cms_account_last_status.last_agent2 = tmp_customer.last_agent2, 
			cms_account_last_status.last_agent3 = tmp_customer.last_agent3,
			cms_account_last_status.temp_assigned_agent = tmp_customer.temp_assigned_agent,
			cms_account_last_status.temp_assignment_date_end = tmp_customer.temp_assignment_date_end,
			cms_account_last_status.assignment_start_date = tmp_customer.assignment_start_date,
			cms_account_last_status.assignment_end_date = tmp_customer.assignment_end_date,
			cms_account_last_status.CLASS_TMP = tmp_customer.CLASS_TMP,
			cms_account_last_status.CLASS_RECALL = tmp_customer.CLASS_RECALL,
			cms_account_last_status.assigned_agent = tmp_customer.AGENT_ID';


		}
        $l = 0;
		foreach ($custom_field as $k => $v) {
			if (empty($set)) {
				$set .= ' ,cms_account_last_status.' . $v . '=tmp_customer.' . $v;
			} else {
				$set .= ' ,cms_account_last_status.' . $v . '=tmp_customer.' . $v;
			}
			$l++;
		}
        $sql = "update tmp_customer, cms_account_last_status, cpcrd_new set " . $set . " where tmp_customer.account_no = cms_account_last_status.account_no and tmp_customer.CM_CARD_NMBR = cpcrd_new.CM_CARD_NMBR";
		// echo "$sql <br/>";
		$this->db->query($sql);
		$sql = "update cpcrd_new,tmp_customer set cpcrd_new.AGENT_ID = tmp_customer.temp_assigned_agent where tmp_customer.CM_CARD_NMBR = cpcrd_new.CM_CARD_NMBR and tmp_customer.temp_assigned_agent is not null and tmp_customer.temp_assignment_date_end >= curdate()";
		$this->db->query($sql);

        return $return;
    }
    function doBR_by_account($aRow, $seq_br){
        $class_id = $aRow['classification_id'];
		$assigned_agent = $aRow['assigned_agent'];
		$class_time = date('Y-m-d H:i:s');
        //todo : remove agent on leave first
		/*
		allocation_type:
			- PERMANENT : mengikat ke agent yg ditunjuk sampai periode assignment berakhir
			- TEMPORER : assign ke  temporer agent - actual agent tetap sampai masa assignment berakhir, setelah habis kembalikan ke agent semula
			- RECALL : Hapus agent yg diassign / CLASS ID tetap di class id semula / tidak dioveride	
		
		
		*/

        // echo "<hr>";
		// echo "<hr>";
        if ($aRow['is_reset_allocation'] == '1') {
            $this->builder = $this->db->table('tmp_customer');
            $this->builder->set('last_agent3', 'last_agent2'); // false untuk menghindari penanganan pemisahan set
            $this->builder->set('last_agent2', 'last_agent1'); // false untuk menghindari penanganan pemisahan set
            $this->builder->set('last_agent1', 'AGENT_ID');
            $this->builder->set('LAST_CLASS', 'CLASS');
            $this->builder->set('AGENT_ID', '');
            $this->builder->set('CLASS', null);
            $this->builder->where('CLASS', $aRow['classification_id']);
            $res_history = $this->builder->update();

            $data = [
                'status_time' => date('Y-m-d H:i:s'),
                'description' => 'Reset Agent ID and Class With CLASS ', $class_id,
                'status_type' => 'BOD_CMS'
            ];
            $this->db->table('bod_log')->insert($data);
        } else {
            $this->builder = $this->db->table('tmp_customer');
            $this->builder->set('last_agent3', 'last_agent2');
            $this->builder->set('last_agent2', 'last_agent1');
            $this->builder->set('last_agent1', 'AGENT_ID');
            $this->builder->set('LAST_CLASS', 'CLASS');
            $sql_history = $this->builder->where('CLASS', $aRow['classification_id']);
        }
        switch ($aRow['assignment_duration']) {
            case 'INDEFINITE':
                $time_type = "DAY";
				$expire = 0;
				break;
			default:
				$time_type = $aRow['assignment_duration'];
				$expire = $aRow['expired_time'];
				break;
        }

        #Do Bussiness Rule
        switch ($aRow['allocation_type']) {
            case 'RECALL':
                $this->builder = $this->db->table('cms_classification');
                $this->builder->select('classification_name');
                $this->builder->where('classification_id', $class_id);
                $query = $this->builder->get();
                if ($query->getNumRows())
                {
                    foreach ($query->getResult() as $row)
                    {
						foreach ($row as $key => $value){
							$class_name[$key] = $value;  
                        }
                    }
                }
                $notes = 'Has entered into Business Rule ' . implode(', ', $class_name);

                $sql = "insert into `cms_contact_history`(`id`,`account_no`,`customer_no`,`user_id`,`input_source`,`notes`,`created_by`,`created_time`,fin_acc) select uuid(),a.CM_CARD_NMBR,a.CM_CUSTOMER_NMBR,'system','BISNIS_RULE', concat('" . $notes . "',if(AGENT_ID is null,'',concat('\n -assigned to: ',AGENT_ID))  ),'system', now(),fin_account from tmp_customer a where " . $aRow["classification_detail"];
				// echo "$sql <br/>";
				$this->db->query($sql);

                $sql = "update tmp_customer set  AGENT_ID = '',assignment_end_date = curdate() - interval 1 day , class_time='$class_time',class_duration = 'RECALL' where  " . $aRow["classification_detail"];
                break;
            case 'TEMPORER':
                $sql = "update tmp_customer set CLASS_TMP='" . $aRow['classification_id'] . "',  class_time='$class_time' where  " . $aRow["classification_detail"];
                break;
            case 'PERMANEN':
            default:
                $this->builder = $this->db->table('cms_classification');
                $this->builder->select('classification_name');
                $this->builder->where('classification_id', $class_id);
                $query = $this->builder->get();
                if ($query->getNumRows())
                {
                    foreach ($query->getResult() as $row)
                    {
						foreach ($row as $key => $value){
							$class_name[$key] = $value;  
                        }
                    }
                }
                $notes = 'Has entered into Business Rule ' . implode(', ', $class_name);
                $sql = "insert into `cms_contact_history`(`id`,`account_no`,`customer_no`,`user_id`,`input_source`,`notes`,`created_by`,`created_time`,fin_acc) select uuid(),a.CM_CARD_NMBR,a.CM_CUSTOMER_NMBR,'system','BISNIS_RULE', concat('" . $notes . "',if(AGENT_ID is null,'',concat('\n -assigned to: ',AGENT_ID))  ),'system', now(),fin_account from tmp_customer a where " . $aRow["classification_detail"];
				// echo "$sql <br/>";
				//$this->db->query($sql);

				if (!empty($assigned_agent)) {

					$sql = "update tmp_customer set CLASS='" . $aRow['classification_id'] . "',CLASS_TMP='" . $aRow['classification_id'] . "', priority_br='" . $seq_br . "', class_time='$class_time',assignment_start_date = curdate(),class_duration = '" . $aRow['assignment_duration'] . "'  where 
					(AGENT_ID IS NULL OR AGENT_ID ='' OR ( assignment_end_date < curdate() )  OR date(class_time) = curdate() )  AND " . $aRow["classification_detail"];

					//20200312 : Tri : set assignment end date nya setelah diassign 
				} else {
					$sql = "update tmp_customer set  class_time='$class_time'  where 
					(AGENT_ID IS NULL OR AGENT_ID ='' OR ( assignment_end_date < curdate() ) OR date(class_time) = curdate() )  AND " . $aRow["classification_detail"];
				}
                break;
        }
        // echo $sql . "<br/>\n";
		$res	= $this->db->query($sql);
		$affct_rows = $this->db->affectedRows();
		$data_history = array(
			'action' => 'Set Class',
			'br_name' => $class_id,
			'sql' => $sql,
			'count' => $affct_rows,
			'created_by`' => 'system',
			'created_time' => date("Y-m-d H:i:s")
		);
        $this->builder = $this->db->table('tmp_history_br');
        $this->builder->insert($data_history);

        if (!empty($assigned_agent)) {
			// echo 'Do Assignment Agent<br/>';
			#Keep Assign To Last Agent
			$is_skip_assignment = 0;
			$arr_agent_req = explode(',', $assigned_agent);
			$arr_weight = explode('|', $aRow['assignment_weight']);
			$order_by = $aRow['order_by_detail'];
			if ((trim($order_by) == "")) {
				$order_by = " CM_AMOUNT_DUE DESC";
			}

			//todo : remove agent on leave first
            $this->builder = $this->db->table('cc_user');
            $this->builder->select('id');
            $this->builder->where('CURDATE() BETWEEN leave_start_date AND leave_end_date');
            $query = $this->builder->get();
			if ($query->getNumRows() > 0) {
				foreach ($query->getRowArray() as $k => $v) {
					if (in_array($v, $arr_agent_req)) {
						unset($arr_agent_req[array_search($v, $arr_agent_req)]);  //cek dulu	
					}
				}
			}


			#Shuffle Array Agent
			$arr_agent_shuffle = explode(',', $assigned_agent);
			shuffle($arr_agent_shuffle);
			// echo "Assigned Class With Scoring > 0 <br>";
			if (!$is_skip_assignment) {
				for ($loop = 0; $loop < 1; $loop++) {
					$sql = " truncate tmp_agent_account_assignment";
					// echo "$sql <br/>";
					$this->db->query($sql);


					$arr_agent_more = $arr_agent_shuffle;
					$count_agent = count($arr_agent_more);

					if ($aRow['distribution_method'] == 'REVERSE_ROUND_ROBIN') {
						$sql = "truncate tmp_agent_os";
						$this->db->query($sql);

						if ($aRow['allocation_type'] == "TEMPORER") {
							$sql = "insert into `tmp_agent_account_assignment`(`account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence`) select CM_CARD_NMBR,CLASS,IFNULL(last_agent1,''),IFNULL(last_agent2,''),IFNULL(last_agent3,''), '1' as sequence from tmp_customer where CLASS_TMP = '" . $class_id . "'  and date(class_time) = curdate() AND " . $aRow["classification_detail"] . " order by LAST_CLASS, " . $order_by;
						} else {
							if ($aRow['assignment_duration'] == "INDEFINITE") {

								$sql = "insert into `tmp_agent_account_assignment`(`account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence`) select CM_CARD_NMBR,CLASS,IFNULL(last_agent1,''),IFNULL(last_agent2,''),IFNULL(last_agent3,''), '1' as sequence from tmp_customer where CLASS = '" . $class_id . "' and assignment_end_date < curdate() and date(class_time) = curdate() AND " . $aRow["classification_detail"] . " order by  LAST_CLASS," . $order_by;
							} else {
								$sql = "insert into `tmp_agent_account_assignment`(`account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence`) select CM_CARD_NMBR,CLASS,IFNULL(last_agent1,''),IFNULL(last_agent2,''),IFNULL(last_agent3,''), '1' as sequence from tmp_customer where CLASS = '" . $class_id . "'  and assignment_end_date < curdate() and date(class_time) = curdate() AND " . $aRow["classification_detail"] . " order by  LAST_CLASS," . $order_by;
							}
						}

						// echo "$sql <br/>";
						$this->db->query($sql);
					} else {
						if ($aRow['allocation_type'] == "TEMPORER") {
							$sql = "insert into `tmp_agent_account_assignment`(`account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence`) select CM_CARD_NMBR,CLASS,IFNULL(last_agent1,''),IFNULL(last_agent2,''),IFNULL(last_agent3,''), '1' as sequence from tmp_customer where CLASS_TMP = '" . $class_id . "'  and date(class_time) = curdate() AND " . $aRow["classification_detail"] . " order by  LAST_CLASS," . $order_by;
						} else {
							if ($aRow['assignment_duration'] == "INDEFINITE") {
								//								$sql = "update tmp_customer set AGENT_ID = last_agent1 where CLASS = LAST_CLASS and class_duration = 'INDEFINITE' and last_agent1 is not null and CLASS = '".$class_id."' ";

								// echo "$sql <br/>";
								//								$this->db->query($sql); 

								//								$sql = "insert into `tmp_agent_account_assignment`(`account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence`) select CM_CARD_NMBR,CLASS,IFNULL(last_agent1,''),IFNULL(last_agent2,''),IFNULL(last_agent3,''), '1' as sequence from tmp_customer where CLASS = '".$class_id."'  and (CLASS != LAST_CLASS OR last_class is null)  and assignment_end_date < curdate()  order by ".$order_by;								

								$sql = "insert into `tmp_agent_account_assignment`(`account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence`) select CM_CARD_NMBR,CLASS,IFNULL(last_agent1,''),IFNULL(last_agent2,''),IFNULL(last_agent3,''), '1' as sequence from tmp_customer where CLASS = '" . $class_id . "'  and assignment_end_date < curdate() and date(class_time) = curdate() AND " . $aRow["classification_detail"] . " order by  LAST_CLASS," . $order_by;
							} else {
								$sql = "insert into `tmp_agent_account_assignment`(`account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence`) select CM_CARD_NMBR,CLASS,IFNULL(last_agent1,''),IFNULL(last_agent2,''),IFNULL(last_agent3,''), '1' as sequence from tmp_customer where CLASS = '" . $class_id . "' and assignment_end_date < curdate() and date(class_time) = curdate() AND " . $aRow["classification_detail"] . " order by  LAST_CLASS," . $order_by;
							}
						}
						//echo "$sql <br/>";
						$this->db->query($sql);
					}


					// $sql15 = "select count(DISTINCT CM_CUSTOMER_NMBR) from tmp_customer where CLASS = '".$class_id."' AND AGENT_ID IS NULL  AND CM_CUSTOMER_NMBR <> ''";
					// $res15 = 	$this->db->query($sql15);
					// $count_total_account = array_pop($res15->row_array());

					$sql = "truncate tmp_agent";
					$query = 	$this->db->query($sql);

					$i = 0;
					$arr_quota = array();
					foreach ($arr_agent_more as $k => $v) {
						$data = array(
							'user_id' => $v,
							'seq' => '1',
							'quota' => @$arr_weight[$k]
						);
                        $this->builder = $this->db->table('tmp_agent');
						$this->builder->insert($data);
					}
					$arr_agent_more = array_reverse($arr_agent_more);
					// $total_quota_class = 0;
					foreach ($arr_agent_more as $k => $v) {
						if ($aRow['distribution_method'] == 'REVERSE_ROUND_ROBIN') {
							$data = array(
								'user_id' => $v,
								'seq' => '2',
							);
                            $this->builder = $this->db->table('tmp_agent');
                            $this->builder->insert($data);
						}

						/* //201907-19 : TRI : NGGAK NGITUNG KUOTA START
						$quota = 0;
						$sql11 = "select a.kuota_assignment from cc_user_group a join cc_user b on a.id=b.group_id where b.id = '$v'";
						$query11 = 	$this->db->query($sql11);
						if($query11->num_rows() > 0){
							$quota = array_pop($query11->row_array());
						}
						// $total_quota_class = $total_quota_class+$quota;
						$sql5 = "select count(*) from tmp_customer where AGENT_ID = '".$v."'";
						$res5 = 	$this->db->query($sql5);
						$count_used_assigment = array_pop($res5->row_array());
						
						$sisa_quota = $quota - $count_used_assigment;
						
						if($sisa_quota >= 0){
							$quota_real =$sisa_quota;
						}
						else{
							$quota_real = 0;
						}
						$sql22 = "replace into tmp_history_assignment values ('$class_id', '$v', '$quota_real', '$count_used_assigment','0','0','$loop', null, now())";
						$query22 = 	$this->db->query($sql22);
						
						if($sisa_quota >= 0){
							$quota_actual = round($sisa_quota/2);
						}
						else{
							$quota_actual = 0;
						}
						$data_quota = array(
							'quota' => $quota_actual
						);
						$this->db->where('user_id', $v);	
						$this->db->where('seq', '1');	
						$this->db->update('tmp_agent', $data_quota);	
						
						
						if($sisa_quota >= 0){
							$quota_actual2 = $sisa_quota - $quota_actual;
						}
						else{
							$quota_actual2 = 0;
						}
						$data_quota2 = array(
							'quota' => $quota_actual2
						);
						$this->db->where('user_id', $v);	
						$this->db->where('seq', '2');	
						$this->db->update('tmp_agent', $data_quota2);	
						*/
						//201907-19 : MGGAK NGITUNG KUOTA END						
					}

                    $this->builder = $this->db->table('tmp_agent');
                    $this->builder->select('*');
                    $this->builder->orderBy('id');
					$res3 = $this->builder->get();
					$count_agent_double = $res3->getNumRows();
					$c = 0;
					foreach ($res3->getResultArray() as $aRow2) {
						// if($count_total_account < $total_quota_class){
						// $limit = ceil($count_total_account/($count_agent*2));
						// }
						// else{
						$limit = $aRow2['quota'];
						// }
						//echo "Assign to ".$aRow2['user_id']."<br/>\n";
						switch ($aRow['distribution_method']) {
							case "ROUND_ROBIN":
							case "REVERSE_ROUND_ROBIN":
								$sql = "update tmp_agent_account_assignment set user_id='" . $aRow2['user_id'] . "' where (id-1) % " . $count_agent_double . " = " . $c;
								// echo "$sql <br>";
								$res_07 = $this->db->query($sql);

								break;
							case "TOTAL_ACCOUNT":
                                $this->builder = $this->db->table('tmp_customer');
                                $this->builder->select('count(*)');
                                $this->builder->where('classification_id', $class_id);
                                $query = $this->builder->get();
                                if ($query->getNumRows())
                                {
                                    foreach ($query->getResult() as $row)
                                    {
                                        foreach ($row as $key => $value){
                                            $total_account_class[$key] = $value;  
                                        }
                                    }
                                }
								$weight = ceil($total_account_class * $limit / 100);
								$sql = "update tmp_agent_account_assignment set user_id='" . $aRow2['user_id'] . "' where user_id is null limit $weight";
								//echo "$sql <br>";
								$res_07 = $this->db->query($sql);

								break;
							case "TOTAL_OS":
								break;
						}
						$assignment = $this->db->affectedRows();

						$sql23 = "update tmp_history_assignment set assignment='" . $assignment . "',limit_quota='" . $aRow2['quota'] . "' where class = '" . $class_id . "' AND user_id = '" . $aRow2['user_id'] . "' and seq = '" . $loop . "'";
						$this->db->query($sql23);
						$c++;
					}
					//(AGENT_ID IS NULL OR (assignment_start_date <= curdate() and assignment_end_date >= curdate() ) ) 				
					$sql = "insert into tmp_agent_history (user_id,quota,seq,class,insert_time) select user_id, quota, seq, '$class_id' as class, now() as insert_time from tmp_agent;";
					$this->db->query($sql);

					switch ($aRow['assignment_duration']) {
						case "INDEFINITE":
							$time_type = "DAY";
							$expire = 0;

							break;
						default:
							$time_type = $aRow['assignment_duration'];
							$expire = $aRow['expired_time'];
							break;
					}

					if ($aRow['allocation_type'] == "TEMPORER") {
						$sql = "UPDATE tmp_agent_account_assignment AS a, tmp_customer AS b
								SET b.temp_assigned_agent = user_id, b.temp_assignment_date_end = CURDATE() + INTERVAL $expire $time_type
								WHERE a.account_no = b.CM_CARD_NMBR";
					} else {
						$sql = "UPDATE tmp_agent_account_assignment AS a, tmp_customer AS b
								-- SET a.AGENT_ID = user_id, b.assignment_start_date = CURDATE(), b.assignment_end_date = CURDATE() + INTERVAL $expire $time_type
								SET b.assignment_start_date = CURDATE(), b.assignment_end_date = CURDATE() + INTERVAL $expire $time_type
								WHERE a.account_no = b.CM_CARD_NMBR";
					}
					// echo "$sql <br/>";
					$this->db->query($sql);

					// 20200316 kembalikan account INDEFINITE
					$sql = "update tmp_customer a,cms_classification b  set AGENT_ID = LAST_AGENT_ID where b.assigned_agent like concat('%',LAST_AGENT_ID,'%') and  a.CLASS = b.classification_id and CLASS = LAST_CLASS and class_duration = 'INDEFINITE'  and CLASS = '" . $class_id . "' and LAST_AGENT_ID is not null and last_assignment_end_date < curdate() and assignment_start_date = curdate() ";


					// echo $sql . "<br/>";
					$this->db->query($sql);

					//kembalikan ke agent sebelumnya jika last_assignment_end_date 	>= curdate()
					$sql = "update tmp_customer a,cms_classification b set AGENT_ID = LAST_AGENT_ID where   a.CLASS = b.classification_id and CLASS = LAST_CLASS and class_duration = 'INDEFINITE' and LAST_AGENT_ID is not null and 	assignment_end_date  > curdate()  and CLASS = '" . $class_id . "' and last_assignment_end_date >= curdate() and assignment_start_date = curdate()";

					// echo "$sql <br/>";
					$this->db->query($sql);
					//2020-03-10 tutup sementara


					$sql = "insert into `tmp_agent_account_assignment_history`(`account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence`) select `account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence` from tmp_agent_account_assignment";
					// echo "$sql <br/>";
					$this->db->query($sql);
				}
			}
		}
        if (!empty($aRow['update_detail'])) {
			if (!empty($assigned_agent)) {
				$sql = "update tmp_customer set " . $aRow['update_detail'] . " where CLASS='" . $class_id . "' AND " . $aRow["classification_detail"];
				$res	= $this->db->query($sql);
			} else {
				//				$sql ="update tmp_customer set ".$aRow['update_detail']." where (AGENT_ID IS NULL OR ( assignment_end_date < curdate() ) )  AND ".$aRow["classification_detail"];
				$sql = "update tmp_customer set " . $aRow['update_detail'] . " where (AGENT_ID IS NULL OR AGENT_ID =''  )  AND " . $aRow["classification_detail"];
				$res	= $this->db->query($sql);
			}
			// echo "$sql <br/>";
			$affct_rows = $this->db->affectedRows();
			echo "accefted rows $affct_rows <br>";

			$data_history = array(
				'action' => 'Do Set Class',
				'br_name' => $class_id,
				'sql' => $sql,
				'count' => $affct_rows,
				'created_by`' => 'system',
				'created_time' => date("Y-m-d H:i:s")
			);
			$this->builder = $this->db->table('tmp_history_br');
			$this->builder->insert($data_history);
		}
        $this->builder = $this->db->table('cc_custom_fields');
        $this->builder->select('field_name value, field_name AS item');
        $this->builder->where('field_name is not null');
        $this->builder->orderBy('field_name');
        $query = $this->builder->get();
		$custom_field = array();
        if ($query->getNumRows())
		{
			foreach ($query->getResult() as $row)
			{
				$custom_field[$row->value] = $row->item;
			}
		}
		$set = '';
		$l = 0;
		foreach ($custom_field as $k => $v) {
			if ($l == 0) {
				$set .= ' tmp_customer.' . $v . '=tmp_cif_detail.' . $v;
			} else {
				$set .= ' ,tmp_customer.' . $v . '=tmp_cif_detail.' . $v;
			}
			$l++;
		}

		/*		$sql = "update tmp_customer,( SELECT * FROM ( SELECT a.* FROM tmp_customer a INNER JOIN ( SELECT cif_number, MAX(class_time) as mpbr FROM tmp_customer WHERE class='".$class_id."' group by cif_number) b ON a.cif_number=b.cif_number and a.class_time=b.mpbr GROUP BY cif_number ) z ) tmp_cif_detail set ".$set." where tmp_cif_detail.cif_number = tmp_customer.cif_number";
		// echo "$sql <br/>";
	//	$this->db->query($sql);
		
		$affct_rows = $this->db->affected_rows();
		$data_history = array(
			'action' => 'Do Set Flaging With Same CIF',
			'br_name' => $class_id ,
			'sql' => $sql,
			'count' => $affct_rows,
			'created_by`' => 'system',
			'created_time' => date("Y-m-d H:i:s")
		);		
		$this->db->insert('tmp_history_br', $data_history);
		
		echo 'Done Update Flaging : '.$class_id.'<br/>';
*/
		#Do Insert History Bisnis Rule
		if (!empty($assigned_agent)) {
            $this->builder = $this->db->table('cms_classification');
            $this->builder->select('classification_name');
            $this->builder->where('classification_id', $class_id);
            $query = $this->builder->get();
		
            if ($query->getNumRows())
                {
                    foreach ($query->getResult() as $row)
                    {
						foreach ($row as $key => $value){
							$class_name[$key] = $value;  
                        }
                    }
                }
			$notes = 'Has entered into Business Rule ' . implode(', ', $class_name);
			$sql = "insert into `cms_contact_history`(`id`,`account_no`,`customer_no`,`user_id`,`input_source`,`notes`,`created_by`,`created_time`,fin_acc) select uuid(),a.CM_CARD_NMBR,a.CM_CUSTOMER_NMBR,'system','BISNIS_RULE', concat('" . $notes . "',if(AGENT_ID is null,'',concat('\n --assigned to: ',AGENT_ID))  ),'system', now(),fin_account from tmp_customer a where a.CLASS = '" . $class_id . "'  and assignment_start_date = curdate()";
			// echo "$sql <br/>";
			$this->db->query($sql);
		}
		echo '<hr>';
    }

    function get_export_class($id){
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
			"concat('`',CM_CARD_NMBR) as 'CARD_NUMBER'", 'CM_BLOCK_CODE as "BLOCK CODE"', 'CM_TYPE as "Product Code"', 'CM_AMOUNT_DUE as "Bill Amount"',
			'CM_KANWIL_CODE as Regional', 'CM_BUCKET as BUCKET', 'CF_AGENCY_STATUS_ID as AGENCY_STATUS_ID',
			'CM_CYCLE as CYCLE', 'CF_CAMPAIGN_ID as CAMPAIGN_ID', 'CM_DOMICILE_BRANCH as BRANCH', 'CR_ZIP_CODE as ZIP_CODE', 'fin_account',
			'BILL_BAL', 'CM_STATUS as CARD_STATUS', 'CM_DTE_PYMT_DUE as PAYMENT_DUE_DATE', 'DPD', 'CM_DTE_LST_STMT as LAST_STATEMENT_DATE', 'CM_DTE_LST_PYMT as LAST_PAYMENT_DATE', 'CM_TOT_BALANCE as TOTAL_BALANCE', 'a.CLASS', 'a.AGENT_ID'
		);
        $this->builder->join('cms_account_last_status b', 'a.CM_CARD_NMBR = b.account_no');
        $this->builder->where('a.CLASS', $id);
        $builder = $this->db->table('cms_classification');
        $builder->select('rule_type');
        $builder->where('classification_id', $id);
        $query = $builder->get();
    
        if ($query->getNumRows())
		{
			foreach ($query->getResult() as $row)
			{
                foreach ($row as $key => $value){
                    $rule_type[$key] = $value;  
				}
			}
		}
        
        if (empty($rule_type)) $rule_type = "REGULER";
		if ($rule_type == "TEMPORER") {
            $builder2 = $this->db->table('cms_account_last_status');
            $builder2->select('max(class_time)');
            $builder2->where('class', $id);
            $query2 = $builder2->get();
        
            if ($query2->getNumRows())
            {
                foreach ($query2->getResult() as $row)
                {
                    $class_date[$row->value] = $row->item;
                }
            }
			$this->builder->where('b.class_time', $class_date);
		}
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $rResult = $this->builder->get();
		return $rResult->getResultArray();
    }
}