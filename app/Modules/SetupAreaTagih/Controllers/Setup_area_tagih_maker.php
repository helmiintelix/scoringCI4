<?php 
namespace App\Modules\SetupAreaTagih\Controllers;
use App\Modules\SetupAreaTagih\Models\Setup_area_tagih_maker_model;
use App\Modules\SetupAreaTagih\Models\Setup_area_tagih_temp_model;
use CodeIgniter\Cookie\Cookie;

class Setup_area_tagih_maker extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_area_tagih_maker_model = new Setup_area_tagih_maker_model();
		$this->Setup_area_tagih_temp_model = new Setup_area_tagih_temp_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\SetupAreaTagih\Views\Area_tagih_list_view', $data);
	}
	function area_tagih_list(){
		$cache = session()->get('USER_ID').'_area_tagih_list';
		if($this->cache->get($cache)){  
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_area_tagih_maker_model->get_area_tagih_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function area_tagih_add_form() {
		return view('App\Modules\SetupAreaTagih\Views\Area_tagih_add_form_view');
	}
	function save_area_tagih_add(){
		$area_tagih_id = $this->input->getPost('txt-area_tagih-area_tagih_id');
		$is_exist = $this->Setup_area_tagih_maker_model->isExistarea_tagihId($area_tagih_id);
		if (!$is_exist) {
			$data['id'] = uuid();
			$data['area_tagih_id'] = $area_tagih_id;
			$data['area_tagih_name'] = $this->input->getPost('txt-area_tagih-area_tagih_name');
			$data['is_active'] = '1';
			$data['flag'] = '1';
			$data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			$return = $this->Setup_area_tagih_maker_model->save_area_tagih_add($data);
			if ($return) {
				$cache = session()->get('USER_ID').'_area_tagih_list_temp';
				$this->cache->delete($cache);
				$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
		} else {
			$rs = array('success' => false, 'message' => 'AREA TAGIH ID Already Registered. Please insert another ID.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
		
	}
	function area_tagih_edit_form(){
		$id= $this->input->getGet('id');
		$data["data"] = $this->Common_model->get_record_values("*", "cms_area_tagih", "id = '".$id."'");
		return view('App\Modules\SetupAreaTagih\Views\Area_tagih_edit_form_view', $data);
	}
	function save_area_tagih_edit(){
		$data['id'] =  $this->input->getPost('txt-id');
		$data['area_tagih_id'] = $this->input->getPost('txt-area_tagih-area_tagih_id');
		$data['area_tagih_name'] = $this->input->getPost('txt-area_tagih-area_tagih_name');
		$data['is_active'] = '1';
		$data['flag'] = '2';
		$data['created_by'] = session()->get('USER_ID');
		$data['created_time'] = date('Y-m-d H:i:s');
		$return = $this->Setup_area_tagih_maker_model->save_area_tagih_edit($data);
		if ($return) {
			$cache = session()->get('USER_ID').'_area_tagih_list_temp';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
}