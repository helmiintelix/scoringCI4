<?php 
namespace App\Modules\SetupAuctionHouse\Controllers;
use App\Modules\SetupAuctionHouse\Models\Event_auction_house_model;
use CodeIgniter\Cookie\Cookie;



class Event_auction_house extends \App\Controllers\BaseController
{

	function __construct()
	{
        $this->Event_auction_house_model = new Event_auction_house_model();

	}
    //SUB MENU EVENT AUCTION HOUSE
    function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\SetupAuctionHouse\Views\Event_auction_house_view', $data);
	}
    function get_event_balai_lelang_list(){
        $cache = session()->get('USER_ID').'_event_balai_lelang_list';
        if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Event_auction_house_model->get_event_balai_lelang_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
    }

    function event_balai_lelang_form(){
        $data['master'] = array(""=>"-PILIH-") + $this->Common_model->get_record_list("balai_id value, name AS item", "cms_balai_lelang", "is_active='1'", "name");
        $data['data'] = array('is_active'=>'1','balai_id'=>'');
        return view('\App\Modules\SetupAuctionHouse\Views\Event_auction_house_add_form_view', $data);
    }
    function save_event_balai_lelang(){
        $id = strtoupper($this->input->getPost('txt-nama-event'));
        $is_exist = $this->Event_auction_house_model->isExist($id);
        if (!$is_exist) {
            $data['id'] = $id;
            $data['balai_id'] = $this->input->getPost('opt-balai-lelang');
            $data['name'] = $this->input->getPost('txt-nama-event');
            $data['description'] = $this->input->getPost('description-event');
            $data['event_date'] = $this->input->getPost('txt-tanggal-event');
            $data['location'] = $this->input->getPost('txt-location-event');
            $data['is_active'] = '1';
            $data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			$return = $this->Event_auction_house_model->save_event_balai_lelang($data);
            if ($return) {
                $cache = session()->get('USER_ID').'_event_balai_lelang_list';
				$this->cache->delete($cache);
				$data = $this->Event_auction_house_model->get_event_balai_lelang_list();
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
    function event_balai_lelang_edit_form(){
        $data['id'] = $this->input->getGet('id');
        $data['master'] = array(""=>"-PILIH-") + $this->Common_model->get_record_list("balai_id value, name AS item", "cms_balai_lelang", "is_active='1'", "name");
        // $data['data'] = $this->Common_model->get_record_values("*", "cms_balai_lelang_event", "id = '" . $data["id"] . "'");
        $data['data'] = $this->Common_model->get_record_values('id, balai_id, name, event_date, is_active, location, description ','cms_balai_lelang_event','id="'.$data['id'].'" ','name');
        $data['is_active'] = $data['data']['is_active'];
        return view('\App\Modules\SetupAuctionHouse\Views\Event_auction_house_edit_form_view', $data);
    }
    function save_edit_event_balai_lelang(){
        $data['id'] = $this->input->getPost('txt-id');
        $data['balai_id'] = $this->input->getPost('opt-balai-lelang');
        $data['name'] = $this->input->getPost('txt-nama-event');
        $data['description'] = $this->input->getPost('description-event');
        $data['event_date'] = $this->input->getPost('txt-tanggal-event');
        $data['location'] = $this->input->getPost('txt-location-event');
        $data['is_active'] = $this->input->getPost('opt-active-flag');
        $data['updated_by'] = session()->get('USER_ID');
        $data['updated_time'] = date('Y-m-d H:i:s');
        $return = $this->Event_auction_house_model->save_edit_event_balai_lelang($data);
        if ($return) {
            $cache = session()->get('USER_ID').'_event_balai_lelang_list';
            $this->cache->delete($cache);
            $data = $this->Event_auction_house_model->get_event_balai_lelang_list();
            $this->cache->save($cache, json_encode($data), env('TIMECACHE_1'));
            $rs = array('success' => true, 'message' => 'Success to update data', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }else{
            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }
    }
    function del_eBalai(){
        $data['id'] = $this->input->getPost('id');
        $is_active = $this->Common_model->get_record_values('is_active', 'cms_balai_lelang_event', 'id="'.$data['id'].'"');
        if ($is_active['is_active'] == "0") {
            $data['is_active'] = "1";
            $response = array('success'=>true, 'message' => 'Data has been activated');
        } else {
            $data['is_active'] = "0";
            $response = array('success'=>true, 'message' => 'Data has been deactivated');
        }
        $return = $this->Event_auction_house_model->save_edit_event_balai_lelang($data);
        if ($return) {
            
            $newCsrfToken = csrf_hash();

            $cache = session()->get('USER_ID').'_event_balai_lelang_list';
            $this->cache->delete($cache);
            $data = $this->Event_auction_house_model->get_event_balai_lelang_list();
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