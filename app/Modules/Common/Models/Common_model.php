<?php	
namespace App\Models;
use CodeIgniter\Model;

Class Common_model Extends Model
{

	function clean_string($string) {
	   $string = str_replace(' ', '_', $string); // Replaces all spaces with underscored.
			$string =preg_replace('/[^A-Za-z0-9\-]/', '_', $string); // Removes special chars.
	   return $string;
	}

	function clean_string2($string) {
			 $string =preg_replace('/[^A-Za-z0-9\-]/', '_', $string); // Removes special chars.
		return $string;
	 }
	
	function get_agent_member(){
		$arr_agent = array();
			switch($this->session->userdata('LEVEL_GROUP')){
				case "AGENT" :
						$arr_agent[] = $this->session->userdata('USER_ID');
						break;
				case "TEAM_LEADER" :
					$agent_list = $this->common_model->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "team_leader = '".$this->session->userdata('USER_ID')."'");		
					$arr_agent = explode("|",$agent_list);
					$arr_agent[] = $this->session->userdata('USER_ID');
				
				break;
				case "SUPERVISOR" :
				$agent_list = $this->common_model->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "supervisor = '".$this->session->userdata('USER_ID')."'");		
				$arr_agent = explode("|",$agent_list);
				$arr_agent[] = $this->session->userdata('USER_ID');
				$arr_agent[] = $this->common_model->get_record_value(" team_leader", "cms_team", "supervisor = '".$this->session->userdata('USER_ID')."'");		
				break;
				default:
				
				break;
			}
		
		return $arr_agent;
		
	}
	function jqGridAccountList(){
		$script = "
	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		editurl: ci_update_controller,
		datatype: 'json',
		height: 360,
		width: null,
		colNames:['ASSIGNED_TO','CARD NO','FIN ACCT','CIF','NAME','CYCLE','OUTSTANDING','LAST RESPONSE','LAST CONTACT','CUST NO', 'ACCOUNT TAGGING', 'ACC STATUS','PROD CODE','AUTO DEBIT','BRANCH','DUE DATE','BUCKET','BLOCK CODE','BLOCK CODE DATE','CLASS','REMARKS','PHONE TAG'],
		colModel:[			
			{name:'AGENT_ID', index:'AGENT_ID', width:150},			
  {name:'a.CM_CARD_NMBR', index:'a.CM_CARD_NMBR', width:150},
  {name:'fin_account', index:'fin_account', width:150},
  {name:'CM_CUSTOMER_NMBR', index:'CM_CUSTOMER_NMBR', width:150},
			{name:'CR_NAME_1', index:'CR_NAME_1', width:260},
			{name:'CM_CYCLE', index:'CM_CYCLE', width:100},
			{name:'CM_TOT_AMNT_CHARGE_OFF', index:'CM_TOT_AMNT_CHARGE_OFF', width:110,formatter:'integer',searchoptions: {sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']}},
			{name:'last_response', index:'last_response', width:100},
			{name:'last_contact_time', index:'last_contact_time', width:100},
			{name:'CM_CUSTOMER_NMBR', index:'CM_CUSTOMER_NMBR', width:150},			
			{name:'ACCOUNT_TAGGING', index:'ACCOUNT_TAGGING', width:150},
			
			{name:'CM_STATUS_DESC', index:'CM_STATUS_DESC', width:150},
			{name:'CM_TYPE', index:'CM_TYPE', width:100},
			{name:'CM_USER_CODE_2', index:'CM_USER_CODE_2', width:100},
			{name:'CM_DOMICILE_BRANCH', index:'CM_DOMICILE_BRANCH', width:100},
			
			{name:'CM_DTE_PYMT_DUE', index:'CM_DTE_PYMT_DUE', width:110,searchoptions:
				{
					dataInit: function(el) {
						$(el).datepicker({
							changeYear: true,
							changeMonth: true,
							showButtonPanel: true,
							//dateFormat: 'yy-mm-dd',
							format: 'yyyy-mm-dd',
						}).on('changeDate',function(e){
                            setTimeout(function () {
                                $(grid_selector)[0].triggerToolbar();
                            }, 100);
							
						});
					}
				}
			},
			{name:'CM_BUCKET', index:'CM_BUCKET', width:110,hidden:true},
			
			{name:'CM_BLOCK_CODE', index:'CM_BLOCK_CODE', width:150,hidden:true},
			{name:'CM_DTE_BLOCK_CODE', index:'CM_DTE_BLOCK_CODE', hidden:true,width:110,searchoptions:
				{
					dataInit: function(el) {
						$(el).datepicker({
							changeYear: true,
							changeMonth: true,
							showButtonPanel: true,
							//dateFormat: 'yy-mm-dd',
							format: 'yyyy-mm-dd',
						}).on('changeDate',function(e){
                            setTimeout(function () {
                                $(grid_selector)[0].triggerToolbar();
                            }, 100);
							
						});
					}
				}
			},
			{name:'CLASS', index:'CLASS', width:150,hidden:true},
			{name:'CM_OLC_REASON_DESC', index:'CM_OLC_REASON_DESC', width:150},
			{ name: 'is_phone_tag', index: 'is_phone_tag', width: 110 }
		],
		ondblClickRow: function(id){
			var row = $(this).getRowData(id);
   			var norekening = row['list_number'];
			var buttons = {
				'button' :
				{
					'label' : 'Close',
					'className' : 'btn-sm'
				}
			}
			
   			showCommonDialog(window.innerWidth-30, window.innerHeight+50, 'Followup', GLOBAL_MAIN_VARS['SITE_URL'] + 'detail_account/detail_account?account_id='+ id , buttons);	

        },

		sortname: 'CR_NAME_1',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30,100,200,300,400,500],
		pager : pager_selector,
		altRows: true,
		multiselect: true,
		multiboxonly: true, 
		toolbar: [true, 'top'], 
		loadComplete : function() {
			var table = this;
			setTimeout(function(){
				updateActionIcons(table);
				updatePagerIcons(table);
				enableTooltips(table);
			}, 0);
		},
		autowidth: false,
		shrinkToFit: false
	});
	
	jQuery(grid_selector).jqGrid('filterToolbar',{defaultSearch:true,stringResult:true});
	//navButtons
	jQuery(grid_selector).jqGrid('navGrid', pager_selector,
		{ 	//navbar options
			edit: false,
			editicon : 'icon-pencil blue',
			add: false,
			addicon : 'icon-plus-sign purple',
			del: false,
			delicon : 'icon-trash red',
			search: true,
			searchicon : 'icon-search orange',
			refresh: true,
			refreshicon : 'icon-refresh green',
			view: false,
			viewicon : 'icon-zoom-in grey',
		},
		{
			//search form
			recreateForm: true,
			afterShowSearch: function(e){
				var form = $(e[0]);
				form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class=\"widget-header\" />')
				style_search_form(form);
			}
		}
	)
		
	";
	$script = "
	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		editurl: ci_update_controller,
		datatype: 'json',
		height: 360,
		width: null,
		colNames:['QUEUE DATE','ACCOUNT_NO','LOAN NO','DPD','BUCKET','PRODUCT TYPE','CYCLE','OS BAL','BILL BAL','Last Response','Last Response Time','CUST NAME','AGENT ID','PHONE TAG','ACCOUNT TAG'],
		colModel:[			
			{name:'assignment_start_date', index:'assignment_start_date', width:110,hidden:true,searchoptions:
				{
					dataInit: function(el) {
						$(el).datepicker({
							changeYear: true,
							changeMonth: true,
							showButtonPanel: true,
							//dateFormat: 'yy-mm-dd',
							format: 'yyyy-mm-dd',
						}).on('changeDate',function(e){
                            setTimeout(function () {
                                $(grid_selector)[0].triggerToolbar();
                            }, 100);
							
						});
					}
				}
			},

			{name:'CM_CUSTOMER_NMBR', index:'CM_CUSTOMER_NMBR', width:150},
			{name:'CM_CARD_NMBR', index:'CM_CARD_NMBR', width:150},
			{name:'DPD', index:'DPD', width:100},
			{name:'CM_BUCKET', index:'CM_BUCKET', width:100},
			{name:'CM_TYPE', index:'CM_TYPE', width:100},
			{name:'CM_CYCLE', index:'CM_CYCLE', width:100},
			{name:'CURRMTH_BAL_X', index:'CURRMTH_BAL_X', width:150,formatter:'number'},
			{name:'BILL_BAL', index:'BILL_BAL', width:100,formatter:'number',hidden:true},
			{name:'last_response', index:'last_response', width:100},
			{name:'last_contact_time', index:'last_contact_time', width:130},
			{name:'CR_NAME_1', index:'CR_NAME_1', width:260},
			{name:'AGENT_ID', index:'c.name', width:150},
			{name: 'is_phone_tag', index: 'is_phone_tag', width: 110 },
			{name: 'ACCOUNT_TAGGING', index: 'ACCOUNT_TAGGING', width: 110 }
                        
		],
		ondblClickRow: function(id){
			var row = $(this).getRowData(id);
   			var norekening = row['list_number'];
			var buttons = {
				'button' :
				{
					'label' : 'Close',
					'className' : 'btn-sm'
				}
			}
			var selr = $(this).jqGrid('getGridParam', 'selrow');
			let ACCOUNT_TAGGING = $(this).getCell(selr, 'ACCOUNT_TAGGING');
			console.log('selr',selr);
			console.log('ACCOUNT_TAGGING',ACCOUNT_TAGGING);
			if(ACCOUNT_TAGGING==''){
				loadDataCustomer(id);
			}else{
				showInfo('upss.. Account tagging ongoing', 2000);
			}
   			

        },

		sortname: 'CR_NAME_1',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30,100,200,300,400,500],
		pager : pager_selector,
		altRows: true,
		multiselect: true,
		multiboxonly: true, 
		toolbar: [true, 'top'], 
		loadComplete : function() {
			var table = this;
			setTimeout(function(){
				updateActionIcons(table);
				updatePagerIcons(table);
				enableTooltips(table);
			}, 0);
		},
		autowidth: false,
		shrinkToFit: false
	});
	
	jQuery(grid_selector).jqGrid('filterToolbar',{defaultSearch:true,stringResult:true});
	//navButtons
	jQuery(grid_selector).jqGrid('navGrid', pager_selector,
		{ 	//navbar options
			edit: false,
			editicon : 'icon-pencil blue',
			add: false,
			addicon : 'icon-plus-sign purple',
			del: false,
			delicon : 'icon-trash red',
			search: true,
			searchicon : 'icon-search orange',
			refresh: true,
			refreshicon : 'icon-refresh green',
			view: false,
			viewicon : 'icon-zoom-in grey',
		},
		{
			//search form
			recreateForm: true,
			afterShowSearch: function(e){
				var form = $(e[0]);
				form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class=\"widget-header\" />')
				style_search_form(form);
			}
		}
	)
		
	";


	return $script;
	}

	function data_logging($module, $action, $result, $description)
	{
		log_message('info', $module.' | '.$action.' | '.$result.' | '.$description.' | '.$this->session->userdata('USER_ID'));
		
		$data = array(
      'id'					=> uuid(),
		  'ip_address'	=> $_SERVER["REMOTE_ADDR"],
		  'module'			=> $module,
		  'action'			=> $action,
		  'result'			=> $result,
		  'description'	=> $description,
		  'created_by'	=> $this->session->userdata('USER_ID'),
		  'created_time'=> date('Y-m-d H:i:s')
    );
		$this->db->insert('cc_app_log', $data);
		
	  return true;
	}
	
	function get_ref_master($fieldName, $tableName, $criteria, $orderBy,$header = TRUE)
	{
		$arrData = array();
		
		
		if($header)
		{
			$arrData = array("" => "[select data]");
		}		
		$sql = "SELECT ".$fieldName." FROM ".$tableName;
		
		if($criteria)
		{
			$sql .= " WHERE ".$criteria;
		}
		
		if($orderBy)
		{
			$sql .= " ORDER BY ".$orderBy;
		}
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows())
		{
			foreach ($query->result() as $row)
			{
				$arrData[$row->value] = $row->item;
			}
		}
		
		return $arrData;	
	}
	
	
	function get_record_value_cti($fieldName, $tableName, $criteria)
	{	
		$this->cti = $this->load->database('cti',true);
		$sql = "SELECT $fieldName FROM $tableName WHERE $criteria";
		$query = $this->cti->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$data=$query->row_array();
			return array_pop($data);
		}
	  
	  return null;
	}

	public function get_record_value_crm($fieldName, $tableName, $criteria)
    {
        $sql = "SELECT $fieldName FROM $tableName WHERE $criteria";
		$query = $this->crm->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$data=$query->row_array();
			return array_pop($data);
		}
	  
	  	return null;
    }
	
	function update_record_value($arrData, $tableName, $criteria)
	{	
		$this->db->where($criteria);
		$return = $this->db->update($tableName, $arrData); 
	  
	  return $return;
	}
	/*
	function update_record_values($arrData, $tableName, $criteria)
	{	
		$this->db->where($criteria);
		$return = $this->db->update($tableName, $arrData); 
	  
	  return $return;
	}
	*/
	function get_record_values($fieldName, $tableName, $criteria)
	{	
		$arr_data = array();
		$sql = "SELECT ".$fieldName." FROM ".$tableName;
		
		if ($criteria)
		{
			$sql .= " WHERE ".$criteria;
		}
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows())
		{
			foreach ($query->result() as $row)
			{
		    foreach ($row as $key => $value){
	        $arr_data[$key] = $value;  
				}
			}
		}
		
		return $arr_data;
	}

	function get_record_values_cti($fieldName, $tableName, $criteria)
	{	
		$this->cti = $this->load->database('cti',true);
		$arr_data = array();
		$sql = "SELECT ".$fieldName." FROM ".$tableName;
		
		if ($criteria)
		{
			$sql .= " WHERE ".$criteria;
		}
		
		$query = $this->cti->query($sql);
		
		if ($query->num_rows())
		{
			foreach ($query->result() as $row)
			{
		    foreach ($row as $key => $value){
	        $arr_data[$key] = $value;  
				}
			}
		}
		
		return $arr_data;
	}
	
	function get_record_list($fieldName, $tableName, $criteria, $orderBy)
	{	
		$arr_data = array();
		$sql = "SELECT ".$fieldName." FROM ".$tableName;
		
		if ($criteria)
		{
			$sql .= " WHERE ".$criteria;
		}
		
		if ($orderBy)
		{
			$sql .= " ORDER BY ".$orderBy;
		}
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows())
		{
			foreach ($query->result() as $row)
			{
				$arr_data[$row->value] = $row->item;
			}
		}
		
		return $arr_data;
	}
	
	function get_record_listx($fieldName, $tableName, $criteria, $orderBy)
	{	
		$arr_data = array();
		$sql = "SELECT ".$fieldName." FROM ".$tableName;
		
		if ($criteria)
		{
			$sql .= " WHERE ".$criteria;
		}
		
		if ($orderBy)
		{
			$sql .= " ORDER BY ".$orderBy;
		}
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows())
		{
			foreach ($query->result() as $row)
			{
				$arr_data["x".$row->value] = $row->item;
			}
		}
		
		return $arr_data;
	}
	
	function get_running_text()
	{		
		$sql = "SELECT message FROM cc_running_text WHERE  start_date <= NOW() AND  end_date >= NOW() and is_active='1' order by start_date,created_time, updated_time asc";
		$query = $this->db->query($sql);
		$message = "";
		$jumlah = $query->num_rows();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row)
			{
				if($jumlah%2==0)
				{
					if(!empty($message))
					{
						$message .= " <img src='".base_url()."/assets/images/navigate_left2.png'> <span style='color:orange'>".$row->message.'</span>';
					}
					else
					{
						$message .= "<span style='color:white'>".$row->message.'</span>';
					}
				}
				else
				{
					if(!empty($message))
					{
						$message .= " <img src='".base_url()."/assets/images/navigate_left2.png'> <span style='color:yellow'>".$row->message.'</span>';
					}
					else
					{
						$message .= "<span style='color:yellow'>".$row->message.'</span>';
					}
				}
				$jumlah = $jumlah-1;
			}
		}
		else
		{
		$message = 'Welcome To Helpdesk Contact Center.';
		}

		return $message;
	}
	
	function sla_telephony($context)
	{
		$this->cti = $this->load->database('ecentrix', true);
		$sql = "SELECT 
							a.agent_id as agent_id, a.direction as direction, a.start_time as start_time, a.end_time as end_time,
							SUM( CASE b.current_status WHEN 1 THEN (b.duration/1000) ELSE 0 END )+(SUM( CASE b.current_status WHEN 3 THEN (b.duration/1000) ELSE 0 END ))+(SUM( CASE b.current_status WHEN 4 THEN (b.duration/1000) ELSE 0 END )) jumlah, 
							a.last_status as last_status, a.context as context, now() as insert_time, a.start_time as report_time
						FROM
							ecentrix.ecentrix_session_log a, ecentrix.ecentrix_session_track b
						WHERE
							a.id = b.session_log_id AND a.context='".$context."'
							and date(a.start_time) = date(now())
							and direction='INB' and a.last_status in (10, 19, 14, 15)
						GROUP BY
							a.call_id";
		$query = $this->cti->query($sql);	
		$less = array();
		$more = array();
		$abandone_while_ringing = array();
		$abandone_on_queue = array();
		$abandone_transfer = array();
		$abandone_more = array();
		$abandone_less = array();
		
		foreach($query->result() as $data)
		{
			if(($data->jumlah <= 30) && ($data->last_status == '10'))
			{
				$less[] = $data->jumlah;
			}
			else if(($data->jumlah >= 30) && ($data->last_status == '10'))
			{
				$more[] = $data->jumlah;
			}
			else if(($data->jumlah <= 5) && ($data->last_status != '10'))
			{
				$abandone_less[] = $data->jumlah;
			}
			else if(($data->jumlah >= 5) && ($data->last_status != '10'))
			{
				$abandone_more[] = $data->jumlah;
			}
			
			if($data->last_status == '14')
			{
				$abandone_on_queue[] = $data->jumlah;
			}
			
			if($data->last_status == '19')
			{
				$abandone_while_ringing[] = $data->jumlah;
			}
			
			if($data->last_status == '15')
			{
				$abandone_transfer[] = $data->jumlah;
			}
		}
		
		if(count($less) == 0)
		{
			$percent = 0;
		}
		else
		{
			$percent = round(((count($less) / (count($less) + count($more) + count($abandone_less) + count($abandone_more)))*100),2);
		}
		
		$total = (count($less) + count($more) + count($abandone_more) + count($abandone_less));
		$arr_data = array("total_call" => $total, "less" => count($less), "more" => count($more), "abandone_more" => count($abandone_more), "abandone_less" => count($abandone_less), "percent" => $percent, "abandoneRinging" => count($abandone_while_ringing), "abandoneQueue" => count($abandone_on_queue), "abandoneTransfer" => count($abandone_transfer));
		
		return json_encode($arr_data);		
	}
	
	function knowledge_data()
	{
		$sql = "SELECT description, description_file, file_path, SUBSTRING_INDEX(file_path, '/', -1) file_name
						FROM cc_knowledge 
						WHERE description LIKE '%".$this->input->get_post('id')."%' 
						OR description_file LIKE '%".$this->input->get_post('id')."%' 
						OR file_path LIKE '%".$this->input->get_post('id')."%'";
						
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		$list = array();
		
		foreach($query->result() as $row){
			$list[] = array(
				'description' => $row->description." - ".$row->description_file,
				'file' => $row->file_path,
				'file_name' => $row->file_name,
			);
		}
		
		$result = array(
			'total' => $total,
			'list' => $list
		);
		
		echo json_encode($result);		
	}
	
	function json_data()
	{
		$arrColor = array('0' => '#808080', '1' => '#68BC31', '2' => '#DA5430', '3' => '#AF4E96');
		$arrLabel = array('0' => 'Agent Not Login', '1' => 'Agent Idle', '2' => 'Agent Not Active', '3' => 'Agent Talking');	
		$arrData = array('0' => '0', '1' => $this->input->get_post('idle'), '2' => $this->input->get_post('notactive'), '3' => $this->input->get_post('talking'));
		
		for($i=1; $i<=3; $i++)
		{
			$list[] = array(
				'label' => $arrLabel[$i],
				'data' => $arrData[$i],
				'color' => $arrColor[$i],
			);
		}
		
		echo json_encode($list);		
	}
	
	function check_phone($phone)
	{
		$real_phone = $phone;
		
		$sql = "SELECT customer_id FROM cc_master_customer_phone WHERE phone_number='".$real_phone."'";
		$query = $this->db->query($sql);		
		
		if($query->num_rows() == 1)
		{
			$responce = array("num_rows" => $query->num_rows(), "customer_id"=>array_pop($query->row_array()));
			echo json_encode($responce);
		}
		else if($query->num_rows() == 0)
		{
			$responce = array("num_rows" => $query->num_rows(), "customer_id"=>"");
			echo json_encode($responce);
		}
		else
		{
			$customer_id = '';
			$j = 0;
		
			foreach($query->result() as $row)
			{
				if($j == 0 )
				{
					$customer_id .= $row->customer_id;
				}
				else
				{
					$customer_id .= ','.$row->customer_id;
				}
				$j++;
			}
			
			$responce = array("num_rows" => $query->num_rows(), "customer_id" => $customer_id);
			
			echo json_encode($responce);
		}		
	}
	
	function get_province_list()
	{
		$arr_data = array();
		
		$arr_data[""] = "(select...)"; 
		$where = array();
		$this->db->select('id_province, province');
		$this->db->from('cc_master_province');

		$query = $this->db->get();
		
		if ($query->num_rows())
		{
			foreach ($query->result() as $row)
			{
				$arr_data[$row->id_province] = $row->province;
			}
		}
		return $arr_data;
	}
	
	function create_xml_file_from_data($outputPathFilename, $fieldName, $tableName, $criteria, $orderBy)
	{	
		header('Content-type: text/xml; charset=utf-8');
		
		$sql = "SELECT ".$fieldName." FROM ".$tableName;
		
		if ($criteria)
		{
			$sql .= " WHERE ".$criteria;
		}
		
		if ($orderBy)
		{
			$sql .= " ORDER BY ".$orderBy;
		}
		
		$query = $this->db->query($sql);
		
		$config = array (
			'root'    => 'loanos',
			'element' => 'data',
			'newline' => "\n",
			'tab'     => "\t"
    );
    
    $this->load->dbutil();
    $xml = $this->dbutil->xml_from_result($query, $config);
    $this->output->set_content_type('text/xml');
		//$this->output->set_output(trim($xml)); 
		
		$this->load->helper('file');
		$file_name = $outputPathFilename;
		if ( ! write_file($file_name, $xml))
		{
    	return true;
		}
		else
		{
    	return false;
		}
		
		// Optionally redirect to the file you (hopefully) just created
		//redirect($file_name); 
	}
	
	function proceed_bi_data()
	{
		$this->db->select('SQL_CALC_FOUND_ROWS 
			raw.id,
			no_registrasi,
			nama_bank,
			IF(fasilitas = "30",
				"CC",
				IF(raw.fasilitas IN ("10", "15"), 
					"KPM", 
					IF(crt.id IN ("KPR", "TLA"), 
						crt.id,
						IF(crt.id = "KMK", 
							IF(raw.usage_amount <> 0, 
							"KMK", 
							"TLA"), 
							IF(crt.id = "KTA", 
								IF(raw.bunga < 39, 
									"KTA", 
									"KTA RK"
								), 
								"TLA"
							)
						)
					)
				)
			) AS fasilitas,
			IF(fasilitas = "30",
				plafond,
				IF(raw.fasilitas IN ("10", "15"), 
					outstanding, 
					IF(crt.id IN ("KPR", "TLA"), 
						outstanding,
						IF(crt.id = "KMK", 
							IF(raw.usage_amount <> 0, 
							plafond, 
							outstanding), 
							IF(crt.id = "KTA", 
								IF(raw.bunga < 39, 
									outstanding, 
									plafond
								), 
								outstanding
							)
						)
					)
				)
			) AS plafond,
			outstanding,
			usage_amount,
			bunga,
			awal_kredit,
			akhir_kredit,
			sisa_kredit,
			"" AS kewajiban_angsuran,
			kolektibilitas1,
			kolektibilitas2,
			maks_terlambat,
			"" AS kode_bank,
			source_file AS aav_file_name,
			ocr_recognize_time', false);
			
		$this->db->from('aav_bi_data_raw AS raw');	
		$this->db->join('aav_credit_type AS crt', 'crt.key_desc = SUBSTRING_INDEX(raw.ket_kredit, " " , 2)', 'LEFT');
		$this->db->where('raw.status = "OPEN"');
		
		$rResult = $this->db->get();
		
		// Total data set length	
		$iTotal = $rResult->num_rows();
		if( $iTotal > 0 )
		{
			foreach($rResult->result_array() as $aRow)
			{
				$return = $this->db->replace('aav_bi_data', $aRow);
				if($return)
				{
					$update_data = array("status" => "PROCEED");
					$this->db->where('id', $aRow["id"]);
					$return = $this->db->update('aav_bi_data_raw', $update_data);
				}
			}
		}
	}
	
	function getAgentList($field, $value){
		$return = array();
		$sql = "SELECT agent_list FROM cms_team WHERE ".$field." = '".$value."'";
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		
		return $return;
	}

	function MonthIndo($tgl){
		$tgl = explode(" ",$tgl);
		$t=explode("-",$tgl[0]);
		
		$b=array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember');
		
		
		
		$tanggal=@$t[2];
		$bulan=@$b[$t[1]];
		$tahun=@$t[0];
		
		$jam = @$tgl[1];
		if($jam<>''){
			$jam = explode(":",@$jam);
			$jam = @$jam[0].':'.@$jam[1];
		}
		

		$indo = $tanggal." ".$bulan." ".$tahun." ".$jam;
		
		return $indo;
	}
	
	function DayIndo($day){
		
		if($day=="Sunday"){
			$day = "Minggu";
		}
		else if($day=="Monday"){
			$day = "Senin";
		}
		else if($day=="Tuesday"){
			$day = "Selasa";
		}
		else if($day=="Wednesday"){
			$day = "Rabu";
		}
		else if($day=="Thursday"){
			$day = "Kamis";
		}
		else if($day=="Friday"){
			$day = "Jumat";
		}
		else if($day=="Saturday"){
			$day = "Sabut";
		}
		return $day;
	}
	
	function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = $this->penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = $this->penyebut($nilai/10)." puluh". $this->penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . $this->penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = $this->penyebut($nilai/100) . " ratus" . $this->penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . $this->penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = $this->penyebut($nilai/1000) . " ribu" . $this->penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = $this->penyebut($nilai/1000000) . " juta" . $this->penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = $this->penyebut($nilai/1000000000) . " milyar" . $this->penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = $this->penyebut($nilai/1000000000000) . " trilyun" . $this->penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}
	
	function check_hirarki(){
		$user_id = $this->session->userdata('USER_ID');
		$hirarki="";
		$sql="select level from cc_user_group a, cc_user b where a.id=b.group_id and b.id='".$user_id."'";
		$res=$this->db->query($sql);
		$level=$res->result_array();
		//3 SELECT id FROM cc_user WHERE spv_id in ('ghozali')
		//4 SELECT id FROM cc_user WHERE spv_id IN (SELECT id FROM cc_user WHERE spv_id in ('xxxx'))
		//5 SELECT id FROM cc_user WHERE spv_id IN (SELECT id FROM cc_user WHERE spv_id in (select id from cc_user where spv_id in ('xxxx')))
		
		$var="SELECT id FROM cc_user WHERE spv_id in (xxx)";	
		for($i=2;$i<=$level[0]['level'];$i++){
		//for($i=2;$i<=3;$i++){	
			if($i==2) {
				$var="select id from cc_user where id in ('".$user_id."')";
			}else  {
				$var="SELECT id FROM cc_user WHERE spv_id in (".$var.")";
				
			}
				
		}
		
		
		$sql=str_replace("xxx","'".$user_id."'",$var);
		
		return $sql;
	}

	function hash256($input)
	{
		return hash_hmac('sha256', $input, $this->config->item('encryption_key'));
	}

	function get_wav_file($file_path){
		$file_path = str_replace(' ','%20',$file_path);
		$download = substr($file_path, 6);
		$arr = explode('/',$file_path);
		
		$wav = substr($arr[7], 0,-4);
		$wav = str_replace('%20','_',$wav);
		$wav = str_replace('%20','-',$wav);
		$existing = 'gsm';
		$format = 'wav';
		

		$dir = '/var/www/democoll74/temp/'.$this->session->userdata('USER_ID');
		

		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}else{
			foreach(scandir($dir) as $file) {
				if ('.' === $file || '..' === $file) continue;
				if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
				else unlink("$dir/$file");
			}
			rmdir($dir);
			mkdir($dir, 0777, true);
		}
		$source = $dir."/".$arr[7];
		$target = $dir."/".$wav.".".$format;
		
		
		$contextOptions = array(
			"ssl" => array(
				"verify_peer"      => false,
				"verify_peer_name" => false,
				),
			);
		copy($file_path, $source, stream_context_create( $contextOptions ) );
		$this->gsm_to_wav($source, $target);
		$response = array('path' => $target,'download' => $download);
		
		return "https://".$_SERVER['SERVER_NAME']."/temp/".$this->session->userdata('USER_ID')."/".$wav.".wav";
	}

	function gsm_to_wav ($source, $target){
		exec("/usr/bin/sox ".$source." -c1 -r8000 -e signed-integer ".$target);
	}

	public function get_record_array($fieldName, $tableName, $criteria)
	{	
		$sql = "SELECT $fieldName FROM $tableName WHERE $criteria";
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		return null;
	}
	
	function generate_no_surat($branch_id,$jenis_surat,$status='1'){
		if($branch_id==''){
			return false;
		}
		else if($jenis_surat==''){
			return false;
		}else{
			$jml_ral = $this->get_record_value('seq','mst_seq','jenis = "'.$jenis_surat.'" and branch_id="'.$branch_id.'"  and tahun = "'.date('Y').'" and status="1" order by seq desc limit 1');
			if($jml_ral==''){
				$jml_ral='0';
				$seq = str_pad($jml_ral,5,'0',STR_PAD_LEFT);
			}else{
				$jml_ral = $jml_ral+1;
				$seq = str_pad($jml_ral,5,'0',STR_PAD_LEFT);
			}
			
			$data_seq = array(
				'id'				=> uuid(),
				'branch_id'			=> $branch_id,
				'jenis'				=> $jenis_surat,
				'tanggal'			=> date('d'),
				'bulan'				=> date('m'),
				'tahun'				=> date('Y'),
				'seq'				=> $jml_ral,
				'status'			=> $status, //1=PRINT ,2=preview
				'keterangan'		=> NULL,
				'created_by'		=> $this->session->userdata('USER_ID'),
				'created_time'		=> date('Y-m-d H:i:s')
				);
			$this->db->insert('mst_seq', $data_seq);	
			$no_ral = $branch_id.''.$jenis_surat.''.$seq.''.date('m').''.date('d').''.date('y');
			return $no_ral;
		}			
		
	}
	public function get_record_array_cms($fieldName, $tableName, $criteria)
	{	
	
		// $this->cms = $this->load->database('cms', TRUE);
		$sql = "SELECT $fieldName FROM $tableName WHERE $criteria";
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		return null;
	}

	function push_notification_template($menu_id, $template_id, $param = array(), $to = array()){
		if($to=="*"){
			$sql = "SELECT * FROM cms_notification_registered WHERE user_id not in (?)";
			$res = $this->db->query($sql,array($this->session->userdata('USER_ID')))->result_array();
		
			$to = array();
			foreach ($res as $key => $value) {
				$to[$key] = $value['user_id'];
			}
		}

		if(count($to)>0){
		
			if($menu_id != ''){
				$jumlah = $this->get_record_value('count(*)','cc_menu','menu_id ="'.$menu_id.'" ');

				if($jumlah>0){
					$sql = "SELECT * FROM cms_notification_template WHERE template_id = ? limit 1";
					$res = $this->db->query($sql,array($template_id))->result_array();

					if(count($res)>0){
						foreach ($res as $key => $value) {

							$msg = str_replace('[[USER]]',$param['USER'], $value['message']);

							$notification_id = 'TEMPLATE'.date('YmdHis');
							$data['id'] = uuid(false);
							$data['notification_id'] = $notification_id;
							$data['notification_type'] =  $value['notification_type'];
							$data['template_id'] =  $template_id;
							$data['title'] = $value['title'];
							$data['message'] = $msg;
							$data['data'] = json_encode($param);
							$data['menu_id'] = $menu_id;
							$data['created_by'] = $this->session->userdata('USER_ID');
							$data['created_time'] = date('Y-m-d H:i:s');
							$this->db->insert('cms_notification',$data);

							foreach ($to as $keyx => $valuex) {
								$datato['notification_id'] = $notification_id;
								$datato['to'] = $valuex;
								$datato['is_read'] = '0';
								$datato['created_time'] =  date('Y-m-d H:i:s');

								$this->db->insert('cms_notification_destination',$datato);
							}
						}
					}

					return $notification_id;
			
				}
			}else{
				return false;
			}
				
			
		}
	}

	function push_notification_free($menu_id='',$notification_type='INFORMATION', $title='Information', $message='', $to = array()){
			$notification_id = 'FREE'.date('YmdHis');
			$notification_type_master = ['INFORMATION','WARNING','DANGER','SUCCESS'];
			
			if(!in_array($notification_type,$notification_type_master)){
				$notification_type = 'INFORMATION';
			}

			$data['id'] = uuid(false);
			$data['notification_id'] = $notification_id;
			$data['notification_type'] =  $notification_type;
			$data['template_id'] =  NULL;
			$data['title'] = $title;
			$data['message'] = $message;
			$data['data'] = NULL;
			$data['menu_id'] = $menu_id;
			$data['created_by'] = $this->session->userdata('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			$this->db->insert('cms_notification',$data);

			foreach ($to as $keyx => $valuex) {
				$datato['notification_id'] = $notification_id;
				$datato['to'] = $valuex;
				$datato['is_read'] = '0';
				$datato['created_time'] =  date('Y-m-d H:i:s');

				$this->db->insert('cms_notification_destination',$datato);
			}

			return $notification_id; 
	}

 
}	
