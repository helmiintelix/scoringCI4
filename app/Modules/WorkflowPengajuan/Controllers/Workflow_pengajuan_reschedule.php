<?php 
namespace App\Modules\WorkflowPengajuan\Controllers;
use App\Modules\WorkflowPengajuan\Models\Workflow_pengajuan_reschedule_model;
use App\Modules\WorkflowPengajuan\Models\Payment_plan_model;
use App\Modules\WorkflowPengajuan\Models\Anuitas_model;
use CodeIgniter\Cookie\Cookie;


class Workflow_pengajuan_reschedule extends \App\Controllers\BaseController
{

	protected $security;
	function __construct()
	{
		$this->Payment_plan_model = new Payment_plan_model();
		$this->Anuitas_model = new Anuitas_model();
		$this->Workflow_pengajuan_reschedule_model = new Workflow_pengajuan_reschedule_model();
		$this->security = \Config\Services::security();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		$data['status'] = $this->input->getGet('status');
		return view('\App\Modules\WorkflowPengajuan\Views\Workflow_pengajuan_reschedule_list_view', $data);
	}
	function workflow_pengajuan_reschedule_list(){
		$statusOld = "";
		$data['status'] = $this->input->getGet('status');
		$cache = session()->get('USER_ID').'_workflow_pengajuan_reschedule_list';
		if($data['status'] != $statusOld){
			$this->cache->delete($cache);
			
			$data = $this->Workflow_pengajuan_reschedule_model->workflow_pengajuan_reschedule_list($data['status']);
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function requestData(){
		return view('\App\Modules\WorkflowPengajuan\Views\Search_account_reschedule_view');
	}
	function search_account(){
		$param = $this->input->getGet('keyword');

		$check = $this->checking_agreement($param);
	

		if($check['success']==false){
			$data = array("success" => FALSE, "message" => $check['message'] , 'data'=>null);
			return $this->response->setStatusCode(200)->setJSON($data);
		}else{
			$builder = $this->db->table("cpcrd_new");
			$builder->select('CM_CARD_NMBR, CM_CUSTOMER_NMBR, trim(CR_NAME_1) as nama, CM_TYPE');
			$builder->like('CM_CARD_NMBR', $param);
			$builder->orLike('CR_NAME_1', $param);
			$result = $builder->get();
			
			if($result->getNumRows()>0){
				$datacs = $result->getResultArray();
				$data = array("success" => TRUE, "message" => "DATA CUSTOMER FOUNDED" , 'data'=>$datacs);
			}else{
				$datacs = 'NOT FOUND';
				$data = array("success" => FALSE, "message" => "DATA CUSTOMER NOT FOUND" , 'data'=>$datacs);
			}

			return $this->response->setStatusCode(200)->setJSON($data);
		}

	}
	function checking_agreement($agreement){
		
		$jumlah = $this->Common_model->get_record_value('count(*) jumlah','cms_pengajuan_diskon',' cm_card_nmbr="'.$agreement.'"  and status in ("DRAFT","VERIFICATION","CHECKER","DEVIATION_APPROVAL","APPROVAL","DEVIATION_APPROVE","DEVIATION_REJECT") ');
		if($jumlah>0){
			$response = array("success" => false, "message" => $agreement." already join diskon pelunasan" );
		}else{
			$jumlah = $this->Common_model->get_record_value('count(*) jumlah','cms_pengajuan_restructure',' cm_card_nmbr="'.$agreement.'" and status in ("DRAFT","VERIFICATION","CHECKER","DEVIATION_APPROVAL","APPROVAL","DEVIATION_APPROVE","DEVIATION_REJECT") ');
			if($jumlah>0){
				$response = array("success" => false, "message" => $agreement." already join restructure/reschedule" );
			}else{
				$response = array("success" => true, "message" => $agreement." ready to join program" );
			}
		}

		return $response;
	}
	function pengajuan_program_form() {

		$id =  $this->input->getGet('id');
		$data['card_no'] =  $this->input->getGet('account_id');
		$data['agent_id'] =  $this->input->getGet('agent_id');
		$data['source'] =  $this->input->getGet('source');
		$tipe_pengajuan =  $this->input->getGet('typ');
		$screen_level =  $this->input->getGet('screen_level');
		if($screen_level=='EDIT'){
			$data["screen_level"] = $screen_level;

		}
		else if($screen_level=='ASSIGNED'){
			$data["screen_level"] = $screen_level;

		}
		else{
			$data["screen_level"] = "NEW";
		}
		
		if(session()->get('USER_ID')==""){
			$agent_id = $data['agent_id'] ;
		}else{
			$agent_id = session()->get('USER_ID');
		}

		$jumlah	= $this->Common_model->get_record_value("count(*)", "cpcrd_new", "CM_CARD_NMBR='".$data['card_no']."'");
		if($jumlah==0){
			echo "NO. PINJAMAN NOT FOUND";
			return false;
		}
		// $jumlah	= $this->Common_model->get_record_value("count(*)", "cms_workflow_view", "cm_card_nmbr='".$data['card_no']."'  and status in ('DRAFT','VERIFICATION','CHECKER','DEVIATION_APPROVAL','APPROVAL','DEVIATION_APPROVE','APPROVE')");
		// if($jumlah>0){
		// 	echo "NO. PINJAMAN SEDANG PENGAJUAN PROGRAM";
		// 	return false;
		// }
		
		$data["discount_data"]["jenis_pengajuan"] = $this->input->getGet('jenis_pengajuan'); 
		
		// $data["customer_data"]	= $this->Common_model->get_record_values("*", "cms_account_last_status", "account_no='".$data['card_no']."'");
		
		$data["customer_data"]	= $this->Common_model->get_record_values(
			"CR_NAME_1 as name,
			CM_CARD_NMBR as cm_card_nmbr,
			CR_HANDPHONE as handphone,
			CR_HOME_PHONE as home_phone,
			'' as home_phone_2,
			CR_GUARANTOR_PHONE as mother_fix_phone,
			CR_GUARANTOR_PHONE as mother_handphone,
			'' as investigation_phone,
			CM_SPOUSE_PHONE as spouse_phone,
			CR_OFFICE_PHONE as office_phone", 
			"cpcrd_new", "CM_CARD_NMBR='".$data['card_no']."'");
		$data["credit_data"]	= $this->Common_model->get_record_values("*", "cpcrd_new", "CM_CARD_NMBR='".$data['card_no']."'");

		$monthly_interest = 0;
		$penalty_ongoinginstallment = 0;

		$data['deviation_reason'] = array(''=>'[select data]') + $this->Common_model->get_ref_master('deviation_id value , deviation_name item', 'cms_deviation_reference', 'is_active="1" and type like "%RESCHEDULE%" ', 'deviation_name asc');
		$data['OngoingInstallmentList']['total'] = 0; //HARCODE 0 KARENA INI SEBENARNYA UNTUK CC
	
		$data['monthly_interest'] = $monthly_interest;
		$data['penalty_ongoinginstallment'] = $penalty_ongoinginstallment;

		$data['kondisi_khusus_list'] = $this->Common_model->get_ref_master('value , CONCAT( value , " - " , description) item', 'cms_reference', 'reference="KONDISI_KHUSUS_CC_RSCH" and status="1" ', 'order_num asc');

		$data['grace_period'] = $this->get_tanggal_ptp_grace($data["credit_data"]['CM_BUCKET']);
		$data['account'] = $this->Common_model->get_record_values('*', 'cpcrd_new', 'CM_CARD_NMBR = "' . $data['card_no'] . '"');
		$data["pengajuan_data"] = $this->Common_model->get_record_values("*", "cms_pengajuan_reschedule ", "id='".$id."'");
		// $data['TL'] =  $this->Common_model->get_record_value("team_leader", "cms_team", " agent_list LIKE '%".  $agent_id ."%' ORDER BY created_time desc limit 1");
		// $data['TL'] =  $this->Common_model->get_record_value("name", "cc_user", " id = '".$data['TL']."'");

		// $data['SPV'] =  $this->Common_model->get_record_value("supervisor", "cms_team", " agent_list LIKE '%".  $agent_id ."%' ORDER BY created_time desc limit 1");
		// $data['SPV'] =  $this->Common_model->get_record_value("name", "cc_user", " id = '".$data['TL']."'");
		
		$builder = $this->db->table("cms_setup_upload_document");
		$builder->select("id , nama_document , is_mandatory");
		$builder->where("is_active", "Y");
		$builder->groupBy("nama_document", "asc");
		$data['master_document'] = $builder->get()->getResultArray();
		$data["contact_result"] = array("" => "[select contact result]") + $this->Common_model->get_record_list("value, description AS item", "cms_reference", "flag_tmp = '1' AND flag = '1' AND reference = 'CONTACT_RESULT_VERIFICATION'", "order_num");
		$data["result_call"] = array("" => "[select call result]") + $this->Common_model->get_record_list("value, description AS item", "cms_reference", "flag_tmp = '1' AND flag = '1' AND reference = 'RESULT_CALL_VERIFICATION'", "order_num");
		$data['call_history_data'] = $this->Common_model->get_record_values("*", "cms_call_verification", "id_pengajuan ='".$id."'");
		
		// print_r($data);
		// exit();
		// dd($data);
		return view('\App\Modules\WorkflowPengajuan\Views\Detail_contract_request_program_reschedule_view', $data)
		.view('\App\Modules\WorkflowPengajuan\Views\Request_pengajuan_reschedule_view', $data);

	}
	function get_tanggal_ptp_grace($bucket){
		$ptp_grace =  $this->Common_model->get_record_value("ptp_grace_period", "cms_bucket", "  bucket_id = '".$bucket ."' ");
		$join_program =  $this->Common_model->get_record_value("join_program", "cms_bucket", "  bucket_id = '".$bucket ."' ");
		// if($join_program=='1'){
		if($join_program=='0'){
			if($ptp_grace==''){
				$ptp_grace = 0;
			}
	
			$sql =  "SELECT curdate() + interval ".$ptp_grace." day as tanggal ";
			$tanggal = $this->db->query($sql)->getRowArray()['tanggal'];
	
			$sql =  "SELECT count(*) jumlah from cc_holiday where holiday_date between curdate() and date('".$tanggal."') ";
			//jumlah holiday antara hari ini dan dari ini + grace periode
			$jumlah = $this->db->query($sql)->getRowArray()['jumlah'];
	
			$ptp_grace = $ptp_grace + $jumlah;
	
			$sql =  "SELECT MONTH(curdate() + interval ".$ptp_grace." day) as bulan ";
			$bulan_ptp = (int) $this->db->query($sql)->getRowArray()['bulan'];
	
			$sql =  "SELECT MONTH(curdate()) as bulan ";
			$bulan_sekarang = (int) $this->db->query($sql)->getRowArray()['bulan'];
	
			
			$interval = 0;
			//bulan grace period tidak lebih bulan
			if($bulan_ptp === $bulan_sekarang){
				
				$interval = $ptp_grace;
			}else if($bulan_ptp > $bulan_sekarang){
				//bulan grace period lebih bulan
				$sql = "SELECT DAY(last_day(curdate()))-DAY(curdate()) grace_period"; //tanggal akhir bulan di kurangi tanggal hari ini
				$grace_period = $this->db->query($sql)->getRowArray()['grace_period'];
				
	
				$interval = $grace_period;
			}
	
			$sql =  "SELECT curdate() + interval ".$interval." day as tanggal ";
			$tanggal =  $this->db->query($sql)->getRowArray()['tanggal'];
			return $tanggal;
		}else{
			$sql = "SELECT LAST_DAY(curdate()) tanggal";
			$tanggal = $this->db->query($sql)->getRowArray()['tanggal'];
			return $tanggal;
		}
		
	}
	function get_data_kotak_merah(){
		$ptp_date = $this->input->getGet('ptp_date');
		$due_date = $this->input->getGet('due_date');
		$product_id = $this->input->getGet('product_id');
		$agreement_no = $this->input->getGet('agreement_no');
		$tipe_pengajuan = $this->input->getGet('tipe_pengajuan');
		
		
		if($ptp_date==""){
			$msg = "PTP is emtpy";
			$data	= array("success" => false, "message" => $msg , 'data'=> NULL);
			$this->Common_model->data_logging('WORKFLOW_PENGAJUAN', 'get_data_kotak_merah','FAILED',NULL );
			return $this->response->setStatusCode(200)->setJSON($data);
		}else if($due_date==""){
			$msg = "due date is emtpy";
			$data	= array("success" => false, "message" => $msg , 'data'=> NULL);
			$this->Common_model->data_logging('WORKFLOW_PENGAJUAN', 'get_data_kotak_merah','FAILED',NULL );
			return $this->response->setStatusCode(200)->setJSON($data);
		}else if($product_id==""){
			$msg = "product_id is emtpy";
			$data	= array("success" => false, "message" => $msg , 'data'=> NULL);
			$this->Common_model->data_logging('WORKFLOW_PENGAJUAN', 'get_data_kotak_merah','FAILED',NULL );
			return $this->response->setStatusCode(200)->setJSON($data);
		}else if($agreement_no==""){
			$msg = "no pengajuan is emtpy";
			$data	= array("success" => false, "message" => $msg , 'data'=> NULL);
			$this->Common_model->data_logging('WORKFLOW_PENGAJUAN', 'get_data_kotak_merah','FAILED',NULL );
			return $this->response->setStatusCode(200)->setJSON($data);
		}else if($tipe_pengajuan==""){
			$msg = "tipe_pengajuan is emtpy";
			$data	= array("success" => false, "message" => $msg , 'data'=> NULL);
			$this->Common_model->data_logging('WORKFLOW_PENGAJUAN', 'get_data_kotak_merah','FAILED',NULL );
			return $this->response->setStatusCode(200)->setJSON($data);
		}
		
		$msg = "success";

		$msg_principal_amount_balance='';
		$msg_total_due_balance_installemnt = '';
		$msg_late_charge = '';
		$msg_interest_due = '';
		$due_date_terakhir = '';
		$due_date_berjalan = '';

		$principal_amount_balance = 0;
		$total_due_balance_installemnt = 0 ;
		$interest_due = 0;
		$late_charge = 0;
		$stamp_duty = 0;
		$penalty = 0;
		$total = 0;

			
			
		// principal_amount_balance
		$principal_amount_balance= (int) $this->Common_model->get_record_value("CM_OS_PRINCIPLE", "cpcrd_new", " CM_CARD_NMBR ='". $agreement_no ."'");
		if($principal_amount_balance==null)$principal_amount_balance = 0;
		if($principal_amount_balance=='')$principal_amount_balance = 0;

		// total_due_balance_installemnt
		$total_due_balance_installemnt = (int) $this->Common_model->get_record_value("CM_OS_BALANCE", "cpcrd_new", " CM_CARD_NMBR ='". $agreement_no ."'");
		if($total_due_balance_installemnt==null)$total_due_balance_installemnt = 0;
		if($total_due_balance_installemnt=='')$total_due_balance_installemnt = 0;

		// interest_due
		$interest_due = $this->Common_model->get_record_value('CM_TOT_INTEREST','cpcrd_new','CM_CARD_NMBR = "'.$agreement_no.'" ');
		if($interest_due==null)$interest_due = 0;
		if($interest_due=='')$interest_due = 0;
		
		// late_charge
		$late_charge =  $this->Common_model->get_record_value('CM_LST_PYMT_AMNT','cpcrd_new','CM_CARD_NMBR = "'.$agreement_no.'" ');
		if($late_charge==null)$late_charge = 0;
		if($late_charge=='')$late_charge = 0;
		
		// stamp_duty
		$stamp_duty = 0;

		
		// penalty
		$penalty =  $penalty = (7/100)*$principal_amount_balance;

		$total = $principal_amount_balance +
					$total_due_balance_installemnt +
					$interest_due +
					$late_charge +
					$penalty+
					$stamp_duty ;
		

		$arr_data = array('principal_amount_balance' 		=> $principal_amount_balance  ,
						'total_due_balance_installemnt'		=> $total_due_balance_installemnt,
						'interest_due'						=>$interest_due ,
						'late_charge'						=>$late_charge,
						'stamp_duty'						=> $stamp_duty ,
						'penalty'							=> $penalty ,
						'total'								=> $total ,
						'msg_principal_amount_balance' 		=> $msg_principal_amount_balance,
						'msg_total_due_balance_installemnt' => $msg_total_due_balance_installemnt,
						'msg_late_charge' 					=> $msg_late_charge,
						'msg_interest_due' 					=> $msg_interest_due,
						'agreement_no'						=>$agreement_no ,
						'PTP'								=>$ptp_date,
						'due_date'							=>$due_date,
						'due_date_berjalan'					=>$due_date_berjalan,
						'due_date_terakhir'					=>$due_date_terakhir,
						'product_id'						=> 	$product_id
		);

		$data	= array("success" => true, "message" => $msg , 'data'=> $arr_data);
	
		$this->Common_model->data_logging('WORKFLOW_PENGAJUAN', 'get_data_kotak_merah','SUCCESS',	json_encode($data) );

		return $this->response->setStatusCode(200)->setJSON($data);


	}
	function cek_config_rest(){
		$id_pengajuan 			= $this->security->sanitizeFilename($this->input->getGet('id_pengajuan'));
		$agreement_no 			= $this->security->sanitizeFilename($this->input->getGet('agreement_no'));
		$product_id 			= $this->security->sanitizeFilename($this->input->getGet('product_id'));
		$block_code 			= $this->security->sanitizeFilename($this->input->getGet('block_code'));
		$request_type 			= $this->security->sanitizeFilename($this->input->getGet('request_type'));
		$bucket 				= $this->security->sanitizeFilename($this->input->getGet('bucket'));
		$flag_kondisi_khusus = $this->input->getGet('flag_kondisi_khusus');
		$flag_kondisi_khusus = $flag_kondisi_khusus ? $this->security->sanitizeFilename($flag_kondisi_khusus) : '0';
		$desc_kondisi_khusus = $this->input->getGet('desc_kondisi_khusus');
		$desc_kondisi_khusus = $desc_kondisi_khusus ? $this->security->sanitizeFilename($desc_kondisi_khusus) : '0';

		
		$diskon_parameter = 'NOT_FOUND';
		$data_diskon_parameter = 'NOT_FOUND';
		$data_diskon = 'NOT_FOUND';
		$message = '';

		$cek_parameter_tmp = $this->Common_model->get_record_value('id_pengajuan','cms_reschedule_parameter_tmp','id_pengajuan="'.$id_pengajuan .'"');
		if($cek_parameter_tmp != ''){
			
			$diskon_parameter = $this->Common_model->get_record_value('restructure_parameter_id','cms_reschedule_parameter_tmp','id_pengajuan="'.$id_pengajuan .'"');
			$builder = $this->db->table("cms_reschedule_parameter_tmp");
			$builder->select("*");
			$builder->where("id_pengajuan", $id_pengajuan);
			$data_diskon = $builder->get()->getRowArray();

			// $sql = "select * from cms_restructure_parameter_tmp where id_pengajuan = '".$id_pengajuan."' ";
			// $data_diskon = $this->db->query($sql)->result_array()[0];
			
			$data	= array("success" => true, "message" => "Success" , "reschedule_parameter"=> $diskon_parameter , 'data'=>$data_diskon ,'alert'=>$message);
			
			return $this->response->setStatusCode(200)->setJSON($data);
		}
		


		$data	= array("success" => false, "message" => "failed" , "reschedule_parameter"=> 'NOT_FOUND' , 'data'=>'NOT FOUND', 'alert'=> 'NOT FOUND');
		
		//diviasi
		// if($flag_kondisi_khusus=='' ){ 
		// 	$data	= array("success" => true, "message" => "DEVIASI" , "diskon_parameter"=> 'DEVIASI' , 'data'=>'DEVIASI', 'alert'=> 'DEVIASI');
		// 	return $this->response->setStatusCode(200)->setJSON($data);
		// }
		
		//non diviasi
		// if($flag_kondisi_khusus == 0 || $flag_kondisi_khusus == '0'){ //block code
			$builder = $this->db->table("cms_reschedule_parameter");
			$builder->select("*");
			// $builder->where("hirarki", "2");
			$builder->where("is_active", "1");
			// $builder->where("restructure_tipe", $request_type);
			$builder->where("bucket_list IS NOT NULL");
			$builder->orderBy("updated_time", "desc");
			$result = $builder->get()->getResultArray();
			// print_r($this->db->getLastQuery());
			// exit();
			// $sql = "select * from cms_restructure_parameter where hirarki='2' and is_active='1' and restructure_tipe = ? and  bucket_list != 'null' order by updated_time desc ";
			// $result = $this->db->query($sql,array($request_type))->result_array();
			if(count($result) >0 ){
				foreach ($result as $key => $value) {
					$bucket_where = '';
					if($value['bucket_list']!=''){
						$bucket_where = $this->setParamBucket($value['bucket_list']);
					}
					// print_r($bucket_where);
					// exit();
					$builder = $this->db->table("cpcrd_new");
					$builder->selectCount('*', 'jml');
					$builder->where('CM_CARD_NMBR', $agreement_no);
					$builder->where($value['parameter_detail']);
					$builder->whereIn("CM_BUCKET", [$bucket_where]);
					$query = $builder->get();
					$result = $query->getRowArray();
					$jumlah = $result['jml'];
					
					if($jumlah>0){
						$diskon_parameter = $value['parameter_id'];
						$diskon_parameter_name = $value['parameter_name'];
						$builder = $this->db->table('cms_reschedule_parameter');
						$builder->select('*');
						$builder->where('parameter_id', $diskon_parameter);
						$builder->where('is_active', '1');
						$query = $builder->get();
						$data_diskon = $query->getRowArray();
						
						break;  

					}else{
						$message = '* parameter NORMAL untuk  '.$agreement_no.' tidak ditemukan ';
					}
				}
				$data	= array("success" => true, "message" => "Success" , "reschedule_parameter"=> $diskon_parameter , 'data'=>$data_diskon, 'alert'=>$message);
			}else{
				$message = '* parameter BLOCK CODE tidak ditemukan ';
			}
			return $this->response->setStatusCode(200)->setJSON($data);
		
		

	}
	function setParamBucket($bucket){
		// $bucket = '["120DPD","150DPD","30DPD","60DPD","90DPD","XDAYS"]
		// ';
		$bucket = trim($bucket);
		$bucket = json_decode($bucket);
		// print_r($bucket);
		$bucket = implode('","',$bucket);
		// echo $bucket;
		// $result = ' AND CM_BUCKET IN ("'.$bucket.'") ';
		$result = $bucket;
		// $sql = "select concat(' AND DPD between ', min(dpd_from) , ' AND ' , max(dpd_until) ) sqlquery from cms_bucket where bucket_id in ? and is_active = '1'";
		// $result = $this->db->query($sql,array($bucket))->result_array();
		
		if($result !=''){
			return 	$result;
		}else{
			return '';
		}
		
	}
	function payment_plan(){

		$tipe = strtoupper($this->input->getGet('tipe')); //EFFECTIVE , FLAT , SLIDING
		$sisa_pokok_pinjaman = $this->input->getGet('hutang');
		$bunga = $this->input->getGet('bunga');
		$tenor = $this->input->getGet('tenor');
		$moratorium = $this->input->getGet('moratorium');
	
		if(!in_array($tipe,array('EFFECTIVE' , 'FLAT' , 'SLIDING'))){

			return $this->response->setStatusCode(200)->setJSON($tipe." : TYPE IS NOT FOUND");
		}

		$output = array();
	

		switch ($tipe) {
			case 'EFFECTIVE':
				$output = $this->Payment_plan_model->effective($tipe, $sisa_pokok_pinjaman, $bunga, $tenor, $moratorium);
				break;
			case 'FLAT':
				$output = $this->Payment_plan_model->flat($tipe, $sisa_pokok_pinjaman, $bunga, $tenor, $moratorium);
				break;
			case 'SLIDING':
				$output = $this->Payment_plan_model->sliding($tipe, $sisa_pokok_pinjaman, $bunga, $tenor, $moratorium);
				break;
			default:
				# code...
				break;
		}
		
		

		$data['payment_plan'] = $output;
		return view('\App\Modules\WorkflowPengajuan\Views\Payment_plan_view', $data);
	}
	function get_new_installment_amount(){
		$tipe = strtoupper($this->input->getGet('tipe')); //EFFECTIVE , FLAT , SLIDING
		$sisa_pokok_pinjaman = $this->input->getGet('hutang');
		$bunga = $this->input->getGet('bunga');
		$tenor = $this->input->getGet('tenor');
		$moratorium = $this->input->getGet('moratorium');
	
		if(!in_array($tipe,array('EFFECTIVE' , 'FLAT' , 'SLIDING'))){
			return $this->response->setStatusCode(200)->setJSON($tipe." : TYPE IS NOT FOUND");
		}

		$output = array();
	

		switch ($tipe) {
			case 'EFFECTIVE':
				$output = $this->Payment_plan_model->effective($tipe, $sisa_pokok_pinjaman, $bunga, $tenor, $moratorium);
				break;
			case 'FLAT':
				$output = $this->Payment_plan_model->flat($tipe, $sisa_pokok_pinjaman, $bunga, $tenor, $moratorium);
				break;
			case 'SLIDING':
				$output = $this->Payment_plan_model->sliding($tipe, $sisa_pokok_pinjaman, $bunga, $tenor, $moratorium);
				break;
			default:
				# code...
				break;
		}
		// print_r($output);
		return $this->response->setStatusCode(200)->setJSON(array('success'=>true,'new_installmet_amount'=>$output[0]['installment_amount']));
		
	}
	function save_pengajuan_reschedule(){
		// echo "success";
		// $flag = 1 = save and finish
		// $flag = 0 = save and draft
		$data['id']	 = $this->input->getPost('txt_id_pengajuan');
		
		$data['class_id'] 		 = '';
		$data['cm_card_nmbr']	 = $this->input->getPost('txt_card_number');
		$data['reschedule_rate_id']	 = $this->input->getPost('reschedule_rate');
		$data['product_id']				 = $this->input->getPost('txt_product_id');
		$data['product_code']				 = $this->input->getPost('txt_product_code');
		$data['desc_kondisi_khusus']	 = $this->input->getPost('desc_kondisi_khusus');
		$data['kondisi_khusus']	 = $this->input->getPost('flag_kondisi_khusus');
		
		$data['note_kondisi_khusus']	 = $this->input->getPost('txt_catatan_kondisi_khusus');
		$data['deviation_flag']	 = $this->input->getPost('flag_deviation');
		$data['deviation_reason']	 = $this->input->getPost('txt_deviation_reason');

		$data['principle_balance']	 = $this->input->getPost('txt-principle-balance-val');
		$data['principle_balance']	 = str_replace(",","",$data['principle_balance']??'');

		$data['total_due_balance_installment']	 = $this->input->getPost('txt-total-due-balance-installment-val');
		$data['total_due_balance_installment']	 = str_replace(",","",$data['total_due_balance_installment']??'');

		$data['due_interest']	 = $this->input->getPost('txt-due-interest-val');
		$data['due_interest']	 = str_replace(",","",$data['due_interest']??'');

		

		$data['late_charge']	 = $this->input->getPost('txt-late-charge-val');
		$data['late_charge']	 = str_replace(",","",$data['late_charge']??'');

		$data['penalty']	 = str_replace(",","",$this->input->getPost('txt-penalty-val')??'');
		$data['stamp_duty']	 = str_replace(",","",$this->input->getPost('txt-stamp-duty-val')??'');
		$data['total']	 = str_replace(",","",$this->input->getPost('txt-total-val')??'');

		$data['amount_ptp']	 = str_replace(",","",$this->input->getPost('txt-amount-ptp-dp')??'');
		$data['payment_bunga']	 = str_replace(",","",$this->input->getPost('txt-payment-bunga-val')??'');
		$data['payment_denda']	 = str_replace(",","",$this->input->getPost('txt-payment-denda-val')??'');

		$data['amount_principle_balance_discount']	 = str_replace(",","",$this->input->getPost('txt-principle-balance-discount')??'');
		$data['discount_amount_principle_balance']	 = $this->input->getPost('txt-principle-balance-discount-2');

		$data['amount_late_charge_discount']	 = str_replace(",","",$this->input->getPost('txt-late-charge-discount')??'');
		$data['discount_amount_late_charge']	 = $this->input->getPost('txt-late-charge-discount-2');

		$data['amount_interest_discount']	 = str_replace(",","",$this->input->getPost('txt-interest-discount')??'');
		$data['discount_amount_interest']	 = $this->input->getPost('txt-interest-discount-2');

		

		$data['payment_pokok']	 = str_replace(",","",$this->input->getPost('txt-payment-pokok-val')??'');
		$data['sisa_pokok_pinjaman_baru']	 = str_replace(",","",$this->input->getPost('txt-sisa-pokok-pinjaman-baru-val')??'');
		$data['tenor']	 = str_replace(",","",$this->input->getPost('txt_tenor_val')??'');
		$data['interest']	 = str_replace(",","",$this->input->getPost('txt_interest_val')??'');

		$data['new_installment']	 = str_replace(",","",$this->input->getPost('txt_new_installment_amount_val')??'');

		$data['moratorium']	 = $this->input->getPost('txt-moratorium-val');
		$data['ptp_date']	 = $this->input->getPost('txt_ptp_date_for_rst');
		$data['bucket'] = $this->Common_model->get_record_value('CM_BUCKET','cpcrd_new','CM_CARD_NMBR="'.$data['cm_card_nmbr'].'" ');

		$data['monthly_interest_ongoinginstallment']	 = $this->input->getPost('txt-monthly-interest-ongoinginstallment');
		$data['penalty_ongoinginstallment']	 = $this->input->getPost('txt-penalty-ongoinginstallment');
		$data['tipe_suku_bunga']	 = $this->input->getPost('btnradiotipesukubunga');

		$txt_screen_level	 = $this->input->getPost('txt_screen_level');

		$agent_id	 = $this->input->getPost('agent_id');
		$source	 = $this->input->getPost('source');
		
		if($txt_screen_level=='ASSIGNED'){
			$flag = 'APPROVAL';
		}else{
			$flag = 'NEW';
		}
		if(trim($data['id']??'')==''){
			$id_pengajuan	= UUID(false);
		}else{
			$id_pengajuan = $data['id'];
		}
		$approval['id'] 			= uuid(false);
		$approval['id_pengajuan'] 	= $id_pengajuan;
		$approval['tipe_pengajuan'] = 'RSCH';
		$approval['status'] 		= $flag;
		$approval['action'] 		= 'REQUEST';
		$approval['created_time']	= date('Y-m-d H:i:s');
		$approval['created_by']		= $agent_id;
		
		$builder = $this->db->table('cms_approval');
		$builder->insert($approval);
		
		$data['status']	 = $flag;
		$data['notes']	 = $this->input->getPost('txt-remark');
		$data['created_by']	 = $agent_id;
		$data['created_time']	 = date('Y-m-d h:i:s');
		$data['tipe_pengajuan']	 = 'RSCH';
		$data['source']	 = $source;

		$builder = $this->db->table('cms_pengajuan_reschedule');
		if(trim($data['id']??'')==''){
			$data['id']	= UUID(false);
			$result = $builder->insert($data);
		}else{
			$builder->where('id',$data['id']);
			$result = $builder->update($data);
		}



		//============================payment plan==========================================================
		$tipe 					= $data['tipe_suku_bunga'];
		$sisa_pokok_pinjaman 	= $data['sisa_pokok_pinjaman_baru'];
		$bunga 					= $data['interest'];
		$tenor 					= $data['tenor'];
		$moratorium 			= $data['moratorium'];
		switch ($tipe) {
			case 'EFFECTIVE':
				$output = $this->Payment_plan_model->effective($tipe, $sisa_pokok_pinjaman, $bunga, $tenor, $moratorium);
				break;
			case 'FLAT':
				$output = $this->Payment_plan_model->flat($tipe, $sisa_pokok_pinjaman, $bunga, $tenor, $moratorium);
				break;
			case 'SLIDING':
				$output = $this->Payment_plan_model->sliding($tipe, $sisa_pokok_pinjaman, $bunga, $tenor, $moratorium);
				break;
			default:
				# code...
				break;
		}
		$builder = $this->db->table('cms_payment_plan_relief_program');
		$builder->where('id_pengajuan', $data['id']);
		$builder->delete();
		foreach ($output as $key => $value) {
			$data_payment_plan['id_pengajuan'] = $data['id'];
			$data_payment_plan['relief_program'] = 'RESCHEDULE';
			$data_payment_plan['created_by'] = session()->get('USER_ID');
			$data_payment_plan['created_time'] = date('Y-m-d H:i:s');
			$data_payment_plan['cicilan'] = $value['installment_no'];
			$data_payment_plan['pokok_pinjaman'] = $value['principle'];
			$data_payment_plan['pokok_angsuran'] = $value['installment_principle'];
			$data_payment_plan['bunga'] = $value['interest'];
			$data_payment_plan['moratorium'] = isset($value['moratorium'])?$value['moratorium']:0;
			$data_payment_plan['angsuran'] = $value['installment_amount'];
			$data_payment_plan['saldo'] = $value['saldo'];

			$builder = $this->db->table('cms_payment_plan_relief_program');
			$builder->insert($data_payment_plan);
		}
		//============================payment plan==========================================================
		 
		
		$builder = $this->db->table('cms_reschedule_parameter a');
		$builder->select("'{$data['id']}' as id_pengajuan, a.*");
		$builder->where('a.parameter_id', $data['reschedule_rate_id']);
		$insertData = $builder->get()->getResultArray();
		if (!empty($insertData)) {
			$tmpBuilder = $this->db->table('cms_reschedule_parameter_tmp');
			foreach ($insertData as $row) {
				// Try to delete the existing record with the same primary key
				// $tmpBuilder->where('id', $row['id']);
				// $tmpBuilder->delete();

				// Insert the new record
				$result = $tmpBuilder->insert($row);
			}
		}
		// $sql = 'replace into cms_restructure_parameter_tmp select "'.$data['id'].'" , a.* from cms_restructure_parameter a where a.parameter_id = "'. $data['restructure_rate_id'].' "';
		// $result = $this->db->query($sql);

		$builder = $this->db->table('cms_reschedule_parameter_tmp a');
		$builder->set('a.updated_time', date('Y-m-d H:i:s'));
		$builder->set('a.updated_by', $data['created_by']);
		$builder->where('a.id_pengajuan', $data['id']);
		$result = $builder->update();
		// $sql = 'update cms_restructure_parameter_tmp a set a.updated_time = now() , a.updated_by = "'.$data['created_by'].'" where a.id_pengajuan= "'.$data['id'].'" ';
		// $result = $this->db->query($sql);

		$id_tenor_interest = $this->input->getPost('txt_interest_id');
		$this->save_contractdetail($data['id']);

	
		
		if($this->input->getPost('status')=='1'){ //save and finish
			if(session()->get('LEVEL_GROUP')=='TELECOLL' || session()->get('LEVEL_GROUP')=='FIELD_COLL'){
				//send email jika agent
				$return_msg = $this->send_message("Request Reschedule",$data['cm_card_nmbr'],'Email',array('id_pengajuan'=>$data['id']),$agent_id,'Internal');
			}else{
				$return_msg = true;
			}
			
			//update cms_account_last_status
			if($return_msg){
				// $this->update_last_status($data['id']);
			}else{
				$data_return	= array("success" => false, "message" => "Pengajuan gagal, approval not found ");	
				
				$builder = $this->db->table('cms_pengajuan_reschedule');
				$builder->where('id', $data['id']);
				$builder->delete();
				// $sql = "DELETE FROM cms_pengajuan_restructure WHERE id = '".$data['id']."' ";
				// $this->db->query($sql);
				// echo json_encode($data_return);
				// return false;
				return $this->response->setStatusCode(200)->setJSON($data_return);

			}
		}

		if($result==true){
			$description = array(
				"action" => "RESCHEDULE PROGRAM REQUEST", 
				"before" => null, 
				"after" => $this->Common_model->get_record_values("
					cm_card_nmbr 'card number',
					product_id 'product id',
					source,
					call_verification_status 'call verification status',
					document_upload_status 'document upload status',
					status 'request status'", 
					'cms_pengajuan_reschedule', 
					"id = '".$data['id']."'",""),
				"status" => "waiting verification", 
				"approval" => null);				
			$description = json_encode($description);
			$this->Common_model->data_logging('PROGRAM REQUEST', 'RESTRUCTURE RESCHEDULE REQUEST', 'SUCCESS', $description);
			$data	= array("success" => true, "message" => "Pengajuan Reschedule Tersimpan" , 'id'=> $data['id'] );
		}else{
			$data	= array("success" => false, "message" => "gagal tersimpan" );
		}
		$newCsrfToken = csrf_hash();
		return $this->response->setStatusCode(200)->setJSON(array_merge($data, ['newCsrfToken' => $newCsrfToken]));



	}
	function save_anuitas($id_pengajuan=null){
	
		$hutang = (double) $this->Common_model->get_record_value('sisa_pokok_pinjaman_baru','cms_workflow_view','id="'.$id_pengajuan.'"');
		$bunga = (double)  $this->Common_model->get_record_value('interest','cms_workflow_view','id="'.$id_pengajuan.'"');
		$tenor = (int) $this->Common_model->get_record_value('tenor','cms_workflow_view','id="'.$id_pengajuan.'"');


		$this->Anuitas_model->anuitas($id_pengajuan,$hutang,$bunga,$tenor);
	}
	function save_contractdetail($id){
		$table_detail = 'cms_pengajuan_reschedule_detail';
		$table_master = 'cms_pengajuan_reschedule';

		$builder = $this->db->table('cpcrd_new a');
		$builder->select("
			b.id as id_pengajuan,
			a.CM_CARD_NMBR as cm_card_nmbr,
			0 CM_TOTAL_OS_AR,
			a.CM_OS_PRINCIPLE,
			0 CM_OS_INTEREST,
			a.CM_INSTALLMENT_AMOUNT,
			a.CM_OS_BALANCE,
			0 CM_AMNT_OUTST_INSTL,
			0 CM_TOT_PRINCIPAL,
			0 CM_TOT_INTEREST,
			0 CM_RTL_MISC_FEES, 
			0 CM_INTR_PER_DIEM,
			'' CM_COLLECTIBILITY,
			a.CM_AMOUNT_DUE,
			a.DPD,
			a.CM_BUCKET,
			a.MOB,
			'' CM_DTE_OPENED,
			a.CM_INSTALLMENT_NO,
			0 CM_TENOR,
			a.CM_DTE_PYMT_DUE,
			a.CM_TYPE,
			a.CM_CRLIMIT,
			a.CM_CYCLE,
			a.CM_BLOCK_CODE,
			0 CM_CURR_DUE,
			a.CM_PAST_DUE,
			a.CM_30DAYS_DELQ,
			a.CM_60DAYS_DELQ,
			a.CM_90DAYS_DELQ,
			a.CM_120DAYS_DELQ,
			a.CM_150DAYS_DELQ,
			a.CM_180DAYS_DELQ,
			a.CM_210DAYS_DELQ
		");
		$builder->join($table_master . ' b', 'a.cm_card_nmbr = b.cm_card_nmbr');
		// $builder->join('dcoll_customer_data c', 'a.account_number = c.account_number');
		$builder->where('b.id', $id);

		$query = $builder->get();

		// Menyiapkan data untuk dimasukkan ke dalam tabel detail
		$insertData = $query->getResultArray();
		$detailBuilder = $this->db->table($table_detail);

        foreach ($insertData as $data) {
            // REPLACE INTO equivalent logic
            $detailBuilder->replace($data);
        }
		// $sql = "REPLACE ".$table_detail."
		// 			(
		// 				id_pengajuan,
		// 				CM_ORG_NMBR,
		// 				CM_TYPE,
		// 				CM_CARD_NMBR,
		// 				CM_CUSTOMER_NMBR,
		// 				CM_STATUS,
		// 				CM_DTE_PYMT_DUE,
		// 				CM_30DAYS_DELQ,
		// 				CM_60DAYS_DELQ,
		// 				CM_90DAYS_DELQ,
		// 				CM_120DAYS_DELQ,
		// 				CM_150DAYS_DELQ,
		// 				CM_180DAYS_DELQ,
		// 				CM_210DAYS_DELQ,
		// 				CM_CRLIMIT,
		// 				CM_TOT_BALANCE,
		// 				CM_BLOCK_CODE,
		// 				CR_NAME_1,
		// 				CR_EU_SEX,
		// 				CM_CYCLE,
		// 				CM_BUCKET,
		// 				AGENT_ID,
		// 				DPD,
		// 				PRODUCT_ID,
		// 				CM_INSTALLMENT_AMOUNT,
		// 				CM_PAID_CHARGE,
		// 				CM_OFFICER_NAME
		// 				)
		// 		SELECT
		// 			b.id,
		// 			a.card_no,
		// 			a.product_type,
		// 			a.account_number,
		// 			c.cif_number,
		// 			a.account_status,
		// 			a.due_date,
		// 			a.dpd_30_days,
		// 			a.dpd_60_days,
		// 			a.dpd_90_days,
		// 			a.dpd_120_days,
		// 			a.dpd_150_days,
		// 			a.dpd_180_days,
		// 			a.dpd_210_days,
		// 			a.credit_limit,
		// 			a.outstanding_balance,
		// 			a.block_code,
		// 			c.name,
		// 			c.sex,
		// 			a.cycle_date,
		// 			a.bucket,
		// 			a.assigned_agent,
		// 			a.day_past_due,
		// 			a.product_code,
		// 			a.installment_amount,
		// 			a.late_payment_charge,
		// 			c.office_name
		// 		from dcoll_account_data a
		// 		join ".$table_master." b on a.account_number = b.cm_card_nmbr
		// 	    JOIN dcoll_customer_data c on a.account_number = c.account_number
		// 		where b.id = ?
		// 		;";
		// 		$this->db->query($sql,array($id));
	}
	function save_tenor_interest_detail($id_pengajuan, $tipe_pengajuan , $id_tenor){
		$builder = $this->db->table("ws_tenor_interest_list a");
		$builder->select("{$id_pengajuan}, {$tipe_pengajuan}, a.*");
		$builder->where('id', $id_tenor);
		$rs = $builder->get();
		$insertData = $rs->getResultArray();
		if (!empty($insertData)) {
			$tmpBuilder = $this->db->table('cms_pengajuan_program_tenor_interest_detail');
			foreach ($insertData as $row) {
				// Try to delete the existing record with the same primary key
				// $tmpBuilder->where('id', $row['id']);
				// $tmpBuilder->delete();

				// Insert the new record
				$result = $tmpBuilder->replace($row);
			}
		}

		// $sql = "REPLACE cms_pengajuan_program_tenor_interest_detail
		// 		SELECT ? , ? , a.* FROM ws_tenor_interest_list a WHERE id = ? ";
		// $result = $this->db->query($sql , array($id_pengajuan, $tipe_pengajuan , $id_tenor));

		return $result;
	}
	function send_message($event , $agreeemnt_no , $sent_by='Email',$param = array() , $user_id = null, $recipient = 'Internal'){
		// echo $event."|".$agreeemnt_no."|".$sent_by."|".$user_id."|".$recipient;
		// echo "<br>";
		$template_id	 = $this->Common_model->get_record_value("template_id", "cms_email_sms_template", " template_relation='".$event."' and recipient = '".$recipient."' and sent_by= '".$sent_by."' and is_active='1' ");
		$template_design = $this->Common_model->get_record_value("template_design", "cms_email_sms_template", " template_relation='".$event."' and recipient = '".$recipient."' and sent_by= '".$sent_by."' and is_active='1' ");
		// $recipient		 = $this->Common_model->get_record_value("recipient", "cms_email_sms_template", " template_relation='".$event."' and sent_by= '".$sent_by."' and is_active='1' ");
		$nama_beditur	 = $this->Common_model->get_record_value("name", "dcoll_customer_data", "account_number='".$agreeemnt_no."' ");
		$user_id 		 = $user_id;
		$to_address		 = '';
		
		$msg_fun = '';

		// echo "|".$template_id;
		if($template_id==''){
			// echo "TEMPLATE NOT FOUND! [1]";
			return false;
		}
		
		$data['id']				 = uuid(false);
		$data['created_by']		 = $user_id;
		$data['created_time']	 = date('Y-m-d H:i:s');
		$data['template_id']	 = $template_id;

		if($sent_by=="Email"){
			
			$data['card_no']	 	 = $agreeemnt_no ;
			$data['sender']			 = NULL;
			
			$data['cc_address']		 = NULL;
			$data['subject']		 = $event;
			$data['message']		 = $template_design;
			$data['attachment']		 = NULL;
			$data['sent_status']	 = 'Q';
			$data['sent_time']		 = date('Y-m-d H:i:s');
			$data['sent_retry']		 = 0;
			$data['nama_debitur']	 = $nama_beditur;

		}else if($sent_by=="Sms"){
			
			$data['type']	 		 = $event ;
			$data['phone_no']	     = NULL;
			$data['content']	 	 = $template_design ;
			$data['status']			 = 'Q';
			$data['response']		 = NULL;
			$data['account_no']		 = $agreeemnt_no ;
			$data['source']			 = "CMS";
			
			

		}else{

			return false;
		}

		


		//=============================================================APPROVAL==================================================================
		if(count($param)>0){
			$id = $this->Common_model->get_record_value("id", "cms_pengajuan_diskon", "id='".$param['id_pengajuan']."' ");
			$level_approval = $this->Common_model->get_record_value("approval_level", "cms_approval", "id_pengajuan = '".$param['id_pengajuan']."' and action='APPROVAL' order by approval_level desc limit 1 ");
			if($level_approval==""){$level_approval = 0 ; }
			
			if($id != ''){
				$deviation_flag = $this->Common_model->get_record_value("deviation_flag", "cms_pengajuan_diskon", "id='".$param['id_pengajuan']."' ");
				$deviation_reason = $this->Common_model->get_record_value("deviation_reason", "cms_pengajuan_diskon", "id='".$param['id_pengajuan']."' ");
				$source = $this->Common_model->get_record_value("source", "cms_pengajuan_diskon", "id='".$param['id_pengajuan']."' ");

				if($deviation_flag == '0'){
					$disc_amount	= $this->Common_model->get_record_value("(amount_principle_discount + amount_interest_discount) disc_amount", "cms_pengajuan_diskon", "id='".$param['id_pengajuan']."'");

					$builder = $this->db->table("setup_approval_diskon a");
					$builder->select('a.*');
					$builder->where("a.amount_from <=", $disc_amount);
					$builder->where("a.amount_until >=", $disc_amount);
					$builder->where("a.is_active", '1');
					$builder->orderBy("a.amount_from", 'desc');
					$builder->limit(1);
					$query = $builder->get();
					$jumlah = $query->getNumRows();
					// $sql = "SELECT a.* from setup_approval_diskon a 
					// 		where 
					// 		a.amount_from <= ?
					// 		and  a.amount_until >= ?
					// 		and a.is_active = '1'
					// 		order by a.amount_from desc limit 1 ";
					// $jumlah  = $this->db->query($sql, array($disc_amount,$disc_amount))->num_rows();
					// echo $this->db->last_query();
					if($jumlah>0){
						$hasil  = $query->getRowArray();
						$json_checker = json_decode($hasil['json_checker'],true);
						$json_approval = json_decode($hasil['json_approval'],true);
					}else{
						//echo "APPROVAL DISKON NOT FOUND [2]";
						return false;
					}
				}else if($deviation_flag == '1'){
					//cheker
					$disc_amount	= $this->Common_model->get_record_value("(amount_principle_discount + amount_interest_discount) disc_amount", "cms_pengajuan_diskon", "id='".$param['id_pengajuan']."'");
					
					$builder = $this->db->table("setup_approval_diskon a");
					$builder->select('a.*');
					$builder->where("a.amount_from <=", $disc_amount);
					$builder->where("a.amount_until >=", $disc_amount);
					$builder->where("a.is_active", '1');
					$builder->orderBy("a.amount_from", 'desc');
					$builder->limit(1);
					$query = $builder->get();
					$jumlah = $query->getNumRows();

					// $sql = "SELECT a.* from setup_approval_diskon a 
					// 		where 
					// 		a.amount_from <= ?
					// 		and  a.amount_until >= ?
					// 		and a.is_active = '1'
					// 		order by a.amount_from desc limit 1 ";
					// $jumlah  = $this->db->query($sql, array($disc_amount,$disc_amount))->num_rows();
					if($jumlah>0){
						$hasil  = $query->getRowArray();
						$json_checker = json_decode($hasil['json_checker'],true);
					}else{
						// echo "CHEKCER DISKON NOT FOUND [3]";
						return false;
					}

					//approval
					$approval = $this->Common_model->get_record_values("*", "cms_deviation_approval", " dev_ref_id like'%".$deviation_reason  ."%'");

					if(isset($approval ['app_1_user_1'])){
						$approval = array(
							'approval_1' => array($approval ['app_1_user_1'],$approval ['app_1_user_2'],$approval ['app_1_user_3']),
							'approval_2' => array($approval ['app_2_user_1'],$approval ['app_2_user_2'],$approval ['app_2_user_3']),
							'approval_3' => array($approval ['app_3_user_1'],$approval ['app_3_user_2'],$approval ['app_3_user_3']),
							'approval_4' => array($approval ['app_4_user_1'],$approval ['app_4_user_2'],$approval ['app_4_user_3'])
						);
						$json_approval = $approval;
					}else{
						echo "APPROVAL DEVIATION DISKON NOT FOUND [2]";
						return false;
					}



				}
				
				
			}else if($id == ''){
				$id = $this->Common_model->get_record_value("id", "cms_pengajuan_restructure", "id='".$param['id_pengajuan']."' ");
				$deviation_flag = $this->Common_model->get_record_value("deviation_flag", "cms_pengajuan_restructure", "id='".$param['id_pengajuan']."' ");
				$deviation_reason = $this->Common_model->get_record_value("deviation_reason", "cms_pengajuan_restructure", "id='".$param['id_pengajuan']."' ");
				$source = $this->Common_model->get_record_value("source", "cms_pengajuan_restructure", "id='".$param['id_pengajuan']."' ");

				if($deviation_flag == '0'){
					// $disc_amount	= $this->Common_model->get_record_value("(amount_principle_balance_discount + amount_interest_discount) disc_amount", "cms_pengajuan_restructure", "id='".$param['id_pengajuan']."'");
					$disc_amount	= $this->Common_model->get_record_value("cm_os_balance", "cpcrd_new", "cm_card_nmbr='".$agreeemnt_no."'");

					$builder = $this->db->table("setup_approval_restructure a");
					$builder->select('a.*');
					$builder->where("a.amount_from <=", $disc_amount);
					$builder->where("a.amount_until >=", $disc_amount);
					$builder->where("a.is_active", '1');
					$builder->orderBy("a.amount_from", 'desc');
					$builder->limit(1);
					$query = $builder->get();
					$jumlah = $query->getNumRows();
					// $sql = "SELECT a.* from setup_approval_restructure a 
					// 		where 
					// 		a.amount_from <= ?
					// 		and  a.amount_until >= ?
					// 		and a.is_active = '1'
					// 		order by a.amount_from desc limit 1 ";
					// $jumlah  = $this->db->query($sql, array($disc_amount,$disc_amount))->num_rows();
					
					if($jumlah>0){
						$hasil  = $query->getRowArray();
						$json_checker = json_decode($hasil['json_checker'],true);
						$json_approval = json_decode($hasil['json_approval'],true);
					}else{
						// echo "APPROVAL RESTRUCTURE/RESCHEDULE NOT FOUND [4]";
						return false;
					}


				}else if($deviation_flag == '1'){
					//cheker
					// $disc_amount	= $this->Common_model->get_record_value("(amount_principle_discount + amount_interest_discount) disc_amount", "cms_pengajuan_restructure", "id='".$param['id_pengajuan']."'");
					$disc_amount	= $this->Common_model->get_record_value("cm_os_balance", "cpcrd_new", "cm_card_nmbr='".$agreeemnt_no."'");

					$builder = $this->db->table("setup_approval_diskon a");
					$builder->select('a.*');
					$builder->where("a.amount_from <=", $disc_amount);
					$builder->where("a.amount_until >=", $disc_amount);
					$builder->where("a.is_active", '1');
					$builder->orderBy("a.amount_from", 'desc');
					$builder->limit(1);
					$query = $builder->get();
					$jumlah = $query->getNumRows();
					// $sql = "SELECT a.* from setup_approval_diskon a 
					// 		where 
					// 		a.amount_from <= ?
					// 		and  a.amount_until >= ?
					// 		and a.is_active = '1'
					// 		order by a.amount_from desc limit 1 ";
					// $jumlah  = $this->db->query($sql, array($disc_amount,$disc_amount))->num_rows();
					if($jumlah>0){
						$hasil  = $query->getRowArray();
						$json_checker = json_decode($hasil['json_checker'],true);
					}else{
						// echo "CHEKCER RESTRUCTURE/RESCHEDULE NOT FOUND [5]";
						return false;
					}

					//approval
					$approval = $this->Common_model->get_record_values("*", "cms_deviation_approval", " dev_ref_id like'%".$deviation_reason  ."%'");

					if(isset($approval ['app_1_user_1'])){
						$approval = array(
							'approval_1' => array($approval ['app_1_user_1'],$approval ['app_1_user_2'],$approval ['app_1_user_3']),
							'approval_2' => array($approval ['app_2_user_1'],$approval ['app_2_user_2'],$approval ['app_2_user_3']),
							'approval_3' => array($approval ['app_3_user_1'],$approval ['app_3_user_2'],$approval ['app_3_user_3']),
							'approval_4' => array($approval ['app_4_user_1'],$approval ['app_4_user_2'],$approval ['app_4_user_3'])
						);
						$json_approval = $approval;
					}else{
						echo "APPROVAL DEVIATION RESTRUCTURE/RESCHEDULE NOT FOUND [4]";
						return false;
					}
				
				}

			}
			


			
			
		}
		

		//=======================================================================================================================================
		
		$source = $this->Common_model->get_record_value("source", "cms_workflow_view", "id='".$param['id_pengajuan']."' ");
		
		if($recipient=='Debitur'){
			
			switch ($event) {
				case "Request Diskon Pelunasan":
				  break;
				case "Request Restructure":
				  break;
				case "Request Reschedule":
				  break;
				case "Verifikasi dan Upload Kelengkapan Checker":
				  break;
				case "Approval Diskon Pelunasan":
					if($sent_by=="Email"){
						$to_address		  	 = $this->Common_model->get_record_value("CR_ADDL_EMAIL", "cpcrd_new", "CM_CARD_NMBR='".$agreeemnt_no."' ");
						$data['to_address']	= $to_address;
						
						$builder = $this->db->table("cc_email_log");
						$builder->insert($data);
					}else if ($sent_by=="Sms"){
						$to_handphone		  	 = $this->Common_model->get_record_value("CR_HANDPHONE", "cpcrd_new", "CM_CARD_NMBR='".$agreeemnt_no."' ");
						$data['phone_no']		 = $to_handphone;
						
						$builder = $this->db->table("cc_sms_log");
						$builder->insert($data);
					}
				  break;
				case "Approval Restructure":
					if($sent_by=="Email"){
						$to_address		  	 = $this->Common_model->get_record_value("CR_ADDL_EMAIL", "cpcrd_new", "CM_CARD_NMBR='".$agreeemnt_no."' ");
						$data['to_address']	= $to_address;
						
						$builder = $this->db->table("cc_email_log");
						$builder->insert($data);
					}else if ($sent_by=="Sms"){
						$to_handphone		  	 = $this->Common_model->get_record_value("CR_HANDPHONE", "cpcrd_new", "CM_CARD_NMBR='".$agreeemnt_no."' ");
						$data['phone_no']		 = $to_handphone;
						
						$builder = $this->db->table("cc_sms_log");
						$builder->insert($data);
					}
				  break;
				case "Approval Reschedule":
					if($sent_by=="Email"){
						$to_address		  	 = $this->Common_model->get_record_value("CR_ADDL_EMAIL", "cpcrd_new", "CM_CARD_NMBR='".$agreeemnt_no."' ");
						$data['to_address']	 = $to_address;

						$builder = $this->db->table("cc_email_log");
						$builder->insert($data);
					}else if ($sent_by=="Sms"){
						$to_handphone		  	 = $this->Common_model->get_record_value("CR_HANDPHONE", "cpcrd_new", "CM_CARD_NMBR='".$agreeemnt_no."' ");
						$data['phone_no']		 = $to_handphone;

						$builder = $this->db->table("cc_sms_log");
						$builder->insert($data);
					}
				  break;
				case "Escalate Account Deskcoll (Call Center)":
				  break;
				case "Request Billing dari dari Layar Agent Deskcoll":
				  break;
				case "Surat Keterangan Lunas":
				  break;
				default:
				  echo "EVENT NOT FOUND!";
			}
		}else{ //Internal		
			switch ($event) {
				case "Request Diskon Pelunasan":
				
					if($source=='CMS'){
						$TL_ID	 = $this->Common_model->get_record_value("team_leader", "cms_team", "agent_list like '%".$user_id."%' or team_leader = '".$user_id."'");
					}else if($source=='TELE'){
						$TL_ID	 = $this->Common_model->get_record_value("a.coordinator", "panin_deskcoll.acs_outbound_team a JOIN panin_deskcoll.acs_agent_assignment b ON a.id=b.team", "b.user_id = '".$user_id."'");
					}
					
					$to_address	 = $this->Common_model->get_record_value("email", "cc_user", "id = '".$TL_ID."' ");
					$data['to_address']	= $to_address;
					// var_dump($to_address);die;

					$builder = $this->db->table("cc_email_log");
					$builder->insert($data);

				  break;
				case "Request Restructure":
					if($source=='CMS'){
						$TL_ID	 = $this->Common_model->get_record_value("team_leader", "cms_team", "agent_list like '%".$user_id."%' or team_leader = '".$user_id."'");
					}else if($source=='TELE'){
						$TL_ID	 = $this->Common_model->get_record_value("a.coordinator", "panin_deskcoll.acs_outbound_team a JOIN panin_deskcoll.acs_agent_assignment b ON a.id=b.team", "b.user_id = '".$user_id."'");
					}
					$to_address	 = $this->Common_model->get_record_value("email", "cc_user", "id = '".$TL_ID."' ");
					$data['to_address']	= $to_address;
					
					$builder = $this->db->table("cc_email_log");
					$builder->insert($data);
					break;
					case "Request Reschedule":
						
					if($source=='CMS'){
						$TL_ID	 = $this->Common_model->get_record_value("team_leader", "cms_team", "agent_list like '%".$user_id."%' or team_leader = '".$user_id."'");
					}else if($source=='TELE'){
						$TL_ID	 = $this->Common_model->get_record_value("a.coordinator", "panin_deskcoll.acs_outbound_team a JOIN panin_deskcoll.acs_agent_assignment b ON a.id=b.team", "b.user_id = '".$user_id."'");
					}
					$to_address	 = $this->Common_model->get_record_value("email", "cc_user", "id = '".$TL_ID."' ");
					$data['to_address']	= $to_address;
					
					$builder = $this->db->table("cc_email_log");
					$builder->insert($data);
					break;
					case "Verifikasi dan Upload Kelengkapan Checker":
						
						foreach ($json_checker as $key => $value) {
							if($value!=''){
								$to_address	 = $this->Common_model->get_record_value("email", "cc_user", "id = '".$value."' ");
								$data['id']		    = uuid(false);
								$data['to_address']	= $to_address;
								
								$builder = $this->db->table("cc_email_log");
								$builder->insert($data);
							}
							
						}
						
				  break;
				  case "Approval Diskon Pelunasan":
					$level_approval += 1; //di tambah 1 agar level berikutnya di kirim email
					$level = 1;
					foreach ($json_approval as $key => $value) {
						if($level==$level_approval){
							foreach ($value as $key1 => $value1) {
								if($value1!=''){
									$to_address	 = $this->Common_model->get_record_value("email", "cc_user", "id = '".$value1."' ");
									$data['id']		    = uuid(false);
									$data['to_address']	= $to_address;
									
									$builder = $this->db->table("cc_email_log");
									$builder->insert($data);
								}
							}
						}
						$level++;
					}
				  break;
				case "Approval Restructure":
					$level_approval += 1; //di tambah 1 agar level berikutnya di kirim email
					$level = 1;
					foreach ($json_approval as $key => $value) {
						if($level==$level_approval){
							foreach ($value as $key1 => $value1) {
								if($value1!=''){
									$to_address	 = $this->Common_model->get_record_value("email", "cc_user", "id = '".$value1."' ");
									$data['id']		    = uuid(false);
									$data['to_address']	= $to_address;
						
									$builder = $this->db->table("cc_email_log");
									$builder->insert($data);
								}
							}
						}
						$level++;
					}
				  break;
				case "Approval Reschedule":
					$level_approval += 1; //di tambah 1 agar level berikutnya di kirim email
					$level = 1;
					foreach ($json_approval as $key => $value) {
						if($level==$level_approval){
							foreach ($value as $key1 => $value1) {
								if($value1!=''){
									$to_address	 = $this->Common_model->get_record_value("email", "cc_user", "id = '".$value1."' ");
									$data['id']		    = uuid(false);
									$data['to_address']	= $to_address;
									
									$builder = $this->db->table("cc_email_log");
									$builder->insert($data);
								}
							}
						}
						$level++;
					}
				  break;
				case "Escalate Account Deskcoll (Call Center)":
				  
				  break;
				case "Request Billing dari dari Layar Agent Deskcoll":
				  
				  break;
				case "Surat Keterangan Lunas":
				  
				  break;
				default:
				  echo "EVENT NOT FOUND!";
			  }

			  
		}

		return true;
	}
	function update_last_status($ID_PENGAJUAN){
		$data_pengajuan = $this->Common_model->get_record_values('*','cms_workflow_view','id="'.$ID_PENGAJUAN.'"');

		$data['status_pengajuan']		= $data_pengajuan['status'];
		$data['tgl_pengajuan']		= $data_pengajuan['created_time'];
		$data['pengajuan_id']			= $data_pengajuan['id'];
		if($data_pengajuan['tipe_pengajuan']=='DP'){
			$data['flag_diskon']			= $data_pengajuan['status'];
			$data['diskon_app_date']		= DATE('Y-m-d');
			$data['total_amount_diskon']	= $data_pengajuan['total_amount_discount'];	
			$data['ptp_date']				= $data_pengajuan['ptp_date'];	
			$data['ptp_amount']				= $data_pengajuan['amount_ptp'];	
		}else if($data_pengajuan['tipe_pengajuan']=='RSCH'){
			$data['flag_reschedule']		= $data_pengajuan['status'];
			$data['reschedule_app_date']	= DATE('Y-m-d');
			$data['sisa_pokok_pinjaman']	= $data_pengajuan['sisa_pokok_pinjaman_baru'];
			$data['ptp_date']				= $data_pengajuan['ptp_date'];	
			$data['ptp_amount']				= $data_pengajuan['amount_ptp'];
		}else if($data_pengajuan['tipe_pengajuan']=='RSTR'){
			$data['flag_restructure']		= $data_pengajuan['status'];
			$data['restructure_app_date']	= DATE('Y-m-d');
			$data['sisa_pokok_pinjaman']	= $data_pengajuan['sisa_pokok_pinjaman_baru'];
			$data['ptp_date']				= $data_pengajuan['ptp_date'];	
			$data['ptp_amount']				= $data_pengajuan['amount_ptp'];
		}
		$builder = $this->db->table('cms_account_last_status');
		$builder->where('account_no',$data_pengajuan['cm_card_nmbr']);
		$builder->update($data);

	}
	function save_call_history(){
		$data['id']				=	uuid();
		$data['status']			=	$this->input->getPost('status');
		$data['tipe_pengajuan']	=	$this->input->getPost('tipe_pengajuan');
		$data['phone']			=	$this->input->getPost('phone');
		$data['caller_id']		=	$this->input->getPost('caller_id');
		// $data['tipe_contact']	=	$this->input->getPost('tipe_contact');
		$data['agreement_no']	=	$this->input->getPost('agreement_no');
		$data['call_status']	=	$this->input->getPost('call_status');
		$data['call_result']	=	$this->input->getPost('call_result');
		$data['notes']			=	$this->input->getPost('remark');
		$data['id_pengajuan']	=	$this->input->getPost('id_pengajuan');
		$data['created_time']	=	date('Y-m-d H:i:s');
		$data['created_by']		=	session()->get('USER_ID');

		$submit_id	=	$this->input->getPost('submit_id');
		
		$builder = $this->db->table('cms_call_verification');
		$result = $builder->insert($data);
		$agent_id = session()->get('USER_ID');
		$table = '';
		if($data['tipe_pengajuan']=="DP"){ //diskon pelunasan
			$table = 'cms_pengajuan_diskon';
			$tipe_pengajuan="'discount' as 'tipe pengajuan'";
		}else{ //restructure dan reschedule
			$table = 'cms_pengajuan_reschedule';
			$tipe_pengajuan="if(tipe_pengajuan='RSCH','reschedule','restructure') as 'tipe pengajuan'";

		}

		$data_log['before']=$this->Common_model->get_record_values("
			cm_card_nmbr 'card number',
			product_id 'product id',".$tipe_pengajuan.",
			source,
			call_verification_status 'call verification status',
			document_upload_status 'document upload status',
			status 'request status'", 
			$table, 
			"id = '".$data['id_pengajuan']."'","");

			$builder = $this->db->table($table);
			$builder->set('call_verification_status',$data['status']);
			$builder->set('updated_time',$data['created_time']);
			$builder->set('updated_by',$data['created_by']	);
			$builder->where('id',$data['id_pengajuan']);
			$builder->update();
		
	

			$status_document = $this->Common_model->get_record_value('document_upload_status',$table,'id="'.$data['id_pengajuan'].'"');
			

			$approval['id'] 			= uuid(false);
			$approval['id_pengajuan'] 	= $data['id_pengajuan'];
			$approval['tipe_pengajuan'] = $data['tipe_pengajuan'];
			$approval['status'] 		= 'REQUEST';
			$approval['action'] 		= 'CALL_VERIFIVATION';
			$approval['created_time']	= date('Y-m-d H:i:s');
			$approval['created_by']		= session()->get('USER_ID');

			if($status_document=='FINISH' && $data['status']=='FINISH' ){
				// $this->db->set('status','APPROVAL');
				// $this->db->set('updated_time',$data['created_time']);
				// $this->db->set('updated_by',$data['created_by']	);
				// $this->db->where('id',$data['id_pengajuan']);
				// $this->db->update($table);
				$builder = $this->db->table('cms_approval');
				$builder->where('id_pengajuan', $data['id_pengajuan']);
				$builder->where('action', 'VERIFICATION');
				$builder->delete();
				$builder->insert($approval);

				// $this->db->delete('cms_approval', array('id_pengajuan' => $data['id_pengajuan'],'action'=>'VERIFICATION' ));
				// $result_approval = $this->db->insert('cms_approval',$approval);

			
			}
			else if($status_document=='CONFIRM' && $data['status']=='FINISH'){
				// $this->db->set('status','APPROVAL');
				// $this->db->set('updated_time',$data['created_time']);
				// $this->db->set('updated_by',$data['created_by']	);
				// $this->db->where('id',$data['id_pengajuan']);
				// $this->db->update($table);
				$builder = $this->db->table('cms_approval');
				$builder->where('id_pengajuan', $data['id_pengajuan']);
				$builder->where('action', 'VERIFICATION');
				$builder->delete();
				$builder->insert($approval);

				// $this->db->delete('cms_approval', array('id_pengajuan' => $data['id_pengajuan'],'action'=>'VERIFICATION' ));
				// $result_approval = $this->db->insert('cms_approval',$approval);

			}
		
				
		if($result){
			$description = array(
				"action" => "VERIVIKASI BY CALL", 
				"before" => $data_log['before'], 
				"after" => $this->Common_model->get_record_values("
					cm_card_nmbr 'card number',
					product_id 'product id',".$tipe_pengajuan.",
					source,
					call_verification_status 'call verification status',
					document_upload_status 'document upload status',
					status 'request status'", 
					$table, 
					"id = '".$data['id_pengajuan']."'",""),
				"status" => "success", 
				"approval" => null);				
			$description = json_encode($description);
			$this->Common_model->data_logging('VERIFIKASI', 'VERIFIKASI BY CALL', 'SUCCESS', $description);

			$data	= array("success" => true, "message" => "'Verification by call' berhasil tersimpan" );
		}else{
			$data	= array("success" => false, "message" => "'Verification by call' Gagal tersimpan" );
		}
		$newCsrfToken = csrf_hash();
		return $this->response->setStatusCode(200)->setJSON(array_merge($data, ['newCsrfToken' => $newCsrfToken]));
	}
	function save_remark_verif(){
		$data['id_pengajuan'] = $this->input->getPost('id_pengajuan');
		$data['remark'] = $this->input->getPost('remark');
		$data['submit_id'] =  date('YmdHis');


		$dataInput = [
			'id' => uuid(),
			'id_pengajuan' => $data['id_pengajuan'],
			'submit_id' => $data['submit_id'],
			'tipe_document' => 'DOC',
			'file_document' => 'REMARK_VERIFICATION',
			'notes' => $data['remark'],
			'created_by' => session()->get('USER_ID'),
			'created_time' => date('Y-m-d H:m:s')
		];
		$builder = $this->db->table('cms_upload_document_history');
		$return = $builder->insert($dataInput);

		// $sql = "INSERT INTO  cms_upload_document_history (id , id_pengajuan ,submit_id, tipe_document , file_document , notes  , created_by , created_time) 
		// 												VALUES( uuid() ,? ,?, ? , ? , ? , ?   ,now())";
		// $return = $this->db->query($sql, array($data['id_pengajuan'], $data['submit_id'], 'DOC', 'REMARK_VERIFICATION' ,$data['remark'], $this->session->userdata('user_id')));

		if($return){
			$data = array("success" => true, "message" => "Save Remark Doc Success!" , 'return'=>$return);
		}else{
			$data = array("success" => false, "message" => "Save Remark Doc Failed!" , 'return'=>$return);
		}
		$newCsrfToken = csrf_hash();
		return $this->response->setStatusCode(200)->setJSON(array_merge($data, ['newCsrfToken' => $newCsrfToken]));
	}
	function save_document_verification(){
		$id_pengajuan = $this->input->getPost('id_pengajuan');
		$id_upload = $this->input->getPost('id_upload');
		$agreement_no = $this->input->getPost('agreement_no');
		$tipe_pengajuan = $this->input->getPost('tipe_pengajuan');
		$status = $this->input->getPost('status');

		$data['tipe_pengajuan']		= $tipe_pengajuan ;
		$data['id_pengajuan'] 		= $id_pengajuan;
		$data['id_upload'] 			= $id_upload;
		$data['agreement_no'] 		= $agreement_no;
		$data['created_by'] 		= session()->get('USER_ID');
		$data['status']		 		= $status;

		$table = '';
		if($data['tipe_pengajuan']=="DP"){ //diskon pelunasan
			$table = 'cms_pengajuan_diskon';
			$tipe_pengajuan="'discount' as 'tipe pengajuan'";

		}else{ //restructure dan reschedule
			$table = 'cms_pengajuan_restructure';
			$tipe_pengajuan="if(tipe_pengajuan='RSCH','reschedule','restructure') as 'tipe pengajuan'";
		}
		$dataUpdate = [
			'document_upload_status' => $status,
			'updated_by'  => session()->get('USER_ID'),
			'updated_time'  =>  date('Y-m-d H:i:s'),
		];
		// $status = (string) $status;
		$builder = $this->db->table($table);  
		$builder->where('id', $id_pengajuan);  
		$builder->update($dataUpdate);
		
		$approval['id'] 			= uuid(false);
		$approval['id_pengajuan'] 	= $data['id_pengajuan'];
		$approval['tipe_pengajuan'] = $data['tipe_pengajuan'];
		$approval['status'] 		= 'APPROVE';
		$approval['action'] 		= 'VERIFICATION';
		$approval['created_time']	= date('Y-m-d H:i:s');
		$approval['created_by']		= session()->get('USER_ID');
		$agent_id = session()->get('USER_ID');

		$status_call = $this->Common_model->get_record_value('call_verification_status',$table,'id="'.$id_pengajuan.'"');
		if($status_call=='FINISH' && $data['status']=='FINISH'){
			// $this->db->set('status','APPROVAL');
			$builder = $this->db->table($table);
			$builder->set('updated_time',date('Y-m-d H:i:s'));
			$builder->set('updated_by',$data['created_by']	);
			$builder->where('id',$id_pengajuan);
			$builder->update();
			
			$builder = $this->db->table('cms_approval');
			$builder->where('id_pengajuan', $data['id_pengajuan']);
			$builder->where('action','ASSIGNED');
			$rs = $builder->delete();
			if ($rs) {
				$result_approval = $builder->insert($approval);
			}

			// $this->send_message("Verifikasi dan Upload Kelengkapan Checker",$data['agreement_no'] ,'Email',array('id_pengajuan'=>$id_pengajuan),$agent_id , 'Internal');
		}
		else if($status_call=='CONFIRM' && $data['status']=='FINISH'){
			// $this->db->set('status','ASSIGNED');
			$builder = $this->db->table($table);
			$builder->set('updated_time',date('Y-m-d H:i:s'));
			$builder->set('updated_by',$data['created_by']	);
			$builder->where('id',$id_pengajuan);
			$builder->update();


			$builder = $this->db->table('cms_approval');
			$builder->where('id_pengajuan', $data['id_pengajuan']);
			$builder->where('action','ASSIGNED');
			$rs = $builder->delete();
			if ($rs) {
				$result_approval = $builder->insert($approval);
			}

			
		}

		$newCsrfToken = csrf_hash();
		$data = array("success" => true, "message" => "Data Saved" , 'update'=>true);
		return $this->response->setStatusCode(200)->setJSON(array_merge($data, ['newCsrfToken' => $newCsrfToken]));

	}
	function get_csrf_token(){
		$newCsrfToken = csrf_hash();
		$data = array("success" => true, "message" => "Get Token Berhasil" , 'update'=>true);
		return $this->response->setStatusCode(200)->setJSON(array_merge($data, ['newCsrfToken' => $newCsrfToken]));
	}
	function upload_document(){
		
		$id_pengajuan = $this->input->getPost('id_pengajuan');
		$id_upload = $this->input->getPost('id_upload');
		$agreement_no = $this->input->getPost('agreement_no');
		$tipe_pengajuan = $this->input->getPost('tipe_pengajuan');
		$status = $this->input->getPost('status');
		$root_dir = './file_upload/join_program';
		$newCsrfToken = csrf_hash();

		$upload_path = $root_dir . "/" . $agreement_no;
		$file = $this->input->getFile($id_upload);
		if ($file && $file->isValid() && !$file->hasMoved()) {
            $file_name = $file->getRandomName();
            if ($file->move($upload_path, $file_name)) {
                $gbr = [
                    'file_name' => $file_name,
                    'file_type' => $file->getClientMimeType(),
                    'file_ext' => $file->getClientExtension(),
                    'full_path' => $upload_path . '/' . $file_name,
                    'file_size' => $file->getSize(),
                ];

                if (file_exists($gbr['full_path'])) {
                    $data = [
                        'id' => uuid(false),
                        'tipe_pengajuan' => $tipe_pengajuan,
                        'id_pengajuan' => $id_pengajuan,
                        'id_upload' => $id_upload,
                        'agreement_no' => $agreement_no,
                        'file_name' => $gbr['file_name'],
                        'file_type' => $gbr['file_type'],
                        'ext' => $gbr['file_ext'],
                        'path' => $gbr['full_path'],
                        'size' => $gbr['file_size'],
                        'json_data' => json_encode($gbr),
                        'created_by' => session()->get('USER_ID'),
                        'status' => $status,
                    ];

                    $builder = $this->db->table('cms_upload_document');
                    $return = $builder->insert($data);

                    $response = [
                        "success" => true,
                        "message" => "File " . $id_upload . " Uploaded",
                        'update' => $return,
                    ];
                } else {
                    $response = [
                        "success" => false,
                        "message" => "File " . $id_upload . " Failed",
                        'update' => @$return,
                    ];
                }

                return $this->response->setStatusCode(200)->setJSON(array_merge($response, ['newCsrfToken' => $newCsrfToken]));
            }
        }

	}
	function assignData(){
		$data['id'] = $this->input->getGet('id');	
		$builder = $this->db->table('cc_user a')	;
		$builder->select('a.id value, a.name item');
		$builder->join('cc_user_group b', 'a.group_id=b.id');
		$builder->where('a.is_active', '1');
		$builder->orderBy('a.name', 'asc');
		$query = $builder->get();
		$arrData = array();
		if ($query->getNumRows())
		{
			foreach ($query->getResult() as $row)
			{
				$arrData[$row->value] = $row->item;
			}
		}
		$data['tl_list'] = $arrData;

		return view('\App\Modules\WorkflowPengajuan\Views\Restructure_assign_to_tl_view', $data);
	}
	function saveAssignData(){
		$id = $this->input->getPost('id');
		$tl = $this->input->getPost('tl');

		$jumlah	= $this->Common_model->get_record_value("count(*)", "cms_pengajuan_reschedule", "id = '".$id."' and status='NEW' ");
		if($jumlah>0){
			$jumlah2	= $this->Common_model->get_record_value("count(*)", "cc_user", "id = '".$tl."' and is_active='1' ");

			if($jumlah2>0){
				$builder = $this->db->table('cms_pengajuan_reschedule');
				$builder->set('status', 'ASSIGNED');
				$builder->set('assign_to', $tl);
				$builder->where('id', $id);
				$rs = $builder->update();

				if($rs){
					$cache = session()->get('USER_ID').'_workflow_pengajuan_reschedule_list';
					$this->cache->delete($cache);
					$response = array('success'=>true , 'message'=>'Reschedule assign success');
				}else{
					$response = array('success'=>false , 'message'=>'Reschedule assign failed');
				}
			}else{
				$response = array('success'=>false , 'message'=>'user invalid');
			}

		}else{
			$response = array('success'=>false , 'message'=>'Reschedule not Found');
		}
		return $this->response->setStatusCode(200)->setJSON($response);
	}
	function approvalrequest(){
		$data['view'] = $this->input->getGet('view'); 
		$id = $this->input->getGet('id'); 
		$data['screen_level'] = "APPROVAL";
		$data['source'] = "CMS";
		$data['agent_id'] = session()->get('user_id');
		
		
		$data["credit_data"] = $this->Common_model->get_record_values("*", "cms_pengajuan_reschedule_detail ", "id_pengajuan='".$id."'");
		$data['deviation_reason'] = array(''=>'[select data]') + $this->Common_model->get_ref_master('deviation_id value , deviation_name item', 'cms_deviation_reference', 'is_active="1" and type like "%RESCHEDULE%" and product like "%'.$data["credit_data"]['CM_TYPE'].'%"  ', 'deviation_name asc');
		// $data["customer_data"]	= $this->Common_model->get_record_values("name , cif_number CIF_NUMBER , handphone , home_phone , home_phone_2 , mother_fix_phone , mother_handphone, investigation_phone , spouse_phone , office_phone  ", "dcoll_customer_data", "account_number='".$data["credit_data"]["account_number"]."'");
		$data["customer_data"]	= $this->Common_model->get_record_values(
			"CR_NAME_1 as name,
			CM_CARD_NMBR as cm_card_nmbr,
			CR_HANDPHONE as handphone,
			CR_HOME_PHONE as home_phone,
			'' as home_phone_2,
			CR_GUARANTOR_PHONE as mother_fix_phone,
			CR_GUARANTOR_PHONE as mother_handphone,
			'' as investigation_phone,
			CM_SPOUSE_PHONE as spouse_phone,
			CR_OFFICE_PHONE as office_phone", 
			"cpcrd_new", "CM_CARD_NMBR='".$data['credit_data']['CM_CARD_NMBR']."'");
		$data["pengajuan_data"] = $this->Common_model->get_record_values("*", "cms_pengajuan_reschedule ", "id='".$id."'");
		
		// $sql = "SELECT id , nama_document , is_mandatory FROM cms_setup_upload_document ORDER BY nama_document asc";
		
		$data['contact_result'] = array(''=>'[SELECT DATA]','CUSTOMER'=>'CUSTOMER','OTHER'=>'OTHER');
		
		$data['result_call'] = array(''=>'[SELECT DATA]','CS_APPROVE'=>'CUSTOMER SETUJU','CS_REJECT'=>'CUSTOMER MENOLAK');

		$builder = $this->db->table('cms_setup_upload_document');
		$builder->select('id , nama_document , is_mandatory');
		$builder->orderBy('nama_document', 'asc');
		$data['master_document'] = $builder->get()->getResultArray();
		
		// $data['master_document'] = $this->db->query($sql)->result_array(); 
		$builder = $this->db->table('cms_call_verification a');
		$select = array(
			"a.id", 
			"a.id_pengajuan",
			"b.description call_status", 
			"c.description call_result", 
			"d.name name", 
			"date(a.created_time) date", 
			"a.notes", 
			"time(a.created_time) time",
			"a.caller_id",
			"'-' action",
			"'' no",
			"a.created_time"
			);
				
		$builder->join("cms_reference b","a.call_status=b.value and b.reference='CONTACT_RESULT_VERIFICATION'");
		$builder->join("cms_reference c","a.call_result=c.value and c.reference='RESULT_CALL_VERIFICATION'");
		$builder->join("cc_user d","d.id=a.created_by");
		$builder->where('id_pengajuan',$id);
		$builder->orderBy('a.created_time','desc');
		$builder->select(' '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$data['history_call'] = $builder->get()->getResultArray();

		$builder = $this->db->table('cms_upload_document a');
		$builder->select('a.*');
		$builder->selectCount('a.id_upload', 'jumlah_data');
		$builder->where('id_pengajuan', $id);
		$builder->groupBy('id_upload');
		$builder->orderBy('created_time', 'desc');
		$data['history_upload'] = $builder->get()->getResultArray();
		
		// $sql = "SELECT a.* ,COUNT(a.id_upload) jumlah_data from cms_upload_document a where id_pengajuan = ?  GROUP BY id_upload order by created_time desc ";
		// $data['history_upload2'] = $this->db->query($sql,array($id))->getResultArray();
		
		// print_r($data);
		// exit();
		$builder = $this->db->table('setup_approval_reschedule a');
		$builder->select('b.level_num , b.level');
		$builder->join('setup_approval_reschedule_detail b', 'a.id=b.id_approval');
		// $builder->where($data["pengajuan_data"]['total'], '>= a.amount_from');
		$builder->where('a.amount_from <= ', $data["pengajuan_data"]['total']);
		$builder->where('a.amount_until >= ', $data["pengajuan_data"]['total']);
		// $builder->where($data["pengajuan_data"]['total'], '<= a.amount_until');
		$builder->groupBy('b.level_num');
		$builder->orderBy('level_num');
		$builder->orderBy('order_num');
		$result = $builder->get()->getResultArray();
		// print_r($result);
		// exit();
		// $sql = " SELECT b.level_num , b.'level' 
		// 			FROM setup_approval_restructure a
		// 			JOIN setup_approval_restructure_detail b ON a.id=b.id_approval
		// 		WHERE '".$data["pengajuan_data"]['total']."' >= a.amount_from AND '".$data["pengajuan_data"]['total']."' <= a.amount_until 
		// 		GROUP BY b.level_num
		// 		ORDER BY level_num , order_num";
		// $result = $this->db->query($sql)->result_array();
		$approval = array();
		if(count($result)>0){
			foreach ($result as $key => $value) {
			// Compile the first subquery
			$subquery1 = $this->db->table('setup_approval_reschedule a')
			->select('a.id , c.id as user_id , c.name as name  , 0 as urut')
			->join('setup_approval_reschedule_detail b', 'a.id = b.id_approval')
			->join('cc_user c', 'b.user_id = c.id')
			->where('b.level_num', $value['level_num'])
			->where('a.amount_from <= ', $data["pengajuan_data"]['total'])
			->where('a.amount_until >= ', $data["pengajuan_data"]['total'])
			->orderBy('b.level_num')
			->orderBy('b.order_num')
			->getCompiledSelect();

			// Compile the second subquery
			$subquery2 = $this->db->table('cms_approval s')
			->select("'' as id, s.created_by as user_id, d.name, 1 as urut")
			->join('cc_user d', 'd.id = s.created_by')
			->where('s.approval_level', $value['level_num'])
			->where('s.id_pengajuan', $data["pengajuan_data"]['id'])
			->getCompiledSelect();

			// Combine the subqueries using UNION ALL
			$unionQuery = "($subquery1) UNION ALL ($subquery2)";

			// Execute the combined query with group by and order by
			$builder = $this->db->query("
			SELECT * FROM ($unionQuery) AS tbl
			GROUP BY user_id
			ORDER BY urut ASC
			");

			$res = $builder->getResultArray();
			$approval[$key]['level_num'] = $value['level_num'];
			$approval[$key]['level'] = $value['level'];

		
			$approval[$key]['list_approval'] = $res;

			

			$data['APPROVAL_ID'] = @$res[0]['id'];
			}

			foreach ($result as $key => $value) {
				$cek= $this->Common_model->get_record_value("count(*)", "cms_approval ", "id_pengajuan='".$id."' and action='APPROVAL' and approval_level='".$value['level_num']."' ");
				if($cek==0){
					$data['APPROVAL_LEVEL'] = $value['level_num'];
					
					break;
				}
			}
		}
		
		$data['verif_data'] = $approval;
		// dd($data);
		return view('\App\Modules\WorkflowPengajuan\Views\Detail_contract_request_program_reschedule_view', $data)
		.view('\App\Modules\WorkflowPengajuan\Views\Approval_pengajuan_reschedule_view', $data);
	}
	function show_payment_plan(){
		$id = $this->input->getGet('id');
		$tipe = $this->input->getGet('tipe');
		$product_code = $this->input->getGet('product_code');

		$builder = $this->db->table('cms_payment_plan_relief_program');
		if($product_code=='CIMB-PL'){
			$builder->select(
				'cicilan installment_no,
				pokok_pinjaman principle,
				pokok_angsuran installment_principle,
				bunga interest,
				moratorium,
				angsuran installment_amount,
				saldo'
				);
			// $sql = "SELECT 
			// 		cicilan installment_no,
			// 		pokok_pinjaman principle,
			// 		pokok_angsuran installment_principle,
			// 		bunga interest,
			// 		moratorium,
			// 		angsuran installment_amount,
			// 		saldo
			// 	FROM cms_payment_plan_relief_program
			// 	WHERE 
			// 		id_pengajuan = ?
			// 	ORDER BY cicilan asc
			// 	";
		}else{
			$builder->select(
				'cicilan installment_no,
				pokok_pinjaman principle,
				pokok_angsuran installment_principle,
				bunga interest,
				angsuran installment_amount,
				saldo'
				);
			// $sql = "SELECT 
			// 			cicilan installment_no,
			// 			pokok_pinjaman principle,
			// 			pokok_angsuran installment_principle,
			// 			bunga interest,
			// 			angsuran installment_amount,
			// 			saldo
			// 		FROM cms_payment_plan_relief_program
			// 		WHERE 
			// 			id_pengajuan = ?
			// 		ORDER BY cicilan asc
			// 		";

		}
		$builder->where('id_pengajuan', $id);
		$builder->orderBy('cicilan', 'asc');
		$data['payment_plan'] = $builder->get()->getResultArray();
		// $data['payment_plan'] = $this->db->query($sql,array($id))->result_array();
		return view('\App\Modules\WorkflowPengajuan\Views\Payment_plan_view', $data);
	}
	function view_detail_upload(){
		$data['id_pengajuan'] = $this->input->getGet('id');
		$data['id_file'] = $this->input->getGet('doc');
		
		$builder = $this->db->table('cms_upload_document');
		$builder->select('*');
		$builder->where('id_pengajuan', $data['id_pengajuan']);
		$builder->where('id_upload', $data['id_file']);
		$builder->orderBy('created_time', 'desc');
		$data['history_upload'] = $builder->get()->getResultArray();
		// $sql = "select * from cms_upload_document where id_pengajuan= ? and id_upload = ? order by created_time desc ";
		// $data['history_upload'] = $this->db->query($sql , array($data['id_pengajuan'],$data['id_file']))->result_array();
		return view('\App\Modules\WorkflowPengajuan\Views\Detail_document_upload_view', $data);
	}
	function saveApprovalberjenjang (){
		$approval_id = $this->input->getPost('approval_id');
		$data['id'] = UUID(false);
		$data['id_pengajuan'] = $this->input->getPost('id');
		$data['status'] = $this->input->getPost('action');
		$data['notes'] = $this->input->getPost('remark');
		$data['approval_level'] = $this->input->getPost('approval_level');
		$data['tipe_pengajuan'] = $this->input->getPost('tipe_pengajuan');
		$data['created_time'] = date('Y-m-d H:i:s');
		$data['created_by'] = session()->get('USER_ID');
		$data['action'] = 'APPROVAL'; 
		

		$table='';
		$table_approval='';
		if($data['tipe_pengajuan']=="DP"){
			$type_pengajuan="'discount' as 'tipe pengajuan'";
			$table = "cms_pengajuan_diskon";
			
			$table_approval = "setup_approval_diskon";
		}else{
			$type_pengajuan="if(tipe_pengajuan='RSCH','reschedule','restructure') as 'tipe pengajuan'";
			$table = "cms_pengajuan_reschedule";
			$table_approval = "setup_approval_reschedule";
		}
		//add log
		$data_log['before']=$this->Common_model->get_record_values("
		cm_card_nmbr 'card number',
		product_id 'product id',".$type_pengajuan.",
		source,
		call_verification_status 'call verification status',
		document_upload_status 'document upload status',
		status 'request status'", 
		$table, 
		"id = '".$data['id_pengajuan']."'","");

		$isFinish = false;
		$finishMsg = '';
		$builder = $this->db->table('cms_approval');
		$return = $builder->insert($data);

		if($data['status']=="APPROVE"){
			$approval_id = $this->input->getPost('approval_id');
			
			// if($data['tipe_pengajuan']=='DP'){
			// 	$event1 = 'Approval Diskon Pelunasan';
			// }else if($data['tipe_pengajuan']=='RSTR'){
			// 	$event1 = 'Approval Restructure';
			// }else if($data['tipe_pengajuan']=='RSCH'){
			// 	$event1 = 'Approval Reschedule';
			// }

			$numOfLevel = $this->Common_model->get_record_value('id', 'setup_approval_reschedule', "id = '".$approval_id."'","");
			if($numOfLevel==$approval_id){
				$builder = $this->db->table($table);
				$builder->set('status', 'APPROVED');
				$builder->set('updated_by', $data['created_by']);
				$builder->set('updated_time', date('Y-m-d H:i:s'));
				$builder->where('id', $data['id_pengajuan']);
				$result2 = $builder->update();
				// print_r($this->db->getLastQuery());
				// $sql = "update ".$table." a set status=? , updated_by = ? ,updated_time= now()  where id=? ";
				// $result2 = $this->db->query($sql , array('APPROVE',$data['created_by'],$data['id_pengajuan']));
				$isFinish = true;
				$finishMsg = 'Pengajuan telah selesai , pengajuan disetujui';

				
			}
								


		}else if($data['status']=="REJECT"){
			$builder = $this->db->table($table);
			$builder->set('status', 'REJECT');
			$builder->set('updated_by', $data['created_by']);
			$builder->set('updated_time', $data['created_time']);
			$builder->where('id', $data['id_pengajuan']);
			$result2 = $builder->update();
			// print_r($this->db->getLastQuery());

			// $sql = "update ".$table." a set status=? , updated_by = ? ,updated_time=?  where id=? ";
			// $result2 = $this->db->query($sql , array('REJECT',$data['created_by'],$data['created_time'],$data['id_pengajuan']));
			$isFinish = true;
			$finishMsg = 'Pengajuan telah selesai , pengajuan tidak disetujui';
			
		}

		

		if($return){
			$description = array(
				"action" => "APPROVAL PROGRAM", 
				"before" => $data_log['before'], 
				"after" => $this->Common_model->get_record_values("
					cm_card_nmbr 'card number',
					product_id 'product id',".$type_pengajuan.",
					source,
					call_verification_status 'call verification status',
					document_upload_status 'document upload status',
					status 'request status'", 
					$table, 
					"id = '".$data['id_pengajuan']."'",""),
				"status" => "success", 
				"approval" => null);				
			$description = json_encode($description);
			$this->Common_model->data_logging('APPROVAL', 'APPROVAL PROGRAM', 'SUCCESS', $description);

			$data = array("success" => true, "message" => "Save Approval Success!" , 'return'=>$return , 'isFinish'=>$isFinish , 'finishMsg'=>$finishMsg);
		}else{
			$data = array("success" => false, "message" => "Save Approval Failed!" , 'return'=>$return, 'isFinish'=>$isFinish, 'finishMsg'=>$finishMsg);
		}
		// echo json_encode($data);
		$newCsrfToken = csrf_hash();
		return $this->response->setStatusCode(200)->setJSON(array_merge($data, ['newCsrfToken' => $newCsrfToken]));

	}
}