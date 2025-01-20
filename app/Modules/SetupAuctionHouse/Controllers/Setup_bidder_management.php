<?php 
namespace App\Modules\SetupAuctionHouse\Controllers;
use App\Modules\SetupAuctionHouse\Models\Setup_bidder_management_model;
use CodeIgniter\Cookie\Cookie;



class Setup_bidder_management extends \App\Controllers\BaseController
{

	function __construct()
	{
        $this->Setup_bidder_management_model = new Setup_bidder_management_model();

	}

    function index(){
		$data['classification'] = $this->input->getPost('classification');
        return view('\App\Modules\SetupAuctionHouse\Views\Setup_bidder_management_view', $data);
    }
    function bidder_list(){
        $cache = session()->get('USER_ID').'_bidder_list';
        if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_bidder_management_model->get_bidder_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
    }
    function bidder_form(){
        $data['area'] = array(""=>"--PILIH--") +$this->Common_model->get_record_list("area_id value, area_name AS item", "cms_area_branch", "area_id is not null", "area_name asc");
		$data['cabang'] = array(""=>"--PILIH--") +$this->Common_model->get_record_list("branch_id value, branch_name AS item", "cms_branch", "branch_id is not null", "branch_name asc");
        return view('\App\Modules\SetupAuctionHouse\Views\Setup_bidder_management_add_form_view', $data);
    }
    function save_bidder(){
        $id = strtoupper($this->input->getPost('txt-nama-bidder'));
        $is_exist = $this->Setup_bidder_management_model->isExist($id);
        if (!$is_exist) {
            $data['bidder_id'] = $id;
            $data['name'] = $this->input->getPost('txt-nama-bidder');
            $data['branch_id'] = $this->input->getPost('opt-cabang');
            $data['area_id'] = $this->input->getPost('opt-area');
            $data['address'] = $this->input->getPost('txt-alamat-bidder');
            $data['phone_1'] = $this->input->getPost('txt-phone-1');
            $data['id_card'] = $this->input->getPost('txt-id-card');
            $data['is_active'] = '1';
            $data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			$return = $this->Setup_bidder_management_model->save_bidder($data);
            if ($return) {
                $cache = session()->get('USER_ID').'_bidder_list';
				$this->cache->delete($cache);
				$data = $this->Setup_bidder_management_model->get_bidder_list();
				$this->cache->save($cache, json_encode($data), env('TIMECACHE_1'));
				$rs = array('success' => true, 'message' => 'Success to save data', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
        } else {
            $rs = array('success' => false, 'message' => 'ID Already Registered. Please insert another ID.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
        }
    }
    function bidder_edit_form(){
        $data['bidder_id'] = $this->input->getGet('id');
        $data['data'] = $this->Common_model->get_record_values('bidder_id, name, branch_id, area_id, address, phone_1, id_card, is_active ','cms_bidder','bidder_id="'.$data['bidder_id'].'" ','name');
        $data['area'] = array(""=>"--PILIH--") +$this->Common_model->get_record_list("area_id value, area_name AS item", "cms_area_branch", "area_id is not null", "area_name asc");
		$data['cabang'] = array(""=>"--PILIH--") +$this->Common_model->get_record_list("branch_id value, branch_name AS item", "cms_branch", "branch_id is not null", "branch_name asc");
        return view('\App\Modules\SetupAuctionHouse\Views\Setup_bidder_management_edit_form_view', $data);
    }
    function save_bidder_edit(){
        $data['bidder_id'] = $this->input->getPost('txt-id');
        $data['name'] = $this->input->getPost('txt-nama-bidder');
        $data['branch_id'] = $this->input->getPost('opt-cabang');
        $data['area_id'] = $this->input->getPost('opt-area');
        $data['address'] = $this->input->getPost('txt-alamat-bidder');
        $data['phone_1'] = $this->input->getPost('txt-phone-1');
        $data['id_card'] = $this->input->getPost('txt-id-card');
        $data['is_active'] = $this->input->getPost('opt-active-flag');
        $data['updated_by'] = session()->get('USER_ID');
        $data['updated_time'] = date('Y-m-d H:i:s');
        $return = $this->Setup_bidder_management_model->save_bidder_edit($data);
        if ($return) {
            $cache = session()->get('USER_ID').'_bidder_list';
            $this->cache->delete($cache);
            $data = $this->Setup_bidder_management_model->get_bidder_list();
            $this->cache->save($cache, json_encode($data), env('TIMECACHE_1'));
            $rs = array('success' => true, 'message' => 'Success to update data', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }else{
            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }
    }
    function del_bidder(){
        $data['bidder_id'] = $this->input->getPost('id');
        $is_active = $this->Common_model->get_record_values('is_active', 'cms_bidder', 'bidder_id="'.$data['bidder_id'].'"');
        if ($is_active['is_active'] == "0") {
            $data['is_active'] = "1";
            $response = array('success'=>true, 'message' => 'Data has been activated');
        } else {
            $data['is_active'] = "0";
            $response = array('success'=>true, 'message' => 'Data has been deactivated');
        }
        $return = $this->Setup_bidder_management_model->save_bidder_edit($data);
        if ($return) {
            
            $newCsrfToken = csrf_hash();

            $cache = session()->get('USER_ID').'_bidder_list';
            $this->cache->delete($cache);
            $data = $this->Setup_bidder_management_model->get_bidder_list();
            $this->cache->save($cache, json_encode($data), env('TIMECACHE_1'));
            // return $this->response->setStatusCode(200)->setJSON($response);
            return $this->response->setStatusCode(200)
            ->setJSON(array_merge($response, ['newCsrfToken' => $newCsrfToken]));
        }else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }
    }
}