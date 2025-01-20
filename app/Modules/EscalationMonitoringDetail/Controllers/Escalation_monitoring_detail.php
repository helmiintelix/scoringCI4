<?php 
namespace App\Modules\EscalationMonitoringDetail\Controllers;
use App\Modules\EscalationMonitoringDetail\Models\Escalation_monitoring_detail_model;
use CodeIgniter\Cookie\Cookie;
use DateTime;

class Escalation_monitoring_detail extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Escalation_monitoring_detail_model = new Escalation_monitoring_detail_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		$data['module'] = "ESCALATION-MONITORING-DETAIL";
		$data['title'] = str_replace('-', ' ', $this->Common_model->get_record_value('title', 'acs_master_report', 'module="'.$data['module'].'"'));
		$data['header_design'] = $this->Common_model->get_record_value('header_design', 'acs_master_report', 'module="'.$data['module'].'"');
		$builder = $this->db->table("acs_matrix_report");
		$builder->select("*");
		$builder->where("module", $data['module']);
		$data['filters'] = $builder->get()->getResultArray();
		return view('\App\Modules\EscalationMonitoringDetail\Views\Template_report_view', $data);
	}
	function getDataRecord(){
		$dataInnput['start'] 		= $this->input->getGet('start');
		$dataInnput['end'] 		= $this->input->getGet('end');
		$dataInnput['status'] 	= $this->input->getGet('status');
		$dataInnput['team_leader_id']	= $this->input->getGet('team_leader_id');
		$html = '';
				
		$loadData = $this->Escalation_monitoring_detail_model->getEscalationMonitoringDetail($dataInnput);
		$arrData  = json_decode($loadData, true);
		
		$html .= '<tbody>';
		
		if(count($arrData) > 0){
			foreach($arrData as $val){
		
				$html .= '<tr>
							<td>'.$val['spv_id'].'</td>
							<td>'.$val['spv_name'].'</td>
							<td>'.$val['spcl_status'].'</td>
							<td>'.$val['contract_number'].'</td>
							<td>'.$val['customer_name'].'</td>
							<td>-</td>
							<td>'.$val['bucket'].'</td>
							<td>'.$val['class_name'].'</td>
							<td>'.$val['contact_status'].'</td>
							<td>'.$val['call_result'].'</td>
							<td>'.$val['notepad1'].'</td>
							<td>'.$val['first_name'].'</td>
							<td>'.$val['call_time'].'</td>
							<td>'.$val['duration_escalation'].'</td>
						</tr>';
			}
		}else{
			$html .= '<tr>
							<td colspan="14" align="center">no data</td>
						</tr>';
		}
		
		$html .= '</tbody>';
		
		$rs = ['success' => true, 'message' => '', 'dataRow' => $html];
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function getDataRecordDownload(){
		$data['module'] = $this->input->getGet('module');
		$data['title'] = str_replace(array('-', '_download'), array(' ', ' '), $data['module']);
		$data['start'] 		= $this->input->getGet('start');
		$data['end'] 		= $this->input->getGet('end');
		$data['status'] 	= $this->input->getGet('status');
		$data['team_leader_id']	= $this->input->getGet('team_leader_id');
		
		$loadData = $this->Escalation_monitoring_detail_model->getEscalationMonitoringDetail($data);
		$data['arrData']  = json_decode($loadData, true);
		
		return view('\App\Modules\EscalationMonitoringDetail\Views\Report_escalation_monitoring_detail_download_view', $data);
	
	}
}