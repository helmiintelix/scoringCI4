<?php

namespace App\Modules\Parameters\Controllers;

use App\Modules\Parameters\Models\ParametersModel;
use App\Models\Common_model;

class Parameters extends \App\Controllers\BaseController
{
    protected $ParametersModel;
    protected $Common_model;

    public function __construct()
    {
        $this->ParametersModel = new ParametersModel();
        $this->Common_model    = new Common_model();
    }

    public function get_parameters_list()
    {
        $data["contract_aging_parameter"]     = $this->ParametersModel->get_parameter("CONTRACT_AGING");
        $data["call_history_parameter"]       = $this->ParametersModel->get_parameter("CALL_HISTORY");
        $data["visit_history_parameter"]      = $this->ParametersModel->get_parameter("VISIT_HISTORY");
        $data["payment_history_parameter"]    = $this->ParametersModel->get_parameter("PAYMENT_HISTORY");
        $data["bucket_dpd_history_parameter"] = $this->ParametersModel->get_parameter("BUCKET_DPD_HISTORY");
        $data["scoring_purple_parameter"]     = $this->ParametersModel->get_parameter("SCORING_PURPLE");

        $data["aging_value_content_list"] = [
            ""              => "[select value content]",
            "MULTIPLE_VALUE" => "Multiple Value",
            "SINGLE_VALUE"  => "Single Value",
            "TEXT"          => "Text",
            "NUMBER"        => "Number",
            "NUMBER_RANGE"  => "Number Range",
            "DATE_RANGE"    => "Date Range",
            "DAY"           => "Day",
            "MONTH"         => "Month",
            "YEAR"          => "Year"
        ];

        $data["history_value_content_list"] = [
            ""              => "[select value content]",
            "MULTIPLE_VALUE" => "Multiple Value",
            "SINGLE_VALUE"  => "Single Value",
            "TEXT"          => "Text",
            "NUMBER"        => "Number",
            "NUMBER_RANGE"  => "Number Range",
            "DATE_RANGE"    => "Date Range",
            "DAY"           => "Day",
            "MONTH"         => "Month",
            "YEAR"          => "Year",
            "MAPPING"       => "Table Mapping"
        ];

        $data["map_reference_list"] = array_merge(
            ["" => "[select map]"],
            $this->Common_model->get_record_list(
                "DISTINCT(parameter) AS value, parameter AS item",
                "sc_mapping",
                "is_active='Y'",
                "parameter ASC"
            )
        );

        return view('App\Modules\Parameters\Views\ParametersView', $data);
    }

    public function update_parameter()
    {
        $param_data = [
            "param"   => $this->request->getPost('param'),
            "param_id" => $this->request->getPost('param_id'),
            "column"  => $this->request->getPost('column'),
            "value"   => $this->request->getPost('value')
        ];

        $return = $this->ParametersModel->update_parameter($param_data);

        if ($return) {
            $data = ["success" => true, "message" => "Successfully updated."];
        } else {
            $data = ["success" => false, "message" => "Failed to update."];
        }

        return $this->response->setJSON($data);
    }

    public function update_parameter_commit()
    {
        $return = $this->ParametersModel->update_parameter_commit();

        if ($return) {
            $data = ["success" => true, "message" => "Successfully updated commit."];
        } else {
            $data = ["success" => false, "message" => "Failed to update commit."];
        }

        return $this->response->setJSON($data);
    }
}
