<?php 
namespace App\Modules\SetupAccountTagging\Controllers;
use App\Modules\SetupAccountTagging\Models\Setup_account_tagging_maker_model;
use App\Modules\SetupAccountTagging\Models\Setup_account_tagging_temp_model;
use CodeIgniter\Cookie\Cookie;

class Setup_account_tagging_maker extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_account_tagging_maker_model = new Setup_account_tagging_maker_model();
		$this->Setup_account_tagging_temp_model = new Setup_account_tagging_temp_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		$data['account_tagging'] = $this->Common_model->get_record_list("id as value, description AS item", "cms_phonetag_ref", "type = 'ACCOUNT TAGGING' and status='1'", "description");
		return view('\App\Modules\SetupAccountTagging\Views\Setup_account_tagging_list_view', $data);
	}
	function get_collection_list(){
		$cache = session()->get('USER_ID').'_collection_list';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_account_tagging_maker_model->get_collection_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function set_account_tagging(){
		$data['account'] = $this->input->getGet('account_no');
		$data['mode'] = $this->input->getGet('mode');
		$data['account_tagging'] = $this->input->getGet('account_tagging');
		
		$return = $this->Setup_account_tagging_maker_model->set_account_tagging($data);
		// print_r($return);
		// exit();
		if ($return) {
			$cache = session()->get('USER_ID') . '_collection_list';
			$this->cache->delete($cache);
			$cache2 = session()->get('USER_ID') . '_collection_list_temp';
			$this->cache->delete($cache2);
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