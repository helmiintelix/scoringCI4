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
		return view('\App\Modules\UserAndGroup\Views\user_group_add_form_view');
	}

    function save_user_group_add(){
		$group_id = str_replace(' ', '_', strtoupper($this->request->getPost('txt-group-id')));
		$is_exist = $this->User_and_group_model->isExistGroup($group_id);
		$listMenu = $this->request->getPost("menu-item");
		if (!$is_exist) {
			$user_data = [
				"id"              => $group_id,
				"name"            => strtoupper($this->request->getPost("txt-group-name")),
				"description"     => strtoupper($this->request->getPost("txt-group-description")),
				"level"       	  => 0,
				"authority"       => implode("|", $listMenu ?? []),
				"created_by"      => session()->get('USER_ID'),
				"created_time"    => date('Y-m-d H:i:s'),
				"is_active"		  => '1'
			];
	
			$return = $this->db->table('cc_user_group')->insert($user_data);
			
			if ($return) {
				$data = ["success" => true, "message" => "Success"];
			} else {
				$data = ["success" => false, "message" => "Failed"];
			}
		} else {
			$data = ["success" => false, "message" => "Group ID Already Registered. Please insert another ID."];
		}

		return $this->response->setStatusCode(200)->setJSON($data);
	}

    function user_group_edit_form(){
		$group_id = $this->input->getGet('id');

		$data["user_data"] = $this->Common_model->get_record_values("id, name, description ,authority as  menu_list, level", "cc_user_group", "id = '{$group_id}' ");
		$data['ACTION'] = 'EDIT';
		return view('\App\Modules\UserAndGroup\Views\user_group_edit_form_view',$data);
	
	}

    function save_user_group_edit()
	{
		$user_data["id"]				= strtoupper($this->input->getPost('txt-group-id')??'');
		$user_data["name"]			= strtoupper($this->input->getPost("txt-group-name")??'');
		$user_data["description"]			= strtoupper($this->input->getPost("txt-group-description")??'');
		$user_data["level"] = 0;
		$user_data["authority"]	= implode("|", $this->input->getPost("menu-item"));
		$user_data["is_active"]	= $this->input->getPost("opt-active-flag");
		
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

    function remove_user_group()
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
