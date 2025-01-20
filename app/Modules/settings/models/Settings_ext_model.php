<?php	if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Settings_ext_model Extends CI_model 
{
	function del_bidder()
	{
		$data_id 	 = $this->input->get_post('data_id', true);
		$is_active=$this->common_model->get_record_value('is_active','cms_bidder','bidder_id="'.$data_id.'"');
		
		// var_dump($is_active);die;
		
		if($is_active=="0"){
			$delete = "update cms_bidder set is_active='1' where bidder_id = '".$data_id."'";
			$response = array('success'=>true, 'message' => 'Data has been activated');
		}else{
			$delete = "update cms_bidder set is_active='0' where bidder_id = '".$data_id."'";
			$response = array('success'=>true, 'message' => 'Data has been deactivated');
		}
		$return = $this->db->query($delete);

		if($return){
			return $response;
		}else{
			return array('success'=>false, 'message' => 'data failed to disable');
		}
	}
	function del_mBalai()
	{
		$data_id 	 = $this->input->get_post('data_id', true);
		$is_active=$this->common_model->get_record_value('is_active','cms_balai_lelang','balai_id="'.$data_id.'"');
		
		// var_dump($is_active);die;
		
		if($is_active=="0"){
			$delete = "update cms_balai_lelang set is_active='1' where balai_id = '".$data_id."'";
			$response = array('success'=>true, 'message' => 'Data has been activated');
		}else{
			$delete = "update cms_balai_lelang set is_active='0' where balai_id = '".$data_id."'";
			$response = array('success'=>true, 'message' => 'Data has been deactivated');
		}
		$return = $this->db->query($delete);

		if($return){
			return $response;
		}else{
			return array('success'=>false, 'message' => 'data failed to disable');
		}
	}

	function del_eBalai()
	{
		$data_id 	 = $this->input->get_post('data_id', true);
		$is_active=$this->common_model->get_record_value('is_active','cms_balai_lelang_event','id="'.$data_id.'"');
		
		// var_dump($is_active);die;
		
		if($is_active=="0"){
			$delete = "update cms_balai_lelang_event set is_active='1' where id = '".$data_id."'";
			$response = array('success'=>true, 'message' => 'Data has been activated');
		}else{
			$delete = "update cms_balai_lelang_event set is_active='0' where id = '".$data_id."'";
			$response = array('success'=>true, 'message' => 'Data has been deactivated');
		}
		$return = $this->db->query($delete);

		if($return){
			return $response;
		}else{
			return array('success'=>false, 'message' => 'data failed to disable');
		}
	}

	function reset_angsuran($data)
	{
		$return = $this->db->replace('cms_account_reset_angsuran', $data); 
		return $return;
	}

	function save_bisnis_unit_add($bisnis_unit_data)
	{
		//$return = $this->db->replace('cms_bisnis_unit_tmp', $bisnis_unit_data); 
		$return = $this->db->insert('cms_bisnis_unit_tmp', $bisnis_unit_data); 
		$return = $this->db->insert('cms_bisnis_unit', $bisnis_unit_data); //mohon utk tidak dihapus atau dicomment, 
		//karena utk menghindari bug duplikasi data pada cms_bisnis_unit_tmp. karena ID auto generate mengikuti ID pada cms_bisnis_unit 
		return $return;
	}

	function save_apk($data)
	{
		$return = $this->db->insert('cms_apk_version_tmp', $data); 
		return $return;
	}
	
	function save_bisnis_unit_edit($bu_data)
	{
		$this->db->query("replace into cms_bisnis_unit_tmp select * from cms_bisnis_unit where bisnis_unit_id='".$bu_data["bisnis_unit_id"] ."'");
		
		$this->db->where('bisnis_unit_id', $bu_data["bisnis_unit_id"]);
		$this->db->set('update_time', date('Y-m-d H:i:s'));
		$this->db->set('flag', $bu_data["flag"]);
		$this->db->set('flag_tmp', '0');
		$this->db->set('description', $bu_data["description"]);
		$this->db->set('product_list', $bu_data["product_list"]);
		$this->db->set('assignment_type', $bu_data["assignment_type"]);
		$this->db->set('fe_to', $bu_data["fe_to"]);
		$this->db->set('be_to', $bu_data["be_to"]);
		$return = $this->db->update('cms_bisnis_unit_tmp'); 

		return $return;
	}
	
	function save_bisnis_unit_edit_temp($data)
	{
		/*
		$return = $this->db->delete('cms_kcu_cabang', array('kcu_id' => $kcu_office_data)); 
		
		$this->db->query("replace into cms_kcu_cabang select * from cms_kcu_cabang_tmp where kcu_id='".$kcu_office_data."'");		
		
		$return = $this->db->delete('cms_kcu_cabang_tmp', array('kcu_id' => $kcu_office_data)); 
		
		$this->db->where('kcu_id', $kcu_office_data);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_kcu_cabang');

		$this->db->where('kcu_id', $kcu_office_data);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_kcu_cabang_tmp'); 
	
		return $return;
		*/
		
		$return = $this->db->delete('cms_bisnis_unit', array('bisnis_unit_id' => $data["id"])); 
		$this->db->query("replace into cms_bisnis_unit select * from cms_bisnis_unit_tmp where bisnis_unit_id='".$data["id"]."'");		
		$return = $this->db->delete('cms_bisnis_unit_tmp', array('bisnis_unit_id' => $data["id"])); 
		
		$this->db->where('bisnis_unit_id', $data["id"]);
		$this->db->set('flag_tmp', '1');
		$this->db->set('update_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_bisnis_unit'); 

		return $return;
	}

	function get_kcu_listing(){
		$select = array('kcu_id,REPLACE(kcu_name,"KCP","KCU") AS kcu_name');
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->from('cms_kcu_cabang');	

		if(!empty($where))
			$this->db->where($where);
			
		$rResult = $this->db->get();
		$list = "";
		foreach($rResult->result_array() as $aRow)
		{
			$list .= '<li>
								<div class="checkbox">
									<label>
										<input type="checkbox" class="ace" value="'. $aRow['kcu_id'].'" id="'. $aRow['kcu_id'].'" name="kcu-list[]" />
										<span class="lbl"> '. $aRow['kcu_id'] .' - '.$aRow['kcu_name'].'</span>
									</label>
								</div>
							</li>
							';

		}
		return $list;
	}

	function get_product_list(){
		$this->db->select('product_id,product_name');
		$this->db->from('cms_product_group');	

		if(!empty($where))
			$this->db->where($where);
			
		$rResult = $this->db->get();
		$list = "";
		foreach($rResult->result_array() as $aRow)
		{
			$list .= '<li>
								<div class="checkbox">
									<label>
										<input type="checkbox" class="ace" value="'. $aRow['product_id'].'" id="'. $aRow['product_id'].'" name="product-list[]" />
										<span class="lbl"> '. $aRow['product_id'] .' - '.$aRow['product_name'].'</span>
									</label>
								</div>
							</li>
							';

		}
		return $list;
	}
	function get_kcu_list_checked($data){
		$this->db->select('kcu_id,kcu_name');
		$this->db->from('cms_kcu_cabang');	

		if(!empty($where))
			$this->db->where($where);
			
		$rResult = $this->db->get();
		$list = "";
		foreach($rResult->result_array() as $aRow)
		{
			$list .= '<li>
								<div class="checkbox">
									<label>';
			if(in_array($aRow['kcu_id'],$data)){
				$list .= 	'						<input type="checkbox" class="ace" value="'. $aRow['kcu_id'].'" id="'. $aRow['kcu_id'].'" name="kcu-list[]" checked/>';
			}else{
				$list .= 	'						<input type="checkbox" class="ace" value="'. $aRow['kcu_id'].'" id="'. $aRow['kcu_id'].'" name="kcu-list[]" />';
			}
			$list .= '							<span class="lbl"> '. $aRow['kcu_id'] .' - '.$aRow['kcu_name'].'</span>
									</label>
								</div>
							</li>
							';

		}
		return $list;
	}

	function get_product_list_checked($data){
		$this->db->select('product_id,product_name');
		$this->db->from('cms_product_group');	

		if(!empty($where))
			$this->db->where($where);
			
		$rResult = $this->db->get();
		$list = "";
		foreach($rResult->result_array() as $aRow)
		{
			$list .= '<li>
								<div class="checkbox">
									<label>';
			if(in_array($aRow['product_id'],$data)){
				$list .= 	'						<input type="checkbox" class="ace" value="'. $aRow['product_id'].'" id="'. $aRow['product_id'].'" name="product-list[]" checked/>';
			}else{
				$list .= 	'						<input type="checkbox" class="ace" value="'. $aRow['product_id'].'" id="'. $aRow['product_id'].'" name="product-list[]" />';
			}
			$list .= '							<span class="lbl"> '. $aRow['product_id'] .' - '.$aRow['product_name'].'</span>
									</label>
								</div>
							</li>
							';

		}
		return $list;
	}

	function isExistBisnisUnit($id){
		$this->db->from('cms_bisnis_unit');
		$this->db->where(array('bisnis_unit_id' => $id
								));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	function delete_bisnis_unit($data)
	{
		$this->db->where('bisnis_unit_id', $data["bisnis_unit_id"]);
		$this->db->set('flag_tmp', '2');
		$this->db->set('update_time', date('Y-m-d H:i:s'));
		//$this->db->set('note_reject', $kcu_data["note_reject"]);
		$return = $this->db->update('cms_bisnis_unit_tmp');

		/*
		$this->db->where('bisnis_unit_id', $data["bisnis_unit_id"]);
		$this->db->set('flag_tmp', '2');
		$this->db->set('update_time', date('Y-m-d H:i:s'));
		//$this->db->set('note_reject', $kcu_data["note_reject"]);
		$return = $this->db->update('cms_bisnis_unit');
		*/

		//$return = $this->db->delete('cms_bisnis_unit', array('bisnis_unit_id' => $data['bisnis_unit_id'])); 
		
		return $return;
	}

	function get_versi_apk_list()
	{
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		if ($sSearch == 'true')
		{
			$filters = $this->input->get_post('filters', true);
			//echo "xxx".$filters."vsdv";
			$filters = json_decode($filters);
			$where = '';
			$whereArray = array();
			$rules = $filters->rules;
			$groupOperation = $filters->groupOp;
			foreach($rules as $rule) {

				$fieldName = $rule->field;
				$fieldData = mysql_real_escape_string($rule->data);
				
				if($fieldName == 'dat.alamat_ktp')
				{
					$fieldOperation = '('.$fieldName.' LIKE "%'.$fieldData.'%" OR dat.rt_ktp LIKE "%'.$fieldData.'%" OR dat.rw_ktp LIKE "%'.$fieldData.'%" OR dat.kelurahan_ktp LIKE "%'.$fieldData.'%" OR dat.kecamatan_ktp LIKE "%'.$fieldData.'%")';
				} else if(($fieldName == 'report_date')){
					$fieldOperation = $fieldName.' = STR_TO_DATE("'.$fieldData.'","%m/%d/%Y") ';
				}
				else
				{
					$fieldOperation = $fieldName.' LIKE "%'.$fieldData.'%"';
				}
				
				if($fieldOperation != '') $whereArray[] = $fieldOperation;
			}
			if (count($whereArray)>0) {
					$where .= join(' '.$groupOperation.' ', $whereArray);
			} else {
					$where = '';
			}
		}
		
		$select = array('DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','file_size','versi_apk','nama_file','lokasi_file','priority','concat(usr.id," - ",usr.name) created_by');
		//$this->db->where($where);
		// Select Data
		$this->db->from('cms_apk_version a');	
		$this->db->join('cc_user usr','a.created_by=usr.id');
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);

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
				"id" => $aRow['versi_apk'], 
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
	function get_versi_apk_list_tmp()
	{
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true')
		{
			$filters = $this->input->get_post('filters', true);
			//echo "xxx".$filters."vsdv";
			$filters = json_decode($filters);
			$where = '';
			$whereArray = array();
			$rules = $filters->rules;
			$groupOperation = $filters->groupOp;
			foreach($rules as $rule) {

				$fieldName = $rule->field;
				$fieldData = mysql_real_escape_string($rule->data);
				
				if($fieldName == 'dat.alamat_ktp')
				{
					$fieldOperation = '('.$fieldName.' LIKE "%'.$fieldData.'%" OR dat.rt_ktp LIKE "%'.$fieldData.'%" OR dat.rw_ktp LIKE "%'.$fieldData.'%" OR dat.kelurahan_ktp LIKE "%'.$fieldData.'%" OR dat.kecamatan_ktp LIKE "%'.$fieldData.'%")';
				} else if(($fieldName == 'report_date')){
					$fieldOperation = $fieldName.' = STR_TO_DATE("'.$fieldData.'","%m/%d/%Y") ';
				}
				else
				{
					$fieldOperation = $fieldName.' LIKE "%'.$fieldData.'%"';
				}
				
				if($fieldOperation != '') $whereArray[] = $fieldOperation;
			}
			if (count($whereArray)>0) {
					$where .= join(' '.$groupOperation.' ', $whereArray);
			} else {
					$where = '';
			}
		}
		
		$select = array('DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','a.id','file_size','versi_apk','nama_file','lokasi_file','priority','concat(usr.id," - ",usr.name) created_by');
		//$this->db->where($where);
		// Select Data
		$this->db->from('cms_apk_version_tmp a');	
		$this->db->join('cc_user usr','a.created_by=usr.id');
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->where("a.flag","0");	
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


	function get_bisnis_unit_list()
	{
		$aColumns				= array('value', 'value', 'description','add_field1','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		
		$select = array('DATE_FORMAT(created_time,"%d-%b-%Y") created_time','DATE_FORMAT(update_time,"%d-%b-%Y") update_time','bisnis_unit_id','description', 'assignment_type','product_list','IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag');
		//$this->db->where($where);
		// Select Data
		$this->db->from('cms_bisnis_unit');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "flag_tmp = '1'";
		$this->db->where($where2);

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
				"id" => $aRow['bisnis_unit_id'], 
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

	function get_bisnis_unit_list_temp()
	{
		$aColumns				= array('reference', 'value', 'DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','description','add_field1','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		$select = array('DATE_FORMAT(created_time,"%d-%b-%Y") created_time','DATE_FORMAT(update_time,"%d-%b-%Y") update_time','bisnis_unit_id','description', 'assignment_type','update_by','product_list','update_by','IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag');
		//$this->db->where($where);
//		$this->db->where("reference ",$this->input->get_post('parameter_type', true));
		// Select Data
		$this->db->from('cms_bisnis_unit_tmp a');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);

		$where2 = "flag_tmp = '0'";
		$this->db->where($where2);

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
				"id" => $aRow['bisnis_unit_id'], 
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

	function get_kcu_office_list()
	{
		$aColumns				= array('id','DATE_FORMAT(created_time,"%d-%b-%Y") created_time','DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','kcu_id', 'kcu_name', 'kcu_group_id','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
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
		
		$select = $aColumns;

		// Select Data
		$this->db->from('cms_kcu_cabang');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		
		$where2 = "flag_tmp = '1'";
		$this->db->order_by('created_time', 'desc');
		$this->db->where($where2);

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
		foreach($rResult->result_array() as $aRow)
		{
			$list[] = array(
				"id" => $aRow['kcu_id'], 
				"cell" => $aRow
			);
			
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

	function get_kcu_office_list_temp()
	{
		$aColumns				= array('id','kcu_id','DATE_FORMAT(created_time,"%d-%b-%Y") created_time','DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','kcu_name', 'kcu_group_id','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			//$this->db->order_by($iSortCol_0, $iSortingCols);
		}
		
		if ($sSearch == 'true') {
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

			//$where = "$searchField $ops '$searchString' "; 
			//kcu_id
			$where = "kcu_id $ops '$searchString' ";
		}
		
		$select = $aColumns;

		// Select Data
		$this->db->from('cms_kcu_cabang_tmp');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		
		$where2 = "flag_tmp = '0'";
		$this->db->order_by('created_time', 'desc');
		$this->db->where($where2);

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
		foreach($rResult->result_array() as $aRow)
		{
			$list[] = array(
				"id" => $aRow['kcu_id'], 
				"cell" => $aRow
			);
			
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

	function save_kcu_office_add($kcu_office_data)
	{
		//log_message('debug',__METHOD__);
		
		$return = $this->db->insert('cms_kcu_cabang_tmp', $kcu_office_data); 
		$return = $this->db->insert('cms_kcu_cabang', $kcu_office_data); 
		
		return $return;
	}
	
	function save_kcu_edit($kcu_data)
	{
		$this->db->query("replace into cms_kcu_tmp select * from cms_kcu where kcu_id='".$kcu_data["kcu_id"] ."'");
		
		$this->db->where('kcu_id', $kcu_data["kcu_id"]);
		$this->db->set('kcu_name', $kcu_data["kcu_name"]);
		$this->db->set('zip_code_list', $kcu_data["zip_code_list"]);
		$this->db->set('cabang', $kcu_data["cabang"]);
		$this->db->set('area', $kcu_data["area"]);
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('flag', $kcu_data["flag"]);
		$this->db->set('flag_tmp', '0');
		$this->db->set('alamat', $kcu_data["alamat"]);
		$this->db->set('action', 'Edit');		
		$return = $this->db->update('cms_kcu_tmp'); 

		return $return;
	}
	function save_kcu_agency_edit($kcu_data)
	{
		$this->db->query("replace into cms_kcu_agency_tmp select * from cms_kcu_agency where kcu_id='".$kcu_data["kcu_id"] ."'");
		
		$this->db->where('kcu_id', $kcu_data["kcu_id"]);
		$this->db->set('kcu_name', $kcu_data["kcu_name"]);
		$this->db->set('zip_code_list', $kcu_data["zip_code_list"]);
		$this->db->set('cabang', $kcu_data["cabang"]);
		$this->db->set('area', $kcu_data["area"]);
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('flag', $kcu_data["flag"]);
		$this->db->set('flag_tmp', '0');
		$this->db->set('alamat', $kcu_data["alamat"]);
		$this->db->set('action', 'Edit');		
		$return = $this->db->update('cms_kcu_agency_tmp'); 

		return $return;
	}
	
	function save_custom_field_edit($custom_field_data)
	{
		$this->db->query("replace into cc_custom_fields_tmp select * from cc_custom_fields where field_name='".$custom_field_data["id"] ."'");
		
		$this->db->where('field_name', $custom_field_data["id"]);
		$this->db->set('field_label', $custom_field_data["field_label"]);
		$this->db->set('field_length', $custom_field_data["field_length"]);
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('field_type', $custom_field_data["field_type"]);
		$this->db->set('table_destination', $custom_field_data["table_destination"]);
		$this->db->set('flag_tmp', '0');
		$this->db->set('description', $custom_field_data["description"]);
		$this->db->set('action', 'Edit');		
		$return = $this->db->update('cc_custom_fields_tmp'); 

		return $return;
	}
	
	function save_master_regional_edit($regional_data)
	{
		$this->db->query("replace into cms_master_regional_tmp select * from cms_master_regional where id='".$regional_data["id"] ."'");
		
		$this->db->where('id', $regional_data["id"]);
		$this->db->set('name', $regional_data["name"]);
		$this->db->set('real_name', $regional_data["real_name"]);
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('flag', $regional_data["flag"]);
		$this->db->set('flag_tmp', '0');
		$this->db->set('action', 'Edit');		
		$return = $this->db->update('cms_master_regional_tmp'); 

		return $return;
	}
	
	
	function save_master_account_group_edit($account_group_data)
	{
		$this->db->query("replace into cms_master_account_group_tmp select * from cms_master_account_group where id='".$account_group_data["id"] ."'");
		
		$this->db->where('id', $account_group_data["id"]);
		$this->db->set('description', $account_group_data["description"]);
		$this->db->set('payment_criteria', $account_group_data["payment_criteria"]);
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('flag', $account_group_data["flag"]);
		$this->db->set('flag_tmp', '0');
		$this->db->set('action', 'Edit');		
		$return = $this->db->update('cms_master_account_group_tmp'); 

		return $return;
	}
	
	function save_kcu_office_edit($kcu_office_data)
	{
		$return = $this->db->delete('cms_kcu_cabang_tmp', array('kcu_id' => $kcu_office_data['kcu_id'])); 
		
		$this->db->query("replace into cms_kcu_cabang_tmp select * from cms_kcu_cabang where kcu_id='".$kcu_office_data["kcu_id"]."'");
		
		$this->db->where('kcu_id', $kcu_office_data["kcu_id"]);
		$this->db->set('kcu_name', $kcu_office_data["kcu_name"]);
		$this->db->set('flag', $kcu_office_data["flag"]);
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('flag_tmp', '0');
		$this->db->set('kcu_group_id', $kcu_office_data["kcu_group_id"]);
		$return = $this->db->update('cms_kcu_cabang_tmp'); 
		
		return $return;
	}

/*
		function save_kcu_edit_temp($kcu_data)
	{	
		//masukkan kcu baru dari approval
		//kalo replace pake ID yg sama, muncul semuanya. Jadi harus pake UUID
		$return = $this->db->delete('cms_kcu', array('kcu_id' => $kcu_data['kode_kcu_group'])); 
				
		$this->db->query("replace into cms_kcu select * from cms_kcu_tmp where id='".$kcu_data['id'] ."'");		
		
		$return = $this->db->delete('cms_kcu_tmp', array('kcu_id' => $kcu_data['kode_kcu_group'])); 
		
		//Ubah flag data yang ga kepake dengan id UUID yg ditangkap dari approval 
		$this->db->where('kcu_id', $kcu_data['kode_kcu_group']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_kcu'); 
		
		$this->db->where('kcu_id', $kcu_data['kode_kcu_group']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_kcu_tmp'); 

		return $return;
	}
*/
	
	function save_kcu_office_edit_flag($kcu_office_data)
	{
		$return = $this->db->delete('cms_kcu_cabang', array('kcu_id' => $kcu_office_data)); 
		
		$this->db->query("replace into cms_kcu_cabang select * from cms_kcu_cabang_tmp where kcu_id='".$kcu_office_data."'");		
		
		$return = $this->db->delete('cms_kcu_cabang_tmp', array('kcu_id' => $kcu_office_data)); 
		
		$this->db->where('kcu_id', $kcu_office_data);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_kcu_cabang');

		$this->db->where('kcu_id', $kcu_office_data);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_kcu_cabang_tmp'); 
	
		return $return;
	}
	
	function edit_flag_ta($ta_id)
	{
		$return = $this->db->delete('cms_tambah_alamat', array('id' => $ta_id)); 
		
		$this->db->query("replace into cms_tambah_alamat select * from cms_tambah_alamat_tmp where id='".$ta_id."'");		
		
		$return = $this->db->delete('cms_tambah_alamat_tmp', array('id' => $ta_id)); 
		
		$this->db->where('id', $ta_id);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('updated_by', $this->session->userdata('USER_ID'));
		$return = $this->db->update('cms_tambah_alamat');

		$this->db->where('id', $ta_id);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('updated_by', $this->session->userdata('USER_ID'));
		$return = $this->db->update('cms_tambah_alamat_tmp'); 
	
		return $return;
	}
	
	/*
	function delete_parameters($id,$type)
	{
		$this->db->delete('cms_reference_tmp', array('value' => $id,'reference'=>$type)); 
	}
	*/
function reject_apk($id)
		{
			$this->db->where('id', $id);
			$this->db->set('flag', '2');
			$this->db->set('updated_time', date('Y-m-d H:i:s'));
			$this->db->set('updated_by', $this->session->userdata('USER_ID'));
			$return = $this->db->update('cms_apk_version_tmp');
			
			
			return $return;
		}
	
	function delete_parameters($id,$type)
		{
			//$return = $this->db->delete('cms_kcu', array('id' => $id));
			/*$this->common_model->data_logging('Setup Paramater Collection Maker', "Delete Setup Paramater", 'SUCESS', ' ID: '.$id );
			$data = array("success" => true, "message" => "Success");
			*/
			$this->db->where('value', $id);
			$this->db->where('reference', $type);
			$this->db->set('flag_tmp', '2');
			$this->db->set('updated_time', date('Y-m-d H:i:s'));
			//$this->db->set('note_reject', $kcu_data["note_reject"]);
			$return = $this->db->update('cms_reference_tmp');
			
			//$this->db->query("replace into cms_kcu select * from cms_kcu_tmp where kcu_id='".$kcu_data["kcu_id"] ."'");

			//$return = $this->db->delete('cms_kcu', array('id' => $kcu_data["id"]));
			//$return = $this->db->delete('cms_kcu_tmp', array('id' => $kcu_data["id"]));
			
			/*
			$this->db->where('id', $kcu_data["id"]);
			$this->db->set('flag_tmp', '2');
			//$this->db->set('id', UUID());
			$this->db->set('updated_time', date('Y-m-d H:i:s'));
			$this->db->set('note_reject', $kcu_data["note_reject"]);
			$return2 = $this->db->update('cms_kcu');  

			return $return;
			*/
			
			return $return;
		}
		
		function direct_delete_parameters($id,$type)
		{
			$this->db->where('value', $id);
			$this->db->where('reference', $type);
			$return = $this->db->delete('cms_reference_tmp');
			
			$this->db->where('value', $id);
			$this->db->where('reference', $type);
			$return = $this->db->delete('cms_reference');			
			
			$this->common_model->data_logging('Setup Paramater Collection Maker', "Delete Setup Paramater", 'SUCESS', ' ID: '.$id );
			$data = array("success" => true, "message" => "Success");
			
			return $return;
		}

	function save_parameters_add($parameters_data)
	{
		//log_message('debug',__METHOD__);
		$return = $this->db->replace('cms_reference_tmp', $parameters_data); 
		//$return = $this->db->insert('cms_reference', $parameters_data); 
		
		return $return;
	}

	function save_parameters_edit($parameters_data)
	{
		//log_message('debug',__METHOD__);
		$return = $this->db->query("replace into cms_reference_tmp select * from cms_reference where value='".$parameters_data["value"]."' and reference='".$parameters_data["reference"]."'");
		
		$this->db->where('value', $parameters_data["value"]);
		$this->db->where('reference', $parameters_data["reference"]);
		$this->db->set('description', $parameters_data["description"]);
		$this->db->set('value', $parameters_data["value"]);
		$this->db->set('add_field1', $parameters_data["add_field1"]);
		$this->db->set('add_field2', $parameters_data["add_field2"]);
		$this->db->set('bisnis_unit', $parameters_data["bisnis_unit"]);
		$this->db->set('updated_by',$parameters_data["updated_by"]);
		$this->db->set('flag',$parameters_data["status"]);
		$this->db->set('flag_tmp', '0');
		$this->db->set('updated_time',$parameters_data["updated_time"]);
		$this->db->set('action', 'Edit');

		$return = $this->db->update('cms_reference_tmp'); 

//		$return = $this->db->insert('cms_reference_tmp', $parameters_data); 
		return $return;
	}

	function save_kcu_edit_temp($kcu_data)
	{
		$return = $this->db->delete('cms_kcu', array('kcu_id' => $kcu_data['kode_kcu_group'])); 
				
		$this->db->query("replace into cms_kcu select * from cms_kcu_tmp where id='".$kcu_data['id'] ."'");		
		
		$return = $this->db->delete('cms_kcu_tmp', array('kcu_id' => $kcu_data['kode_kcu_group'])); 
		
		$this->db->where('kcu_id', $kcu_data['kode_kcu_group']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_kcu'); 
		
		$this->db->where('kcu_id', $kcu_data['kode_kcu_group']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_kcu_tmp'); 

		return $return;
	}
	function save_kcu_agency_edit_temp($kcu_data)
	{
		$return = $this->db->delete('cms_kcu_agency', array('kcu_id' => $kcu_data['kode_kcu_group'])); 
				
		$this->db->query("replace into cms_kcu_agency select * from cms_kcu_agency_tmp where id='".$kcu_data['id'] ."'");		
		
		$return = $this->db->delete('cms_kcu_agency_tmp', array('kcu_id' => $kcu_data['kode_kcu_group'])); 
		
		$this->db->where('kcu_id', $kcu_data['kode_kcu_group']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_kcu_agency'); 
		
		$this->db->where('kcu_id', $kcu_data['kode_kcu_group']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_kcu_agency_tmp'); 

		return $return;
	}
	
	function save_custom_field_edit_temp($custom_field_data)
	{
		 $return = $this->db->delete('cc_custom_fields', array('field_name' => $custom_field_data['field_name'])); 
				
		$this->db->query("replace into cc_custom_fields select * from cc_custom_fields_tmp where field_name='".$custom_field_data['field_name'] ."'");		
		
		$return = $this->db->delete('cc_custom_fields_tmp', array('field_name' => $custom_field_data['field_name'])); 
		
		$this->db->where('field_name', $custom_field_data['field_name']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('updated_by', $this->session->userdata('USER_ID'));
		$return = $this->db->update('cc_custom_fields'); 
		
		$this->db->where('field_name', $custom_field_data['field_name']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('updated_by', $this->session->userdata('USER_ID'));
		$return = $this->db->update('cc_custom_fields_tmp'); 
		
		if($custom_field_data["action"] == 'Add'){
			if($custom_field_data["field_type"] == 'varchar' || $custom_field_data["field_type"] == 'int'){
				$sql = "alter table mega_unsecured_db.".$custom_field_data["table_destination"]." add column ".$custom_field_data["field_name"]." ".$custom_field_data["field_type"]."(".$custom_field_data["field_length"].") NOT NULL";
				
			}
			else{
				$sql = "alter table mega_unsecured_db.".$custom_field_data["table_destination"]." add column ".$custom_field_data["field_name"]." ".$custom_field_data["field_type"]." NOT NULL";
			}
		}
		else{
			if($custom_field_data["field_type"] == 'varchar' || $custom_field_data["field_type"] == 'int'){
				$sql = "alter table mega_unsecured_db.".$custom_field_data["table_destination"]." change ".$custom_field_data["field_name"]." ".$custom_field_data["field_name"]." ".$custom_field_data["field_type"]."(".$custom_field_data["field_length"].") NULL";
				
			}
			else{
				$sql = "alter table mega_unsecured_db.".$custom_field_data["table_destination"]." change ".$custom_field_data["field_name"]." ".$custom_field_data["field_name"]." ".$custom_field_data["field_type"]." NULL";
			}
		}
		$this->db->query($sql); 
		
		return $return;
	}
	
	function save_master_regional_edit_temp($regional_data)
	{
		$return = $this->db->delete('cms_master_regional', array('id' => $regional_data['id'])); 
				
		$this->db->query("replace into cms_master_regional select * from cms_master_regional_tmp where id='".$regional_data['id'] ."'");		
		
		$return = $this->db->delete('cms_master_regional_tmp', array('id' => $regional_data['id'])); 
		
		$this->db->where('id', $regional_data['id']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_master_regional'); 
		
		$this->db->where('id', $regional_data['id']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_master_regional_tmp'); 

		return $return;
	}
	
	function save_master_account_group_edit_temp($account_group_data)
	{
		$return = $this->db->delete('cms_master_account_group', array('id' => $account_group_data['id'])); 
				
		$this->db->query("replace into cms_master_account_group select * from cms_master_account_group_tmp where id='".$account_group_data['id'] ."'");		
		
		$return = $this->db->delete('cms_master_account_group_tmp', array('id' => $account_group_data['id'])); 
		
		$this->db->where('id', $account_group_data['id']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_master_account_group'); 
		
		$this->db->where('id', $account_group_data['id']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_master_account_group_tmp'); 

		return $return;
	}

	function save_apk_temp($data)
	{
				
		$return = $this->db->query("replace into cms_apk_version select * from cms_apk_version_tmp where id='".$this->input->get_post('id',true) ."'");		
		$apk_path = $data = $this->common_model->get_record_value("lokasi_file", "cms_apk_version_tmp", "id='".$this->input->get_post('id',true) ."'");		
		$filename = $this->common_model->get_record_value("nama_file", "cms_apk_version_tmp", "id='".$this->input->get_post('id',true) ."'");		

		$return = $this->db->delete('cms_apk_version_tmp', array('id' => $this->input->get_post('id',true))); 

//		var_dump($apk_path);	
		if($filename == "CMSMobileCollection.apk"){
			$cmd = "cp $apk_path /var/www/html/panin/download/cloud/CMSMobileCollection.apk2";
			//exec($cmd);
			//echo $cmd;
			//copy($apk_path, "/var/www/html/panin/download/cloud/CMSMobileCollection.apk1");
			if(!copy($apk_path, "/var/www/html/panin/download/cloud/CMSMobileCollection.apk")){
				echo "failed to copy $apk_path...\n";
			}
			//exec($cmd);
		}

		return $return;
	}
	
	function save_parameters_edit_flag($parameters_data)
	{
		/*
		$return = $this->db->query("replace into cms_reference select * from cms_reference_tmp where value='".$parameters_data["id"]."' and reference='".$parameters_data["reference"]."'");
		$this->db->delete('cms_reference_tmp', array('value' => $parameters_data["id"],'reference'=>$parameters_data["reference"])); 
		return $return;
		*/
		
		$return = $this->db->delete('cms_reference', array('value' => $parameters_data["id"],'reference'=>$parameters_data["reference"])); 
				
		$this->db->query("replace into cms_reference select * from cms_reference_tmp where value='".$parameters_data["id"]."' and reference='".$parameters_data["reference"]."'");	
		
		$return = $this->db->delete('cms_reference_tmp', array('value' => $parameters_data["id"],'reference'=>$parameters_data["reference"])); 
		
		$this->db->where('value', $parameters_data['id']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_reference'); 
		
		$this->db->where('value', $parameters_data['id']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_reference_tmp'); 

		return $return;
	}

	function get_parameters_list()
	{
		$aColumns				= array('IF(add_field1 = "DESKTOP", "DESKTOP", IF(add_field1 = "MOBILE", "MOBILE", "DESKTOP DAN MOBILE")) AS add_field1','value', 'value', 'description','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		
		$select = array('DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','a.value as id','a.value','a.description', 'IF(a.add_field1 = "DESKTOP", "DESKTOP", IF(a.add_field1 = "MOBILE", "MOBILE", "DESKTOP DAN MOBILE")) AS add_field1','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','concat(c.id," - ",c.name) AS created_by');
		//$this->db->where($where);
		$this->db->where("a.reference ",$this->input->get_post('parameter_type', true));
		$this->db->join('cc_user c','c.id=a.created_by','left');	
		// Select Data
		$this->db->from('cms_reference a');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "a.flag_tmp = '1'";
		$this->db->where($where2);

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

	function get_parameters_list_temp()
	{
		$aColumns				= array('IF(add_field1 = "DESKTOP", "DESKTOP", IF(add_field1 = "MOBILE", "MOBILE", "DESKTOP DAN MOBILE")) AS add_field1','reference', 'value', 'DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','description','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag, action');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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

		$select = array('IF(b.add_field1 = "DESKTOP", "DESKTOP", IF(b.add_field1 = "MOBILE", "MOBILE", "DESKTOP DAN MOBILE")) AS add_field1','DATE_FORMAT(b.created_time,"%d-%b-%Y") created_time','DATE_FORMAT(b.updated_time,"%d-%b-%Y") updated_time','IF(b.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(b.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','b.value as id','c.description as reference','b.reference as reff_id','b.value','b.description','b.updated_by as updated_by','IF(b.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'concat(a.id," - ",a.name) AS created_by, b.action');
		//$this->db->where($where);
//		$this->db->where("reference ",$this->input->get_post('parameter_type', true));
		// Select Data
		$this->db->from('cms_reference_tmp b');	
		$this->db->join('cc_user a','a.id=b.created_by','left');	
		$this->db->join('mst_ref c','b.reference=c.id ','LEFT');
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);

		$where2 = "b.flag_tmp = '0'";
		$this->db->where($where2);
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

	function save_mpa_add($mpa_data)
	{
		//log_message('debug',__METHOD__);
		$return = $this->db->insert('cms_mpa_template_tmp', $mpa_data); 
		$return = $this->db->insert('cms_mpa_template', $mpa_data); 
		
		return $return;
	}

/*	
		function save_kcu_edit($kcu_data)
	{
		$this->db->query("replace into cms_kcu_tmp select * from cms_kcu where kcu_id='".$kcu_data["kcu_id"] ."'");
		
		$this->db->where('kcu_id', $kcu_data["kcu_id"]);
		$this->db->set('kcu_name', $kcu_data["kcu_name"]);
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('flag', $kcu_data["flag"]);
		$this->db->set('flag_tmp', '0');
		$return = $this->db->update('cms_kcu_tmp'); 

		return $return;
	}
*/

	function save_mpa_edit($mpa_data)
	{
		//log_message('debug',__METHOD__);
		$this->db->query("replace into cms_mpa_template_tmp select * from cms_mpa_template where mpa_id='".$mpa_data["mpa_id"]."'");
		
		$this->db->where('mpa_id', $mpa_data["mpa_id"]);
		$this->db->set('content', $mpa_data["content"]);
		$this->db->set('dpd_to', $mpa_data["dpd_to"]);
		$this->db->set('dpd_from', $mpa_data["dpd_from"]);
		$this->db->set('info', $mpa_data["info"]);
		$this->db->set('flag', $mpa_data["flag"]);
		$this->db->set('flag_tmp', '0');
		$this->db->set('updated_by', $this->session->userdata('USER_ID'));
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_mpa_template_tmp'); 
		
		return $return;
	}

	function save_mpa_edit_temp($mpa_data)
	{
		$return = $this->db->delete('cms_mpa_template', array('mpa_id' => $mpa_data['mpa_id']));
		
		$this->db->query("replace into cms_mpa_template select * from cms_mpa_template_tmp where mpa_id='".$mpa_data["mpa_id"]."'");
		
		$return = $this->db->delete('cms_mpa_template_tmp', array('mpa_id' => $mpa_data['mpa_id']));

		$this->db->where('mpa_id', $mpa_data["mpa_id"]);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_mpa_template'); 

		$this->db->where('mpa_id', $mpa_data["mpa_id"]);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_mpa_template_tmp'); 

		//$return = $this->db->delete('cms_mpa_template_tmp', array('mpa_id' => $mpa_data["mpa_id"])); 		
		return $return;
	}

	function get_mpa_list()
	{
		$aColumns				= array('mpa_id', 'info','dpd_from','dpd_to', 'content','flag');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
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
		
		$select = array('DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','DATE_FORMAT(created_time,"%d-%b-%Y") created_time','mpa_id','info','dpd_from','dpd_to','content','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');

		// Select Data
		$this->db->from('cms_mpa_template');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "flag_tmp = '1'";
		$this->db->where($where2);

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
				"id" => $aRow['mpa_id'], 
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

	function get_mpa_list_temp()
	{
		$aColumns				= array('DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','mpa_id', 'info','dpd_from','dpd_to', 'content');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
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
		
		$select = array('mpa_id','info','dpd_from','dpd_to','content','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time', 'DATE_FORMAT(created_time,"%d-%b-%Y") created_time','IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');

		// Select Data
		$this->db->from('cms_mpa_template_tmp');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "flag_tmp = '0'";
		$this->db->where($where2);

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
				"id" => $aRow['mpa_id'], 
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
		function save_area_edit($kcu_data)
	{
		$return = $this->db->delete('cms_area_kcu_tmp', array('area_id' => $kcu_data['old_area_id'])); 
		
		$this->db->query("replace into cms_area_kcu_tmp select * from cms_area_kcu where area_id='".$kcu_data["old_area_id"] ."'");
		
		$this->db->where('area_id', $kcu_data["old_area_id"]);
		$this->db->set('area_name', $kcu_data["area_name"]);
		$this->db->set('flag', $kcu_data["flag"]);
		$this->db->set('updated_time', $kcu_data["updated_time"]);
		$this->db->set('flag_tmp', '0');
		$return = $this->db->update('cms_area_kcu_tmp'); 
		
		return $return;
	}
*/
	
	function save_letter_edit($letter_data)
	{
		//$return = $this->db->delete('cms_letter_template_tmp', array('letter_id' => $letter_data['letter_id'])); 
		$return = $this->db->delete('cms_letter_template_tmp', array('id' => $letter_data['id'])); 
		
		//$this->db->query("insert into cms_letter_template_tmp select * from cms_letter_template where letter_id='". $letter_data["letter_id"]."'");		
		$this->db->query("insert into cms_letter_template_tmp select * from cms_letter_template where id='". $letter_data["id"]."'");		
		
		$this->db->where('letter_id', $letter_data["letter_id"]);
		$this->db->where('asset_type', $letter_data["asset_type"]);
		$this->db->set('content', $letter_data["content"]);
		$this->db->set('dpd_to', $letter_data["dpd_to"]);
		$this->db->set('dpd_from', $letter_data["dpd_from"]);
		$this->db->set('flag_tmp', '0');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('updated_by', $this->session->userdata('USER_ID'));
		$return = $this->db->update('cms_letter_template_tmp'); 
		
		/*
		$this->db->where('letter_id', $letter_data["letter_id"]);
		$this->db->set('content', $letter_data["content"]);
		$this->db->set('dpd_to', $letter_data["dpd_to"]);
		$this->db->set('dpd_from', $letter_data["dpd_from"]);
		$this->db->set('flag_tmp', '0');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_letter_template'); 
		*/ 
		
		return $return;
	}
	
	function save_ral_edit($ral_data)
	{
		// echo htmlspecialchars($ral_data["content"]);
		
		//$return = $this->db->delete('cms_letter_template_tmp', array('letter_id' => $letter_data['letter_id'])); 
		$return = $this->db->delete('cms_ral_template_tmp', array('id' => $ral_data['id'])); 
		
		//$this->db->query("insert into cms_letter_template_tmp select * from cms_letter_template where letter_id='". $letter_data["letter_id"]."'");		
		$this->db->query("insert into cms_ral_template_tmp select * from cms_ral_template where id='". $ral_data["id"]."'");		
		
		$this->db->where('ral_id', $ral_data["ral_id"]);
		// $this->db->where('asset_type', $ral_data["asset_type"]);
		if($ral_data["dpd_to"]==''){
			$ral_data["dpd_to"]='0';
		}
		if($ral_data["dpd_from"]==''){
			$ral_data["dpd_from"]='0';
		}
		
		$style = "<style>
					.page_break { page-break-before: always; }
					.justify { text-align: justify;}
				</style>";
		
		
	

		
		$ral_data["content"] = $style.''.$ral_data["content"];
		// $ral_data["content"] = $ral_data["content"];
		
		$this->db->set('content', $ral_data["content"]);
		$this->db->set('dpd_to', $ral_data["dpd_to"]);
		$this->db->set('dpd_from', $ral_data["dpd_from"]);
		$this->db->set('flag_tmp', '0');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('updated_by', $this->session->userdata('USER_ID'));
		$return = $this->db->update('cms_ral_template_tmp'); 
		
		/*
		$this->db->where('letter_id', $letter_data["letter_id"]);
		$this->db->set('content', $letter_data["content"]);
		$this->db->set('dpd_to', $letter_data["dpd_to"]);
		$this->db->set('dpd_from', $letter_data["dpd_from"]);
		$this->db->set('flag_tmp', '0');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_letter_template'); 
		*/ 
		
		return $return;
	}
	
	/*
	function save_kcu_edit_temp($kcu_data)
	{
		$return = $this->db->delete('cms_kcu', array('kcu_id' => $kcu_data['kode_kcu_group'])); 
				
		$this->db->query("replace into cms_kcu select * from cms_kcu_tmp where id='".$kcu_data['id'] ."'");		
		
		$return = $this->db->delete('cms_kcu_tmp', array('kcu_id' => $kcu_data['kode_kcu_group'])); 
		
		$this->db->where('kcu_id', $kcu_data['kode_kcu_group']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_kcu'); 
		
		$this->db->where('kcu_id', $kcu_data['kode_kcu_group']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_kcu_tmp'); 

		return $return;
	}
	*/

	function save_letter_edit_temp($letter_data)
	{		
		//$return = $this->db->delete('cms_letter_template', array('letter_id' => $letter_data['letter_id'])); 
		$return = $this->db->delete('cms_letter_template', array('id' => $letter_data['id'])); 
		if($return){
		
			//$this->db->query("replace into cms_letter_template select * from cms_letter_template_tmp where letter_id='". $letter_data["letter_id"]."'");				
			$this->db->query("replace into cms_letter_template select * from cms_letter_template_tmp where id='". $letter_data["id"]."'");				
			
			//$return = $this->db->delete('cms_letter_template_tmp', array('letter_id' => $letter_data['letter_id'])); 
			$return = $this->db->delete('cms_letter_template_tmp', array('id' => $letter_data['id'])); 
			
			$this->db->where('letter_id', $letter_data["letter_id"]);
			$this->db->set('flag_tmp', '1');
			$this->db->set('updated_time', date('Y-m-d H:i:s'));
			$return = $this->db->update('cms_letter_template_tmp'); 

			$this->db->where('letter_id', $letter_data["letter_id"]);
			$this->db->set('flag_tmp', '1');
			$this->db->set('approve_time', date('Y-m-d H:i:s'));
			$this->db->set('approve_by',  $this->session->userdata('USER_ID'));
			$return = $this->db->update('cms_letter_template'); 
			
			return $return;
		}else{
			return false;
		}
	}
	
	function save_ral_edit_temp($ral_data)
	{		
		//$return = $this->db->delete('cms_letter_template', array('letter_id' => $letter_data['letter_id'])); 
		$return = $this->db->delete('cms_ral_template', array('id' => $ral_data['id'])); 
		
		//$this->db->query("replace into cms_letter_template select * from cms_letter_template_tmp where letter_id='". $letter_data["letter_id"]."'");				
		$this->db->query("replace into cms_ral_template select * from cms_ral_template_tmp where id='". $ral_data["id"]."'");				
		
		//$return = $this->db->delete('cms_letter_template_tmp', array('letter_id' => $letter_data['letter_id'])); 
		$return = $this->db->delete('cms_ral_template_tmp', array('id' => $ral_data['id'])); 
		
		$this->db->where('ral_id', $ral_data["ral_id"]);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_ral_template_tmp'); 

		$this->db->where('ral_id', $ral_data["ral_id"]);
		$this->db->set('flag_tmp', '1');
		$this->db->set('approve_time', date('Y-m-d H:i:s'));
		$this->db->set('approve_by',  $this->session->userdata('USER_ID'));
		$return = $this->db->update('cms_ral_template'); 
		
		return $return;
	}

	/*
	function delete_kcu($kcu_data)
	{
		$this->db->where('kcu_id', $kcu_data["kcu_id"]);
		$this->db->set('flag_tmp', '2');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		//$this->db->set('note_reject', $kcu_data["note_reject"]);
		$return = $this->db->update('cms_kcu_tmp');
		return $return;
	}
	*/
	
	function delete_letter_edit_temp($letter_data)
	{
		//log_message('debug',__METHOD__);
		//$return = $this->db->delete('cms_letter_template_tmp', array('letter_id' => $letter_data["letter_id"])); 
		//$this->db->where('letter_id', $letter_data["letter_id"]);
		$this->db->where('id', $letter_data["letter_id"]);
		$this->db->set('flag_tmp', '2');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_letter_template_tmp');
		
		return $return;
	}	
	function delete_ral_edit_temp($ral_data)
	{
		//log_message('debug',__METHOD__);
		//$return = $this->db->delete('cms_letter_template_tmp', array('letter_id' => $letter_data["letter_id"])); 
		//$this->db->where('letter_id', $letter_data["letter_id"]);
		$this->db->where('id', $ral_data["ral_id"]);
		$this->db->set('flag_tmp', '2');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_ral_template_tmp');
		
		return $return;
	}

	function get_letter_list() 
	{
		$aColumns				= array('letter_id', 'info','dpd_from','dpd_to', 'content','id');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
			$ops = array(
				'eq' => 'LIKE', //equal
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
		
		$select = array('DISTINCT a.id','DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','letter_id','info','dpd_from','dpd_to','descriptionproduct asset','content','IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');

		// Select Data
		$this->db->from('cms_letter_template a');	
		//$this->db->join('aav_configuration b','a.asset_type=b.id and b.parameter="ASSET_TYPE"');	
		$this->db->join('mst_safassetcategory b','a.asset_type=b.InitialProductCode');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
//		$where2 = "flag = '1'";
//		$this->db->where($where2);

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
	
	function get_ral_list() 
	{
		$aColumns				= array('ral_id', 'info','dpd_from','dpd_to', 'content','id');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
			$ops = array(
				'eq' => 'LIKE', //equal
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
		
		$select = array('DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time',
						'ral_id',
						'info',
						'dpd_from',
						'dpd_to',
						'asset_type asset',
						'content','IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp',
						'a.id');

		// Select Data
		$this->db->from('cms_ral_template a');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "flag = '1' and is_active='1'";
		$this->db->where($where2);

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
	
	function get_letter_list_temp() 
	{
		$aColumns				= array('letter_id', 'info','dpd_from','dpd_to', 'content','id');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
			$ops = array(
				'eq' => 'LIKE', //equal
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
		
		$select = array('DISTINCT a.id','letter_id','info','dpd_from','dpd_to','descriptionproduct asset','content','DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');

		// Select Data
		$this->db->from('cms_letter_template_tmp a');	
		//$this->db->join('aav_configuration b','a.asset_type=b.id and b.parameter="ASSET_TYPE"');	
		$this->db->join('mst_safassetcategory b','a.asset_type=b.InitialProductCode');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "flag_tmp = '0'";
		$this->db->where($where2);

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

	
	function get_ral_list_temp() 
	{
		$aColumns				= array('ral_id', 'info','dpd_from','dpd_to', 'content','id');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
			$ops = array(
				'eq' => 'LIKE', //equal
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
		
		$select = array(
			'ral_id',
			'info',
			'dpd_from',
			'dpd_to',
			'asset_type asset',
			'content',
			'DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time',
			'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp',
			'a.id');

		// Select Data
		$this->db->from('cms_ral_template_tmp a');		
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "flag_tmp = '0'";
		$this->db->where($where2);

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

	function isExistKcuGroup($id){
		$this->db->from('cms_kcu');
		$this->db->where(array('kcu_id' => $id
								));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}	
	function isExistKcuAgency($id,$name){
		$this->db->from('cms_kcu_agency');
		$this->db->where(array('kcu_id' => $id
								));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return true;
		}else{
			$this->db->from('cms_kcu_agency');
			$this->db->where(array('kcu_name' => $name
									));
			$query = $this->db->get();
				if ($query->num_rows() > 0){
					return true;
				}else{
					return false;
				}
		}
	}
	
	function isExistFieldName($id){
		$this->db->from('cc_custom_fields');
		$this->db->where(array('field_name' => $id
								));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function isExistRegional($id){
		$this->db->from('cms_master_regional');
		$this->db->where(array('id' => $id
								));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function isExistAccountGroup($id){
		$this->db->from('cms_master_account_group');
		$this->db->where(array('id' => $id
								));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function isExistKcuParam($id){
		$this->db->from('cms_reference');
		$this->db->where(array('value' => $id,'flag_tmp' => '1'
								));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	
		function isExistMpa($id){
		$this->db->from('cms_mpa_template');
		$this->db->where(array('mpa_id' => $id
								));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function isExistKcu($id){
		$this->db->from('cms_kcu_cabang');
		$this->db->where(array('kcu_id' => $id
								));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function isExistZipReg($zip_reg, $kel){
		$this->db->from('cms_zip_reg');
		$this->db->where(array(
						'zip_reg' => $zip_reg,
						'kelurahan' => $kel,
						));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return 1;
		}else{
			$this->db->from('cms_zip_reg_tmp');
			$this->db->where(array('zip_reg' => $zip_reg,'kelurahan' => $kel,'flag_tmp' => '0'));
			$query = $this->db->get();
			
			if ($query->num_rows() > 0){
				return 2;
			}else {
				return false;
			}
		}
	}
	
	function isExistArea($id){
		$this->db->from('cms_area_kcu');
		$this->db->where(array('area_id' => $id
								));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return 1;
		}else{
			$this->db->from('cms_area_kcu_tmp');
			$this->db->where(array('area_id' => $id,'flag_tmp' => '0'));
			$query = $this->db->get();
			
			if ($query->num_rows() > 0){
				return 2;
			}else {
				return false;
			}
		}
	}

	function isExistSubArea($id){
		$this->db->from('cms_sub_area_kcu');
		$this->db->where(array('sub_area_id' => $id
								));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return 1;
		}else{
			$this->db->from('cms_sub_area_kcu_tmp');
			$this->db->where(array('sub_area_id' => $id,'flag_tmp' => '0'));
			$query = $this->db->get();
			
			if ($query->num_rows() > 0){
				return 2;
			}else {
				return false;
			}
		}
	}
	
	function delete_kcu($kcu_data)
	{
		$return = $this->db->delete('cms_kcu', array('kcu_id' => $kcu_data["kcu_id"]));
		//$return = $this->db->delete('cms_kcu_tmp', array('kcu_id' => $kcu_data["kcu_id"]));
/*		$this->db->where('kcu_id', $kcu_data["kcu_id"]);
		$this->db->set('flag_tmp', '2');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		//$this->db->set('note_reject', $kcu_data["note_reject"]);
		$return = $this->db->update('cms_kcu_tmp');
	*/	
		//$this->db->query("replace into cms_kcu select * from cms_kcu_tmp where kcu_id='".$kcu_data["kcu_id"] ."'");

		//$return = $this->db->delete('cms_kcu', array('id' => $kcu_data["id"]));
		//$return = $this->db->delete('cms_kcu_tmp', array('id' => $kcu_data["id"]));
		
		/*
		$this->db->where('id', $kcu_data["id"]);
		$this->db->set('flag_tmp', '2');
		//$this->db->set('id', UUID());
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('note_reject', $kcu_data["note_reject"]);
		$return2 = $this->db->update('cms_kcu');  

		return $return;
		*/
		
		return $return;
	}
	function delete_kcu_agency($kcu_data)
	{
		$return = $this->db->delete('cms_kcu_agency', array('kcu_id' => $kcu_data["kcu_id"]));
		return $return;
	}
	
	function delete_custom_field($custom_field_data)
	{
		$return = $this->db->delete('cc_custom_fields', array('field_name' => $custom_field_data["field_name"]));
		
		$sql = "alter table mega_unsecured_db.".$custom_field_data["table_destination"]." drop column ".$custom_field_data["field_name"];
		
		$this->db->query($sql); 
		//$return = $this->db->delete('cms_kcu_tmp', array('kcu_id' => $kcu_data["kcu_id"]));
/*		$this->db->where('kcu_id', $kcu_data["kcu_id"]);
		$this->db->set('flag_tmp', '2');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		//$this->db->set('note_reject', $kcu_data["note_reject"]);
		$return = $this->db->update('cms_kcu_tmp');
	*/	
		//$this->db->query("replace into cms_kcu select * from cms_kcu_tmp where kcu_id='".$kcu_data["kcu_id"] ."'");

		//$return = $this->db->delete('cms_kcu', array('id' => $kcu_data["id"]));
		//$return = $this->db->delete('cms_kcu_tmp', array('id' => $kcu_data["id"]));
		
		/*
		$this->db->where('id', $kcu_data["id"]);
		$this->db->set('flag_tmp', '2');
		//$this->db->set('id', UUID());
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('note_reject', $kcu_data["note_reject"]);
		$return2 = $this->db->update('cms_kcu');  

		return $return;
		*/
		
		return $return;
	}

		function delete_master_regional($regional_data)
	{
		$return = $this->db->delete('cms_master_regional', array('id' => $regional_data["id"]));
		//$return = $this->db->delete('cms_kcu_tmp', array('kcu_id' => $kcu_data["kcu_id"]));
/*		$this->db->where('kcu_id', $kcu_data["kcu_id"]);
		$this->db->set('flag_tmp', '2');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		//$this->db->set('note_reject', $kcu_data["note_reject"]);
		$return = $this->db->update('cms_kcu_tmp');
	*/	
		//$this->db->query("replace into cms_kcu select * from cms_kcu_tmp where kcu_id='".$kcu_data["kcu_id"] ."'");

		//$return = $this->db->delete('cms_kcu', array('id' => $kcu_data["id"]));
		//$return = $this->db->delete('cms_kcu_tmp', array('id' => $kcu_data["id"]));
		
		/*
		$this->db->where('id', $kcu_data["id"]);
		$this->db->set('flag_tmp', '2');
		//$this->db->set('id', UUID());
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('note_reject', $kcu_data["note_reject"]);
		$return2 = $this->db->update('cms_kcu');  

		return $return;
		*/
		
		return $return;
	}
	
	function delete_master_account_group($account_group_data)
	{
		$return = $this->db->delete('cms_master_account_group', array('id' => $account_group_data["id"]));
		//$return = $this->db->delete('cms_kcu_tmp', array('kcu_id' => $kcu_data["kcu_id"]));
/*		$this->db->where('kcu_id', $kcu_data["kcu_id"]);
		$this->db->set('flag_tmp', '2');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		//$this->db->set('note_reject', $kcu_data["note_reject"]);
		$return = $this->db->update('cms_kcu_tmp');
	*/	
		//$this->db->query("replace into cms_kcu select * from cms_kcu_tmp where kcu_id='".$kcu_data["kcu_id"] ."'");

		//$return = $this->db->delete('cms_kcu', array('id' => $kcu_data["id"]));
		//$return = $this->db->delete('cms_kcu_tmp', array('id' => $kcu_data["id"]));
		
		/*
		$this->db->where('id', $kcu_data["id"]);
		$this->db->set('flag_tmp', '2');
		//$this->db->set('id', UUID());
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('note_reject', $kcu_data["note_reject"]);
		$return2 = $this->db->update('cms_kcu');  

		return $return;
		*/
		
		return $return;
	}
	
	function reject_kcu($kcu_data)
	{
		$return = $this->db->delete('cms_kcu_tmp', array('kcu_id' => $kcu_data["kcu_id"]));
		
		return $return;
	}
	function reject_kcu_agency($kcu_data)
	{
		$return = $this->db->delete('cms_kcu_agency_tmp', array('kcu_id' => $kcu_data["kcu_id"]));
		
		return $return;
	}

	function reject_custom_field($custom_field_data)
	{
		$return = $this->db->delete('cc_custom_fields_tmp', array('field_name' => $custom_field_data["field_name"]));
		
		return $return;
	}
	
	function reject_master_regional($regional_data)
	{
		$return = $this->db->delete('cms_master_regional_tmp', array('id' => $regional_data["id"]));
		
		return $return;
	}
	
	function reject_master_account_group($account_group_data)
	{
		$return = $this->db->delete('cms_master_account_group_tmp', array('id' => $account_group_data["id"]));
		
		return $return;
	}

/*
	function delete_kcu($kcu_data)
	{
		$this->db->where('kcu_id', $kcu_data["kcu_id"]);
		$this->db->set('flag_tmp', '2');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_kcu_tmp');
		
		return $return;
	}

*/	
	function delete_kcu_office($kcu_data_office)
	{
		//$this->db->delete('cms_kcu_cabang_tmp', array('kcu_id' => $id)); 

		//log_message('debug',__METHOD__);
		//$this->db->query("replace into cms_kcu_cabang select * from cms_kcu_cabang_tmp where id='".$kcu_data_office["id"] ."'");

		//$this->db->delete('cms_kcu_tmp', array('kcu_id' => $id));
		$this->db->where('kcu_id', $kcu_data_office["id"]);
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('flag_tmp', '2');
		$return = $this->db->update('cms_kcu_cabang_tmp');

		return $return;
	}
	
	function delete_kcu_agency_office($kcu_data_office)
	{
		//$this->db->delete('cms_kcu_cabang_tmp', array('kcu_id' => $id)); 

		//log_message('debug',__METHOD__);
		//$this->db->query("replace into cms_kcu_cabang select * from cms_kcu_cabang_tmp where id='".$kcu_data_office["id"] ."'");

		//$this->db->delete('cms_kcu_tmp', array('kcu_id' => $id));
		$this->db->where('kcu_id', $kcu_data_office["id"]);
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('flag_tmp', '2');
		$return = $this->db->update('cms_kcu_agency_tmp');

		return $return;
	}
	
	

	function delete_area($area_data)
	{
		$this->db->where('area_id', $area_data);
		$this->db->set('flag_tmp', '2');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_area_kcu_tmp');

		return $return;
	}

	function delete_sub_area($sub_area_data)
	{
		$this->db->where('sub_area_id', $sub_area_data);
		$this->db->set('flag_tmp', '2');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_sub_area_kcu_tmp');

		return $return;
	}

	function delete_mpa($mpa_data)
	{
		//$this->db->delete('cms_mpa_template_tmp', array('mpa_id' => $id)); 
		
		//log_message('debug',__METHOD__);
		//$this->db->query("replace into cms_mpa_template select * from cms_mpa_template_tmp where mpa_id='".$mpa_data["mpa_id"] ."'");

		//$this->db->delete('cms_kcu_tmp', array('kcu_id' => $id));
		$this->db->where('mpa_id', $mpa_data["mpa_id"]);
		$this->db->set('flag_tmp', '2');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_mpa_template_tmp');

		return $return;
	}

	function save_area_add($area_data)
	{
		//log_message('debug',__METHOD__);

		// $return = $this->db->replace('cms_area_kcu_tmp', $area_data); 
		// $return = $this->db->insert('cms_zip_reg', $area_data); 
		$return = $this->db->insert('cms_zip_reg_tmp', $area_data); 
		
		return $return;
	}

	function save_sub_area_add($sub_area_data)
	{
		//log_message('debug',__METHOD__);

		$return = $this->db->replace('cms_sub_area_kcu', $sub_area_data); 
		$return = $this->db->replace('cms_sub_area_kcu_tmp', $sub_area_data); 
		//$return = $this->db->insert('cms_area_kcu', $area_data); 
		
		return $return;
	}
	
	function save_area_add_temp($kcu_data)
	{
		//log_message('debug',__METHOD__);
		
		$return = $this->db->insert('cms_area_kcu_tmp', $kcu_data); 
		
		return $return;
	}
	
	/*
	function save_kcu_edit_temp($kcu_data)
	{
		$return = $this->db->delete('cms_kcu', array('kcu_id' => $kcu_data['kode_kcu_group'])); 
				
		$this->db->query("replace into cms_kcu select * from cms_kcu_tmp where id='".$kcu_data['id'] ."'");		
		
		$return = $this->db->delete('cms_kcu_tmp', array('kcu_id' => $kcu_data['kode_kcu_group'])); 
		
		$this->db->where('kcu_id', $kcu_data['kode_kcu_group']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_kcu'); 
		
		$this->db->where('kcu_id', $kcu_data['kode_kcu_group']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_kcu_tmp'); 

		return $return;
	}
	*/
	
	function edit_flag_chekermaker($area_data)
	{
		$return = $this->db->delete('cms_zip_reg', array('id' => $area_data['id'])); 
		
		$this->db->query("insert into cms_zip_reg select * from cms_zip_reg_tmp where zip_reg='".$area_data['zip_reg']."' AND kelurahan='".$area_data['kelurahan']."'");
		
		$return = $this->db->delete('cms_zip_reg_tmp', array('zip_reg' => $area_data['zip_reg'],'kelurahan' => $area_data['kelurahan'])); 

		$this->db->where('zip_reg', $area_data['zip_reg']);
		$this->db->where('kelurahan', $area_data['kelurahan']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_zip_reg'); 

		$this->db->where('zip_reg', $area_data['zip_reg']);
		$this->db->where('kelurahan', $area_data['kelurahan']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_zip_reg_tmp'); 
	
		return $return; 
	}

	function edit_flag_chekermaker_sub_area($sub_area_data)
	{
		$return = $this->db->delete('cms_sub_area_kcu', array('sub_area_id' => $sub_area_data['sub_area_id'])); 
		
		$this->db->query("replace into cms_sub_area_kcu select * from cms_sub_area_kcu_tmp where sub_area_id='".$sub_area_data['sub_area_id']."'");
		
		$return = $this->db->delete('cms_sub_area_kcu_tmp', array('sub_area_id' => $sub_area_data['sub_area_id'])); 

		$this->db->where('sub_area_id', $sub_area_data['sub_area_id']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_sub_area_kcu'); 

		$this->db->where('sub_area_id', $sub_area_data['sub_area_id']);
		$this->db->set('flag_tmp', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_sub_area_kcu_tmp'); 
	
		return $return;
	}

/*
	function edit_flag_psd($psd_data)
	{
		//log_message('debug',__METHOD__);
		//$this->db->query(" replace into cms_account_last_status select * from cms_account_last_status_temp where no_rekening='".$psd_data["psd_id"]."'");
		$this->db->where('id', $psd_data["psd_id"]);
		$this->db->set('updated_by', $this->session->userdata('USER_ID'));
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		//$this->db->set('area_id', $kcu_data["area_id"]);
		//$this->db->set('area_name', $kcu_data["area_name"]);
		//$this->db->set('kcu_id', $kcu_data["kcu_id"]);
		$this->db->set('flag_wo','1');
		$return = $this->db->update('cms_account_last_status'); 
	
		//$return = $this->db->delete('cms_account_last_status_temp', array('no_rekening' => $psd_data["psd_id"])); 		
		return $return;
	}
*/

	function edit_flag_psd($norekening)
	{
		$return = $this->db->delete('cms_account_last_status', array('no_rekening' => $norekening)); 
		
		$this->db->query("replace into cms_account_last_status select * from cms_account_last_status_tmp where no_rekening='".$norekening."'");
		$data = $this->common_model->get_record_values("ifnull(flag_ayda,'NULL')flag_ayda,flag_wo", "cms_account_last_status_tmp", "no_rekening='".$norekening."'", "");
		$return = $this->db->delete('cms_account_last_status_tmp', array('no_rekening' => $norekening)); 
		
		$this->db->where('no_rekening', $norekening);
		$this->db->set('flag_wo', '1');
		//var_dump($data);
		if(@$data['flag_ayda']=='0'){
			$this->db->set('flag_ayda', '1');
		}
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_account_last_status'); 
		//duplicat data ke cms_customer_profile_wo
		$this->db->query("insert ignore into cms_customer_profile_wo select * from cms_customer_profile where no_rekening = '".$norekening . "'" );
		$this->db->query("update cms_customer_profile_wo set status_rekening = 'WO' where no_rekening = '$norekening'");
		$this->db->query("update cms_customer_profile set status_rekening = 'WO' where no_rekening = '$norekening'");

		$this->db->query("update cms_assignment set assigned_fc = '' where no_rekening = '$norekening'");
		$this->db->query("delete from cms_assignment_agent where no_rekening = '$norekening'");
		
/*		$this->db->where('no_rekening', $norekening);
		$this->db->set('flag_wo', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_account_last_status_tmp'); 
*/	
		return $return;
	}

		function edit_flag_psd_ayda($norekening)
	{
		//$return = $this->db->delete('cms_account_last_status', array('id' => $psd_data['psd_id'])); 
		
		//$this->db->query("replace into cms_account_last_status select * from cms_account_last_status_tmp where id='".$psd_data['psd_id']."'");
		
		$return = $this->db->delete('cms_account_last_status_tmp', array('no_rekening' => $norekening)); 

		$this->db->where('no_rekening', $norekening);
		$this->db->set('flag_ayda', '1');
		$this->db->set('flag_wo', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_account_last_status'); 
		$this->db->query("insert ignore into cms_customer_profile_wo select * from cms_customer_profile where no_rekening = '".$norekening . "'" );
		$this->db->query("update cms_customer_profile_wo set status_rekening = 'AYDA' where no_rekening = '$norekening'");
		$this->db->query("update cms_customer_profile set status_rekening = 'AYDA' where no_rekening = '$norekening'");
		$this->db->query("update cms_assignment set assigned_fc = '' where no_rekening = '$norekening'");
		$this->db->query("delete from cms_assignment_agent where no_rekening = '$norekening'");
		/*
		$this->db->where('id', $psd_data['psd_id']);
		$this->db->set('flag_wo', '1');
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_account_last_status_tmp'); 
		*/

		return $return;
	}

	function edit_flag_sp($sp_id)
	{
		$return = $this->db->delete('cms_letter_history', array('id' => $sp_id)); 
		$return = $this->db->delete('cms_contact_history', array('id' => $sp_id)); 
		
		$this->db->query("replace into cms_letter_history select * from cms_letter_history_tmp where id='".$sp_id."'");
		$this->db->query("replace into cms_contact_history select * from cms_contact_history_tmp where id='".$sp_id."'");
		
		$return = $this->db->delete('cms_letter_history_tmp', array('id' => $sp_id)); 
		$return = $this->db->delete('cms_contact_history_tmp', array('id' => $sp_id)); 
		
		$this->db->where('id', $sp_id);
		$this->db->set('flag_tmp', '1');
		$this->db->set('created_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_letter_history'); 

		$this->db->where('id', $sp_id);
		$this->db->set('flag_tmp', '1');
		$this->db->set('created_time', date('Y-m-d H:i:s'));
		$return = $this->db->update('cms_contact_history'); 
	
		return $return;
	}

	function edit_flag_ayda($ayda_data)
	{
		//log_message('debug',__METHOD__);
		//$this->db->query(" replace into cms_account_last_status select * from cms_account_last_status_temp where no_rekening='".$psd_data["psd_id"]."'");
		$this->db->where('id', $ayda_data["ayda_id"]);
		$this->db->set('updated_by', $this->session->userdata('USER_ID'));
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		//$this->db->set('area_id', $kcu_data["area_id"]);
		//$this->db->set('area_name', $kcu_data["area_name"]);
		//$this->db->set('kcu_id', $kcu_data["kcu_id"]);
		$this->db->set('flag','1');
		$return = $this->db->update('cms_ayda_payment'); 
	
		//$return = $this->db->delete('cms_account_last_status_temp', array('no_rekening' => $psd_data["psd_id"])); 		
		return $return;
	}

	function delete_ayda($ayda_data)
	{
		$this->db->where('id', $ayda_data["ayda_id"]);
		$this->db->set('updated_by', $this->session->userdata('USER_ID'));
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('flag','2');
		$return = $this->db->update('cms_ayda_payment'); 

		$this->db->where('id', $ayda_data["ayda_id"]);
		$this->db->set('updated_by', $this->session->userdata('USER_ID'));
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('flag','2');
		
		$return = $this->db->update('cms_ayda_payment_history'); 
		//$return = $this->db->query("replace into cms_ayda_payment_history select * from cms_ayda_payment where id='".$ayda_data["ayda_id"]."'");	
		//$return = $this->db->delete('cms_ayda_payment', array('id' => $ayda_data["ayda_id"])); 
		return $return;
	}

	function delete_psd($psd_data)
	{

		$query  = "SELECT id, no_rekening,wo_status,ayda_status,flag_wo,flag_ayda FROM cms_account_last_status WHERE no_rekening ='".$psd_data["no_rekening"] ."'";
		$result = mysql_query($query) or die(mysql_error());
		//echo $query;

		while($row = mysql_fetch_assoc($result)) {
			$id_main = $row['id'];
			$main_wo_status = $row['wo_status'];
			$main_flag_wo = $row['flag_wo'];
			$main_ayda_status = $row['ayda_status'];
			$main_flag_ayda = $row['flag_ayda'];
		}

		$query  = "SELECT id, no_rekening,wo_status,ayda_status,flag_wo,flag_ayda FROM cms_account_last_status_tmp WHERE no_rekening ='".$psd_data["no_rekening"] ."'";
		$result = mysql_query($query) or die(mysql_error());

		while($row = mysql_fetch_assoc($result)) {
			$id_tmp = $row['id'];
			$tmp_wo_status = $row['wo_status'];
			$tmp_flag_wo = $row['flag_wo'];
			$tmp_ayda_status = $row['ayda_status'];
			$tmp_flag_ayda = $row['flag_ayda'];

		}

		//echo $psd_data["no_rekening"]; 
		//echo $id_tmp; 

		//echo @$id_tmp; 

		//jika id pada tmp == main
		if(@$id_tmp == @$id_main){
			if($tmp_flag_wo == '1') { //sebelumnya sudah WO approve berarti AYDA saja yang direject
				$this->db->where('id', $psd_data["psd_id"]);
				$this->db->set('updated_by', $this->session->userdata('USER_ID'));
				$this->db->set('updated_time', date('Y-m-d H:i:s'));
				$this->db->set('flag_ayda','2');
				$this->db->set('ayda_status','');
				//$this->db->set('wo_status','0');
				$return = $this->db->update('cms_account_last_status_tmp'); 
				$this->db->where('id', $psd_data["psd_id"]);
				$this->db->set('updated_by', $this->session->userdata('USER_ID'));
				$this->db->set('updated_time', date('Y-m-d H:i:s'));
				$this->db->set('flag_ayda','2');
				$this->db->set('ayda_status','');
				//$this->db->set('wo_status','0');
				$return = $this->db->update('cms_account_last_status'); 
				
			}else{ //wo reject ayda reject
					$this->db->where('id', $psd_data["psd_id"]);
					$this->db->set('updated_by', $this->session->userdata('USER_ID'));
					$this->db->set('updated_time', date('Y-m-d H:i:s'));
					$this->db->set('flag_wo','2');
					$this->db->set('wo_status','');
					
					if($tmp_ayda_status == 'on'){
						$this->db->set('flag_ayda','2');
						$this->db->set('ayda_status','0');
					}
					//$this->db->set('wo_status','0');
					$return = $this->db->update('cms_account_last_status'); 

					$this->db->where('id', $psd_data["psd_id"]);
					$this->db->set('updated_by', $this->session->userdata('USER_ID'));
					$this->db->set('updated_time', date('Y-m-d H:i:s'));
					$this->db->set('flag_wo','2');
					$this->db->set('wo_status','');
					if($tmp_ayda_status == 'on'){
						$this->db->set('flag_ayda','2');
						$this->db->set('ayda_status','');
					}
					//$this->db->set('wo_status','0');
					$return = $this->db->update('cms_account_last_status_tmp'); 
			}
/*			$this->db->where('id', $psd_data["psd_id"]);
			$this->db->set('updated_by', $this->session->userdata('USER_ID'));
			$this->db->set('updated_time', date('Y-m-d H:i:s'));
			$this->db->set('flag_wo','2');
			//$this->db->set('wo_status','0');
			$return = $this->db->update('cms_account_last_status_tmp'); 
*/			

//			$return = $this->db->delete('cms_account_last_status', array('id' => $psd_data["psd_id"])); //buat delete lagi yg id UUID ikut yg tmp
			$this->db->query("replace into cms_account_last_status_history select * from cms_account_last_status_tmp where id='".$psd_data["psd_id"]."'");	
			$return = $this->db->delete('cms_account_last_status_tmp', array('id' => $psd_data["psd_id"])); 
			return $return;
		}
		else if(@$id_tmp != @$id_main){
			if($tmp_flag_wo == '1') { //sebelumnya sudah WO approve berarti AYDA saja yang direject
				$this->db->where('id', $psd_data["psd_id"]);
				$this->db->set('updated_by', $this->session->userdata('USER_ID'));
				$this->db->set('updated_time', date('Y-m-d H:i:s'));
				$this->db->set('flag_ayda','2'); 
				//$this->db->set('wo_status','0');
				$this->db->set('ayda_status','');
				$return = $this->db->update('cms_account_last_status_tmp'); 

				$this->db->where('id', $psd_data["psd_id"]);
				$this->db->set('updated_by', $this->session->userdata('USER_ID'));
				$this->db->set('updated_time', date('Y-m-d H:i:s'));
				$this->db->set('flag_ayda','2'); 
				//$this->db->set('wo_status','0');
				$this->db->set('ayda_status','');
				$return = $this->db->update('cms_account_last_status'); 
				
			}else{//wo reject ayda reject
				$this->db->where('id', $psd_data["psd_id"]);
				$this->db->set('updated_by', $this->session->userdata('USER_ID'));
				$this->db->set('updated_time', date('Y-m-d H:i:s'));
				if($tmp_ayda_status == 'on'){
						$this->db->set('flag_ayda','2');
						$this->db->set('ayda_status','');
				}
				$this->db->set('wo_status','');
				$return = $this->db->update('cms_account_last_status_tmp'); 

				$this->db->where('id', $psd_data["psd_id"]);
				$this->db->set('updated_by', $this->session->userdata('USER_ID'));
				$this->db->set('updated_time', date('Y-m-d H:i:s'));
				if($tmp_ayda_status == 'on'){
						$this->db->set('flag_ayda','2');
						$this->db->set('ayda_status','');
				}
				$this->db->set('wo_status','');
				$return = $this->db->update('cms_account_last_status'); 
				
				
			}

			//$return = $this->db->delete('cms_account_last_status', array('id' => $id_main)); //buat delete lagi yg id UUID ikut yg tmp
			$this->db->query("replace into cms_account_last_status_history select * from cms_account_last_status_tmp where id='".$psd_data["psd_id"]."'");	
			$return = $this->db->delete('cms_account_last_status_tmp', array('id' => $psd_data["psd_id"])); 
			return $return;
		}

		//jika id pada tmp != main
	}

	/*
	function delete_psd($psd_data)
	{
		//log_message('debug',__METHOD__);
		//$this->db->query(" replace into cms_account_last_status select * from cms_account_last_status_temp where no_rekening='".$psd_data["psd_id"]."'");
		$this->db->where('id', $psd_data["psd_id"]);
		$this->db->set('updated_by', $this->session->userdata('USER_ID'));
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		//$this->db->set('area_id', $kcu_data["area_id"]);
		//$this->db->set('area_name', $kcu_data["area_name"]);
		//$this->db->set('kcu_id', $kcu_data["kcu_id"]);
		$this->db->set('flag_wo','2');
		$this->db->set('wo_status','0');
		$return = $this->db->update('cms_account_last_status'); 
	
		//$return = $this->db->delete('cms_account_last_status_temp', array('no_rekening' => $psd_data["psd_id"])); 		
		return $return;
	}
	*/

	function delete_wo($wo_data)
	{
		$this->db->where('id', $wo_data["wo_id"]);
		$this->db->set('updated_by', $this->session->userdata('USER_ID'));
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('flag_tmp','2');
		$return = $this->db->update('cms_wo_payment_history'); 

		//$this->db->query("replace into cms_wo_payment_history select * from cms_wo_payment where id='".$wo_data["wo_id"]."'");	
		$return = $this->db->delete('cms_wo_payment', array('id' => $wo_data["wo_id"])); 
		return $return;
	}
	
	function delete_ta($ta_id)
	{
		$return = $this->db->delete('cms_tambah_alamat', array('id' => $ta_id["id"])); 
				
		$this->db->where('id', $ta_id["id"]);
		$this->db->set('updated_by', $this->session->userdata('USER_ID'));
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('flag_tmp','2');
		$return = $this->db->update('cms_tambah_alamat_tmp'); 

		$this->db->query("replace into cms_tambah_alamat select * from cms_tambah_alamat_tmp where id='".$ta_id["id"]."'");	
		$return = $this->db->delete('cms_tambah_alamat_tmp', array('id' => $ta_id["id"])); 
		return $return;
	}
	
	function delete_sp($sp_data)
	{
		$this->db->where('id', $sp_data["sp_id"]);
		//$this->db->set('created_by', $this->session->userdata('USER_ID'));
		$this->db->set('created_time', date('Y-m-d H:i:s'));
		$this->db->set('flag_tmp','2');
		$return = $this->db->update('cms_letter_history'); 

		$this->db->query("replace into cms_letter_history select * from cms_letter_history_tmp where id='".$sp_data["sp_id"]."'");	
		$return = $this->db->delete('cms_letter_history_tmp', array('id' => $sp_data["sp_id"]));

		$this->db->where('id', $sp_data["sp_id"]);
		//$this->db->set('created_by', $this->session->userdata('USER_ID'));
		$this->db->set('created_time', date('Y-m-d H:i:s'));
		$this->db->set('flag_tmp','2');
		$return = $this->db->update('cms_contact_history_tmp'); 

		$this->db->query("replace into cms_contact_history select * from cms_contact_history_tmp where id='".$sp_data["sp_id"]."'");	
		$return = $this->db->delete('cms_contact_history_tmp', array('id' => $sp_data["sp_id"]));
		
		return $return;
	}
	
	function delete_ik($ik_data)
	{
		$this->db->where('id', $ik_data["ik_id"]);
		//$this->db->set('updated_by', $this->session->userdata('USER_ID'));
		$this->db->set('created_time', date('Y-m-d H:i:s'));
		$this->db->set('flag_tmp','2');
		$return = $this->db->update('cms_contact_history_tmp'); 

		$this->db->query("replace into cms_contact_history select * from cms_contact_history_tmp where id='".$ik_data["ik_id"]."'");	
		$return = $this->db->delete('cms_contact_history_tmp', array('id' => $ik_data["ik_id"])); 
		return $return;
	}

		function delete_it($it_data)
	{
		$this->db->where('id', $it_data["it_id"]);
		//$this->db->set('updated_by', $this->session->userdata('USER_ID'));
		$this->db->set('created_time', date('Y-m-d H:i:s'));
		$this->db->set('flag_tmp','2');
		$return = $this->db->update('cms_contact_history_tmp'); 

		$this->db->query("replace into cms_contact_history select * from cms_contact_history_tmp where id='".$it_data["it_id"]."'");	
		$return = $this->db->delete('cms_contact_history_tmp', array('id' => $it_data["it_id"])); 
		return $return;
	}

	function save_input_kunjungan_edit($ik)
	{
		$this->db->delete('cms_contact_history', array('id' => $ik)); 
		
		$this->db->query("replace into cms_contact_history select * from cms_contact_history_tmp where id='".$ik."'");
		
		$this->db->delete('cms_contact_history_tmp', array('id' => $ik)); 

		$this->db->where('id', $ik);
		$this->db->set('created_time', date('Y-m-d H:i:s'));
		$this->db->set('flag_tmp', '1');
		$return = $this->db->update('cms_contact_history'); 
		
		return $return;
	}

		function save_input_telepon_edit($it)
	{
		$this->db->delete('cms_contact_history', array('id' => $it)); 
		
		$this->db->query("replace into cms_contact_history gselect * from cms_contact_history_tmp where id='".$it."'");
		
		$this->db->delete('cms_contact_history_tmp', array('id' => $it)); 

		$this->db->where('id', $it);
		$this->db->set('created_time', date('Y-m-d H:i:s'));
		$this->db->set('flag_tmp', '1');
		$return = $this->db->update('cms_contact_history'); 
		
		return $return;
	}

/*	
	function edit_flag_wo($wo_id,$no_rekening,$pokok,$bunga,$denda)
	{
		//Dapatkan data row yg dipilih di admin
		$this->db->select('no_rekening, tunggakan_pokok, tunggakan_bunga, denda, id');
		$this->db->where('no_rekening', $no_rekening); //no_rek harus sama dengan yg dipilih
		$return = $this->db->get('cms_customer_profile');
		$data = array_shift($return->result_array()); //buat ambil value hasil query

		//echo $wo_id. '|'; //tarik wo_id
		//echo $data['id'] . '|';	//id customer_profile_lama

		//Dapatkan data row yg diinpu dari deskcoll
		$this->db->select('no_rekening, pokok, bunga, denda, id');
		$this->db->where('no_rekening', $no_rekening); //no_rek harus sama dengan yg dipilih
		$return = $this->db->get('cms_wo_payment');
		$data2 = array_shift($return->result_array()); //buat ambil value hasil query
		
		//echo $data2['id'] . '|'; //id cms_wo_payment_lama

		//buat variabel kayak -> echo($data['no_rekening']);
		$pokok_wo = (int)$data2['pokok'];
		$bunga_wo = (int)$data2['bunga'];
		$denda_wo = (int)$data2['denda'];

		$pokok_cust = (int)$data['tunggakan_pokok'];
		$bunga_cust = (int)$data['tunggakan_bunga'];
		$denda_cust = (int)$data['denda'];

		//operasi hitung
		$total_pokok = $pokok_cust-$pokok_wo; 
		$total_bunga = $bunga_cust-$bunga_wo; 
		$total_denda = $denda_cust-$denda_wo; 
		
		//echo $pokok_wo . '|';
		//echo $pokok_cust . '|'; 
		//echo $total_pokok . '|';
		
		//update set hasil pokok, bunga, denda ke tabel cms_customer_profile
		// 1 orang 1 no_rekening
		$this->db->where('no_rekening', $no_rekening);
		$this->db->set('tunggakan_pokok', $total_pokok); //baru bisa update total pada tunggakan_pokok
		$this->db->set('tunggakan_bunga', $total_bunga); //baru bisa update total pada tunggakan_pokok
		$this->db->set('denda', $total_denda); //baru bisa update total pada tunggakan_pokok
		$return = $this->db->update('cms_customer_profile');
		
		//$this->db->query("replace into cms_wo_payment_history select * from cms_wo_payment where no_rekening='".$no_rekening."'");

		//Data numpuk
		$return = $this->db->delete('cms_wo_payment_history', array('id' => $wo_id));
		
		$return = $this->db->query("replace into cms_wo_payment_history select * from cms_wo_payment where no_rekening='".$no_rekening."'");

		$return = $this->db->delete('cms_wo_payment', array('no_rekening' => $no_rekening));

		$this->db->where('id', $wo_id);
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('flag_tmp', '1');
		$return = $this->db->update('cms_wo_payment_history'); 
		
		return $return;		
	}
*/

function edit_flag_wo($wo_id,$no_rekening,$pokok,$bunga,$denda)
	{
		//Dapatkan data row yg dipilih di admin
		$this->db->select('no_rekening, tunggakan_pokok, tunggakan_bunga, denda');
		$this->db->where('no_rekening', $no_rekening); //no_rek harus sama dengan yg dipilih
		$return = $this->db->get('cms_customer_profile');
		$data = array_shift($return->result_array()); //buat ambil value hasil query

		//echo $wo_id. '|'; //tarik wo_id
		//echo $data['id'] . '|';	//id customer_profile_lama

		//Dapatkan data row yg diinpu dari deskcoll
		$this->db->select('no_rekening, pokok, bunga, denda, id');
		$this->db->where('id', $wo_id); //no_rek harus sama dengan yg dipilih
		$return = $this->db->get('cms_wo_payment');
		$data2 = array_shift($return->result_array()); //buat ambil value hasil query
		
		//echo $data2['id'] . '|'; //id cms_wo_payment_lama

		//buat variabel kayak -> echo($data['no_rekening']);
		$pokok_wo = (int)$data2['pokok'];
		$bunga_wo = (int)$data2['bunga'];
		$denda_wo = (int)$data2['denda'];

		$pokok_cust = (int)$data['tunggakan_pokok'];
		$bunga_cust = (int)$data['tunggakan_bunga'];
		$denda_cust = (int)$data['denda'];

		//operasi hitung
		$total_pokok = $pokok_cust-$pokok_wo; 
		$total_bunga = $bunga_cust-$bunga_wo; 
		$total_denda = $denda_cust-$denda_wo; 
		
		//echo $pokok_wo . '|';
		//echo $pokok_cust . '|'; 
		//echo $total_pokok . '|';
		
		//update set hasil pokok, bunga, denda ke tabel cms_customer_profile
		// 1 orang 1 no_rekening
/*		$this->db->where('no_rekening', $no_rekening);
		$this->db->set('tunggakan_pokok', $total_pokok); //baru bisa update total pada tunggakan_pokok
		$this->db->set('tunggakan_bunga', $total_bunga); //baru bisa update total pada tunggakan_pokok
		$this->db->set('denda', $total_denda); //baru bisa update total pada tunggakan_pokok
		$return = $this->db->update('cms_customer_profile');
*/		
		//$this->db->query("replace into cms_wo_payment_history select * from cms_wo_payment where no_rekening='".$no_rekening."'");

		//Data numpuk
//tri : sudah ada di history ketika input		
//		$return = $this->db->delete('cms_wo_payment_history', array('id' => $wo_id));
		
//		$return = $this->db->query("replace into cms_wo_payment_history select * from cms_wo_payment where id='".$wo_id."'");

		$return = $this->db->delete('cms_wo_payment', array('id' => $wo_id));

		$this->db->where('id', $wo_id);
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('flag_tmp', '1');
		$return = $this->db->update('cms_wo_payment_history'); 
		
		return $return;		
	}

	function save_edit_flag_ayda($wo_id,$no_rekening,$pokok,$bunga,$denda)
	{
	
		//Dapatkan data row yg dipilih di admin
		//Tri : tidak bisa mengurangi dari data customer karena setiap hari data direfresh
/*		$this->db->select('no_rekening, tunggakan_pokok, tunggakan_bunga, denda');
		$this->db->where('no_rekening', $no_rekening); //no_rek harus sama dengan yg dipilih
		$return = $this->db->get('cms_customer_profile');
		$data = array_shift($return->result_array()); //buat ambil value hasil query

		//Dapatkan data row yg diinpu dari deskcoll
		$this->db->select('no_rekening, pokok, bunga, denda, id');
		$this->db->where('id', $wo_id); //no_rek harus sama dengan yg dipilih
		$return = $this->db->get('cms_ayda_payment');
		$data2 = array_shift($return->result_array()); //buat ambil value hasil query

		//buat variabel kayak -> echo($data['no_rekening']);
		$pokok_wo = (int)$data2['pokok'];
		$bunga_wo = (int)$data2['bunga'];
		$denda_wo = (int)$data2['denda'];

		$pokok_cust = (int)$data['tunggakan_pokok'];
		$bunga_cust = (int)$data['tunggakan_bunga'];
		$denda_cust = (int)$data['denda'];

		//operasi hitung
		$total_pokok = $pokok_cust-$pokok_wo; 
		$total_bunga = $bunga_cust-$bunga_wo; 
		$total_denda = $denda_cust-$denda_wo; 
		
		//update set hasil pokok, bunga, denda ke tabel cms_customer_profile
		// 1 orang 1 no_rekening
		$this->db->where('no_rekening', $no_rekening);
		$this->db->set('tunggakan_pokok', $total_pokok); //baru bisa update total pada tunggakan_pokok
		$this->db->set('tunggakan_bunga', $total_bunga); //baru bisa update total pada tunggakan_pokok
		$this->db->set('denda', $total_denda); //baru bisa update total pada tunggakan_pokok
		$return = $this->db->update('cms_customer_profile');
*/
		//Data numpuk
		$return = $this->db->delete('cms_ayda_payment_history', array('id' => $wo_id));
		
		$return = $this->db->query("replace into cms_ayda_payment_history select * from cms_ayda_payment where id='".$wo_id."'");

		$return = $this->db->delete('cms_ayda_payment', array('id' => $wo_id));

		$this->db->where('id', $wo_id);
		$this->db->set('updated_time', date('Y-m-d H:i:s'));
		$this->db->set('flag', '1');
		$return = $this->db->update('cms_ayda_payment_history'); 
		
		return $return;		
	}

	function save_area_edit($area_data)
	{
		$return = $this->db->delete('cms_zip_reg_tmp', array('zip_reg' => $area_data['old_area_id'],'kelurahan' => $area_data['old_area_kel'])); 
		
		$this->db->query("insert into cms_zip_reg_tmp select * from cms_zip_reg where zip_reg='".$area_data["old_area_id"] ."' AND kelurahan='".$area_data["old_area_kel"] ."'");
		
		$this->db->where('zip_reg', $area_data["old_area_id"]);
		$this->db->where('kelurahan', $area_data["old_area_kel"]);
		$this->db->set('zip_reg', $area_data["zip_reg"]);
		$this->db->set('kodepos', $area_data["kodepos"]);
		$this->db->set('provinsi', $area_data["provinsi"]);
		$this->db->set('kabupaten', $area_data["kabupaten"]);
		$this->db->set('kecamatan', $area_data["kecamatan"]);
		$this->db->set('flag', $area_data["flag"]);
		$this->db->set('kelurahan', $area_data["kelurahan"]);
		$this->db->set('updated_time', $area_data["updated_time"]);
		// $this->db->set('bisnis_unit', $area_data["bisnis_unit"]);
		$this->db->set('flag_tmp', '0');
		$this->db->set('approval_notes', 'Edit');
		$return = $this->db->update('cms_zip_reg_tmp'); 
		
		return $return;	
	}

	function save_sub_area_edit($sub_area_data)
	{
		$return = $this->db->delete('cms_area_kcu_tmp', array('area_id' => $sub_area_data['old_area_id'])); 
		
		$this->db->query("replace into cms_sub_area_kcu_tmp select * from cms_sub_area_kcu where sub_area_id='".$sub_area_data["old_area_id"] ."'");
		
		$this->db->where('sub_area_id', $sub_area_data["old_area_id"]);
		$this->db->set('sub_area_name', $sub_area_data["sub_area_name"]);
		$this->db->set('flag', $sub_area_data["flag"]);
		$this->db->set('area_id', $sub_area_data["area_id"]);
		$this->db->set('updated_time', $sub_area_data["updated_time"]);
		$this->db->set('bisnis_unit', $sub_area_data["bisnis_unit"]);
		$this->db->set('flag_tmp', '0');
		$this->db->set('approval_notes', 'Edit');
		$return = $this->db->update('cms_sub_area_kcu_tmp'); 
		
		return $return;	
	}

	function save_kcu_add($kcu_data)
	{
		//log_message('debug',__METHOD__);
		
		$return = $this->db->insert('cms_kcu_tmp', $kcu_data); 
		//$return = $this->db->insert('cms_kcu', $kcu_data); 
		
		return $return;
	}
	function save_kcu_agency_add($kcu_data)
	{
		//log_message('debug',__METHOD__);
		
		$return = $this->db->insert('cms_kcu_agency_tmp', $kcu_data); 
		//$return = $this->db->insert('cms_kcu', $kcu_data); 
		
		return $return;
	}
	
	function save_custom_field_add($custom_field_data)
	{
		//log_message('debug',__METHOD__);
		
		$return = $this->db->insert('cc_custom_fields_tmp', $custom_field_data); 
		
	
		//$return = $this->db->insert('cms_kcu', $kcu_data); 
		
		return $return;
	}
	
	function save_master_regional_add($regional_data)
	{
		//log_message('debug',__METHOD__);
		
		$return = $this->db->insert('cms_master_regional_tmp', $regional_data); 
		//$return = $this->db->insert('cms_master_regional', $regional_data); 
		
		return $return;
	}
	
	function save_master_account_group_add($account_group_data)
	{
		//log_message('debug',__METHOD__);
		
		$return = $this->db->insert('cms_master_account_group_tmp', $account_group_data); 
		//$return = $this->db->insert('cms_master_regional', $regional_data); 
		
		return $return;
	}
	
	function get_kcu_list()
	{
		$aColumns				= array('id', 'kcu_id', 'kcu_name','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp', 'alamat');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0) && $iSortCol_0!= '' )
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		
		//$select = array('note_reject','DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','kcu_id as id','kcu_id','kcu_name','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
		$select = array('kcu_list','area','cabang','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','note_reject','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','kcu_id as id','kcu_id','kcu_name','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','zip_code_list', 'alamat');

		// Select Data
		$this->db->from('cms_kcu a');	
		

		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "a.flag_tmp = '1'";
		$this->db->order_by('a.created_time', 'desc');
		$this->db->where($where2);

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
	
	function get_kcu_agency_list()
	{
		$aColumns				= array('id', 'kcu_id', 'kcu_name','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp', 'alamat');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0) && $iSortCol_0!= '' )
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		
		//$select = array('note_reject','DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','kcu_id as id','kcu_id','kcu_name','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
		$select = array('kcu_list','area','cabang','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','note_reject','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','kcu_id as id','kcu_id','kcu_name','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','zip_code_list', 'alamat');

		// Select Data
		$this->db->from('cms_kcu_agency a');	
		

		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "a.flag_tmp = '1'";
		$this->db->order_by('a.created_time', 'desc');
		$this->db->where($where2);

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
		$arr_cabang = array();
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
			
			$arr_cabang = explode("|",$list[$row_number]['cell']['zip_code_list']);
			$arr_cabangs = array();
			for($i=0;$i<count($arr_cabang);$i++){
					$a = $this->common_model->get_record_values("cgid_name", "cms_subzipcode", "cgid = '".$arr_cabang[$i]."' ");
					$arr_cabangs[] = $a['cgid_name'];
			}
			$list[$row_number]['cell']['zip_code_list'] = implode("|", $arr_cabangs);
			
			$list_number++;
			$row_number++;
		}		
		// print_r($list);
		$output = array(
			'page'	=> $iDisplayStart,
			'total' => $total_pages,
			'rows'	=> $list,
			'records' => $iFilteredTotal
		);	
		echo json_encode($output); 					
	}
	
	function get_custom_field_list()
	{
		$aColumns				= array('field_name', 'field_label', 'field_type','field_length', 'table_destination', 'description', 'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		
		//$select = array('note_reject','DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','kcu_id as id','kcu_id','kcu_name','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
		$select =  array('field_name', 'field_label', 'field_type','field_length', 'table_destination', 'description', 'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');

		// Select Data
		$this->db->from('cc_custom_fields a');	
		

		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "a.flag_tmp = '1'";
		$this->db->order_by('a.created_time', 'desc');
		$this->db->where($where2);

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
				"id" => $aRow['field_name'], 
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
	
	function get_master_regional_list()
	{
		$aColumns				= array('id', 'name', 'real_name','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		
		//$select = array('note_reject','DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','kcu_id as id','kcu_id','kcu_name','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
		$select = array('id','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','name','real_name','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');

		// Select Data
		$this->db->from('cms_master_regional a');	
		

		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "a.flag_tmp = '1'";
		$this->db->order_by('a.created_time', 'desc');
		$this->db->where($where2);

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
	
	function master_account_group_list()
	{
		$aColumns				= array('id', 'description', 'payment_criteria','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		
		//$select = array('note_reject','DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','kcu_id as id','kcu_id','kcu_name','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
		$select = array('id','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','description','payment_criteria','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');

		// Select Data
		$this->db->from('cms_master_account_group a');	
		

		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "a.flag_tmp = '1'";
		$this->db->order_by('a.created_time', 'desc');
		$this->db->where($where2);

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
	
	function get_kcu_list_temp()
	{
		$aColumns				= array('id', 'kcu_id', 'DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','DATE_FORMAT(created_time,"%d-%b-%Y") created_time','kcu_name','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp, action', 'alamat');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0) && $iSortCol_0!='')
		{
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}
		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		
		//$select = array('id','note_reject','kcu_id','DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','kcu_id','kcu_name','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\"></span>") AS flag', 'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
		$select = array('id','area','cabang','zip_code_list','note_reject','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','kcu_id','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','kcu_id','kcu_name','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp, a.action', 'alamat');
		// Select Data
		$this->db->from('cms_kcu_tmp a');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "a.flag_tmp = '0'";
		$this->db->order_by('a.created_time', 'desc');
		$this->db->where($where2);

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
	function get_kcu_agency_list_temp()
	{
		$aColumns				= array('id', 'kcu_id', 'DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','DATE_FORMAT(created_time,"%d-%b-%Y") created_time','kcu_name','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp, action', 'alamat');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0) && $iSortCol_0!='')
		{
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}
		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		
		//$select = array('id','note_reject','kcu_id','DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','kcu_id','kcu_name','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\"></span>") AS flag', 'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
		$select = array('id','area','cabang','zip_code_list','note_reject','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','kcu_id','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','kcu_id','kcu_name','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp, a.action', 'alamat');
		// Select Data
		$this->db->from('cms_kcu_agency_tmp a');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "a.flag_tmp = '0'";
		$this->db->order_by('a.created_time', 'desc');
		$this->db->where($where2);

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
			$arr_cabang = "";
			$list[] = array(
				"id" => $aRow['id'], 
				"cell" => $aRow
			);
			
			$list[$row_number]["cell"]["list_number"] = ($list_number+1).".";
			
			//echo $list[$row_number]['cell']['zip_code_list'];
			
			$arr_cabang = explode("|",$list[$row_number]['cell']['zip_code_list']);
			
			
			$arr_cabangs = array();
			if($arr_cabang[0]!=""){
				for($i=0;$i<count($arr_cabang);$i++){
					$a = $this->common_model->get_record_values("cgid_name", "cms_subzipcode", "cgid = '".$arr_cabang[$i]."' ");
					// print_r($a);
					$arr_cabangs[] = $a['cgid_name'];
				}
				$list[$row_number]['cell']['zip_code_list'] = implode("|", $arr_cabangs);
			}else{
				$list[$row_number]['cell']['zip_code_list'] = "";
			}
			
			
			$list_number++;
			$row_number++;
		}		
		// echo "test";
		
		$output = array(
			'page'	=> $iDisplayStart,
			'total' => $total_pages,
			'rows'	=> $list,
			'records' => $iFilteredTotal
		);	
		
		// print_r($list);
		echo json_encode($output); 					
	}
	
	
	
	function get_custom_field_list_temp()
	{
		$aColumns				= array('field_name', 'field_label', 'field_type','field_length', 'table_destination', 'description', 'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','action');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			//$this->db->order_by($iSortCol_0, $iSortingCols);
		}
		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		
		//$select = array('id','note_reject','kcu_id','DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','kcu_id','kcu_name','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\"></span>") AS flag', 'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
		$select				= array('field_name', 'field_label', 'field_type','field_length', 'table_destination', 'description', 'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','action');
		// Select Data
		$this->db->from('cc_custom_fields_tmp a');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "a.flag_tmp = '0'";
		$this->db->order_by('a.created_time', 'desc');
		$this->db->where($where2);

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
				"id" => $aRow['field_name'], 
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
	
	function get_master_regional_list_temp()
	{
		$aColumns				= array('id', 'name', 'DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','DATE_FORMAT(created_time,"%d-%b-%Y") created_time','real_name','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp, action');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			//$this->db->order_by($iSortCol_0, $iSortingCols);
		}
		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		
		//$select = array('id','note_reject','kcu_id','DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','kcu_id','kcu_name','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\"></span>") AS flag', 'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
		$select = array('id','name','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','real_name','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp, a.action');
		// Select Data
		$this->db->from('cms_master_regional_tmp a');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "a.flag_tmp = '0'";
		$this->db->order_by('a.created_time', 'desc');
		$this->db->where($where2);

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
	
	function get_master_account_group_list_temp()
	{
		$aColumns				= array('id', 'description', 'DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','DATE_FORMAT(created_time,"%d-%b-%Y") created_time','payment_criteria','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp, action');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			//$this->db->order_by($iSortCol_0, $iSortingCols);
		}
		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		
		//$select = array('id','note_reject','kcu_id','DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','kcu_id','kcu_name','IF(flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\"></span>") AS flag', 'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
		$select = array('id','description','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','payment_criteria','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp, a.action');
		// Select Data
		$this->db->from('cms_master_account_group_tmp a');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$where2 = "a.flag_tmp = '0'";
		$this->db->order_by('a.created_time', 'desc');
		$this->db->where($where2);

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

	function get_area_list()
	{
		$aColumns				= array('a.zip_reg','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','kodepos', 'kabupaten', 'kecamatan','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','a.kelurahan','a.provinsi');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		
		$where2 = "a.flag_tmp = '1'";
		
		$select = $aColumns;

		// Select Data
		$this->db->from('cms_zip_reg a');
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->where($where2);
		
		if(!empty($where))
			$this->db->where($where);
		
		$rResult = $this->db->get();
		// echo $this->db->last_query();
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
		foreach($rResult->result_array() as $aRow)
		{
			$list[] = array(
				"id" => $aRow['zip_reg'], 
				"cell" => $aRow
			);
			
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
	
	function get_sub_area_list()
	{
		$aColumns				= array('a.id','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','sub_area_id', 'sub_area_name', 'concat(b.area_id," - ",b.area_name) AS area_name','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag','IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','a.zip_code');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		
		$where2 = "a.flag_tmp = '1'";
		
		$select = $aColumns;

		// Select Data
		$this->db->from('cms_sub_area_kcu a');
		$this->db->join('cms_area_kcu b','a.area_id=b.area_id',FALSE);	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->where($where2);
		
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
		foreach($rResult->result_array() as $aRow)
		{
			$list[] = array(
				"id" => $aRow['sub_area_id'], 
				"cell" => $aRow
			);
			
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

	function get_area_list_temp()
	{
		$aColumns				= array('a.zip_reg','a.kodepos', 'a.kabupaten','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','kecamatan','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','a.kelurahan', 'approval_notes','a.provinsi','a.id');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging TTT
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		
		$where2 = "a.flag_tmp = '0'";
		
		$select = $aColumns;

		// Select Data
		$this->db->from('cms_zip_reg_tmp a');
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->where($where2);
		
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
		foreach($rResult->result_array() as $aRow)
		{
			$list[] = array(
				"id" => $aRow['zip_reg'],
				"cell" => $aRow
			);
			
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

	function get_sub_area_list_temp()
	{
		$aColumns				= array('a.id','a.sub_area_id', 'a.sub_area_name','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','concat(a.area_id," - ",b.area_name) AS area_name','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','a.zip_code', 'a.approval_notes');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging TTT
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
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
					$fieldData = mysql_real_escape_string($rule->data);
		
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
		
		$where2 = "a.flag_tmp = '0'";
		
		$select = $aColumns;

		// Select Data
		$this->db->from('cms_sub_area_kcu_tmp a');
		$this->db->join('cms_area_kcu b','a.area_id=b.area_id',FALSE);
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->where($where2);
		
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
		foreach($rResult->result_array() as $aRow)
		{
			$list[] = array(
				"id" => $aRow['sub_area_id'],
				"cell" => $aRow
			);
			
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
	
	function get_psd_list_temp()
	{
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
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
			
			//$where = "$searchField $ops '$searchString'";
			if ($searchField == 'no_rekening'){
				//$group_kcu == 'concat(kcu_group.kcu_id, " - ", kcu_group.kcu_name)';
				$no_rekening = 'a.no_rekening';
				$where = "$no_rekening $ops '$searchString' ";
			}
			else {
				$where = "$searchField $ops '$searchString' "; 
			} 
		}
		
		$where2 = "flag_wo = '0'";
		$aColumns				= array('name','concat(b.kode_kcu," - ",kcu.kcu_name) as kcu_cabang', 'concat(c.area," - ",ar.area_name) as area', 'concat(kcu_group.kcu_id, " - ", kcu_group.kcu_name)kcu_group','IF(ayda_status = "on", "<span class=\"label label-sm label-success\">YES</span>", "<span class=\"label label-sm label-danger\">NO</span>") AS ayda_status','DATE_FORMAT(wo_status_time,"%d-%b-%Y") wo_status_time','IF(flag_ayda = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_ayda = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_ayda','ayda_status_time','a.id', 'a.no_rekening','first_name','DATE_FORMAT(wo_status_time,"%d-%b-%Y") wo_status_time','IF(flag_wo = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_wo = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_wo', 'wo_user','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','IF(wo_status = "on", "<span class=\"label label-sm label-success\">YES</span>", "<span class=\"label label-sm label-danger\">NO</span>") AS wo_status');
		
		$select = $aColumns;
		
		// Select Data
		$this->db->from('cms_account_last_status_tmp a');	
		$this->db->join('cms_customer_profile b','a.no_rekening=b.no_rekening',FALSE);
		
		if($this->session->userdata('ASSIGNMENT_TYPE') == "NON_OWNERSHIP"){
			$this->db->join('cms_assignment AS ass', 'b.no_rekening = ass.no_rekening', 'LEFT');
		}else{
			$this->db->join('cms_assignment_agent AS ass', 'b.no_rekening = ass.no_rekening', 'LEFT');
		}

		$this->db->join('cms_kcu_cabang AS kcu', 'b.kode_kcu = kcu.kcu_id', 'LEFT');
		$this->db->join('tmp_group_kcu_to_kcu map','b.kode_kcu = map.kcu_id and map.bisnis_unit="'.$this->session->userdata('PRODUK_GROUP') .'"','LEFT');
		$this->db->join('cms_kcu AS kcu_group', 'kcu_group.kcu_id = map.group_kcu_id and kcu_group.bisnis_unit="' .$this->session->userdata('PRODUK_GROUP') .'"','LEFT');
		$this->db->join('cc_user c','a.wo_user=c.id',FALSE);
		$this->db->join('cms_area_kcu AS ar', 'ass.assigned_area = area_id','LEFT');
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->where("(flag_wo = 0 or flag_ayda = 0)");
		$kode_product = array();
		$query = $this->db->query("select product_list,assignment_type from cms_bisnis_unit where bisnis_unit_id='".$this->session->userdata('PRODUK_GROUP')."'");
		foreach($query->result_array() as $aRow){
			//$kode_product[]= $aRow['product_id'];
			$kode_product= explode(";",$aRow['product_list']);
			$assignment_type = $aRow['assignment_type'];
		}
		$this->db->where_in("kode_produk",$kode_product);
		
		if(($this->session->userdata('GROUP_LEVEL')=="SUPERVISOR")||(($this->session->userdata('ASSIGNMENT_TYPE')!="NON_OWNERSHIP")&&($this->session->userdata('GROUP_LEVEL')=="AGENT"))){
			$this->db->where("kcu_group.kcu_id",$this->session->userdata('KCU'));			
		}

		
		if(($this->session->userdata('GROUP_LEVEL')=="COORDINATOR")||($this->session->userdata('GROUP_LEVEL')=="AGENT")||($this->session->userdata('GROUP_LEVEL')=="FC")){
			if($this->session->userdata('ASSIGNMENT_TYPE')=="NON_OWNERSHIP"){
			$this->db->where("assigned_area",$this->session->userdata('AREA'))			;
			}
		}

		
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
		foreach($rResult->result_array() as $aRow)
		{
			$list[] = array(
				"id" => $aRow['id'], 
				"cell" => $aRow
			);
			
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
	
	function get_ayda_list_temp()
	{
		$aColumns				= array(			'cust.no_cif','concat(kcu_group.kcu_id, " - ", kcu_group.kcu_name) group_kcu', 
			'cust.no_rekening', 
			'first_name','dpd','credit_line_number',
			'date_format(due_date,"%d-%b-%Y")due_date',
			'city','concat(kcu.kcu_id," - ",kcu.kcu_name) kcu',
			'zip_code','concat(assigned_area," - ",area_name)assigned_area',
			'b.id','concat(a.id," - ",a.name) as nama_petugas',
			'DATE_FORMAT(b.created_time,"%d-%b-%Y") created_time',
			'IF(b.flag = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(b.flag = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag', 'b.pokok','b.bunga','b.denda', 'b.user_id', 'DATE_FORMAT(b.tgl_bayar,"%d-%b-%Y") tgl_bayar');
		//$aColumns				= array('id','no_rekening','DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time','IF(b.flag = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(b.flag = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag', 'pokok','bunga','denda', 'user_id', 'DATE_FORMAT(tgl_bayar,"%d-%b-%Y") tgl_bayar');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
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
			
			//$where = "$searchField $ops '$searchString'";
			if ($searchField == 'nama_petugas'){
				$nama_petugas = 'concat(a.id, " - ", a.name)';
				$where = "$nama_petugas $ops '$searchString' ";
			}
			else if ($searchField == 'updated_time'){
				$updated_time = 'b.updated_time';
				$where = "$updated_time $ops '$searchString' ";
			}
			else {
				$where = "$searchField $ops '$searchString' "; 
			}  
		}
		
		$where2 = "b.flag is NULL";
		
		$select = $aColumns;

		// Select Data
		$this->db->from('cms_ayda_payment b');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->join('cc_user a','a.id=b.user_id',FALSE);
		$this->db->join('cms_customer_profile AS cust','cust.no_rekening = b.no_rekening');	
		$this->db->join('cms_assignment AS ass', 'cust.no_rekening = ass.no_rekening', 'LEFT');
		$this->db->join('cms_kcu_cabang AS kcu', 'cust.kode_kcu = kcu.kcu_id', 'LEFT');
		$this->db->join('cms_area_kcu AS ar', 'assigned_area = area_id','LEFT');
		$this->db->join('tmp_group_kcu_to_kcu map','cust.kode_kcu = map.kcu_id and map.bisnis_unit="'.$this->session->userdata('PRODUK_GROUP') .'"','LEFT');
		$this->db->join('cms_kcu AS kcu_group', 'map.group_kcu_id = kcu_group.kcu_id  and kcu_group.bisnis_unit="' .$this->session->userdata('PRODUK_GROUP') .'"','LEFT');
				$kode_product = array();
		$query = $this->db->query("select product_list,assignment_type from cms_bisnis_unit where bisnis_unit_id='".$this->session->userdata('PRODUK_GROUP')."'");
		$this->db->where("(b.pokok+b.bunga +b.denda)>0");
		foreach($query->result_array() as $aRow){
			//$kode_product[]= $aRow['product_id'];
			$kode_product= explode(";",$aRow['product_list']);
			$assignment_type = $aRow['assignment_type'];
		}

		$this->db->where_in("kode_produk",$kode_product);
		if(($this->session->userdata('GROUP_LEVEL')=="SUPERVISOR")||(($this->session->userdata('ASSIGNMENT_TYPE')!="NON_OWNERSHIP")&&($this->session->userdata('GROUP_LEVEL')=="AGENT"))){
			$this->db->where("kcu_group.kcu_id",$this->session->userdata('KCU'));			
		}

		
		if(($this->session->userdata('GROUP_LEVEL')=="COORDINATOR")||($this->session->userdata('GROUP_LEVEL')=="AGENT")||($this->session->userdata('GROUP_LEVEL')=="FC")){
			if($this->session->userdata('ASSIGNMENT_TYPE')=="NON_OWNERSHIP"){
			$this->db->where("assigned_area",$this->session->userdata('AREA'))			;
			}
		}

	$this->db->where($where2);
		
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
		foreach($rResult->result_array() as $aRow)
		{
			$list[] = array(
				"id" => $aRow['id'], 
				"cell" => $aRow
			);
			
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

	function get_wo_list_temp()
	{
		// $file_id = $this->input->get_post('file_id');
		$iDisplayStart = $this->input->get_post('page', true);
        $iDisplayLength = $this->input->get_post('rows', true);
        $iSortCol_0 = $this->input->get_post('sidx', true);
        $iSortingCols = $this->input->get_post('sord', true);
        $sSearch = $this->input->get_post('_search', true);
        $sEcho = $this->input->get_post('sEcho', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		$select = array(
			'cust.no_cif','concat(kcu_group.kcu_id, " - ", kcu_group.kcu_name) group_kcu', 
			'cust.no_rekening', 
			'first_name','dpd','credit_line_number',
			'date_format(due_date,"%d-%b-%Y")due_date',
			'city','concat(kcu.kcu_id," - ",kcu.kcu_name) kcu',
			'zip_code','concat(assigned_area," - ",area_name)assigned_area',
			'b.id',
			'b.no_rekening',
			'b.pokok',
			'b.bunga',
			'b.denda',
			'concat(a.id," - ",a.name) user_id',
			'DATE_FORMAT(tgl_bayar,"%d-%b-%Y") tgl_bayar',
			'DATE_FORMAT(b.created_time,"%d-%b-%Y") created_time',
			'IF(b.flag = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(b.flag = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag',
								);
								
		$this->db->from('cms_wo_payment b');
		$this->db->join('cc_user a','a.id=b.user_id',FALSE);
		$this->db->join('cms_customer_profile AS cust','cust.no_rekening = b.no_rekening');	
		$this->db->join('cms_assignment AS ass', 'cust.no_rekening = ass.no_rekening', 'LEFT');
		$this->db->join('cms_kcu_cabang AS kcu', 'cust.kode_kcu = kcu.kcu_id', 'LEFT');
		$this->db->join('cms_area_kcu AS ar', 'assigned_area = area_id','LEFT');
		$this->db->join('tmp_group_kcu_to_kcu map','cust.kode_kcu = map.kcu_id and map.bisnis_unit="'.$this->session->userdata('PRODUK_GROUP') .'"','LEFT');
		$this->db->join('cms_kcu AS kcu_group', 'map.group_kcu_id = kcu_group.kcu_id  and kcu_group.bisnis_unit="' .$this->session->userdata('PRODUK_GROUP') .'"','LEFT');
		$kode_product = array();
		$query = $this->db->query("select product_list,assignment_type from cms_bisnis_unit where bisnis_unit_id='".$this->session->userdata('PRODUK_GROUP')."'");
		foreach($query->result_array() as $aRow){
			//$kode_product[]= $aRow['product_id'];
			$kode_product= explode(";",$aRow['product_list']);
			$assignment_type = $aRow['assignment_type'];
		}
		$this->db->where("(b.pokok+b.bunga +b.denda)>0");
		$this->db->where_in("kode_produk",$kode_product);
		if(($this->session->userdata('GROUP_LEVEL')=="SUPERVISOR")||(($this->session->userdata('ASSIGNMENT_TYPE')!="NON_OWNERSHIP")&&($this->session->userdata('GROUP_LEVEL')=="AGENT"))){
			$this->db->where("kcu_group.kcu_id",$this->session->userdata('KCU'));			
		}

		
		if(($this->session->userdata('GROUP_LEVEL')=="COORDINATOR")||($this->session->userdata('GROUP_LEVEL')=="AGENT")||($this->session->userdata('GROUP_LEVEL')=="FC")){
			if($this->session->userdata('ASSIGNMENT_TYPE')=="NON_OWNERSHIP"){
			$this->db->where("assigned_area",$this->session->userdata('AREA'))			;
			}
		}

		//$this->db->where('no_rekening',$this->input->get_post('no_rekening'));
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		
		
        $rResult = $this->db->get();
        //echo $this->db->last_query();
		// Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
		
        $iFilteredTotal = $this->db->get()->row()->found_rows;
        // Total data set length	
		$iTotal = $rResult->num_rows();
        if( $iTotal > 0 ) {
            $total_pages = ceil($iFilteredTotal/$iDisplayLength);
        } else {
            $total_pages = 0;
        }
		
        // Output
		$output = array();

		$list = array();
        foreach($rResult->result_array() as $aRow)
        {
			$list[] = array(
						"id" => $aRow['id'], //utk menginisiasikan id = no_rekening di list
						"cell" => $aRow
					);
        }		
        $output = array(
            'page' => $iDisplayStart,
            'total' => $total_pages,
            'rows' => $list,
        );
		
        echo json_encode($output);
	}
	
	function get_ta_list_temp()
	{
		// $file_id = $this->input->get_post('file_id');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
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
			if($searchOper == 'cn' || $searchOper == 'eq' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';

			//$where = "$searchField $ops '$searchString' ";
			if ($searchField == 'kode_cabang'){
				$where = "kcu_name $ops '$searchString' "; 
			}
			else if ($searchField == 'assigned_fc'){
				$where = "usr.name $ops '$searchString' "; 
			}
			else {
				$where = "$searchField $ops '$searchString' "; 
			}
		}
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		$select = array(
			'his.id','cust.no_cif','concat(kcu_group.kcu_id, " - ", kcu_group.kcu_name) group_kcu', 
			'cust.no_rekening', 
			'first_name','dpd','credit_line_number',
			'date_format(due_date,"%d-%b-%Y")due_date',
			'city','concat(kcu.kcu_id," - ",kcu.kcu_name) kcu',
			'zip_code','concat(assigned_area," - ",area_name)assigned_area',
			'his.no_rekening',
			'his.nama_kontak',
			'his.telpon_rumah',
			'his.telpon_selular',
			'his.alamat_rumah',
			'his.user_id',
			'DATE_FORMAT(his.updated_time,"%d-%b-%Y") updated_time',
			'IF(his.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(his.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp',
								);
								
		$this->db->from('cms_tambah_alamat_tmp his');
		$this->db->join('cms_customer_profile AS cust','cust.no_rekening = his.no_rekening');	
		$this->db->join('cms_assignment AS ass', 'cust.no_rekening = ass.no_rekening', 'LEFT');
		$this->db->join('cms_kcu_cabang AS kcu', 'cust.kode_kcu = kcu.kcu_id', 'LEFT');
		$this->db->join('cms_area_kcu AS ar', 'assigned_area = area_id','LEFT');
		$this->db->join('tmp_group_kcu_to_kcu map','cust.kode_kcu = map.kcu_id and map.bisnis_unit="'.$this->session->userdata('PRODUK_GROUP') .'"','LEFT');
		$this->db->join('cms_kcu AS kcu_group', 'map.group_kcu_id = kcu_group.kcu_id  and kcu_group.bisnis_unit="' .$this->session->userdata('PRODUK_GROUP') .'"','LEFT');
		//$this->db->join('cc_user usr','usr.id=his.user_id',FALSE);

		$kode_product = array();
		$query = $this->db->query("select product_list,assignment_type from cms_bisnis_unit where bisnis_unit_id='".$this->session->userdata('PRODUK_GROUP')."'");
		foreach($query->result_array() as $aRow){
			//$kode_product[]= $aRow['product_id'];
			$kode_product= explode(";",$aRow['product_list']);
			$assignment_type = $aRow['assignment_type'];
		}

		$this->db->where_in("kode_produk",$kode_product);
		if(($this->session->userdata('GROUP_LEVEL')=="SUPERVISOR")||(($this->session->userdata('ASSIGNMENT_TYPE')!="NON_OWNERSHIP")&&($this->session->userdata('GROUP_LEVEL')=="AGENT"))){
			$this->db->where("kcu_group.kcu_id",$this->session->userdata('KCU'));			
		}

		
		if(($this->session->userdata('GROUP_LEVEL')=="COORDINATOR")||($this->session->userdata('GROUP_LEVEL')=="AGENT")||($this->session->userdata('GROUP_LEVEL')=="FC")){
			if($this->session->userdata('ASSIGNMENT_TYPE')=="NON_OWNERSHIP"){
			$this->db->where("assigned_area",$this->session->userdata('AREA'))			;
			}
		}

		//$this->db->where('no_rekening',$this->input->get_post('no_rekening'));
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
//		$this->db->where('nama_kontak !=""');
//		$this->db->where('telpon_selular !=""');
//		$this->db->where('telpon_rumah !=""');
//		$this->db->where('alamat_rumah !=""');
		$this->db->where("(his.nama_kontak != '' or his.telpon_kantor !='' or his.telpon_rumah !='' or his.telpon_selular !='' or his.alamat_kantor !='' or his.alamat_rumah !='')");
		if(!empty($where))
			$this->db->where($where);
		
        $rResult = $this->db->get();
        //echo $this->db->last_query();
		// Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
		
        $iFilteredTotal = $this->db->get()->row()->found_rows;
        // Total data set length	
		$iTotal = $rResult->num_rows();
        if( $iTotal > 0 ) {
            $total_pages = ceil($iFilteredTotal/$iDisplayLength);
        } else {
            $total_pages = 0;
        }
		
        // Output
		$output = array();

		$list = array();
        foreach($rResult->result_array() as $aRow)
        {
			$list[] = array(
						"id" => $aRow['id'], //utk menginisiasikan id = no_rekening di list
						"cell" => $aRow
					);
        }		
        $output = array(
            'page' => $iDisplayStart,
            'total' => $total_pages,
            'rows' => $list,
        );
		
        echo json_encode($output);
	}

function get_sp_list_temp()
	{
		// $file_id = $this->input->get_post('file_id');
		$iDisplayStart = $this->input->get_post('page', true);
        $iDisplayLength = $this->input->get_post('rows', true);
        $iSortCol_0 = $this->input->get_post('sidx', true);
        $iSortingCols = $this->input->get_post('sord', true);
        $sSearch = $this->input->get_post('_search', true);
        $sEcho = $this->input->get_post('sEcho', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}

		$select = array(
								'id', 
								'jenis_sp', 
								'ttd_sp', 
								'user_id',
								'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp', 
								'DATE_FORMAT(created_time,"%d-%b-%Y") created_time'
						);
					
		$this->db->from('cms_letter_history_tmp');

		//$this->db->where('no_rekening',$this->input->get_post('no_rekening'));
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		
		
        $rResult = $this->db->get();
        //echo $this->db->last_query();
		// Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
		
        $iFilteredTotal = $this->db->get()->row()->found_rows;
        // Total data set length	
		$iTotal = $rResult->num_rows();
        if( $iTotal > 0 ) {
            $total_pages = ceil($iFilteredTotal/$iDisplayLength);
        } else {
            $total_pages = 0;
        }
		
        // Output
		$output = array();

		$list = array();
        foreach($rResult->result_array() as $aRow)
        {
			$list[] = array(
						"id" => $aRow['id'], //utk menginisiasikan id = no_rekening di list
						"cell" => $aRow
					);
        }		
        $output = array(
            'page' => $iDisplayStart,
            'total' => $total_pages,
            'rows' => $list,
        );
		
        echo json_encode($output);
	}
	
	function get_input_telepon_list_temp()
	{
		// $file_id = $this->input->get_post('file_id');
		$iDisplayStart = $this->input->get_post('page', true);
        $iDisplayLength = $this->input->get_post('rows', true);
        $iSortCol_0 = $this->input->get_post('sidx', true);
        $iSortingCols = $this->input->get_post('sord', true);
        $sSearch = $this->input->get_post('_search', true);
        $sEcho = $this->input->get_post('sEcho', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		$select = array(
			'DATE_FORMAT(tgl_kunjungan,"%d-%b-%Y") tgl_kunjungan',
			'tujuan',
			'nama_petugas',
			'no_yang_dihubungi',
			'nama_yang_dihubungi',	
			'hasil_telpon',
			'nominal_bayar',
			'janji_bayar',
			'rekomendasi',
			'catatan',
			'bidang_usaha_saat_ini',
			'id',
			'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp',
								);
					
		$this->db->from('cms_contact_history_tmp');
		$this->db->where("kode_input = 2");
		$this->db->order_by('created_time', 'desc');
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		
		
        $rResult = $this->db->get();
        //echo $this->db->last_query();
		// Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
		
        $iFilteredTotal = $this->db->get()->row()->found_rows;
        // Total data set length	
		$iTotal = $rResult->num_rows();
        if( $iTotal > 0 ) {
            $total_pages = ceil($iFilteredTotal/$iDisplayLength);
        } else {
            $total_pages = 0;
        }
		
        // Output
		$output = array();

		$list = array();
        foreach($rResult->result_array() as $aRow)
        {
			$list[] = array(
						//"id" => $aRow['id'], 
						"cell" => $aRow
					);
        }		
        $output = array(
            'page' => $iDisplayStart,
            'total' => $total_pages,
            'rows' => $list,
        );
		
        echo json_encode($output);
			
			
	}

	function get_input_kunjungan_list_temp()
	{
		// $file_id = $this->input->get_post('file_id');
		$iDisplayStart = $this->input->get_post('page', true);
        $iDisplayLength = $this->input->get_post('rows', true);
        $iSortCol_0 = $this->input->get_post('sidx', true);
        $iSortingCols = $this->input->get_post('sord', true);
        $sSearch = $this->input->get_post('_search', true);
        $sEcho = $this->input->get_post('sEcho', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		$select = array(
			'IF(flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp',
			'no_rekening',
			'DATE_FORMAT(tgl_kunjungan,"%d-%b-%Y") tgl_kunjungan',
			//'tgl_kunjungan',
			'nama_petugas',
			'tugas_kunjungan',
			'tempat_dikunjungi',
			'nama_yang_ditemui',
			'hubungan',
			'hasil_kunjungan',
			'nominal_bayar',
			'DATE_FORMAT(janji_bayar,"%d-%b-%Y") janji_bayar',
			//'janji_bayar',
			'rencana_penagihan',
			'catatan',
			'bidang_usaha_saat_ini',
			'created_time',
			'id',
								);
					
		$this->db->from('cms_contact_history_tmp');
		$this->db->where("(kode_input = 1 or kode_input is null)");	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->order_by('created_time', 'desc');
		
        $rResult = $this->db->get();
        //echo $this->db->last_query();
		// Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
		
        $iFilteredTotal = $this->db->get()->row()->found_rows;
        // Total data set length	
		$iTotal = $rResult->num_rows();
        if( $iTotal > 0 ) {
            $total_pages = ceil($iFilteredTotal/$iDisplayLength);
        } else {
            $total_pages = 0;
        }
		
        // Output
		$output = array();

		$list = array();
        foreach($rResult->result_array() as $aRow)
        {
			$list[] = array(
						//"id" => $aRow['id'], 
						"cell" => $aRow
					);
        }		
        $output = array(
            'page' => $iDisplayStart,
            'total' => $total_pages,
            'rows' => $list,
        );
		
        echo json_encode($output);
	}

	function get_general_setting()
	{
		$this->db->from('aav_configuration');	
		$this->db->select('id, value', false);
		
		$result = $this->db->get();
		$data		= $result->result_array();
		
		$arr_data = array();
		
		foreach ($data AS $row)
		{
			$arr_data[$row["id"]] = $row["value"];
		}
		
		return $arr_data;
	}
	
	function get_ftp_setting()
	{
		$this->db->from('aav_configuration');	
		$this->db->select('id, value', false);
		$this->db->where('parameter','FTP');
		
		$result = $this->db->get();
		$data		= $result->result_array();
		
		$arr_data = array();
		
		foreach ($data AS $row)
		{
			$arr_data[$row["id"]] = $row["value"];
		}
		
		return $arr_data;
	}
	
	function get_system_configuration() 
	{
		$this->db->from('aav_configuration');	
		$this->db->select('id, value', false);
		
		$result = $this->db->get();
		$data		= $result->result_array();
		
		return $data;
	}
	
	function get_sms_info_for_aav_list()
	{
		$aColumns				= array('id', 'list_number', 'sms_info', 'sms_content');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		
		// Paging
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
			$ops = array(
				'eq' => '=', //equal
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
			if($searchOper == 'eq' ) $searchString = $searchString;
			if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
			if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
			if($searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';

			$where = "$searchField $ops '$searchString' "; 
		}
		
		$select = array('id', '"" AS list_number', 'info AS sms_info', 'content AS sms_content');

		// Select Data
		$this->db->from('aav_sms_info_for_aav');	
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->where('is_active = "1"');
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
	
	function save_sms_add($sms_data)
	{
		//log_message('debug',__METHOD__);
		
		$return = $this->db->insert('aav_sms_info_for_aav', $sms_data); 
		
		return $return;
	}
	
	function save_sms_edit($sms_data)
	{
		//log_message('debug',__METHOD__);
		
		$this->db->where('id', $sms_data["id"]);
		$return = $this->db->update('aav_sms_info_for_aav', $sms_data); 
		
		return $return;
	}
	
	function delete_sms($id)
	{
		$this->db->delete('aav_sms_info_for_aav', array('id' => $id)); 
	}
	
	function update_system_setting($param_data)
	{
		//log_message('debug',__METHOD__);
		
		//$this->db->where('parameter', "SYSTEM");
		//$this->db->where('id', $param_data["id"]);
		$param_data["parameter"] = "SYSTEM";

		$return = $this->db->replace('aav_configuration_tmp', $param_data); 
		
		return $return;
	}
	
	function update_ftp_setting($param_data)
	{
		//log_message('debug',__METHOD__);
		
		$this->db->where('parameter', "FTP");
		$this->db->where('id', $param_data["id"]);
		$return = $this->db->update('aav_configuration_tmp', $param_data); 
		
		return $return;
	}
	
	function approve_system_setting($param_data)
	{
		//log_message('debug',__METHOD__);
		$this->db->query("replace into aav_configuration select * from aav_configuration_tmp where parameter ='SYSTEM' and add_field1 = 'APPROVAL' and id='". $param_data["id"]."'");
		$this->db->where('parameter', "SYSTEM");
		$this->db->where('id', $param_data["id"]);
		$return = $this->db->update('aav_configuration_tmp', $param_data); 
		
		return $return;
	}
	
	function approve_ftp_setting($param_data)
	{
		//log_message('debug',__METHOD__);
		$this->db->query("replace into aav_configuration select * from aav_configuration_tmp where parameter ='FTP' and add_field1 = 'APPROVAL' and id='". $param_data["id"]."'");
		$this->db->where('parameter', "FTP");
		$this->db->where('id', $param_data["id"]);
		$return = $this->db->update('aav_configuration_tmp', $param_data); 
		
		return $return;
	}

	function reject_system_setting($param_data)
	{
		//log_message('debug',__METHOD__);
		
		$this->db->where('parameter', "SYSTEM");
		$this->db->where('id', $param_data["id"]);
		$return = $this->db->update('aav_configuration_tmp', $param_data); 
		
		return $return;
	}

	function reject_ftp_setting($param_data)
	{
		//log_message('debug',__METHOD__);
		
		$this->db->where('parameter', "FTP");
		$this->db->where('id', $param_data["id"]);
		$return = $this->db->update('aav_configuration_tmp', $param_data); 
		
		return $return;
	}

	function get_setup_angsuran_temp()
	{
		$aColumns				= array('a.id','a.area_id', 'a.area_name','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','concat(a.kcu_id," - ",b.kcu_name) AS kcu_name','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','c.description as bisnis_unit');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging TTT
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
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
			
			$where = "$searchField $ops '$searchString'";
		}
		
		$where2 = "a.add_field1 = 'APPROVAL'";
		
		$select = array('a.id','a.name','a.value','concat(b.id," - ",b.name) created_by','date_format(a.created_time,"%d %b %Y")created_time');

		// Select Data
		$this->db->from('aav_configuration_tmp a');
		$this->db->join('cc_user b','a.created_by=b.id',FALSE);
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->where($where2);
		$list_parameter = array('RAL_VISIT','MAX_REPO_DAYS','EXPIRED_RAL','EXPIRED_PHDP');
		$this->db->where_in("a.id",$list_parameter);
		
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
		foreach($rResult->result_array() as $aRow)
		{
			if($aRow['id']=='RAL_VISIT'){
				
				$sql="SET group_concat_max_len=4294967295;";
				$this->db->query($sql);
				$sql="select group_concat(description) description from cms_response_status where code in ('".str_replace(",","','",$aRow['value'])."')";
				$res=$this->db->query($sql);
				$row=$res->result_array();
				$aRow['value']=$row[0]['description'];
			}
			
			$list[] = array(
				"id" => $aRow['id'],
				"cell" => $aRow
			);
			
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

	function get_password_setting_temp()
	{
		$aColumns				= array('a.id','a.area_id', 'a.area_name','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','concat(a.kcu_id," - ",b.kcu_name) AS kcu_name','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','c.description as bisnis_unit');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging TTT
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
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
			
			$where = "$searchField $ops '$searchString'";
		}
		
		$where2 = "a.add_field1 = 'APPROVAL'";
		
		$select = array('a.id','a.name','a.value','concat(b.id," - ",b.name) created_by','date_format(a.created_time,"%d %b %Y")created_time');

		// Select Data
		$this->db->from('aav_configuration_tmp a');
		$this->db->join('cc_user b','a.created_by=b.id',FALSE);
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->where($where2);
		$this->db->where("a.id !=","ALERT_ANGSURAN");
		
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
		foreach($rResult->result_array() as $aRow)
		{
			$list[] = array(
				"id" => $aRow['id'],
				"cell" => $aRow
			);
			
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

	function get_ftp_setting_temp()
	{
		$aColumns				= array('a.id','a.area_id', 'a.area_name','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','concat(a.kcu_id," - ",b.kcu_name) AS kcu_name','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','c.description as bisnis_unit');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging TTT
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
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
			
			$where = "$searchField $ops '$searchString'";
		}
		
		$where2 = "a.add_field1 = 'APPROVAL'";
		
		$select = array('a.id','a.name','a.value','concat(b.id," - ",b.name) created_by','date_format(a.created_time,"%d %b %Y")created_time');

		// Select Data
		$this->db->from('aav_configuration_tmp a');
		$this->db->join('cc_user b','a.created_by=b.id',FALSE);
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->db->where($where2);
		$this->db->where("a.parameter","FTP");
		
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
		foreach($rResult->result_array() as $aRow)
		{
			$list[] = array(
				"id" => $aRow['id'],
				"cell" => $aRow
			);
			
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
	
	function get_balai_lelang_list()
	{
		// $aColumns				= array('a.id','a.area_id', 'a.area_name','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','concat(a.kcu_id," - ",b.kcu_name) AS kcu_name','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','c.description as bisnis_unit');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging TTT
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
			$filters = $this->input->get_post('filters', true);
			if($filters){
				$filters = json_decode($filters);
				$where = '';
				$whereArray = array();
				$rules = $filters->rules;
				$groupOperation = $filters->groupOp;
				
				foreach($rules as $rule) {
					
					$fieldName = $rule->field;
					//$fieldData = mysql_real_escape_string($rule->data);
					$fieldData = $rule->data;
					
					if($fieldName=='is_active'){
						$fieldName = "a.is_active";
					}else if($fieldName=='id'){
						$fieldName = "a.balai_id";
					}else if($fieldName=='balai_name'){
						$fieldName = "a.name";
					}else if($fieldName=='created_by'){
						$fieldName = "b.name";
					}else if($fieldName=='date(created_time)'){
						$fieldName = "date(a.created_time)";
					}
					$fieldOperation = $fieldName.' LIKE "%'.$fieldData.'%"';
					
					
					if($fieldOperation != '') $whereArray[] = $fieldOperation;
				}
				
				if (count($whereArray)>0) {
					$where .= join(' '.$groupOperation.' ', $whereArray);
				} 
				else {
					$where = '';
				}
			}
			
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
			
			// $where = "$searchField $ops '$searchString'";
			// $where .= "fieldOperation";
		}
		
		// $where2 = "a.is_active = '1'";
		
		$select = array('a.balai_id as id','a.name balai_name','a.address','b.name as created_by','date_format(a.created_time,"%d %b %Y")created_time' ,'if(a.is_active="1","<span class=\"label label-success\">ENABLE</span>","<span class=\"label label-default\">DISABLE</span>") AS is_active');

		// Select Data
		$this->db->from('cms_balai_lelang a');
		$this->db->join('cc_user b','a.created_by=b.id');
		// $this->db->where($where2);
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		
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
		foreach($rResult->result_array() as $aRow)
		{
			$list[] = array(
				"id" => $aRow['id'],
				"cell" => $aRow
			);
			
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
	function get_event_balai_lelang_list()
	{
		// $aColumns				= array('a.id','a.area_id', 'a.area_name','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','concat(a.kcu_id," - ",b.kcu_name) AS kcu_name','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','c.description as bisnis_unit');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging TTT
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
			$filters = $this->input->get_post('filters', true);
			if($filters){
				$filters = json_decode($filters);
				$where = '';
				$whereArray = array();
				$rules = $filters->rules;
				$groupOperation = $filters->groupOp;
				
				foreach($rules as $rule) {
					
					$fieldName = $rule->field;
					//$fieldData = mysql_real_escape_string($rule->data);
					$fieldData = $rule->data;
					
					if($fieldName=='is_active'){
						$fieldName = "b.is_active";
					}else if($fieldName=='id'){
						$fieldName = "a.balai_id";
					}else if($fieldName=='balai_name'){
						$fieldName = "a.name";
					}else if($fieldName=='created_by'){
						$fieldName = "c.name";
					}else if($fieldName=='date(created_time)'){
						$fieldName = "date(b.created_time)";
					}else if($fieldName=='name'){
						$fieldName = "b.name";
					}
					$fieldOperation = $fieldName.' LIKE "%'.$fieldData.'%"';
					
					
					if($fieldOperation != '') $whereArray[] = $fieldOperation;
				}
				
				if (count($whereArray)>0) {
					$where .= join(' '.$groupOperation.' ', $whereArray);
				} 
				else {
					$where = '';
				}
			}
			
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
			
			// $where = "$searchField $ops '$searchString'";
			// $where .= "fieldOperation";
		}
		
		// $where2 = "a.is_active = '1'";
		
		$select = array('b.id','a.name as balai_name','b.location','b.description','b.name','b.event_date','c.name as created_by','date_format(b.created_time,"%d %b %Y")created_time' ,'if(b.is_active="1","<span class=\"label label-success\">ENABLE</span>","<span class=\"label label-default\">DISABLE</span>") AS is_active');

		// Select Data
		$this->db->from('cms_balai_lelang a');
		$this->db->join('cms_balai_lelang_event b','a.balai_id=b.balai_id');
		$this->db->join('cc_user c','b.created_by=c.id');
		// $this->db->where($where2);
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		
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
		foreach($rResult->result_array() as $aRow)
		{
			$list[] = array(
				"id" => $aRow['id'],
				"cell" => $aRow
			);
			
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
	
	function get_bidder_list()
	{
		// $aColumns				= array('a.id','a.area_id', 'a.area_name','DATE_FORMAT(a.updated_time,"%d-%b-%Y") updated_time','DATE_FORMAT(a.created_time,"%d-%b-%Y") created_time','concat(a.kcu_id," - ",b.kcu_name) AS kcu_name','IF(a.flag = "1", "<span class=\"label label-sm label-success\">ENABLE</span>", "<span class=\"label label-sm label-danger\">DISABLE</span>") AS flag', 'IF(a.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(a.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>")) AS flag_tmp','c.description as bisnis_unit');
		$iDisplayStart	= $this->input->get_post('page', true);
		$iDisplayLength = $this->input->get_post('rows', true);
		$iSortCol_0			= $this->input->get_post('sidx', true);
		$iSortingCols		= $this->input->get_post('sord', true);
		$sSearch				= $this->input->get_post('_search', true);
		$sEcho					= $this->input->get_post('sEcho', true);
		$searchOper			= $this->input->get_post('searchOper', true);
		$searchString			= $this->input->get_post('searchString', true);
		$searchField			= $this->input->get_post('searchField', true);
		
		// Paging TTT
		if(isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->db->limit($this->db->escape_str($iDisplayLength), ($this->db->escape_str($iDisplayStart)-1) * $this->db->escape_str($iDisplayLength));
		}
		
		// Ordering
		if(isset($iSortCol_0))
		{
			
			$this->db->order_by($iSortCol_0, $iSortingCols);
		}

		
		if ($sSearch == 'true') {
			$filters = $this->input->get_post('filters', true);
			if($filters){
				$filters = json_decode($filters);
				$where = '';
				$whereArray = array();
				$rules = $filters->rules;
				$groupOperation = $filters->groupOp;
				
				foreach($rules as $rule) {
					
					$fieldName = $rule->field;
					//$fieldData = mysql_real_escape_string($rule->data);
					$fieldData = $rule->data;
					
					if($fieldName=='is_active'){
						$fieldName = "b.is_active";
					}else if($fieldName=='id'){
						$fieldName = "a.balai_id";
					}else if($fieldName=='balai_name'){
						$fieldName = "a.name";
					}else if($fieldName=='created_by'){
						$fieldName = "c.name";
					}else if($fieldName=='date(created_time)'){
						$fieldName = "date(b.created_time)";
					}else if($fieldName=='name'){
						$fieldName = "b.name";
					}
					$fieldOperation = $fieldName.' LIKE "%'.$fieldData.'%"';
					
					
					if($fieldOperation != '') $whereArray[] = $fieldOperation;
				}
				
				if (count($whereArray)>0) {
					$where .= join(' '.$groupOperation.' ', $whereArray);
				} 
				else {
					$where = '';
				}
			}
			
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
			
			// $where = "$searchField $ops '$searchString'";
			// $where .= "fieldOperation";
		}
		
		// $where2 = "a.is_active = '1'";
		
		$select = array('a.bidder_id as id','a.name','a.address','c.branch_name','d.area_name','b.name as created_by','phone_1','id_card','date_format(a.created_time,"%d %b %Y")created_time' ,'if(a.is_active="1","<span class=\"label label-success\">ENABLE</span>","<span class=\"label label-default\">DISABLE</span>") AS is_active');

		// Select Data
		$this->db->from('cms_bidder a');
		$this->db->join('cc_user b','a.created_by=b.id');
		$this->db->join('cms_branch c','a.branch_id=c.branch_id');
		$this->db->join('cms_area_branch d','a.area_id=d.area_id');
		// $this->db->where($where2);
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $select)), false);
		
		if(!empty($where))
			$this->db->where($where);
		
		$rResult = $this->db->get();
		// echo $this->db->last_query();;
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
		foreach($rResult->result_array() as $aRow)
		{
			$list[] = array(
				"id" => $aRow['id'],
				"cell" => $aRow
			);
			
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
	
}
