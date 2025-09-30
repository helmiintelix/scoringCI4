<?php

namespace App\Modules\UserManagement\Controllers;

use App\Controllers\BaseController;
use App\Modules\UserManagement\Models\User_and_group_model;

class UserController extends \App\Controllers\BaseController
{

	public $cache_user_management_list = 'user_management_list';

    function __construct()
	{
		$this->User_and_group_model = new User_and_group_model();
	}

    public function index()
    {
        return view('App\Modules\UserManagement\Views\usercontrollerView');
    }

    function user_management_list()
	{
		
		$cache = $this->cache_user_management_list;

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

	function user_add_form(){
	
		$cache = session()->get('USER_ID').'_user_add_form';
		$data["group_list"] = $this->Common_model->get_record_list("id AS value, name AS item", "cc_user_group", "is_active = '1' ", "id");

		return view('App\Modules\UserManagement\Views\user_add_form_view',$data);
	}

	function save_user_add(){
		$user_data["id"] = $this->input->getPost("txt-user-id-temp");
		$user_data["name"] = $this->input->getPost("txt-user-name");
		$user_data["email"] = $this->input->getPost("txt-email");
		$user_data["group_id"] = $this->input->getPost("opt-user-level");
		$user_data["is_active"] = $this->input->getPost("opt-active-flag");
		$user_data["ldap"] = $this->input->getPost("btnLdap");
		$user_data["password_date"] = date("Y-m-d H:i:s");
		$user_data["password_status"] = "default";
		$user_data["created_by"] = session()->get('USER_ID');
		$user_data["created_time"] = date("Y-m-d H:i:s");
		// $user_data["authority"] = 'user-management-delete';

		
		$password = $this->input->getPost("txt-password");
		if(!empty($password)){
			$user_data["password"] = md5($password);
			$user_data["password_date"] = date("Y-m-d H:i:s");
			$user_data["password_status"] = "new";
		}else{
			$user_data["password"] = $this->Common_model->get_record_value("MD5(value)", "cc_app_configuration", "parameter = 'SYSTEM' AND id = 'PASSWORD_DEFAULT'");
			$user_data["password_date"] = date("Y-m-d H:i:s");
			$user_data["password_status"] = "new";
		}

		$return	= $this->User_and_group_model->save_user_add($user_data);

		if ($return) {
			$this->cache->delete($this->cache_user_management_list);

			$this->Common_model->data_logging('User Management', 'Add user', 'SUCCESS', 'User ID: '.$user_data["id"].', Name: '.$user_data["name"]);
			$data = array("success" => true, "message" => "Success");
		}
		else {
			$this->Common_model->data_logging('User Management', 'Add user', 'FAILED', 'User ID: '.$user_data["id"].', Name: '.$user_data["name"]);
			$data = array("success" => false, "message" => "Failed");
		}

		return $this->response->setStatusCode(200)->setJSON($data);
	}

	function user_edit_form(){
		$data["id_user"] = $this->input->getGet('id');
		$data["user_data"] = $this->Common_model->get_record_values("*", "cc_user", "id = '".$data["id_user"]."'");
		$data["group_list"] = $this->Common_model->get_record_list("id AS value, name AS item", "cc_user_group", "is_active = '1' ", "id");
		
		

		return view('App\Modules\UserManagement\Views\user_edit_form_view',$data);
	}

	function save_user_edit(){
		$user_data["id"] = $this->input->getPost("txt-user-id");
		$user_data["name"] = $this->input->getPost("txt-user-name");
		$user_data["email"] = $this->input->getPost("txt-email");
		$user_data["group_id"] = $this->input->getPost("opt-user-level");
		$user_data["is_active"] = $this->input->getPost("opt-active-flag");
		$user_data["ldap"] = $this->input->getPost("btnLdap");
		
		$password = $this->input->getPost("txt-password");
		if(!empty($password)){
			$user_data["password"] = md5($password);
			$user_data["password_date"] = date("Y-m-d H:i:s");
			$user_data["password_status"] = "new";
		}

		// $file = $this->request->getFile('profile_image');
			
		// if ($file->isValid() && !$file->hasMoved()) {
		// 	$newName = 'PP_'.$user_data["id"];
		// 	if (file_exists($newName)) {
        //         unlink($newName);
        //     }

		// 	$file->move(WRITEPATH . 'uploads', $newName);
		// }
		
		$user_data["updated_by"] = session()->get('USER_ID');
		$user_data["updated_time"] = date("Y-m-d H:i:s");
		
		$user_data["login_attempts"] = 0;
		
		$return	= $this->User_and_group_model->save_user_edit($user_data);
		
		if ($return) {
			$this->cache->delete($this->cache_user_management_list);

			$this->Common_model->data_logging('User Management', 'Edit user', 'SUCCESS', 'User ID: '.$user_data["id"].', Name: '.$user_data["name"]);
			$data = array("success" => true, "message" => "Success");
		}
		else {
			$this->Common_model->data_logging('User Management', 'Edit user', 'SUCCESS', 'User ID: '.$user_data["id"].', Name: '.$user_data["name"]);
			$data = array("success" => false, "message" => "Failed");
		}
		
		return $this->response->setStatusCode(200)->setJSON($data);
	}

	function check_user_status($id_user) {
		$row = $this->User_and_group_model->check_user_status($id_user);
		return $row;
	}

	function delete_user() {
		$id_user = $this->input->getPost('id_user');
		$response = array();  
			
		try {
			if ($this->check_user_status($id_user)) {
				$data = array("success" => false, "message" => "User masih aktif!");
			}
			else {
				$user_data["id"] = $id_user;
				$user_data["is_active"] = "2";
				
				$this->User_and_group_model->save_user_edit($user_data);

				$this->cache->delete($this->cache_user_management_list);
				$this->Common_model->data_logging('User Management', "Delete user", 'SUCESS', 'User ID: '.$id_user);

				$data = array("success" => true, "message" => "Success"); 
			}
		}
		catch (execption $e) {
			$this->Common_model->data_logging('User Management', "Delete user", 'FAILED', 'User ID: '.$id_user);
			$data = array("success" => false, "message" => "Failed", "error" => $e);
		}
			
		return $this->response->setStatusCode(200)->setJSON($data);
	}

	function reset_password() {
		$id_user = $this->input->getPost('id_user');
		$response = array();  
		
		try {
			$user_data["id"] = $id_user;
			$user_data["password"] = $this->Common_model->get_record_value("MD5(value)", "cc_app_configuration", "parameter = 'SYSTEM' AND id = 'PASSWORD_DEFAULT'");
			$user_data["password_status"] = "default";
			$user_data["login_attempts"] = 0;
		  
			$this->User_and_group_model->save_user_edit($user_data);
			$this->Common_model->data_logging('User Management', "Reset password", 'SUCCESS', 'User ID: '.$id_user);
			$data = array("success" => true, "message" => "Success"); 
		}
		catch (execption $e) {
			$this->Common_model->data_logging('User Management', "Reset password", 'FAILED', 'User ID: '.$id_user);
			$data = array("success" => false, "message" => "Failed", "error" => $e);
		}
        
		return $this->response->setStatusCode(200)->setJSON($data);
	}

	function force_logout_user() {
		$id_user = $this->input->getPost('id_user');
		$response = array();  
		
		try {
			$user_data["id"] = $id_user;
			$user_data["login_status"] = "0";

			$this->User_and_group_model->save_user_edit($user_data);
			$this->Common_model->data_logging('User Management', "Force logout", 'SUCCESS', 'User ID: '.$id_user);
			$data = array("success" => true, "message" => "Success"); 
		}
		catch (execption $e) {
			$this->Common_model->data_logging('User Management', "Force logout", 'FAILED', 'User ID: '.$id_user);
			$data = array("success" => false, "message" => "Failed", "error" => $e);
		}
        
		return $this->response->setStatusCode(200)->setJSON($data);
	}
}
