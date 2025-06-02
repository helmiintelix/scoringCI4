<?php
namespace App\Modules\SetupPassword\models;
use App\Models\Common_model;
use CodeIgniter\Model;

Class SetupPassword_model Extends Model 
{
	protected $Common_model;
	function __construct()
	{
		parent::__construct();
		$this->Common_model = new Common_model();
	}

	function get_general_setting()
	{
		$this->builder = $this->db->table('aav_configuration');

		$this->builder->select('id, value', false);

		$result = $this->builder->get();
		$data		= $result->getResultArray();

		$arr_data = array();

		foreach ($data as $row) {
			$arr_data[$row["id"]] = $row["value"];
		}

		return $arr_data;
	}

	function update_system_setting($param_data)
	{
		//log_message('debug',__METHOD__);
		$this->builder = $this->db->table('aav_configuration_tmp');

		$this->builder->where('parameter', $param_data["parameter"]);
		$this->builder->where('id', $param_data["id"]);
		$return = $this->builder->update($param_data);
		

		return $return;
	}

	function get_password_setting_temp(){
        $this->builder = $this->db->table('aav_configuration_tmp a');
       
        $select = array(
            'a.parameter','a.id', 'a.name', 'a.value', 'concat(b.id," - ",b.name) created_by', 'date_format(a.created_time,"%d %b %Y")created_time'
        );
        $this->builder->join('cc_user b', 'a.created_by=b.id');
        $this->builder->where("a.id !=", "ALERT_ANGSURAN");
        $this->builder->where("a.add_field1 =", "APPROVAL");
        $this->builder->where("a.parameter !=", "UDF");
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
      
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
            foreach ($rResult->getResultArray()[0] as $key => $value) {
                $result['header'][] = array('field' => $key);
            }
            $result['data'] = $return;
        } else {
            $result['header'] = array();
            $result['data'] = array();
        }
        
        $rs =  $result;
        return $rs;
    }

	function approve_system_setting($param_data)
	{
		//log_message('debug',__METHOD__);
		$this->db->query("replace into aav_configuration select * from aav_configuration_tmp where parameter ='" . $param_data["parameter"] . "' and add_field1 = 'APPROVAL' and id='" . $param_data["id"] . "'");
		
		$this->builder = $this->db->table('aav_configuration_tmp');

		$this->builder->where('parameter', $param_data["parameter"]);
		$this->builder->where('id', $param_data["id"]);
		$return = $this->builder->update($param_data);
	
		return $return;
	}

	function reject_system_setting($param_data)
	{
		$this->builder = $this->db->table('aav_configuration_tmp');

		$this->builder->where('parameter', $param_data["parameter"]);
		$this->builder->where('id', $param_data["id"]);
		$return = $this->builder->update($param_data);

		return $return;
	}
}