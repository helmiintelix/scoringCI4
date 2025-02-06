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
			"dialing_mode_id" => $data_post['dialing_mode_id'],
			"contract_process_time" => $data_post['contract_process_time'],
			"busy_call_back" => $data_post['busy_call_back'],
			"left_message_call_back" => $data_post['left_message_call_back'],
			"auto_disconect" => $data_post['auto_disconect'],
			"no_ans_call_back" => $data_post['no_ans_call_back'],
			"daily_dial_limiter" => $data_post['daily_dial_limiter'],
			"max_ptp_days" => $data_post['max_ptp_days'],
			"max_req_visit" => $data_post['max_req_visit'],
			"can_call" => $data_post['can_call'],
			"call_timeout" => $data_post['call_timeout'],
			"formula_factor" => $data_post['formula_factor'],
			"max_call_attempt" => $data_post['max_call_attempt'],
			"ecentrix_group"  => $data_post['ecentrix_group'],
			"call_priority_1" => $data_post['call_priority_1'],
			"call_priority_2" => $data_post['call_priority_2'],
			"call_priority_3" => $data_post['call_priority_3'],
			"call_priority_4" => $data_post['call_priority_4'],
			"call_priority_5" => $data_post['call_priority_5'],
			"call_priority_6" => $data_post['call_priority_6'],
			"call_priority_7" => $data_post['call_priority_7'],
			"call_priority_8" => $data_post['call_priority_8'],
			"call_priority_9" => $data_post['call_priority_9'],
			"call_priority_10" => $data_post['call_priority_10'],
			"call_priority_11" => $data_post['call_priority_11'],
			"call_priority_12" => $data_post['call_priority_12'],
			"call_priority_13" => $data_post['call_priority_13'],
			"call_priority_14" => $data_post['call_priority_14'],
			"call_priority_15" => $data_post['call_priority_15'],
			"call_priority_16" => $data_post['call_priority_16'],
			"call_priority_17" => $data_post['call_priority_17'],
			"call_priority_18" => $data_post['call_priority_18']
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