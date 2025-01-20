<?php 
namespace App\Modules\SetupDeviationReference\Controllers;
use App\Modules\SetupDeviationReference\Models\Setup_deviation_reference_maker_model;
use App\Modules\SetupDeviationReference\Models\Setup_deviation_reference_temp_model;
use CodeIgniter\Cookie\Cookie;

class Setup_deviation_reference_maker extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_deviation_reference_maker_model = new Setup_deviation_reference_maker_model();
		$this->Setup_deviation_reference_temp_model = new Setup_deviation_reference_temp_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\SetupDeviationReference\Views\Deviation_reference_list_view', $data);
	}
	function deviation_reference_list(){
		$cache = session()->get('USER_ID').'_deviation_reference_list';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_deviation_reference_maker_model->get_deviation_reference_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function deviation_reference_add_form() {
		$builder = $this->db->table('acs_cms_product');
        $builder->select('*');
        $res = $builder->get()->getResultArray();
		$opt = array();
		foreach ($res as $key => $value) {
			$opt[$value['ProductCode']] = $value['ProductName'];

		}
		$data['product'] = $opt;
		return view('App\Modules\SetupDeviationReference\Views\Deviation_reference_add_form_view', $data);
	}
	function save_deviation_reference_add(){
		$deviation_id = $this->input->getPost('txt-deviation_reference-deviation_id');
		$is_exist = $this->Setup_deviation_reference_maker_model->isExistdeviation_referenceId($deviation_id);
		if (!$is_exist) {
			$product = $this->input->getPost("opt-deviation_reference-product") == null ? '' : implode(",", $this->input->getPost("opt-deviation_reference-product"));
			$type = $this->input->getPost("opt-deviation_reference-type") == null ? '' : implode(",", $this->input->getPost("opt-deviation_reference-type"));
			$data["id"] = UUID();
			$data["deviation_id"] = $deviation_id;
			$data["deviation_name"] = $this->input->getPost("txt-deviation_reference-deviation_name");
			$data["product"] = $product;
			$data["type"] = $type;
			$data["is_active"]	= $this->input->getPost("opt-active-flag");
			$data['flag'] = '1';
			$data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			$return = $this->Setup_deviation_reference_maker_model->save_deviation_reference_add($data);
			if ($return) {
				$this->Common_model->data_logging('DEVIATION REFERENCE', $data["deviation_name"], 'SUCCESS', 'Set ' . $data["deviation_id"] . ' = ' . $data["deviation_name"]);
				$cache = session()->get('USER_ID').'_deviation_reference_list_temp';
				$this->cache->delete($cache);
				$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$this->Common_model->data_logging('DEVIATION REFERENCE', $data["deviation_name"], 'FAILED', 'Set ' . $data["deviation_id"] . ' = ' . $data["deviation_name"]);
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
		} else {
			$rs = array('success' => false, 'message' => 'DEVIATION REFERENCE ID Already Registered. Please insert another ID.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
		
	}
	function deviation_referenceEditForm(){
		$id= $this->input->getGet('id');
		$data['data'] = $this->Common_model->get_record_values("*", "cms_deviation_reference", "id = '" . $id. "'");

		$dataEdit = $this->Common_model->get_record_values("product,type,is_active", "cms_deviation_reference", "id='" . $id . "'", "product");
		$data['product'] = explode(',', $dataEdit['product']);
		$data['type'] = explode(',', $dataEdit['type']);
		$builder = $this->db->table('acs_cms_product');
        $builder->select('*');
        $res = $builder->get()->getResultArray();
		$opt = array();
		foreach ($res as $key => $value) {
			$opt[$value['ProductCode']] = $value['ProductName'];

		}
		$data['product'] = $opt;

		return view('App\Modules\SetupDeviationReference\Views\Deviation_reference_edit_form_view', $data);
	}
	function save_deviation_reference_edit(){
		$product = $this->input->getPost("opt-deviation_reference-product") == null ? '' : implode(",", $this->input->getPost("opt-deviation_reference-product"));
		$type = $this->input->getPost("opt-deviation_reference-type") == null ? '' : implode(",", $this->input->getPost("opt-deviation_reference-type"));
		$data["id"] = $this->input->getPost('txt-id');
		$data["deviation_id"] = $this->input->getPost('txt-deviation_reference-deviation_id');
		$data["deviation_name"] = $this->input->getPost("txt-deviation_reference-deviation_name");
		$data["product"] = $product;
		$data["type"] = $type;
		$data["is_active"]	= $this->input->getPost("opt-active-flag");
		$data['flag'] = '2';
		$data['created_by'] = session()->get('USER_ID');
		$data['created_time'] = date('Y-m-d H:i:s');
		$return = $this->Setup_deviation_reference_maker_model->save_deviation_reference_edit($data);
		if ($return) {
			$cache = session()->get('USER_ID').'_deviation_reference_list_temp';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function delete_deviation_reference(){
		$id= $this->input->getPost('id');
		$return = $this->Setup_deviation_reference_maker_model->delete_deviation_reference($id);
		if ($return) {
			$newCsrfToken = csrf_hash();
			$cache = session()->get('USER_ID').'_deviation_reference_list';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to delete data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON(array_merge($rs, ['newCsrfToken' => $newCsrfToken]));
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	
}