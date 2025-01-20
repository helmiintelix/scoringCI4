<?php 
namespace App\Modules\SetupWaTemplate\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\SetupWaTemplate\Models\Setup_wa_template_model;


class Setup_wa_template extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_wa_template_model = new Setup_wa_template_model();
	}

	function view_template_list()
	{
		
		$data['classification'] = $this->input->getPost('classification');
		
		return view('\App\Modules\SetupWaTemplate\Views\Setup_wa_template_view',$data);
	}
    
	function get_wa_template_list()
	{
		
		$cache = session()->get('USER_ID').'_setup_wa_template';

		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_wa_template_model->get_wa_template_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	} 

	function save_wa_template_add()
	{
        $data['id']=uuid();
        $data['template_id']=$this->input->getPost('txt_wa_template_template_id');
        $data['template_name']=$this->input->getPost('txt_wa_template_template_name');
        $data['template_design']=$this->input->getPost('txt_wa_template_template_design');
        $data['select_time']=$this->input->getPost('txt-template-input-times1');
        $parameter_raw = $this->input->getPost('txt-template-input-param');
        $data['parameter']=implode('|', $parameter_raw);

        $data['created_time']=date('Y-m-d H:i:s');
        $data['created_by']=session()->get('USER_ID');
        $data['is_active']=$this->input->getPost('opt-active-flag');
        $data['max_retry']=$this->input->getPost('txt_wa_max_retry');

        $template_arr = explode('{{',preg_replace('/[0-9]+/', '', $data['template_design'])); //ini masih ada bug, gimana kalo ada angka tanpa bracket?
            $new_template='';
            foreach($template_arr as $keys => $row){
                if(str_contains($row, '}}')){
                    // echo $row;
                    $row=str_replace("}}", $parameter_raw[$keys-1], $row );
                }
                // echo $row;
                $new_template.=$row;
            }
            // var_dump($template_arr);
            $data['template_replace']=$new_template; //untuk preview di layar agent
            $data_already = $this->Common_model->get_record_values('template_id','cms_wa_template',"template_id='".$data['template_id']."'");
            if(!empty($data_already)){
                $data	= array("success" => false, "message" => "Failed. Template ID already exist!");
                return $this->response->setStatusCode(200)->setJSON($data);
            }
    
            $return	= $this->Setup_wa_template_model->save_wa_template_add($data);
        
        if($return){
            $this->Common_model->data_logging('WA Template', 'Add template ', 'SUCCESS', 'Template ID '.$this->input->getPost('txt_wa_template_template_id'));
            $data	= array("success" => true, "message" => "Success");

            $rs = array('success' => true, 'message' => 'Success', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }else{
            $this->Common_model->data_logging('WA Template', 'Add template ', 'FAILED', 'Template ID '.$this->input->getPost('txt_wa_template_template_id'));
            $data	= array("success" => false, "message" => "Failed");

            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }
        return $this->response->setStatusCode(200)->setJSON($data);
	}

    function wa_template_add_form()
	{
        $data['value_mapping'] = array(''=>'Select Param')+$this->Common_model->get_record_list('value as value, description as item', 'cms_field_template_mapping', "is_active = 'Y' and replace_with is not null", 'description asc');

		return view('\App\Modules\SetupWaTemplate\Views\Setup_wa_template_add_view',$data);
	}

	function wa_template_edit_form()
	{
        $id=$this->input->getGet('id');
        $data['data_template'] = $this->Common_model->get_record_values('*','cms_wa_template',"id='".$id."'");
		$data['value_mapping'] = array(''=>'Select Param')+$this->Common_model->get_record_list('value as value, description as item', 'cms_field_template_mapping', "is_active = 'Y' and replace_with is not null", 'description asc');

		return view('\App\Modules\SetupWaTemplate\Views\Setup_wa_template_edit_view',$data);
	}

    function save_wa_template_edit(){
        $data['id']=$this->input->getPost('id');
        $data['template_id']=$this->input->getPost('txt_wa_template_template_id');
        $data['template_name']=$this->input->getPost('txt_wa_template_template_name');
        $data['template_design']=$this->input->getPost('txt_wa_template_template_design');
        $data['select_time']=$this->input->getPost('txt-template-input-times1');
        $data['created_time']=date('Y-m-d H:i:s');
        $data['created_by']=session()->get('USER_ID');
        $parameter_raw=$this->input->getPost('txt-template-input-param');
        $data['parameter']=implode('|', $parameter_raw);
        $data['is_active']=$this->input->getPost('opt-active-flag');
        $data['max_retry']=$this->input->getPost('txt_wa_max_retry');

        $template_arr = explode('{{',preg_replace('/[0-9]+/', '', $data['template_design']));
        $new_template='';
        foreach($template_arr as $keys => $row){
            if(str_contains($row, '}}')){
                $row=str_replace("}}", $parameter_raw[$keys-1], $row );
            }
            $new_template.=$row;
        }
        $data['template_replace']=$new_template; 

        $return	= $this->Setup_wa_template_model->save_wa_template_edit($data);

        if($return){
            $rs = array('success' => true, 'message' => 'Success', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }else{
            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }
		
		return $this->response->setStatusCode(200)->setJSON($data);
    }


    function save_device_edit()
	{
		$data["id"]= $this->input->getPost('id');
        $data["phone"]= $this->input->getPost('phone');
        $data["expired_date"]= $this->input->getPost('expired_date').date(' H:i:s');
        $data["token"]= $this->input->getPost('token');
        $data["last_update"] = date('Y-m-d H:i:s');
        $data["status"] = $this->input->getPost('opt-active-flag');
        $data["group_name"] = $this->input->getPost('server');

        $return	= $this->Setup_wa_template_model->save_device_edit($data);
        
        if($return){
            $rs = array('success' => true, 'message' => 'Success', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }else{
            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }
		
		return $this->response->setStatusCode(200)->setJSON($data);
	}

}
