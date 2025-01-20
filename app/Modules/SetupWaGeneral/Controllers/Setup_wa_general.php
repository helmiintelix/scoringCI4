<?php 
namespace App\Modules\SetupWaGeneral\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\SetupWaGeneral\Models\Setup_wa_general_model;


class Setup_wa_general extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_wa_general_model = new Setup_wa_general_model();
	}

	function view_general_list()
	{
		$data['data_already'] = $this->Common_model->get_record_values('id','wa_general_setup','1=1 limit 1');
        if (empty($data['data_already'])) {
            $data['data_already']['id']='';
        }
		return view('\App\Modules\SetupWaGeneral\Views\Setup_wa_general_view',$data);
	}
    
	function get_wa_general_list()
	{
        $data = $this->Setup_wa_general_model->get_wa_general_list();
        $rs = array('success' => true, 'message' => '', 'data' => $data);
        return $this->response->setStatusCode(200)->setJSON($rs);
	}

    function wa_general_add_form()
    {
        return view('\App\Modules\SetupWaGeneral\Views\Setup_wa_general_add_view');
    } 

	function save_wa_general_add()
	{
        
        $data['id']=uuid();

        $data['attemp_per_agent']=$this->input->getPost('txt_tot_agent_message');
        $data['attemp_per_cust']=$this->input->getPost('txt_tot_customer_message');
        $data['from_wa_outgoing']=$this->input->getPost('txt_outgoing_from');
        $data['to_wa_outgoing']=$this->input->getPost('txt_outgoing_to');
        $data['from_office_hour']=$this->input->getPost('txt_office_hour_from');
        $data['to_office_hour']=$this->input->getPost('txt_office_hour_to');
        $data['holiday_content']=$this->input->getPost('txt_wa_template_template_design');
        $data['updated_time']=date('Y-m-d H:i:s');
        $data['updated_by']=session()->get('USER_ID');
    
        $return	= $this->Setup_wa_general_model->save_wa_general_add($data);
        
        if($return){
            $this->Common_model->data_logging('WA General', 'Add template ', 'SUCCESS', 'Template ID '.$this->input->getPost('txt_wa_template_template_id'));
            $data	= array("success" => true, "message" => "Success");

            $rs = array('success' => true, 'message' => 'Success', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }else{
            $this->Common_model->data_logging('WA General', 'Add template ', 'FAILED', 'Template ID '.$this->input->getPost('txt_wa_template_template_id'));
            $data	= array("success" => false, "message" => "Failed");

            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }

		echo json_encode($data);
	}

	function wa_general_edit_form()
	{
        $id=$this->input->getGet('id');
        $data['data_template'] = $this->Common_model->get_record_values('*','wa_general_setup','id="'.$id.'"');
		return view('\App\Modules\SetupWaGeneral\Views\Setup_wa_general_edit_view',$data);
	}

    function save_wa_general_edit(){
        $data['id']=$this->input->getPost('id');
        $data['attemp_per_agent']=$this->input->getPost('txt_tot_agent_message');
        $data['attemp_per_cust']=$this->input->getPost('txt_tot_customer_message');
        $data['from_wa_outgoing']=$this->input->getPost('txt_outgoing_from');
        $data['to_wa_outgoing']=$this->input->getPost('txt_outgoing_to');
        $data['from_office_hour']=$this->input->getPost('txt_office_hour_from');
        $data['to_office_hour']=$this->input->getPost('txt_office_hour_to');
        $data['holiday_content']=$this->input->getPost('txt_wa_template_template_design');
        $data['updated_time']=date('Y-m-d H:i:s');
        $data['updated_by']=session()->get('USER_ID'); 

        $return	= $this->Setup_wa_general_model->save_wa_general_edit($data);

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
