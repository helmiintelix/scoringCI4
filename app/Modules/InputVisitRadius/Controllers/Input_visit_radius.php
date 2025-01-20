<?php 
namespace App\Modules\InputVisitRadius\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\InputVisitRadius\Models\Input_visit_radius_model;


class Input_visit_radius extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Input_visit_radius_model = new Input_visit_radius_model();
	}

	function visit_radius_all()
	{
		
		$data['classification'] = $this->input->getPost('classification');
		
		return view('\App\Modules\InputVisitRadius\Views\input_visit_radius_view',$data);
	}

	function visit_radius_all_list()
	{
		
		$cache = session()->get('USER_ID').'_visit_radius_all_list';

		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Input_visit_radius_model->get_visit_radius_all_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function visit_radius_all_add_form(){
		return view('\App\Modules\InputVisitRadius\Views\Input_visit_radius_add_form_view');
	}

	function visit_radius_all_edit_form()
	{
		$data["id"] = $this->input->getGet('id');
		$data["data"] = $this->Common_model->get_record_values("id, radius, is_active", "cms_visit_radius_all", "id = '" . $this->db->escapeString($data["id"]) . "'");

		if(empty($data["data"])){
			echo "NOT FOUND";
		}else{
			return view('\App\Modules\InputVisitRadius\Views\Input_visit_radius_edit_form_view',$data);
		}
		
	}

	function save_visit_radius_all_add(){
		
		$is_exist = $this->Input_visit_radius_model->isExist($this->input->getPost('txt-id'));
	
		if(!$is_exist){
			$data['id'] = $this->input->getPost('txt-id');
			$data['is_active'] = $this->input->getPost('opt-active-flag');
			$data['radius'] = $this->input->getPost('txt-radius');
			$data["created_by"]	= session()->get('USER_ID');
			$data["created_date"]	= date('Y-m-d H:i:s');
			
			$return	= $this->Input_visit_radius_model->save_visit_radius_all_add($data);
			
			if($return){
				$rs = array('success' => true, 'message' => 'Success', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}

		}else{
			$rs = array('success' => false, 'message' => 'ID Already Registered. Please insert another ID.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

	function save_visit_radius_all_edit(){
		
		$is_exist = $this->Input_visit_radius_model->isExist($this->input->getPost('txt-user-id-temp'));
	
		if(!$is_exist){
			$data['id'] = $this->input->getPost('id_edit');
			$data['is_active'] = $this->input->getPost('opt-active-flag');
			$data['radius'] = $this->input->getPost('txt-radius');
			$data["created_by"]	= session()->get('USER_ID');
			$data["created_date"]	= date('Y-m-d H:i:s');

			$return	= $this->Input_visit_radius_model->save_visit_radius_all_update($data);
			
			if($return){
				$rs = array('success' => true, 'message' => 'Success', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}

		}else{
			$rs = array('success' => false, 'message' => 'Your Request is being EDIT process.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

}
