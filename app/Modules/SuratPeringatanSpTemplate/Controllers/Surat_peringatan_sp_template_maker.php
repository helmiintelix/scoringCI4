<?php 
namespace App\Modules\SuratPeringatanSPTemplate\Controllers;
use App\Modules\SuratPeringatanSPTemplate\Models\Surat_peringatan_sp_template_maker_model;
use CodeIgniter\Cookie\Cookie;

class Surat_peringatan_sp_template_maker extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Surat_peringatan_sp_template_maker_model = new Surat_peringatan_sp_template_maker_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\SuratPeringatanSPTemplate\Views\Letter_list_view', $data);
	}
	function letter_list(){
		$cache = session()->get('USER_ID').'_letter_list';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Surat_peringatan_sp_template_maker_model->get_letter_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function letter_add_form() {
		$data['product'] = $this->Common_model->get_record_list("productcode value, productname AS item", "acs_cms_product", "1=1", "productcode");
		return view('App\Modules\SuratPeringatanSPTemplate\Views\Letter_add_form_view', $data);
	}
	function save_letter_add(){

		$data["letter_id"]		= $this->input->getPost('txt-letter-id');
		$data["info"]			= strtoupper($this->input->getPost("txt-letter-info"));
		$data["content"]			= $this->input->getPost("content");
		$data["product"]			= $this->input->getPost("opt-template-product-code");
		$data["dpd_from"]		= $this->input->getPost("txt-letter-dpd-from");
		$data["dpd_to"]			= $this->input->getPost("txt-letter-dpd-to");
		$data["is_active"]		= '1';
		$data["created_by"]		= session()->get('USER_ID');
		$data["created_time"]	= date('Y-m-d H:i:s');
		$data["flag_tmp"]		= '0';
		$return = $this->Surat_peringatan_sp_template_maker_model->save_letter_add($data);
		if ($return) {
			$cache = session()->get('USER_ID').'_letter_list_temp';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function letterEditForm(){
		$data['id'] = $this->input->getGet('id');
		$data["data"] = $this->Common_model->get_record_values("letter_id, info,dpd_from,dpd_to,content,product", "cms_letter_template", "letter_id = '" . $data['id'] . "'");
		$data['product'] = $this->Common_model->get_record_list("productcode value, productname AS item", "acs_cms_product", "1=1", "productcode");
		return view('App\Modules\SuratPeringatanSPTemplate\Views\Letter_edit_form_view', $data);
	}
	function save_letter_edit(){
		$data["letter_id"]		= $this->input->getPost('txt-letter-id');
		$is_exist = $this->Surat_peringatan_sp_template_maker_model->isExistSP_templateUpdateId($data['letter_id']);
		if (!$is_exist) {
			$data["info"]	= strtoupper($this->input->getPost("txt-letter-info"));
			$data["content"]	= $this->input->getPost("content");
			$data["product"]	= $this->input->getPost("txt-letter-product");
			$data["dpd_from"]	= $this->input->getPost("txt-letter-dpd-from");
			$data["dpd_to"]	= $this->input->getPost("txt-letter-dpd-to");
			$data["is_active"]		= '1';
			$data["updated_by"]		= session()->get('USER_ID');
			$data["updated_time"]	= date('Y-m-d H:i:s');
			$data["flag_tmp"]		= '0';
			$return = $this->Surat_peringatan_sp_template_maker_model->save_letter_edit($data);
			if ($return) {
				$cache = session()->get('USER_ID').'_letter_list_temp';
				$this->cache->delete($cache);
				$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
		} else {
			$rs = array('success' => false, 'message' => 'SP TEMPLATE ID Already Requested for Some Updates. Please Contact Your System Administrator', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

}