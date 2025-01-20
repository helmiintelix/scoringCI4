<?php 
namespace App\Modules\SetUpBranch\Controllers;
// namespace App\Modules\VisitRadius\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\SetUpBranch\Models\Set_up_branch_temp_model;


class Set_up_branch_temp extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Set_up_branch_temp_model = new Set_up_branch_temp_model;
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\SetUpBranch\Views\Setup_branch_temp_view', $data);
	}
	function branch_list_temp(){
		$cache = session()->get('USER_ID').'_branch_list_temp';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Set_up_branch_temp_model->get_branch_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function save_branch_edit_temp(){
		$id = $this->input->getGet('id');
		$return = $this->Set_up_branch_temp_model->save_branch_edit_temp($id);
		if($return){
			$cache = session()->get('USER_ID').'_branch_list_temp';
			$cachemaker = session()->get('USER_ID').'_branch_list';

			$this->cache->delete($cache);
			$this->cache->delete($cachemaker);


			// Update cache dengan data terbaru
			$data = $this->Set_up_branch_temp_model->get_branch_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1'));
			
			$rs = array('success' => true, 'message' => 'Success to approve data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function save_note_reject_branch(){
		$id = $this->input->getGet('id');
		$return = $this->Set_up_branch_temp_model->reject_branch($id);
		if($return){
			$cache = session()->get('USER_ID').'_branch_list_temp';

			$this->cache->delete($cache);
			$data = $this->Set_up_branch_temp_model->get_branch_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1'));
			
			$rs = array('success' => true, 'message' => 'Success to reject data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}

	}

}