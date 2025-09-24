<?php

namespace App\Modules\Preview\Models;

use CodeIgniter\Model;

class PreviewModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_scheme_list()
    {
        $builder = $this->db->table('sc_scoring_scheme a');
        $builder->select('
            a.id AS scheme_id,
            a.name AS scheme_name,
            a.score_value,
            a.score_value2,
            "0" AS total_data,
            method,
            IF(group_by="CM_CARD_NMBR", "Agreement Number", "CIF No.") AS group_by,
            "" AS primary_filter,
            d.description AS parameter_group,
            c.name AS parameter_selected,
            IF(LENGTH(b.parameter_value) > 80, CONCAT(LEFT(b.parameter_value, 60), " ..."), b.parameter_value) AS parameter_value,
            b.parameter_function AS parameter_function,
            c.value_content,
            c.map_reference
        ', false);

        $builder->join('sc_scoring_scheme_setup b', 'b.id=a.id');
        $builder->join('sc_scoring_parameter c', 'c.id=b.parameter_id AND c.parameter=b.parameter AND c.is_active="Y"');
        $builder->join('cc_reference d', 'd.reference="PARAMETER_GROUP" AND d.value=c.parameter');
        $builder->where('a.is_active', 'Y');
        $builder->orderBy('a.name', 'ASC');
        $builder->orderBy('d.order_num', 'ASC');

        $query = $builder->get();
        $tmp_arr_data = $query->getResultArray();

        $arr_data = [];
        foreach ($tmp_arr_data as $row) {
            $row["total_data"] = '0';
            $arr_data[] = $row;
        }

        return $arr_data;
    }
}
