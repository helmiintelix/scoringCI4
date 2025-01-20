<?php 
namespace App\Modules\Bucket\Controllers;
// namespace App\Modules\VisitRadius\Controllers;
use App\Modules\Bucket\Models\Bucket_maker_model;
use App\Modules\Bucket\Models\Bucket_temp_model;
use CodeIgniter\Cookie\Cookie;

class Bucket_maker extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Bucket_maker_model = new Bucket_maker_model();
		$this->Bucket_temp_model = new Bucket_temp_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\Bucket\Views\Bucket_maker_view', $data);
	}
	function bucket_list(){
		$cache = session()->get('USER_ID').'_bucket_list';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Bucket_maker_model->get_bucket_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function bucket_add_form() {
		$data["list_of_bucket"] = $this->Common_model->get_record_list("value, description AS item", "cms_reference", "reference='BUCKET' and value not in(select bucket_id from cms_bucket_detail)", "description");
		$builder = $this->db->table('acs_cms_product');
		$builder->select('productsubcategory');
		$data['product'] = $builder->get()->getResultArray();
		return view('App\Modules\Bucket\Views\Bucket_add_form_view', $data);
	}
	function save_bucket_add(){
		$data['bucket_id'] = $this->input->getPost('bucket_id');
		$data['bucket_label'] = $this->input->getPost('bucket_label');
		$data['product'] = implode(',', $this->input->getPost('product'));
		$data['dpd_from'] = $this->input->getPost('dpd_from');
		$data['dpd_until'] = $this->input->getPost('dpd_until');
		// $data['flag_wo_co'] = $this->input->getPost('txt-holiday-name');
		$data['amount_to_be_paid_to_keep_promise'] = $this->input->getPost('amount_to_be_paid_to_keep_promise');
		$data['any_amount_keep_promise'] = $this->input->getPost('any_amount_keep_promise');
		$data['min_amount_acceptable_promise'] = $this->input->getPost('min_amount_acceptable_promise');
		$data['any_amount_acceptable_promise'] = $this->input->getPost('any_amount_acceptable_promise');
		$data['ptp_grace_period'] = $this->input->getPost('ptp_grace_period');
		$data['include_holiday'] = $this->input->getPost('include_holiday');
		$data['join_program'] = $this->input->getPost('join_program');
		$data['is_active'] = '1';
		$data['flag'] = '1';
		$data['created_by'] = session()->get('USER_ID');
		$data['created_time'] = date('Y-m-d H:i:s');
		// $data['flag'] = $this->input->getPost('txt-id');
		$return = $this->Bucket_maker_model->save_bucket_add($data);
		if ($return) {
			$cache = session()->get('USER_ID').'_bucket_list_temp';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function bucket_edit_form(){
		$id= $this->input->getGet('id');
		$data["data"] = $this->Common_model->get_record_values("*", "cms_bucket", "bucket_id = '".$id."'");
		$dataProduct=$this->Common_model->get_record_values("product", "cms_bucket", "bucket_id='".$id."'", "");
		$data['dataProduct']=explode(',',$dataProduct['product']);
		$builder = $this->db->table('acs_cms_product');
		$builder->select('productsubcategory');
		$data['product'] = $builder->get()->getResultArray();
		return view('App\Modules\Bucket\Views\Bucket_edit_form_view', $data);
	}
	function save_bucket_edit(){
		$is_exist = $this->Bucket_maker_model->isExistbucketId($this->input->getPost('bucket_id'));
		if (!$is_exist) {
			$data['bucket_id'] = $this->input->getPost('bucket_id');
			$data['bucket_label'] = $this->input->getPost('bucket_label');
			$data['product'] = implode(',', $this->input->getPost('product'));
			$data['dpd_from'] = $this->input->getPost('dpd_from');
			$data['dpd_until'] = $this->input->getPost('dpd_until');
			// $data['flag_wo_co'] = $this->input->getPost('txt-holiday-name');
			$data['amount_to_be_paid_to_keep_promise'] = $this->input->getPost('amount_to_be_paid_to_keep_promise');
			$data['any_amount_keep_promise'] = $this->input->getPost('any_amount_keep_promise');
			$data['min_amount_acceptable_promise'] = $this->input->getPost('min_amount_acceptable_promise');
			$data['any_amount_acceptable_promise'] = $this->input->getPost('any_amount_acceptable_promise');
			$data['ptp_grace_period'] = $this->input->getPost('ptp_grace_period');
			$data['include_holiday'] = $this->input->getPost('include_holiday');
			$data['join_program'] = $this->input->getPost('join_program');
			$data['is_active'] = '1';
			$data['flag'] = '2';
			$data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			// $data['flag'] = $this->input->getPost('txt-id');
			$return = $this->Bucket_maker_model->save_bucket_add($data);
			if ($return) {
				$cache = session()->get('USER_ID').'_bucket_list_temp';
				$this->cache->delete($cache);
				$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
		} else {
			$rs = array('success' => false, 'message' => 'Please approve/reject the data first', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

}