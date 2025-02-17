<?php
namespace App\Modules\FcRecordingVoice\models;
use Config\Database;
use CodeIgniter\Model;

Class Fc_recording_voice_model Extends Model 
{
	function get_ecx8_token(){
		if (session()->get('ecx8_access_token') !== null) {
			// 2023-01-20T21:04:17.321
			if (session()->get('ecx8_expire_time') < date('Y-m-dTH:00:00')) {
				return session()->get('ecx8_access_token');
			}
		}
        $url = "https://ecentrix8.ecentrix.net/ecentrix/auth/supervisor.php"; 
		$clientId = "ecentrix8";
		$authKey = "AFCD32C15ADAB0819EB48F820BC832DABD09170986BC2CA42E379D24F57CE467";
		$spvId = "spv_oub01";
			
		$param = array(
			"client_id" => $clientId ,
			"auth_key" => $authKey,
			"supervisor_id" => $spvId
		);
		$payload = json_encode($param);

        $curl = curl_init();

		curl_setopt_array($curl, 
			array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $payload,
				CURLOPT_HTTPHEADER => array(
					'Content-Type: text/plain'
					),
			)
		);
		
		$data = curl_exec($curl);
		$error_no = "-";
		$error_msg = "-";
		if (curl_error($curl)) {
			$error_no = curl_errno($curl);
			$error_msg =  '(' . $error_no . ') : ' . curl_error($curl);
		}
		curl_close($curl);
		if (!$data) {
			echo "CALLING API ".$url." || RESPONSE error: " . $error_msg . "<br>";
			return 'TOKENERROR';
		}
		//echo "CALLING API ".$url." || RESPONSE : " . $data . "<br>";
		$json1  = json_decode($data,true);

        session()->set('ecx8_access_token', $json1['access_token']);
		session()->set('ecx8_expire_time', $json1['expire_time']);
		
		return $json1['access_token'];
	}
	function get_recording_list(){
		$this->builder = $this->db->table("ecentrix.recording as a");
        $select = array(
			' "-" action',
           	'id',
			'IFNULL(SUBSTRING_INDEX(SUBSTRING_INDEX(customer_Data, \'~~\', 2), \'~~\', -1), \'\')  contract_number',
			'IFNULL(SUBSTRING_INDEX(SUBSTRING_INDEX(customer_Data, \'~~\', 4), \'~~\', -1), \'\') customer_name',
			'IFNULL(SUBSTRING_INDEX(SUBSTRING_INDEX(customer_Data, \'~~\', 1), \'~~\', -1), \'\') agent_id',
			'a.extension_id',
			'phone_number as  number',
			'TIME(a.start_time) start_time',
			'TIME(a.end_time) end_time',
			'SEC_TO_TIME(ROUND(a.duration/1000)) duration',
			'file_path',
			'customer_data',
			'create_time'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		// $this->builder->join('acs_contract_aging b','b.contract_number = a.contract_number','left');
		// $this->builder->join('acs_customer_profile c','b.customer_id = c.customer_id','left');
        // $this->builder->orderBy('a.create_time', 'DESC');
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
		
        if ($rResult->getNumRows() > 0) {
			foreach ($return as &$row) {
                $row['action'] = '<a href="#" onClick="get_path_recording(\''.$row['id'].'\')" >play</a> | <a href="#" onClick="get_path_download(\''.$row['id'].'\')" >download</a>';
            }
            unset($row);
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
}