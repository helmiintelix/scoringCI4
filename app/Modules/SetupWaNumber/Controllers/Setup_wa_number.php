<?php 
namespace App\Modules\SetupWaNumber\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\SetupWaNumber\Models\Setup_wa_number_model;


class Setup_wa_number extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_wa_number_model = new Setup_wa_number_model();
	}

	function setup_wa_number()
	{
		
		$data['classification'] = $this->input->getPost('classification');
		
		return view('\App\Modules\SetupWaNumber\Views\Setup_wa_number_view',$data);
	}
    
	function device_list()
	{
		
		$data = $this->Setup_wa_number_model->get_device_list();
		$rs = array('success' => true, 'message' => '', 'data' => $data);
		return $this->response->setStatusCode(200)->setJSON($rs);
		
	} 

	function save_device_add()
	{

		$is_exist = $this->Setup_wa_number_model->isExistVRId($this->input->getPost('id'));
		if(!($is_exist)){
			foreach ($this->input->getPost('list_agent') as $key => $value) {
				if ($value=='ALL') {
					$agent=json_encode(array('ALL'));
					break;
				}else{
					$agent=json_encode($this->input->getPost('list_agent'));
				}
			}

			if (getenv('TYPE_WA') === 'long_number') {
				$type_wa='long_number';
			}else{
				$type_wa='business';
			}
			$data["id"]= $this->input->getPost('id');
			$data["phone"]= $this->input->getPost('phone');
			$data["expired_date"]= $this->input->getPost('expired_date').date(' H:i:s');
			$data["token"]= $this->input->getPost('token');
			$data["last_update"] = date('Y-m-d H:i:s');
			$data["group_name"] = $this->input->getPost('server');
			$data["status"] = $this->input->getPost('opt-active-flag');
			$data["passwd"]= $this->input->getPost('password');
			$data["agent_list"]= $agent;
			$data["url"]= $this->input->getPost('url');
			$data["type"]= $type_wa;
			
			$return	= $this->Setup_wa_number_model->save_device_add($data);
			
			if($return){
				$rs = array('success' => true, 'message' => 'Success', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
		}
		else{
            $rs = array('success' => false, 'message' => 'DEVICE Already exist.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
		
		echo json_encode($data);
	}

    function device_add_form()
	{
		$data['list_agent'] = array('ALL'=>'ALL Agent')+$this->Common_model->get_record_list('id as value, name as item', 'cc_user', 'is_active = "1" and group_id="AGENT_FIELD_COLLECTOR"', 'id asc');
		return view('\App\Modules\SetupWaNumber\Views\Setup_wa_number_add_view',$data);
	}

	function device_edit_form()
	{
		
		$id = $this->input->getGet('id');
		$data['data'] = $this->Common_model->get_record_values("*", "wa_devices", "id='".$id."'", "");
        
        $data['id']=$data['data']['id'];
		$data['phone']=$data['data']['phone'];
		$data['expired_date']=explode(',',$data['data']['expired_date']);
		$data['token']=$data['data']['token'];
		$data['group_name']=$data['data']['group_name'];
		$data['status']=$data['data']['status'];
		$data["passwd"]= $data['data']['passwd'];
		$data["agent_list_selected"]= json_decode($data['data']['agent_list']) ;
		$data["url"]= $data['data']['url'];
		$data["type"]= $data['data']['type'];

		$data['list_agent'] = array('ALL'=>'ALL Agent')+$this->Common_model->get_record_list('id as value, name as item', 'cc_user', 'is_active = "1" and group_id="AGENT_FIELD_COLLECTOR"', 'id asc');

		/*echo "<pre>";
		print_r($data);*/
		return view('\App\Modules\SetupWaNumber\Views\Setup_wa_number_edit_view',$data);
	}

    function save_device_edit()
	{
		foreach ($this->input->getPost('list_agent') as $key => $value) {
			if ($value=='ALL') {
				$agent=json_encode(array('ALL'));
				break;
			}else{
				$agent=json_encode($this->input->getPost('list_agent'));
			}
		}

		if (getenv('TYPE_WA') === 'long_number') {
			$type_wa='long_number';
		}else{
			$type_wa='business';
		}

		$data["id"]= $this->input->getPost('id');
        $data["phone"]= $this->input->getPost('phone');
        $data["expired_date"]= $this->input->getPost('expired_date').date(' H:i:s');
        $data["token"]= $this->input->getPost('token');
        $data["last_update"] = date('Y-m-d H:i:s');
        $data["status"] = $this->input->getPost('opt-active-flag');
        $data["group_name"] = $this->input->getPost('server');
        $data["passwd"]= $this->input->getPost('password');
		$data["agent_list"]= $agent;
		$data["url"]= $this->input->getPost('url');
		$data["type"]= $type_wa;

        $return	= $this->Setup_wa_number_model->save_device_edit($data);
        
        if($return){
            $rs = array('success' => true, 'message' => 'Success', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }else{
            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }
		
		echo json_encode($data);
	}

}
