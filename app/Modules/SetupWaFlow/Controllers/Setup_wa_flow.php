<?php 
namespace App\Modules\SetupWaFlow\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\SetupWaFlow\Models\Setup_wa_flow_model;


class Setup_wa_flow extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_wa_flow_model = new Setup_wa_flow_model();
	}

	function view_flow_list()
	{
		
		//$data['classification'] = $this->input->getPost('classification');
		
		return view('\App\Modules\SetupWaFlow\Views\Setup_wa_flow_view');
	}
    
	function get_wa_flow_list()
	{
        $data = $this->Setup_wa_flow_model->get_wa_flow_list();
        $rs = array('success' => true, 'message' => '', 'data' => $data);
        return $this->response->setStatusCode(200)->setJSON($rs);
	}

    function wa_flow_add_form()
    {
        $data['list_template'] = array(''=>'Select Param')+$this->Common_model->get_record_list('id as value, template_name as item', 'cms_wa_template', 'is_active = "1"', 'template_name asc');
        $data['list_order'] = array(''=>'Select Param')+$this->Common_model->get_record_list('value as value, description as item', 'cms_reference', 'reference="ACCOUNT_STATUS"', 'value asc');
        $data['value_mapping'] = array(''=>'Select Param')+$this->Common_model->get_record_list('value as value, description as item', 'cms_field_template_mapping', 'is_active = "Y" and replace_with is not null', 'description asc');

        return view('\App\Modules\SetupWaFlow\Views\Setup_wa_flow_add_view',$data);
    } 

	function save_wa_flow_add()
	{
        $data['wa_id']=uuid();
        $data['template_name']=$this->input->getPost('txt_wa_child_name');
        $data['mask_name']=$this->input->getPost('opt_wa_template');
        $data['label_ops']=$this->input->getPost('txt_wa_label_name');
        $data['order_num']=$this->input->getPost('opt_wa_label_number');
        $data['preview_message']=$this->input->getPost('txt_wa_template_template_design');
        $parameter_raw = $this->input->getPost('txt-template-input-param');
        $data['parameter']=implode('|', $parameter_raw);
        if (empty($data['parameter'])) {
            $data['is_routing']='N';
        }else{
            $data['is_routing']='Y';
        }
        $data['created_time']=date('Y-m-d H:i:s');
        $data['created_by']=session()->get('USER_ID');
        $data['status']=$this->input->getPost('opt-active-flag');
        $data['total_parameter']=count($this->input->getPost('txt-template-input-param'));
        $data['template_type']='TEXT';

        $template_arr = explode('{{',preg_replace('/[0-9]+/', '', $data['preview_message'])); //ini masih ada bug, gimana kalo ada angka tanpa bracket?
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
        $data_already = $this->Common_model->get_record_values('wa_id','wa_template_flow','template_name="'.$data['template_name'].'"');
        if(!empty($data_already)){
            $data	= array("success" => false, "message" => "Failed. Template ID already exist!");
            echo json_encode($data);
            return false;
        }

        $return	= $this->Setup_wa_flow_model->save_wa_flow_add($data);
        
        if($return){
            $this->Common_model->data_logging('WA Flow', 'Add template ', 'SUCCESS', 'Template ID '.$this->input->getPost('txt_wa_template_template_id'));
            $data	= array("success" => true, "message" => "Success");

            $rs = array('success' => true, 'message' => 'Success', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }else{
            $this->Common_model->data_logging('WA Flow', 'Add template ', 'FAILED', 'Template ID '.$this->input->getPost('txt_wa_template_template_id'));
            $data	= array("success" => false, "message" => "Failed");

            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }

		echo json_encode($data);
	}


	function wa_flow_edit_form()
	{
        $id=$this->input->getGet('id');
        $data['data_template'] = $this->Common_model->get_record_values('*','wa_template_flow','wa_id="'.$id.'"');
		$data['list_template'] = array(''=>'Select Param')+$this->Common_model->get_record_list('id as value, template_name as item', 'cms_wa_template', 'is_active = "1"', 'template_name asc');
        $data['list_order'] = array(''=>'Select Param')+$this->Common_model->get_record_list('value as value, description as item', 'cms_reference', 'reference="ACCOUNT_STATUS"', 'value asc');
        $data['value_mapping'] = array(''=>'Select Param')+$this->Common_model->get_record_list('value as value, description as item', 'cms_field_template_mapping', 'is_active = "Y" and replace_with is not null', 'description asc');

		return view('\App\Modules\SetupWaFlow\Views\Setup_wa_flow_edit_view',$data);
	}

    function save_wa_flow_edit(){
        $data['wa_id']=$this->input->getPost('id');
        $data['template_name']=$this->input->getPost('txt_wa_child_name');
        $data['mask_name']=$this->input->getPost('opt_wa_template');
        $data['label_ops']=$this->input->getPost('txt_wa_label_name');
        $data['order_num']=$this->input->getPost('opt_wa_label_number');
        $data['preview_message']=$this->input->getPost('txt_wa_template_template_design');
        $parameter_raw = $this->input->getPost('txt-template-input-param');
        $data['parameter']=implode('|', $parameter_raw);
        if (empty($data['parameter'])) {
            $data['is_routing']='N';
        }else{
            $data['is_routing']='Y';
        }
        $data['created_time']=date('Y-m-d H:i:s');
        $data['created_by']=session()->get('USER_ID');
        $data['status']=$this->input->getPost('opt-active-flag');
        $data['total_parameter']=count($this->input->getPost('txt-template-input-param'));   
        $data['template_type']='TEXT';

        $template_arr = explode('{{',preg_replace('/[0-9]+/', '', $data['preview_message']));
        $new_template='';
        foreach($template_arr as $keys => $row){
            if(str_contains($row, '}}')){
                $row=str_replace("}}", $parameter_raw[$keys-1], $row );
            }
            $new_template.=$row;
        }
        $data['template_replace']=$new_template; 

        $return	= $this->Setup_wa_flow_model->save_wa_flow_edit($data);

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
