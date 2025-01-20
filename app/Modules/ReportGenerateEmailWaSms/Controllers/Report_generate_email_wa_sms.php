<?php 
namespace App\Modules\ReportGenerateEmailWaSms\Controllers;
use App\Modules\ReportGenerateEmailWaSms\Models\Report_generate_email_wa_sms_model;
use CodeIgniter\Cookie\Cookie;
use DateTime;

class Report_generate_email_wa_sms extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Report_generate_email_wa_sms_model = new Report_generate_email_wa_sms_model();
	}

	function index(){
		$builder = $this->db->table('acs_cms_product');
        $builder->select('ProductCode , ProductName');
        $res = $builder->get()->getResultArray();
        $prod = array();
        foreach ($res as $key => $value) {
            $prod[$value['ProductCode']] = $value['ProductName'];
        }
        $data['product'] = $prod;
		$data['classification'] = $this->input->getPost('classification');
		
		return view('\App\Modules\ReportGenerateEmailWaSms\Views\Report_generate_email_sms_view', $data);
	}
	function get_report_email_sms_data(){
		$dataInput['tgl_from'] = '';
		$dataInput['tgl_to'] = '';
		$tgl = $this->input->getGet('tgl');
		$dataInput['sent_by'] = $this->input->getGet('sent_by');
		$dataInput['product'] = $this->input->getGet('product');
		$date = explode(' - ', $tgl);
		if (count($date) == 2) {
			$dataInput['tgl_from'] = DateTime::createFromFormat('d/m/Y', $date[0])->format('Y-m-d');
			
			// Ubah format tanggal dari dd/mm/yyyy ke dd-mm-yyyy untuk tanggal akhir
			$dataInput['tgl_to']= DateTime::createFromFormat('d/m/Y', $date[1])->format('Y-m-d');
		}
		// print_r($dataInput);
		// exit();
		switch ($dataInput['sent_by']) {
			case 'Email':
				$data = $this->Report_generate_email_wa_sms_model->get_report_generate_email($dataInput);
				break;
			case 'Sms':
				$data = $this->Report_generate_email_wa_sms_model->get_report_generate_sms($dataInput);
				break;
			case 'Wa':
				$data = $this->Report_generate_email_wa_sms_model->get_report_generate_wa($dataInput);
				break;
			default:
				$data = $this->Report_generate_email_wa_sms_model->get_report_generate_email($dataInput);
				break;
		}
		// print_r($data);
		// exit();
		// $data = $this->Report_generate_email_wa_sms_model->get_report_email_sms_data($dataInput);
	    if ($data) {
			$cacheKey = session()->get('USER_ID') . '_report_email_sms_data';
			$this->cache->delete($cacheKey);
			$this->cache->save($cacheKey, json_encode($data), env('TIMECACHE_1'));
	
			$rs = ['success' => true, 'message' => 'Success to apply filter', 'data' => $data];
		} else {
			$rs = ['success' => false, 'message' => 'failed', 'data' => null];
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
}