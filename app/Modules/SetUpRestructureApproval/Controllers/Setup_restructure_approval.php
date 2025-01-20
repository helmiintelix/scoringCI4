<?php 
namespace App\Modules\SetUpRestructureApproval\Controllers;
use App\Modules\SetUpRestructureApproval\Models\Setup_restructure_approval_model;
use CodeIgniter\Cookie\Cookie;
use CodeIgniter\Model;



class Setup_restructure_approval extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_restructure_approval_model = new Setup_restructure_approval_model();
	}

	function index()
	{
		
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\SetUpRestructureApproval\Views\Setup_approval_restructure_list_view',$data);
	}


	function get_list_approval_restructure()
	{
		
		$cache = session()->get('USER_ID').'_list_approval_restructure';

		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_restructure_approval_model->get_list_approval_restructure();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function add_approval_restructure(){
		$data['LIMIT_LEVEL_APPROVAL'] = $this->Common_model->get_record_value('value','aav_configuration','parameter="SYSTEM" and id = "LIMIT_LEVEL_APPROVAL" ');
		$data['LIMIT_USER_APPROVAL'] = $this->Common_model->get_record_value('value','aav_configuration','parameter="SYSTEM" and id = "LIMIT_USER_APPROVAL" ');
		$data['LIMIT_USER_CHECKER'] = $this->Common_model->get_record_value('value','aav_configuration','parameter="SYSTEM" and id = "LIMIT_USER_CHECKER" ');

		if($data['LIMIT_LEVEL_APPROVAL']==''){
			$data['LIMIT_LEVEL_APPROVAL'] = '99';
		}
		if($data['LIMIT_USER_APPROVAL']==''){
			$data['LIMIT_USER_APPROVAL'] = '99';
		}
		if($data['LIMIT_USER_CHECKER']==''){
			$data['LIMIT_USER_CHECKER'] = '99';
		}
		$data["user_list_approval"] = array("" => "[select User]")+ $this->Common_model->get_record_list("a.id value,concat(a.id , ' - ', a.name) AS item", "cc_user a", "a.is_active='1' ", "a.name asc");
		$builder = $this->db->table('cc_user a');
		$builder->join('cc_user_group b', 'a.group_id=b.id');
		$builder->where('b.level_group NOT IN (\'TELECOLL\', \'FIELD_COLL\') AND a.is_active = 1');
		$builder->orderBy('a.name', 'asc');
		$builder->select('a.id value, concat(a.id, \' - \', a.name) AS item');
		$result = $builder->get()->getResultArray();

		$formattedResult = ['' => '[Select User]'];
		foreach ($result as $row) {
			$formattedResult[$row['value']] = $row['item'];
		}
		$data["user_list_approval"] = $formattedResult;


		// $data["user_list_checker"] = array("" => "[select User]")+ $this->Common_model->get_record_list("a.id value,concat(a.id , ' - ', a.name) AS item", "cc_user a join cc_user_group b on a.group_id=b.id", "b.level_group not in ('TELECOLL','FIELD_COLL') and a.is_active='1' ", "a.name asc");		
		$builder = $this->db->table('cc_user a');
		$builder->join('cc_user_group b', 'a.group_id=b.id');
		$builder->where('b.level_group NOT IN (\'TELECOLL\', \'FIELD_COLL\') AND a.is_active = 1');
		$builder->orderBy('a.name', 'asc');
		$builder->select('a.id value, concat(a.id, \' - \', a.name) AS item');
		$result = $builder->get()->getResultArray();
		
		$formattedResult_checker = ['' => '[Select User]'];
		foreach ($result as $row) {
			$formattedResult_checker[$row['value']] = $row['item'];
		}
		$data["user_list_checker"] = $formattedResult_checker;
		
		return view('\App\Modules\SetUpRestructureApproval\Views\Setup_approval_restructure_add_form_view',$data);
	}

	function get_user_list(){
		$param = $this->input->getGet('param');

		if($param == 'checker'){
			$builder = $this->db->table('cc_user a');
			$builder->join('cc_user_group b', 'a.group_id=b.id');
			$builder->where('b.level_group NOT IN (\'TELECOLL\', \'FIELD_COLL\') AND a.is_active = 1');
			$builder->orderBy('a.name', 'asc');
			$builder->select('a.id value , a.name AS item');
			$result = $builder->get()->getResultArray();
			// $sql = "select a.id value , a.name AS item from cc_user a join cc_user_group b on a.group_id=b.id where a.is_active= ? and b.level_group not in ('TELECOLL','FIELD_COLL') order by a.name asc ";
		}else if($param == 'approval'){
			// $sql = "select a.id value , a.name AS item from cc_user a join cc_user_group b on a.group_id=b.id where a.is_active= ? and b.level_group not in ('TELECOLL','FIELD_COLL') order by a.name asc ";
			$builder = $this->db->table('cc_user a');
			$builder->join('cc_user_group b', 'a.group_id=b.id');
			$builder->where('b.level_group NOT IN (\'TELECOLL\', \'FIELD_COLL\') AND a.is_active = 1');
			$builder->orderBy('a.name', 'asc');
			$builder->select('a.id value , a.name AS item');
			$result = $builder->get()->getResultArray();
		}
		
		$user_list = $result;
		$data = array("success" => true, "message" => "success" , 'data'=>$user_list);
		echo json_encode($data);
	}

	function saveApprovalRestructure(){
		$data['disc_approval_name'] = $this->input->getPost('txt-disc-approval-name');
		$data['amount_from'] = $this->input->getPost('txt-dic-amount-from');
		$data['amount_from'] = str_replace(',','',$data['amount_from']);

		$data['amount_until'] = $this->input->getPost('txt-dic-amount-until');
		$data['amount_until'] = str_replace(',','',$data['amount_until']);

		$data['numOfLevel'] = $this->input->getPost('numLevel');
		$data['json_checker'] = json_encode($this->input->getPost('checked_by[]'));
		

		$data['is_active'] = $this->input->getPost('txt-disc-is-active');
		// $data['arrlevel'] = $this->input->getPost('arrlevel');
		$arrlevel = $this->input->getPost('arrlevel');
		$arrlevel = explode("|",$arrlevel);
		$arrLevelApproval = array();

		$n=1;
		foreach ($arrlevel as $key => $value) {
			$arr = $this->input->getPost($value);
			$arrLevelApproval['approval-by-'.$n] = $arr;
			$n++;
		}

		$data['json_approval'] = json_encode($arrLevelApproval);

		$data['id'] = uuid(false);
		$data['created_by'] = session()->get('USER_ID');
		$data['created_time'] = date('Y-m-d H:i:s');
		// print_r($data);
		// exit();
		
		$return	= $this->Setup_restructure_approval_model->saveApprovalRestructure_add($data);
		
		if($return){
			$data_log = array(
				"before" => null,
				"after" => $this->Common_model->get_record_values("disc_approval_name as `Disc Approval Name`, 
					amount_from as `Disc Amount From`, amount_until as `Disc Amount Until`,
					json_checker checker,json_approval approval,
					if(is_active=1, 'yes','no') is_active", 
					"setup_approval_restructure", 
					"id = '".$data["id"]."'",""),
				"approval" => null
				);
		
			$description = array(
				"action" => "add restructure/reschedule approval", 
				"before" => null, 
				"after" => $data_log['after'], 
				"status" => "success add", 
				"approval" => $data_log['approval']);				
			$description = json_encode($description);
			$this->Common_model->data_logging('SETUP RESTRUCTURE/RESCHEDULE APPROVAL', 'ADD RESTRUCTURE/RESCHEDULE APPROVAL', 'SUCCESS', $description);

			$rs = array('success' => true, 'message' => 'Success to save data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

	function edit_approval_restructure(){
        $data["id"] = $this->input->getGet('id');

		// $sql = "select * ,  FORMAT(amount_from,0) amtfrom ,  FORMAT(amount_until,0) amtuntil from setup_approval_diskon where id = ?";
		// $data['data'] = $this->db->query($sql, array($data['id']))->result_array()[0];
		$builder = $this->db->table('setup_approval_restructure a');
		$builder->select('* ,  FORMAT(amount_from,0) amtfrom ,  FORMAT(amount_until,0) amtuntil');
		$builder->where('id', $data['id']); // Gunakan parameter binding
		$data['data'] = $builder->get()->getResultArray()[0];

		if($data['data']['numOfLevel']>1){
			$data['arrlevel'] = 'approval-by-1[]';
			for ($i=2; $i <= $data['data']['numOfLevel']; $i++) { 
				$data['arrlevel'] .= '|approval-by-'.$i.'[]';
			}
		}else{
			$data['arrlevel'] = 'approval-by-1[]';
		}
		$data['data']['json_checker'] = json_decode($data['data']['json_checker']);
		$data['data']['json_approval'] = json_decode($data['data']['json_approval']);

		// $data["user_list_approval"] = array("" => "[select User]")+ $this->Common_model->get_record_list("a.id value,concat(a.id , ' - ', a.name) AS item", "cc_user a", "a.is_active='1' ", "a.name asc");
		$builder = $this->db->table('cc_user a');
		$builder->join('cc_user_group b', 'a.group_id=b.id');
		$builder->where('b.level_group NOT IN (\'TELECOLL\', \'FIELD_COLL\') AND a.is_active = 1');
		$builder->orderBy('a.name', 'asc');
		$builder->select('a.id value, concat(a.id, \' - \', a.name) AS item');
		$result = $builder->get()->getResultArray();

		$formattedResult = ['' => '[Select User]'];
		foreach ($result as $row) {
			$formattedResult[$row['value']] = $row['item'];
		}
		$data["user_list_approval"] = $formattedResult;


		// $data["user_list_checker"] = array("" => "[select User]")+ $this->Common_model->get_record_list("a.id value,concat(a.id , ' - ', a.name) AS item", "cc_user a join cc_user_group b on a.group_id=b.id", "b.level_group not in ('TELECOLL','FIELD_COLL') and a.is_active='1' ", "a.name asc");		
		$builder = $this->db->table('cc_user a');
		$builder->join('cc_user_group b', 'a.group_id=b.id');
		$builder->where('b.level_group NOT IN (\'TELECOLL\', \'FIELD_COLL\') AND a.is_active = 1');
		$builder->orderBy('a.name', 'asc');
		$builder->select('a.id value, concat(a.id, \' - \', a.name) AS item');
		$result = $builder->get()->getResultArray();

		$formattedResult_checker = ['' => '[Select User]'];
		foreach ($result as $row) {
			$formattedResult_checker[$row['value']] = $row['item'];
		}
		$data["user_list_checker"] = $formattedResult_checker;
		
		$data['LIMIT_LEVEL_APPROVAL'] = $this->Common_model->get_record_value('value','aav_configuration','parameter="SYSTEM" and id = "LIMIT_LEVEL_APPROVAL" ');
		$data['LIMIT_USER_APPROVAL'] = $this->Common_model->get_record_value('value','aav_configuration','parameter="SYSTEM" and id = "LIMIT_USER_APPROVAL" ');
		$data['LIMIT_USER_CHECKER'] = $this->Common_model->get_record_value('value','aav_configuration','parameter="SYSTEM" and id = "LIMIT_USER_CHECKER" ');

		if($data['LIMIT_LEVEL_APPROVAL']==''){
			$data['LIMIT_LEVEL_APPROVAL'] = '99';
		}
		if($data['LIMIT_USER_APPROVAL']==''){
			$data['LIMIT_USER_APPROVAL'] = '99';
		}
		if($data['LIMIT_USER_CHECKER']==''){
			$data['LIMIT_USER_CHECKER'] = '99';
		}

		return view('\App\Modules\SetUpRestructureApproval\Views\Setup_approval_restructure_edit_form_view',$data);
	}
	function saveEditApprovalRestructure(){
		$data['id'] = $this->input->getPost('id');
		$data['disc_approval_name'] = $this->input->getPost('txt-disc-approval-name');
		$data['amount_from'] = $this->input->getPost('txt-dic-amount-from');
		$data['amount_from'] = str_replace(',','',$data['amount_from']);

		$data['amount_until'] = $this->input->getPost('txt-dic-amount-until');
		$data['amount_until'] = str_replace(',','',$data['amount_until']);

		$data['numOfLevel'] = $this->input->getPost('numLevel');
		$data['json_checker'] = json_encode($this->input->getPost('checked_by[]'));
		

		$data['is_active'] = $this->input->getPost('txt-disc-is-active');
		// $data['arrlevel'] = $this->input->getPost('arrlevel');
		$arrlevel = $this->input->getPost('arrlevel');
		$arrlevel = explode("|",$arrlevel);
		$arrLevelApproval = array();

		$n=1;
		foreach ($arrlevel as $key => $value) {
			$arr = $this->input->getPost($value);
			$arrLevelApproval['approval-by-'.$n] = $arr;
			$n++;
		}

		$data['json_approval'] = json_encode($arrLevelApproval);

		$data['updated_by'] = session()->get('USER_ID');
		$data['updated_time'] = date('Y-m-d H:i:s');
		// print_r($data);
		// exit();
		
		$return	= $this->Setup_restructure_approval_model->saveApprovalRestructure_edit($data);
		
		if($return){
			$data_log = array(
				"before" => $this->Common_model->get_record_values("disc_approval_name as `Disc Approval Name`, 
					amount_from as `Disc Amount From`, amount_until as `Disc Amount Until`,
					json_checker checker,json_approval approval,
					if(is_active=1, 'yes','no') is_active", 
					"setup_approval_restructure", 
					"id = '".$data["id"]."'",""),
				"after" => $this->Common_model->get_record_values("disc_approval_name as `Disc Approval Name`, 
					amount_from as `Disc Amount From`, amount_until as `Disc Amount Until`,
					json_checker checker,json_approval approval,
					if(is_active=1, 'yes','no') is_active", 
					"setup_approval_restructure", 
					"id = '".$data["id"]."'",""),
				"approval" => null
				);
		
			$description = array(
				"action" => "add restructure/reschedule approval", 
				"before" => $data_log['before'], 
				"after" => $data_log['after'], 
				"status" => "success edit", 
				"approval" => null);				
			$description = json_encode($description);
			$this->Common_model->data_logging('SETUP RESTRUCTURE/RESCHEDULE APPROVAL', 'EDIT RESTRUCTURE/RESCHEDULE APPROVAL', 'SUCCESS', $description);

			$rs = array('success' => true, 'message' => 'Success to update data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
}