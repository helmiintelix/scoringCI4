<?php 
namespace App\Modules\Settings\Controllers;
use App\Modules\Settings\Models\SettingsModel;

class Settings extends \App\Controllers\BaseController
{
    protected $SettingsModel;

	function __construct()
	{
		$this->SettingsModel = new SettingsModel();
	}

    function get_system_configuration(){
 		$result = $this->SettingsModel->get_system_configuration(); 
		$response = array("success" => true, "message" => "get data berhasil", "data" => $result);
		
		echo json_encode($response);
	}
}