<?php 
namespace App\Modules\SetupPassword\Controllers;

use App\Modules\SetupPassword\Models\SetupPassword_model;
use CodeIgniter\Cookie\Cookie;
use Config\Database;


class SetupPassword extends \App\Controllers\BaseController
{

	function __construct()
	{
		$session = \Config\Services::session();

		$this->SetupPassword_model = new SetupPassword_model();
	}

	function general()
	{
		$data["general_setting"] = $this->SetupPassword_model->get_general_setting();
		// $data['language'] = json_decode($this->session->userdata("LANGUAGE"))->aav_configuration;
		return view('\App\Modules\SetupPassword\Views\general_setting_view', $data);
	}

	function update_system_setting()
	{
		$param_data["id"]			= strtoupper($this->input->getPost('id'));
		$param_data["value"]	= $this->input->getPost("value");
		$param_data["add_field1"]	= "APPROVAL";
		$param_data["created_by"] = session()->get('USER_ID');
		$param_data["created_time"] = date('Y-m-d H:i:s');

		// var_dump($param_data);
		// die;

		$return	= $this->SetupPassword_model->update_system_setting($param_data);

		if ($return) {
			$this->Common_model->data_logging('SETTING', $param_data["id"], 'WAITING APPROVAL SUCCESS', 'Set ' . $param_data["id"] . ' = ' . $param_data["value"]);
			$data	= array("success" => true, "message" => "Data berhasil disimpan Menunggu approval");
		} else {
			$this->Common_model->data_logging('SETTING', $param_data["id"], 'WAITING APPROVAL FAILED', 'Set ' . $param_data["id"] . ' = ' . $param_data["value"]);
			$data	= array("success" => false, "message" => "Data tidak berhasil disimpan");
		}

		echo json_encode($data);
	}

	function general_tmp()
	{
		return view('\App\Modules\SetupPassword\Views\general_setting_temp_view');
	}
	
	function get_password_setting_temp(){
		
		$data = $this->SetupPassword_model->get_password_setting_temp();

		$rs = array('success' => true, 'message' => '', 'data' => $data);
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function approve_system_setting()
	{
		$param_data["id"]			= strtoupper($this->input->getPost('id'));
		$param_data["value"]	= $this->input->getPost("value");
		$param_data["add_field1"]	= "APPROVED";
		$param_data["created_by"] = session()->get('USER_ID');
		$param_data["created_time"] = date('Y-m-d H:i:s');
		// var_dump($param_data);die;

		$return	= $this->SetupPassword_model->approve_system_setting($param_data);

		if ($return) {
			$this->Common_model->data_logging('SETTING', $param_data["id"], 'APPROVAL SUCCESS', 'Set ' . $param_data["id"] . ' = ' . $param_data["value"]);
			$data	= array("success" => true, "message" => "Success");
		} else {
			$this->Common_model->data_logging('SETTING', $param_data["id"], 'APPROVAL FAILED', 'Set ' . $param_data["id"] . ' = ' . $param_data["value"]);
			$data	= array("success" => false, "message" => "Failed");
		}

		echo json_encode($data);
	}

	function reject_system_setting()
	{
		$param_data["id"]			= $this->input->getPost('id');
		$param_data["value"]	= $this->input->getPost("value");
		$param_data["add_field1"]	= "REJECTED";
		$param_data["created_by"] = session()->get('USER_ID');
		$param_data["created_time"] = date('Y-m-d H:i:s');

		$return	= $this->SetupPassword_model->reject_system_setting($param_data);

		if ($return) {
			$this->Common_model->data_logging('SETTING', $param_data["id"], 'APPROVAL SUCCESS', 'Set ' . $param_data["id"] . ' = ' . $param_data["value"]);
			$data	= array("success" => true, "message" => "Success");
		} else {
			$this->Common_model->data_logging('SETTING', $param_data["id"], 'APPROVAL FAILED', 'Set ' . $param_data["id"] . ' = ' . $param_data["value"]);
			$data	= array("success" => false, "message" => "Failed");
		}

		echo json_encode($data);
	}

}