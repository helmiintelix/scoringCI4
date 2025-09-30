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

    public function delete_scheme($scheme_id)
    {
        $builder = $this->db->table('sc_scoring_scheme_setup a');
        $builder->select('a.*');
        $builder->where('a.id', $scheme_id);
        $scheme_before = $builder->get()->getResultArray();

        $builder = $this->db->table('sc_scoring_scheme a');
        $builder->select('a.*');
        $builder->where('a.id', $scheme_id);
        $scheme = $builder->get()->getResultArray();

        if (empty($scheme)) {
            return false;
        }

        $arr = [
            'id'                   => uuid(false),
            'id_scheme'            => $scheme[0]['id'],
            'id_upload'            => $scheme[0]['upload_id'],
            'name_scheme'          => $scheme[0]['name'],
            'score_value'          => $scheme[0]['score_value'],
            'score_value2'         => $scheme[0]['score_value2'],
            'scheme_detail_before' => json_encode($scheme_before),
            'scheme_detail_after'  => null,
            'action'               => 'DELETE',
            'created_by'           => session()->get('USER_ID'),
            'created_time'         => date('Y-m-d H:i:s')
        ];

        $this->db->table('sc_scoring_log')->insert($arr);

        $this->db->table('sc_scoring_scheme_setup')
            ->where('id', $scheme_id)
            ->delete();

        $return = $this->db->table('sc_scoring_scheme')
            ->where('id', $scheme_id)
            ->delete();

        return $return;
    }

    public function active_parameter($param)
    {
        $builder = $this->db->table('sc_scoring_scheme_setup');
        $builder->select('parameter, parameter_id', false);
        $builder->where('id', $param['scheme_id']);
        $result = $builder->get();
        $arr_data = $result->getResultArray();

        $scheme_data = [
            'is_include' => 'YES'
        ];

        foreach ($arr_data as $key => $value) {
            //dibuat yes
            $this->db->table('sc_scoring_parameter')
                ->where('id', $value['parameter_id'])
                ->where('parameter', $value['parameter'])
                ->update($scheme_data);

            $return = $this->db->table('sc_scoring_parameter_tmp')
                ->where('id', $value['parameter_id'])
                ->where('parameter', $value['parameter'])
                ->update($scheme_data);
        }

        return $return;
    }
}
