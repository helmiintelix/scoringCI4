<?php 
namespace App\Modules\ReportWa\Controllers;
use App\Modules\ReportWa\Models\Report_wa_model;
use CodeIgniter\Cookie\Cookie;


class Report_wa extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Report_wa_model = new Report_wa_model();
	}

	function view($id = null){
		if ($id !== null) {
            $data['report_name'] = $this->Common_model->get_record_value('report_name', 'wa_master_report', 'id="'.$id.'"');
			$data['classification'] = $id;
			return view('\App\Modules\ReportWa\Views\Report_wa_view', $data);
        } else {
            $data = $this->Report_wa_model->insert_report();
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		
			return $this->response->setStatusCode(200)->setJSON($rs);
        }
	}
	function get_data_wa_list(){
		$cache = session()->get('USER_ID').'_data_wa_list_'.$this->input->getGet('id');
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Report_wa_model->get_data_wa_list($this->input->getGet('id'));
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
}