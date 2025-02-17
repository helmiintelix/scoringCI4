<?php

include_once(dirname(__FILE__).'/util/class.database.php');
include_once(dirname(__FILE__). "/util/encrypt_decrypt.php");
include_once(dirname(__FILE__). "/util/common.php");
require_once (dirname(__FILE__). "/util/KLogger.php");

$logfile = dirname(__FILE__)."/logs/bod_process".date('Ymd').".log";
$tz = new DateTimeZone('Asia/Jakarta');
date_default_timezone_set('Asia/Jakarta');

$log = new KLogger ($logfile , KLogger::DEBUG);
$dateGenerate = date('Y-m-d');

$log->LogInfo("[START] BOD Process");

$db = Database::getInstance();
try {
    $conn = $db->getConnection();
} catch(Exception $e) {
    $log->LogError("Connect failed: %s\n", $e);
    exit();
}

function set_classification_by_account(){
    global $conn, $log;
    
    
    try {
        //code...
        $sql = "INSERT INTO `bod_log`(`status_time`,`description`,`status_type`,sequence) values(now(),'Execute Classification','BOD_CMS','5')";
        // echo $sql."\n";
        $result	= $conn->query($sql);
   
        // echo "Execute Classification & Business Rule<br><br>";
        $sql = "INSERT IGNORE INTO trn_bod (bod_date, description, status, start_time, end_time) values (now(), 'Execute Classification & Business Rule', '1', now(), null)";
        $result	= $conn->query($sql);
        
        
        $sql = "INSERT IGNORE INTO cms_account_last_status (account_no, cif_number, fin_acc, flag_fwo, CF_ACCOUNT_STATUS,CF_AGENCY_STATUS_ID, assignment_end_date ) 
                SELECT CM_CARD_NMBR, CM_CUSTOMER_NMBR, fin_account, '1', CM_STATUS,'101',curdate() - interval 1 day  FROM cpcrd_new;";
        $result	= $conn->query($sql);
        $log->LogInfo("[INSERT CMS_ACCOUNT_LAST_STATUS] ". $sql);


        $tgl_now = date('Ymd');
       
        $sql = "update cms_account_last_status set temp_assignment_date_end = null,temp_assigned_agent = null   
        where temp_assignment_date_end < curdate() ";
        $result	= $conn->query($sql);

        // $sql = "UPDATE cms_account_last_status a SET a.assignment_end_date = now() - INTERVAL 1 DAY  
        // WHERE  date(a.class_time) = CURDATE() and date(a.assignment_start_date) = CURDATE() ";
        // $result	= $conn->query($sql);

        //update yg status 2 (closed) jadi 5( aktif)
        $sql = "UPDATE cpcrd_new a,cms_account_last_status b SET b.CF_AGENCY_STATUS_ID = '5' 
        WHERE a.CM_CARD_NMBR = b.account_no AND b.CF_AGENCY_STATUS_ID = '2'";
        $result	= $conn->query($sql);

        $sql_drop = "DROP TABLE IF EXISTS tmp_customer";
        $result	= $conn->query($sql_drop);


        $sql = "UPDATE cms_account_last_status a, acs_coordinator_task b 
                set a.call_result = b.collection_result
                where a.account_no = b.contract_number ";
        $result	= $conn->query($sql);
        // $this->set_area_zipcode();

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
                        INDEX CM_NEW_TO_M1FLAG (CM_NEW_TO_M1FLAG),
                        INDEX CF_AGENCY_STATUS_ID (CF_AGENCY_STATUS_ID),
                        INDEX DPD_REMINDER (DPD_REMINDER),
                        INDEX CF_CAMPAIGN_ID (CF_CAMPAIGN_ID))
                        ENGINE=MYISAM
                        SELECT 
                                CM_ORG_NMBR,
                                CM_TYPE,
                                cpcrd_new.CM_CARD_NMBR,
                                CM_SHORT_NAME,
                                CM_CUSTOMER_ORG,
                                CM_CUSTOMER_NMBR,
                                CM_ACH_RT_NMBR,
                                CM_SAVINGS_ACCT,
                                CM_DOMICILE_BRANCH,
                                CM_OLC_REASON,
                                CM_STATUS,
                                CM_DTE_PYMT_DUE,
                                CM_CARD_EXPIR_DTE,
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
                                mob,

                                cms_account_last_status.class LAST_CLASS,
                                AGENCY_ID,
                                cms_account_last_status.assigned_agent AGENT_ID,
                                cms_account_last_status.assigned_agent LAST_AGENT_ID,
                                cms_account_last_status.assigned_fc FIELD_ID,
                                CM_COLLECTIBILITY,
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
                                cpcrd_new.fin_account,
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
                                cpcrd_new.FLD_DEC_1,
                                CURMTH_PAYDUE_DATE,
                                CURRMTH_BAL_X,
                                DPD,
                                OPS_DEF_BAL,
                                PRICING_PROFILE_CODE,
                                RISK_PROFILE_CODE,
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
                                CR_ID_ZIPCODE,
                            
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
                                cpcrd_new.CR_MOTHER_NM,
                                CM_ADDRESS_ZIPCODE,
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
                                assignment_end_date as last_assignment_end_date,
                                CM_NEW_TO_M1FLAG,
                                CM_DTE_PYMT_DUE_DAY,
                                AREA_TAGIH_ID 	area_tagih_id,
                                DPD_REMINDER,
                                cm_os_balance,
                                CM_TENOR,
                                cms_account_last_status.call_result as collection_result,
                                cpcrd_ext.productCode,
                                cpcrd_ext.cm_sub_prdk_ctg,
                                cms_account_last_status.*,
                                CAST('' as CHAR(50)) as CR_HANDPHONE_STATUS, CAST('' as CHAR(50)) as CR_GUARANTOR_PHONE_STATUS, CAST('' as CHAR(50)) as CR_HANDPHONE2_STATUS, CAST('' as CHAR(50)) as CR_HANDPHONE3_STATUS  
                                
                                FROM cpcrd_new 
                                JOIN cms_account_last_status on cpcrd_new.cm_card_nmbr=cms_account_last_status.account_no 
                                JOIN cpcrd_ext on cpcrd_new.CM_CUSTOMER_NMBR = cpcrd_ext.cm_cust_nmbr
                                LEFT JOIN cpcrd_ext_address on cpcrd_new.CM_CARD_NMBR = cpcrd_ext_address.CM_CARD_NMBR
                                WHERE  CF_AGENCY_STATUS_ID != '2'
                                group by cm_card_nmbr
                                ";
        //and cpcrd_new.CM_CUSTOMER_ORG != 'CLO'";
        $res_create	= $conn->query($sql_create);
        //	echo $sql_create;

        $sql = "insert into `bod_log`(`status_time`,`description`,`status_type`,sequence) values(now(),'Creating Temporary Table','BOD_CMS','5')";
        $result	= $conn->query($sql);
        //exit;
        $sql = "UPDATE tmp_customer 
                    SET last_agent3 = last_agent2, last_agent2 = last_agent1, last_agent1 = assigned_agent, LAST_CLASS = CLASS,last_class_duration = class_duration 
                    where last_agent1 != assigned_agent or last_agent1 is null";
        $result	= $conn->query($sql);
        // echo $sql . "<br/>";

        //reset yg sudah habis masa assignment
        $sql = "update tmp_customer set CLASS = NULL,AGENT_ID = '' where  assignment_end_date < curdate() or assignment_end_date is null";
        $result	= $conn->query($sql);
        // die;


        $sql = " truncate tmp_agent_history";
        $result	= $conn->query($sql);

        $sql_class = "SELECT classification_id,classification_name,classification_detail,assigned_agent,update_detail,job_schedule,weekly_day,is_reset_allocation,effective_date,class_expired_date,start_date,end_date,spesific_date,allocation_type,assignment_duration,expired_time,distribution_method,schedule_detail,order_by_detail,assignment_weight,class_priority,update_detail_json,account_handling
        FROM cms_classification
        WHERE 
        (
        (effective_date IS NULL AND class_expired_date >= CURDATE()) OR 
        (effective_date IS NULL AND class_expired_date IS NULL) OR 
        (class_expired_date is NULL AND effective_date <= CURDATE()) OR 
        (class_expired_date is NULL AND effective_date <= CURDATE() ) OR 
        (CURDATE() between effective_date and class_expired_date)
        )
        AND rule_type != 'TEMPORER'
        AND is_active = '1'
        ORDER BY class_priority ASC, updated_time ASC;
        ";
      
        $rResult = $conn->query($sql_class);
        // var_dump($rResult->num_rows);die;

        //echo "<ul>";
        $seq_br = 0;
        if($rResult->num_rows > 0){
            while ($aRow = $rResult->fetch_assoc()) {
                // foreach ($rResult->result_array() as $aRow) {
    
                // echo $seq_br . 'Do Business Rule : ' . $aRow['classification_name'] . "<br/>\n";
                //backup per_table dulu
                $sql = " drop table if exists tmp_customer_" . $aRow['classification_id'] . "_pre";
                $result	= $conn->query($sql);
    
                @unlink("/data/data_unsecured_collection/class_result/CMS_PRE_" . $aRow['classification_id'] . ".csv");
                $sql = " SELECT  
                    'CardNo','EMployeeID','UserID','EmployeeName','Cycle','DueDate','Aging','AccountNumber','AmountOS','MinimumPayment','LastPaymentDate','LastPaymentAmount','Region','ProductCode','StatusCode','DPD','AgencyStatusID','MDO','BusinessRuleID','BusinessRuleName','LastAllocationDate','InstallmentCardpro','CurrentBalance','AssignmentStartDate','AssignmentEndDate','CampaignId','TotalOS','AgeingObligor' 
                    UNION ALL 
                    SELECT CM_CARD_NMBR, AGENT_ID,c.nik,c.name,CM_CYCLE,CM_DTE_PYMT_DUE,CM_BUCKET,fin_account,CURRMTH_BAL_X ,BILL_BAL,CM_DTE_LST_PYMT,CM_LST_PYMT_AMNT,CM_KANWIL_CODE,CM_TYPE,CM_BLOCK_CODE,DPD,CF_AGENCY_STATUS_ID,CF_COUNT_MDO,CLASS,classification_name,class_time,OPS_DEF_BAL, CM_AMOUNT_DUE,assignment_start_date,assignment_end_date,CF_CAMPAIGN_ID,CF_TOTAL_OS,CF_BUCKET_OBLIGOR
                    INTO OUTFILE '/data/data_unsecured_collection/class_result/CMS_PRE_" . $aRow['classification_id'] . ".csv'
                    FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'
                    FROM tmp_customer a left join cms_classification b on (a.CLASS = b.classification_id)
                    left join cc_user c on(a.AGENT_ID=c.id)";
                //		$conn->query($sql);
    
                $is_run = false;
                $week = date('w');
                $day = date('d');
                switch ($aRow['job_schedule']) {
                    case 'WEEKLY':
                        if ($aRow['weekly_day'] == $week) {
                            doBR_by_account($aRow, $seq_br);
    
                            $seq_br++;
                        }
                        break;
                    case 'FIRST_DAY_OF_MONTH':
                    case 'MONTHLY':
                        $month_val = json_decode($aRow["schedule_detail"], true);
                        //var_dump($month_val);
                        if (is_array($month_val["months"])) {
                            foreach ($month_val["months"] as $val) {
                                if (date('m') == $val) {
                                    switch ($month_val['options']) {
                                        case "FIRST_DAY":
                                            if ($day == '01') {
                                                doBR_by_account($aRow, $seq_br);
                                                $seq_br++;
                                            }
                                            break;
                                        case "LAST_DAYS":
                                            if ($day == date('t')) {
                                                doBR_by_account($aRow, $seq_br);
                                                $seq_br++;
                                            }
                                            break;
                                        case "DAYS":
                                            if (is_array($month_val["day"])) {
                                                foreach ($month_val["day"] as $aday) {
                                                    if ($aday == date('d')) {
                                                        doBR_by_account($aRow, $seq_br);
                                                        $seq_br++;
                                                    }
                                                }
                                            }
                                            break;
                                        case "WEEK_DAYS":
                                            if (is_array($month_val["weeks"])) {
                                                foreach ($month_val["weeks"] as $weeks) {
                                                    //Get the first day of the month.
                                                    $firstOfMonth = strtotime(date("Y-m-01"));
                                                    $weeknum = intval(date("W")) - intval(date("W", $firstOfMonth)) + 1;
                                                    if ($weeks == $weeknum) {
                                                        if (is_array($month_val["weekday"])) {
                                                            foreach ($month_val["weekday"] as $weekday) {
                                                                if ($weekday == date("N")) {
                                                                    doBR_by_account($aRow, $seq_br);
                                                                    $seq_br++;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            break;
                                    }
                                }
                            }
                        }
                        /*					
                    */
                        break;
                    case 'LAST_DAY_OF_MONTH':
                        if ($day == date('t')) {
                            doBR_by_account($aRow, $seq_br);
                            $seq_br++;
                        }
    
                        break;
                    case 'DATE':
                        if ($aRow['spesific_date'] == date('Y-m-d')) {
                            doBR_by_account($aRow, $seq_br);
                            $seq_br++;
                        }
                    case 'DATE_RANGE':
                        if (($aRow['start_date'] <=  date('Y-m-d')) and (date('Y-m-d') <= $aRow['end_date'])) {
                            doBR_by_account($aRow, $seq_br);
                            $seq_br++;
                        }
    
                    case 'DAILY':
                    default:
                        doBR_by_account($aRow, $seq_br);
                        $seq_br++;
    
                        break;
                }
    
                // echo "<hr>";
                //sementara dulu 
                //backup per_table dulu
                $sql = " drop table if exists tmp_customer_" . $aRow['classification_id'] . "_post";
                $result	= $conn->query($sql);
    
                
            }
        }

        //TODO : jika assignment type temporer simpan assigned awalnya dan tempelkan agent temporernya
        $set = 'cpcrd_new.AGENT_ID = tmp_customer.AGENT_ID, cpcrd_new.CLASS = tmp_customer.CLASS, cpcrd_new.LAST_CLASS = tmp_customer.LAST_CLASS, cpcrd_new.CM_OLC_REASON_DESC = tmp_customer.CM_OLC_REASON_DESC, 
        cms_account_last_status.class = tmp_customer.CLASS, cms_account_last_status.class_time = tmp_customer.class_time, cms_account_last_status.priority_br = tmp_customer.priority_br, 
        cms_account_last_status.last_agent1 = tmp_customer.last_agent1, cms_account_last_status.last_agent2 = tmp_customer.last_agent2, cms_account_last_status.last_agent3 = tmp_customer.last_agent3  ,
        cms_account_last_status.temp_assigned_agent = tmp_customer.temp_assigned_agent,cms_account_last_status.temp_assignment_date_end = tmp_customer.temp_assignment_date_end,
        cms_account_last_status.assignment_start_date = tmp_customer.assignment_start_date,cms_account_last_status.assignment_end_date = tmp_customer.assignment_end_date
        ,cms_account_last_status.CLASS_TMP = tmp_customer.CLASS_TMP
        ,cms_account_last_status.CLASS_RECALL = tmp_customer.CLASS_RECALL
        ,cms_account_last_status.assigned_agent = tmp_customer.AGENT_ID,cms_account_last_status.class_duration = tmp_customer.class_duration, 
        cms_account_last_status.CF_AGENCY_STATUS_ID = tmp_customer.CF_AGENCY_STATUS_ID, cms_account_last_status.CF_CAMPAIGN_ID = tmp_customer.CF_CAMPAIGN_ID';

        $sql_customField = "select field_name as value, field_name AS item from cc_custom_fields where field_name is not null  group by field_name order by field_name ";
        $custom_field = $conn->query($sql_customField);
        if($custom_field->num_rows > 0){
            $l = 0;
            while ($v = $custom_field->fetch_assoc()) {
                if (empty($set)) {
                    $set .= ' cms_account_last_status.' . $v . '=tmp_customer.' . $v;
                } else {
                    $set .= ' ,cms_account_last_status.' . $v . '=tmp_customer.' . $v;
                }
            }

        }
       
        $sql = "update tmp_customer, cms_account_last_status, cpcrd_new set " . $set . " where tmp_customer.cm_card_nmbr = cms_account_last_status.account_no and tmp_customer.cm_card_nmbr = cpcrd_new.cm_card_nmbr";
        $result	= $conn->query($sql);
        $log->LogInfo("[UPDATE LAST] ".$sql);
        
        //update temporary
        $sql = "update cpcrd_new,tmp_customer set cpcrd_new.AGENT_ID = tmp_customer.temp_assigned_agent where tmp_customer.CM_CARD_NMBR = cpcrd_new.CM_CARD_NMBR and tmp_customer.temp_assigned_agent is not null and tmp_customer.temp_assignment_date_end >= curdate()";
        $conn->query($sql);

        $sql = "insert into `bod_log`(`status_time`,`description`,`status_type`,sequence) values(now(),'Done Process Classification','BOD_CMS','5')";
        $result	= $conn->query($sql);
        $log->LogInfo("[DONE PROCESS CLASSIFICATION] ".$sql);

        $tgl_now = $tgl_now . "_" . date("Hi");

        @unlink('/data/data_unsecured_collection/class_result/CMS_postclass_' . $tgl_now . '.csv');
        /*		$sql_report_post = "SELECT  
            'CARD_NMBR', 'CM_CUSTOMER_NMBR', 'fin_account', 'CLASS', 'LAST_CLASS', 
            'class_time', 'AGENT_ID', 'last_agent1', 'last_agent2', 'last_agent3', 'SUM_WO_BALANCE', 'REGIONAL',
            'CIF_REGIONAL', 'CM_ZIP_REC', 'CIF_ZIP_REC', 'REMARK', 'CF_COUNT_BR', 'CF_PAYMENTFLAG', 'CF_KEEP_EXTEND', 'CF_COUNT_MDO', 'CF_REQUEST_EXTEND', 
            'CF_SCORING', 'CF_ACCOUNT_GROUP', 'CF_ACCOUNT_STAY', 'CF_EXTEND_VALUE', 'CF_ACCOUNT_STATUS', 'CF_ACCOUNT_REQUEST', 'CF_USER_REQUEST',
            'CF_USER_ACCOUNT_GROUP','AGENT_ID'
            UNION ALL 
            SELECT CM_CARD_NMBR, CM_CUSTOMER_NMBR, fin_account, cpcrd_new.CLASS, LAST_CLASS, 
            class_time, AGENT_ID, last_agent1, last_agent2, last_agent3, SUM_WO_BALANCE, CM_KANWIL_CODE AS REGIONAL,
            CIF_REGIONAL, CM_ZIP_REC, CIF_ZIP_REC, CM_OLC_REASON_DESC AS REMARK, CF_COUNT_BR, CF_PAYMENTFLAG, CF_KEEP_EXTEND, CF_COUNT_MDO, CF_REQUEST_EXTEND, 
            CF_SCORING, CF_ACCOUNT_GROUP, CF_ACCOUNT_STAY, CF_EXTEND_VALUE, CF_ACCOUNT_STATUS, CF_ACCOUNT_REQUEST, CF_USER_REQUEST,
            CF_USER_ACCOUNT_GROUP,AGENT_ID
            INTO OUTFILE '/data/ccrec/recovery_postclass_".$tgl_now.".csv'
            FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n'
            FROM cpcrd_new, cms_account_last_status
            WHERE cpcrd_new.fin_account=fin_acc AND CF_ACCOUNT_STATUS != 2
            ;";
            */
        $jam = date("Hi");
        $sql_report_post = "SELECT  
            'CardNo','EMployeeID','UserID','EmployeeName','Cycle','DueDate','Aging','AccountNumber','AmountOS','MinimumPayment','LastPaymentDate','LastPaymentAmount','Region','ProductCode','StatusCode','DPD','AgencyStatusID','MDO','BusinessRuleID','BusinessRuleName','LastAllocationDate','InstallmentCardpro','CurrentBalance','AssignmentStartDate','AssignmentEndDate','CampaignId','TotalOS','AgeingObligor' 
            UNION ALL 
            SELECT CM_CARD_NMBR, AGENT_ID,c.nik,c.name,CM_CYCLE,CM_DTE_PYMT_DUE,CM_BUCKET,fin_account,CURRMTH_BAL_X ,BILL_BAL,CM_DTE_LST_PYMT,CM_LST_PYMT_AMNT,CM_KANWIL_CODE,CM_TYPE,CM_BLOCK_CODE,DPD,CF_AGENCY_STATUS_ID,CF_COUNT_MDO,CLASS,classification_name,class_time,OPS_DEF_BAL, CM_AMOUNT_DUE,assignment_start_date,assignment_end_date,CF_CAMPAIGN_ID,CF_TOTAL_OS,CF_BUCKET_OBLIGOR
            INTO OUTFILE '/data/data_unsecured_collection/class_result/CMS_postclass_" . $tgl_now . ".csv'
            FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'
            FROM tmp_customer a left join cms_classification b on (a.CLASS = b.classification_id)
            left join cc_user c on(a.AGENT_ID=c.id) where CM_BUCKET != 'CURRENT'";
        //		$conn->query($sql_report_post);

        $sql = "truncate cms_agent_queue";
        $result	= $conn->query($sql);

        $sql = " INSERT INTO `cms_agent_queue` (`account_no`, `status`, `agent_id`, `last_status_time`, `class_id`) SELECT CM_CARD_NMBR,'0',AGENT_ID,NOW(),CLASS FROM cpcrd_new";
        $result	= $conn->query($sql);
        @unlink('/data/data_unsecured_collection/class_result/CMS_postclass_' . $tgl_now . '.csv.gz');
        $cmd = "gzip /data/data_unsecured_collection/class_result/CMS_postclass_" . $tgl_now . ".csv";

        //exec($cmd);
        //		$this->put_ftp("CMS_postclass_".$tgl_now.".csv");
        $sql = "insert into `bod_log`(`status_time`,`description`,`status_type`,sequence) values(now(),'Send file CMS_postclass_" . $tgl_now . ".csv to FTP server','BOD_CMS','5')";
        //		$conn->query($sql);

        $sql = "replace into cpcrd_new_history select * from cpcrd_new";
        // $res =  $result	= $conn->query($sql);

        #scoring history
        $sql = "INSERT INTO cms_score_history (account_no, score_date, score, score_label, cif_no) select cm_card_nmbr,curdate(),score_value,tiering_label,CM_CUSTOMER_NMBR from cpcrd_new where score_value is not NULL";
        // echo "$sql <br/>";
        $result	= $conn->query($sql);


        $sql = "update trn_bod set end_time = now() where bod_date=curdate()";
        $result	= $conn->query($sql);

    } catch (\Throwable $th) {
        //throw $th;
        $message  = 'Invalid query: ' . mysqli_error($conn) . "\n";
        $message .= 'Whole query: ' . $sql;
        $log->LogError($message);
        $log->LogError($th);
        die;

    }
}

function doBR_by_account($aRow, $seq_br){
    global $conn, $log;
    try{

		// echo "runn doBR_by_account";
		$class_id = $aRow['classification_id'];
		$assigned_agent = $aRow['assigned_agent'];
		if (empty($assigned_agent)) {
			// if ($aRow["account_handling"] == "Telecoll") {
			// 	$assigned_agent = "Telecoll";
			// }
		}
		$class_time = date('Y-m-d H:i:s');
		//todo : remove agent on leave first
		/*
		allocation_type:
			- PERMANENT : mengikat ke agent yg ditunjuk sampai periode assignment berakhir
			- TEMPORER : assign ke  temporer agent - actual agent tetap sampai masa assignment berakhir, setelah habis kembalikan ke agent semula
			- RECALL : Hapus agent yg diassign / CLASS ID tetap di class id semula / tidak dioveride	
		*/
        // print_r($aRow, true);
		echo "<hr>";
		print("<pre>" . print_r($aRow, true) . "</pre>");
		echo "<hr>";
		//end todo
		if ($aRow['is_reset_allocation'] == '1') {
			#Do History Assignment
			$sql_history = "UPDATE tmp_customer 
		        SET last_agent3 = last_agent2, last_agent2 = last_agent1, last_agent1 = AGENT_ID, LAST_CLASS = CLASS, AGENT_ID = '', CLASS = null where CLASS='" . $aRow['classification_id'] . "'";
			$res_history	= $conn->query($sql_history);
			// echo "$sql_history <br/>";
			$sql = "insert into `bod_log`(`status_time`,`description`,`status_type`) values(now(),'Reset Agent ID and Class With CLASS $class_id' ,'BOD_CMS')";
			$conn->query($sql);
		} else {
			//20200310  simpan dulu hasil class yg lama
			$sql_history = "UPDATE tmp_customer 
		        SET last_agent3 = last_agent2, last_agent2 = last_agent1, last_agent1 = AGENT_ID, LAST_CLASS = CLASS where CLASS = '" . $aRow['classification_id'] . "'";
			//			$res_history	= $conn->query($sql_history);

		}
		// echo "$sql_history <br/>";

		switch ($aRow['assignment_duration']) {
			case "INDEFINITE":
				//jika indefinite, durasi selama masih masuk kriteria . dan agent tetap tidak pindah.
				$time_type = "DAY";
				$expire = 0;
				break;
			default:
				$time_type = $aRow['assignment_duration'];
				$expire = $aRow['expired_time'];
				break;
		}
		if ($expire == 0) {
			//$time_type = 'YEAR';
			//	$expire = 1;
		}


		#Do Bussiness Rule
		switch ($aRow['allocation_type']) {
			case "RECALL": //Hapus agent yg diassign / CLASS ID tetap di class id semula / tidak dioveride
				//				$sql ="update tmp_customer set CLASS='".$aRow['classification_id']."', AGENT_ID = NULL,assignment_end_date = curdate() - interval 1 day , class_time=now() where  ".$aRow["classification_detail"];

                $sql = "select classification_name from cms_classification where classification_id='" . $class_id . "'";
                $class_name = $conn->query($sql);
                
                if ($class_name->num_rows > 0) {
                    $row = $class_name->fetch_assoc();
                    $notes = 'Has entered into Business Rule ' . $row['classification_name'];
                } 
				
				$sql = "insert into `cms_contact_history`(`id`,`account_no`,`customer_no`,`user_id`,`class_id`,`input_source`,`notes`,`created_by`,`created_time`,fin_acc) select uuid(),a.CM_CARD_NMBR,a.CM_CUSTOMER_NMBR,'system','" . $class_id . "','BISNIS_RULE', concat('" . $notes . "',if(AGENT_ID is null,'',concat('\n -assigned to: ',b.name))  ),'system', now(),fin_account from tmp_customer a left join cc_user b on (a.AGENT_ID = b.id)  where " . $aRow["classification_detail"];
				// echo "$sql <br/>";
				$conn->query($sql);

				$sql = "update tmp_customer set class=null,CLASS_RECALL = '$class_id', AGENT_ID = '',assignment_end_date = curdate() - interval 1 day , class_time='$class_time',CLASS_TMP = '$class_id', class_duration = 'RECALL' where  " . $aRow["classification_detail"];

				break;
			case "TEMPORER": //assign ke  temporer agent - actual agent tetap sampai masa assignment berakhir, setelah habis kembalikan ke agent semula
				$sql = "update tmp_customer set CLASS_TMP='" . $aRow['classification_id'] . "',  class_time='$class_time' where  " . $aRow["classification_detail"];
				break;
			case "PERMANEN": //mengikat ke agent yg ditunjuk sampai periode assignment berakhir
			default:
                $sql = "select classification_name from cms_classification where classification_id='" . $class_id . "'";
                $class_name = $conn->query($sql);
                
                if ($class_name->num_rows > 0) {
                    $row = $class_name->fetch_assoc();
                    $notes = 'Has entered into Business Rule ' . $row['classification_name'];
                } 
				
				//				$sql = "insert into `cms_contact_history`(`id`,`account_no`,`customer_no`,`user_id`,`input_source`,`notes`,`created_by`,`created_time`,fin_acc) select uuid(),a.CM_CARD_NMBR,a.CM_CUSTOMER_NMBR,'system','BISNIS_RULE', concat('".$notes."',if(AGENT_ID is null,'',concat('\n -assigned to: ',AGENT_ID))  ),'system', now(),fin_account from tmp_customer a where ".$aRow["classification_detail"];
				$sql = "insert into `cms_contact_history`(`id`,`account_no`,`customer_no`,`user_id`,`class_id`,`input_source`,`notes`,`created_by`,`created_time`,fin_acc) 
                select uuid(),a.CM_CARD_NMBR,a.CM_CUSTOMER_NMBR,'system','" . $class_id . "','BISNIS_RULE', concat('" . $notes . "',if(AGENT_ID is null,'',concat('\n -assigned to: ',b.name))  ),
                'system', now(),fin_account from tmp_customer a left join cc_user b on (a.AGENT_ID = b.id)  where " . $aRow["classification_detail"];

				// echo "$sql <br/>";
				//$conn->query($sql);

				if ($expire == 0) {
					$expire = 1;
					//$time_type
				}

				if (!empty($assigned_agent)) {
					$sql = "update tmp_customer set CLASS='" . $aRow['classification_id'] . "',CLASS_TMP='" . $aRow['classification_id'] . "', priority_br='" . $seq_br . "', class_time='$class_time',assignment_start_date = curdate(),
				assignment_end_date  = curdate() + interval $expire $time_type  where 
					(AGENT_ID IS NULL OR AGENT_ID ='' ( assignment_end_date < curdate() ) )  AND CLASS IS NULL   AND " . $aRow["classification_detail"];

                    // , assignment_end_date  = curdate() + interval $expire $time_type
					$sql = "update tmp_customer set CLASS='" . $aRow['classification_id'] . "',CLASS_TMP='" . $aRow['classification_id'] . "', priority_br='" . $seq_br . "', class_time='$class_time',assignment_start_date = curdate(),
                    class_duration = '" . $aRow['assignment_duration'] . "'
                    where 
					(FIELD_ID IS NULL OR FIELD_ID ='' or AGENT_ID IS NULL OR AGENT_ID ='' OR ( assignment_end_date < curdate() )  )  AND CLASS IS NULL   AND (" . $aRow["classification_detail"].")";

					//20200312 : Tri : set assignment end date nya setelah diassign 
				} else {
					$sql = "update tmp_customer set  class_time='$class_time', CLASS='" . $aRow['classification_id'] . "'  where 
					assignment_end_date < curdate() AND CLASS IS NULL  AND (" . $aRow["classification_detail"].")";
				}
				break;
		}
		// echo $sql . "<br/>\n";

		$res	= $conn->query($sql);
        $log->LogInfo("[UPDATE TMP_CUSTOMER 1] ".$sql);
		$affct_rows = $conn->affected_rows;
        // die;

        $sql = "INSERT INTO tmp_history_br (`action`, `br_name`, `sql`, `count`, `created_by`, `created_time`) VALUES ('Set Class', '$class_id', '', '$affct_rows', 'system', now())";
        $res = $conn->query($sql);
        

		if (!empty($assigned_agent)) {
			// echo 'Do Assignment Agent<br/>';
			#Keep Assign To Last Agent
			
			$is_skip_assignment = 0;
			$arr_agent_req = explode('|', $assigned_agent);
			$arr_weight = explode('|', $aRow['assignment_weight']);
          
			$order_by = $aRow['order_by_detail'];
			if ((trim($order_by??'') == "")) {
				$order_by = " CM_AMOUNT_DUE DESC";
			}

			//todo : remove agent on leave first
			$sql = "select id from cc_user where curdate() between leave_start_date and leave_end_date";
			$query = 	$conn->query($sql);
			if ($query->num_rows > 0) {
				foreach ($query->row_array() as $k => $v) {
					if (in_array($v, $arr_agent_req)) {
						unset($arr_agent_req[array_search($v, $arr_agent_req)]);  //cek dulu	
					}
				}
			}

			//end todo
			
			#Shuffle Array Agent
			$arr_agent_shuffle = explode('|', $assigned_agent);
			if ($aRow['distribution_method'] == 'TOTAL_ACCOUNT') {
				$arr_agent_shuffle = $arr_agent_shuffle;
			} else {
				shuffle($arr_agent_shuffle);
			}

			// echo "Assigned Class";
			// var_dump($arr_agent_shuffle);
			if (!$is_skip_assignment) {
				for ($loop = 0; $loop < 1; $loop++) {
					$sql = " truncate tmp_agent_account_assignment";
					// echo "$sql <br/>";
					$conn->query($sql);


					$arr_agent_more = $arr_agent_shuffle;
					$count_agent = count($arr_agent_more);

					if ($aRow['distribution_method'] == 'REVERSE_ROUND_ROBIN') {
						$sql = "truncate tmp_agent_os";
						$conn->query($sql);

						
						if ($aRow['allocation_type'] == "TEMPORER") {
							$sql = "insert into `tmp_agent_account_assignment`(`account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence`) select CM_CARD_NMBR,CLASS,IFNULL(last_agent1,''),IFNULL(last_agent2,''),IFNULL(last_agent3,''), '1' as sequence from tmp_customer where CLASS_TMP = '" . $class_id . "' order by LAST_CLASS, " . $order_by;
						} else {
							if ($aRow['assignment_duration'] == "INDEFINITE") {
								$sql = "insert into `tmp_agent_account_assignment`(`account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence`) select CM_CARD_NMBR,CLASS,IFNULL(last_agent1,''),IFNULL(last_agent2,''),IFNULL(last_agent3,''), '1' as sequence from tmp_customer where CLASS = '" . $class_id . "' and assignment_end_date < curdate()  order by  LAST_CLASS," . $order_by;
							} else {
								$sql = "insert into `tmp_agent_account_assignment`(`account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence`) select CM_CARD_NMBR,CLASS,IFNULL(last_agent1,''),IFNULL(last_agent2,''),IFNULL(last_agent3,''), '1' as sequence from tmp_customer where CLASS = '" . $class_id . "'  and assignment_end_date < curdate()  order by  LAST_CLASS," . $order_by;
							}
						}

						// echo "$sql <br/>";
						$conn->query($sql);
                        $log->LogInfo("[INSERT TMP_AGENT_ACCOUNT_ASSIGNMENT REVERSE_ROUND_ROBIN] ".$sql);
					} else {
						if ($aRow['allocation_type'] == "TEMPORER") {
							$sql = "insert into `tmp_agent_account_assignment`(`account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence`) select CM_CARD_NMBR,CLASS,IFNULL(last_agent1,''),IFNULL(last_agent2,''),IFNULL(last_agent3,''), '1' as sequence from tmp_customer where CLASS_TMP = '" . $class_id . "'  order by  LAST_CLASS," . $order_by;
						} else {
							if ($aRow['assignment_duration'] == "INDEFINITE") {

								$sql = "insert into `tmp_agent_account_assignment`(`account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence`) select CM_CARD_NMBR,CLASS,IFNULL(last_agent1,''),IFNULL(last_agent2,''),IFNULL(last_agent3,''), '1' as sequence from tmp_customer where CLASS = '" . $class_id . "'  and assignment_end_date < curdate()  order by  LAST_CLASS," . $order_by;
							} else {
								$sql = "insert into `tmp_agent_account_assignment`(`account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence`) select CM_CARD_NMBR,CLASS,IFNULL(last_agent1,''),IFNULL(last_agent2,''),IFNULL(last_agent3,''), '1' as sequence from tmp_customer where CLASS = '" . $class_id . "' and assignment_end_date < curdate()  order by  LAST_CLASS," . $order_by;
							}
						}
						// echo "$sql <br/>";
						$conn->query($sql);
                        $log->LogInfo("[INSERT TMP_AGENT_ACCOUNT_ASSIGNMENT ELSE] ".$sql);

					}

                    $sql = "truncate tmp_agent";
					$query = 	$conn->query($sql);

					$i = 0;
					$arr_quota = array();
					foreach ($arr_agent_more as $k => $v) {
                        $weight = $arr_weight[$k] ?? 0;
                        $log->LogInfo("Agent ID : $v, Weight : $weight");
                        $sql = "INSERT INTO tmp_agent (user_id, seq, quota) VALUES ('$v', 1, $weight)";
                        $res = $conn->query($sql);
					}
					$arr_agent_more = array_reverse($arr_agent_more);
					// $total_quota_class = 0;
					foreach ($arr_agent_more as $k => $v) {
						if ($aRow['distribution_method'] == 'REVERSE_ROUND_ROBIN') {
							
                            $sql  = "INSERT INTO tmp_agent (user_id, seq) VALUES ('$v', '2')";
                            $res = $conn->query($sql);
						}

											
					}


					$sql3 = "select * from tmp_agent order by id";
					$res3 = 	$conn->query($sql3);
					$count_agent_double = $res3->num_rows;
					$c = 0;
                    while ($aRow2 = $res3->fetch_assoc()) {

						
						$limit = $aRow2['quota'];
						
						//echo "Assign to ".$aRow2['user_id']."<br/>\n";
						switch ($aRow['distribution_method']) {
							case "ROUND_ROBIN":
							case "REVERSE_ROUND_ROBIN":
								$sql = "update tmp_agent_account_assignment set user_id='" . $aRow2['user_id'] . "' where (id-1) % " . $count_agent_double . " = " . $c;
								// echo "$sql <br>";
								$res_07 = $conn->query($sql);

								break;
							case "TOTAL_ACCOUNT":
								$total_account_class = $this->common_model->get_record_value("count(*)", "tmp_customer", "class='" . $class_id . "'");
								$weight = ceil($total_account_class * $limit / 100);
								var_dump($total_account_class);
								var_dump($weight);
								var_dump($aRow2['user_id']);
								$sql = "update tmp_agent_account_assignment set user_id='" . $aRow2['user_id'] . "' where user_id is null limit $weight";
								// echo "$sql <br>";
								//die;
								$res_07 = $conn->query($sql);

								break;
							case "TOTAL_OS":
								break;
						}
						$assignment = $conn->affected_rows;

						$sql23 = "update tmp_history_assignment set assignment='" . $assignment . "',limit_quota='" . $aRow2['quota'] . "' 
                        where class = '" . $class_id . "' AND user_id = '" . $aRow2['user_id'] . "' and seq = '" . $loop . "'";
						$conn->query($sql23);
						$c++;
					}
					//(AGENT_ID IS NULL OR (assignment_start_date <= curdate() and assignment_end_date >= curdate() ) ) 				
					$sql = "insert into tmp_agent_history (user_id,quota,seq,class,insert_time) select user_id, quota, seq, '$class_id' as class, now() as insert_time from tmp_agent;";
					$conn->query($sql);

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
						$sql = "update tmp_agent_account_assignment a,tmp_customer b set temp_assigned_agent = user_id,b.temp_assignment_date_end = curdate() + interval $expire $time_type   
                        where a.account_no = b.CM_CARD_NMBR and b.assignment_end_date < curdate() ";
					} else {
						$sql = "update tmp_agent_account_assignment a,tmp_customer b set AGENT_ID = user_id,assignment_start_date = curdate(),
						assignment_end_date  = curdate() + interval $expire $time_type where a.account_no = b.CM_CARD_NMBR and b.assignment_end_date < curdate() ";
					}
					// echo "$sql <br/>";
					$conn->query($sql);
                    $log->LogInfo("[UPDATE TMP_CUSTOMER 2] ".$sql);

					// 20200316 kembalikan account INDEFINITE
					$sql = "update tmp_customer a,cms_classification b set AGENT_ID = LAST_AGENT_ID where 
                    b.assigned_agent  like concat('%',LAST_AGENT_ID,'%') and  a.CLASS = b.classification_id 
                    and CLASS = LAST_CLASS and class_duration = 'INDEFINITE'  and CLASS = '" . $class_id . "' 
                    and LAST_AGENT_ID is not null and last_assignment_end_date < curdate() and assignment_start_date = curdate() ";


					// echo $sql . "<br/>";
					$conn->query($sql);

					//kembalikan ke agent sebelumnya jika last_assignment_end_date 	>= curdate()
					$sql = "update tmp_customer a,cms_classification b set AGENT_ID = LAST_AGENT_ID where   
                    a.CLASS = b.classification_id and CLASS = LAST_CLASS and class_duration = 'INDEFINITE' 
                    and LAST_AGENT_ID is not null and 	assignment_end_date  > curdate()  and CLASS = '" . $class_id . "'
                    and last_assignment_end_date >= curdate() and assignment_start_date = curdate()";

					// echo "$sql <br/>";
					$conn->query($sql);
					//2020-03-10 tutup sementara


					$sql = "insert into `tmp_agent_account_assignment_history`(`account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence`) select `account_no`,`class_id`,`last_agent1`,`last_agent2`,`last_agent3`,`sequence` from tmp_agent_account_assignment";
					// echo "$sql <br/>";
					$conn->query($sql);
				}
			}
		}

		if (!empty($aRow['update_detail'])) {
			if (!empty($assigned_agent)) {

				$sql = "update tmp_customer set " . $aRow['update_detail'] . " where CLASS='" . $class_id . "' and class_time = '$class_time'";
				$res	= $conn->query($sql);
			} else {
				
				if ($aRow['allocation_type'] == "RECALL") {
					$sql = "update tmp_customer set " . $aRow['update_detail'] . " where  class_time = '$class_time' and CLASS_TMP = '$class_id' and class_duration = 'RECALL'";
				} else {
					$sql = "update tmp_customer set " . $aRow['update_detail'] . " where CLASS='" . $class_id . "' and class_time = '$class_time'";
				}
				$res	= $conn->query($sql);
			}
			// echo "$sql <br/>";
            $sql_escaped = $conn->real_escape_string($sql);
            $affct_rows = $conn->affected_rows;
			// $affct_rows = 0;

			// echo "accefted rows $affct_rows <br>";

            $sql = "INSERT INTO tmp_history_br (`action`, `br_name`, `sql`, `count`, `created_by`, `created_time`) VALUES ('Do Set Class', '$class_id', '$sql_escaped', '$affct_rows', 'system', now())";
            $res = $conn->query($sql);

			$arr_notes = array();
			$arr_update = json_decode($aRow['update_detail_json'], true);
			foreach ($arr_update["opt_search"] as $key => $value) {
				$arr_notes[] = " Set $value to " . $arr_update["txt_keyword"][$key];
			}
			$sql = "insert into `cms_contact_history`(`id`,`account_no`,`customer_no`,`user_id`,`class_id`,`input_source`,`notes`,`created_by`,`created_time`,fin_acc) select uuid(),a.CM_CARD_NMBR,a.CM_CUSTOMER_NMBR,'system','" . $class_id . "','BISNIS_RULE', '" . implode("\n", $arr_notes) . "','system', now(),fin_account from tmp_customer a   where a.CLASS = '" . $class_id . "'  and class_time = '$class_time'";
			$conn->query($sql);
		}


        $sql_customField = "select field_name as value, field_name AS item from cc_custom_fields where field_name is not null and field_name <> 'CF_ACCOUNT_STATUS' group by field_name order by field_name ";
        $custom_field = $conn->query($sql_customField);
		$set = '';
		$l = 0;
        if($custom_field->num_rows > 0){

            while ($v = $custom_field->fetch_assoc()) {
                if ($l == 0) {
                    $set .= ' tmp_customer.' . $v . '=tmp_cif_detail.' . $v;
                } else {
                    $set .= ' ,tmp_customer.' . $v . '=tmp_cif_detail.' . $v;
                }
                $l++;
            }
        }

		
		#Do Insert History Bisnis Rule
		if (!empty($assigned_agent)) {
            $sql = "select classification_name from cms_classification where classification_id='" . $class_id . "'";
                $class_name = $conn->query($sql);
                
                if ($class_name->num_rows > 0) {
                    $row = $class_name->fetch_assoc();
                    $notes = 'Has entered into Business Rule ' . $row['classification_name'];
                } 
			
			$sql = "insert into `cms_contact_history`(`id`,`account_no`,`customer_no`,`user_id`,`class_id`,`input_source`,`notes`,`created_by`,`created_time`,fin_acc) select uuid(),a.CM_CARD_NMBR,a.CM_CUSTOMER_NMBR,'system','" . $class_id . "','BISNIS_RULE', concat('" . $notes . "',if(AGENT_ID is null,'',concat('\n --assigned to: ',b.name))  ),'system', now(),fin_account from tmp_customer a  left join cc_user b on(a.AGENT_ID = b.id) where a.CLASS = '" . $class_id . "'  and assignment_start_date = curdate()";

			// echo "$sql <br/>";
			$conn->query($sql);
		}

		@unlink("/data/data_unsecured_collection/class_result/CMS_POST_" . $class_id . ".csv");
		$sql = " SELECT  
		'CardNo','EMployeeID','UserID','EmployeeName','Cycle','DueDate','Aging','AccountNumber','AmountOS','MinimumPayment','LastPaymentDate','LastPaymentAmount','Region','ProductCode','StatusCode','DPD','AgencyStatusID','MDO','BusinessRuleID','BusinessRuleName','LastAllocationDate','InstallmentCardpro','CurrentBalance','AssignmentStartDate','AssignmentEndDate','CampaignId' 
		UNION ALL 
		SELECT CM_CARD_NMBR, AGENT_ID,c.nik,c.name,CM_CYCLE,CM_DTE_PYMT_DUE,CM_BUCKET,fin_account,CURRMTH_BAL_X ,BILL_BAL,CM_DTE_LST_PYMT,CM_LST_PYMT_AMNT,CM_KANWIL_CODE,CM_TYPE,CM_BLOCK_CODE,DPD,CF_AGENCY_STATUS_ID,CF_COUNT_MDO,CLASS,classification_name,class_time,OPS_DEF_BAL, CM_AMOUNT_DUE,assignment_start_date,assignment_end_date,CF_CAMPAIGN_ID
		INTO OUTFILE '/data/data_unsecured_collection/class_result/CMS_POST_" . $class_id . ".csv'
		FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'
		FROM tmp_customer a left join cms_classification b on (a.CLASS = b.classification_id)
		left join cc_user c on(a.AGENT_ID=c.id)";
		//$conn->query($sql);

		// echo '<hr>';
    } catch (\Throwable $th) {
        //throw $th;
        $message  = 'Invalid query: ' . mysqli_error($conn) . "\n";
        $message .= 'Whole query: ' . $sql;
        $log->LogError($message);
        $log->LogError($th);
        die;

    }
}




set_classification_by_account();

$log->LogInfo("[FINISH] BOD Process");