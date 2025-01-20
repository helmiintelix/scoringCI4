<?php 
namespace App\Modules\SetupWaQuickReply\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\SetupWaQuickReply\Models\Setup_wa_quick_reply_model;


class Setup_wa_quick_reply extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_wa_quick_reply_model = new Setup_wa_quick_reply_model();
	}

	function view_quick_reply_list()
	{
		return view('\App\Modules\SetupWaQuickReply\Views\Setup_wa_quick_reply_view');
	}
    
	function get_wa_quick_reply_list()
	{
		
        $data = $this->Setup_wa_quick_reply_model->get_wa_quick_reply_list();
        $rs = array('success' => true, 'message' => '', 'data' => $data);
        return $this->response->setStatusCode(200)->setJSON($rs);
	}

    function wa_quick_reply_add_form()
    {
        $data['list_group'] = $this->Common_model->get_record_list('id as value, name as item', 'cc_user_group', 'is_active = "1" and level_group="FIELD_COLL"', 'id asc');

        return view('\App\Modules\SetupWaQuickReply\Views\Setup_wa_quick_reply_add_view',$data);
    } 

	function save_wa_quick_reply_add()
	{
        $data['id']=uuid();
        
        $data['template_name']=$this->input->getPost('txt_wa_template_template_name');
        $data['message']=$this->input->getPost('txt_wa_template_template_design');
        $data['group_id']=json_encode($this->input->getPost('list_group'));
        $data['created_time']=date('Y-m-d H:i:s');
        $data['created_by']=session()->get('USER_ID');
        $data['is_active']=$this->input->getPost('opt-active-flag');
        $data['type']='AGENT';
        
        $return	= $this->Setup_wa_quick_reply_model->save_wa_quick_reply_add($data);
        
        if($return){
            $this->Common_model->data_logging('WA Quick Reply', 'Add template ', 'SUCCESS', 'Template name '.$this->input->getPost('txt_wa_template_template_name'));
            $data	= array("success" => true, "message" => "Success");

            $rs = array('success' => true, 'message' => 'Success', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }else{
            $this->Common_model->data_logging('WA Quick Reply', 'Add template ', 'FAILED', 'Template name '.$this->input->getPost('txt_wa_template_template_name'));
            $data	= array("success" => false, "message" => "Failed");

            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }

		echo json_encode($data);
	}

	function wa_quick_reply_edit_form()
	{
        $id=$this->input->getGet('id');
        $data['data_template'] = $this->Common_model->get_record_values('*','wa_quick_reply','id="'.$id.'"');
        $data["group_list_selected"]= json_decode($data['data_template']['group_id']) ;
	    $data['list_group'] = $this->Common_model->get_record_list('id as value, name as item', 'cc_user_group', 'is_active = "1" and level_group="FIELD_COLL"', 'id asc');
		return view('\App\Modules\SetupWaQuickReply\Views\Setup_wa_quick_reply_edit_view',$data);
	}

    function save_wa_quick_reply_edit(){
        $data['id']=$this->input->getPost('id');
        
        $data['template_name']=$this->input->getPost('txt_wa_template_template_name');
        $data['message']=$this->input->getPost('txt_wa_template_template_design');
        $data['group_id']=json_encode($this->input->getPost('list_group'));
        $data['created_time']=date('Y-m-d H:i:s');
        $data['created_by']=session()->get('USER_ID');
        $data['is_active']=$this->input->getPost('opt-active-flag');
        $data['type']='AGENT';

        $return	= $this->Setup_wa_quick_reply_model->save_wa_quick_reply_edit($data);

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
