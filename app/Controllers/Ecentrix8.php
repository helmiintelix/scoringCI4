<?php

namespace App\Controllers;
use CodeIgniter\Model;
use CodeIgniter\Cookie\Cookie;

class Ecentrix8 extends BaseController
{
    function getCallCenterConfiguration()
	{
		$cache = session()->get('USER_ID').'_getCallCenterConfiguration';
		
		if($this->cache->get($cache)){
			$rs = json_decode($this->cache->get($cache));
			return $this->response->setStatusCode(200)->setJSON($rs);
		}


		$ECX8_URL = env('APP_URL');
		$curl = curl_init(); 


		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://'.$ECX8_URL.'/ecentrix-agent-simulator/configuration.php',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Basic ZWN4MTIzNDplY3gxMjM0'
			),
		));

		$response = curl_exec($curl);
		
		$this->cache->save($cache,json_encode($response),env('TIMECACHE_2'));
	
		return $this->response->setStatusCode(200)->setJSON($response);
	}

	function getCallCenterConfigurationSupervisor()
	{
		$cache = session()->get('USER_ID').'_getCallCenterConfigurationSupervisor';
		
		if($this->cache->get($cache)){
			$rs = json_decode($this->cache->get($cache));
			return $this->response->setStatusCode(200)->setJSON($rs);
		}

		$ECX8_URL = env('APP_URL');
		$curl = curl_init();

		// echo "supervisor|";
		//  echo 'https://'.$ECX8_URL.'/ecentrix-agent-simulator/configurationSupervisor.php';
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://'.$ECX8_URL.'/ecentrix-agent-simulator/configurationSupervisor.php',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Basic ZWN4MTIzNDplY3gxMjM0'
			),
		));

		$response = curl_exec($curl);

		$this->cache->save($cache,json_encode($response),env('TIMECACHE_2'));
		return $this->response->setStatusCode(200)->setJSON($response);
	}

	function updateAccountCodeSessionLog(){
		$contract_number =  $this->input->getGet('contract_number');
		$call_id = $this->input->getGet('call_id');

		$db = Database::connect('cti');

		if($contract_number != '' && $call_id !=''){
			// Data yang akan diupdate
			$data = [
				'account_code' => $contract_number,  // Nilai baru untuk account_code
			];

			// Update dengan Query Builder
			$res = $db->table('session_log')
			->set($data)
			->where('call_id', $callId)  // Kondisi berdasarkan call_id
			->update();
	
			if($res){
				$response = array('success'=>true , 'message'=>'update session log success','data'=>$contract_number.'|'.$call_id);
			}else{
				$response = array('success'=>false , 'message'=>'update session log failed','data'=>$contract_number.'|'.$call_id);
			}
		}else{
			$response =  array('success'=>false , 'message'=>'update session log success','data'=>$contract_number.'|'.$call_id);
		}

	
		return $this->response->setStatusCode(200)->setJSON($response);
	}
}
?>