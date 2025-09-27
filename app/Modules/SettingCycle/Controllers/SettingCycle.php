<?php

namespace App\Modules\SettingCycle\Controllers;

use App\Modules\SettingCycle\Models\SettingCycleModel;
use App\Models\Common_model;

class SettingCycle extends \App\Controllers\BaseController
{
    protected $SettingCycleModel;
    protected $Common_model;

    public function __construct()
    {
        $this->SettingCycleModel = new SettingCycleModel();
        $this->Common_model    = new Common_model();
    }

    public function setting_cycle()
    {
        return view('App\Modules\SettingCycle\Views\SettingCycleView');
    }

    public function setting_cycle_list()
    {
        $data = $this->SettingCycleModel->get_cycle_management_list();
        $this->Common_model->data_logging('Setting Cycle', "LIST DATA", 'SUCCESS', '');

        return $this->response->setJSON($data);
    }
}
