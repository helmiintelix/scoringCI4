<?php
namespace App\Modules\DialingSetup\models;
use App\Models\Common_model;
use CodeIgniter\Model;

Class DialingSetupModel Extends Model 
{

    function found_class_dialing_mode_call_status($class_id)
	{
		$bind = array($class_id);
		$sql	= "SELECT * FROM  acs_dialing_mode_call_status WHERE class_id = ? ";
		$res	= $this->db->query($sql, $bind);
		$data = $res->getResultArray();

		// var_dump($data);
		// die;
		// echo $this->db->last_query();
		if (COUNT($data) > 0) {
			return true;
		}

		return false;
	}

	function update_dialing_mode_call_status_predictive($data_post)
	{
		$data = array(
			"dialing_mode_id"		=> $data_post['dialing_mode_id'],
			"formula_factor"		=> $data_post['formula_factor'],
			"call_timeout"			=> $data_post['call_timeout'],
			//"left_message_call_back"	=> $data_post['left_message_callback_select'],
			"try_again_after"		=> $data_post['try_again_after'],
			"max_call_attempt"		=> $data_post['max_call_attempt'],
			"call_per_cycle"		=>  8, //$data_post['call_per_cycle'],
			"call_priority_1"		=> $data_post['call_priority_1'],
			"call_priority_2"		=> $data_post['call_priority_2'],
			"call_priority_3"		=> $data_post['call_priority_3'],
			"call_priority_4"		=> $data_post['call_priority_4'],
			"call_priority_5"		=> $data_post['call_priority_5'],
			"call_priority_6"		=> $data_post['call_priority_6'],
			"call_priority_7"		=> $data_post['call_priority_7'],
			"call_priority_8"		=> $data_post['call_priority_8'],
			"can_call"				=> $data_post['can_call']
		);


		$builder = $this->db->table('acs_dialing_mode_call_status');

		$builder->where('class_id', $data_post['class_id']);
		$builder->update($data);
	}

	
	function insert_dialing_mode_call_status($data)
	{
		$builder = $this->db->table('acs_dialing_mode_call_status');
		$rs = $builder->insert($data);
		return $rs; 
	}

}