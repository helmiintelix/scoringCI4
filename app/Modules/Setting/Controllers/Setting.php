<?php

namespace App\Modules\Setting\Controllers;

use App\Modules\Setting\Models\SettingModel;
use App\Models\Common_model;

class Setting extends \App\Controllers\BaseController
{
    protected $SettingModel;
    protected $Common_model;

    public function __construct()
    {
        $this->SettingModel = new SettingModel();
        $this->Common_model = new Common_model();
    }

    public function setting()
    {
        $scheme_id = $this->request->getUri()->getSegment(3);

        if (!empty($scheme_id)) {
            $data["form_mode"] = 'EDIT';
            $data["scheme_id"] = $scheme_id;
            $data["scheme_data"] = $this->SettingModel->get_scheme_data($scheme_id);
            $data["contract_aging_parameter"] = $this->SettingModel->get_setting($scheme_id, "CONTRACT_AGING");
            $data["call_history_parameter"] = $this->SettingModel->get_setting($scheme_id, "CALL_HISTORY");
            $data["visit_history_parameter"] = $this->SettingModel->get_setting($scheme_id, "VISIT_HISTORY");
            $data["payment_history_parameter"] = $this->SettingModel->get_setting($scheme_id, "PAYMENT_HISTORY");
            $data["bucket_dpd_history_parameter"] = $this->SettingModel->get_setting($scheme_id, "BUCKET_DPD_HISTORY");
            $data["scoring_purple_parameter"] = $this->SettingModel->get_setting($scheme_id, "SCORING_PURPLE");
        } else {
            $data["form_mode"] = 'ADD';
            $data["scheme_id"] = null;
            $data["scheme_data"] = [];
            $data["contract_aging_parameter"] = $this->SettingModel->get_setting("NEW", "CONTRACT_AGING");
            $data["call_history_parameter"] = $this->SettingModel->get_setting("NEW", "CALL_HISTORY");
            $data["visit_history_parameter"] = $this->SettingModel->get_setting("NEW", "VISIT_HISTORY");
            $data["payment_history_parameter"] = $this->SettingModel->get_setting("NEW", "PAYMENT_HISTORY");
            $data["bucket_dpd_history_parameter"] = $this->SettingModel->get_setting("NEW", "BUCKET_DPD_HISTORY");
            $data["scoring_purple_parameter"] = $this->SettingModel->get_setting("NEW", "SCORING_PURPLE");
        }

        // $scoring_purple_parameter = $this->SettingModel->get_setting($scheme_id, "SCORING_PURPLE");

        // dd($data["scoring_purple_parameter"]);

        $data["function_list"]["history_month"] = array(
            "" => "(select function)",
            "IN_MONTH" => "IN MONTH"
        );

        //GENERAL
        $data["function_list"]["MULTIPLE_VALUE"] = array(
            "" => "(select function)",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS",
            "IN" => "IN"
        );

        $data["function_list"]["SINGLE_VALUE"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS",
            "IN" => "IN"
        );

        $data["function_list"]["TEXT"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS",
            "IN" => "IN"
        );

        $data["function_list"]["NUMBER"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO"
        );

        $data["function_list"]["NUMBER_RANGE"] = array(
            "" => "(select function)",
            "BETWEEN" => "BETWEEN"
        );

        $data["function_list"]["DATE"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
        );

        $data["function_list"]["DATE_RANGE"] = array(
            "" => "(select function)",
            "BETWEEN" => "BETWEEN"
        );

        $data["function_list"]["DAY"] = array(
            "" => "(select function)",
            "IN_DAY" => "IN DAY"
        );

        $data["function_list"]["MONTH"] = array(
            "" => "(select function)",
            "IN_MONTH" => "IN MONTH"
        );

        $data["function_list"]["YEAR"] = array(
            "" => "(select function)",
            "IN_YEAR" => "IN YEAR"
        );

        //TEXT
        $data["function_list"]["text"]["MULTIPLE_VALUE"] = array(
            "" => "(select function)",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS",
            "IN" => "IN"
        );

        $data["function_list"]["text"]["SINGLE_VALUE"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS"
        );

        $data["function_list"]["text"]["TEXT"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS",
            "IN" => "IN"
        );

        $data["function_list"]["text"]["NUMBER"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO"
        );

        $data["function_list"]["text"]["NUMBER_RANGE"] = array(
            "" => "(select function)",
            "BETWEEN" => "BETWEEN"
        );

        $data["function_list"]["text"]["DATE"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
        );

        $data["function_list"]["text"]["DATE_RANGE"] = array(
            "" => "(select function)",
            "BETWEEN" => "BETWEEN"
        );

        $data["function_list"]["text"]["DAY"] = array(
            "" => "(select function)",
            "IN_DAY" => "IN DAY"
        );

        $data["function_list"]["text"]["MONTH"] = array(
            "" => "(select function)",
            "IN_MONTH" => "IN MONTH"
        );

        $data["function_list"]["text"]["YEAR"] = array(
            "" => "(select function)",
            "IN_YEAR" => "IN YEAR"
        );

        $data["function_list"]["text"]["SCORING"] = array(
            "" => "(select function)",
            "IN" => "IN"
        );

        //NUMBER
        $data["function_list"]["number"]["MULTIPLE_VALUE"] = array(
            "" => "(select function)",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS",
            "IN" => "IN"
        );

        $data["function_list"]["number"]["SINGLE_VALUE"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS"
        );

        $data["function_list"]["number"]["TEXT"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS"
        );

        $data["function_list"]["number"]["NUMBER"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO"
        );

        $data["function_list"]["number"]["NUMBER_RANGE"] = array(
            "" => "(select function)",
            "BETWEEN" => "BETWEEN"
        );

        $data["function_list"]["number"]["DATE"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
        );

        $data["function_list"]["number"]["DATE_RANGE"] = array(
            "" => "(select function)",
            "BETWEEN" => "BETWEEN"
        );

        $data["function_list"]["number"]["DAY"] = array(
            "" => "(select function)",
            "IN_DAY" => "IN DAY"
        );

        $data["function_list"]["number"]["MONTH"] = array(
            "" => "(select function)",
            "IN_MONTH" => "IN MONTH"
        );

        $data["function_list"]["number"]["YEAR"] = array(
            "" => "(select function)",
            "IN_YEAR" => "IN YEAR"
        );

        //DATE
        $data["function_list"]["date"]["MULTIPLE_VALUE"] = array(
            "" => "(select function)",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS",
            "IN" => "IN"
        );

        $data["function_list"]["date"]["SINGLE_VALUE"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS"
        );

        $data["function_list"]["date"]["TEXT"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS"
        );

        $data["function_list"]["date"]["NUMBER"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO"
        );

        $data["function_list"]["date"]["NUMBER_RANGE"] = array(
            "" => "(select function)",
            "BETWEEN" => "BETWEEN"
        );

        $data["function_list"]["date"]["DATE"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
        );

        $data["function_list"]["date"]["DATE_RANGE"] = array(
            "" => "(select function)",
            "BETWEEN" => "BETWEEN"
        );

        $data["function_list"]["date"]["DAY"] = array(
            "" => "(select function)",
            "IN_DAY" => "IN DAY"
        );

        $data["function_list"]["date"]["MONTH"] = array(
            "" => "(select function)",
            "IN_MONTH" => "IN MONTH"
        );

        $data["function_list"]["date"]["YEAR"] = array(
            "" => "(select function)",
            "IN_YEAR" => "IN YEAR"
        );

        //DAY
        $data["function_list"]["day"]["MULTIPLE_VALUE"] = array(
            "" => "(select function)",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS",
            "IN" => "IN"
        );

        $data["function_list"]["day"]["SINGLE_VALUE"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS"
        );

        $data["function_list"]["day"]["TEXT"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS"
        );

        $data["function_list"]["day"]["NUMBER"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO"
        );

        $data["function_list"]["day"]["NUMBER_RANGE"] = array(
            "" => "(select function)",
            "BETWEEN" => "BETWEEN"
        );

        $data["function_list"]["day"]["DATE"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
        );

        $data["function_list"]["day"]["DATE_RANGE"] = array(
            "" => "(select function)",
            "BETWEEN" => "BETWEEN"
        );

        $data["function_list"]["day"]["DAY"] = array(
            "" => "(select function)",
            "IN_DAY" => "IN DAY"
        );

        $data["function_list"]["day"]["MONTH"] = array(
            "" => "(select function)",
            "IN_MONTH" => "IN MONTH"
        );

        $data["function_list"]["day"]["YEAR"] = array(
            "" => "(select function)",
            "IN_YEAR" => "IN YEAR"
        );

        //MONTH
        $data["function_list"]["month"]["MULTIPLE_VALUE"] = array(
            "" => "(select function)",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS",
            "IN" => "IN"
        );

        $data["function_list"]["month"]["SINGLE_VALUE"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS"
        );

        $data["function_list"]["month"]["TEXT"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS"
        );

        $data["function_list"]["month"]["NUMBER"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO"
        );

        $data["function_list"]["month"]["NUMBER_RANGE"] = array(
            "" => "(select function)",
            "BETWEEN" => "BETWEEN"
        );

        $data["function_list"]["month"]["DATE"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
        );

        $data["function_list"]["month"]["DATE_RANGE"] = array(
            "" => "(select function)",
            "BETWEEN" => "BETWEEN"
        );

        $data["function_list"]["month"]["DAY"] = array(
            "" => "(select function)",
            "IN_DAY" => "IN DAY"
        );

        $data["function_list"]["month"]["MONTH"] = array(
            "" => "(select function)",
            "IN_MONTH" => "IN MONTH"
        );

        $data["function_list"]["month"]["YEAR"] = array(
            "" => "(select function)",
            "IN_YEAR" => "IN YEAR"
        );

        //YEAR
        $data["function_list"]["year"]["MULTIPLE_VALUE"] = array(
            "" => "(select function)",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS",
            "IN" => "IN"
        );

        $data["function_list"]["year"]["SINGLE_VALUE"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS"
        );

        $data["function_list"]["year"]["TEXT"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
            "CONTAINS" => "CONTAINS",
            "DOES_NOT_CONTAINS" => "DOES NOT CONTAINS"
        );

        $data["function_list"]["year"]["NUMBER"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO"
        );

        $data["function_list"]["year"]["NUMBER_RANGE"] = array(
            "" => "(select function)",
            "BETWEEN" => "BETWEEN"
        );

        $data["function_list"]["year"]["DATE"] = array(
            "" => "(select function)",
            "EQUAL_TO" => "EQUAL TO",
            "GREATER_THAN" => "GREATER_THAN",
            "GREATER_OR_EQUAL_TO" => "GREATER THAN OR EQUAL TO",
            "LESS_THAN" => "LESS_THAN",
            "LESS_OR_EQUAL_TO" => "LESS THAN OR EQUAL TO",
        );

        $data["function_list"]["year"]["DATE_RANGE"] = array(
            "" => "(select function)",
            "BETWEEN" => "BETWEEN"
        );

        $data["function_list"]["year"]["DAY"] = array(
            "" => "(select function)",
            "IN_DAY" => "IN DAY"
        );

        $data["function_list"]["year"]["MONTH"] = array(
            "" => "(select function)",
            "IN_MONTH" => "IN MONTH"
        );

        $data["function_list"]["year"]["YEAR"] = array(
            "" => "(select function)",
            "IN_YEAR" => "IN YEAR"
        );

        $data["ref_list"] = $this->Common_model->get_all_ref_master_crm(
            "reference, value AS value, description AS item",
            "cms_reference",
            "status='1'",
            "description",
            false
        );

        $data["ref_list"]["class"] = $this->Common_model->get_ref_master_crm(
            "classification_id AS value, concat(classification_id,' - ',classification_name) AS item",
            "cms_classification",
            "1=1",
            "classification_name",
            false
        );

        $data["ref_list"]["code_branch"] = $this->Common_model->get_ref_master_crm(
            "kcu_id AS value, concat(kcu_id,' - ',kcu_name) AS item",
            "cms_kcu",
            "flag ='1' ",
            "kcu_id",
            false
        );

        $data["ref_list"]["setup_division"] = $this->Common_model->get_ref_master_crm(
            "division_code AS value, division_name AS item",
            "cms_division_approval",
            "is_active ='1' ",
            "division_name",
            false
        );

        $data["ref_list"]["setup_department"] = $this->Common_model->get_ref_master_crm(
            "department_code AS value, department_name AS item",
            "cms_department_approval",
            "is_active ='1' ",
            "department_name",
            false
        );

        $data["ref_list"]["setup_region"] = $this->Common_model->get_ref_master_crm(
            "region_code AS value, region_name AS item",
            "cms_region_approval",
            "is_active ='1' ",
            "region_name",
            false
        );

        $data["ref_list"]["setup_collection_representative"] = $this->Common_model->get_ref_master_crm(
            "collection_representative_code AS value, collection_representative_name AS item",
            "cms_collection_representative_approval",
            "is_active ='1' ",
            "collection_representative_name",
            false
        );

        $data["ref_list"]["setup_area_zipcode"] = $this->Common_model->get_ref_master_crm(
            "area_zipcode_code AS value, area_zipcode_name AS item",
            "cms_area_zipcode_approval",
            "is_active ='1' ",
            "area_zipcode_name",
            false
        );

        $data["ref_list"]["setup_bucket"] = $this->Common_model->get_ref_master_crm(
            "bucket_id AS value, bucket_label AS item",
            "cms_bucket_baf",
            "is_active ='1' ",
            "bucket_label",
            false
        );

        $data["ref_list"]["days"] = array(
            "1" => "1",
            "2" => "2",
            "3" => "3",
            "4" => "4",
            "5" => "5",
            "6" => "6",
            "7" => "7",
            "8" => "8",
            "9" => "9",
            "10" => "10",
            "11" => "11",
            "12" => "12",
            "13" => "13",
            "14" => "14",
            "15" => "15",
            "16" => "16",
            "17" => "17",
            "18" => "18",
            "19" => "19",
            "20" => "20",
            "21" => "21",
            "22" => "22",
            "23" => "23",
            "24" => "24",
            "25" => "25",
            "26" => "26",
            "27" => "27",
            "28" => "28",
            "29" => "29",
            "30" => "30",
            "31" => "31"
        );

        $data["ref_list"]["months"] = array(
            "1" => "January",
            "2" => "February",
            "3" => "March",
            "4" => "April",
            "5" => "May",
            "6" => "June",
            "7" => "July",
            "8" => "August",
            "9" => "September",
            "10" => "October",
            "11" => "November",
            "12" => "December"
        );

        $last_year = date("Y", strtotime("-20 year"));
        $curr_year = date("Y");
        $data["ref_list"]["years"] = array();
        for ($i = $last_year; $i <= $curr_year; $i++) {
            $data["ref_list"]["years"][$i] = $i;
        }

        //echo "<pre>";
        //print_r($data["ref_list"]["years"]);
        //echo "</pre>";

        $data["month_list"] = array(
            "1" => "January",
            "2" => "February",
            "3" => "March",
            "4" => "April",
            "5" => "May",
            "6" => "June",
            "7" => "July",
            "8" => "August",
            "9" => "September",
            "10" => "October",
            "11" => "November",
            "12" => "December"
        );

        $data["m_month_list"] = array(
            "1" => "Month 1",
            "2" => "Month 2",
            "3" => "Month 3",
            "4" => "Month 4",
            "5" => "Month 5",
            "6" => "Month 6",
            "7" => "Month 7",
            "8" => "Month 8",
            "9" => "Month 9",
            "10" => "Month 10",
            "11" => "Month 11",
            "12" => "Month 12"
        );

        $data["yes_no"] = array(
            "Y" => "Yes",
            "N" => "No"
        );

        $data["ref_list"]["m_bucket"] = array(
            "M0" => "M0",
            "M1" => "M1",
            "M2" => "M2",
            "M3" => "M3",
            "M4" => "M4",
            "M5" => "M5",
            "M6" => "M6",
            "M7" => "M7"
        );

        return view('App\Modules\Setting\Views\SettingView', $data);
    }
}
