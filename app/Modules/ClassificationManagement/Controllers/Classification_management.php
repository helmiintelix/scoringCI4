<?php 
namespace App\Modules\ClassificationManagement\Controllers;
use App\Modules\ClassificationManagement\Models\Classification_management_model;
use CodeIgniter\Cookie\Cookie;

class Classification_management extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Classification_management_model = new Classification_management_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\ClassificationManagement\Views\Classification_list_view', $data);
	}
	function get_classification_list(){
		$cache = session()->get('USER_ID').'_classification_list';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Classification_management_model->get_classification_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function getDetail(){
		$param = $this->input->getGet('value');
		switch ($param) {
			case 'ptp_status':
				echo '<option value="BROKEN">Broken PTP</option>';
				break;
			case "MAX_DPD_30":
				echo '<option value="MAX_DPD_30">Ever 30+</option>';
				break;
			case "MAX_DPD_90":
				echo '<option value="MAX_DPD_90">Ever 90+</option>';
				break;
			default:
				$where = '';
				if (empty($param)) {
					$where = "reference = null";
				} else {
					$where = 'r.reference="' . $param . '"';
				}
				switch ($param) {
					case 'LOV1':
						$builder = $this->db->table('cms_lov_relation');
						$builder->select('CONCAT(lov1_category,",",(SELECT lov1_category FROM cms_lov_relation  WHERE lov_id ="1db1fc7c-83e0-aee4-95c8-10b248449e9d")) lov');
						$builder->where('lov_id', '1db1fc7c-83e0-aee4-95c8-10b248449e9c');
						$data['lov1'] = $builder->get()->getResultArray();
						$data = explode(',',$data['lov1'][0]['lov']);
						break;
					case 'LOV2':
						$builder = $this->db->table('cms_lov_relation');
						$builder->select('CONCAT(lov2_category,",",(SELECT lov2_category FROM cms_lov_relation  WHERE lov_id ="1db1fc7c-83e0-aee4-95c8-10b248449e9d")) lov');
						$builder->where('lov_id', '1db1fc7c-83e0-aee4-95c8-10b248449e9c');
						$data['lov2'] = $builder->get()->getResultArray();
						$data = explode(',',$data['lov2'][0]['lov']);
						break;
					case 'LOV3':
						$builder = $this->db->table('cms_lov_relation');
						$builder->select('CONCAT(lov3_category,",",(SELECT lov3_category FROM cms_lov_relation  WHERE lov_id ="1db1fc7c-83e0-aee4-95c8-10b248449e9d")) lov');
						$builder->where('lov_id', '1db1fc7c-83e0-aee4-95c8-10b248449e9c');
						$data['lov3'] = $builder->get()->getResultArray();
						$data = explode(',',$data['lov3'][0]['lov']);
						break;
					case 'LOV4':
						$builder = $this->db->table('cms_lov_relation');
						$builder->select('CONCAT(lov4_category,",",(SELECT lov3_category FROM cms_lov_relation  WHERE lov_id ="1db1fc7c-83e0-aee4-95c8-10b248449e9d")) lov');
						$builder->where('lov_id', '1db1fc7c-83e0-aee4-95c8-10b248449e9c');
						$data['lov4'] = $builder->get()->getResultArray();
						$data = explode(',',$data['lov4'][0]['lov']);
						break;
					case 'LOV5':
						$builder = $this->db->table('cms_lov_relation');
						$builder->select('CONCAT(lov5_category,",",(SELECT lov3_category FROM cms_lov_relation  WHERE lov_id ="1db1fc7c-83e0-aee4-95c8-10b248449e9d")) lov');
						$builder->where('lov_id', '1db1fc7c-83e0-aee4-95c8-10b248449e9c');
						$data['lov5'] = $builder->get()->getResultArray();
						$data = explode(',',$data['lov5'][0]['lov']);
						break;
				}
				// print_r($data);
				// exit();
				foreach ($data as $row) {
					echo '<option value="' . $row . '">' . $row. '</option>';
				}
		}
	}
	function getTemplate(){
		$param = $this->input->getGet('value');
		$builder = $this->db->table('cms_email_sms_template');
        $builder->select('template_id value, template_name item');
        $builder->where('sent_by', $param);
        $builder->where('is_active', "1");
        $builder->orderBy('template_name', 'asc');
        $data = $builder->get()->getResultArray();
		if ($data) {
			foreach ($data as $row) {
				echo '<option value="' . $row['value'] . '">' . $row['item'] . '</option>';
			}
		} else {
			echo '<option value="">Template ' . $param . ' tidak tersedia.</option>';
		}
	}
	function getTemplateWa(){
		$param = $this->input->getGet('value');
		$builder = $this->db->table('cms_wa_template');
        $builder->select('template_id value, template_name item');
        $builder->where('is_active', "1");
        $builder->orderBy('template_name', 'asc');
        $data = $builder->get()->getResultArray();
		if ($data) {
			foreach ($data as $row) {
				echo '<option value="' . $row['value'] . '">' . $row['item'] . '</option>';
			}
		} else {
			echo '<option value="">Template ' . $param . ' tidak tersedia.</option>';
		}
	}
	function getListCustomField(){
		$DbName = $this->db->database;
		$id = $this->input->getGet('_value');
		$data = $this->Common_model->get_ref_master('column_name value, column_name item', 'information_schema.COLUMNS', "TABLE_NAME = 'cms_account_last_status' AND table_schema = '".$DbName ."' ", 'column_name');

		
		if (count($data) > 1) {
			$arr_json[] = array();
			foreach ($data as $key => $value) {
				$arr_json[] = array($key => $value);
			}
		
			$arr_json[] = array('CM_OLC_REASON_DESC' => 'Remark');

			$arr_json[] = array("CF_AGENCY_STATUS_ID" => "Agency Status ID");
			$arr_json[] = array("CF_CAMPAIGN_ID" => "Campaign ID");
			$arr_json[] = array("AGENT_ID" => "AGENT_ID");
		} else {
			$arr_json[] = array("0" => "[no data]");
		}

		if (!empty($arr_json)) {
			echo json_encode($arr_json);
		}
	}
	function classification_add_form() {
		$DBDRIVER = $this->db->DBDriver;
		$DbName = $this->db->database;
		
		$data['field_name'] = $this->Common_model->get_record_list("column_name value, column_name AS item", "information_schema.COLUMNS", "TABLE_NAME = 'cms_account_last_status' and column_name not in ('account_no') AND table_schema = '".$DbName ."'", "column_name");
		$data['field_name'] = $data['field_name'] + array("CM_OLC_REASON_DESC" => "Remark", "CF_AGENCY_STATUS_ID" => "Agency Status ID", "CF_CAMPAIGN_ID" => "Campaign ID","AGENT_ID"=>"AGENT_ID");
		array_unshift($data['field_name'], "[select data]");
		$data['order_list'] = $data['field_name'] + array("CM_TOT_AMNT_CHARGE_OFF" => "Amount Write Off", "SUM_WO_BALANCE" => "Sum CIF Amount Write Off");
		
		if ($DBDRIVER === 'SQLSRV') {
            // SQL Server
			$sql = "SELECT 
						column_name AS id, 
						column_name AS label, 
						data_type AS type
					FROM 
						information_schema.COLUMNS
					WHERE 
						TABLE_NAME = 'cms_account_last_status'
						AND table_catalog = ?
					ORDER BY 
						column_name";
        } elseif ($DBDRIVER === 'Postgre') {
            // PostgreSQL
			$sql = "SELECT 
						column_name AS id, 
						column_name AS label, 
						data_type AS type
					FROM 
						information_schema.COLUMNS
					WHERE 
						TABLE_NAME = 'cms_account_last_status'
						AND table_schema = ?
					ORDER BY 
						column_name";
        } else {
            // MySQL
            $sql = "SELECT 
						column_name AS id, 
						column_name AS label, 
						data_type AS type
					FROM 
						information_schema.COLUMNS
					WHERE 
						TABLE_NAME = 'cms_account_last_status'
						AND table_schema = ?
					ORDER BY 
						column_name";
        }
		

		$query = $this->db->query($sql, [$DbName]);
	
		$arr_data = array();
		if ($query->getNumRows()) {
			foreach ($query->getResult() as $row) {
				if ($row->type == 'varchar' || $row->type == 'text') {
					$row->type = 'string';
				} else if ($row->type == 'int') {
					$row->type = 'integer';
				} else {
					$row->type = 'date';
				}
				if ($row->id == 'CF_ACCOUNT_GROUP') {
					
					// $ACCOUNT_GROUP = $this->Common_model->get_record_list('id as value ,description as item', 'cms_master_account_group', "id is not null and flag='1' and flag_tmp='1'", "description");
					
					// $arr_data[] = array(
					// 	"id" => $row->id,
					// 	"label" => $row->label,
					// 	"type" => $row->type,
					// 	"input" => 'checkbox',
					// 	"operators" => array('equal', 'not_equal', 'in', 'not_in', 'is_null', 'is_not_null'),
					// 	"values" => $ACCOUNT_GROUP
					// );
				} else if ($row->id == 'CF_ACCOUNT_STATUS') {
					// $ACCOUNT_STATUS = $this->Common_model->get_record_list('value as value ,description as item', 'cms_reference', "reference ='ACCOUNT_STATUS'", "description");
					// $arr_data[] = array(
					// 	"id" => $row->id,
					// 	"label" => $row->label,
					// 	"type" => $row->type,
					// 	"input" => 'checkbox',
					// 	"operators" => array('equal', 'not_equal', 'in', 'not_in', 'is_null', 'is_not_null'),
					// 	"values" => $ACCOUNT_STATUS
					// );
				} else {
					$arr_data[] = array(
						"id" => $row->id,
						"label" => $row->label,
						"type" => $row->type
					);
				}
			}
		}
		$data['new_where'] = json_encode($arr_data);
		// $data['account_group'] = $this->Common_model->get_record_list('id as value ,description as item', 'cms_master_account_group', "id is not null and flag='1' and flag_tmp='1'", "description");
		

        if ($DBDRIVER === 'SQLSRV') {
            // SQL Server
            $sql = "SELECT 'AGENT_ID' AS id, 'AGENT_ID' AS label, 'string' AS type, 'cpcrd_new' AS table_name
					UNION ALL
					SELECT 
						'DATEDIFF(DAY, CM_DTE_LST_PYMT, GETDATE())' AS id, 
						'DAYS SINCE LAST PAYMENT' AS label, 
						'int' AS type, 
						'cms_account_last_status' AS table_name
					UNION ALL
					SELECT 
						column_name AS id, 
						column_name AS label, 
						'string' AS type, 
						'cms_account_last_status' AS table_name
					FROM 
						INFORMATION_SCHEMA.COLUMNS
					WHERE 
						TABLE_NAME = 'cms_account_last_status' 
						AND column_name NOT IN ('account_no') 
						AND table_catalog = '{$DbName}'
					UNION ALL
					SELECT 
						id, 
						max(reference) AS label, 
						'string' AS type, 
						max(table_name) table_name 
					FROM 
						stagging_reference
					WHERE 
						reference IS NOT NULL
						AND id !=''
					GROUP BY 
						id
					ORDER BY 
						label ASC;
					";
        } elseif ($DBDRIVER === 'Postgre') {
            // PostgreSQL
           $sql = "SELECT 'AGENT_ID' AS id, 'AGENT_ID' AS label, 'string' AS type, 'cpcrd_new' AS table_name
					UNION ALL
					SELECT 
						DATE_PART('day', CURRENT_DATE - CM_DTE_LST_PYMT) AS id, 
						'DAYS SINCE LAST PAYMENT' AS label, 
						'int' AS type, 
						'cms_account_last_status' AS table_name
					UNION ALL
					SELECT 
						column_name AS id, 
						column_name AS label, 
						'string' AS type, 
						'cms_account_last_status' AS table_name
					FROM 
						information_schema.COLUMNS
					WHERE 
						TABLE_NAME = 'cms_account_last_status' 
						AND id !=''
						AND column_name NOT IN ('account_no') 
						AND table_schema = '{$DbName}'
					UNION ALL
					SELECT 
						id, 
						reference AS label, 
						'string' AS type, 
						table_name 
					FROM 
						stagging_reference
					WHERE 
						reference IS NOT NULL
					GROUP BY 
						id, label, table_name
					ORDER BY 
						label ASC;
					";
        } else {
            // MySQL
            $sql = 'SELECT "AGENT_ID" id ,"AGENT_ID"  as label , "string" as type ,"cpcrd_new" table_name UNION ALL ';
			$sql .= 'SELECT "DATEDIFF(CURDATE(),CM_DTE_LST_PYMT)" id ,"DAYS SINCE LAST PAYMENT"  as label , "int" as type ,"cms_account_last_status" table_name UNION ALL ';
			$sql .= 'SELECT column_name id, column_name as label , "string" as type ,"cms_account_last_status" table_name from information_schema.COLUMNS  where TABLE_NAME = "cms_account_last_status" and column_name not in ("account_no") AND table_schema = "'.$DbName .'"  UNION ALL ';
			$sql .= 'SELECT id ,reference as label , "string" as type ,table_name FROM stagging_reference WHERE reference is not null and id !="" GROUP BY id  order by label asc ';
        }
		
		
		$res = $this->db->query($sql)->getResultArray();
		foreach ($res as $key => $value) {

			if ($DBDRIVER === 'SQLSRV') {
				// SQL Server
				$tipe = $this->Common_model->get_record_value("DATA_TYPE", "information_schema.COLUMNS", "COLUMN_NAME = '". $value['id']."' AND table_catalog = '".$DbName."'  AND TABLE_NAME = '".strtolower($value['table_name'])."' ");
			} elseif ($DBDRIVER === 'Postgre') {
				// PostgreSQL
				$tipe = $this->Common_model->get_record_value("DATA_TYPE", "information_schema.COLUMNS", "COLUMN_NAME = '". $value['id']."' AND table_schema = '".$DbName."'  AND TABLE_NAME = '".strtolower($value['table_name'])."' ");
			} else {
				// MySQL
				$tipe = $this->Common_model->get_record_value("DATA_TYPE", "information_schema.`COLUMNS`", "COLUMN_NAME = '". $value['id']."' AND table_schema = '".$DbName."'  AND TABLE_NAME = '".strtolower($value['table_name'])."' ");
			}

			if($tipe=='int'){

				$tipe = 'integer';
			}else if($tipe=='date'){

				$tipe = 'date';
			}else {
				$tipe = 'string';
			}

			$res[$key]['type'] = $tipe;
			
		}
		$tmp = $res;
		$data['daftar_filter'] = json_encode($tmp);
	
		return view('App\Modules\ClassificationManagement\Views\Classification_add_form_view', $data);
	}
	function save_classification_add(){
		$name = $this->input->getPost('txt-classification-name');
		$is_exist = $this->Classification_management_model->isExistclassification($name);
		if (!$is_exist) {
			$data["classification_name"] = $name;
			$data["check_number"] = $this->input->getPost("check-number");
			$data["class_category"]	= $this->input->getPost("txt-class-category");
			$data["classification_detail"]	= stripslashes(urldecode($this->input->getPost("sql")));
			$data["description"] = $this->input->getPost("description");
			$data["class_priority"] = $this->input->getPost("txt-classification-priority");
			$data["classification_json"] = $this->input->getPost("sql_json");
			// var_dump($data["classification_json"]);
			// die;
			$data["effective_date"]	= $this->input->getPost("effectiveDate");
			$data["spesific_date"] = $this->input->getPost("specificDate");
			$data["start_date"]	= $this->input->getPost("DateRangeFrom");
			$data["end_date"] = $this->input->getPost("DateRangeTo");
			$data["class_expired_date"]	= $this->input->getPost("expiredDate");
			$data["allocation_type"] = $this->input->getPost("opt-allocation-type");
			$data["class_type"]	= $this->input->getPost("opt-class-type");
			$data['updated_time'] = date('Y-m-d H:i:s');
			$data['updated_by']	=  session()->get('USER_ID');

			$schedule_detail = array();
			$schedule_detail["months"] =  $this->input->getPost("months-checkbox");
			$schedule_detail["options"] = $this->input->getPost("month-option");
			$schedule_detail["weekday"] = $this->input->getPost("weekday-checkbox");
			$schedule_detail["weeks"] = $this->input->getPost("weeks-checkbox");
			$schedule_detail["day"] = $this->input->getPost("day-checkbox");
			$data["schedule_detail"] = json_encode($schedule_detail);
			$opt_search = $this->input->getPost("opt-search");
			$txt_keyword = $this->input->getPost("txt-keyword");
			$opt_self = $this->input->getPost("opt-self");
			$update_sql = '';
			foreach($opt_search as $k => $v){
				if($k == 0){

					if(!empty($v)){
						if(!empty($opt_self[$k])){
							
							if (($opt_self[$k] == 'CF_USER_ACCOUNT_GROUP')&&($opt_self[$k] == 'last_agent1')) {
								$update_sql .= $v." = ".$opt_self[$k].$txt_keyword[$k];
							
							}else{
								$update_sql .= $v." = ".$v.$txt_keyword[$k];
							
							}
						}else{
							$update_sql .= $v." = '".$txt_keyword[$k]."'";
						}
					}else{

					}
				}else{
					if(!empty($opt_self[$k])){
						
						if (($opt_self[$k] == 'CF_USER_ACCOUNT_GROUP')||($opt_self[$k] == 'last_agent1')) {
							if(empty($update_sql)){
								$update_sql .= $v." = ".$opt_self[$k].$txt_keyword[$k];
							}else{
								$update_sql .= ", ".$v." = ".$opt_self[$k].$txt_keyword[$k];
							}
						}else{
							if(empty($update_sql)){
								$update_sql .= $v." = ".$opt_self[$k].$txt_keyword[$k];
							}else{
								$update_sql .= ", ".$v." = ".$opt_self[$k].$txt_keyword[$k];
							}
						}
					}else{
						if(empty($update_sql)){
							$update_sql .= $v." = '".$txt_keyword[$k]."'";
						}else{
							$update_sql .= ", ".$v." = '".$txt_keyword[$k]."'";
						}
					}
				}
			}
			$update_detail_json = array(
				'opt_search' => $opt_search,
				'opt_self' => $opt_self,  
				'txt_keyword' => $txt_keyword
			);
			$account_handling = $this->input->getPost("opt-handling") == null ? '' : implode(",", $this->input->getPost("opt-handling"));
			$data["account_handling"] = $account_handling;
			$data["sms_template"] = $this->input->getPost("opt-sms-template");
			$data["email_template"]	= $this->input->getPost("opt-email-template");
			// $data["wa_template"]			= $this->input->getPost("opt-wa-template");
			$data['update_detail'] = $update_sql;
			$data['update_detail_json'] = json_encode($update_detail_json);

			$data["job_schedule"] = $this->input->getPost("opt-schedule");
			if (!empty($this->input->getPost("opt-week"))) {
				$data["weekly_day"]	= implode(",", $this->input->getPost("opt-week"));
			}
			// $data["is_reset_allocation"]			= $this->input->getPost("opt-reset-allocation");
			$data["assignment_duration"] = $this->input->getPost("opt-assignment_duration");
			$data["expired_time"] = $this->input->getPost("expired_time");
			$data["assignment_weight"]	= '';
			$data["rule_type"]	= '';
			$data["account_list"] = '';
			$return = $this->Classification_management_model->save_classification_add($data);
			$filter["id"] = uuid();
			$filter["class_id"] = $this->db->insertID();
			$filter["value"] = $this->input->getPost("opt-detail-list");
			$filter["times"] = $this->input->getPost("txt-times");
			$filter["days"] = $this->input->getPost("txt-days");
			$filter["created_time"] = date('Y-m-d H:i:s');
			$filter["created_by"] = session()->get('USER_ID');
			$filter["parameter_field"] = $this->input->getPost("opt-filter-list");
			$this->Classification_management_model->save_classification_filter($filter);
			if($return){
				$cache = session()->get('USER_ID').'_classification_list';
				$this->cache->delete($cache);
				$this->Common_model->data_logging('classification', 'Add classification', 'SUCCESS', 'Set ' . $data["classification_name"] . ' = ' . $data["classification_detail"]);
				$rs = array('success' => true, 'message' => 'Success to save data', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$this->Common_model->data_logging('classification', 'Add classification ', 'FAILED', 'Set ' . $data["classification_name"] . ' = ' . $data["classification_detail"]);
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
			
		} else {
			$rs = array('success' => false, 'message' => 'Classification Name already exist. Please use another Name.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function classification_edit_form(){
		$DBDRIVER = $this->db->DBDriver;
		$classification_id = $this->input->getGet('id');
		$data['data'] = $this->Common_model->get_record_values("*", "cms_classification", "classification_id='$classification_id'", "");
		$data['update_detail_json'] = $data['data']['update_detail_json'];
		$DbName = $this->db->database;
		$data['field_name'] = $this->Common_model->get_record_list("column_name value, column_name AS item", "information_schema.COLUMNS", "TABLE_NAME = 'cms_account_last_status' and column_name not in ('account_no') AND table_schema = '".$DbName ."'", "column_name");
		$data['field_name'] = $data['field_name'] + array("CM_OLC_REASON_DESC" => "Remark", "CF_AGENCY_STATUS_ID" => "Agency Status ID", "CF_CAMPAIGN_ID" => "Campaign ID","AGENT_ID"=>"AGENT_ID");
		
		// array_unshift($data['field_name'], "[select data]");
		$data['order_list'] = $data['field_name'] + array("CM_TOT_AMNT_CHARGE_OFF" => "Amount Write Off", "SUM_WO_BALANCE" => "Sum CIF Amount Write Off");
		$data['check_number'] = $data['data']['check_number'];
		$data['valueAllocationType'] = $data['data']['allocation_type'];
		$data['valueAssignmentDuration'] = $data['data']['assignment_duration'];
		$data['expired_time'] = $data['data']['expired_time'];
		$data['effective_date'] = $data['data']['effective_date'];
		$data['class_expired_date'] = $data['data']['class_expired_date'];
		$data['classification_json'] = $data['data']['classification_json'];
		$data['update_detail_json'] = $data['data']['update_detail_json'];
		$data['job_schedule'] = $data['data']['job_schedule'];
		$arr_data = array();
		
		$data['account_group'] = $this->Common_model->get_record_list('id as value ,description as item', 'cms_master_account_group', "id is not null and flag='1' and flag_tmp='1'", "description");

		$data['account_handling'] = explode(',', $data['data']["account_handling"]);
		$data['sms_template_list'] = array("" => "SELECT SMS TEMPLATE") +  $this->Common_model->get_record_list("template_id value, template_name AS item", "cms_email_sms_template", "is_active='1' and sent_by='SMS'", "template_name");
		$data['email_template_list'] = array("" => "SELECT EMAIL TEMPLATE") +  $this->Common_model->get_record_list("template_id value, template_name AS item", "cms_email_sms_template", "is_active='1' and sent_by='Email'", "template_name");
		$data['wa_template_list'] = array("" => "SELECT Whatsaap TEMPLATE") +  $this->Common_model->get_record_list("template_id value, template_name AS item", "cms_wa_template", "is_active='1'", "template_name");
		$filter = $this->Common_model->get_record_values("*", "cms_classification_filter", "class_id='$classification_id'", "");
		$data["param"] = $this->Common_model->get_record_list("id value, category_name AS item", "cms_lov_registration", "is_active = '1'", "category_name");
		$data["value_c"] = $filter["value"] ?? '';
		$data["times"] = $filter["times"] ?? '';
		$data["days"]  = $filter["days"] ?? '';
		$parameter_field = $this->Common_model->get_record_values("value", "cms_classification_filter", "class_id='$classification_id'", "");
		// $data['parameter_value'] = explode(',', $parameter_field['value']);
		$data["parameter_field"]  = $filter["parameter_field"] ?? '';

		if ($DBDRIVER === 'SQLSRV') {
            // SQL Server
            $sql = "SELECT 'AGENT_ID' AS id, 'AGENT_ID' AS label, 'string' AS type, 'cpcrd_new' AS table_name
					UNION ALL
					SELECT 
						'DATEDIFF(DAY, CM_DTE_LST_PYMT, GETDATE())' AS id, 
						'DAYS SINCE LAST PAYMENT' AS label, 
						'int' AS type, 
						'cms_account_last_status' AS table_name
					UNION ALL
					SELECT 
						column_name AS id, 
						column_name AS label, 
						'string' AS type, 
						'cms_account_last_status' AS table_name
					FROM 
						INFORMATION_SCHEMA.COLUMNS
					WHERE 
						TABLE_NAME = 'cms_account_last_status' 
						AND column_name NOT IN ('account_no') 
						AND table_catalog = '{$DbName}'
					UNION ALL
					SELECT 
						id, 
						max(reference) AS label, 
						'string' AS type, 
						max(table_name) table_name 
					FROM 
						stagging_reference
					WHERE 
						reference IS NOT NULL
						AND id !=''
					GROUP BY 
						id
					ORDER BY 
						label ASC;
					";
        } elseif ($DBDRIVER === 'Postgre') {
            // PostgreSQL
           $sql = "SELECT 'AGENT_ID' AS id, 'AGENT_ID' AS label, 'string' AS type, 'cpcrd_new' AS table_name
					UNION ALL
					SELECT 
						DATE_PART('day', CURRENT_DATE - CM_DTE_LST_PYMT) AS id, 
						'DAYS SINCE LAST PAYMENT' AS label, 
						'int' AS type, 
						'cms_account_last_status' AS table_name
					UNION ALL
					SELECT 
						column_name AS id, 
						column_name AS label, 
						'string' AS type, 
						'cms_account_last_status' AS table_name
					FROM 
						information_schema.COLUMNS
					WHERE 
						TABLE_NAME = 'cms_account_last_status' 
						AND column_name NOT IN ('account_no') 
						AND table_schema = '{$DbName}'
					UNION ALL
					SELECT 
						id, 
						reference AS label, 
						'string' AS type, 
						table_name 
					FROM 
						stagging_reference
						AND id !=''
					WHERE 
						reference IS NOT NULL
					GROUP BY 
						id, label, table_name
					ORDER BY 
						label ASC;
					";
        } else {
            // MySQL
            $sql = 'SELECT "AGENT_ID" id ,"AGENT_ID"  as label , "string" as type ,"cpcrd_new" table_name UNION ALL ';
			$sql .= 'SELECT "DATEDIFF(CURDATE(),CM_DTE_LST_PYMT)" id ,"DAYS SINCE LAST PAYMENT"  as label , "int" as type ,"cms_account_last_status" table_name UNION ALL ';
			$sql .= 'SELECT column_name id, column_name as label , "string" as type ,"cms_account_last_status" table_name from information_schema.COLUMNS  where TABLE_NAME = "cms_account_last_status" and column_name not in ("account_no") AND table_schema = "'.$DbName .'"  UNION ALL ';
			$sql .= 'SELECT id ,reference as label , "string" as type ,table_name FROM stagging_reference WHERE reference is not null AND id !="" GROUP BY id  order by label asc ';
        }

		$res = $this->db->query($sql)->getResultArray();
		foreach ($res as $key => $value) {

			
			if ($DBDRIVER === 'SQLSRV') {
				// SQL Server
				$tipe = $this->Common_model->get_record_value("DATA_TYPE", "information_schema.COLUMNS", "COLUMN_NAME = '". $value['id']."' AND table_catalog = '".$DbName."'  AND TABLE_NAME = '".strtolower($value['table_name'])."' ");
			} elseif ($DBDRIVER === 'Postgre') {
				// PostgreSQL
				$tipe = $this->Common_model->get_record_value("DATA_TYPE", "information_schema.COLUMNS", "COLUMN_NAME = '". $value['id']."' AND table_schema = '".$DbName."'  AND TABLE_NAME = '".strtolower($value['table_name'])."' ");
			} else {
				// MySQL
				$tipe = $this->Common_model->get_record_value("DATA_TYPE", "information_schema.`COLUMNS`", "COLUMN_NAME = '". $value['id']."' AND table_schema = '".$DbName."'  AND TABLE_NAME = '".strtolower($value['table_name'])."' ");
			}
			if($tipe=='int'){

				$tipe = 'integer';
			}else if($tipe=='date'){

				$tipe = 'date';
			}else {
				$tipe = 'string';
			}
			$res[$key]['type'] = $tipe;
		}
		$tmp = $res;
		$data['daftar_filter'] = json_encode($tmp);
		// dd($data);
		return view('App\Modules\ClassificationManagement\Views\Classification_edit_form_view', $data);
	}
	function save_classification_edit(){
		$data["classification_id_old"]			= $this->input->getPost("classification_id");
		$data["classification_id"]			= $this->input->getPost("classification_id");
		$data["check_number"]			= $this->input->getPost("check-number");

		//$data["classification_id"]			= $this->common_model->clean_string(strtoupper($this->input->getPost("txt-classification-name")));
		$data["classification_name"]			= $this->input->getPost("txt-classification-name");
		$data["allocation_type"]			= $this->input->getPost("opt-allocation-type");
		$data["class_priority"] = $this->input->getPost("txt-classification-priority");
		$data["class_category"]			= $this->input->getPost("txt-class-category");
		$data["class_type"]			= $this->input->getPost("opt-class-type");
		$data["classification_detail"]		= stripslashes(urldecode($this->input->getPost("sql")));
		$data["description"]          = $this->input->getPost("description");
		$data["classification_json"]			= $this->input->getPost("sql_json");
		$data["class_expired_date"]			= $this->input->getPost("expiredDate");
		$schedule_detail = array();
		$schedule_detail["months"] =  $this->input->getPost("months-checkbox");
		$schedule_detail["options"] = $this->input->getPost("month-option");
		$schedule_detail["weekday"] = $this->input->getPost("weekday-checkbox");
		$schedule_detail["weeks"] = $this->input->getPost("weeks-checkbox");
		$schedule_detail["day"] = $this->input->getPost("day-checkbox");
		$data["schedule_detail"] = json_encode($schedule_detail);
		//$data['order_by_detail']  = implode(",",$this->input->get_post("opt-order", true));
		$order_f = $this->input->getPost("order_field_list");
		$order_desc = $this->input->getPost("order_desc");

		$order_fields = array();
		for ($i = 0; $i < count(array($order_f)); $i++) {
			$order_fields[]  = @$order_f[$i] . " " . @$order_desc[$i];
		}
		//			$data['order_by_detail']  = implode(",",$this->input->getPost("opt-order"));
		$data['order_by_detail'] = implode(",", $order_fields);

		$opt_search = $this->input->getPost("opt-search");
		$txt_keyword = $this->input->getPost("txt-keyword");
		$opt_self = $this->input->getPost("opt-self");
		$update_sql = '';
		if (!empty($opt_search)) {

			foreach ($opt_search as $k => $v) {

				if ($k == 0) {
					if (!empty($v)) {
						if (!empty($opt_self[$k])) {
							
							if (($opt_self[$k] == 'CF_USER_ACCOUNT_GROUP')||($opt_self[$k] == 'last_agent1')) {
								$update_sql .= $v . " = " . $opt_self[$k] . $txt_keyword[$k];
							
							} else {
								$update_sql .= $v . " = " . $v . $txt_keyword[$k];
							
							}
						} else {
							$update_sql .= $v . " = '" . $txt_keyword[$k] . "'";
							
						}
					} else {
					}
				} else {
					if (!empty($opt_self[$k])) {
						if (($opt_self[$k] == 'CF_USER_ACCOUNT_GROUP')||($opt_self[$k] == 'last_agent1')) {
							if (empty($update_sql)) {
								$update_sql .= $v . " = " . $opt_self[$k] . $txt_keyword[$k];
							} else {
								$update_sql .= ", " . $v . " = " . $opt_self[$k] . $txt_keyword[$k];
							}
						} else {
							if (empty($update_sql)) {
								$update_sql .= $v . " = " . $v . $txt_keyword[$k];
							} else {
								$update_sql .= ", " . $v . " = " . $v . $txt_keyword[$k];
							}
						}
					} else {
						if (empty($update_sql)) {
							$update_sql .= $v . " = '" . $txt_keyword[$k] . "'";
						} else {
							$update_sql .= ", " . $v . " = '" . $txt_keyword[$k] . "'";
						}
					}
				}
			}
		}

		$update_detail_json = array(
			'opt_search' => $opt_search,
			'opt_self' => $opt_self,
			'txt_keyword' => $txt_keyword
		);
		$account_handling = $this->input->getPost("opt-handling") == null ? '' : implode(",", $this->input->getPost("opt-handling"));
		$data["account_handling"]		= $account_handling;
		$data["sms_template"]			= $this->input->getPost("opt-sms-template");
		$data["email_template"]			= $this->input->getPost("opt-email-template");
		// $data["wa_template"]			= $this->input->getPost("opt-wa-template");
		$data['update_detail'] = $update_sql;
		$data['update_detail_json'] = json_encode($update_detail_json);

		$data["job_schedule"]			= $this->input->getPost("opt-schedule");
		$data["weekly_day"]			= implode(",", $this->input->getPost("opt-week")??array());
		// $data["is_reset_allocation"]			= $this->input->getPost("opt-reset-allocation");
		$data["effective_date"]			= $this->input->getPost("effectiveDate");
		$data["spesific_date"]			= $this->input->getPost("specificDate");
		$data["start_date"]			= $this->input->getPost("DateRangeFrom");
		$data["end_date"]			= $this->input->getPost("DateRangeTo");
		$data["assignment_duration"]			= $this->input->getPost("opt-assignment_duration");
		$data["expired_time"]			= $this->input->getPost("expired_time");
		// $data["distribution_method"]			= $this->input->getPost("opt-distribution-method");
		// dd($data);
		$return = $this->Classification_management_model->save_classification_edit($data);
		$filter["id"] = uuid();
		$filter["class_id"] = $data["classification_id_old"];
		$filter["value"] = $this->input->getPost("opt-detail-list");
		$filter["times"] = $this->input->getPost("txt-times");
		$filter["days"] = $this->input->getPost("txt-days");
		$filter["updated_time"] = date('Y-m-d H:i:s');
		$filter["updated_by"] = session()->get('USER_ID');
		$filter["parameter_field"] = $this->input->getPost("opt-filter-list");
		$this->Classification_management_model->edit_classification_filter($filter, $data["classification_id_old"]);
		if ($return) {
			$cache = session()->get('USER_ID').'_classification_list';
			$this->cache->delete($cache);
			$this->Common_model->data_logging('classification', 'Edit classification', 'SUCCESS', 'Set ' . $data["classification_name"] . ' = ' . $data["classification_detail"]);
			$rs = array('success' => true, 'message' => 'Success to update data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$this->Common_model->data_logging('classification', 'Edit classification ', 'FAILED', 'Set ' . $data["classification_name"] . ' = ' . $data["classification_detail"]);
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function delete_classification(){
		$classification_id = $this->input->getPost('id');
		$data =  $this->Common_model->get_record_values("classification_name,classification_detail", "cms_classification", "classification_id='$classification_id'", "");
		$return = $this->Classification_management_model->delete_classification($classification_id);
		if ($return) {
			$newCsrfToken = csrf_hash();
			$cache = session()->get('USER_ID').'_classification_list';
			$this->cache->delete($cache);
			$this->Common_model->data_logging('classification', 'Delete classification', 'SUCCESS', 'Set ' . $data["classification_name"] . ' = ' . $data["classification_detail"]);
			$rs = array('success' => true, 'message' => 'Success to delete data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON(array_merge($rs, ['newCsrfToken' => $newCsrfToken]));
		}else{
			$this->Common_model->data_logging('classification', 'Delete classification ', 'FAILED', 'Set ' . $data["classification_name"] . ' = ' . $data["classification_detail"]);
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function classification_test_form(){
		$classification_id = $this->input->getGet('id');
		$field_list = '[
			{ "id":"CM_CUSTOMER_NMBR", "label" :"Account Number", "type" : "string"},
			{ "id":"AGENT_ID", "label" :"CURRENT AGENT", "type" : "string"},
			{ "id":"CLASS", "label" :"CURRENT CLASS", "type" : "string"},
			{ "id":"BILL_BAL", "label" :"Bill Balance", "type" : "integer"},
			{ "id":"CM_BUCKET", "label" :"BUCKET", "type" : "string"},
			{ "id":"CM_CARD_NMBR", "label" :"Loan Number", "type" : "string"},
			{ "id":"CM_DTE_PYMT_DUE", "label" :"Due Date", "type" : "date"},
			{ "id":"DPD", "label" :"DPD", "type" : "integer"},
			{ "id":"CM_CARD_EXPIR_DTE", "label" :"Original Maturity Date", "type" : "date"},
			{ "id":"CM_DTE_LST_PYMT", "label" :"Last Payment Date", "type" : "date"},
			{ "id":"CM_TOT_BALANCE", "label" :"Baki Debet", "type" : "integer"},
			{ "id":"CM_CYCLE", "label" :"Cycle", "type" : "string"},
			{ "id":"AGENT_ID", "label" :"User ID", "type" : "string"},
			{ "id":"CM_CRLIMIT", "label" :"Limit", "type" : "integer"},
			{ "id":"CM_TENOR", "label" :"Tenor", "type" : "integer"},
			{ "id":"CM_INSTALLMENT_AMOUNT", "label" :"Installment Amount", "type" : "integer"},
			{ "id":"CM_OS_PRINCIPLE", "label" :"Outstanding Principle", "type" : "integer"},
			{ "id":"CM_COLLECTIBILITY", "label" :"Collectability", "type" : "string"},
			{ "id":"CM_CHGOFF_STATUS_FLAG", "label" :"Charge Off Status", "type" : "string"},
			{ "id":"CM_INSTALLMENT_NO", "label" :"Installment No.", "type" : "string"},
			{ "id":"CR_OCCUPATION", "label" :"Occupation", "type" : "string"},
			{ "id":"CM_DOMICILE_BRANCH", "label" :"Customer Branch ID", "type" : "string"}
			]
			';

		$field_array = json_decode($field_list, true);

		$data["str_header"] = "";
		$data["str_field"] = "";
		$i = 0;
		foreach ($field_array as $field) {
			//$arr_header[] = $field["label"];
			if ($i == 0) {
				$data["str_header"] .= $field["label"];
			} else {
				$data["str_header"] .= "','" . $field["label"];
			}

			$data["str_field"] .= "{name:'" . $field["id"] . "', index:'" . $field["id"] . "'," . ($field["type"] == "integer" ? 'formatter:"integer",align: "right",' : '')   . " width:100},";
			$i++;
		}
		$data["classification_id"] = $classification_id;

		return view('App\Modules\ClassificationManagement\Views\Classification_test_form_view', $data);

	}
	function get_classification_test(){
		$classification_id = $this->input->getGet('id');
		$data["classification_id"] = $classification_id;
		$data = $this->Classification_management_model->get_classification_test_current($classification_id);
		$rs = array('success' => true, 'message' => '', 'data' => $data);
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function apply_classification(){
		$classification_id = $this->input->getPost('id');
		$data["classification_id"] = $classification_id;
		$return = $this->Classification_management_model->apply_classification($classification_id);
		if ($return) {
			$newCsrfToken = csrf_hash();
			$cache = session()->get('USER_ID').'_classification_list';
			$this->cache->delete($cache);
			$this->Common_model->data_logging('classification', 'Apply classification', 'SUCCESS', 'Apply ' . $data["classification_id"]);
			$rs = array('success' => true, 'message' => 'Success to apply class', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON(array_merge($rs, ['newCsrfToken' => $newCsrfToken]));
		}else{
			$this->Common_model->data_logging('classification', 'Delete classification ', 'FAILED', 'Apply ' . $data["classification_id"]);
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function get_export_class(){
		$id = $this->input->getGet('id');
		$data["filename"] = "list_account_on_class_" . $id . ".xls";
		$data["account_list"] = $this->Classification_management_model->get_export_class($id);
		if (count($data['account_list']) > 0) {
			$data["header"] = array_keys($data["account_list"][0]);
		} else {
			$data["header"] = array();
		}
		return view('App\Modules\ClassificationManagement\Views\Export_class_view', $data);
	}

}