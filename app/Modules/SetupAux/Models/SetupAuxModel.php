<?php
namespace App\Modules\SetupAux\models;
use CodeIgniter\Model;

Class SetupAuxModel Extends Model 
{
    function get_general_setting_tele()
	{
		$builder = $this->db->table('acs_configuration');
		$builder->select('id, value');

		$result = $builder->get();
		$data		= $result->getResultArray();

		$arr_data = array();
       
		foreach ($data as $key=>$value) {
			$arr_data[$value["id"]] = $value["value"];
		}

		return $arr_data;
	}
}