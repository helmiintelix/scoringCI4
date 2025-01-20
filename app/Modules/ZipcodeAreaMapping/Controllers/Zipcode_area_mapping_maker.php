<?php 
namespace App\Modules\ZipcodeAreaMapping\Controllers;
use App\Modules\ZipcodeAreaMapping\Models\Zipcode_area_mapping_maker_model;
use CodeIgniter\Cookie\Cookie;

class Zipcode_area_mapping_maker extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Zipcode_area_mapping_maker_model = new Zipcode_area_mapping_maker_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\ZipcodeAreaMapping\Views\Zipcode_area_mapping_list_view', $data);
	}
	function zipcode_area_mapping_list(){
		$cache = session()->get('USER_ID').'_zipcode_area_mapping_list';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Zipcode_area_mapping_maker_model->get_zipcode_area_mapping_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function zipcode_area_mapping_add_form() {
		$builder = $this->db->table('acs_cms_product');
		$builder->select('productcode');
		$data['product'] = $builder->get()->getResultArray();
		$data['branch'] = array(""=>"SELECT BRANCH AREA TAGIH")+  $this->Common_model->get_record_list("area_tagih_name AS value, area_tagih_name AS item", "cms_area_tagih", "is_active=1", "area_tagih_id");
		return view('App\Modules\ZipcodeAreaMapping\Views\Zipcode_area_mapping_add_form_view', $data);
	}
	function save_zipcode_area_mapping_add(){
		$is_exist = $this->Zipcode_area_mapping_maker_model->isExistzipcode_area_mappingId($this->input->getPost('txt-zipcode_area_mapping-sub_area_id'));
		print_r($is_exist);
		if (!$is_exist) {
			$product=$this->input->getPost("opt-zipcode_area_mapping-product");
			$data["id"] = UUID();
			$data["sub_area_id"] = $this->input->getPost("txt-zipcode_area_mapping-sub_area_id");
			$data["sub_area_name"] = $this->input->getPost("txt-zipcode_area_mapping-sub_area_name");
			$data["area_tagih"] = $this->input->getPost("opt-zipcode_area_mapping-area_tagih");
			if($product == null){
				$data["product"] = '';
			}
			else 
			{
				$data["product"] = implode(",",$this->input->getPost("opt-zipcode_area_mapping-product"));
			}
			$data["zip_code"] = $this->input->getPost("txt-zipcode_area_mapping-zip_code");
			$data['is_active'] = '1';
			$data['flag'] = '1';
			$data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			$builder = $this->db->table('cms_zipcode_area_mapping');
			$builder->select('*');
			$builder->where('zip_code', $data['zip_code']);
			$result = $builder->get()->getResultArray();
			if (count($result) != 0) {
				$rs = array("success"=>false,"message"=>"Zipcode Already Assigned. Please insert another ZIPCODE.");
				return $this->response->setStatusCode(200)->setJSON($rs);
			} else {
				$return = $this->Zipcode_area_mapping_maker_model->save_zipcode_area_mapping_add($data);
				if ($return) {
					$this->Common_model->data_logging('zipcode_area_mapping', $data["sub_area_name"], 'SUCCESS', 'Set '.$data["sub_area_id"].' = '.$data["sub_area_name"]);
					$cache = session()->get('USER_ID').'_zipcode_area_mapping_list_temp';
					$this->cache->delete($cache);
					$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
					return $this->response->setStatusCode(200)->setJSON($rs);
				}else{
					$this->Common_model->data_logging('zipcode_area_mapping', $data["sub_area_name"], 'FAILED', 'Set '.$data["sub_area_id"].' = '.$data["sub_area_name"]);
					$rs = array('success' => false, 'message' => 'failed', 'data' => null);
					return $this->response->setStatusCode(200)->setJSON($rs);
				}
			}
		} else {
			$rs = array('success' => false, 'message' => 'zipcode_area_mapping ID Already Registered. Please insert another ID.', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
	}
	function zipcode_area_mappingEditForm(){
		$id= $this->input->getGet('id');
		$data["zipcode_area_mapping_data"] = $this->Common_model->get_record_values("*", "cms_zipcode_area_mapping", "id = '".$id."'");
		$builder = $this->db->table('cms_zipcode_area_mapping');
		$builder->select('area_tagih');
		$builder->where('id', $id);
		$data["area_tagih_existing"] = $builder->get()->getResultArray();

		$builder = $this->db->table('cms_area_tagih');
		$builder->select('area_tagih_name');
		$builder->where('is_active', '1');
		$builder->orderBy('area_tagih_id');
		$data["area_tagih"] = $builder->get()->getResultArray();

		$dataProduct=$this->Common_model->get_record_values("product", "cms_zipcode_area_mapping", "id='".$id."'", "");
		$data['product_existing']=explode(',',$dataProduct['product']);

		$builder = $this->db->table('acs_cms_product');
		$builder->select('productcode');
		$data['product'] = $builder->get()->getResultArray();
		return view('App\Modules\ZipcodeAreaMapping\Views\Zipcode_area_mapping_edit_form_view', $data);
	}
	function save_zipcode_area_mapping_edit(){
		$product=$this->input->getPost("opt-zipcode_area_mapping-product");
		$data["id"] = $this->input->getPost('txt-id');
		$data["sub_area_id"] = $this->input->getPost("txt-zipcode_area_mapping-sub_area_id");
		$data["sub_area_name"] = $this->input->getPost("txt-zipcode_area_mapping-sub_area_name");
		$data["area_tagih"] = $this->input->getPost("opt-zipcode_area_mapping-area_tagih");
		if($product == null){
			$data["product"] = '';
		}
		else 
		{
			$data["product"] = implode(",",$this->input->getPost("opt-zipcode_area_mapping-product"));
		}
		$data["zip_code"] = $this->input->getPost("txt-zipcode_area_mapping-zip_code");
		$data['is_active'] = '1';
		$data['flag'] = '2';
		$data['created_by'] = session()->get('USER_ID');
		$data['created_time'] = date('Y-m-d H:i:s');
		$return = $this->Zipcode_area_mapping_maker_model->save_zipcode_area_mapping_edit($data);
		if ($return) {
			$cache = session()->get('USER_ID').'_zipcode_area_mapping_list_temp';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

}