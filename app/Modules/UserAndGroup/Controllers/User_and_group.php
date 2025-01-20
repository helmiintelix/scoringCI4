<?php 
namespace App\Modules\UserAndGroup\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\UserAndGroup\Models\User_and_group_model;


class User_and_group extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->User_and_group_model = new User_and_group_model();
	}

	function user_management()
	{
		
		$data['classification'] = $this->input->getPost('classification');
		
		return view('\App\Modules\UserAndGroup\Views\user_management_view',$data);
	}

	function user_management_list()
	{
		
		$cache = session()->get('USER_ID').'_user_management_list';

		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->User_and_group_model->get_user_management_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function user_group_management_list_temp(){
	
		$data = $this->User_and_group_model->get_user_group_management_list_temp();
		$rs = array('success' => true, 'message' => '', 'data' => $data);
		return $this->response->setStatusCode(200)->setJSON($rs);		
	}

	function user_add_form(){
	
		$cache = session()->get('USER_ID').'_user_add_form';

		$data["spv_list"] = array("" => "[select data]") +  $this->Common_model->get_record_list("c.id AS value, c.name AS item", "cc_user c join cc_user_group g on c.group_id = g.id", "c.is_active = ? and g.level_group in('TEAM_LEADER','SUPERVISOR','MANAGER','ADMIN') ", "c.id",array('1'));
		$data["group_id_telecoll_list"] = $this->Common_model->get_record_list("id AS value, name AS item", "cc_user_group", "is_active = '1' and type_collection = 'TELECOLL' ", "id");
		$data["group_id_fieldcoll_list"] = $this->Common_model->get_record_list("id AS value, name AS item", "cc_user_group", "is_active = '1' and type_collection = 'FIELDCOLL' ", "id");
		
		// return view('\App\Modules\UserAndGroup\Views\user_add_form_view',$data, ['cache' => env('TIMECACHE_1'), 'cache_name' => $cache]);
		return view('\App\Modules\UserAndGroup\Views\user_add_form_view',$data);
	}

	function user_edit_form()
	{
		$data["id_user"] = $this->input->getGet('id');
		$data["user_data"] = $this->Common_model->get_record_values("id AS id_user, nik, employeeid, address, name AS name_user, password AS id_user_password, group_id AS user_level, report_to, is_active AS flag_active, kcu, area, agent_bucket, bisnis_unit, kcu_list,kuota_request_extend, kuota_keep_extend, email,handphone,template_email,template_sms,bucket ,receive_assigment,realocate_account,leave_start_date,image,supervisor_name,imei,join_date,type_collection,token", "cc_user", "id = '" . $this->db->escapeString($data["id_user"]) . "'");
		$data["spv_list"] =  $this->Common_model->get_ref_master("c.id AS value, c.name AS item", "cc_user c join cc_user_group g on (c.group_id=g.id and g.level_group in('TEAM_LEADER','SUPERVISOR','MANAGER','ADMIN'))", "c.is_active = '1'", "c.id");
		$data["group_id_telecoll_list"] = $this->Common_model->get_record_list("id AS value, name AS item", "cc_user_group", "is_active = '1' and type_collection ='TELECOLL' ", "id");
		$data["group_id_fieldcoll_list"] = $this->Common_model->get_record_list("id AS value, name AS item", "cc_user_group", "is_active = '1' and type_collection ='FIELDCOLL' ", "id");

		if(empty($data["user_data"])){
			echo "NOT FOUND";
		}else{
			return view('\App\Modules\UserAndGroup\Views\user_edit_form_view',$data);
		}
		
	}

	function save_user_add(){
		
		$is_exist = $this->User_and_group_model->isExist($this->input->getPost('txt-user-id-temp'));
	
		if(!$is_exist){
			$data['id'] = $this->input->getPost('txt-user-id-temp');
			$data['name'] = $this->input->getPost('txt-user-name');
			$data['email'] = $this->input->getPost('txt-email');
			$data['handphone'] = $this->input->getPost('txt-phone-number');
			$data['type_collection'] = $this->input->getPost('opt-type_collection');
			$data['group_id'] = $this->input->getPost('opt-user-level');
			$data['join_date'] = $this->input->getPost('join_date');
			$data['is_active'] = $this->input->getPost('opt-active-flag');
			
			if($this->input->getPost('txt-password')==''){
				$data["password"]	= md5($this->Common_model->get_record_value("value", "aav_configuration", "id='PASSWORD_DEFAULT'", ""));
			}else{
				$data['password'] = md5($this->input->getPost('txt-password'));
			}
			$data["notes"]	= 'ADD';
			$data["created_by"]	= session()->get('USER_ID');
			$data["created_time"]	= date('Y-m-d H:i:s');
			$data["updated_time"]	= date('Y-m-d H:i:s');
			
			$return	= $this->User_and_group_model->save_user_add($data);
			
			if($return){
				$rs = array('success' => true, 'message' => 'Success', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}

		}else{
			$rs = array('success' => false, 'message' => 'User ID Already Registered. Please insert another ID.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

	function user_management_temp(){
		return view('\App\Modules\UserAndGroup\Views\user_management_view_temp');
	}

	function save_user_edit(){
		
		$cek = $this->Common_model->get_record_value('id','cc_user',"id='{$this->input->getPost('txt-user-id')}'");
		
		if($cek==''){
			$rs = array('success' => false, 'message' => 'User Not Found', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$data = $this->Common_model->get_record_values('*','cc_user',"id='{$this->input->getPost('txt-user-id')}'");

			$data['id'] = $this->input->getPost('txt-user-id');
			$data['name'] = $this->input->getPost('txt-user-name');
			$data['email'] = $this->input->getPost('txt-email');
			$data['handphone'] = $this->input->getPost('txt-phone-number');
			$data['type_collection'] = $this->input->getPost('opt-type_collection');
			$data['group_id'] = $this->input->getPost('opt-user-level');
			$data['join_date'] = $this->input->getPost('join_date');
			$data['is_active'] = $this->input->getPost('opt-active-flag');
			
			if($this->input->getPost('txt-password')==''){
				// $data['password'] = $pass;
				
			}else{
				$data['password'] = md5($this->input->getPost('txt-password'));
			}

			

			$data["notes"]	= 'EDIT';
			$data["updated_by"] = session()->get('USER_ID');
			$data["updated_time"]	= date('Y-m-d H:i:s');

			$builder = $this->db->table('cc_user_tmp');

			// Check if record exists
			$exists = $builder->where('id', $data['id'])->countAllResults();

			if ($exists) {
				// Update record
				$return = $builder->update($data, ['id' => $data['id']]);
			} else {
				// Insert new record
				$return = $builder->insert($data);
			}

			if ($return) {
				$this->Common_model->data_logging('User Management', 'Edit user', 'SUCCESS', 'User ID: ' . $data["id"] . ', Name: ' . $data["name"]);
				$data	= array("success" => true, "message" => "Success");
			} else {
				$this->Common_model->data_logging('User Management', 'Edit user', 'SUCCESS', 'User ID: ' . $data["id"] . ', Name: ' . $data["name"]);
				$data	= array("success" => false, "message" => "Failed");
			}
			return $this->response->setStatusCode(200)->setJSON($data);	

		}
	}


	function user_group_management()
	{
		return view('\App\Modules\UserAndGroup\Views\user_group_management_view');
	}

	function user_group_management_list(){
		
		$data = $this->User_and_group_model->get_user_group_management_list();
		$rs = array('success' => true, 'message' => '', 'data' => $data);
		
		return $this->response->setStatusCode(200)->setJSON($rs);	
	}

	function user_group_add_form()
	{
		$data['level_master'] = array("" => "[SELECT DATA]") + $this->Common_model->get_record_list("id AS value, level_name AS item", "cc_user_level", "1=1 ", "id");
		return view('\App\Modules\UserAndGroup\Views\user_group_add_form_view',$data,['cache' => env('TIMECACHE_1')]);
	}

	function save_user_group_add(){
		$group_id = str_replace(' ', '_', strtoupper($this->request->getPost('txt-group-id')));
		$is_exist = $this->User_and_group_model->isExistGroupTmp($group_id);
		$listMenu = $this->request->getPost("menu-item");
		if (!$is_exist) {
			$user_data = [
				"id"              => $group_id,
				"name"            => strtoupper($this->request->getPost("txt-group-name")),
				"description"     => strtoupper($this->request->getPost("txt-group-description")),
				"level"       	  => $this->request->getPost('opt-heirarki'),
				"type_collection" => $this->request->getPost('opt-type_collection'),
				"menu_list"       => implode("|", $listMenu ?? []),
				"created_by"      => session()->get('USER_ID'),
				"created_time"    => date('Y-m-d H:i:s'),
				"is_active"		  => '1'
			];
	
			$return = $this->db->table('cc_user_group_tmp')->insert($user_data);
			
			if ($return) {
				$this->db->table('cc_user_group_menu_tmp')->where('group_id', $group_id)->delete();
				
				foreach ($listMenu as $key => $value) {
					$dataMenu['group_id'] = $group_id;
					$dataMenu['list_menu_id'] = $value;
					$dataMenu['is_active'] = '1';
					$dataMenu['created_by'] = session()->get('USER_ID');
					$dataMenu['created_time'] = date('Y-m-d H:i:s');
					$this->db->table('cc_user_group_menu_tmp')->insert($dataMenu);
			
				}


				$this->Common_model->data_logging(
					'User Group Management',
					'Add User Group',
					'SUCCESS',
					'Group ID: ' . $user_data["id"] . ', Name: ' . $user_data["name"] . ', Description: ' . $user_data["description"]
				);

				$data = ["success" => true, "message" => "Success"];
			} else {
				$this->Common_model->data_logging(
					'User Management',
					'Add user',
					'FAILED',
					'Group ID: ' . $user_data["id"] . ', Name: ' . $user_data["name"] . ', Description: ' . $user_data["description"]
				);

				$data = ["success" => false, "message" => "Failed"];
			}
		} else {
			$data = ["success" => false, "message" => "Group ID Already Registered. Please insert another ID."];
		}

		return $this->response->setStatusCode(200)->setJSON($data);
	}

	function user_group_management_temp(){
		return view('\App\Modules\UserAndGroup\Views\user_group_management_view_temp');
	}

	function user_group_edit_form(){
		$group_id = $this->input->getGet('id');
		$is_exist = $this->User_and_group_model->isExistGroupTmp($group_id);

		if(!$is_exist){
			$data['level_master'] = array("" => "[SELECT DATA]") + $this->Common_model->get_record_list("id AS value, level_name AS item", "cc_user_level", "1=1 ", "id");
			$data["user_data"] = $this->Common_model->get_record_values("id, name, description , menu_list, level,type_collection", "cc_user_group", "id = '{$group_id}' ");
			$data['ACTION'] = 'EDIT';
			return view('\App\Modules\UserAndGroup\Views\user_group_edit_form_view',$data);
		}else{
			echo "[GROUP ID ON APPROVAL]";
		}
	}

	function user_group_view_form(){
		$group_id = $this->input->getGet('id');
		$is_exist = $this->User_and_group_model->isExistGroupTmp($group_id);

		if($is_exist){
			$data['level_master'] = array("" => "[SELECT DATA]") + $this->Common_model->get_record_list("id AS value, level_name AS item", "cc_user_level", "1=1 ", "id");
			$data["user_data"] = $this->Common_model->get_record_values("id, name, description , menu_list, level,type_collection", "cc_user_group_tmp", "id = '{$group_id}' ");
			$data['ACTION'] = 'VIEW';
			return view('\App\Modules\UserAndGroup\Views\user_group_edit_form_view',$data);
		}else{
			echo "[NOT FOUND]";
		}
	}

	function approved_user_group()
	{
		$id = $this->request->getPost('id_user');

		// Fetch data by ID
		$data = $this->User_and_group_model->getUserGroupById($id);

		$array = [];

	

		foreach ($data as $value) {
			$array = [
				'id'            => $value['id'],
				'name'          => $value['name'],
				'description'   => $value['description'],
				'menu_list'     => $value['menu_list'],
				'created_by'    => $value['created_by'],
				'created_time'  => $value['created_time'],
				'updated_by'    => $value['updated_by'],
				'updated_time'  => $value['updated_time'],
				'type_collection'  => $value['type_collection'],
				'level'  => $value['level'],
			];
		}

		$response = [];

		try {
			// Check if group already exists
			if (count($this->User_and_group_model->getUserGroupIdBy($id)) === 0) {
				// Insert new group
				$rs = $this->db->table('cc_user_group')->insert($array);
			} else {
				// Update existing group
				$rs = $this->db->table('cc_user_group')
				->where('id', $id)
				->update($array);
			}
			if($rs){

				$this->builder = $this->db->table('cc_user_group_menu');
        
				$this->builder->where('group_id', $id);
				$this->builder->delete();

			
				$builder = $this->db->table('cc_user_group_menu_tmp');
				$builder->where('group_id', $id);
				$query = $builder->get();
		
				$datax = $query->getResultArray();
				foreach ($datax as $key => $value) {
					$dataMenu['group_id'] = $id;
					$dataMenu['list_menu_id'] = $value['list_menu_id'];
					$dataMenu['is_active'] = '1';
					$dataMenu['created_by'] = session()->get('USER_ID');
					$dataMenu['created_time'] = date('Y-m-d H:i:s');
					$this->db->table('cc_user_group_menu')->insert($dataMenu);
				}

				$builder = $this->db->table('cc_user_group_menu_tmp')->where('group_id',$id)->delete();
			}

			// Remove pending approval
			$this->db->table('cc_user_group_tmp')->where('id', $id)->delete();

			$this->cache->delete($id);

			// Log success
			$this->Common_model->data_logging('Group Management', 'Approve', 'Success', 'Approve group');
			$response = ["success" => true, "message" => "Approve berhasil"];
		} catch (\Exception $e) {
			// Log failure
			$this->Common_model->data_logging('Group Management', 'Approve', 'Failed', 'Approve group');
			$response = ["success" => false, "message" => "Approve gagal", "error" => $e->getMessage()];
		}

		return $this->response->setStatusCode(200)->setJSON($response);
	}

	function save_user_group_edit()
	{
		$user_data["id"]				= strtoupper($this->input->getPost('txt-group-id')??'');
		$user_data["name"]			= strtoupper($this->input->getPost("txt-group-name")??'');
		$user_data["description"]			= strtoupper($this->input->getPost("txt-group-description")??'');
		$user_data["updated_by"]			= session()->get('USER_ID');
		$user_data["menu_list"]	= implode("|", $this->input->getPost("menu-item"));
		$user_data["updated_time"]	= date('Y-m-d H:i:s');
		$user_data["is_active"]	= $this->input->getPost("opt-active-flag");
		$user_data["level_group"] = $this->input->getPost("opt-level_group");
		$user_data["type_collection"] = $this->input->getPost("opt-type_collection");
		// $user_data["level"] = $this->input->get_post("opt-priority", true);
		$user_data["bisnis_unit"] = $this->input->getPost("opt-bisnis_unit");
		$user_data["kuota_request_extend"] = $this->input->getPost("txt-kuota-request-extend");
		$user_data["kuota_keep_extend"] = $this->input->getPost("txt-kuota-keep-extend");
		$user_data["kuota_assignment"] = $this->input->getPost("txt-kuota-assignment");
		$user_data["kuota_account_request"] = $this->input->getPost("txt-kuota-account-request");
		$user_data["level_account_group"] = $this->input->getPost("opt-account-group");
		$user_data["level"] = $this->input->getPost("opt-heirarki");
		$user_data["flag"]	= 0;
		$user_data["notes"] = 'Edit';
		$return	= $this->User_and_group_model->save_user_group_edit($user_data);

		if ($return) {

			$this->Common_model->data_logging('User Group Management', 'Edit User Group', 'SUCCESS', 'Group ID: ' . $user_data["id"] . ', Name: ' . $user_data["name"]);
			$data		= array("success" => true, "message" => "Success");
		} else {
			$this->Common_model->data_logging('User Management', 'Add user', 'FAILED', 'Group ID: ' . $user_data["id"] . ', Name: ' . $user_data["name"]);
			$data		= array("success" => false, "message" => "Failed");
		}

		return $this->response->setStatusCode(200)->setJSON($data);
	}

	function reject_user_group()
	{

		try {
			$user_data["id"] = $this->input->getPost('id_user');
			$this->User_and_group_model->delete_user_group($user_data);
			$this->Common_model->data_logging('User Group Management', "Delete group", 'SUCESS', 'Group ID: ' . $user_data["id"]);
			$data = array("success" => true, "message" => "Success");
		} catch (execption $e) {
			$this->Common_model->data_logging('User Group Management', "Delete group", 'FAILED', 'Group ID: ' . $user_data["id"]);
			$data = array("success" => false, "message" => "Failed", "error" => $e);
		}

		return $this->response->setStatusCode(200)->setJSON($data);
	}

}
