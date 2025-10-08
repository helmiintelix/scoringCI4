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

    public function cycle_add_form()
    {
        $data["yes_no"] = [
            "Y" => "Yes",
            "N" => "No"
        ];

        $data["active_status"] = [
            "1" => "Yes",
            "0" => "No"
        ];

        $this->Common_model->data_logging('Setting Cycle', "Load add cycle form", 'SUCCESS', '');

        return view('App\Modules\SettingCycle\Views\SettingCycleAddView', $data);
    }

    public function save_cycle_add()
    {
        $request = $this->request;

        $user_data["id"]          = UUID(false);
        $user_data["cycle_name"]  = $request->getPost("txt-cycle-name");
        $user_data["cycle_from"]  = $request->getPost("txt-cycle-from");
        $user_data["cycle_to"]    = $request->getPost("txt-cycle-to");
        $user_data["is_active"]   = $request->getPost("opt-active-flag");

        $userId = session()->get("USER_ID");

        $user_data["created_by"]   = $userId;
        $user_data["created_time"] = date("Y-m-d H:i:s");
        $user_data["updated_by"]   = $userId;
        $user_data["updated_time"] = date("Y-m-d H:i:s");

        $return = $this->SettingCycleModel->save_cycle_add($user_data);

        if ($return) {
            $this->Common_model->data_logging(
                'Setting Cycle',
                'Add Cycle',
                'SUCCESS',
                'Cycle ID: ' . $user_data["id"] . ', Name: ' . $user_data["cycle_name"]
            );
            $data = ["success" => true, "message" => "Saved successfully."];
        } else {
            $this->Common_model->data_logging(
                'Setting Cycle',
                'Add Cycle',
                'FAILED',
                'Cycle ID: ' . $user_data["id"] . ', Name: ' . $user_data["cycle_name"]
            );
            $data = ["success" => false, "message" => "Failed to save."];
        }

        return $this->response->setJSON($data);
    }

    public function cycle_edit_form($id = null)
    {
        if ($id === null) {
            $uri = service('uri');
            $id = $uri->getSegment(3);
        }

        $data["id_user"] = $id;
        $data["user_data"] = $this->Common_model->get_record_values(
            "*",
            "sc_scoring_cycle",
            "id = '" . $data["id_user"] . "'"
        );

        $data["yes_no"] = [
            "Y" => "Yes",
            "N" => "No"
        ];

        $data["active_status"] = [
            "1" => "Yes",
            "0" => "No"
        ];

        $this->Common_model->data_logging('Setting Cycle', "Load edit cycle form", 'SUCCESS', '');

        return view('App\Modules\SettingCycle\Views\SettingCycleEditView', $data);
    }

    public function save_cycle_edit()
    {
        $user_data["id"]         = $this->request->getPost("txt-user-id");
        $user_data["cycle_name"] = $this->request->getPost("txt-cycle-name");
        $user_data["cycle_from"] = $this->request->getPost("txt-cycle-from");
        $user_data["cycle_to"]   = $this->request->getPost("txt-cycle-to");
        $user_data["is_active"]  = $this->request->getPost("opt-active-flag");

        $session = session();
        $user_data["updated_by"]   = $session->get("USER_ID");
        $user_data["updated_time"] = date("Y-m-d H:i:s");

        $return = $this->SettingCycleModel->save_cycle_edit($user_data);

        if ($return) {
            $this->Common_model->data_logging(
                'Setting Cycle',
                'Edit cycle',
                'SUCCESS',
                'Cycle ID: ' . $user_data["id"] . ', Name: ' . $user_data["cycle_name"]
            );
            $data = ["success" => true, "message" => "Updated successfully."];
        } else {
            $this->Common_model->data_logging(
                'Setting Cycle',
                'Edit cycle',
                'FAILED',
                'Cycle ID: ' . $user_data["id"] . ', Name: ' . $user_data["cycle_name"]
            );
            $data = ["success" => false, "message" => "Failed to update."];
        }

        return $this->response->setJSON($data);
    }

    public function check_cycle_status($id_user)
    {
        return $this->SettingCycleModel->check_cycle_status($id_user);
    }

    public function delete_cycle()
    {
        $id_user = $this->request->getPost('id_user');

        try {
            $is_used = $this->Common_model->get_record_value(
                "count(*)",
                "sc_scoring_tiering_setup",
                "cycle = '" . $id_user . "'"
            );

            if ($this->check_cycle_status($id_user)) {
                $data = ["success" => false, "message" => "Cycle is active, cannot be deleted."];
            } elseif ($is_used > 0) {
                $data = [
                    "success" => false,
                    "message" => "Cycle is still used in tiering. Check the Tiering Preview menu."
                ];
            } else {
                $user_data["id"] = $id_user;

                $this->SettingCycleModel->delete_cycle($user_data);
                $this->Common_model->data_logging(
                    'Setting Cycle',
                    "Delete cycle",
                    'SUCCESS',
                    'Cycle ID: ' . $id_user
                );

                $data = ["success" => true, "message" => "Successfully deleted."];
            }
        } catch (\Exception $e) {
            $this->Common_model->data_logging(
                'Setting Cycle',
                "Delete cycle",
                'FAILED',
                'Cycle ID: ' . $id_user
            );

            $data = [
                "success" => false,
                "message" => "Failed to delete.",
                "error"   => $e->getMessage()
            ];
        }

        return $this->response->setJSON($data);
    }
}
