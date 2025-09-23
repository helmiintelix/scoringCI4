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
}
