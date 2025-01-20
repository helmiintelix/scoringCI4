<?php 
namespace App\Modules\RecordingVoice\Controllers;
use App\Modules\RecordingVoice\Models\Recording_voice_model;
use CodeIgniter\Cookie\Cookie;

class Recording_voice extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Recording_voice_model = new Recording_voice_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\RecordingVoice\Views\Recording_list_view', $data);
	}
	function get_recording_list(){
		$cache = session()->get('USER_ID').'_recording_list';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Recording_voice_model->get_recording_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function get_path(){
		$id = $this->input->getGet('id');
		$context = $this->input->getGet('context');
		$dirname = getenv('ECX8_DOWNLOADED_RECORDING');

		// if (!file_exists($dirname)) {
		
		// 	mkdir($dirname, 0755);
		// }
		$file_name = $id.'.mp3';
		$output_filename = $dirname.''.$file_name;

		$url = 'https://'.getenv('ECX8_URL').':8180/api/recording/download/context/'.$context.'/id/'.$id;

		$curl = \Config\Services::curlRequest();
		$headers = [
            'X-Access-Token: ' . session()->get('SPV_TOKEN'),
        ];
		$response = $curl->request('GET', $url, [
            'headers' => $headers,
            'timeout' => 0,
            'http_errors' => false,
        ]);
		if ($response->getStatusCode() === 200) {
            $fp = fopen($output_filename, 'w');
            fwrite($fp, $response->getBody());
            fclose($fp);

            return $this->response->setJSON([
                'success' => true,
                'file_path' => $output_filename,
                'url_download' => "https://".$_SERVER['HTTP_HOST'].'/downloaded/'.$file_name,
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to download the recording.',
            ]);
        }

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