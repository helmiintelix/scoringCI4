<?php 
namespace App\Modules\SetupAuctionHouse\Controllers;
use App\Modules\SetupAuctionHouse\Models\Master_auction_house_model;
use CodeIgniter\Cookie\Cookie;



class Master_auction_house extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Master_auction_house_model = new Master_auction_house_model();

	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\SetupAuctionHouse\Views\Master_auction_house_view', $data);
	}
    function balai_lelang_list(){
        $cache = session()->get('USER_ID').'_balai_lelang_list';
        if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Master_auction_house_model->get_balai_lelang_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
    }
	function balai_lelang_add_form(){
        return view('\App\Modules\SetupAuctionHouse\Views\Master_auction_house_add_form_view');
    }
    function save_balai_lelang(){
        $id = strtoupper($this->input->getPost('txt-nama-balai'));
        $is_exist = $this->Master_auction_house_model->isExist($id);
        if (!$is_exist) {
            $data['balai_id'] = $id;
            $data['name'] = $this->input->getPost('txt-nama-balai');
            $data['address'] = $this->input->getPost('txt-alamat');
            $data['is_active'] = '1';
            $data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			$return = $this->Master_auction_house_model->save_balai_lelang($data);
            if ($return) {
                $cache = session()->get('USER_ID').'_balai_lelang_list';
				$this->cache->delete($cache);
				$data = $this->Master_auction_house_model->get_balai_lelang_list();
				$this->cache->save($cache, json_encode($data), env('TIMECACHE_1'));
				$rs = array('success' => true, 'message' => 'Success to save data', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
        } else {
            $rs = array('success' => false, 'message' => 'User ID Already Registered. Please insert another ID.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
        }
    }
    function balai_lelang_edit_form(){
        $data['balai_id'] = $this->input->getGet('id');
        $data['data'] = $this->Common_model->get_record_values("*", "cms_balai_lelang", "balai_id = '" . $data["balai_id"] . "'");
        $data['is_active'] = $data['data']['is_active'];
        return view('\App\Modules\SetupAuctionHouse\Views\Master_auction_house_edit_form_view', $data);
    }
    function save_edit_balai_lelang(){
        $data['balai_id'] = $this->input->getPost('txt-balai-id');
        $data['name'] = $this->input->getPost('txt-nama-balai');
        $data['address'] = $this->input->getPost('txt-alamat');
        $data['is_active'] = $this->input->getPost('opt-active-flag');
        $data['updated_by'] = session()->get('USER_ID');
        $data['updated_time'] = date('Y-m-d H:i:s');
        $return = $this->Master_auction_house_model->edit_balai_lelang($data);
        if ($return) {
            $cache = session()->get('USER_ID').'_balai_lelang_list';
            $this->cache->delete($cache);
            $data = $this->Master_auction_house_model->get_balai_lelang_list();
            $this->cache->save($cache, json_encode($data), env('TIMECACHE_1'));
            $rs = array('success' => true, 'message' => 'Success to update data', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }else{
            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }
    }
    function del_mBalai(){
        $data['balai_id'] = $this->input->getPost('id');
        $is_active = $this->Common_model->get_record_values('is_active', 'cms_balai_lelang', 'balai_id="'.$data['balai_id'].'"');
        if ($is_active['is_active'] == "0") {
            $data['is_active'] = "1";
            $response = array('success'=>true, 'message' => 'Data has been activated');
        } else {
            $data['is_active'] = "0";
            $response = array('success'=>true, 'message' => 'Data has been deactivated');
        }
        $return = $this->Master_auction_house_model->edit_balai_lelang($data);
        if ($return) {
            
            $newCsrfToken = csrf_hash();

            $cache = session()->get('USER_ID').'_balai_lelang_list';
            $this->cache->delete($cache);
            $data = $this->Master_auction_house_model->get_balai_lelang_list();
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