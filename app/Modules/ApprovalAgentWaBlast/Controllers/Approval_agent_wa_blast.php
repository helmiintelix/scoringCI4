<?php 
namespace App\Modules\ApprovalAgentWaBlast\Controllers;
use App\Modules\ApprovalAgentWaBlast\Models\Approval_agent_wa_blast_model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use CodeIgniter\Cookie\Cookie;
use DateTime;

class Approval_agent_wa_blast extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Approval_agent_wa_blast_model = new Approval_agent_wa_blast_model();
	}

	function view_approval_agent_wa_blast_list(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\ApprovalAgentWaBlast\Views\Approval_agent_wa_blast_view', $data);
	}
	function get_pengiriman_surat_file_list(){

		$data = $this->Approval_agent_wa_blast_model->get_pengiriman_surat_file_list();
        $rs = array('success' => true, 'message' => '', 'data' => $data);
        return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function upload_file_form(){
		$data['list_template'] = array(''=>'Select Param')+$this->Common_model->get_record_list('id as value, template_name as item', 'cms_wa_template', 'is_active = "1"', 'template_name asc');
		return view('\App\Modules\ApprovalAgentWaBlast\Views\Upload_file_form_view',$data);
	}

	function getDetailTemplate()
    {
        $id = $this->input->getGet('data');
        $data = $this->Common_model->get_record_values('*', 'cms_wa_template', 'id="'.$id.'"');
        $rs = array('success' => true, 'message' => '', 'data' => $data);
        return $this->response->setStatusCode(200)->setJSON($rs);
    }

    function get_message_content($acc,$template_text,$template_param)
    {
    	
		$parameterSelect = array_map(function($param) {
		    return trim($param, '[]'); // Menghapus tanda [] di sekeliling
		}, explode('|', $template_param));

		$header_select = implode(",'|',", $parameterSelect);

		$value_param = $this->Common_model->get_record_value("concat(".$header_select.") as val", 'cpcrd_new', 'cm_card_nmbr="'.$acc.'"');
		
		$valueArray = explode('|', $value_param);
		$parameterArray = explode('|', $template_param);
		
		$message = str_replace($parameterArray, $valueArray, $template_text);
		
		$rs = array('success' => true, 'message' => $message, 'data' => $value_param);
		return $rs;
    }

	
	private function convertDateFormat($date)
    {
        $dateObject = DateTime::createFromFormat('m/d/Y', $date);
        if ($dateObject === false) {
            return null;
        }
        return $dateObject->format('Y-m-d');
    }
	function show_uploaded_file_form(){
		
		$id = $this->input->getGet('id');
		$data = $this->Approval_agent_wa_blast_model->show_activity_by_file($id);
		$header = "<tr><td><input type='checkbox' id='check_all' name='check_all' onchange='allCheck()' ></td><td>No</td><td>Account Number</td><td>Phone Number</td><td>Status</td><td>Template ID</td><td>Message</td></tr>";
		$detail = "";

		foreach ($data as $key => $value) {
            $html='<td><input type="checkbox" class="check" id="checkModal_'.$key.'" name="checkModal[]" value="'.$value['id'].'"></td>';
			$detail .= "<tr>".$html."<td>".($key+1)."</td><td>".$value['account_number']."</td><td>".$value['phone_number']."</td><td>".$value['status']."</td><td>".$value['template_name']."</td><td>".$value['message']."</td></tr>";
		}
		
		$data["upload_id"] = $id;
		$data["table"] = '<table class="table table-striped table-bordered table-hover">'.$header.$detail."</table>";
		// dd($data);
		return view('\App\Modules\ApprovalAgentWaBlast\Views\Show_uploaded_file_form_view', $data);
	}
	function approve_upload_file(){

		$data['id']=$this->input->getPost('checkModal');
		$data['upload_id']=$this->input->getPost('upload_id');
		
		$return = $this->Approval_agent_wa_blast_model->approve_upload_file($data);
		if($return){
			$cache = session()->get('USER_ID').'_wa_agent_blast_appove';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to upload data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'Already loaded', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

	function reject_upload_file(){

		$data['id']=$this->input->getPost('checkModal');
		$data['upload_id']=$this->input->getPost('upload_id');
		
		$return = $this->Approval_agent_wa_blast_model->reject_upload_file($data);
		if($return){
			$cache = session()->get('USER_ID').'_wa_agent_blast_appove';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to upload data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'Already loaded', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

}