<?php

namespace App\Modules\Scoring\Controllers;

use App\Modules\Scoring\Models\ScoringModel;

class Scoring extends \App\Controllers\BaseController
{
	protected $ScoringModel;

	function __construct()
	{
		$this->ScoringModel = new ScoringModel();
	}

	function scheduler()
	{
		$data["scheduler_setting"] = $this->ScoringModel->getSchedulerSetting();
		return view('App\Modules\Scoring\Views\SchedulerView', $data);
	}

	function updateSystemSetting()
	{
		$param_data["id"]			= strtoupper($this->input->getPost('id'));
		$param_data["value"]	= $this->input->getPost("value");
		$param_data["parameter"]	= $this->input->getPost("parameter");
		$param_data["created_by"] = session()->get('USER_ID');
		$param_data["created_time"] = date('Y-m-d H:i:s');


		$return	= $this->ScoringModel->updateSystemSetting($param_data);

		if ($return) {
			$this->Common_model->data_logging('SETTING SCHEDULER ', $param_data["id"], ' SUCCESS', 'Set ' . $param_data["id"] . ' = ' . $param_data["value"]);
			$data	= array("success" => true, "message" => "Data berhasil disimpan Menunggu approval");
		} else {
			$this->Common_model->data_logging('SETTING SCHEDULER ', $param_data["id"], ' FAILED', 'Set ' . $param_data["id"] . ' = ' . $param_data["value"]);
			$data	= array("success" => false, "message" => "Data tidak berhasil disimpan");
		}

		echo json_encode($data);
	}
}
