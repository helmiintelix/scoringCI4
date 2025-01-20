<?php 
namespace App\Modules\SetupVoiceBlast\Controllers;
use App\Modules\SetupVoiceBlast\Models\Setup_voice_blast_maker_model;
use App\Modules\SetupVoiceBlast\Models\Setup_voice_blast_temp_model;
use CodeIgniter\Cookie\Cookie;


class Setup_voice_blast_maker extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_voice_blast_maker_model = new Setup_voice_blast_maker_model();
		$this->Setup_voice_blast_temp_model = new Setup_voice_blast_temp_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\SetupVoiceBlast\Views\Setup_voice_blast_maker_view', $data);
	}
	function get_campaign_list(){
		$cache = session()->get('USER_ID').'_campaign_list';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_voice_blast_maker_model->get_campaign_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function campaign_add_form(){
		$data["voice"] = $this->Common_model->get_record_list("paramvalue value, paramdescription AS item", "voice_aktor", "paramtype='voicename'", "paramdescription");
		$data['days'] = $this->Common_model->get_record_list("id value, name AS item", "aav_configuration", "parameter='DAYS'", "id");

		return view('\App\Modules\SetupVoiceBlast\Views\Setup_voice_blast_maker_add_form_view', $data);

	}
	
	function save_campaign(){
		$id = $this->input->getPost('campaignid');
		$is_exist = $this->Common_model->get_record_value('id', 'voice_blast_campaign', "id = '" . $id . "'");
		$is_exist_tmp = $this->Common_model->get_record_value('id', 'voice_blast_campaign_tmp', "id = '" . $id . "' and flag = '1'");
		if (!$is_exist || !$is_exist_tmp) {
			$data['id'] = $id;
			$data['campaign_description'] = $this->input->getPost('description');
			$data['call_script'] = $this->input->getPost('call_script');
			$data['days'] = implode(',', $this->input->getPost('days'));
			$data['start_time'] = $this->input->getPost('start_time1') . ":" . $this->input->getPost('start_time2');
			$data['end_time'] = $this->input->getPost('end_time1') . ":" . $this->input->getPost('end_time2');
			$data['max_retry'] = $this->input->getPost('max_retry');
			$data['call_timeout'] = $this->input->getPost('call_timeout');
			$data['interval_next_dial_not_connect'] = $this->input->getPost('next_dial');
			$data['priority'] = $this->input->getPost('priority');
			$data['voiceblast_detail'] = stripslashes($this->input->getPost('sql')) ;
			$data['voiceblast_json'] = $this->input->getPost('sql_json');
			$data['action'] = 'ADD';
			$data['holiday'] = $this->input->getPost('holiday');
			$data['voice_type'] = $this->input->getPost('voice_type');
			$data['phone_priority'] = $this->input->getPost('phone_priority');
			$data['is_active'] = $this->input->getPost('opt-active-flag');
			$data['flag'] = "1";
			$data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			$return = $this->Setup_voice_blast_maker_model->save_campaign($data);
			// dd($this->input->getPost('campaignid'));
			if($return){
				$return2 = $this->Setup_voice_blast_maker_model->add_campaign($data);
				if ($return2) {
					$cache = session()->get('USER_ID').'_campaign_list';
					$this->cache->delete($cache);
				}

				$cache2 = session()->get('USER_ID').'_campaign_list_temp';
				$this->cache->delete($cache2);
				
				$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
			
		} else{
			$rs = array('success' => false, 'message' => 'User ID Already Registered. Please insert another ID.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function campaign_edit_form(){
		
		$data['id_campaign'] = $this->input->getGet('id');
		$data['days'] = $this->Common_model->get_record_list("id value, name AS item", "aav_configuration", "parameter='DAYS'", "id");
		$data["campaign_data"] = $this->Common_model->get_record_values("*", "voice_blast_campaign", "id = '" . $data["id_campaign"] . "'");
		return view('\App\Modules\SetupVoiceBlast\Views\Setup_voice_blast_maker_edit_form_view', $data);


	}
	function update_campaign(){
		$id = $this->input->getPost('campaignid');
		$is_exist_tmp = $this->Common_model->get_record_value('id', 'voice_blast_campaign_tmp', "id = '" . $id . "' and flag = '1'");
		if (!$is_exist_tmp) {
			$data['id'] = $id;
			$data['campaign_description'] = $this->input->getPost('description');
			$data['call_script'] = $this->input->getPost('call_script');
			$data['days'] = implode(',', $this->input->getPost('days'));
			$data['start_time'] = $this->input->getPost('start_time1') . ":" . $this->input->getPost('start_time2');
			$data['end_time'] = $this->input->getPost('end_time1') . ":" . $this->input->getPost('end_time2');
			$data['max_retry'] = $this->input->getPost('max_retry');
			$data['call_timeout'] = $this->input->getPost('call_timeout');
			$data['interval_next_dial_not_connect'] = $this->input->getPost('next_dial');
			$data['priority'] = $this->input->getPost('priority');
			$data['voiceblast_detail'] = stripslashes($this->input->getPost('sql')) ;
			$data['voiceblast_json'] = $this->input->getPost('sql_json');
			$data['action'] = 'EDIT';
			$data['holiday'] = $this->input->getPost('holiday');
			$data['voice_type'] = $this->input->getPost('voice_type');
			$data['phone_priority'] = $this->input->getPost('phone_priority');
			$data['is_active'] = $this->input->getPost('opt-active-flag');
			$data['flag'] = "1";
			$data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			$return = $this->Setup_voice_blast_maker_model->update_campaign($data);
			// dd($this->input->getPost('campaignid'));
			if($return){
				// $return2 = $this->Setup_voice_blast_maker_model->add_campaign($data);
				// if ($return2) {
				// }
				
				$cache = session()->get('USER_ID').'_campaign_list_temp';
				$cachemaker = session()->get('USER_ID').'_campaign_list';
	
				$this->cache->delete($cache);
				$this->cache->delete($cachemaker);
				
				$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
			
		} else{
			$rs = array('success' => false, 'message' => 'Please approve/reject the campaign first.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
	}
	function delete_campaign(){
		$id = $this->input->getPost('id');
		$is_exist_tmp = $this->Common_model->get_record_value('id', 'voice_blast_campaign_tmp', "id = '" . $id . "' and flag = '1'");
		if (!$is_exist_tmp) {
			$return = $this->Setup_voice_blast_maker_model->delete_campaign($id);
			// return $this->response->setStatusCode(200)->setJSON($return);
			if($return){
				$newCsrfToken = csrf_hash();

				$cache = session()->get('USER_ID').'_campaign_list_temp';
				$cachemaker = session()->get('USER_ID').'_campaign_list';
	
				$this->cache->delete($cache);
				$this->cache->delete($cachemaker);
				
				$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON(array_merge($rs, ['newCsrfToken' => $newCsrfToken]));
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
			
		} else{
			$rs = array('success' => false, 'message' => 'Please approve/reject the campaign first.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function test_data_view(){
		$data['classification'] = $this->input->getPost('classification');
		// $data['id'] = $this->input->getGet('id');
		return view('\App\Modules\SetupVoiceBlast\Views\test_data_sms_view', $data);
	}
	function get_test_data(){
		$id = $this->input->getGet('id');
		$cache = session()->get('USER_ID').'_test_data_list';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_voice_blast_maker_model->get_test_data($id);
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);

	}

}