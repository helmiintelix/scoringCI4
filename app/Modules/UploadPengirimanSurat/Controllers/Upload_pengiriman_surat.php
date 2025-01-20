<?php 
namespace App\Modules\UploadPengirimanSurat\Controllers;
use App\Modules\UploadPengirimanSurat\Models\Upload_pengiriman_surat_model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use CodeIgniter\Cookie\Cookie;
use DateTime;

class Upload_pengiriman_surat extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Upload_pengiriman_surat_model = new Upload_pengiriman_surat_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\UploadPengirimanSurat\Views\Upload_pengiriman_surat_view', $data);
	}
	function get_pengiriman_surat_file_list(){
		$cache = session()->get('USER_ID').'_pengiriman_surat_file_list';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Upload_pengiriman_surat_model->get_pengiriman_surat_file_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function upload_file_form(){
		return view('\App\Modules\UploadPengirimanSurat\Views\Upload_file_form_view');
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
			$uploadPath = ROOTPATH . 'file_upload/pengiriman_surat/';  // pastikan direktori ini ada dan memiliki izin tulis
			$filePath = $uploadPath . $originalName;
			$file->move($uploadPath, $originalName);
			if (DIRECTORY_SEPARATOR == '\\') {
				$filePath = str_replace('/', '\\', $filePath);
			}
			$data_exclude_file = [
				'id' => uuid(), // Generate UUID
				"tipe_upload" =>'pengiriman_surat',
				'file_name' => $originalName,
				'full_path' => $filePath,
				'upload_time' => date('Y-m-d H:i:s'),
				'upload_by' => session()->get('USER_ID'),
			];
			$this->Upload_pengiriman_surat_model->save_pengiriman_surat_upload_file($data_exclude_file);
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
				$originalTanggalKirim = $allDataInSheet[$i]['D'];
				$formattedTanggalKirim = $this->convertDateFormat($originalTanggalKirim);

				$originalTanggalTerima = $allDataInSheet[$i]['H'];
				$formattedTanggalTerima = $this->convertDateFormat($originalTanggalTerima);
				
				//jenis_letter belum tau ambil field dari apa
				$data_batch[] = [	
					'jenis_letter' => "",
					'account_no' => $allDataInSheet[$i]['A'],
					'customer_name' => $allDataInSheet[$i]['B'],
					'letter_id' => $allDataInSheet[$i]['C'],
					'tgl_kirim' => $formattedTanggalKirim, // Menggunakan tanggal yang sudah diformat
					'nama_kurir' => $allDataInSheet[$i]['E'],
					'no_resi' => $allDataInSheet[$i]['F'],
					'nama_penerima' => $allDataInSheet[$i]['G'],
					'tgl_penerima' => $formattedTanggalTerima, // Menggunakan tanggal yang sudah diformat
					'remark' => $allDataInSheet[$i]['I'],
					'file_upload_id' => $data_exclude_file['id'],
				];
			}
			$return = $this->Upload_pengiriman_surat_model->save_pengiriman_surat_from_file($data_batch);
			if($return){
				$cache = session()->get('USER_ID').'_pengiriman_surat_file_list';
				$this->cache->delete($cache);
				$rs = array('success' => true, 'message' => 'Success to save data excel', 'id' => $data_exclude_file['id']);
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
		$data = $this->Upload_pengiriman_surat_model->show_activity_by_file($id);
		$header = "<tr><td>No. Pinjaman</td><td>Nama Debitur</td><td>Letter ID</td><td>Tanggal Kirim</td><td>Nama Kurir</td><td>No Resi</td><td>Nama Penerima</td><td>Tanggal Terima</td><td>Remarks</td></tr>";
		$detail = "";
				foreach($data as $a){
			$detail .= "<tr><td>".$a["account_no"] ."</td><td>".$a["customer_name"] ."</td><td>".$a["letter_id"] ."</td><td>".$a["tgl_kirim"] ."</td><td>".$a["nama_kurir"] ."</td><td>".$a["no_resi"] ."</td><td>".$a["nama_penerima"] ."</td><td>".$a["tgl_penerima"] ."</td><td>". $a["remark"]."</td></tr>";
			
		}
		
		$data["table"] = '<table class="table table-striped table-bordered table-hover">'.$header.$detail."</table>";
		// dd($data);
		return view('\App\Modules\UploadPengirimanSurat\Views\Show_uploaded_file_form_view', $data);
	}
	function upload_file(){
		$id = $this->input->getPost('id');
		$return = $this->Upload_pengiriman_surat_model->upload_activity_by_file($id);
		if($return){
			$cache = session()->get('USER_ID').'_pengiriman_surat_file_list';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to upload data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'Already loaded', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
	}

}