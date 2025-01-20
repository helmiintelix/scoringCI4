<?php 
namespace App\Modules\AgentMonitoring\Controllers;
use App\Modules\AgentMonitoring\Models\Agent_monitoring_model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use CodeIgniter\Cookie\Cookie;
use DateTime;

class Agent_monitoring extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Agent_monitoring_model = new Agent_monitoring_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		$this->Common_model->data_logging('Monitoring', "VIEW Menu Agent Monitoring", 'SUCESS', '');
		$builder = $this->db->table("cms_reference");
		$builder->select("value break, add_field1 duration");
		$builder->where("REFERENCE", "BREAK_REASON");
		$builder->where("STATUS", "1");
		$data['aux'] = $builder->get()->getResultArray();
		$data['json_aux'] = json_encode($data['aux'], true);
		return view('\App\Modules\AgentMonitoring\Views\Agent_monitoring_new_view2', $data);
	}
	function get_account_handling(){
		$agent_list = $this->input->getGet('agent_list');
		$builder = $this->db->table("cc_user");
		$builder->select("id , contract_number_handling");
		$builder->whereIn("id", [$agent_list]);
		$data = $builder->get()->getResultArray();
		$rs = array('success' => true, 'message' => '', 'data' => $data);
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function upload_file_form(){
		$agent_list = $this->Common_model->get_record_value(" GROUP_CONCAT(agency_id separator '\',\'')", "cms_agency", "deleted_flag IS NULL and agency_name!=''");	
		$data["list_of_agency"] = $this->Common_model->get_record_list("agency_id value, agency_name AS item", "cms_agency", "agency_id in('$agent_list')", "agency_name");
		return view('\App\Modules\AgentMonitoring\Views\Upload_file_form_view', $data);

	}
	function save_file(){
		$file = $this->input->getFile('file');
		$validationRule = [
            'file' => [
                'label' => 'File',
                'rules' => 'uploaded[file]|ext_in[file,xls,xlsx]|max_size[file,2048]',
            ],
        ];

        if (!$this->validate($validationRule)) {
			$rs = array('success' => false, 'message' => 'File must be of type xls or xlsx', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
        } else {
			$originalName = $file->getClientName();
			$uploadPath = ROOTPATH . 'file_upload/';  // pastikan direktori ini ada dan memiliki izin tulis
			$filePath = $uploadPath . $originalName;
			$file->move($uploadPath, $originalName);
			if (DIRECTORY_SEPARATOR == '\\') {
				$filePath = str_replace('/', '\\', $filePath);
			}
			$data_agency_file = [
				'id' => uuid(), // Generate UUID
				'agency_id' => $this->input->getPost('opt-agency-list'),
				'file_name' => $originalName,
				'full_path' => $filePath,
				'upload_time' => date('Y-m-d H:i:s'),
				'upload_by' => session()->get('USER_ID'),
			];
			$this->Agent_monitoring_model->save_agency_upload_file($data_agency_file);
			try {
				$inputFileType = IOFactory::identify($filePath);
				$reader = IOFactory::createReader($inputFileType);
				$spreadsheet = $reader->load($filePath);
			} catch (\Exception $e) {
				return $this->response->setJSON(['error' => 'Error loading file "' . pathinfo($filePath, PATHINFO_BASENAME) . '": ' . $e->getMessage()]);
			}
			$allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        	$arrayCount = count($allDataInSheet);

			$data_batch = [];
			for ($i = 2; $i <= $arrayCount; $i++) {
				// Mengubah format tanggal dari mm/dd/yyyy ke yyyy-mm-dd
				$originalVisitTime = $allDataInSheet[$i]['C'];
				$formattedVisitTime = $this->convertDateFormat($originalVisitTime);

				$originalPtpDate = $allDataInSheet[$i]['H'];
				$formattedPtpDate = $this->convertDateFormat($originalPtpDate);

				$data_batch[] = [
					'no' => $allDataInSheet[$i]['A'],
					'card_number' => $allDataInSheet[$i]['A'],
					'nama_debitur' => $allDataInSheet[$i]['B'],
					'visit_time' => $formattedVisitTime, // Menggunakan tanggal yang sudah diformat
					'agency_code' => $allDataInSheet[$i]['D'],
					'agency_name' => $allDataInSheet[$i]['E'],
					'nama_fc' => $allDataInSheet[$i]['F'],
					'collection_result' => $allDataInSheet[$i]['G'],
					'ptp_date' => $formattedPtpDate, // Menggunakan tanggal yang sudah diformat
					'ptp_amount' => $allDataInSheet[$i]['I'],
					'remarks' => $allDataInSheet[$i]['J'],
					'file_upload_id' => $data_agency_file['id'],
				];
			}
			$return = $this->Agent_monitoring_model->save_activity_from_file($data_batch);
			if($return){
				$cache = session()->get('USER_ID').'_activity_file_list';
				$this->cache->delete($cache);
				$rs = array('success' => true, 'message' => 'Success to save data excel', 'id' => $data_agency_file['id']);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
		}
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
		$data = $this->Agent_monitoring_model->show_activity_by_file($id);
		$header = "<tr><td>Loan No.</td><td>Customer Name</td><td>Visit Date</td><td>Agency ID</td><td>Agency Name</td><td>Field Collector Name</td><td>Visit Result</td><td>PTP Date</td><td>PTP Amount</td><td>Remarks</td></tr>";
		$detail = "";
		foreach($data as $a){
			$detail .= "<tr><td>".$a["no"] ."</td><td>".$a["nama_debitur"] ."</td><td>". $a["visit_time"]."</td><td>". $a["agency_code"]."</td><td>".$a["agency_name"] .
			"</td><td>".$a["nama_fc"] .
			"</td><td>".$a["collection_result"] ."</td><td>".$a["ptp_date"] ."</td><td>".$a["ptp_amount"] ."</td><td>". $a["remarks"]."</td></tr>";
			
		}
		
		$data["table"] = '<table class="table table-striped table-bordered table-hover">'.$header.$detail."</table>";
		return view('\App\Modules\AgentMonitoring\Views\Show_uploaded_file_form_view', $data);
	}
	function upload_file(){
		$id = $this->input->getPost('id');
		$return = $this->Agent_monitoring_model->upload_activity_by_file($id);
		if($return){
			$cache = session()->get('USER_ID').'_activity_file_list';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to upload data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'Already loaded', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
	}

}