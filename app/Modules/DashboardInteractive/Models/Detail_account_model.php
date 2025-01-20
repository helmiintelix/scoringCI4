<?php
namespace App\Modules\DetailAccount\models;
use App\Models\Common_model;
use CodeIgniter\Model;

Class Detail_account_model Extends Model 
{
	protected $Common_model;
	function __construct()
	{
		parent::__construct();
		$this->Common_model = new Common_model();
	}

    function get_contract_data_new($customer_id,$account_no)
	{	
		$sql = "SET SESSION group_concat_max_len = 1000000;";
		$this->db->query($sql);

        $product_id = $this->Common_model->get_record_value("CM_TYPE","cpcrd_new","CM_CARD_NMBR='".$account_no."' ");

		$CHECK = $this->Common_model->get_record_value("product_id","cc_detail_account_mapping","product_id='".$product_id."' ");
		if($CHECK==''){
			$fields = $this->Common_model->get_record_value("GROUP_CONCAT( CONCAT('a.',a.field_name , ' ' )) as field","cc_detail_account_mapping a","product_id='DEFAULT'");
		}else{
			$fields = $this->Common_model->get_record_value("GROUP_CONCAT( CONCAT('a.',a.field_name , ' ' )) as field","cc_detail_account_mapping a","product_id='".$product_id."'");
		}
		$koma = ' , ';
		if($fields != ''){
			$fields = $koma.$fields;
		}

		$this->builder = $this->db->table('cpcrd_new a');
		$this->builder->select(
			"DISTINCT(CM_CARD_NMBR) as CARD_NO,
			CM_CARD_NMBR,
			CM_AMOUNT_DUE,
			CM_CUSTOMER_NMBR,
			CM_BUCKET,
			fin_account,
			CM_BLOCK_CODE,
			status_pengajuan,
			tgl_pengajuan,
			u.name as agent_name,
			last_notes,
			CF_AGENCY_STATUS_ID,
			b.hot_notes,
			if(CM_CARD_NMBR='$account_no',0,1) as urut".$fields.""
		);
		$this->builder->join('cms_account_last_status b', 'a.CM_CARD_NMBR=b.account_no');
		$this->builder->join('cc_user u', 'a.AGENT_ID = u.id');
		$this->builder->where('a.CM_CUSTOMER_NMBR', $customer_id);
		$this->builder->orderBy('urut');
		$query = $this->builder->get();
		$data = $query->getResultArray();

		if(count($data) > 0){
				
			$data_history = array("id"=>uuid(),"action_code"=>"8","account_no" => $data[0]["CARD_NO"],"customer_no" => $data[0]["CM_CUSTOMER_NMBR"],"user_id"=>session()->get('USER_NAME'),"input_source"=>"ACTIVITY","notes" => "review by ".session()->get('USER_NAME'),"created_time" => date('Y-m-d H:i:s') ,'created_by' => session()->get('USER_NAME'),'fin_acc'=> $this->Common_model->get_record_value("fin_account","cpcrd_new","CM_CARD_NMBR= '".$data[0]["CARD_NO"]."'")); 
			$this->builder = $this->db->table('cms_contact_history');
			$this->builder->insert($data_history);
		}
		return $data;
	}
    function get_contact_history_data($customer_id){
        $this->builder = $this->db->table('cpcrd_new');
		$this->builder->select('CM_CARD_NMBR AS card_number');
		$this->builder->where('CM_CUSTOMER_NMBR', $customer_id);
		$card_numbers = $this->builder->get()->getResultArray();

		$data = array();
		foreach ($card_numbers as $row) {
			$this->builder = $this->db->table('cms_contact_history a');
			$this->builder->select('a.*, a.action_code AS action_code_desc');
			$this->builder->where('a.account_no', $row['card_number']);
			$this->builder->orderBy('a.created_time', 'DESC');
			$this->builder->limit(10);
			$data[$row["card_number"]] = $this->builder->get()->getResultArray();
		}
        
		return $data;
        
    }
    function get_all_contracts_join($account_id)
	{
       $this->builder = $this->db->table('cpcrd_new');
	   $this->builder->select('*');
	   $this->builder->join('cms_account_last_status', 'cpcrd_new.CM_CARD_NMBR=cms_account_last_status.account_no');
	   $this->builder->where('cpcrd_new.CM_CARD_NMBR', $account_id);
	   $data = $this->builder->get()->getResultArray();
	   return $data;
	}

	function get_parameter_list_tree($data)
	{
        $param = $data['param'];
		if (empty($param)) {
			return;
		}
		switch ($param) {
			case "CLASS_ID":
                $this->builder = $this->db->table('cms_classification');
                $this->builder->select('classification_id as id,classification_name as description');
				//      $this->db->where("product_group", $this->input->get_post('segmentasi', true));
				break;
			case "CM_ZIP_REC":
			case "CIF_REGIONAL":
                $this->builder = $this->db->table('cms_zip_reg');
                $this->builder->select('zip_reg as id,zip_reg as description');
				$this->builder->where("zip_reg is not null");
				$this->builder->groupBy("zip_reg");
				break;
		}
		$list = "";
		$rResult = $this->builder->get()->getResultArray();
		//var_dump($rResult->result_array());
		foreach ($rResult as $aRow) {
			$list .= '<li>
								<div class="checkbox">
									<label>
										<input type="checkbox" class="ace" value="' . $aRow['id'] . '" id="' . $aRow['id'] . '" name="product-list[]" />
										<span class="lbl"> ' . $aRow['description'] . '</span>
									</label>
								</div>
							
							';

			$list .=  '</li>';
		}
		return $list;
	}
    function action_code_history_data_sp($data){
		if ($data['query_filter'] == "SURAT_PERINGATAN") {
			$this->builder = $this->db->table('cms_letter_due a');
			$select = array(
				'account_no',
				'date(a.tgl_terbit) created_time',
				'IF(a.type_sp = "SP1", "<span class=\"badge\">SP1</span>", 
				IF(a.type_sp = "SP2", "<span class=\"badge\">SP2</span>", 
				"<span class=\"badge\">SP3</span>")) AS jenis_sp',
				'a.no_sp no_surat',
				'a.id',
				'null tgl_terima',
				'dpd'
			);
			$this->builder->where('a.account_no', $data['card_no']);
			$this->builder->where('a.no_sp is not null');

		} else if($data['query_filter'] == "SURAT_PENARIKAN"){
			$this->builder = $this->db->table('reposses a');
			$select = array(
				'cm_card_nmbr account_no',
				'date(a.created_time) created_time',
				'"<span class=\"badge\">RAL</span>" AS jenis_sp',
				'a.no_ral no_surat',
				'a.id',
				'null tgl_terima',
				'dpd');
			$this->builder->where('a.CM_CARD_NMBR', $data['card_no']);
		}
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->orderBy('a.created_time', 'DESC');
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
    function call_result_history_data($data){
		if ($data['query_filter'] == "CWX") {
			$this->builder = $this->db->table('tmp_mig_visitresult_hist a');
			$this->builder->distinct();
			$select = array(
				'a.id,
				place_code,
				description,
				a.created_time,
				ifnull(b.name,a.user_id)created_by'
			);
			$this->builder->join('cc_user b',"a.user_id=b.id","left");
			$fin_acc = $this->Common_model->get_record_value("fin_account","cpcrd_new","CM_CARD_NMBR= '".$card_no."'");
			$this->builder->where("invoicenumber",$fin_acc);
		} else{
			$this->builder = $this->db->table('cms_contact_history a');
			$select = array(
					'a.id, 
					c.category_name as description, 
					concat(a.created_by," - ", b.name) as created_by, 
					a.phone_no, 
					d.category_name lov4, 
					e.category_name lov5,
					a.created_time, a.place_code'
				);
				$this->builder->join('cc_user b',"a.user_id=b.id","left");
				$this->builder->join('cms_lov_registration c',"a.lov3=c.id","left");
				$this->builder->join('cms_lov_registration d',"a.lov4=d.id","left");
				$this->builder->join('cms_lov_registration e',"a.lov5=e.id","left");
				$this->builder->where("a.account_no",$data['card_no']);
				$this->builder->where("a.input_source",$data['query_filter']);
		}
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->orderBy('a.created_time', 'DESC');
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
    function sms_history_data($card_no){
		$this->builder = $this->db->table('cc_sms_log a');
		$select = array(
				'id, 
				phone_no, 
				content,  
				status, 
				created_by, 
				created_time'
			);
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->where("a.account_no",$card_no);
        $this->builder->orderBy('a.created_time', 'DESC');
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
    function email_history_data($card_no){
		$this->builder = $this->db->table('cc_email_log a');
		$select = array(
				'id, 
				to_address, 
				subject, 
				message as content, 
				created_by, 
				sent_time');
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->where("a.card_no", $card_no);
		$this->builder->where("a.sent_time < now()");
        $this->builder->orderBy('a.sent_time', 'DESC');
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
    function br_history_data($card_no){
		$this->builder = $this->db->table('cms_contact_history a');
		$select = array(
				'id, 
				notes, 
				created_time'
			);
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->where("a.account_no", $card_no);
		$this->builder->where("a.input_source", "BISNIS_RULE");
        $this->builder->orderBy('a.id', 'DESC');
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
    function payment_history_data($card_no){
		$this->builder = $this->db->table('cms_payment_history_new a');
		$select = array(
				'UUID() AS PayID',
				'a.CM_CARD_NMBR AS FIN_ACCT',
				'concat(a.TRANS_CODE," - ",c.description) TC',
				'a.CM_DTE_LST_PYMT AS PROC_DT',
				'a.CM_LST_PYMT_AMNT AS PAYMENT',
				'SEQNO AS AGENT_ID',
				'date(input_time)input_time',
				'a.description'
			);
		$fin_acc = $this->Common_model->get_record_value("fin_account","cpcrd_new","CM_CARD_NMBR= '".$card_no."'");
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->join('cms_reference c',"a.TRANS_CODE=c.value and c.reference ='PAYMENT_CODE'","left");
		$this->builder->where("CM_CARD_NMBR", $fin_acc);
        $this->builder->orderBy('PROC_DT', 'ASC');
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
    function note_history_data($card_no){
		$this->builder = $this->db->table('cms_hot_note_history a');
		$select = array(
				'a.agent_id',
				'a.created_date_time',
				'a.hot_note');
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->where("account_no", $card_no);
		$this->builder->where("hot_note != '' ");
        $this->builder->orderBy('created_date_time', 'DESC');
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
    function ptp_history_list($fin_account){
		$this->builder = $this->db->table('tmp_import_ptp ptp');
		$select = array(
			'his.id','CR_NAME_1',
			'date_format(his.created_time,"%d-%b-%Y %T")created_time',
			'customer_no','account_no','user_id','notes','ptp_amount',			
			'date_format(ptp_date,"%d-%b-%Y")ptp_date','ptp_status, cara_bayar,ptp.status'
		);
		$select = array(
			'date_format(ptp.DateTaken,"%d-%b-%Y %T") created_time',
			'ifnull(b.name,ptp.EmployeeID) as user_id'/*,'CM_CARD_NMBR as account_no',
			'CR_NAME_1'*/,
			'date_format(ptp.DatePromised,"%d-%b-%Y")as ptp_date',
			'ptp.AmountPromised as ptp_amount',
			'AmountPaid',
			'ptp.invoiceNumber',
			'c.description'
		);
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->join('cms_reference c', 'ptp.status = c.value and c.reference = "PTP_STATUS"');
		$this->builder->join('cc_user b',"ptp.EmployeeID=b.id","left");
		$this->builder->where("ptp.invoiceNumber",$fin_account);
        $this->builder->orderBy('ptp.DatePromised', 'DESC');
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
	function get_payment_schedule_data($card_no){
		$this->builder = $this->db->table('cms_payment_schedule a');
		$select = array(
				'format(billing,0) billing',
				'due_date',
				'cycle',
				'installment_no'
			);
		$this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->where("account_no", $card_no);
		$this->builder->orderBy('due_date', 'DESC');
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
	function send_message($data){

		$uuid = uuid();
		$data_customer = $this->Common_model->get_record_values("*","cpcrd_new","CM_CARD_NMBR='".$data["account_no"]."'");
		switch($data["type"]){
			case "sms":
				$ref_number = "ONBRD".date('Ymdhis');	
				$sms_gtw_url = env('sms_gtw_url');
				$url = $sms_gtw_url . "&nohandphone=62".ltrim($data["to"],"0")."&sms=".rawurlencode($data["message"])."&ref_transaction=".$ref_number;
				$curlHandle = curl_init();
				curl_setopt($curlHandle, CURLOPT_URL, $url) ;
				curl_setopt($curlHandle, CURLOPT_HEADER, 0);
				curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
				curl_setopt($curlHandle, CURLOPT_POST, 0);
				// $results = curl_exec($curlHandle);
				// //var_dump($results);
				// curl_close($curlHandle);
				$log = array(
					'id'			=> uuid(),
					'phone_no'		=> $data["to"],//$this->input->get_post('phone_no'),
					'content' 		=> $data["message"],
					'created_by'	=> session()->get('USER_ID'),
					'created_time' 	=> date("Y-m-d H:i:s"),
					'account_no'	=> $data['account_no'],
					'template_id'	=> $data['template_id'],

					'status' 		=> "Q"
				);
				$this->builder = $this->db->table('cc_sms_log');
				$this->builder->insert($log);
				
			break;
			case "email":
				$NOW		 = date('Y-m-d H:i:s');
				$datalog = array(
					'id'			=> $uuid,
					'card_no'		=> $data['account_no'],
					'message' 		=> $data["message"],
					'sent_status' 	=> "Q",
					'sent_retry' 	=> 0,
					'to_address' 	=> $data["to"],
					'nama_debitur'	=> $data_customer['CR_NAME_1'],
					'template_id'	=> $data['template_id'],
					'created_by'	=> session()->get('USER_ID'),
					'created_time' 	=> $NOW,
					'source' 		=> 'AGENT',
					'select_time' 	=> $NOW,
					'subject'		=> $this->Common_model->get_record_value("template_name", "cms_email_sms_template", "template_id = '" . $data["template_id"]. "'")
					);
				$this->builder = $this->db->table('cc_email_log');
				$send = $this->builder->insert( $datalog);
			break;
			case "wa":
				$CURTIME	 = date('H:i');
				$NOW		 = date('Y-m-d H:i:s');
				$this->builder = $this->db->table('cms_wa_template');
				$this->builder->select("*");
				$this->builder->where('template_id', $data["template_id"]);
				$template = $this->builder->get()->getResultArray();
				
				foreach ($template as $key1 => $value1) {
					$this->builder = $this->db->table('cpcrd_new a');
					$this->builder->select("
						UUID() as id, 
						cm_card_nmbr as card_no, 
						'{$data["to"]}' AS phone_no,
						'' as template_data,
						'{$value1['template_replace']}' as message,
						NULL response, 
						'Q' sent_status , 
						'{$NOW}' as sent_time,
						0 as sent_retry,
						'SYSTEM' as created_by, 
						'{$NOW}' created_time , 
						'{$value1['template_id']}' as template_id, 
						cr_name_1 as nama_debitur,
						'{$CURTIME}' as select_time,
						'AGENT' source, 
						'NULL' as message_id
					");
					$this->builder->join('cms_account_last_status b', 'a.CM_CARD_NMBR=b.account_no');
					$this->where('a.cm_card_nmbr', $data["account_no"]);
					$results = $this->builder->get()->getResultArray();

					$this->builder = $this->db->table('cc_wa_log');
					foreach ($results as $row) {
						$this->builder->insert($row);
					}


				// 	$sql = "INSERT INTO cc_wa_log 
				// 			SELECT UUID() , 
				// 					cm_card_nmbr card_no , 
				// 					? phone_no , 
				// 					NULL template_data , 
				// 					? mesasage , 
				// 					NULL response , 
				// 					'Q' sent_status , 
				// 					? sent_time , 
				// 					0 , 
				// 					'SYSTEM' created_by , 
				// 					? created_time  , 
				// 					? template_id , 
				// 					cr_name_1 , 
				// 					? select_time,
				// 					'AGENT' source, 
				// 					NULL 
				// 			FROM cpcrd_new a
				// 			JOIN cms_account_last_status b ON a.CM_CARD_NMBR=b.account_no
				// 			WHERE a.cm_card_nmbr = ?";
						
				// 	#SEMENTARA DI MATIKAN , JIKA DIPERLUKAN TINGGAL DI BUKA
				// 	$this->db->query($sql,array(
				// 		$data["to"],
				// 		$value1['template_replace'] ,
				// 		$NOW,
				// 		$NOW ,
				// 		$value1['template_id'] ,
				// 		$CURTIME ,
				// 		$data["account_no"]
				// 	));
				}

				$this->builder = $this->db->table("cc_wa_log a");
				$this->builder->select('a.*, b.template_name, b.parameter, b.max_retry');
				$this->builder->join('cms_wa_template b', 'a.template_id = b.template_id');
				$this->builder->whereIn('a.sent_status', ["Q"]); // Perbaiki penggunaan whereIn() disini
				$this->builder->where('DATE(a.created_time)', date('Y-m-d')); // Ubah date(a.created_time) dengan DATE(a.created_time) dan date('Y-m-d') untuk mengambil tanggal hari ini
				$this->builder->where('a.phone_no IS NOT NULL AND a.phone_no != ', ''); // Perbaiki penggunaan where() disini
				$rResult = $this->builder->get()->getResultArray();
		        
				$params=array();
				$params1=array();
				$params_arr=array();
				foreach($rResult as $aRow){

					//conversi 08 ke 628
					$position = strpos($aRow['phone_no'], "08", 0);
			        if ($position === 0)
			        {
			        	$aRow['phone_no']   = "628". ltrim($aRow['phone_no'], "08");   
			        }
			        $this->builder = $this->db->table('cc_wa_log');
			        $this->builder->where('id', $aRow["id"]);
		        	$this->builder->set('phone_no', $aRow['phone_no']);
		        	$this->builder->update();

					// var_dump(array($aRow['max_retry'],$aRow['sent_retry']));die;
					$parameter = explode('|',$aRow['parameter']);
					$params_arr=[];
					foreach ($parameter as $row) {
						$params['type']='text';
						$params['text']=$row;
						array_push($params1,$params);
						array_push($params_arr,$row);
						
					}
					// var_dump(json_encode($params1));die;
					$aRow['parameter'] = json_encode($params1);
					$aRow['parameter_arr'] = json_encode($params_arr);
					$html='';
					//var_dump($aRow['parameter_arr']);
		            $html = $this->replaceValue($aRow,'wa');
					//var_dump($html);die;

					if($aRow['sent_retry']<$aRow['max_retry']){
						$this->create_wa_pending($aRow, $html);
					}else{
						echo "\nudah maximal bro id = ".$aRow['id'];
					}
		           
		        }	
			break;

		}
		// var_dump($data);
		$data_contact = array(
			'id'			=> $uuid,
			'account_no' 	=> $data['account_no'],
			'customer_no' 	=> $data_customer["CM_CUSTOMER_NMBR"],
			'fin_acc' 	=> $data_customer["fin_account"],
			'user_id'		=> session()->get('USER_ID'),
			'input_source'	=> $data['type'],
			'action_code'	=> $data['type'],
			'phone_no'		=> $data['to'],
			'notes' 		=> $data['message'],
			'created_by'	=> session()->get('USER_ID'),
			'created_time' 	=> date("Y-m-d H:i:s")
		);
		$this->builder = $this->db->table('cms_contact_history');
		$send = $this->builder->insert($data_contact);

		return $send;
	}
	function insert_contact_result($call_result_data, $hot_note,$status_agency){
		$this->builder = $this->db->table("cms_contact_history");
		$result = $this->builder->insert($call_result_data);
		// echo "status_agency = ".$status_agency;
		// echo $this->db->last_query();
		
		//insert hot note history
		$is_exist = $this->Common_model->get_record_value("count(*) tot", "cms_hot_note_history", "account_no='". $call_result_data["account_no"]."' and hot_note ='".str_replace("'","`",$hot_note)."'", "");
		if($is_exist == 0){
			$this->builder = $this->db->table("cms_hot_note_history");
			$hot_note_data = array(
				'account_no' => $call_result_data["account_no"],
				'created_date_time' => date('Y-m-d H:i:s'),
				'agent_id' =>$call_result_data["user_id"],
				'hot_note' => $hot_note
			);
			$this->builder->replace($hot_note_data);
		}
		$parameters_data = array(
			'ptp_date' => $call_result_data["ptp_date"],
			'ptp_amount' => $call_result_data["ptp_amount"],
			'last_contact_time' => $call_result_data["created_time"],
			'last_response'			=> $call_result_data["action_code"],
			'last_notes'			=> $call_result_data["notes"],
			'hot_notes'			=> $hot_note,
			'approval_status' => "PENDING",
			'CF_AGENCY_STATUS_ID' => $status_agency,

			'contact_history_id'			=> $call_result_data["id"],
		);

		$this->builder = $this->db->table("acs_customer_appointment");
		$this->builder->set("status", '1');
		$this->builder->set("last_update_datetime", date('Y-m-d H:i:s')); // Menggunakan date() untuk mendapatkan tanggal dan waktu saat ini dalam format yang sesuai
		$this->builder->where("contract_number", $call_result_data["account_no"]);
		$this->builder->where("appointment_datetime <=", date('Y-m-d H:i:s')); // Menggunakan date() untuk mendapatkan tanggal dan waktu saat ini dalam format yang sesuai
		$this->builder->update();

		// $sql = "UPDATE acs_customer_appointment
		// 		SET 
		// 			status = 1,
		// 			last_update_datetime = now()
		// 		WHERE contract_number =  ? and appointment_datetime <= now() ";
		// $this->db->query($sql,array( $call_result_data["account_no"]));


		if ( $call_result_data["ptp_date"]&&$call_result_data["ptp_amount"]&&$call_result_data["cara_bayar"]) {
			$parameters_data['CF_AGENCY_STATUS_ID'] = '9';
			$ptp_data = array('invoicenumber' => $call_result_data["fin_acc"],
								'EmployeeID' => session()->get('USER_ID'),
								'AmountPromised' => $call_result_data["ptp_amount"],
								'DatePromised' => $call_result_data["ptp_date"],
								'Status' =>'0',
								'DateTaken' => date('Y-m-d H:i:s'),
								'PromiseID' => $call_result_data["id"]
								);
			$this->builder = $this->db->table("tmp_import_ptp");
			$result = $this->builder->insert($ptp_data );		
					

		}
		$this->builder = $this->db->table("cms_account_last_status");
		$this->builder->where('account_no', $call_result_data["account_no"]);				
		
		$is_exist = $this->Common_model->get_record_value("count(*) tot", "cms_account_last_status", "account_no='". $call_result_data["account_no"]."'", "");
		
		if($is_exist > 0){
			
			$return = $this->builder->update($parameters_data);
		
		}else{
			$parameters_data = array(
					'account_no' 			=> $call_result_data["account_no"],
					'flag_tmp' 				=> '0',
					'ptp_date' => $call_result_data["ptp_date"],
					
					'ptp_amount' => $call_result_data["ptp_amount"],
					'last_contact_time' => $call_result_data["created_time"],
					// 'last_response'			=> $call_result_data["action_code"],
					'last_notes'			=> $call_result_data["notes"],
					'hot_notes'			=> $hot_note,
					'contact_history_id'			=> $call_result_data["id"],
					'CF_AGENCY_STATUS_ID' => $status_agency,
			
			);
			
			/*if($call_result_data["action_code"] == 'PTP'){
				$parameters_data['CF_AGENCY_STATUS_ID'] = '9';
			}*/

			if ( $call_result_data["ptp_date"]&&$call_result_data["ptp_amount"]&&$call_result_data["cara_bayar"]) {
				$parameters_data['CF_AGENCY_STATUS_ID'] = '9';
			}
			// print_r($parameters_data);
			$return = $this->builder->insert($parameters_data); 
		}

		if ($call_result_data["phone_type"]=='Other') {
			$updated = $this->Common_model->get_record_value("id", "cms_customer_data_update", "customer_id='".$call_result_data["customer_no"]."' ORDER BY created_time DESC LIMIT 1");

			$data = [
				'customer_id'   => $call_result_data["customer_no"],
				'other_phone'   => $call_result_data["phone_no"],
				'created_by'    => session()->get('USER_ID'),
				'created_time'  => date('Y-m-d H:i:s')
			];

			$this->builder = $this->db->table("cms_customer_data_update");

			if ($updated) {
				// Gunakan replace() untuk mengganti data jika sudah ada record dengan ID yang sama
				$this->builder->replace([
					'id' => $updated,
					'customer_id' => $data['customer_id'],
					'other_phone' => $data['other_phone'],
					'created_by' => $data['created_by'],
					'created_time' => $data['created_time']
				]);
			} else {
				// Gunakan insert() untuk memasukkan data baru jika tidak ada record dengan ID yang sama
				$data['id'] = uuid(); // Pastikan Anda memiliki fungsi uuid() yang menghasilkan UUID baru
				$this->builder->insert($data);
			}

		}
		
		return $result;
	}
}