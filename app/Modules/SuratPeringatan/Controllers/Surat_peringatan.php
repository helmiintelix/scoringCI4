<?php 
namespace App\Modules\SuratPeringatan\Controllers;
use App\Modules\SuratPeringatan\Models\Surat_peringatan_model;
use CodeIgniter\Cookie\Cookie;


class Surat_peringatan extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Surat_peringatan_model = new Surat_peringatan_model();
		helper('pdf');
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\SuratPeringatan\Views\Sp_due_list_view', $data);
	}
	function get_sp_due_list(){
		$cache = session()->get('USER_ID').'_sp_due_list';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Surat_peringatan_model->get_sp_due_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function sp_pdf($type, $list_sp)
	{
		// $cd_customers = $this->input->get_post('list_customer', true);
		//$cd_customers = explode("xsplitx", $cd_customers);
		$html = $this->Surat_peringatan_model->create_sp_html($list_sp, $type);
		if ($type == "print") {
			$DEFAULT_PATH ='D:\project upgrade skill\CI4';
			$filepath = $DEFAULT_PATH . "/file_upload/sp_letter_" . date('YmdHis') . ".pdf";
			create_pdf($filepath, $html);
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            flush(); // Flush system output buffer
            readfile($filepath);
            unlink($filepath);
        } else {
            echo $html;
        }
	}
	function print_sp(){
		$type = $this->input->getGet('type');
		$list_sp = trim($this->input->getGet('no_sp'));
		$data["the_sp"] = $this->sp_pdf($type, $list_sp);
		return view('\App\Modules\SuratPeringatan\Views\sp_view', $data);

	}
}