<?php	
namespace App\Models;
use CodeIgniter\Model;

Class Common_model Extends Model
{

	function get_running_text(): string
	{

		$builder = $this->db->table('cc_running_text');
		$builder->select('message')
				->where('start_date <=', date('Y-m-d H:i:s'))
				->where('end_date >=', date('Y-m-d H:i:s'))
				->where('is_active', '1')
				->orderBy('start_date, created_time, updated_time', 'ASC');

		$query   = $builder->get();
		$message = "";
		$jumlah  = $query->getNumRows();

		if ($jumlah > 0) {
			foreach ($query->getResult() as $row) {
				if ($jumlah % 2 == 0) {
					if (!empty($message)) {
						$message .= " <img src='" . base_url('assets/images/navigate_left2.png') . "'> 
									<span style='color:orange'>" . $row->message . '</span>';
					} else {
						$message .= "<span style='color:white'>" . $row->message . '</span>';
					}
				} else {
					if (!empty($message)) {
						$message .= " <img src='" . base_url('assets/images/navigate_left2.png') . "'> 
									<span style='color:yellow'>" . $row->message . '</span>';
					} else {
						$message .= "<span style='color:yellow'>" . $row->message . '</span>';
					}
				}
				$jumlah--;
			}
		} else {
			$message = 'Welcome To Helpdesk Contact Center.';
		}

		return $message;
	}
	
	function get_record_value($fieldName, $tableName, $criteria, $param = array())
	{
	
		// $builder = $this->db->table($tableName);
		// $builder->select($fieldName)->where($criteria);
		
		// $query = $builder->get();

		// if ($query->getNumRows() > 0) {
		// 	$data = $query->getRowArray();
			
		// 	return array_pop($data);
		// }

		// return null;

		$sql = "SELECT $fieldName FROM $tableName WHERE $criteria";


		if(is_null($param)){
			$query = $this->db->query($sql);
			
		}else{

			$query = $this->db->query($sql, $param);
		}

		if ($query->getNumRows() > 0) {
			$data = $query->getRowArray();
			return array_pop($data);
		}

		return null;
	}

	function get_record_value_crm($fieldName, $tableName, $criteria, $param = array())
	{
	 	$this->crm  = db_connect('crm');
		$sql = "SELECT $fieldName FROM $tableName WHERE $criteria";

		if(is_null($param)){
			$query = $this->crm->query($sql);
			
		}else{
			$query =$this->crm->query($sql, $param);
		}

		if ($query->getNumRows() > 0) {
			$data = $query->getRowArray();
			return array_pop($data);
		}


		return null;
	}

	function data_logging($module, $action, $result, $description)
	{
		

		$data = array(
			'id'					=> uuid(),
			'ip_address'	=> $_SERVER["REMOTE_ADDR"],
			'module'			=> $module,
			'action'			=> $action,
			'result'			=> $result,
			'description'	=> $description,
			'created_by'	=> session()->get('USER_ID'),
			'created_time' => date('Y-m-d H:i:s')
		);
		$builder = $this->db->table('cc_app_log');
		$builder->insert($data);
		return true;
	}

	function get_record_list($fieldName, $tableName, $criteria, $orderBy , $param=array())
	{	
			$arr_data = array();
		  // Bangun query dasar
		  $sql = "SELECT {$fieldName} FROM {$tableName}";

		  // Tambahkan WHERE jika $criteria ada
		  if ($criteria) {
			  $sql .= " WHERE " . $criteria;
		  }
	  
		  // Tambahkan ORDER BY jika $orderBy ada
		  if ($orderBy) {
			  $sql .= " ORDER BY " . $orderBy;
		  }
	  
		  // Eksekusi query
		  if(empty($param)){
			  $query = $this->db->query($sql);
		  }else{
			  $query = $this->db->query($sql,$param);
		  }
	  
		  // Ambil hasil jika ada data
		  if ($query->getNumRows()) {
			  foreach ($query->getResult() as $row) {
				  $arr_data[$row->value] = $row->item;
			  }
		  }
	  
		  return $arr_data;
	}

	function get_ref_master($fieldName, $tableName, $criteria, $orderBy,$param=array(),$header = TRUE)
	{
		$arrData = array();

		if ($header) {
			$arrData[""] = "[SELECT DATA]";
		}

		$db = $this->db;

		
		$sql = "SELECT {$fieldName} FROM {$tableName}";


		if ($criteria) {
			$sql .= " WHERE " . $criteria;
		}

		// Tambahkan ORDER BY jika ada $orderBy
		if ($orderBy) {
			$sql .= " ORDER BY " . $orderBy;
		}

		// Eksekusi query
		if(empty($param)){
			$query = $this->db->query($sql);
		}else{
			$query = $this->db->query($sql,$param);
		}

		// Proses hasil jika ada data
		if ($query->getNumRows()) {
			foreach ($query->getResult() as $row) {
				$arrData[$row->value] = $row->item;
			}
		}

		return $arrData;	
	}

	function get_record_values($fieldName, $tableName, $criteria)
	{	
		$arr_data = array();
		$sql = "SELECT $fieldName FROM $tableName WHERE $criteria";
		$query =   $this->db->query($sql);
		
		if ($query->getNumRows())
		{
			foreach ($query->getResult() as $row)
			{
		    foreach ($row as $key => $value){
	        $arr_data[$key] = $value;  
				}
			}
		}
		
		return $arr_data;
	}

	function MonthIndo($tgl)
	{
		$tgl = explode(" ", $tgl??'');
		$t = explode("-", $tgl[0]??'');

		$b = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');



		$tanggal = @$t[2];
		$bulan = @$b[$t[1]];
		$tahun = @$t[0];

		$jam = @$tgl[1];
		if ($jam <> '') {
			$jam = explode(":", @$jam??'');
			$jam = @$jam[0] . ':' . @$jam[1];
		}


		$indo = $tanggal . " " . $bulan . " " . $tahun . " " . $jam;

		return $indo;
	}

	function generateCode($inbound_source_id=false)
	{

		$seed = str_split('0123456789');
		shuffle($seed);
		$rand = '';
		foreach (array_rand($seed, 3) as $k) $rand .= $seed[$k];
		$s = strtoupper(md5(uniqid(rand(),true))); 
		$guidText = date('ymdHi').$rand;
		return $guidText;		
	}

	function dateDiff($date1,$date2) {
		$date1 = new \DateTime($date1);
		$date2 = new \DateTime($date2);
	
		// Menghitung selisih antara tanggal saat ini dan password_date
		$interval = $date1->diff($date2);
	
		// Mengembalikan jumlah hari selisih
		return $interval->days;
	}

	function setEcxGroupByClass($class_id){
		helper('cms_ecentrix8_helper');

		$sql = "SELECT a.user_id , c.ecentrix_group
				from acs_agent_assignment a
				JOIN acs_class_work_assignment b on b.outbound_team = a.team
				JOIN acs_dialing_mode_call_status c on c.class_id = b.class_mst_id
				WHERE b.class_mst_id = ? ";
		$arr_agent = $this->db->query($sql,array($class_id))->getResultArray();
		
		$i = 0;
		foreach ($arr_agent as $key=>$value) {
				update_agent_groupid($value['user_id'], $value['ecentrix_group']);
			$i++;
		}

	}

	function setEcxGroupByTeam($team){
		helper('cms_ecentrix8_helper');

		$sql = "SELECT a.user_id , c.ecentrix_group
				from acs_agent_assignment a
				JOIN acs_class_work_assignment b on b.outbound_team = a.team
				JOIN acs_dialing_mode_call_status c on c.class_id = b.class_mst_id
				WHERE a.team = ? ";
		$arr_agent = $this->db->query($sql,array($team))->getResultArray();
		
		$i = 0;
		foreach ($arr_agent as $key=>$value) {
			update_agent_groupid($value['user_id'], $value['ecentrix_group']);
			$i++;
		}

	}
}	
