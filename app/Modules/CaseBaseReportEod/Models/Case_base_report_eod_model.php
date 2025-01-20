<?php
namespace App\Modules\CaseBaseReportEod\models;
use CodeIgniter\Model;

Class Case_base_report_eod_model Extends Model 
{
    function get_case_base_data($data){
        $DBDRIVER = $this->db->DBDriver;
        if ($DBDRIVER === 'SQLSRV') {
            // SQL Server
            $call_to = '(select TOP 1 category_name from cms_lov_registration where id = a.last_call_to )';
            $action_code = '(select TOP 1 category_name from cms_lov_registration where id = a.last_call_result)';
            $contact_code = '(select TOP 1 category_name from cms_lov_registration where id = a.last_contact_person)';


            $ptp_date = "CASE 
                            WHEN a.ptp_date = '0000-00-00' THEN null
                            ELSE a.ptp_date  
                        END  ";
            $ptp_amount = "CASE 
                            WHEN FORMAT(a.ptp_amount, 'N0') = '0' THEN NULL
                            ELSE FORMAT(a.ptp_amount, 'N0') 
                        END";
            $call_date = 'CAST(a.last_call_status_time AS DATE)';
            $month_date = "FORMAT(a.insert_time, 'yyyy-MM') "; 
            $call_duration = "DATEADD(SECOND, CEILING(a.duration / 1000), '00:00:00') ";
            $updater = "CONCAT(gg.id,' - ',gg.name) ";
            $createdTime = 'CAST(a.created_time AS DATE)';
            $last_call_status_time = "DATE(a.last_call_status_time) ";
        } elseif ($DBDRIVER === 'Postgre') {
            // PostgreSQL
            $call_to = '(select category_name from cms_lov_registration where id = a.last_call_to limit 1)';
            $action_code = '(select category_name from cms_lov_registration where id = a.last_call_result limit 1)';
            $contact_code = '(select category_name from cms_lov_registration where id = a.last_contact_person limit 1)';

            $ptp_date = "CASE 
                        WHEN a.ptp_date = '0000-00-00' THEN null
                        ELSE a.ptp_date  
                        END  ";
            $ptp_amount = "CASE 
            WHEN TO_CHAR(a.ptp_amount, 'FM999,999,999') = '0' THEN NULL
            ELSE TO_CHAR(a.ptp_amount, 'FM999,999,999') 
            END";
            $call_date = 'DATE(a.last_call_status_time)';
            $month_date = "TO_CHAR(a.insert_time, 'YYYY-MM') "; 
            $call_duration = "TO_CHAR((CEILING(a.duration / 1000) * INTERVAL '1 second'), 'HH24:MI:SS')";
            $updater = "gg.id || ' - ' || gg.name ";
            $last_call_status_time = "gg.id || ' - ' || gg.name ";
        } else {
            // MySQL
            $call_to = '(select category_name from cms_lov_registration where id = a.last_call_to limit 1)';
            $action_code = '(select category_name from cms_lov_registration where id = a.last_call_result limit 1)';
            $contact_code = '(select category_name from cms_lov_registration where id = a.last_contact_person limit 1)';

            $ptp_date = "IF(a.ptp_date = '0000-00-00', null, a.ptp_date) ";
            $ptp_amount = "if(FORMAT(a.ptp_amount,0)=0,null,FORMAT(a.ptp_amount,0))";
            $call_date = 'DATE(a.last_call_status_time)'; 
            $month_date = "DATE_FORMAT(a.insert_time, '%Y-%m')"; 
            $call_duration = 'SEC_TO_TIME(CEILING(a.duration/1000))';
            $updater = "CONCAT(gg.id,' - ',gg.name) ";
            $last_call_status_time = "DATE(a.last_call_status_time) ";
        }

        $this->builder = $this->db->table('acs_case_base_od a');
        $select = array(
            "a.contract_number AS id",
			"a.contract_number",
			"a.customer_name",
			// "h.branch_name",
			"a.due_date_last as due_date",
			"a.last_call_status_agent AS updater",
			 $call_date." AS call_date",
			"product_id product_id",
			"a.product_type product_type",
			$call_to." AS call_to", //lov1
			$action_code." AS action_code", //lov3
			"a.ptp_status AS pmt_status",
			"'' AS location",
			"a.last_call_remarks AS remarks",
			"'' AS touch_count",
			"'' AS contacted_count",
			$ptp_date."  ptp_date",
			$ptp_amount." ptp_amount",
			$month_date." AS month_date",
			"a.last_call_od_days AS od_days",
			// "e.product_id AS product_id",
			"a.bucket AS bucket",
			// "h.branch_name AS branch_id",
			"g.classification_name AS class_name",
			$contact_code." AS contact_code", //lov2
			"a.join_program",
			$call_duration." call_duration",
			"a.call_history_id call_id",
			$updater." updater"
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cms_classification AS g', 'a.class_id = g.classification_id', 'left');
		$this->builder->join('cc_user AS gg', 'a.last_call_status_agent = gg.id');
		$this->builder->where('(a.last_call_status <> "UNTOUCH" AND a.last_call_status <> "" AND a.last_call_status IS NOT NULL)');
        $this->builder->groupBy('a.contract_number');

        if ($data['start_date']) {
            $this->builder->where($last_call_status_time.' >= ', $data['start_date']);
        }
        if ($data['end_date']) {
            $this->builder->where($last_call_status_time.' <= ', $data['end_date']);
        }
        if ($data['sub_product']) {
            $this->builder->where('a.product_type', $data['sub_product']);
        }
        if ($data['keyword']) {
            $this->builder->where($data['search_by']." like '%".$data['keyword']."%'");
        }
        $this->builder->orderBy('contract_number', 'ASC');
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