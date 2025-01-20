<?php 
namespace App\Modules\DashboardInteractive\Controllers;

use CodeIgniter\Cookie\Cookie;
use Config\Database;

class DashboardInteractive extends \App\Controllers\BaseController
{
	
	function __construct()
	{
		$session = \Config\Services::session();

	}

	function index(){
		echo "rerer";
		// return view('\App\Modules\DetailAccount\Views\Detail_account_view', $data);
	}

	function gat_data_class_wallboard(){
		$DBDRIVER = $this->db->DBDriver;
		if ($DBDRIVER == 'SQLSRV') {
			$sql="SELECT *, FLOOR((worked * 1.0 / total) * 100) AS progress
				FROM 
					(
					SELECT 
						b.classification_id, 
						b.classification_name, 
						(SELECT COUNT(*) 
						FROM acs_predictive_queue 
						WHERE STATUS IN (2) AND class_id = a.class_id) AS worked,
						
						(SELECT COUNT(*) 
						FROM report_pred_call_history 
						WHERE call_status = 'Not Answered' 
						AND class_id = a.class_id 
						AND CAST(status_time AS DATE) = CAST(GETDATE() AS DATE)) AS no_answer,
						
						COUNT(a.contract_number) AS total,
						
						(SELECT COUNT(*) 
						FROM cms_contact_history 
						WHERE class_id = a.class_id 
						AND action_code = 'PTP' 
						AND CAST(created_time AS DATE) = CAST(GETDATE() AS DATE)) AS ptp,
						
						(SELECT COUNT(*) 
						FROM cms_contact_history 
						WHERE class_id = a.class_id 
						AND call_status = 'CTC' 
						AND action_code != 'PTP' 
						AND CAST(created_time AS DATE) = CAST(GETDATE() AS DATE)) AS no_ptp,
						
						GETDATE() AS update_time
					FROM acs_predictive_queue a
					JOIN cms_classification b ON a.class_id = b.classification_id
					GROUP BY a.class_id, b.classification_id, b.classification_name
					) AS tbl;";
		}else{
			$sql = "SELECT * , FLOOR((worked/total)*100)  AS progress FROM 
				(
				SELECT 
					b.classification_id, 
					b.classification_name , 
					(SELECT COUNT(*) FROM acs_predictive_queue WHERE STATUS IN (2) AND class_id=a.class_id ) worked,
					(SELECT COUNT(*) FROM report_pred_call_history WHERE call_status = 'Not Answered' AND class_id=a.class_id and date(status_time)=curdate() ) no_answer,
					COUNT(a.contract_number) total ,
					(SELECT COUNT(*) FROM cms_contact_history WHERE class_id=a.class_id AND action_code = 'PTP' AND DATE(created_time)=CURDATE() ) ptp,
					(SELECT COUNT(*) FROM cms_contact_history WHERE class_id=a.class_id AND call_status = 'CTC' AND action_code != 'PTP' AND DATE(created_time)=CURDATE() ) no_ptp,
					now() update_time
				FROM acs_predictive_queue a
				JOIN cms_classification b ON a.class_id=b.classification_id
				-- WHERE DATE(a.createdDatetime) = CURDATE()
				GROUP BY a.class_id
				) AS tbl";
		}
		

		
		
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}

	function gat_data_class_wallboard_json(){
		echo json_encode($this->gat_data_class_wallboard());
	}

	function class_wallboard(){
		$data['data_class_wallboard'] = $this->gat_data_class_wallboard();
		$allSessionData = $this->session->get();
		// var_dump($data);die;

		// echo '<pre>';
		// print_r($allSessionData);
		// echo '</pre>';
		// die;
		// $language = json_decode($this->session->userdata('LANGUAGE'));

		// $data["language"] 			= $language->label;
		return view('\App\Modules\DashboardInteractive\Views\Class_wallboard_view', $data);

	}

