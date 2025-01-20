<?php 
namespace App\Modules\ParameterPengajuanRestructure\Controllers;
use App\Modules\ParameterPengajuanRestructure\Models\Parameter_pengajuan_restructure_model;
use CodeIgniter\Cookie\Cookie;


class Parameter_pengajuan_restructure extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Parameter_pengajuan_restructure_model = new Parameter_pengajuan_restructure_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\ParameterPengajuanRestructure\Views\restructure_parameter_view', $data);
	}
	function get_restructure_parameter_list(){
		$cache = session()->get('USER_ID').'_restructure_parameter_list';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Parameter_pengajuan_restructure_model->get_restructure_parameter_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function restructure_parameter_add_form(){
		$data['kondisi_khusus_list_pl_rsch'] = json_encode($this->Common_model->get_ref_master('value , description item', 'cms_reference', 'reference="KONDISI_KHUSUS_PL_RSCH" and flag="1" and flag_tmp="1" ', 'order_num asc'));
		$data['kondisi_khusus_list_pl_rstr'] = json_encode($this->Common_model->get_ref_master('value , description item', 'cms_reference', 'reference="KONDISI_KHUSUS_PL_RSTR" and flag="1" and flag_tmp="1" ', 'order_num asc'));

		$data['bucket_pl'] = json_encode($this->Common_model->get_ref_master('bucket_id value , bucket_label item', 'cms_bucket', 'is_active="1" ', 'bucket_label asc',false));
		
		$data['flag_hirarki'] = $this->Common_model->get_ref_master('value , description item', 'cms_reference', 'reference="HIRARKI_DISKON" and flag="1" and flag_tmp="1" ', 'order_num asc');
		// print_r($data);
		// exit();
		return view('\App\Modules\ParameterPengajuanRestructure\Views\restructure_parameter_add_form_view', $data);

	}
	
	function save_restructure_parameter_add(){
		$name = strtoupper($this->input->getPost("txt-restructure-parameter-name"));
		$id = str_replace(' ', '_', $name); // Replaces all spaces with underscored.
		$id = preg_replace('/[^A-Za-z0-9\-]/', '_', $name);
		$is_exist = $this->Parameter_pengajuan_restructure_model->isExistDiscountParam($name);
		// print_r($id);
		// exit();
		if (!$is_exist) {
			$data['restructure_parameter_id'] = $id;
			$data['restructure_parameter_name'] = $name;
			$data["restructure_parameter_detail"]	= stripslashes(urldecode($this->input->getPost("sql")));
			$sep = "\r\n";
			$tmp = "";
			$line = strtok($data["restructure_parameter_detail"], $sep);
			while ($line !== false) {
				if (strpos($line, "IN('") !== false) {
					//hilangkan '
					$tmp2 = str_replace("'", "", $line);
					//tambah ',';
					$tmp2 = str_replace(",", "','", $tmp2);
					$tmp2 = str_replace("(", "('", $tmp2);
					$tmp2 = str_replace(")", "')", $tmp2);
					$tmp .= $tmp2 . "\r\n";
				} else {
					$tmp .= $line . "\r\n";
				}
				$line = strtok($sep);
			}
			$data['restructure_parameter_detail'] = $tmp;
			$data['restructure_parameter_json'] = $this->input->getPost("sql_json");
			$data['restructure_tipe'] = $this->input->getPost('tipe_pengajuan');
			$data['max_discount_rate'] = $this->input->getPost('txt-max-disc-rate');
			$data['max_interest_rate'] = $this->input->getPost('txt-max-interest-rate');
			$data['max_tenor'] = $this->input->getPost('txt-max-tenor');
			$data['ratio_cicilan'] = $this->input->getPost('txt-ratio-cicilan');
			$data['hirarki'] = $this->input->getPost('hirarki_flag');
			$data['desc_kondisi_khusus'] = json_encode($this->input->getPost('desc_kondisi_khusus'));
			$data['bucket_list'] = json_encode($this->input->getPost('bucket_id'));
			$data['is_active'] = $this->input->getPost('opt-active-flag');
			$data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			$return = $this->Parameter_pengajuan_restructure_model->save_restructure_parameter_add($data);
			if($return){
				$cache = session()->get('USER_ID').'_restructure_parameter_list';
				$this->cache->delete($cache);
				$rs = array('success' => true, 'message' => 'Success to save data', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
			
		} else{
			$rs = array('success' => false, 'message' => 'Data ID Already Registered. Please insert another ID.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function restructure_parameter_edit_form(){
		
		$id = $this->input->getGet('id');
		$data['data'] = $this->Common_model->get_record_values(" * ", "cms_restructure_parameter", "restructure_parameter_id='$id'", "");
		$data['kondisi_khusus_list_pl_rsch'] = json_encode($this->Common_model->get_ref_master('value , description item', 'cms_reference', 'reference="KONDISI_KHUSUS_PL_RSCH" and flag="1" and flag_tmp="1" ', 'order_num asc'));
		
		$data['kondisi_khusus_list_pl_rstr'] = json_encode($this->Common_model->get_ref_master('value , description item', 'cms_reference', 'reference="KONDISI_KHUSUS_PL_RSTR" and flag="1" and flag_tmp="1" ', 'order_num asc'));
		
		$data['flag_hirarki'] = $this->Common_model->get_ref_master('value , description item', 'cms_reference', 'reference="HIRARKI_DISKON" and flag="1" and flag_tmp="1" ', 'order_num asc');
		$data['bucket_pl'] = json_encode($this->Common_model->get_ref_master('bucket_id value , bucket_label item', 'cms_bucket', 'is_active="1" ', 'bucket_label asc',false));
		return view('\App\Modules\ParameterPengajuanRestructure\Views\restructure_parameter_edit_form_view', $data);


	}
	function save_restructure_parameter_edit(){
		$data['restructure_parameter_id'] = $this->input->getPost('parameter_id');
		$data["restructure_parameter_detail"]	= stripslashes(urldecode($this->input->getPost("sql")));
		$sep = "\r\n";
		$tmp = "";
		$line = strtok($data["restructure_parameter_detail"], $sep);
		while ($line !== false) {
			if (strpos($line, "IN('") !== false) {
				//hilangkan '
				$tmp2 = str_replace("'", "", $line);
				//tambah ',';
				$tmp2 = str_replace(",", "','", $tmp2);
				$tmp2 = str_replace("(", "('", $tmp2);
				$tmp2 = str_replace(")", "')", $tmp2);
				$tmp .= $tmp2 . "\r\n";
			} else {
				$tmp .= $line . "\r\n";
			}
			$line = strtok($sep);
		}
		$data['restructure_parameter_detail'] = $tmp;
		$data['restructure_parameter_json'] = $this->input->getPost("sql_json");
		$data['restructure_tipe'] = $this->input->getPost('tipe_pengajuan');
		$data['max_discount_rate'] = $this->input->getPost('txt-max-disc-rate');
		$data['max_interest_rate'] = $this->input->getPost('txt-max-interest-rate');
		$data['max_tenor'] = $this->input->getPost('txt-max-tenor');
		$data['ratio_cicilan'] = $this->input->getPost('txt-ratio-cicilan');
		$data['hirarki'] = $this->input->getPost('hirarki_flag');
		$data['desc_kondisi_khusus'] = json_encode($this->input->getPost('desc_kondisi_khusus'));
		$data['bucket_list'] = json_encode($this->input->getPost('bucket_id'));
		$data['is_active'] = $this->input->getPost('opt-active-flag');
		$data['updated_by'] = session()->get('USER_ID');
		$data['updated_time'] = date('Y-m-d H:i:s');
		$return = $this->Parameter_pengajuan_restructure_model->save_restructure_parameter_edit($data);
		// dd($this->input->getPost('campaignid'));
		if($return){
			$cache = session()->get('USER_ID').'_restructure_parameter_list';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to update data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
	}
}