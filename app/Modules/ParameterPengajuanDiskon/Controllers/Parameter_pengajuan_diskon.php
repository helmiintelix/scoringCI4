<?php 
namespace App\Modules\ParameterPengajuanDiskon\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\ParameterPengajuanDiskon\models\Parameter_pengajuan_diskon_model;


class Parameter_pengajuan_diskon extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Parameter_pengajuan_diskon_model = new Parameter_pengajuan_diskon_model();
	}

	function discount_parameter()
	{
		
		$data['classification'] = $this->input->getPost('classification');
		
		return view('\App\Modules\ParameterPengajuanDiskon\Views\Parameter_pengajuan_diskon_view',$data);
	}
    
	function get_discount_parameter_list()
	{
		
		$cache = session()->get('USER_ID').'_setup_wa_number';

		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Parameter_pengajuan_diskon_model->get_discount_parameter_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	} 

	// function save_device_add()
	// {

	// 	$is_exist = $this->Parameter_pengajuan_diskon_model->isExistVRId($this->input->getPost('id'));
	// 	if(!($is_exist)){
	// 		$data["id"]= $this->input->getPost('id');
	// 		$data["phone"]= $this->input->getPost('phone');
	// 		$data["expired_date"]= $this->input->getPost('expired_date').date(' H:i:s');
	// 		$data["token"]= $this->input->getPost('token');
	// 		$data["last_update"] = date('Y-m-d H:i:s');
	// 		$data["group_name"] = $this->input->getPost('server');
	// 		$data["status"] = $this->input->getPost('opt-active-flag');
			
	// 		$return	= $this->Parameter_pengajuan_diskon_model->save_device_add($data);
			
	// 		if($return){
	// 			$rs = array('success' => true, 'message' => 'Success', 'data' => null);
	// 			return $this->response->setStatusCode(200)->setJSON($rs);
	// 		}else{
	// 			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
	// 			return $this->response->setStatusCode(200)->setJSON($rs);
	// 		}
	// 	}
	// 	else{
    //         $rs = array('success' => false, 'message' => 'DEVICE Already exist.', 'data' => null);
	// 		return $this->response->setStatusCode(200)->setJSON($rs);
	// 	}
		
		
	// 	echo json_encode($data);
	// }

    function discount_parameter_add_form()
	{
		$data['kondisi_khusus_list_pl'] = json_encode($this->Common_model->get_ref_master('value , description item', 'cms_reference', 'reference="KONDISI_KHUSUS_PL" and flag="1" and flag_tmp="1" ', 'order_num asc'));
		$data['hirarki'] = $this->Common_model->get_ref_master('value , description item', 'cms_reference', 'reference="HIRARKI_DISKON" and flag="1" and flag_tmp="1" ', 'order_num asc');
		$data['bucket_pl'] = json_encode($this->Common_model->get_ref_master('bucket_id value , bucket_label item', 'cms_bucket', ' is_active="1" ', 'bucket_label asc',false));

		return view('\App\Modules\ParameterPengajuanDiskon\Views\Parameter_pengajuan_diskon_add_view',$data);
	}

	// function device_edit_form()
	// {
		
	// 	$id = $this->input->getGet('id');
	// 	$data['data'] = $this->Common_model->get_record_values("*", "devices", "id='".$id."'", "");
        
    //     $data['id']=$data['data']['id'];
	// 	$data['phone']=$data['data']['phone'];
	// 	$data['expired_date']=explode(',',$data['data']['expired_date']);
	// 	$data['token']=$data['data']['token'];
	// 	$data['group_name']=$data['data']['group_name'];
	// 	$data['status']=$data['data']['status'];

	// 	return view('\App\Modules\ParameterPengajuanDiskon\Views\Setup_wa_number_edit_view',$data);
	// }

    // function save_device_edit()
	// {
	// 	$data["id"]= $this->input->getPost('id');
    //     $data["phone"]= $this->input->getPost('phone');
    //     $data["expired_date"]= $this->input->getPost('expired_date').date(' H:i:s');
    //     $data["token"]= $this->input->getPost('token');
    //     $data["last_update"] = date('Y-m-d H:i:s');
    //     $data["status"] = $this->input->getPost('opt-active-flag');
    //     $data["group_name"] = $this->input->getPost('server');

    //     $return	= $this->Parameter_pengajuan_diskon_model->save_device_edit($data);
        
    //     if($return){
    //         $rs = array('success' => true, 'message' => 'Success', 'data' => null);
    //         return $this->response->setStatusCode(200)->setJSON($rs);
    //     }else{
    //         $rs = array('success' => false, 'message' => 'failed', 'data' => null);
    //         return $this->response->setStatusCode(200)->setJSON($rs);
    //     }
		
	// 	echo json_encode($data);
	// }

}
