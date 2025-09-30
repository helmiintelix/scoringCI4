<?php

namespace App\Modules\HistoryUpload\Controllers;

use App\Controllers\BaseController;
use App\Modules\HistoryUpload\Models\HistoryUploadModel;

class HistoryUpload extends \App\Controllers\BaseController
{

    public $cache_history_upload = 'history_upload_list';

    function __construct()
	{
		$this->HistoryUploadModel = new HistoryUploadModel();
	}

    public function index()
    {
        return view('App\Modules\HistoryUpload\Views\historyuploadView');
    }

    function summary_upload_list() {
		$data = $this->HistoryUploadModel->get_summary_upload_list();
		$this->Common_model->data_logging('History Upload', "LIST DATA", 'SUCCESS', '');
        $rs = array('success' => true, 'message' => '', 'data' => $data);
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	
	
	function delete_scheme_upload() {
		$id_upload = $this->input->getPost('id_upload');
		$response = array();

        $builder = $this->db->table('sc_setting_upload_activity');
        $return = $builder->where('id', $id_upload)->update(['upload_status' => 'DELETED']);
		
		try {
			if ($this->check_parameter_status($id_upload)) {
				$data = array("success" => false, "message" => "Parameter tidak ada!");
			}
			else {
				
				$this->HistoryUploadModel->delete_scheme_upload($id_upload);
				$this->Common_model->data_logging('History Upload', "Delete parameter", 'SUCESS', 'Upload ID: '.$id_upload);
				$data = array("success" => true, "message" => "Success"); 
			}
		}
		catch (execption $e) {
			$this->Common_model->data_logging('History Upload', "Delete parameter", 'FAILED', 'Upload ID: '.$id_user);
			$data = array("success" => false, "message" => "Failed", "error" => $e);
		}
			
		return $this->response->setStatusCode(200)->setJSON($data);
	}
	
	

	function check_parameter_status($id_upload) {
		$row = $this->HistoryUploadModel->check_parameter_status($id_upload);
		return $row;
	}
	
}
