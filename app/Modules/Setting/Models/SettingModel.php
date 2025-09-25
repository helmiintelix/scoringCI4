<?php

namespace App\Modules\Setting\Models;

use CodeIgniter\Model;
use App\Models\Common_model;

class SettingModel extends Model
{
    protected $Common_model;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new Common_model();
    }

    public function get_scheme_data($scheme_id)
    {
        $builder = $this->db->table('sc_scoring_scheme');
        $builder->select('*');
        $builder->where('id', $scheme_id);

        $query    = $builder->get();
        $arr_data = $query->getResultArray();

        return $arr_data;
    }

    public function get_setting($scheme_id, $parameter)
    {
        $builder = $this->db->table('sc_scoring_parameter AS a');

        if ($scheme_id === 'NEW') {
            $builder->select('UUID() AS obj_id, a.id AS param_id, a.name, a.is_include, a.is_primary, a.is_sum, a.is_monthly, a.value_type, a.value_content, a.map_reference, a.reference_table AS reference_table, a.reference AS parameter_reference, "" AS parameter_function, "[]" AS parameter_value, "" AS parameter_function_month, "[]" AS parameter_value_month, "[]" AS parameter_value_month_tmp, "" AS mapping_reference, "" AS mapping_parameter_function, "" AS mapping_parameter_value, "" AS score_value, "" AS score_value2', false);
            $builder->where('a.is_include', 'YES');
            $builder->where('a.is_active', 'Y');
            $builder->where('a.name <>', '');
            $builder->where('a.parameter', $parameter);
            $builder->orderBy('a.order_num', 'ASC');
        } else {
            $builder->select('\'' . $scheme_id . '\' AS obj_id, a.id AS param_id, a.name, a.is_include, a.is_primary, a.is_sum, a.is_monthly, a.value_type, a.value_content, a.map_reference, a.reference_table AS reference_table, a.reference AS parameter_reference, b.parameter_function, b.parameter_value, b.parameter_function_month, b.parameter_value_month, b.parameter_value_month_tmp, b.mapping_reference, b.mapping_parameter_function, b.mapping_parameter_value, b.score_value, b.score_value2', false);
            $builder->join('sc_scoring_scheme_setup b', 'a.parameter = b.parameter AND a.id = b.parameter_id AND b.id = "' . $scheme_id . '"', 'LEFT');
            $builder->where('a.is_include', 'YES');
            $builder->where('a.is_active', 'Y');
            $builder->where('a.name <>', '');
            $builder->where('a.parameter', $parameter);
            $builder->orderBy('a.order_num', 'ASC');
        }

        $query = $builder->get();
        return $query->getResultArray();
    }
}
