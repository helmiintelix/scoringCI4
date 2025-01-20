<?php 
namespace App\Modules\FieldcollAndAgencyApprovalReassignment\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\FieldcollAndAgencyApprovalReassignment\Models\Fieldcoll_and_agency_approval_reassignment_model;


class Fieldcoll_and_agency_approval_reassignment extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Fieldcoll_and_agency_approval_reassignment_model = new Fieldcoll_and_agency_approval_reassignment_model;
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\FieldcollAndAgencyApprovalReassignment\Views\Reassignment_list_view_temp', $data);
	}
	function approval_reassignment_temp(){
		$cache = session()->get('USER_ID').'_approval_reassignment_temp';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Fieldcoll_and_agency_approval_reassignment_model->get_approval_reassignment_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function save_app_reassignment_request_temp(){
		$id = $this->input->getGet('id');
		$return = $this->Fieldcoll_and_agency_approval_reassignment_model->save_reassignment_approval($id);
		if($return){
			$cache = session()->get('USER_ID').'_approval_reassignment_temp';
			$cachemaker = session()->get('USER_ID').'_reassignment_list';

			$this->cache->delete($cache);
			$this->cache->delete($cachemaker);

			$rs = array('success' => true, 'message' => 'Success to approve data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function save_note_reject_reassignment(){
		$id = $this->input->getGet('id');
		$return = $this->Fieldcoll_and_agency_approval_reassignment_model->reject_reassignment($id);
		if($return){
			$cache = session()->get('USER_ID').'_approval_reassignment_temp';
			$this->cache->delete($cache);
			
			$rs = array('success' => true, 'message' => 'Success to reject data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}

	}

}