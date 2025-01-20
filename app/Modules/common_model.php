<?php	if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Common_model Extends CI_model
{

	function clean_string($string) {
	   $string = str_replace(' ', '_', $string); // Replaces all spaces with underscored.
			$string =preg_replace('/[^A-Za-z0-9\-]/', '_', $string); // Removes special chars.
	   return $string;
	}
	function jqGridAccountList(){
		$script = "
	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		editurl: ci_update_controller,
		datatype: 'json',
		height: 360,
		width: null,
		colNames:['CUST NO','NAME', 'ACCOUNT TAGGING', 'CARD NO','ACC STATUS','PROD CODE','BRANCH','CYCLE','DUE DATE','BUCKET','OUTSTANDING','BLOCK CODE','BLOCK CODE DATE','CLASS'],
		colModel:[			
			{name:'CM_CUSTOMER_NMBR', index:'CM_CUSTOMER_NMBR', width:150},			
			{name:'CR_NAME_1', index:'CR_NAME_1', width:260},
			{name:'ACCOUNT_TAGGING', index:'ACCOUNT_TAGGING', width:150},
			{name:'CM_CARD_NMBR', index:'CM_CARD_NMBR', width:150},
			{name:'CM_STATUS_DESC', index:'CM_STATUS_DESC', width:150},
			{name:'CM_TYPE', index:'CM_TYPE', width:100},
			{name:'CM_DOMICILE_BRANCH', index:'CM_DOMICILE_BRANCH', width:100},
			{name:'CM_CYCLE', index:'CM_CYCLE', width:100},
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
			{name:'CM_BUCKET', index:'CM_BUCKET', width:110,},
			{name:'CM_TOT_BALANCE', index:'CM_TOT_BALANCE', width:110,formatter:'integer',searchoptions: {sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']}},
			{name:'CM_BLOCK_CODE', index:'CM_BLOCK_CODE', width:150},
			{name:'CM_DTE_BLOCK_CODE', index:'CM_DTE_BLOCK_CODE', width:110,searchoptions:
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
			{name:'CLASS', index:'CLASS', width:150},
                        
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
		rowList:[10,20,30],
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
		$this->db->insert('cc_app_log', $data, FALSE);
		
	  return true;
	}
	
	function get_ref_master($fieldName, $tableName, $criteria, $orderBy)
	{
		$arrData = array();
		$header = TRUE;
		
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
	
	function get_record_value($fieldName, $tableName, $criteria)
	{	
		$sql = "SELECT $fieldName FROM $tableName WHERE $criteria";
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
	    return array_pop($query->row_array());
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
}	