	function get_data_summary(){
		$DBDRIVER = $this->db->DBDriver;
		if ($DBDRIVER == 'SQLSRV') {
			$data['total_contact'] = $this->Common_model->get_record_value('count(distinct account_no)','cms_contact_history',"CAST(created_time AS DATE) = CAST(GETDATE() AS DATE) and call_status= 'CTC' ");
			$data['total_ncontact'] = $this->Common_model->get_record_value('count(distinct account_no)','cms_contact_history',"CAST(created_time AS DATE) = CAST(GETDATE() AS DATE) and call_status= 'NTC' ");
			$data['total_untouch'] = $this->db->query("SELECT contract_number 
														FROM acs_predictive_queue 
														WHERE contract_number NOT IN (SELECT DISTINCT account_no FROM cms_contact_history WHERE CAST(created_time AS DATE) = CAST(GETDATE() AS DATE))")->getNumRows();
	 
			$data['total_ptp'] = $this->Common_model->get_record_value('count(distinct account_no)','cms_contact_history','lov3=\'PTP\'  and  CAST(created_time AS DATE) = CAST(GETDATE() AS DATE) ');
			$data['total_ptp_active'] = $this->Common_model->get_record_value('count(distinct account_no)','cms_contact_history','lov3=\'PTP\'  and CAST(ptp_date AS DATE) = CAST(GETDATE() AS DATE) and CAST(created_time AS DATE) = CAST(GETDATE() AS DATE) ');
			$data['total_broken_ptp'] = $this->Common_model->get_record_value('count(distinct cm_card_nmbr)','tmp_last_payment_checking','ptp_date = CAST(GETDATE() AS DATE) and final_status= \'BROKEN\' ');
			$data['total_keep_ptp'] = $this->Common_model->get_record_value('count(distinct cm_card_nmbr)','tmp_last_payment_checking','ptp_date = CAST(GETDATE() AS DATE) and final_status= \'KEEP\' ');
		}else{
			$data['total_contact'] = $this->Common_model->get_record_value('count(distinct account_no)','cms_contact_history','date(created_time) = curdate() and call_status= "CTC" ');
			$data['total_ncontact'] = $this->Common_model->get_record_value('count(distinct account_no)','cms_contact_history','date(created_time) = curdate() and call_status= "NTC" ');
			$data['total_untouch'] = $this->db->query("SELECT contract_number 
														FROM acs_predictive_queue 
														WHERE contract_number NOT IN (SELECT DISTINCT account_no FROM cms_contact_history WHERE DATE(created_time)=CURDATE())")->getNumRows();
	 
			$data['total_ptp'] = $this->Common_model->get_record_value('count(distinct account_no)','cms_contact_history','lov3="PTP"  and  date(created_time) = curdate() ');
			$data['total_ptp_active'] = $this->Common_model->get_record_value('count(distinct account_no)','cms_contact_history','lov3="PTP"  and  date(ptp_date) >= curdate() and date(created_time)=curdate() ');
			$data['total_broken_ptp'] = $this->Common_model->get_record_value('count(distinct cm_card_nmbr)','tmp_last_payment_checking','ptp_date = curdate() and final_status= "BROKEN" ');
			$data['total_keep_ptp'] = $this->Common_model->get_record_value('count(distinct cm_card_nmbr)','tmp_last_payment_checking','ptp_date = curdate() and final_status= "KEEP" ');
		}
		$data['total_data'] = $this->Common_model->get_record_value('count(*)','acs_predictive_queue','id is not null ');
		
		
		$data['total_persen_ptp'] = ($data['total_contact']*$data['total_ptp'])/100;
		$data['total_repetition'] = 0;
		
		
		// var_dump($builder);die;
		
		$builder = $this->cmsMysql->table('ecentrix.ecentrix_agent_track');
		$builder->select('count(*) AS total_dialed')
		->where('date(start_time) = curdate()');
		$data['total_dialed'] = $builder->get()->getResultArray()[0]['total_dialed'];
		
		$builder = $this->cmsMysql->table('ecentrix.ecentrix_session_log');
		$builder->select('count(*) AS total_connect')
			->where('last_status in ("9","10")', null, false)
			->where('date(start_time) = curdate()');
		$data['total_connect'] = $builder->get()->getResultArray()[0]['total_connect'];
		
		$builder->select('count(*) AS total_unconnect')
			->where("last_status in ('9','10')")
			->where('date(start_time) = curdate()');
		$data['total_unconnect'] = $builder->get()->getResultArray()[0]['total_unconnect'];

		
		$data['update_time'] = date('Y-m-d H:i:s');
		return $data;
	}

	function get_data_summary_json(){
		echo json_encode($this->get_data_summary());
	}

	function summary_wallboard(){
		$data = $this->get_data_summary();

		// $language = json_decode($this->session->userdata('LANGUAGE'));

		// $data["language"] 			= $language->label;
		return view('\App\Modules\DashboardInteractive\Views\dashboard_card_accumulation_view', $data);

	}

	function get_data_telephony_activity(){
		$DBDRIVER = $this->db->DBDriver;
		if ($DBDRIVER == 'SQLSRV') {
			$data['total_agent_login'] = $this->Common_model->get_record_value('count(distinct id)','cc_user',"group_id = 'COLLECTOR' and is_active='1' and CAST(last_login AS DATE) = CAST(GETDATE() AS DATE) and login_status='1' ");
			$data['total_agent_logout'] = $this->Common_model->get_record_value('count(distinct id)','cc_user',"group_id = 'COLLECTOR' and is_active='1' and CAST(last_login AS DATE) = CAST(GETDATE() AS DATE) and login_status='0' ");
			$subQuery = $this->db->table('cms_contact_history')
			->select('COUNT(*)')
			->where('created_by = a.id')
			->where('action_code', 'PTP')
			->where("CAST(created_time AS DATE) = CAST(GETDATE() AS DATE)", null, false)
			->getCompiledSelect();
		}else{
			$data['total_agent_login'] = $this->Common_model->get_record_value('count(distinct id)','cc_user',"group_id = 'COLLECTOR' and is_active='1' and date(last_login)=curdate() and login_status='1' ");
			$data['total_agent_logout'] = $this->Common_model->get_record_value('count(distinct id)','cc_user',"group_id = 'COLLECTOR' and is_active='1' and date(last_login)=curdate() and login_status='0' ");
			$subQuery = $this->db->table('cms_contact_history')
			->select('COUNT(*)')
			->where('created_by = a.id')
			->where('action_code', 'PTP')
			->where("DATE(created_time) = CURDATE()", null, false)
			->getCompiledSelect();
		}
		
		$data['total_agent'] = $this->Common_model->get_record_value('count(distinct id)','cc_user',"group_id = 'COLLECTOR' and is_active='1' ");
		
		$builder = $this->cmsMysql->table('ecentrix.ecentrix_agent_track');
		$builder->select('sec_to_time(ROUND(avg(duration/1000))) AS avg_talktime')
			->where('current_status', '7')
			->where('start_time >=', date('Y-m-d').' 00:00:00');
		$data['avg_talktime'] = $builder->get()->getResultArray()[0]['avg_talktime'];
		
		$builder->select('sec_to_time(ROUND(avg(duration/1000))) AS avg_acw')
		->where('current_status', '13')
		->where('start_time >=', date('Y-m-d').' 00:00:00');
		$data['avg_acw'] = $builder->get()->getResultArray()[0]['avg_acw'];
		
		$builder->select('sec_to_time(ROUND(avg(duration/1000))) AS avg_wrp')
		->where('start_time >=', date('Y-m-d').' 00:00:00');
		$data['avg_wrp'] = $builder->get()->getResultArray()[0]['avg_wrp'];
		
		if($data['avg_acw']==''){
			$data['avg_acw'] = '00:00:00';
		}
		if($data['avg_talktime']==''){
			$data['avg_talktime'] = '00:00:00';
		}
		if($data['avg_wrp']==''){
			$data['avg_wrp'] = '00:00:00';
		}
		
		
			
		$query = $this->db->table('cc_user a')
			->select("a.id, a.name, a.image, ($subQuery) as total_ptp", false)
			->where('a.is_active', '1')
			->where("group_id", "COLLECTOR")
			->orderBy("total_ptp", "DESC")
			->orderBy("a.name", "ASC")
			->limit(10)
			->get();
			
		$data['top_ptp_agent'] = $query->getResultArray();
		
		$data['update_time'] = date('Y-m-d H:i:s');
		// die;

		return $data;
		
	}
	function get_data_telephony_activity_json(){
		echo json_encode($this->get_data_telephony_activity());
	}

	function telephony_wallboard(){
		$data = $this->get_data_telephony_activity();
		$data['LIST_AGENT'] = json_encode($this->Common_model->get_record_list('id value , name as item','cc_user',"group_id = 'COLLECTOR' and is_active='1' ",'name asc'));

		// $language = json_decode($this->session->userdata('LANGUAGE'));

		// $data["language"] 			= $language->label;
		
		// $this->load->view('telephony_activity_view',$data); //card
		return view('\App\Modules\DashboardInteractive\Views\Telephony_activity_view', $data);

	}

	function dashboard_sum_agent_login()
	{
		$DBDRIVER = $this->db->DBDriver;

		// echo $DBDRIVER;die;
		$builder = $this->db->table('cc_app_log');

		// Menyiapkan array jam
		$jamArray = [
			'07:00:00' => '07:59:59',
			'08:00:00' => '08:59:59',
			'09:00:00' => '09:59:59',
			'10:00:00' => '10:59:59',
			'11:00:00' => '11:59:59',
			'12:00:00' => '12:59:59',
			'13:00:00' => '13:59:59',
			'14:00:00' => '14:59:59',
			'15:00:00' => '15:59:59',
			'16:00:00' => '16:59:59',
			'17:00:00' => '17:59:59',
			'18:00:00' => '18:59:59',
			'19:00:00' => '19:59:59',
			'20:00:00' => '20:59:59',
			'21:00:00' => '21:59:59',
			'22:00:00' => '22:59:59'
		];

		// Menyimpan hasil untuk tiap jam
		$results = [];

		if ($DBDRIVER == 'SQLSRV') {
			foreach ($jamArray as $startTime => $endTime) {
				// Reset query builder
				$builder->resetQuery();
				
				// Membuat query untuk setiap jam
				$builder->select('cc_app_log.created_by')
					->join('cc_user', 'cc_app_log.created_by = cc_user.id')
					->where('module', 'login')
					->where('CONVERT(DATE, cc_app_log.created_time)', 'CONVERT(DATE, GETDATE())', false) // Tanggal hari ini
					->where('CONVERT(TIME, cc_app_log.created_time) >=', $startTime)
					->where('CONVERT(TIME, cc_app_log.created_time) <=', $endTime)
					->groupBy('cc_app_log.created_by');
			
				// Menjalankan query dan menyimpan hasilnya
				$query = $builder->get();
				$results[$startTime] = $query->getResultArray();
			}
			
		}else{
			foreach ($jamArray as $startTime => $endTime) {
				// Reset query builder
				$builder->resetQuery();
				
				// Membuat query untuk setiap jam
				$builder->select('cc_app_log.created_by')
					->join('cc_user', 'cc_app_log.created_by = cc_user.id')
					->where('module', 'login')
					->where('DATE(cc_app_log.created_time)', 'CURDATE()', false) // Menggunakan 'CURDATE()' untuk mendapatkan tanggal hari ini
					->where('TIME(cc_app_log.created_time) >=', $startTime)
					->where('TIME(cc_app_log.created_time) <=', $endTime)
					->groupBy('cc_app_log.created_by');
			
				// Menjalankan query dan menyimpan hasilnya
				$query = $builder->get();
				$results[$startTime] = $query->getResultArray();
			}
		}

		$builder = $this->db->table('cc_user');
		$builder->select('id, name');
		$dataUser = $builder->get()->getResultArray();
		
		$builder = $this->cmsMysql->table('ecentrix.ecentrix_session_log');
		$builder->select('agent_id, "" AS name, direction, start_time, COUNT(*) AS jumlah')
				->where('CAST(start_time AS DATE)', date('Y-m-d'))
				->groupBy('agent_id, direction, start_time')
				->limit(10); 
		$data["top10"] = $builder->get()->getResultArray();

		foreach ($dataUser as $key => $value) {
			foreach ($data["top10"] as $key2 => $value2) {
				if($value['id'] == $value2['agent_id']){
					$data["top10"][$key2]['name'] = $value['name'];
				}
			}
		}
		


		$data["jam7"] = count($results['07:00:00']);
		$data["jam8"] = count($results['08:00:00']);
		$data["jam9"] = count($results['09:00:00']);
		$data["jam10"] = count($results['10:00:00']);
		$data["jam11"] = count($results['11:00:00']);
		$data["jam12"] = count($results['12:00:00']);
		$data["jam13"] = count($results['13:00:00']);
		$data["jam14"] = count($results['14:00:00']);
		$data["jam15"] = count($results['15:00:00']);
		$data["jam16"] = count($results['16:00:00']);
		$data["jam17"] = count($results['17:00:00']);
		$data["jam18"] = count($results['18:00:00']);
		$data["jam19"] = count($results['19:00:00']);
		$data["jam20"] = count($results['20:00:00']);
		$data["jam21"] = count($results['21:00:00']);
		$data["jam22"] = count($results['22:00:00']);

		return view('\App\Modules\DashboardInteractive\Views\dashboard_accumulation_view', $data);

		// $this->load->view('dashboard_accumulation_view', $data);
	}
}