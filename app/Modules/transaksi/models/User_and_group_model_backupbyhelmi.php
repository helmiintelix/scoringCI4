<?php	if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class User_and_group_model Extends CI_model 
{
	function get_user_group_management_list()
	{
		$aColumns				= array('list_number', 'id', 'name', 'description','level_group','bisnis_unit');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchField			= $this->input->get_post('searchField', true);
		$searchString			= $this->input->get_post('searchString', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			$this->db->protect_identifiers=false;
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		//Search filter function
		if ($sSearch == 'true') {
			$filters = $this->input->get_post('filters', true);
			if($filters){
				//echo "xxx".$filters."vsdv";
				$filters = json_decode($filters);
				$where = '';
				$whereArray = array();
				$rules = $filters->rules;
				$groupOperation = $filters->groupOp;
				foreach($rules as $rule) {
		
					$fieldName = $rule->field;
					$fieldData = $rule->data;
		
						$fieldOperation = $fieldName.' LIKE "%'.$fieldData.'%"';
		
					if($fieldOperation != '') $whereArray[] = $fieldOperation;
				}
				if (count($whereArray)>0) {
					$where .= join(' '.$groupOperation.' ', $whereArray);
				} else {
					$where = '';
				}
			}else{
		
				$ops = array(
				//'eq' => '=', //equal
				'eq' => 'LIKE', // contains
				'ne' => '<>',//not equal
				'lt' => '<', //less than
				'le' => '<=',//less than or equal
				'gt' => '>', //greater than
				'ge' => '>=',//greater than or equal
				'bw' => 'LIKE', //begins with
				'bn' => 'NOT LIKE', //doesn't begin with
				'in' => 'LIKE', //is in
				'ni' => 'NOT LIKE', //is not in
				'ew' => 'LIKE', //ends with
				'en' => 'NOT LIKE', //doesn't end with
				'cn' => 'LIKE', // contains
				'nc' => 'NOT LIKE'	//doesn't contain
				);
		
				foreach ($ops as $key=>$value){
					if ($searchOper==$key) {
						$ops = $value;
					}
				}
				//if($searchOper == 'eq' ) $searchString = $searchString;
				if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
				if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
				if($searchOper == 'eq' || $searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';
		
				$where = "$searchField $ops '$searchString' ";
			}
		}
		$select = array('"" AS list_number', 'a.id','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','a.name', 'a.description','a.level_group', 'IF(a.is_active = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag,a.type_collection');

		// Select Data
		$this->db->from('cc_user_group a');
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->where('id <> "ROOT"');
		$where2 = "flag = '1'";
		//$this->db->where($where2);

		if(!empty($where))
			$this->db->where($where);
		
		$rResult = $this->db->get();
		
		// Data set length after filtering
		$this->db->select('FOUND_ROWS() AS found_rows');
		
		$iFilteredTotal = $this->db->get()->row()->found_rows;
		
		// Total data set length	
		$iTotal = $rResult->num_rows();
		if( $iTotal > 0 )
		{
			$total_pages = ceil($iFilteredTotal/$iDisplayLength);
		}
		else
		{
			$total_pages = 0;
		}		
		
		// Output
		$output = array();
		$list = array();
		$row_number = 0;
		$list_number = ($iDisplayStart-1)*10;
		//$row_number = ($iDisplayStart-1)*10;
		//echo ($iDisplayStart-1)*10;
		foreach($rResult->result_array() as $aRow)
		{
			//$output[] = $aRow;
			//$output['rows'][] = $aRow;
			$list[] = array(
				"id" => $aRow['id'], 
				"cell" => $aRow
			);
			
			$list[$row_number]["cell"]["list_number"] = ($list_number+1).".";
			
			$list_number++;
			$row_number++;
		}		
		
		$output = array(
			'page'	=> $iDisplayStart,
			'total' => $total_pages,
			'rows'	=> $list,
			'records' => $iFilteredTotal
		);	
		echo json_encode($output); 					
	}

	function get_user_group_management_list_temp()
	{
		$aColumns				= array('list_number', 'id', 'name', 'description','level_group', 'is_active','bisnis_unit', 'notes');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchField			= $this->input->get_post('searchField', true);
		$searchString			= $this->input->get_post('searchString', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			$this->db->protect_identifiers=false;
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		//Search filter function
		if ($sSearch == 'true') {
			$filters = $this->input->get_post('filters', true);
			if($filters){
				//echo "xxx".$filters."vsdv";
				$filters = json_decode($filters);
				$where = '';
				$whereArray = array();
				$rules = $filters->rules;
				$groupOperation = $filters->groupOp;
				foreach($rules as $rule) {
		
					$fieldName = $rule->field;
					$fieldData = $rule->data;
		
						$fieldOperation = $fieldName.' LIKE "%'.$fieldData.'%"';
		
					if($fieldOperation != '') $whereArray[] = $fieldOperation;
				}
				if (count($whereArray)>0) {
					$where .= join(' '.$groupOperation.' ', $whereArray);
				} else {
					$where = '';
				}
			}else{
		
				$ops = array(
				//'eq' => '=', //equal
				'eq' => 'LIKE', // contains
				'ne' => '<>',//not equal
				'lt' => '<', //less than
				'le' => '<=',//less than or equal
				'gt' => '>', //greater than
				'ge' => '>=',//greater than or equal
				'bw' => 'LIKE', //begins with
				'bn' => 'NOT LIKE', //doesn't begin with
				'in' => 'LIKE', //is in
				'ni' => 'NOT LIKE', //is not in
				'ew' => 'LIKE', //ends with
				'en' => 'NOT LIKE', //doesn't end with
				'cn' => 'LIKE', // contains
				'nc' => 'NOT LIKE'	//doesn't contain
				);
		
				foreach ($ops as $key=>$value){
					if ($searchOper==$key) {
						$ops = $value;
					}
				}
				//if($searchOper == 'eq' ) $searchString = $searchString;
				if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
				if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
				if($searchOper == 'eq' || $searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';
		
				$where = "$searchField $ops '$searchString' ";
			}
		}
		
		$select = array('"" AS list_number','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','a.id', 'a.name', 'a.description','a.level_group', 'IF(a.is_active = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag, a.notes,a.type_collection');

		// Select Data
		$this->db->from('cc_user_group_tmp a');
//		$this->db->join('cms_bisnis_unit bu','a.bisnis_unit=bu.bisnis_unit_id',"LEFT");
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->where('a.id <> "ROOT"');
		$where2 = "a.flag = '0'";
		//$this->db->where($where2);
	$this->db->where('a.flag','0');
		if(!empty($where))
			$this->db->where($where);
		
		$rResult = $this->db->get();
		
		// Data set length after filtering
		$this->db->select('FOUND_ROWS() AS found_rows');
		
		$iFilteredTotal = $this->db->get()->row()->found_rows;
		
		// Total data set length	
		$iTotal = $rResult->num_rows();
		if( $iTotal > 0 )
		{
			$total_pages = ceil($iFilteredTotal/$iDisplayLength);
		}
		else
		{
			$total_pages = 0;
		}		
		
		// Output
		$output = array();
		$list = array();
		$row_number = 0;
		$list_number = ($iDisplayStart-1)*10;
		//$row_number = ($iDisplayStart-1)*10;
		//echo ($iDisplayStart-1)*10;
		foreach($rResult->result_array() as $aRow)
		{
			//$output[] = $aRow;
			//$output['rows'][] = $aRow;
			$list[] = array(
				"id" => $aRow['id'], 
				"cell" => $aRow
			);
			
			$list[$row_number]["cell"]["list_number"] = ($list_number+1).".";
			
			$list_number++;
			$row_number++;
		}		
		
		$output = array(
			'page'	=> $iDisplayStart,
			'total' => $total_pages,
			'rows'	=> $list,
			'records' => $iFilteredTotal
		);	
		echo json_encode($output); 					
	}
	
	/*
		function save_system_access_authority_group_set($user_data)
	{
		//log_message('debug',__METHOD__);
		
		$this->db->where('id', $user_data["id"]);
		$return = $this->db->update('cc_user_group', $user_data); 
		
		return $return;
	}
	*/
	
	//add user baru
	function save_user_group_add($user_data)
	{
		//log_message('debug',__METHOD__);
		//if($user_data["is_active"] == 0){
			//echo "User not active";
			//echo json_encode(array("success" => false, "msg" => "User not active"));
		//}
		//else{
			$return = $this->db->replace('cc_user_group_tmp', $user_data); 
			return $return;
		//}
	}

	function get_user_level($level)
	{
		$query = "SELECT level, level_group from cc_user_group where id=?";
		$binds = array("id" => $level);

		$res = $this->db->query($query, $binds);
		$raw = $res->result_array();
		if($raw){
				$data = array("success" => true, "message" => "Load data berhasil.", "data" => $raw[0]);
		}else{
					$data = array("success" => false, "message" => "Tidak ada data.");
		}
    	echo json_encode($data);				
	}
	
	function get_report_to_list($level)
	{
		$query = "SELECT c.id AS VALUE, c.name AS NAME from cc_user_group b JOIN cc_user c ON b.id = c.group_id where b.level=?";
		$binds = array("level" => $level);

		$res = $this->db->query($query, $binds);
		$raw = $res->result_array();
		if($raw){
				$data = array("success" => true, "message" => "Load data berhasil.", "data" => $raw);
		}else{
					$data = array("success" => false, "message" => "Tidak ada data.");
		}
    	echo json_encode($data);				
	}

	function get_area_list($reference)
	{
		$query = "SELECT kcu_list from cms_kcu where kcu_id=?";
		$binds = array("kcu_id" => $reference);

		$res = $this->db->query($query, $binds);
		$list_kcu = "";
		$raw = $res->result_array();
		//var_dump($raw);
		$kcu ="";
		$str_kcu="";
		if($raw){
			$ar_kcu = explode(";",$raw[0]["kcu_list"]);
			$str_kcu = str_replace(";","','",$raw[0]["kcu_list"]);
			
			$this->db->select('kcu_id,kcu_name');
			$this->db->from('cms_kcu_cabang');	
			
			$this->db->where_in('kcu_id',$ar_kcu);
				
			$rResult = $this->db->get();
			
			foreach($rResult->result_array() as $aRow)
			{
				$list_kcu .= '<li><div class="checkbox"><label><input type="checkbox" class="ace" value="'. $aRow['kcu_id'].'" id="'. $aRow['kcu_id'].'" name="kcu-list[]" /><span class="lbl"> '. $aRow['kcu_id'] .' - '.$aRow['kcu_name'].'</span></label></div></li>';
				$kcu = $aRow['kcu_id'];
			}
		}

		/*
		$query = "SELECT distinct area_id AS id, concat(area_id,' - ',area_name) AS value FROM cms_area_kcu a join tmp_group_kcu_to_kcu b on (a.kcu_id = b.kcu_id) 
		join cms_kcu c on (b.group_kcu_id = c.kcu_id) WHERE a.flag = '1' AND a.flag_tmp = '1' AND c.kcu_id =? 
		ORDER BY area_name ASC";
		$binds = array("kcu_id" => $reference);
		*/ 

		$query = "SELECT distinct area_id AS id, CONCAT(area_id,' - ',area_name) AS value FROM cms_area_kcu a JOIN cms_kcu b ON (a.kcu_id = b.kcu_list) JOIN tmp_group_kcu_to_kcu c ON (a.kcu_id = c.kcu_id) WHERE a.flag = '1' AND a.flag_tmp = '1' AND b.kcu_id =? ORDER BY area_name ASC";
		$binds = array("kcu_id" => $reference);

		$res = $this->db->query($query, $binds);
		$raw = $res->result_array();
		
		$data = array("success" => true, "message" => "Load data berhasil.", "data" => $raw,"list_kcu"=>$list_kcu);

    echo json_encode($data);				
	}

	function kcu_cabang_user($kcu,$kcu_cabang)
	{
		$query = "SELECT kcu_list from cms_kcu where kcu_id=?";
		$binds = array("kcu_id" => $kcu);

		$res = $this->db->query($query, $binds);
		$list_kcu = "";
		$raw = $res->result_array();
		//var_dump($raw);
		if($raw){
			$ar_kcu = explode(";",$raw[0]["kcu_list"]);
			$ar_kcu_cabang = explode(";",$kcu_cabang);
			$this->db->select('kcu_id,kcu_name');
			$this->db->from('cms_kcu_cabang');	
			
			$this->db->where_in('kcu_id',$ar_kcu);
				
			$rResult = $this->db->get();
			$list_kcu="<ul>";
			foreach($rResult->result_array() as $aRow)
			{
				if(in_array($aRow['kcu_id'],$ar_kcu_cabang))
					$list_kcu .= '<li><div class="checkbox"><label><input type="checkbox" class="ace" checked value="'. $aRow['kcu_id'].'" id="'. $aRow['kcu_id'].'" name="kcu-list[]" /><span class="lbl"> '. $aRow['kcu_id'] .' - '.$aRow['kcu_name'].'</span></label></div></li>';
				else
					$list_kcu .= '<li><div class="checkbox"><label><input type="checkbox" class="ace" value="'. $aRow['kcu_id'].'" id="'. $aRow['kcu_id'].'" name="kcu-list[]" /><span class="lbl"> '. $aRow['kcu_id'] .' - '.$aRow['kcu_name'].'</span></label></div></li>';
	
			}
			$list_kcu.="</ul>";
		}
		return $list_kcu;
	}

	function get_kcu_list_per_bu($reference)
	{
/*		$query = "SELECT kcu_id AS id, concat(kcu_id,' - ',kcu_name) AS value FROM cms_kcu WHERE flag = '1' AND bisnis_unit=? ORDER BY kcu_id ASC";
		$binds = array("bisnis_unit" => $reference);

		$res = $this->db->query($query, $binds);
		$raw = $res->result_array();
		
		$data = array("success" => true, "message" => "Load data berhasil.", "data" => $raw);

    echo json_encode($data);				
*/
			$query = "SELECT kcu_id AS id, concat(kcu_id,' - ',kcu_name) AS value FROM cms_kcu WHERE flag = '1' AND bisnis_unit=? ORDER BY kcu_id ASC";
		$binds = array("bisnis_unit" => $reference);
		$res = $this->db->query($query, $binds);
		$raw = $res->result_array();
		return $raw;

	}

	function get_user_group_per_bu($reference)
	{
			$query = "SELECT id, concat(id,' - ',name) AS value FROM cc_user_group WHERE flag = '1' AND is_active = '1' and bisnis_unit=? ORDER BY name ASC";
		$binds = array("bisnis_unit" => $reference);
		$res = $this->db->query($query, $binds);
		$raw = $res->result_array();
		return $raw;
	}
	
	function save_user_group_edit($user_data)
	{
		//log_message('debug',__METHOD__);
		
		// $return = $this->db->update('cc_user_group', $user_data); 
		
		$this->db->where('id', $user_data["id"]);
		$return = $this->db->replace('cc_user_group_tmp', $user_data); 
		
		return $return;
	}

	function save_user_group_edit_temp($user_data)
	{
		//log_message('debug',__METHOD__);

		//baru
		//$this->db->where('id', $user_data['id']);
		//$return = $this->db->delete('cc_user_group'); 
		
		$this->db->query("replace into cc_user_group select * from cc_user_group_tmp where id='".$user_data["id"] ."'");
		//$this->db->where('id', $user_data["id"]); 
		//$return = $this->db->update('cc_user_group', $user_data); 
		$this->db->where('id', $user_data["id"]);
		
		$return = $this->db->delete('cc_user_group_tmp'); 
		
		$this->db->where('id', $user_data['id']);
		$this->db->set('flag', '1');
		$return = $this->db->update('cc_user_group'); 
		
		return $return;
	}

	function delete_user_group($user_data)
	{
		//log_message('debug',__METHOD__);
		
		$this->db->where('id', $user_data["id"]);
		$return = $this->db->delete('cc_user_group_tmp'); 
		//$return = $this->db->delete('cc_user_group'); 
		//$return = $this->db->update('cc_user_group_tmp', array('flag' => '2')); 
		
		return $return;
	}
	
	function direct_delete_user_group($user_data)
	{
		//log_message('debug',__METHOD__);
		
		$this->db->where('id', $user_data["id"]);
		//$return = $this->db->delete('cc_user_group_tmp'); 
		$return = $this->db->delete('cc_user_group'); 
		//$return = $this->db->update('cc_user_group_tmp', array('flag' => '2')); 
		
		return $return;
	}
	function get_report_last_login_list()
	{
		$aColumns				= array('list_number', 'id', 'name', 'group_id',"kcu","area", 'is_active', 'login_status','bisnis_unit', 'is_status','bisnis_unit');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchField			= $this->input->get_post('searchField', true);
		$searchString			= $this->input->get_post('searchString', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if ($iSortCol_0 == 'name'){
			$this->db->order_by("updated_time","desc");
		}
		else{
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}
	
		//$this->db->order_by($iSortCol_0, $iSortingCols);
		
		//Search filter function
		if ($sSearch == 'true') {
			$filters = $this->input->get_post('filters', true);
			if($filters){
				//echo "xxx".$filters."vsdv";
				$filters = json_decode($filters);
				$where = '';
				$whereArray = array();
				$rules = $filters->rules;
				$groupOperation = $filters->groupOp;
				foreach($rules as $rule) {
		
					$fieldName = $rule->field;
					$fieldData = $rule->data;
		
						$fieldOperation = $fieldName.' LIKE "%'.$fieldData.'%"';
		
					if($fieldOperation != '') $whereArray[] = $fieldOperation;
				}
				if (count($whereArray)>0) {
					$where .= join(' '.$groupOperation.' ', $whereArray);
				} else {
					$where = '';
				}
			}else{
		
				$ops = array(
				//'eq' => '=', //equal
				'eq' => 'LIKE', // contains
				'ne' => '<>',//not equal
				'lt' => '<', //less than
				'le' => '<=',//less than or equal
				'gt' => '>', //greater than
				'ge' => '>=',//greater than or equal
				'bw' => 'LIKE', //begins with
				'bn' => 'NOT LIKE', //doesn't begin with
				'in' => 'LIKE', //is in
				'ni' => 'NOT LIKE', //is not in
				'ew' => 'LIKE', //ends with
				'en' => 'NOT LIKE', //doesn't end with
				'cn' => 'LIKE', // contains
				'nc' => 'NOT LIKE'	//doesn't contain
				);
		
				foreach ($ops as $key=>$value){
					if ($searchOper==$key) {
						$ops = $value;
					}
				}
				//if($searchOper == 'eq' ) $searchString = $searchString;
				if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
				if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
				if($searchOper == 'eq' || $searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';
		
				$where = "$searchField $ops '$searchString' ";
			}
		}
		
		//$select = array('"" AS list_number','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','concat(c.kcu_id," - ",c.kcu_name) as group_kcu','a.id', 'a.flag', 'a.name', 'd.name as group_id',"concat(e.kcu_id,' - ',e.kcu_name) as kcu_office","concat(area_id,' - ',area_name) as area", 'IF(a.is_active = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS is_active', 'IF(a.login_status = "1", "<span class=\"label label-sm label-success\">Logged in</span>", "<span class=\"label label-sm label-danger\">Logged out</span>") AS login_status','IF(a.flag_status = "1", "<span class=\"label label-sm label-info\">Approve</span>", IF(a.flag_status = "0", "<span class=\"label label-sm label-pink\">No Status</span>", "<span class=\"label label-sm label-yellow\">Reject</span>")) AS is_status','d.bisnis_unit');
		$select = array('"" AS list_number','DATE_FORMAT(a.last_login,"%d-%b-%Y") last_login','datediff(curdate(),a.last_login)days_last_login','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','a.id', 'a.flag', 'a.name', 'd.name as group_id', 'IF(a.is_active = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS is_active', 'IF(a.login_status = "1", "<span class=\"label label-sm label-success\">Logged in</span>", "<span class=\"label label-sm label-danger\">Logged out</span>") AS login_status', 'IF(a.flag_status = "1", "<span class=\"label label-sm label-info\">Approve</span>", IF(a.flag_status = "0", "<span class=\"label label-sm label-pink\">No Status</span>", "<span class=\"label label-sm label-yellow\">Reject</span>")) AS is_status');
		
		$max_login = $this->common_model->get_record_value("value","aav_configuration","id='REPORT_LAST_LOGIN'","");
		// Select Data
		$this->db->from('cc_user a');
		// $this->db->join('cms_area_branch b','a.area=b.area_id','left');
		// $this->db->join('cms_branch c','a.kcu=c.branch_id ','left');
		//$this->db->join('cms_kcu_cabang e','c.kcu_list=e.kcu_id','left');
		$this->db->join('cc_user_group d','a.group_id=d.id');
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->where('a.is_active <> "2"');
		$this->db->where("datediff(curdate(),a.last_login) > $max_login");
		$where2 = "a.flag = '1'";
		if(!empty($where))
			$this->db->where($where);

		$rResult = $this->db->get();
		
		// Data set length after filtering
		$this->db->select('FOUND_ROWS() AS found_rows');
		
		$iFilteredTotal = $this->db->get()->row()->found_rows;
		
		// Total data set length	
		$iTotal = $rResult->num_rows();
		if( $iTotal > 0 )
		{
			$total_pages = ceil($iFilteredTotal/$iDisplayLength);
		}
		else
		{
			$total_pages = 0;
		}		
		
		// Output
		$output = array();
		$list = array();
		$row_number = 0;
		$list_number = ($iDisplayStart-1)*10;
		//$row_number = ($iDisplayStart-1)*10;
		//echo ($iDisplayStart-1)*10;
		foreach($rResult->result_array() as $aRow)
		{
			//$output[] = $aRow;
			//$output['rows'][] = $aRow;
			$list[] = array(
				"id" => $aRow['id'], 
				"cell" => $aRow
			);
			
			$list[$row_number]["cell"]["list_number"] = ($list_number+1).".";
			
			$list_number++;
			$row_number++;
		}		
		
		$output = array(
			'page'	=> $iDisplayStart,
			'total' => $total_pages,
			'rows'	=> $list,
			'records' => $iFilteredTotal
		);	
		echo json_encode($output); 					
	}

	function get_report_last_login_export_list()
	{
		$aColumns				= array('list_number', 'id', 'name', 'group_id',"kcu","area", 'is_active', 'login_status','bisnis_unit', 'is_status','bisnis_unit');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchField			= $this->input->get_post('searchField', true);
		$searchString			= $this->input->get_post('searchString', true);
		
		
		// Ordering
		$this->db->order_by("a.name","desc");
	
		//$this->db->order_by($iSortCol_0, $iSortingCols);
		
		//Search filter function
		if ($sSearch == 'true') {
			$filters = $this->input->get_post('filters', true);
			if($filters){
				//echo "xxx".$filters."vsdv";
				$filters = json_decode($filters);
				$where = '';
				$whereArray = array();
				$rules = $filters->rules;
				$groupOperation = $filters->groupOp;
				foreach($rules as $rule) {
		
					$fieldName = $rule->field;
					$fieldData = $rule->data;
		
						$fieldOperation = $fieldName.' LIKE "%'.$fieldData.'%"';
		
					if($fieldOperation != '') $whereArray[] = $fieldOperation;
				}
				if (count($whereArray)>0) {
					$where .= join(' '.$groupOperation.' ', $whereArray);
				} else {
					$where = '';
				}
			}else{
		
				$ops = array(
				//'eq' => '=', //equal
				'eq' => 'LIKE', // contains
				'ne' => '<>',//not equal
				'lt' => '<', //less than
				'le' => '<=',//less than or equal
				'gt' => '>', //greater than
				'ge' => '>=',//greater than or equal
				'bw' => 'LIKE', //begins with
				'bn' => 'NOT LIKE', //doesn't begin with
				'in' => 'LIKE', //is in
				'ni' => 'NOT LIKE', //is not in
				'ew' => 'LIKE', //ends with
				'en' => 'NOT LIKE', //doesn't end with
				'cn' => 'LIKE', // contains
				'nc' => 'NOT LIKE'	//doesn't contain
				);
		
				foreach ($ops as $key=>$value){
					if ($searchOper==$key) {
						$ops = $value;
					}
				}
				//if($searchOper == 'eq' ) $searchString = $searchString;
				if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
				if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
				if($searchOper == 'eq' || $searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';
		
				$where = "$searchField $ops '$searchString' ";
			}
		}
		$max_login = $this->common_model->get_record_value("value","aav_configuration","id='REPORT_LAST_LOGIN'","");
		
		$select = array(
		'a.id as "USER ID"', 
		'a.name as "NAMA"', 
		'd.name as "JABATAN"',
		'DATE_FORMAT(a.last_login,"%d-%b-%Y") as "TGL TERAKHIR LOGIN"',
		'datediff(curdate(),a.last_login) as "HARI TERAKHIR LOGIN"',
		'DATE_FORMAT(a.created_time,"%d-%b-%Y") "TANGGAL INPUT"',
		'DATE_FORMAT(a.updated_time,"%d-%b-%Y") "TANGGAL UPDATE"'

		);
		
		// Select Data
		$this->db->from('cc_user a');
		// $this->db->join('cms_area_kcu b','a.area=b.area_id','left');
		// $this->db->join('cms_kcu c','a.kcu=c.kcu_id and a.bisnis_unit=c.bisnis_unit','left');
		// $this->db->join('cms_bisnis_unit bu','bu.bisnis_unit_id=c.bisnis_unit','left');
		//$this->db->join('cms_kcu_cabang e','c.kcu_list=e.kcu_id','left');
		$this->db->join('cc_user_group d','a.group_id=d.id');
		//$this->db->join('cms_kcu AS kcu_group', "kcu_group.kcu_list  = cust.kode_kcu and kcu_group.bisnis_unit='" .$this->session->userdata('PRODUK_GROUP') ."'",'LEFT');
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->where('a.is_active = "1"');
		$this->db->where("datediff(curdate(),a.last_login) > $max_login");
		//$this->db->where('group_id <> "ROOT"'); //di level production, level ROOT tidak boleh ada
		$where2 = "a.flag = '1'";
		//$this->db->where($where2);		
		if(!empty($where))
			$this->db->where($where);

		$rResult = $this->db->get();
		return $rResult->result_array();		
	}

	function get_user_management_list()
	{
		$aColumns				= array('list_number', 'id', 'name', 'group_id',"kcu","area", 'is_active', 'login_status','bisnis_unit', 'is_status','bisnis_unit');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchField			= $this->input->get_post('searchField', true);
		$searchString			= $this->input->get_post('searchString', true);
		$classification			= $this->input->get_post('classification', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if ($iSortCol_0 == 'name'){
			$this->db->order_by("a.id","asc");
		}
		else{
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}
	
		//$this->db->order_by($iSortCol_0, $iSortingCols);
		
		//Search filter function
		if ($sSearch == 'true') {
			$filters = $this->input->get_post('filters', true);
			if($filters){
				//echo "xxx".$filters."vsdv";
				$filters = json_decode($filters);
				$where = '';
				$whereArray = array();
				$rules = $filters->rules;
				$groupOperation = $filters->groupOp;
				foreach($rules as $rule) {
		
					$fieldName = $rule->field;
					$fieldData = $rule->data;
		
						$fieldOperation = $fieldName.' LIKE "%'.$fieldData.'%"';
		
					if($fieldOperation != '') $whereArray[] = $fieldOperation;
				}
				if (count($whereArray)>0) {
					$where .= join(' '.$groupOperation.' ', $whereArray);
				} else {
					$where = '';
				}
			}else{
		
				$ops = array(
				//'eq' => '=', //equal
				'eq' => 'LIKE', // contains
				'ne' => '<>',//not equal
				'lt' => '<', //less than
				'le' => '<=',//less than or equal
				'gt' => '>', //greater than
				'ge' => '>=',//greater than or equal
				'bw' => 'LIKE', //begins with
				'bn' => 'NOT LIKE', //doesn't begin with
				'in' => 'LIKE', //is in
				'ni' => 'NOT LIKE', //is not in
				'ew' => 'LIKE', //ends with
				'en' => 'NOT LIKE', //doesn't end with
				'cn' => 'LIKE', // contains
				'nc' => 'NOT LIKE'	//doesn't contain
				);
		
				foreach ($ops as $key=>$value){
					if ($searchOper==$key) {
						$ops = $value;
					}
				}
				//if($searchOper == 'eq' ) $searchString = $searchString;
				if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
				if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
				if($searchOper == 'eq' || $searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';
		
				$where = "$searchField $ops '$searchString' ";
			}
		}

		$max_failed = $this->common_model->get_record_value("value", "aav_configuration", "id = 'PASSWORD_MAX_FAILED_ATTEMPS'", "");		
		// $select=array("count(*)");
		$select = array('"" AS list_number','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','concat(c.branch_id," - ",c.branch_name) as group_kcu','a.id', 'a.flag', 'a.name', 'd.name as group_id',"a.kcu_list as kcu_office","concat(area_id,' - ',area_name) as area", 'IF(a.is_active = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS is_active', 'IF(a.login_status = "1", "<span class=\"label label-sm label-success\">Logged in</span>", "<span class=\"label label-sm label-danger\">Logged out</span>") AS login_status', 'IF(a.flag_status = "1", "<span class=\"label label-sm label-info\">Approve</span>", IF(a.flag_status = "0", "<span class=\"label label-sm label-pink\">No Status</span>", "<span class=\"label label-sm label-yellow\">Reject</span>")) AS is_status','IF(a.failed_attempt < "'.$max_failed.'", concat("<span class=\"label label-sm label-info\">",a.failed_attempt,"</span>"),concat("<span class=\"label label-sm label-warning\">",a.failed_attempt,"</span>")) failed_attempt','rto.name report_to','a.nik','a.address','a.supervisor_name','a.imei','a.join_date','a.type_collection','a.email','a.handphone','spv.name spv_name');
		
		// Select Data
		$this->db->from('cc_user a');
		$this->db->join('cms_area_branch b','a.area=b.area_id','left');
		$this->db->join('cms_branch c','a.kcu=c.branch_id ','left');
		$this->db->join('cc_user spv','a.supervisor_name=spv.id','left');
		$this->db->join('cc_user rto','a.report_to=rto.id','left');
		//$this->db->join('cms_kcu_cabang e','c.kcu_list=e.kcu_id','left');
		$this->db->join('cc_user_group d','a.group_id=d.id','left');
		//$this->db->join('cms_kcu AS kcu_group', "kcu_group.kcu_list  = cust.kode_kcu and kcu_group.bisnis_unit='" .$this->session->userdata('PRODUK_GROUP') ."'",'LEFT');
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		if ($classification=='not_active'){
			$this->db->where('a.is_active = "0"');
		}elseif ($classification=='failed_attempt'){
			$this->db->where('a.failed_attempt >= (select value from aav_configuration where id = "PASSWORD_MAX_FAILED_ATTEMPS")');
		}else{
			// $this->db->where('a.is_active <> "2"');
			$this->db->where('a.is_active = "1"');
		}
		//$this->db->where('group_id <> "ROOT"'); //di level production, level ROOT tidak boleh ada
		$where2 = "a.flag = '1'";
		//$this->db->where($where2);
		
		if(!empty($where))
			$this->db->where($where);

		$rResult = $this->db->get();
		
		// Data set length after filtering
		$this->db->select('FOUND_ROWS() AS found_rows');
		
		$iFilteredTotal = $this->db->get()->row()->found_rows;
		
		// Total data set length	
		$iTotal = $rResult->num_rows();
		if( $iTotal > 0 )
		{
			$total_pages = ceil($iFilteredTotal/$iDisplayLength);
		}
		else
		{
			$total_pages = 0;
		}		
		
		// Output
		$output = array();
		$list = array();
		$row_number = 0;
		$list_number = ($iDisplayStart-1)*10;
		//$row_number = ($iDisplayStart-1)*10;
		//echo ($iDisplayStart-1)*10;
		foreach($rResult->result_array() as $aRow)
		{
			//$output[] = $aRow;
			//$output['rows'][] = $aRow;
			$list[] = array(
				"id" => $aRow['id'], 
				"cell" => $aRow
			);
			
			$list[$row_number]["cell"]["list_number"] = ($list_number+1).".";
			
			$list_number++;
			$row_number++;
		}		
		
		$output = array(
			'page'	=> $iDisplayStart,
			'total' => $total_pages,
			'rows'	=> $list,
			'records' => $iFilteredTotal
		);	
		echo json_encode($output); 					
	}

	function get_user_management_list_temp()
	{
		$aColumns				= array('list_number', 'id', 'name', 'group_id',"kcu","area", 'is_active', 'login_status','bisnis_unit', 'is_status', 'notes');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchField			= $this->input->get_post('searchField', true);
		$searchString			= $this->input->get_post('searchString', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		 if ($iSortCol_0 == 'concat(c.kcu_id,  c.kcu_name)'){
			 $this->db->order_by("c.kcu_id",$iSortingCols);
		 }
		 else if ($iSortCol_0 == 'concat(area_id, area_name)'){
			 $this->db->order_by("area_id",$iSortingCols);
		 }

		else{
			 $this->db->order_by($iSortCol_0, $iSortingCols);
		}
	
		//$this->db->order_by($iSortCol_0, $iSortingCols);
		
		//Search filter function
		if ($sSearch == 'true') {
			$filters = $this->input->get_post('filters', true);
			if($filters){
				//echo "xxx".$filters."vsdv";
				$filters = json_decode($filters);
				$where = '';
				$whereArray = array();
				$rules = $filters->rules;
				$groupOperation = $filters->groupOp;
				foreach($rules as $rule) {
		
					$fieldName = $rule->field;
					$fieldData = $rule->data;
		
						$fieldOperation = $fieldName.' LIKE "%'.$fieldData.'%"';
		
					if($fieldOperation != '') $whereArray[] = $fieldOperation;
				}
				if (count($whereArray)>0) {
					$where .= join(' '.$groupOperation.' ', $whereArray);
				} else {
					$where = '';
				}
			}else{
		
				$ops = array(
				//'eq' => '=', //equal
				'eq' => 'LIKE', // contains
				'ne' => '<>',//not equal
				'lt' => '<', //less than
				'le' => '<=',//less than or equal
				'gt' => '>', //greater than
				'ge' => '>=',//greater than or equal
				'bw' => 'LIKE', //begins with
				'bn' => 'NOT LIKE', //doesn't begin with
				'in' => 'LIKE', //is in
				'ni' => 'NOT LIKE', //is not in
				'ew' => 'LIKE', //ends with
				'en' => 'NOT LIKE', //doesn't end with
				'cn' => 'LIKE', // contains
				'nc' => 'NOT LIKE'	//doesn't contain
				);
		
				foreach ($ops as $key=>$value){
					if ($searchOper==$key) {
						$ops = $value;
					}
				}
				//if($searchOper == 'eq' ) $searchString = $searchString;
				if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
				if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
				if($searchOper == 'eq' || $searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';
		
				$where = "$searchField $ops '$searchString' ";
			}
		}
		
		$max_failed = $this->common_model->get_record_value("value", "aav_configuration", "id = 'PASSWORD_MAX_FAILED_ATTEMPS'", "");		
		// $select = array('"" AS list_number', 'a.id', 'a.name', 'a.flag', 'DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time',"concat(e.kcu_id,' - ',e.kcu_name) as kcu_office",'d.name as group_id',"concat(c.kcu_id,' - ',c.kcu_name) as kcu","concat(area_id,' - ',area_name) as area", 'IF(a.is_active = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS is_active', 'IF(a.login_status = "1", "<span class=\"label label-sm label-success\">Logged in</span>", "<span class=\"label label-sm label-danger\">Logged out</span>") AS login_status','d.bisnis_unit', 'a.created_time as time', 'IF(a.flag_status = "1", "<span class=\"label label-sm label-info\">Approve</span>", IF(a.flag_status = "0", "<span class=\"label label-sm label-pink\">No Status</span>", "<span class=\"label label-sm label-yellow\">Reject</span>")) AS is_status,report_to','nik');

		$select = array('"" AS list_number', 'a.id', 'a.name', 'a.flag',
		'DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time',
		'DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time', 
		"a.kcu_list as kcu_office",
		'd.name as group_id',
		"concat(c.branch_id,' - ',c.branch_name) as kcu",
		"concat(area_id,' - ',area_name) as area", 
		'IF(a.is_active = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS is_active', 
		'IF(a.login_status = "1", "<span class=\"label label-sm label-success\">Logged in</span>", "<span class=\"label label-sm label-danger\">Logged out</span>") AS login_status',
		'a.created_time as time',
		 'IF(a.flag_status = "1", "<span class=\"label label-sm label-info\">Approve</span>", IF(a.flag_status = "0", "<span class=\"label label-sm label-pink\">No Status</span>", "<span class=\"label label-sm label-yellow\">Reject</span>")) AS is_status',
		 'IF(a.failed_attempt < "'.$max_failed.'", concat("<span class=\"label label-sm label-info\">",failed_attempt,"</span>"),concat("<span class=\"label label-sm label-warning\">",failed_attempt,"</span>")) failed_attempt, a.notes,report_to','nik','a.email','a.handphone','a.supervisor_name','a.imei','a.join_date','a.type_collection','a.created_by');
		
		// Select Data
		$this->db->from('cc_user_tmp a');
		$this->db->join('cms_area_branch b','a.area=b.area_id','left');
		$this->db->join('cms_branch c','a.kcu=c.branch_id' ,'left');
		$this->db->join('cc_user_group d','a.group_id=d.id',"LEFT");
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->where('a.is_active <> "2"');
		$this->db->where('a.flag_status','0');
		$where2 = "a.flag = '0'";
		
		//$this->db->where($where2);

		if(!empty($where))
			$this->db->where($where);
			$this->db->order_by('a.updated_time desc');

		$rResult = $this->db->get();
		
		// Data set length after filtering
		$this->db->select('FOUND_ROWS() AS found_rows');
		
		$iFilteredTotal = $this->db->get()->row()->found_rows;
		
		// Total data set length	
		$iTotal = $rResult->num_rows();
		if( $iTotal > 0 )
		{
			$total_pages = ceil($iFilteredTotal/$iDisplayLength);
		}
		else
		{
			$total_pages = 0;
		}		
		
		// Output
		$output = array();
		$list = array();
		$row_number = 0;
		$list_number = ($iDisplayStart-1)*10;
		//$row_number = ($iDisplayStart-1)*10;
		//echo ($iDisplayStart-1)*10;
		foreach($rResult->result_array() as $aRow)
		{
			//$output[] = $aRow;
			//$output['rows'][] = $aRow;
			$list[] = array(
				"id" => $aRow['id'], 
				"cell" => $aRow
			);
			
			$list[$row_number]["cell"]["list_number"] = ($list_number+1).".";
			
			$list_number++;
			$row_number++;
		}		
		
		$output = array(
			'page'	=> $iDisplayStart,
			'total' => $total_pages,
			'rows'	=> $list,
			'records' => $iFilteredTotal
		);	
		echo json_encode($output); 					
	}

	
	function isExist($id){
		$this->db->from('cc_user');
		$this->db->where(array('id' => $id
								));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	function isExistEdit($id){
		$this->db->from('cc_user_tmp');
		$this->db->where(array('id' => $id,
							   'flag_status'=>'0'
								));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	
	//cc_user_group
	function isExistGroup($id){
		$this->db->from('cc_user_group');
		$this->db->where(array('id' => $id
								));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function save_user_add($user_data)
	{
		//log_message('debug',__METHOD__);
		
		$return = $this->db->insert('cc_user_tmp', $user_data); 
		
		return $return;
	}
	
	function save_user_edit($user_data)
	{
		//log_message('debug',__METHOD__);
		
		//$this->db->where('id', $user_data["id"]);
		//$return = $this->db->update('cc_user_tmp', $user_data); 
		$return = $this->db->insert('cc_user_tmp', $user_data); 
		
		return $return;
	}

	function force_logout($user_data)
	{
		//log_message('debug',__METHOD__);

			$this->db->where('id', $user_data["id"]);
			$return = $this->db->update('cc_user', array('login_status' => '0')); 		
		
		return $return;
	}

	function unlock_user($user_data)
	{
		//log_message('debug',__METHOD__);

			$this->db->where('id', $user_data["id"]);
			$return = $this->db->update('cc_user', array('failed_attempt' => '0')); 		
		
		return $return;
	}

	function save_user_edit_temp($user_data)
	{
		//log_message('debug',__METHOD__);
		/*
		$this->db->where('id', $user_data["id"]);
		$return = $this->db->update('cc_user', $user_data); 
		*/
		// $this->db->query("replace into cc_user select * from cc_user_tmp where id ='".$user_data["id"] ."'");
		// $this->db->where('id', $user_data["id"]);
		// $return = $this->db->delete('cc_user_tmp'); 
		
		//UPDATE BY IQBAL
		$this->db->from('cc_user_tmp');
		$this->db->where('id', $user_data["id"]);
		$this->db->where('flag_status', '0');
		$hasil = $this->db->get();
		
		if ($hasil->num_rows() > 0){
			$this->db->where('id', $user_data["id"]);
			$this->db->update('cc_user_tmp', array('flag_status' => '1')); 
//			$data_tmp = $this->common_model->get_record_values("a.is_active,a.kcu,a.area,a.kcu_list,assignment_type,a.group_id ", "cc_user_tmp a join cms_bisnis_unit b on(a.bisnis_unit = b.bisnis_unit_id) join cc_user_group c on(a.group_id=c.id)", "a.id='".$user_data["id"]."'", "");
//			$data = $this->common_model->get_record_values("a.is_active,a.kcu,a.area,a.kcu_list,assignment_type,level_group,a.group_id ", "cc_user a join cms_bisnis_unit b on(a.bisnis_unit = b.bisnis_unit_id) join cc_user_group c on(a.group_id=c.id)", "a.id='".$user_data["id"]."'", "");
/*			if((@$data_tmp["is_active"] == 0)|| (@$data_tmp["area"] != @$data["area"]) || (@$data_tmp["group_id"] != @$data["group_id"]) || (@$data_tmp["kcu"] != @$data["kcu"])){
				if(@$data["assignment_type"]== "NON_OWNERSHIP"){
					$sql = "update cms_assignment set assigned_fc='',assigned_fc_permanen = '' where assigned_fc = '".$user_data["id"]."'";
				}else{
					$sql = "delete from cms_assignment_agent where agent_id = '".$user_data["id"]."'";
				}
				$this->db->query($sql);
				
			}
*/
			$this->db->query("replace into cc_user select * from cc_user_tmp where id ='".$user_data["id"] ."'");
		 $this->db->where('id', $user_data["id"]);
		 $return = $this->db->delete('cc_user_tmp'); 

		 $tipe = $this->common_model->get_record_value("type_collection", "cc_user", "id = '".$user_data["id"]."' ", "");	
		 $group_id = $this->common_model->get_record_value("group_id", "cc_user", "id = '".$user_data["id"]."' ", "");	
		 $name = $this->common_model->get_record_value("name", "cc_user", "id = '".$user_data["id"]."' ", "");	
		 $password = $this->common_model->get_record_value("password", "cc_user", "id = '".$user_data["id"]."' ", "");	
		 $password_date = $this->common_model->get_record_value("password_date", "cc_user", "id = '".$user_data["id"]."' ", "");	
		 $password_status = $this->common_model->get_record_value("password_status", "cc_user", "id = '".$user_data["id"]."' ", "");
			$is_active = $this->common_model->get_record_value("is_active", "cc_user", "id = '".$user_data["id"]."' ", "");		 
		 
		 $this->cti = $this->load->database('cti',true);
		 // if($tipe=="TeleColl"){
			$profile = '';
			if($group_id=="COLLECTOR"){
				$group_id = 'AGENT';
				$profile = 'AGENT';

				$sql = "REPLACE ecentrix_agent
						SELECT '".$user_data["id"]."' , '".$name."' , null , now()   ";
				$this->cti->query($sql);

			}
			else if($group_id=="SUPERADMIN"){
				$group_id = 'ADMIN';
				$profile = 'ADMIN';
			}
			else if($group_id=="SUPERVISOR"){
				$group_id = 'COORDINATOR';
				$profile = 'ADMIN';
			}
			else if($group_id=="TEAM_LEADER"){
				
				$group_id = 'COORDINATOR';
				$profile = 'COORDINATOR_TYPE_A';
				$sql = "REPLACE ecentrix.ecentrix_agent
						SELECT '".$user_data["id"]."' , '".$name."' , null , now()";
				$this->cti->query($sql);
			}
			
			$this->tele = $this->load->database('tele',true);

			$sql = "REPLACE into acs_user
					SELECT
						'".$user_data["id"]."'	id,
						'".$name."'	first_name,
						null	last_name,
						'".$password."'	password,
						'".$password_date."'	password_date,
						'".$password_status."'	password_status,
						'".$group_id."'	group_id,
						'".$profile."'	group_profile_id,
						NULL	nik,
						null	cd_collector,
						null	cd_sp,
						NULL	spv_id,
						null	contract_number_handling,
						null	ip_address,
						null	extension,
						null	agent_shift,
						'0'	is_login,
						null	last_login,
						'".$is_active."'	is_active,
						'0'	login_attempts,
						null	authority,
						'CMS'	created_by,
						NOW()	created_time,
						NULL	updated_by,
						NULL	updated_time
							
					";
				$this->tele->query($sql);

		 // }

			return true;
		}else{
			
			return false;
		}
		//END UPDATE
	}

	function delete_user($user_data)
	{
		//log_message('debug',__METHOD__);
		
		// $this->db->where('id', $user_data["id"]);
		// $return = $this->db->delete('cc_user_tmp'); 
		
		// return $return;
		
		//UPDATE BY IQBAL
		$this->db->from('cc_user_tmp');
		$this->db->where('id', $user_data["id"]);
		$this->db->where('flag_status', '0');
		$hasil = $this->db->get();
		
		if ($hasil->num_rows() > 0){
			$this->db->where('id', $user_data["id"]);
			$this->db->update('cc_user_tmp', array('flag_status' => '2')); 
			
		//	$this->db->query("replace into cc_user select * from cc_user_tmp where id ='".$user_data["id"] ."'");
			return true;
		}else{
			
			return false;
		}
		//END UPDATE
		
	}
	
	function direct_delete_user($user_data)
	{
		$this->db->where('id', $user_data["id"]);
		$return = $this->db->delete('cc_user_tmp'); 
		
		if ($return){
			$this->db->where('id', $user_data["id"]);
			$return = $this->db->delete('cc_user');
		}
		
		return $return;
	}
	
	function check_user_status($id_user) {
		$bind = array("id" => $id_user);
		$sql = "SELECT id FROM cc_user WHERE id = ? AND is_active = '1'";
		$result = $this->db->query($sql,$bind);
		$data = $result->result_array();
		
		if(count($data)>0) {
			return true;
		}

		return false;
	}
	
	function get_system_access_authority_list()
	{
		$aColumns				= array('list_number', 'id', 'name', 'authority');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchField			= $this->input->get_post('searchField', true);
		$searchString			= $this->input->get_post('searchString', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			$this->db->_protect_identifiers=false;
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		//Search filter function
		if ($sSearch == 'true') {
			$filters = $this->input->get_post('filters', true);
			if($filters){
				//echo "xxx".$filters."vsdv";
				$filters = json_decode($filters);
				$where = '';
				$whereArray = array();
				$rules = $filters->rules;
				$groupOperation = $filters->groupOp;
				foreach($rules as $rule) {
		
					$fieldName = $rule->field;
					$fieldData = $rule->data;
		
						$fieldOperation = $fieldName.' LIKE "%'.$fieldData.'%"';
		
					if($fieldOperation != '') $whereArray[] = $fieldOperation;
				}
				if (count($whereArray)>0) {
					$where .= join(' '.$groupOperation.' ', $whereArray);
				} else {
					$where = '';
				}
			}else{
		
				$ops = array(
				//'eq' => '=', //equal
				'eq' => 'LIKE', // contains
				'ne' => '<>',//not equal
				'lt' => '<', //less than
				'le' => '<=',//less than or equal
				'gt' => '>', //greater than
				'ge' => '>=',//greater than or equal
				'bw' => 'LIKE', //begins with
				'bn' => 'NOT LIKE', //doesn't begin with
				'in' => 'LIKE', //is in
				'ni' => 'NOT LIKE', //is not in
				'ew' => 'LIKE', //ends with
				'en' => 'NOT LIKE', //doesn't end with
				'cn' => 'LIKE', // contains
				'nc' => 'NOT LIKE'	//doesn't contain
				);
		
				foreach ($ops as $key=>$value){
					if ($searchOper==$key) {
						$ops = $value;
					}
				}
				//if($searchOper == 'eq' ) $searchString = $searchString;
				if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
				if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
				if($searchOper == 'eq' || $searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';
		
				$where = "$searchField $ops '$searchString' ";
			}
		}
		
		$select = array('"" AS list_number', 'id', 'name', 'authority');

		// Select Data
		$this->db->from('cc_user');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->where('is_active <> "2"');
		$this->db->where('group_id <> "ROOT"');
		
		
		
		$rResult = $this->db->get();
		
		// Data set length after filtering
		$this->db->select('FOUND_ROWS() AS found_rows');
		
		$iFilteredTotal = $this->db->get()->row()->found_rows;
		
		// Total data set length	
		$iTotal = $rResult->num_rows();
		if( $iTotal > 0 )
		{
			$total_pages = ceil($iFilteredTotal/$iDisplayLength);
		}
		else
		{
			$total_pages = 0;
		}		
		
		// Output
		$output = array();
		$list = array();
		$row_number = 0;
		$list_number = ($iDisplayStart-1)*10;
		//$row_number = ($iDisplayStart-1)*10;
		//echo ($iDisplayStart-1)*10;
		foreach($rResult->result_array() as $aRow)
		{
			//$output[] = $aRow;
			//$output['rows'][] = $aRow;
			$list[] = array(
				"id" => $aRow['id'], 
				"cell" => $aRow
			);
			
			$list[$row_number]["cell"]["list_number"] = ($list_number+1).".";
			
			$list_number++;
			$row_number++;
		}		
		
		$output = array(
			'page'	=> $iDisplayStart,
			'total' => $total_pages,
			'rows'	=> $list,
			'records' => $iFilteredTotal
		);	
		echo json_encode($output); 					
	}
	
	function save_system_access_authority_set($user_data)
	{
		//log_message('debug',__METHOD__);
		
		$this->db->where('id', $user_data["id"]);
		$return = $this->db->update('mcc_user', $user_data); 
		
		return $return;
	}
	
	//authority
	function save_system_access_authority_group_set($user_data)
	{
		//log_message('debug',__METHOD__);
		
		$this->db->where('id', $user_data["id"]);
		$return = $this->db->update('cc_user_group', $user_data); 
		
		return $return;
	}

	function create_uploader_xml_data()
	{
		$xml_file_name = "loanos_user.xml";
		$this->common_model->create_xml_file_from_data($this->config->item('temp_create_path').$xml_file_name, "*", "cc_user", "group_id = 'UPLOADER'", "");
		
		$source_path = $this->config->item('temp_create_path');
		
		$ftp_config['hostname'] = $this->config->item('ftp_hostname');
		$ftp_config['username'] = $this->config->item('ftp_username');
		$ftp_config['password'] = $this->config->item('ftp_password');
		$ftp_config['debug']	= TRUE;
		
		$this->load->library('ftp');
		$this->ftp->connect($ftp_config);
		$this->ftp->upload($source_path.$xml_file_name, $this->config->item('user_upload_path').$xml_file_name, 'ascii');
		//$this->ftp->move($xml_file_name, $this->config->item('user_upload_path').$xml_file_name);
		
		$this->ftp->close();
	}
	
	function user_verification ($user_id, $password)
	{
		$this->db->select('a.*, b.name group_name, b.level_group');
		$this->db->from('cc_user a');
		$this->db->join('cc_user_group b', 'b.id=a.group_id');
		$this->db->where('a.id', $user_id);
		$this->db->where('a.password', $password);	
		$query = $this->db->get();
		
		return $query->row();
	}
	
	function check_current_password ($user_id, $current_password){
		$this->db->select("*", FALSE);
		$this->db->from("cc_user");
		$this->db->where("id", $user_id);
		$this->db->where("password", md5($current_password));
		$query = $this->db->get();
//		$row = $query->row();
			// return $query->row();
			return $query->result_array();
	}

	function check_password_history ($user_id, $current_password){
			$limit = $this->common_model->get_record_value("value", "aav_configuration", "id = 'PASSWORD_HISTORY'", "");		
			$this->db->select("*", FALSE);
			$this->db->from("cc_password_history");
			$this->db->where("user_id", $user_id);
			$this->db->limit($limit);
			$this->db->order_by("created_time","desc");
			$query = $this->db->get();
			foreach($query->result_array() as $aRow){
			 	 // loop through values
			 	 if (md5($current_password) == $aRow["password"]) {
					return false; 	  	
			 	 } 
			 }
			 return true; 
	}
	
	function change_password ($user_id, $new_password,$user_data){
		//$this->db->set("password", md5($new_password));
		$this->db->where("id", $user_id);
		
		$return = $this->db->update("cc_user", $user_data);
		
		$password_data = array(
			"user_id" => $user_data["id"],
			"password" => $user_data["password"],
			"created_time" => $user_data["password_date"]
		);
		$return = $this->db->insert('cc_password_history', $password_data); 	
		return $return;
	}
	
	function get_export_list_user()
	{
		$iDisplayStart = $this->input->get_post('page', true);
        $iDisplayLength = $this->input->get_post('rows', true);
        $iSortCol_0 = $this->input->get_post('sidx', true);
        $iSortingCols = $this->input->get_post('sord', true);
        $sSearch = $this->input->get_post('_search', true);
        $sEcho = $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		$classification			= $this->input->get_post('classification', true);

		$group_kcu 			= $this->input->get_post('group_kcu', true);		
		$area 			= $this->input->get_post('area', true);
		$kcu 			= $this->input->get_post('kcu', true);
		$petugas 			= $this->input->get_post('petugas', true);
		
		if ($iSortCol_0 == 'name'){
			$this->db->order_by("a.id","asc");
		}
		else{
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}
	
		//$this->db->order_by($iSortCol_0, $iSortingCols);
		
		//Search filter function
		if ($sSearch == 'true') {
			$filters = $this->input->get_post('filters', true);
			if($filters){
				//echo "xxx".$filters."vsdv";
				$filters = json_decode($filters);
				$where = '';
				$whereArray = array();
				$rules = $filters->rules;
				$groupOperation = $filters->groupOp;
				foreach($rules as $rule) {
		
					$fieldName = $rule->field;
					$fieldData = $rule->data;
		
						$fieldOperation = $fieldName.' LIKE "%'.$fieldData.'%"';
		
					if($fieldOperation != '') $whereArray[] = $fieldOperation;
				}
				if (count($whereArray)>0) {
					$where .= join(' '.$groupOperation.' ', $whereArray);
				} else {
					$where = '';
				}
			}else{
		
				$ops = array(
				//'eq' => '=', //equal
				'eq' => 'LIKE', // contains
				'ne' => '<>',//not equal
				'lt' => '<', //less than
				'le' => '<=',//less than or equal
				'gt' => '>', //greater than
				'ge' => '>=',//greater than or equal
				'bw' => 'LIKE', //begins with
				'bn' => 'NOT LIKE', //doesn't begin with
				'in' => 'LIKE', //is in
				'ni' => 'NOT LIKE', //is not in
				'ew' => 'LIKE', //ends with
				'en' => 'NOT LIKE', //doesn't end with
				'cn' => 'LIKE', // contains
				'nc' => 'NOT LIKE'	//doesn't contain
				);
		
				foreach ($ops as $key=>$value){
					if ($searchOper==$key) {
						$ops = $value;
					}
				}
				//if($searchOper == 'eq' ) $searchString = $searchString;
				if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
				if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
				if($searchOper == 'eq' || $searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';
		
				$where = "$searchField $ops '$searchString' ";
			}
		}
		
		// $select=array("count(*)");
		$select = array('a.id as EmployeeID','a.id as UserID', 'a.name as Name', 'a.address Description','spv.name as SupervisorName','a.created_time CreationDate', 'a.group_id as Role','a.bucket as bucket_list');

		$this->db->from('cc_user a');
		$this->db->join('cc_user spv','a.report_to = spv.id',"left");
		$this->db->join('cms_area_branch b','a.area=b.area_id','left');
		$this->db->join('cms_branch c','a.kcu=c.branch_id' ,'left');
		$this->db->join('cc_user_group d','a.group_id=d.id',"LEFT");
		if ($classification=='not_active'){
			$this->db->where('a.is_active = "0"');
		}elseif ($classification=='failed_attempt'){
			$this->db->where('a.failed_attempt >= (select value from aav_configuration where id = "PASSWORD_MAX_FAILED_ATTEMPS")');
		}else{
			// $this->db->where('a.is_active <> "2"');
			$this->db->where('a.is_active = "1"');
		}
		$where2 = "a.flag = '0'";
		
		//$this->db->where($where2);

		if(!empty($where))
			$this->db->where($where);
			$this->db->order_by('a.updated_time desc');


		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$rResult = $this->db->get();
		$arr_data = $rResult->result_array();		
		return $rResult->result_array();			
	}
}