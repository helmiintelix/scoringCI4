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

    public function get_included_parameter($parameter)
    {
        $builder = $this->db->table('sc_scoring_parameter');
        $builder->select('id, name, is_include, is_primary, is_sum, is_monthly, value_content, map_reference', false);
        $builder->where('parameter', $parameter);
        $builder->where('is_active', 'Y');
        $builder->where('name <>', '');
        $builder->where('is_include', 'YES');
        $builder->orderBy('order_num', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function set_setting($form_mode, $scheme_data, $setting_data)
    {
        $user_id = session()->get('USER_ID');
        $created_time = date('Y-m-d H:i:s');

        $builder_scheme = $this->db->table('sc_scoring_scheme');
        $builder_setup  = $this->db->table('sc_scoring_scheme_setup');
        $builder_log    = $this->db->table('sc_scoring_log');

        if ($form_mode == "ADD") {
            // Hapus dulu jika ada
            $builder_scheme->where('id', $scheme_data['id'])->delete();
            // Insert scheme baru
            $builder_scheme->insert($scheme_data);

            // Insert log
            $arr = [
                'id' => uuid(false),
                'id_scheme' => $scheme_data['id'],
                'id_upload' => null,
                'name_scheme' => $scheme_data['name'],
                'score_value' => $scheme_data['score_value'],
                'score_value2' => $scheme_data['score_value2'],
                'scheme_detail_before' => null,
                'scheme_detail_after' => json_encode($setting_data),
                'action' => 'ADD',
                'created_by' => $user_id,
                'created_time' => $created_time
            ];
            $builder_log->insert($arr);
        } else {
            // Ambil data sebelum update
            $scheme_before = $this->db->table('sc_scoring_scheme_setup')
                ->where('id', $scheme_data['id'])
                ->get()
                ->getResultArray();

            // Insert log
            $arr = [
                'id' => uuid(false),
                'id_scheme' => $scheme_data['id'],
                'id_upload' => $scheme_data['upload_id'] ?? null,
                'name_scheme' => $scheme_data['name'],
                'score_value' => $scheme_data['score_value'],
                'score_value2' => $scheme_data['score_value2'],
                'scheme_detail_before' => json_encode($scheme_before),
                'scheme_detail_after' => json_encode($setting_data),
                'action' => 'EDIT',
                'created_by' => $user_id,
                'created_time' => $created_time
            ];
            $builder_log->insert($arr);

            $builder_scheme->where('id', $scheme_data['id'])->update($scheme_data);
        }

        $builder_setup->where('id', $scheme_data['id'])->delete();
        $return = $builder_setup->insertBatch($setting_data);

        return $return;
    }
}
