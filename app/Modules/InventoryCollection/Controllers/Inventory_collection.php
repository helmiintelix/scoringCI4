<?php 
namespace App\Modules\InventoryCollection\Controllers;
use App\Modules\InventoryCollection\Models\Inventory_collection_model;
use CodeIgniter\Cookie\Cookie;


class Inventory_collection extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Inventory_collection_model = new Inventory_collection_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\InventoryCollection\Views\Collection_list_view', $data);
	}
	function get_collection_list(){
		$cache = session()->get('USER_ID').'_collection_list';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Inventory_collection_model->get_collection_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function customer_address(){
		$data['customer_id'] = $this->input->getGet("customer_id");
		$data['account_id'] = $this->input->getGet("account_id");
		// dd($data);
		$builder = $this->db->table('cpcrd_new a');
        $builder->select('CM_CURR_ADDR,CM_ADDR_LN1,CM_ADDR_LN2,CM_ADDR_LN3,CM_SUB_DSTR,CM_DSTR');
        $builder->join('cpcrd_ext b', 'a.cm_customer_nmbr = b.cm_cust_nmbr', 'inner');
        $builder->where('a.cm_customer_nmbr', $data['account_id']);
        $data["address"] = $builder->get()->getResultArray();

		$data["address_curr"] = $this->Common_model->get_record_values("CM_CURR_ADDR,
		CM_CURR_SUBDIST,
		CM_CURR_DISTRICT,
		CM_CURR_CITY,
		CM_CURR_PROVINCE,
		CM_CURR_ZIPCODE,
		CM_HOME_ADDR,
		CM_HOME_PROVINCE,
		CM_HOME_CITY,
		CM_HOME_SUBDIST,
		CM_HOME_DISTRICT,
		CM_HOME_ZIPCODE,
		CM_ID_ZIPCODE,
		CM_ID_PROVINCE,
		CM_ID_CITY,
		CM_ID_DISTRICT,
		CM_ID_SUBDIST,
		CM_ID_ADDR,
		CR_GUARANTOR_ZIPCODE,
		CR_GUARANTOR_PROVINCE,
		CR_GUARANTOR_CITY,
		CR_GUARANTOR_DISTRICT,
		CR_GUARANTOR_SUB_DISTRICT,
		CR_GUARANTOR_LINE1,
		CR_EC_LINE1,
		CR_EC_PROVINCE,
		CR_EC_CITY,
		CR_EC_SUB_DISTRICT,
		CR_EC_DISTRICT,
		CR_EC_ZIPCODE,
		CM_COMPANY_PROVINCE,
		CR_COMPANY_CITY,
		CR_COMPANY_SUB_DISTRICT,
		CR_COMPANY_DISTRICT,
		CR_COMPANY_ZIP_CODE",
		"cpcrd_ext","cm_cust_nmbr='".$data['customer_id']."' ");
		return view('\App\Modules\InventoryCollection\Views\Customer_address_list_view', $data);
	}

	function customer_mail(){
		$data['customer_id'] = $this->input->getGet("customer_id");
		$data['account_id'] = $this->input->getGet("account_id");

		$builder = $this->db->table('cpcrd_ext_mailphone');
        $builder->select('contact_type,email_phone,mailphone');
        $builder->where('cm_cust_nmbr', $data['customer_id']);
        $builder->where('email_phone', 'EMAIL');
        $data["address"] = $builder->get()->getResultArray();
		return view('\App\Modules\InventoryCollection\Views\Customer_email_list_view', $data);

	}

	function customer_phone(){
		$data['customer_id'] = $this->input->getGet("customer_id");
		$data['account_id'] = $this->input->getGet("account_id");

		$builder = $this->db->table('cpcrd_new a');
        $builder->select(
			'CR_HANDPHONE,
			CR_HANDPHONE2,
			CR_OFFICE_PHONE,
			CR_CO_OFFICE_PHONE,
			CR_GUARANTOR_PHONE,
			CR_EC_PHONE,
			CM_SPOUSE_PHONE'
		);
        $builder->where('CM_CARD_NMBR', $data['account_id']);
        $res = $builder->get()->getResultArray();

		$output = array();
		$i=0;
		foreach ($res[0] as $key => $value) {
			
			$output[$i]['phone'] = $value;

			
			if($key=='CR_HANDPHONE'){
				$output[$i]['type'] = 'hp1';
			}
			else if($key=='CR_HANDPHONE2'){
				$output[$i]['type'] = 'hp2';
			}
			else if($key=='CR_OFFICE_PHONE'){
				$output[$i]['type'] = 'Of1';
			}
			else if($key=='CR_CO_OFFICE_PHONE'){
				$output[$i]['type'] = 'Of2';
			}
			else if($key=='CR_GUARANTOR_PHONE'){
				$output[$i]['type'] = 'guarantor';
			}
			else if($key=='CR_EC_PHONE'){
				$output[$i]['type'] = 'emergency';
			}
			else if($key=='CM_SPOUSE_PHONE'){
				$output[$i]['type'] = 'spouse';
			}
			else{
				$output[$i]['type'] = 'other';
			}

			$builder = $this->db->table('cms_predictive_phone');
			$builder->select('created_time, priority');
			$builder->where('cm_card_nmbr', $data['account_id']);
			$builder->where('content', $value);
			$builder->where('created_time is not null');
			$builder->groupBy('cm_card_nmbr');
			$resx = $builder->get()->getResultArray();
			
			foreach ($resx as $keyx => $valuex) {
				$output[$i]['priority'] = $valuex['priority'];
				$output[$i]['best_time'] = $valuex['created_time'];
			}
			$i++;
		}
		$data["address"]= $output;
		return view('\App\Modules\InventoryCollection\Views\Customer_phone_list_view', $data);

	}
}