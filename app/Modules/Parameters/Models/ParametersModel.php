<?php

namespace App\Modules\Parameters\Models;

use CodeIgniter\Model;
use App\Models\Common_model;

class ParametersModel extends Model
{
    protected $Common_model;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new Common_model();
    }

    public function get_parameter($parameter)
    {
        $builder = $this->db->table('sc_scoring_parameter');
        $builder->select('id, name, is_include, is_primary, is_sum, is_monthly, value_content, map_reference');
        $builder->where('parameter', $parameter);
        $builder->where('is_active', 'Y');
        $builder->where('name <>', '');
        $builder->orderBy('order_num', 'ASC');

        $query    = $builder->get();
        $arr_data = $query->getResultArray();

        return $arr_data;
    }

    public function update_parameter($param_data)
    {
        $builder = $this->db->table('sc_scoring_parameter_tmp');

        $data = [$param_data["column"] => $param_data["value"]];

        $builder->where('parameter', $param_data["param"]);
        $builder->where('id', $param_data["param_id"]);
        $return = $builder->update($data);

        return $return;
    }

    public function update_parameter_commit()
    {
        $sql = "REPLACE INTO sc_scoring_parameter SELECT * FROM sc_scoring_parameter_tmp";
        $return = $this->db->query($sql);

        return $return;
    }
}
