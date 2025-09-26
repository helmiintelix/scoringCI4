<?php

namespace App\Modules\GeneralSetting\Controllers;

use App\Modules\GeneralSetting\Models\GeneralSettingModel;

class GeneralSetting extends \App\Controllers\BaseController
{
	protected $GeneralSettingModel;

	function __construct()
	{
		$this->GeneralSettingModel = new GeneralSettingModel();
	}

	function GeneralSetting()
	{
		$data["general_setting"] = $this->GeneralSettingModel->getGeneralSetting();
		return view('App\Modules\GeneralSetting\Views\GeneralSettingView', $data);
	}

	function updateSystemSetting()
	{
		$param_data["id"]			= strtoupper($this->input->getPost('id'));
		$param_data["value"]	= $this->input->getPost("value");
		$param_data["parameter"]	= $this->input->getPost("parameter");
		$param_data["created_by"] = session()->get('USER_ID');
		$param_data["created_time"] = date('Y-m-d H:i:s');


		$return	= $this->GeneralSettingModel->updateSystemSetting($param_data);

		if ($return) {
			$this->Common_model->data_logging('SETTING GENERAL ', $param_data["id"], ' SUCCESS', 'Set ' . $param_data["id"] . ' = ' . $param_data["value"]);
			$data	= array("success" => true, "message" => "Data berhasil disimpan Menunggu approval");
		} else {
			$this->Common_model->data_logging('SETTING GENERAL ', $param_data["id"], ' FAILED', 'Set ' . $param_data["id"] . ' = ' . $param_data["value"]);
			$data	= array("success" => false, "message" => "Data tidak berhasil disimpan");
		}

		echo json_encode($data);
	}
}
