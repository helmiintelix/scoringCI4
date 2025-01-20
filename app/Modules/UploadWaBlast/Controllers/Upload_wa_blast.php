<?php 
namespace App\Modules\UploadWaBlast\Controllers;
use App\Modules\UploadWaBlast\Models\Upload_wa_blast_model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use CodeIgniter\Cookie\Cookie;
use DateTime;

class Upload_wa_blast extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Upload_wa_blast_model = new Upload_wa_blast_model();
	}

	function view_upload_wa_blast_list(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\UploadWaBlast\Views\Upload_wa_blast_view', $data);
	}
	function get_pengiriman_surat_file_list(){

		$data = $this->Upload_wa_blast_model->get_pengiriman_surat_file_list();
        $rs = array('success' => true, 'message' => '', 'data' => $data);
        return $this->response->setStatusCode(200)->setJSON($rs);

		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function upload_file_form(){
		$data['list_template'] = array(''=>'Select Param')+$this->Common_model->get_record_list('id as value, template_name as item', 'cms_wa_template', 'is_active = "1"', 'template_name asc');
		return view('\App\Modules\UploadWaBlast\Views\Upload_file_form_view',$data);
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

	function save_file(){
		$file = $this->input->getFile('file');
		$template = $this->Common_model->get_record_values("*", "cms_wa_template", "id='".$this->input->getPost('opt_wa_template')."'");
		$note = $this->input->getPost('txt_wa_template_template_design');
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
			$uploadPath = ROOTPATH . 'file_upload/wa_blast_upload/';  // pastikan direktori ini ada dan memiliki izin tulis

			// Cek apakah direktori sudah ada, jika tidak, buat direktori
			if (!is_dir($uploadPath)) {
			    mkdir($uploadPath, 0755, true); // 0755 adalah izin untuk direktori, true untuk membuat direktori secara rekursif
			}

			$filePath = $uploadPath . $originalName;
			$file->move($uploadPath, $originalName);

			if (DIRECTORY_SEPARATOR == '\\') {
			    $filePath = str_replace('/', '\\', $filePath);
			}

			$id=uuid();

			
			try {
				$inputFileType = IOFactory::identify($filePath);
				$reader = IOFactory::createReader($inputFileType);
				$spreadsheet = $reader->load($filePath);
			} catch (\Exception $e) {
				return $this->response->setJSON(['error' => 'Error loading file "' . pathinfo($filePath, PATHINFO_BASENAME) . '": ' . $e->getMessage()]);
			}
			$allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        	$arrayCount = count($allDataInSheet)-1;

			$data_batch = [];
			$count_failed = 0;

			foreach ($allDataInSheet as $key => $value) {
				if ($key==1) {
					continue;
				}

				//untuk GET message utuh
				$arrTemp[]=$this->get_message_content($value['A'],$template['template_replace'],$template['parameter']);
				
				$handphone = $this->Common_model->get_record_value("CR_HANDPHONE", 'cpcrd_new', 'cm_card_nmbr="'.$value['A'].'"');

				//source jika depan 62
		        $position = strpos($handphone, "62", 0);
		        if ($position === 0)
		        {
		            $phone_source = $handphone;
		        }
		        else
		        {
		            //source jika depan 08
		            $position=strpos($handphone, "08", 0);
		            if ($position===0) {
		                $phone_source   = "62". ltrim($handphone, "0");    
		            }else{
		                //source ketika phone number invalid format 
		                $phone_source   = $handphone;
		            } 
		        }

		        //file jika depan 62
		        $position = strpos($value['B'], "62", 0);
		        if ($position === 0)
		        {
		            $phone_file = $value['B'];
		        }
		        else
		        {
		            //file jika depan 08
		            $position=strpos($value['B'], "08", 0);
		            if ($position===0) {
		                $phone_file   = "62". ltrim($value['B'], "0");    
		            }else{
		                //file ketika phone number invalid format 
		                $phone_file   = $value['B'];
		            } 
		        }

				if ($phone_source!=$phone_file) {
					$count_failed++;
					$arr = array(
						'id' => uuid(),
						'upload_id' => $id,
						'mask_name' => $template['id'],
						'file_name' => $originalName,
						'template_id' => $template['template_id'],
						'message' => $arrTemp[0]['message'],
						'json_data' => $arrTemp[0]['data'],
						'created_by' => session()->get('USER_ID'),
						'status' => 'failed - phone',
						'created_time' => date('Y-m-d H:i:s'),
						'account_number' => $value['A'],
						'phone_number' => $phone_source
					);
					$this->Upload_wa_blast_model->save_wa_blast_upload_detail($arr);
				}else{

					$check_exclude=$this->Common_model->get_record_value("ACCOUNT_TAGGING", 'cpcrd_new', 'cm_card_nmbr="'.$value['A'].'"');

					if (!empty($check_exclude)) {
						$count_failed++;
						$arr = array(
							'id' => uuid(),
							'upload_id' => $id,
							'mask_name' => $template['id'],
							'file_name' => $originalName,
							'template_id' => $template['template_id'],
							'message' => $arrTemp[0]['message'],
							'json_data' => $arrTemp[0]['data'],
							'created_by' => session()->get('USER_ID'),
							'status' => 'failed - exclude',
							'created_time' => date('Y-m-d H:i:s'),
							'account_number' => $value['A'],
							'phone_number' => $phone_source
						);
						$this->Upload_wa_blast_model->save_wa_blast_upload_detail($arr);
					}else{
						$arr = array(
							'id' => uuid(),
							'upload_id' => $id,
							'mask_name' => $template['id'],
							'file_name' => $originalName,
							'template_id' => $template['template_id'],
							'message' => $arrTemp[0]['message'],
							'json_data' => $arrTemp[0]['data'],
							'created_by' => session()->get('USER_ID'),
							'status' => 'success',
							'created_time' => date('Y-m-d H:i:s'),
							'account_number' => $value['A'],
							'phone_number' => $phone_source
						);
						$this->Upload_wa_blast_model->save_wa_blast_upload_detail($arr);
					}	
				}	
				
			}

			$data_exclude_file = [
				'id' => $id,
				'file_name' => $originalName,
				'mask_name' => $template['id'],
				'template_name' => $template['template_name'],
				'total_data'=>$arrayCount,
            	'failed_data'=>$count_failed,
            	'success_data'=>$arrayCount-$count_failed,
				'notes' => $note,
				'status' => 'NEED APPROVAL',
				'created_by' => session()->get('USER_ID'),
				'created_time' =>date('Y-m-d H:i:s')
			];
			
			$return = $this->Upload_wa_blast_model->save_wa_blast_upload($data_exclude_file);
			if($return){
				$cache = session()->get('USER_ID').'_wa_blast';
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
		$data = $this->Upload_wa_blast_model->show_activity_by_file($id);
		$header = "<tr><td>No</td><td>Account Number</td><td>Phone Number</td><td>Status Upload</td><td>Message</td><td>Status Approval</td></tr>";
		$detail = "";

		foreach ($data as $key => $value) {
			$detail .= "<tr><td>".($key+1)."</td><td>".$value['account_number']."</td><td>".$value['phone_number']."</td><td>".$value['status']."</td><td>".$value['message']."</td><td>".$value['status_description']."</td></tr>";
		}
		
		$data["table"] = '<table class="table table-striped table-bordered table-hover">'.$header.$detail."</table>";
		// dd($data);
		return view('\App\Modules\UploadWaBlast\Views\Show_uploaded_file_form_view', $data);
	}
	function upload_file(){
		$id = $this->input->getPost('id');
		$return = $this->Upload_wa_blast_model->upload_activity_by_file($id);
		if($return){
			$cache = session()->get('USER_ID').'_wa_blast';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to upload data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'Already loaded', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
	}

}