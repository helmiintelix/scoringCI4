<?php 
namespace App\Modules\SuratPeringatanSPTemplate\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\SuratPeringatanSPTemplate\Models\Surat_peringatan_sp_template_temp_model;


class Surat_peringatan_sp_template_temp extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Surat_peringatan_sp_template_temp_model = new Surat_peringatan_sp_template_temp_model;
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\SuratPeringatanSPTemplate\Views\Letter_list_view_temp', $data);
	}
	function letter_list_temp(){
		$cache = session()->get('USER_ID').'_letter_list_temp';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Surat_peringatan_sp_template_temp_model->get_letter_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function save_letter_edit_temp(){
		$id = $this->input->getGet('id');
		$return = $this->Surat_peringatan_sp_template_temp_model->save_letter_edit_temp($id);
		if($return){
			$cache = session()->get('USER_ID').'_letter_list_temp';
			$cachemaker = session()->get('USER_ID').'_letter_list';

			$this->cache->delete($cache);
			$this->cache->delete($cachemaker);

			$rs = array('success' => true, 'message' => 'Success to approve data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function delete_letter_edit_temp(){
		$id = $this->input->getGet('id');
		$return = $this->Surat_peringatan_sp_template_temp_model->delete_letter_edit_temp($id);
		if($return){
			$cache = session()->get('USER_ID').'_letter_list_temp';

			$this->cache->delete($cache);
			
			$rs = array('success' => true, 'message' => 'Success to reject data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}

	}

}