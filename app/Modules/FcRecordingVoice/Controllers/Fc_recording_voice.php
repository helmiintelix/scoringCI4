<?php 
namespace App\Modules\FcRecordingVoice\Controllers;
use App\Modules\FcRecordingVoice\Models\Fc_recording_voice_model;
use CodeIgniter\Cookie\Cookie;

class Fc_recording_voice extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Fc_recording_voice_model = new Fc_recording_voice_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		// $data['token'] = $this->Fc_recording_voice_model->get_ecx8_token();		
		return view('\App\Modules\FcRecordingVoice\Views\Recordingfc_list_view', $data);
	}
	function get_recording_list(){
		// $cache = session()->get('USER_ID').'_recordingfc_list';
		// if($this->cache->get($cache)){
		// 	$data = json_decode($this->cache->get($cache));
		// 	$rs = array('success' => true, 'message' => '', 'data' => $data);
		// }else{
		// 	$data = $this->Fc_recording_voice_model->get_recording_list();
		// 	$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
		// 	$rs = array('success' => true, 'message' => '', 'data' => $data);
		// }
		$data = $this->Fc_recording_voice_model->get_recording_list();
		$rs = array('success' => true, 'message' => '', 'data' => $data);
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function get_path(){
		$data['id'] = $this->input->getGet('id');
		
		$data["file_path"] = $this->Common_model->get_record_value('file_path', 'ecentrix.ecentrix_recording', 'id = "'.$data['id'].'"');
		$data["data"] = $this->Common_model->get_record_value('*', 'ecentrix.ecentrix_recording', 'id = "'.$data['id'].'"');

		//$data["file_path"] = $this->Common_model->get_wav_file($data['file_path']);
		$data["file_path"] = 'https://ecx8demo.ecentrix.net:8180/api/recording/stream/context/ecentrix-outbound/id/'.$data['id'].'/token/'.$this->Fc_recording_voice_model->get_ecx8_token();
		$rs = array('success' => true, 'message' => '', 'data' => $data);
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	//karena curl yang tidak bisa dan token_spv belum ada jadi sementara menggunakan function ini untuk uji coba secara lokal.
	//masih belum tau kenapa curl tidak bekerja di laragon versi 6.0 dan php 8.3.3
	// public function get_path_for_dev(){
	// 	$id = $this->request->getGet('id');
	// 	$context = $this->request->getGet('context');
		
	// 	$file_name = $id . '.mp3';
	// 	$output_filename = "D:\\laragon\\www\\downloaded\\" . $file_name;
		
	// 	return $this->response->setJSON([
	// 		'success' => true,
	// 		'file_path' => $output_filename,
	// 		'url_download' => "https://" . $_SERVER['HTTP_HOST'] . '/downloaded/' . $file_name,
	// 	]);
	// }

}