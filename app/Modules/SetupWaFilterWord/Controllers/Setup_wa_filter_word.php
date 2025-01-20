<?php 
namespace App\Modules\SetupWaFilterWord\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\SetupWaFilterWord\Models\Setup_wa_filter_word_model;


class Setup_wa_filter_word extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_wa_filter_word_model = new Setup_wa_filter_word_model();
	}

	function view_filter_word_list()
	{
		return view('\App\Modules\SetupWaFilterWord\Views\Setup_wa_filter_word_view');
	}
    
	function get_wa_filter_word_list()
	{
        $data = $this->Setup_wa_filter_word_model->get_wa_filter_word_list();
        $rs = array('success' => true, 'message' => '', 'data' => $data);
        return $this->response->setStatusCode(200)->setJSON($rs);
	}

    function wa_filter_word_add_form()
    {
        return view('\App\Modules\SetupWaFilterWord\Views\Setup_wa_filter_word_add_view');
    } 

	function save_wa_filter_word_add()
	{
        $arrWord=explode(";",$this->input->getPost('txt_word'));
        foreach ($arrWord as $key => $value) {
            $data['id']=uuid();
            $data['word']=$value;
            $data['is_active']=$this->input->getPost('opt-active-flag');
            $data['created_time']=date('Y-m-d H:i:s');
            $data['created_by']=session()->get('USER_ID');
        
            $return	= $this->Setup_wa_filter_word_model->save_wa_filter_word_add($data);
        }
        
        if($return){
            $this->Common_model->data_logging('WA Filter Word', 'Add template ', 'SUCCESS', 'Template ID '.$this->input->getPost('txt_wa_template_template_id'));
            $data	= array("success" => true, "message" => "Success");

            $rs = array('success' => true, 'message' => 'Success', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }else{
            $this->Common_model->data_logging('WA Filter Word', 'Add template ', 'FAILED', 'Template ID '.$this->input->getPost('txt_wa_template_template_id'));
            $data	= array("success" => false, "message" => "Failed");

            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }

		echo json_encode($data);
	}

	function wa_filter_word_edit_form()
	{
        $id=$this->input->getGet('id');
        $data['data_template'] = $this->Common_model->get_record_values('*','wa_filter_word','id="'.$id.'"');
		return view('\App\Modules\SetupWaFilterWord\Views\Setup_wa_filter_word_edit_view',$data);
	}

    function save_wa_filter_word_edit(){

        $arrWord=explode(";",$this->input->getPost('txt_word'));
        foreach ($arrWord as $key => $value) {
            if ($key < 1) {
                
                $data['id']=$this->input->getPost('id');
                $data['word']=$value;
                $data['is_active']=$this->input->getPost('opt-active-flag');
                $data['created_time']=date('Y-m-d H:i:s');
                $data['created_by']=session()->get('USER_ID');

                $return	= $this->Setup_wa_filter_word_model->save_wa_filter_word_edit($data);
            }else{

                $data['id']=uuid();
                $data['word']=$value;
                $data['is_active']=$this->input->getPost('opt-active-flag');
                $data['created_time']=date('Y-m-d H:i:s');
                $data['created_by']=session()->get('USER_ID');
            
                $return = $this->Setup_wa_filter_word_model->save_wa_filter_word_add($data);
            }
        } 

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
