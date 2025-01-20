<?php 
namespace App\Modules\SetupAreaBranch\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\SetupAreaBranch\models\Setup_area_branch_model_temp;


class Setup_area_branch_temp extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_area_branch_model_temp = new Setup_area_branch_model_temp();
	}

	function area_branch_temp()
	{
		
		$data['classification'] = $this->input->getPost('classification');
		
		return view('\App\Modules\SetupAreaBranch\Views\Setup_area_branch_view_temp',$data);
	}

	function area_branch_list_temp(){
		$cache = session()->get('USER_ID').'_area_branch_list_temp';

        if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_area_branch_model_temp->get_area_branch_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
		
	}

	function save_area_branch_edit_temp(){
  
        $area_branch_data['id'] = $this->input->getGet('id');
        $area_branch_data['area_id'] = $this->input->getGet('arr_kk');

        $return	= $this->Setup_area_branch_model_temp->save_area_branch_edit_temp($area_branch_data);
        
        if($return){
            $rs = array('success' => true, 'message' => 'Success', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }else{
            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }
	}

    function save_note_reject_area_branch(){
        
		$area_branch_data['id'] = $this->input->getGet('id');
        $area_branch_data['area_id'] = $this->input->getGet('arr_kk');

        $return	= $this->Setup_area_branch_model_temp->save_note_reject_area_branch($area_branch_data);
        
        if($return){
            $rs = array('success' => true, 'message' => 'Success', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }else{
            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }
    }
}
