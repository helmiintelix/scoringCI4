<?php 
namespace App\Modules\UnassignedZipcode\Controllers;
use App\Modules\UnassignedZipcode\Models\Unassigned_zipcode_model;
use CodeIgniter\Cookie\Cookie;

class Unassigned_zipcode extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Unassigned_zipcode_model = new Unassigned_zipcode_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\UnassignedZipcode\Views\Unassigned_zipcode', $data);
	}
	function unassigned_zipcode_mapping_list(){
		$cache = session()->get('USER_ID').'_unassigned_zipcode_mapping_list';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Unassigned_zipcode_model->get_unassigned_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function zipcode_assign_mappingAddForm() {
		$data['id'] = $this->input->getGet('id');
		$builder = $this->db->table('acs_cms_product');
		$builder->select('productcode');
		$data['product'] = $builder->get()->getResultArray();
		$data['branch_area_tagih'] = $this->Common_model->get_record_list("area_tagih_name AS value, area_tagih_name AS item", "cms_area_tagih", "is_active=1", "area_tagih_id");
		return view('App\Modules\UnassignedZipcode\Views\Assign_area_add_view', $data);
	}
	function save_zipcode_area_mapping_assign(){
		$is_exist = $this->Unassigned_zipcode_model->isExistzipcode_area_mappingId($this->input->getPost('txt-zipcode_area_assign-sub_area_id'));
		print_r($is_exist);
		if (!$is_exist) {
			$product=$this->input->getPost("opt-zipcode_area_assign-product")==null?'':implode(",",$this->input->getPost("opt-zipcode_area_assign-product"));
			$data["id"] = UUID();
			$data["sub_area_id"] = $this->input->getPost("txt-zipcode_area_assign-sub_area_id");
			$data["sub_area_name"] = $this->input->getPost("txt-zipcode_area_assign-sub_area_name");
			$data["area_tagih"] = $this->input->getPost("opt-zipcode_area_assign-area_tagih");
			$data['product'] = $product;
			$data["zip_code"] = $this->input->getPost("txt-id");
			$data['is_active'] = '1';
			$data['flag'] = '1';
			$data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			$return = $this->Unassigned_zipcode_model->save_zipcode_area_mapping_assign($data);
			if ($return) {
				$this->Common_model->data_logging('zipcode_area_mapping', $data["sub_area_name"], 'SUCCESS', 'Set '.$data["sub_area_id"].' = '.$data["sub_area_name"]);
				$cache = session()->get('USER_ID').'_unassigned_zipcode_mapping_list';
				$this->cache->delete($cache);
				$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$this->Common_model->data_logging('zipcode_area_mapping', $data["sub_area_name"], 'FAILED', 'Set '.$data["sub_area_id"].' = '.$data["sub_area_name"]);
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
		} else {
			$rs = array('success' => false, 'message' => 'zipcode_area_mapping ID Already Registered. Please insert another ID.', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
	}

}