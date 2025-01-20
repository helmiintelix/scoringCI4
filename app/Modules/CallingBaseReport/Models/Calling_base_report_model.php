<?php
namespace App\Modules\CallingBaseReport\models;
use CodeIgniter\Model;

Class Calling_base_report_model Extends Model 
{
    function get_calling_base_data($data){
        $DBDRIVER = $this->db->DBDriver;
        if (empty($data['start_date'])) {
            $data['start_date'] = date('Y-m-d');
        }
        if ($DBDRIVER === 'SQLSRV') {
            // SQL Server
            $ptp_date = "CASE 
                            WHEN a.lov3 = 'PTP' THEN a.ptp_date 
                            ELSE '' 
                        END ";
            $duration = "CONVERT(VARCHAR, DATEADD(SECOND, c.duration / 1000, 0), 108) ";
            $createdTime = 'CAST(a.created_time AS DATE)';
            
        } elseif ($DBDRIVER === 'Postgre') {
            // PostgreSQL
            $ptp_date = "CASE 
                                WHEN a.lov3 = 'PTP' THEN a.ptp_date 
                                ELSE '' 
                            END  ";
            $duration = "TO_CHAR((c.duration / 1000) * INTERVAL '1 second', 'HH24:MI:SS')  ";
            $createdTime = 'DATE(a.created_time)';
        } else {
            // MySQL
            $ptp_date = "IF(a.lov3 = 'PTP', a.ptp_date, '') ";
            $duration = "SEC_TO_TIME(c.duration/1000)";
            $createdTime = 'DATE(a.created_time)';
        }

        $this->builder = $this->db->table('cms_contact_history a');

        $select = array(
            "a.id",
			"a.account_no contract_number",
			"b.cr_name_1 customer_name",
			"b.cm_domicile_branch branch_name",
			"CONCAT(f.name, ' (', f.id, ')') AS updater",
			"a.due_date",
			"(select category_name from cms_lov_registration where id = a.lov1) lov1",
			"(select category_name from cms_lov_registration where id = a.lov2) lov2",
			"(select category_name from cms_lov_registration where id = a.lov3) lov3",
			"(select category_name from cms_lov_registration where id = a.lov4) lov4",
			"(select category_name from cms_lov_registration where id = a.lov5) lov5",
			"product_id  product_id",
			"cm_type  product_type",
			"a.dpd",
			"a.bucket",
			$ptp_date." AS ptp_date",
			"FORMAT(a.ptp_amount,0) ptp_amount",
			"a.notes AS remarks",
			"TIME(c.start_time) start_time",
			"TIME(c.end_time) end_time",
			"'' AS location",
			"'' AS phone_update",
			"'' AS call_count",
			"date(a.created_time) AS call_date",
			"'' AS collector",
			"a.created_by AS agent_id",
			"a.created_time AS last_update",
			"'' AS agent_backup",
			"'' AS overdue_reason",
			"a.caller_id",
			"a.phone_no phone_number",
			$duration." duration",
			"g.classification_name as class"
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cpcrd_new AS b', 'b.cm_card_nmbr = a.account_no','left');
		$this->builder->join('tmp_ecentrix_session_log_report AS c', 'a.caller_id = c.call_id','left');
		$this->builder->join('cc_user f','f.id=a.created_by');
		$this->builder->join('cms_classification g','g.classification_id=a.class_id','left');
		$this->builder->where($createdTime.' >= "'.$data['start_date'].'" and input_source="PHONE" ');
        if ($data['bucket']) {
            $this->builder->where('a.bucket', $data['bucket']);
        }
        if ($data['class_id']) {
            $this->builder->where('a.class_id', $data['class_id']);
        }
        // if ($data['sub_product']) {
        //     $this->builder->where('a.product_type', $data['sub_product']);
        // }
        if ($data['end_date']) {
            $this->builder->where($createdTime.' <= ', $data['end_date']);
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