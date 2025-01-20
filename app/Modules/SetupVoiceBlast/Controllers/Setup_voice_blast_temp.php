<?php 
namespace App\Modules\SetupVoiceBlast\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\SetupVoiceBlast\Models\Setup_voice_blast_maker_model;
use App\Modules\SetupVoiceBlast\Models\Setup_voice_blast_temp_model;


class Setup_voice_blast_temp extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_voice_blast_maker_model = new Setup_voice_blast_maker_model();
		$this->Setup_voice_blast_temp_model = new Setup_voice_blast_temp_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\SetupVoiceBlast\Views\Setup_voice_blast_temp_view', $data);
	}
	function get_campaign_list_tmp(){
		$cache = session()->get('USER_ID').'_campaign_list_temp';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_voice_blast_temp_model->get_campaign_list_tmp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function approve_campaign(){
		$id = $this->input->getGet('id');
		$action = $this->Common_model->get_record_value('action', 'voice_blast_campaign', "id = '" . $id . "'");
		if ($action == "DELETE") {
			$return = $this->Setup_voice_blast_temp_model->approve_campaign_delete($id);

		} else {
			$return = $this->Setup_voice_blast_temp_model->approve_campaign($id);
		}
		if($return){
			$cache = session()->get('USER_ID').'_campaign_list_temp';
			$cachemaker = session()->get('USER_ID').'_campaign_list';

			$this->cache->delete($cache);
			$this->cache->delete($cachemaker);
			$rs = array('success' => true, 'message' => 'Success to approve data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'Failed to approve data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function reject_campaign(){
		$id = $this->input->getGet('id');
		$return = $this->Setup_voice_blast_temp_model->reject_campaign($id);

		// $data['data'] = json_encode($return);
		// return $this->response->setStatusCode(200)->setJSON($data['data']);
		if($return){
			$cache = session()->get('USER_ID').'_campaign_list_temp';
			$cachemaker = session()->get('USER_ID').'_campaign_list';

			$this->cache->delete($cache);
			$this->cache->delete($cachemaker);

			// Update cache dengan data terbaru
			$data = $this->Setup_voice_blast_temp_model->get_campaign_list_tmp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1'));
			
			$rs = array('success' => true, 'message' => 'Success to reject data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

}