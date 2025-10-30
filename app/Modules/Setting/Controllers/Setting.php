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
        $data["ref_list"]['LOBCODE'] = $this->Common_model->get_all_ref_master_crm(
            "reference, value AS value, description AS item",
            "cms_reference",
            "status='1' and reference='LOB_CODE'",
            "description",
            false
        );
        $data["ref_list"]['GENDER'] = $this->Common_model->get_all_ref_master_crm(
            "reference, value AS value, description AS item",
            "cms_reference",
            "status='1' and reference='GENDER'",
            "order_num",
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

    public function save_setting()
    {
        $post = [];
        foreach ($this->request->getPost() as $key => $value) {
            if ($this->request->getPost($key) !== '' && $this->request->getPost($key) !== "--") {
                $post[$key] = $this->request->getPost($key);
            }
        }

        try {
            $form_mode = $post['form_mode'];
            $method    = $post['opt_method'];
            $group_by  = $post['opt_group_by'];

            $scheme_data = [
                "id"           => $post['score_scheme_id'],
                "name"         => $post['score_label'],
                "score_value"  => $post['score_value_all'],
                "score_value2" => $post['score_value_all2'],
                "method"       => $method,
                "group_by"     => $group_by,
                "created_by"   => session()->get('USER_ID'),
                "created_time" => date('Y-m-d H:i:s')
            ];

            $setting_data = [];

            // === CONTRACT_AGING ===
            $included_parameter = $this->SettingModel->get_included_parameter("CONTRACT_AGING");
            foreach ($included_parameter as $row) {
                if (
                    array_key_exists('par_CONTRACT_AGING_' . $row['id'], $post)
                    || (array_key_exists('par_CONTRACT_AGING_' . $row['id'] . '_start', $post)
                        && array_key_exists('par_CONTRACT_AGING_' . $row['id'] . '_end', $post))
                    || array_key_exists('par_CONTRACT_AGING_' . $row['id'] . '[]', $post)
                ) {
                    $tmp_value = [];
                    switch ($row['value_content']) {
                        case "MULTIPLE_VALUE":
                        case "DAY":
                        case "MONTH":
                        case "YEAR":
                            if (!empty($post['par_CONTRACT_AGING_' . $row['id']])) {
                                foreach ($post['par_CONTRACT_AGING_' . $row['id']] as $selected) {
                                    $tmp_value[] = $selected;
                                }
                            }
                            break;
                        case "SINGLE_VALUE":
                        case "TEXT":
                        case "NUMBER":
                            $tmp_value[] = $post['par_CONTRACT_AGING_' . $row['id']];
                            break;
                        case "NUMBER_RANGE":
                            $tmp_value = [
                                $post['par_CONTRACT_AGING_' . $row['id'] . '_start'],
                                $post['par_CONTRACT_AGING_' . $row['id'] . '_end']
                            ];
                            break;
                        case "DATE":
                            $tmp_value[] = date("Y-m-d", strtotime($post['par_CONTRACT_AGING_' . $row['id']]));
                            break;
                        case "DATE_RANGE":
                            if (!empty($post['par_CONTRACT_AGING_' . $row['id'] . '_start']) && $post['par_CONTRACT_AGING_' . $row['id'] . '_start'] !== "--") {
                                $tmp_value = [
                                    date("Y-m-d", strtotime($post['par_CONTRACT_AGING_' . $row['id'] . '_start'])),
                                    date("Y-m-d", strtotime($post['par_CONTRACT_AGING_' . $row['id'] . '_end']))
                                ];
                            } else {
                                $tmp_value = ['', ''];
                            }
                            break;
                    }

                    $parameter_value = json_encode($tmp_value);

                    if ($method === "METHOD1") {
                        $score_value  = $post['txt_score_value_' . $row['id']];
                        $score_value2 = $post['txt_score_value_' . $row['id']];
                    } else {
                        $score_value  = "";
                        $score_value2 = "";
                    }

                    $setting_data[] = [
                        "id"                        => $scheme_data["id"],
                        "parameter"                 => "CONTRACT_AGING",
                        "parameter_id"              => $row['id'],
                        "parameter_function"        => $post['opt_function1_CONTRACT_AGING_' . $row['id']] ?? null,
                        "parameter_value"           => $parameter_value,
                        "parameter_function_month"  => "",
                        "parameter_value_month"     => "",
                        "parameter_value_month_tmp" => "",
                        "mapping_reference"         => "",
                        "mapping_parameter_function" => "",
                        "mapping_parameter_value"   => "",
                        "score_value"               => $score_value,
                        "score_value2"              => $score_value2,
                        "created_time"              => date('Y-m-d H:i:s'),
                        "created_by"                => session()->get('USER_ID')
                    ];
                }
            }

            // === SCORING_PURPLE ===
            $included_parameter = $this->SettingModel->get_included_parameter("SCORING_PURPLE");
            foreach ($included_parameter as $row) {
                if (
                    array_key_exists('par_SCORING_PURPLE_' . $row['id'], $post)
                    || (array_key_exists('par_SCORING_PURPLE_' . $row['id'] . '_start', $post)
                        && array_key_exists('par_SCORING_PURPLE_' . $row['id'] . '_end', $post))
                    || array_key_exists('par_SCORING_PURPLE_' . $row['id'] . '[]', $post)
                ) {
                    $tmp_value = [];
                    switch ($row['value_content']) {
                        case "MULTIPLE_VALUE":
                        case "DAY":
                        case "MONTH":
                        case "YEAR":
                            // Check if the value exists and is an array, not a string
                            if (
                                !empty($post['par_SCORING_PURPLE_' . $row['id']]) &&
                                is_array($post['par_SCORING_PURPLE_' . $row['id']])
                            ) {
                                foreach ($post['par_SCORING_PURPLE_' . $row['id']] as $selected) {
                                    $tmp_value[] = $selected;
                                }
                            } elseif (
                                !empty($post['par_SCORING_PURPLE_' . $row['id']]) &&
                                $post['par_SCORING_PURPLE_' . $row['id']] !== '[]'
                            ) {
                                // If it's a single value that's not an empty array string
                                $tmp_value[] = $post['par_SCORING_PURPLE_' . $row['id']];
                            }
                            break;
                        case "SINGLE_VALUE":
                        case "TEXT":
                        case "NUMBER":
                            if (
                                !empty($post['par_SCORING_PURPLE_' . $row['id']]) &&
                                $post['par_SCORING_PURPLE_' . $row['id']] !== '[]'
                            ) {
                                $tmp_value[] = $post['par_SCORING_PURPLE_' . $row['id']];
                            }
                            break;
                        case "NUMBER_RANGE":
                            $tmp_value = [
                                $post['par_SCORING_PURPLE_' . $row['id'] . '_start'],
                                $post['par_SCORING_PURPLE_' . $row['id'] . '_end']
                            ];
                            break;
                        case "DATE":
                            if (
                                !empty($post['par_SCORING_PURPLE_' . $row['id']]) &&
                                $post['par_SCORING_PURPLE_' . $row['id']] !== '[]'
                            ) {
                                $tmp_value[] = date("Y-m-d", strtotime($post['par_SCORING_PURPLE_' . $row['id']]));
                            }
                            break;
                        case "DATE_RANGE":
                            if (
                                !empty($post['par_SCORING_PURPLE_' . $row['id'] . '_start']) &&
                                $post['par_SCORING_PURPLE_' . $row['id'] . '_start'] !== "--"
                            ) {
                                $arr_dateStart = date("Y-m-d", strtotime($post['par_SCORING_PURPLE_' . $row['id'] . '_start']));
                                $arr_dateEnd   = date("Y-m-d", strtotime($post['par_SCORING_PURPLE_' . $row['id'] . '_end']));
                                $tmp_value     = [$arr_dateStart, $arr_dateEnd];
                            } else {
                                $tmp_value = ['', ''];
                            }
                            break;
                    }

                    // Only add to setting_data if we actually have a value to process
                    if (!empty($tmp_value) || $row['value_content'] === "DATE_RANGE") {
                        $parameter_value = str_replace("\/", "/", json_encode($tmp_value));

                        if ($method === "METHOD1") {
                            $score_value  = $post['txt_score_value_' . $row['id']] ?? "";
                            $score_value2 = $post['txt_score_value_' . $row['id']] ?? "";
                        } else {
                            $score_value  = "";
                            $score_value2 = "";
                        }

                        $setting_data[] = [
                            "id"                        => $scheme_data["id"],
                            "parameter"                 => "SCORING_PURPLE",
                            "parameter_id"              => $row['id'],
                            "parameter_function"        => $post['opt_function1_SCORING_PURPLE_' . $row['id']] ?? null,
                            "parameter_value"           => $parameter_value,
                            "parameter_function_month"  => "",
                            "parameter_value_month"     => "",
                            "parameter_value_month_tmp" => "",
                            "mapping_reference"         => "",
                            "mapping_parameter_function" => "",
                            "mapping_parameter_value"   => "",
                            "score_value"               => $post['score_value_all'],
                            "score_value2"              => $post['score_value_all2'],
                            "created_time"              => date('Y-m-d H:i:s'),
                            "created_by"                => session()->get('USER_ID')
                        ];
                    }
                }
            }

            // === HISTORICAL PARAM ===
            $arr_historical_param = ["CALL_HISTORY", "VISIT_HISTORY", "PAYMENT_HISTORY", "BUCKET_DPD_HISTORY"];
            foreach ($arr_historical_param as $historical_param) {
                $included_parameter = $this->SettingModel->get_included_parameter($historical_param);
                foreach ($included_parameter as $row) {
                    if (
                        array_key_exists('par_' . $historical_param . '_' . $row['id'], $post)
                        || array_key_exists('par_' . $historical_param . '_' . $row['id'] . '_start', $post)
                        || array_key_exists('par_' . $historical_param . '_' . $row['id'] . '[]', $post)
                    ) {
                        $tmp_value = [];
                        switch ($row['value_content']) {
                            case "MULTIPLE_VALUE":
                            case "DAY":
                                foreach ($post['par_' . $historical_param . '_' . $row['id']] as $selected) {
                                    $tmp_value[] = $selected;
                                }
                                break;
                            case "SINGLE_VALUE":
                            case "TEXT":
                            case "NUMBER":
                            case "MAPPING":
                                $tmp_value[] = $post['par_' . $historical_param . '_' . $row['id']];
                                break;
                            case "NUMBER_RANGE":
                            case "DATE_RANGE":
                                $tmp_value = [
                                    $post['par_' . $historical_param . '_' . $row['id'] . '_start'],
                                    $post['par_' . $historical_param . '_' . $row['id'] . '_end']
                                ];
                                break;
                        }

                        $parameter_value = json_encode($tmp_value);

                        if ($method === "METHOD1") {
                            $score_value = $post['txt_score_value_' . $row['id']];
                        } else {
                            $score_value = "";
                        }
                        $score_value2 = "";

                        if (!empty($post['opt_month_' . $historical_param . '_' . $row['id']])) {
                            $parameter_value_month = [];
                            foreach ($post['opt_month_' . $historical_param . '_' . $row['id']] as $month_ke) {
                                $parameter_value_month[] = date('m', strtotime("-" . $month_ke . " month"));
                            }

                            if ($row['value_content'] === "MAPPING") {
                                $setting_data[] = [
                                    "id"                        => $post['score_scheme_id'],
                                    "parameter"                 => $historical_param,
                                    "parameter_id"              => $row['id'],
                                    "parameter_function"        => "",
                                    "parameter_value"           => "",
                                    "parameter_function_month"  => "",
                                    "parameter_value_month"     => "",
                                    "parameter_value_month_tmp" => json_encode($post['opt_month_' . $historical_param . '_' . $row['id']]),
                                    "mapping_reference"         => $parameter_value,
                                    "mapping_parameter_function" => $post['opt_function2_' . $historical_param . '_' . $row['id']] ?? null,
                                    "mapping_parameter_value"   => json_encode($parameter_value_month),
                                    "score_value"               => $score_value,
                                    "score_value2"              => $score_value2,
                                    "created_time"              => date('Y-m-d H:i:s'),
                                    "created_by"                => session()->get('USER_ID')
                                ];
                            } else {
                                $setting_data[] = [
                                    "id"                        => $post['score_scheme_id'],
                                    "parameter"                 => $historical_param,
                                    "parameter_id"              => $row['id'],
                                    "parameter_function"        => $post['opt_function1_' . $historical_param . '_' . $row['id']] ?? null,
                                    "parameter_value"           => $parameter_value,
                                    "parameter_function_month"  => $post['opt_function2_' . $historical_param . '_' . $row['id']] ?? null,
                                    "parameter_value_month"     => json_encode($parameter_value_month),
                                    "parameter_value_month_tmp" => json_encode($post['opt_month_' . $historical_param . '_' . $row['id']]),
                                    "mapping_reference"         => "",
                                    "mapping_parameter_function" => "",
                                    "mapping_parameter_value"   => "",
                                    "score_value"               => $score_value,
                                    "score_value2"              => $score_value2,
                                    "created_time"              => date('Y-m-d H:i:s'),
                                    "created_by"                => session()->get('USER_ID')
                                ];
                            }
                        }
                    }
                }
            }

            $return = $this->SettingModel->set_setting($form_mode, $scheme_data, $setting_data);

            if ($return) {
                $response = ["success" => true, "message" => "Save berhasil."];
            } else {
                $response = ["success" => false, "message" => "Save gagal."];
            }
        } catch (\Exception $e) {
            $response = ["success" => false, "message" => "Save Gagal", "error" => $e->getMessage()];
        }

        return $this->response->setJSON($response);
    }

    public function upload_file_form()
    {
        $data["BUCKET_SC"] = $this->Common_model->get_ref_master_crm(
            "value AS value, concat(value,' - ',description) AS item",
            "cms_reference",
            "status='1' and reference='BUCKET_SC'",
            "description",
            false
        );

        $data["LOB_CODE"] = $this->Common_model->get_ref_master_crm(
            "value AS value, concat(value,' - ',description) AS item",
            "cms_reference",
            "status='1' and reference='LOB_CODE'",
            "value",
            false
        );

        return view('App\Modules\Setting\Views\UploadFileFormView', $data);
    }

    public function save_file()
    {
        $file = $this->request->getFile('userfile');

        if (!$file || !$file->isValid()) {
            $data = ["error" => $file ? $file->getErrorString() : "No file uploaded"];
            return $this->response->setJSON($data);
        }

        $uploadPath = FCPATH . 'file_upload/setting_upload_file/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $fileName = $file->getName();
        $file->move($uploadPath, $fileName, true);

        $upload_data = [
            "file_name" => $fileName,
            "full_path" => $uploadPath . $fileName
        ];

        if (empty($upload_data['file_name'])) {
            $data = ["success" => false, "message" => "Failed"];
            return $this->response->setJSON($data);
        }

        $scorring_file = [
            "id"          => UUID(false),
            "file_name"   => $upload_data['file_name'],
            "full_path"   => $upload_data["full_path"],
            "lob"         => $this->request->getPost('opt_lob'),
            "bucket"      => $this->request->getPost('opt_bucket'),
            "upload_time" => date('Y-m-d H:i:s'),
            "upload_by"   => session()->get('USER_ID')
        ];

        $return = $this->SettingModel->upload_file($scorring_file);

        if ($return) {
            $response = ["success" => true, "message" => "Upload berhasil."];
        } else {
            $response = ["success" => false, "message" => "Upload gagal."];
        }

        return $this->response->setJSON($response);
    }
}
