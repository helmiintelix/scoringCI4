<?php 
namespace App\Modules\UploadAccountData\Controllers;
use App\Modules\UploadAccountData\Models\UploadAccountData_model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use CodeIgniter\Cookie\Cookie;

class UploadAccountData extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->UploadAccountData_model = new UploadAccountData_model();
	}

	function index(){
		return view('\App\Modules\UploadAccountData\Views\UploadAccountData_view');
	}

	function get_upload_data(){
		$data = $this->UploadAccountData_model->get_upload_data();
	    if ($data) {
			$cacheKey = session()->get('USER_ID') . '_get_upload_data';
			$this->cache->delete($cacheKey);
			$this->cache->save($cacheKey, json_encode($data), env('TIMECACHE_1'));
	
			$rs = ['success' => true, 'message' => 'Success to apply filter', 'data' => $data];
		} else {
			$rs = ['success' => false, 'message' => 'failed', 'data' => null];
		}
	
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

    function upload_file_form(){
		return view('\App\Modules\UploadAccountData\Views\UploadAccountData_form_view');
    }

    function save_file(){
		$file = $this->input->getFile('file');
		$validationRule = [
            'file' => [
                'label' => 'File',
                'rules' => 'uploaded[file]|ext_in[file,xls,xlsx]',
            ],
        ];

        if (!$this->validate($validationRule)) {
			$rs = array('success' => false, 'message' => 'File must be of type xls or xlsx', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
        } else {
			$originalName = $file->getClientName();
			$uploadPath = ROOTPATH . 'writable/uploads/account_data/';  // pastikan direktori ini ada dan memiliki izin tulis
			$filePath = $uploadPath . $originalName;
			$file->move($uploadPath, $originalName);
			if (DIRECTORY_SEPARATOR == '\\') {
				$filePath = str_replace('/', '\\', $filePath);
			}

            $data_exclude_file = [
                'id' => uuid(), // Generate UUID
                'fileName' => $originalName,
                'fullPath' => $filePath,
                'createdTime' => date('Y-m-d H:i:s'),
                'createdBy' => session()->get('USER_ID'),
            ];

            // Insert data into upload_account_data table
            $this->db->table('upload_account_data')->insert($data_exclude_file);

			try {
				$inputFileType = IOFactory::identify($filePath);
				$reader = IOFactory::createReader($inputFileType);
				$spreadsheet = $reader->load($filePath);
			} catch (\Exception $e) {
				return $this->response->setJSON(['error' => 'Error loading file "' . pathinfo($filePath, PATHINFO_BASENAME) . '": ' . $e->getMessage()]);
			}
			$allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
			// var_dump($allDataInSheet);die;
        	$arrayCount = count($allDataInSheet);
			$data_batch = [];
			$data_batch_phone_hp = [];
			$data_batch_phone_home = [];
			$data_batch_phone_office = [];
			$data_batch_address_cur = [];
			$data_batch_address_home = [];
			$data_batch_address_off = [];
			for ($i = 2; $i <= $arrayCount; $i++) {
				
				//jenis_letter belum tau ambil field dari apa
				$data_batch[] = [	
					"CM_CUSTOMER_NMBR" => $allDataInSheet[$i]["A"], 
					"CM_CARD_NMBR" => $allDataInSheet[$i]["B"], 
					"CM_TYPE" => $allDataInSheet[$i]["C"], 
					"CM_CRLIMIT" => $allDataInSheet[$i]["D"], 
					"CM_DTE_OPENED" => $allDataInSheet[$i]["E"], 
					"CM_TENOR" => $allDataInSheet[$i]["F"], 
					"CM_DTE_LIQUIDATE" => $allDataInSheet[$i]["G"], 
					"CM_INSTALLMENT_AMOUNT" => $allDataInSheet[$i]["H"], 
					"CM_CYCLE" => $allDataInSheet[$i]["I"], 
					"CM_INSTALLMENT_NO" => $allDataInSheet[$i]["J"], 
					"DPD" => $allDataInSheet[$i]["K"], 
					"CM_COLLECTIBILITY" => $allDataInSheet[$i]["L"], 
					"CM_OS_BALANCE" => $allDataInSheet[$i]["M"], 
					"CM_DTE_LST_PYMT" => $allDataInSheet[$i]["N"], 
					"CM_LST_PYMT_AMNT" => $allDataInSheet[$i]["O"], 
					"CM_DTE_PYMT_DUE" => $allDataInSheet[$i]["O"], 
					"AGENT_ID" => $allDataInSheet[$i]["P"], 
					"CM_BUCKET" => $allDataInSheet[$i]["Q"], 
					"CR_NAME_1" => $allDataInSheet[$i]["R"], 
					"CR_HANDPHONE" => $allDataInSheet[$i]["S"], 
					"CR_HOME_PHONE" => $allDataInSheet[$i]["T"], 
					"CR_OFFICE_PHONE" => $allDataInSheet[$i]["U"],
					'file_upload_id' => $data_exclude_file['id']
				];

				$data_batch_phone_hp[] = [
					"CM_CARD_NMBR" => $allDataInSheet[$i]["B"], 
					"PHONE_TYPE" => 'hp1', 
					"CONTENT" => $allDataInSheet[$i]["S"], 
					"PRIORITY" => '1',
					"PERCENTAGE" => '100',
					'file_upload_id' => $data_exclude_file['id']

				]; 
				$data_batch_phone_home[] = [
					"CM_CARD_NMBR" => $allDataInSheet[$i]["B"], 
					"PHONE_TYPE" => 'home1', 
					"CONTENT" => $allDataInSheet[$i]["T"], 
					"PRIORITY" => '2',
					"PERCENTAGE" => '100',
					'file_upload_id' => $data_exclude_file['id']

				]; 
				$data_batch_phone_office[] = [
					"CM_CARD_NMBR" => $allDataInSheet[$i]["B"], 
					"PHONE_TYPE" => 'Of1', 
					"CONTENT" => $allDataInSheet[$i]["U"],
					"PRIORITY" => '3',
					"PERCENTAGE" => '100',
					'file_upload_id' => $data_exclude_file['id']

				]; 

				$data_batch_address_cur[] = [
					"CM_CARD_NMBR" => $allDataInSheet[$i]["B"], 
					"CR_NAME_1" => $allDataInSheet[$i]["S"], 
					"ADDRESS_TYPE" => "Current",
					"CM_PROVINCE" => $allDataInSheet[$i]["V"], 
					"CM_CITY" => $allDataInSheet[$i]["W"], 
					"CM_KEC" => $allDataInSheet[$i]["X"], 
					"CM_KEL" => $allDataInSheet[$i]["Y"], 
					"ADDRESS" => $allDataInSheet[$i]["Z"], 
					"ZIP_CODE" => $allDataInSheet[$i]["AA"], 
					"LONGITUDE" => $allDataInSheet[$i]["AB"], 
					"LONGITUDE" => $allDataInSheet[$i]["AC"], 
					"PRIORITY" => '3',
					'file_upload_id' => $data_exclude_file['id']

				];
				$data_batch_address_home[] = [
					"CM_CARD_NMBR" => $allDataInSheet[$i]["B"], 
					"CR_NAME_1" => $allDataInSheet[$i]["S"], 
					"ADDRESS_TYPE" => "Home",
					"CM_PROVINCE" => $allDataInSheet[$i]["AD"], 
					"CM_CITY" => $allDataInSheet[$i]["AE"], 
					"CM_KEC" => $allDataInSheet[$i]["AF"], 
					"CM_KEL" => $allDataInSheet[$i]["AG"], 
					"ADDRESS" => $allDataInSheet[$i]["AH"], 
					"ZIP_CODE" => $allDataInSheet[$i]["AI"], 
					"LATITUDE" => $allDataInSheet[$i]["AJ"], 
					"LONGITUDE" => $allDataInSheet[$i]["AK"], 
					"PRIORITY" => '3',
					'file_upload_id' => $data_exclude_file['id']

				];
				$data_batch_address_off[] = [
					"CM_CARD_NMBR" => $allDataInSheet[$i]["B"], 
					"CR_NAME_1" => $allDataInSheet[$i]["S"], 
					"ADDRESS_TYPE" => "Office",
					"CM_PROVINCE" => $allDataInSheet[$i]["AL"], 
					"CM_CITY" => $allDataInSheet[$i]["AM"], 
					"CM_KEC" => $allDataInSheet[$i]["AN"], 
					"CM_KEL" => $allDataInSheet[$i]["AO"], 
					"ADDRESS" => $allDataInSheet[$i]["AP"], 
					"ZIP_CODE" => $allDataInSheet[$i]["AQ"], 
					"LATITUDE" => $allDataInSheet[$i]["AR"], 
					"LONGITUDE" => $allDataInSheet[$i]["AS"], 
					"PRIORITY" => '3',
					'file_upload_id' => $data_exclude_file['id']

				];
			}
			// var_dump($data_batch);die;

			$return = $this->db->table('cpcrd_new_upload_temp')->insertBatch($data_batch);
			
			$return = $this->db->table('cms_predictive_phone_upload_tmp')->insertBatch($data_batch_phone_hp);
			$return = $this->db->table('cms_predictive_phone_upload_tmp')->insertBatch($data_batch_phone_home);
			$return = $this->db->table('cms_predictive_phone_upload_tmp')->insertBatch($data_batch_phone_office);
			
			$return = $this->db->table('cms_predictive_address_upload_tmp')->insertBatch($data_batch_address_cur);
			$return = $this->db->table('cms_predictive_address_upload_tmp')->insertBatch($data_batch_address_home);
			$return = $this->db->table('cms_predictive_address_upload_tmp')->insertBatch($data_batch_address_off);

			if($return){
				$cache = session()->get('USER_ID').'_get_upload_data';
				$this->cache->delete($cache);
				$rs = array('success' => true, 'message' => 'Success to save data excel, waiting approval!', 'id' => $data_exclude_file['id']);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
		}
	}

	function show_uploaded_file_form(){
		
		$id = $this->request->getGet('id');

		return view('\App\Modules\UploadAccountData\Views\ShowUploadedFileForm_view', ['id' => $id]);
	}

	function get_view_data(){
		$uploadId = $this->request->getGet('uploadId');

		$data = $this->UploadAccountData_model->get_view_data($uploadId);
	    if ($data) {
			$rs = ['success' => true, 'message' => 'Success to apply filter', 'data' => $data];
		} else {
			$rs = ['success' => false, 'message' => 'failed', 'data' => null];
		}
		return $this->response->setStatusCode(200)->setJSON($rs);

	}

	function approval(){
		return view('\App\Modules\UploadAccountData\Views\ApprovalAccountData_view');
	}
	
	function get_upload_data_approval(){
		$data = $this->UploadAccountData_model->get_upload_data_approval();
	    if ($data) {
		
			$rs = ['success' => true, 'message' => 'Success to apply filter', 'data' => $data];
		} else {
			$rs = ['success' => false, 'message' => 'failed', 'data' => null];
		}
	
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function approve_file(){
		$uploadId = $this->request->getPost('id');

		$truncate = $this->db->table('cpcrd_new')->truncate();
		$truncate = $this->db->table('cms_predictive_address')->truncate();
		$truncate = $this->db->table('cms_predictive_phone')->truncate();

		if ($truncate) {
			$query = "INSERT INTO cpcrd_new (CM_CUSTOMER_NMBR, CM_CARD_NMBR, CM_TYPE, CM_CRLIMIT, CM_CREDIT_LINE, CM_DTE_OPENED, CM_INTEREST, CM_TENOR, FLD_DATE_5, MOB, CM_DTE_LIQUIDATE, CM_CURRENCY_CODE, CM_HOLD_AMOUNT, CM_AO_CODE, CM_OFFICER_NAME, CM_SECTOR_CODE, CM_SECTOR_DESC, CM_DTE_PK, CM_CARD_EXPIR_DTE, CM_INSTALLMENT_AMOUNT, CM_INSTL_BAL, CM_INTR_PER_DIEM, CM_INSTL_LIMIT, CM_AMNT_OUTST_INSTL, CM_TOTALDUE, CM_CYCLE, CM_INSTALLMENT_NO, CM_DTE_PYMT_DUE, DPD, CM_BUCKET_PROGRAM, FLD_CHAR_2, CM_STATUS, CM_COLLECTIBILITY, CM_BLOCK_CODE, CM_DTE_BLOCK_CODE, CM_OS_BALANCE, CM_OS_PRINCIPAL, CM_OS_INTEREST, CM_RTL_MISC_FEES, CM_TOTAL_OS_AR, CM_CHGOFF_STATUS_FLAG, CM_DTE_CHGOFF_STAT_CHANGE, SUM_WO_BALANCE, CM_CHGOFF_PRICIPLE, CM_ZIP_REC, CM_DELQ_COUNTER1, CM_DELQ_COUNTER2, CM_DELQ_COUNTER3, CM_DELQ_COUNTER4, CM_DELQ_COUNTER5, CM_DELQ_COUNTER6, CM_DELQ_COUNTER7, CM_DELQ_COUNTER8, CM_DELQ_COUNTER9, CM_CURR_DUE, CM_PAST_DUE, CM_30DAYS_DELQ, CM_60DAYS_DELQ, CM_90DAYS_DELQ, CM_120DAYS_DELQ, CM_150DAYS_DELQ, CM_180DAYS_DELQ, CM_210DAYS_DELQ, CM_RESTRUCTURE_FLAG, CM_DTE_RESTRUCTURE, CM_DTE_LST_PYMT, CM_STATUS_DESC, CM_LST_PYMT_AMNT, CM_PAID_PRICIPAL, CM_PAID_INTEREST, CM_PAID_CHARGE, CM_SOURCE_CODE, CR_ACCT_NBR, AGENT_ID, CM_BUCKET)
					  SELECT CM_CUSTOMER_NMBR, CM_CARD_NMBR, CM_TYPE, CM_CRLIMIT, CM_CREDIT_LINE, CM_DTE_OPENED, CM_INTEREST, CM_TENOR, FLD_DATE_5, MOB, CM_DTE_LIQUIDATE, CM_CURRENCY_CODE, CM_HOLD_AMOUNT, CM_AO_CODE, CM_OFFICER_NAME, CM_SECTOR_CODE, CM_SECTOR_DESC, CM_DTE_PK, CM_CARD_EXPIR_DTE, CM_INSTALLMENT_AMOUNT, CM_INSTL_BAL, CM_INTR_PER_DIEM, CM_INSTL_LIMIT, CM_AMNT_OUTST_INSTL, CM_TOTALDUE, CM_CYCLE, CM_INSTALLMENT_NO, CM_DTE_PYMT_DUE, DPD, CM_BUCKET_PROGRAM, FLD_CHAR_2, CM_STATUS, CM_COLLECTIBILITY, CM_BLOCK_CODE, CM_DTE_BLOCK_CODE, CM_OS_BALANCE, CM_OS_PRINCIPAL, CM_OS_INTEREST, CM_RTL_MISC_FEES, CM_TOTAL_OS_AR, CM_CHGOFF_STATUS_FLAG, CM_DTE_CHGOFF_STAT_CHANGE, SUM_WO_BALANCE, CM_CHGOFF_PRICIPLE, CM_ZIP_REC, CM_DELQ_COUNTER1, CM_DELQ_COUNTER2, CM_DELQ_COUNTER3, CM_DELQ_COUNTER4, CM_DELQ_COUNTER5, CM_DELQ_COUNTER6, CM_DELQ_COUNTER7, CM_DELQ_COUNTER8, CM_DELQ_COUNTER9, CM_CURR_DUE, CM_PAST_DUE, CM_30DAYS_DELQ, CM_60DAYS_DELQ, CM_90DAYS_DELQ, CM_120DAYS_DELQ, CM_150DAYS_DELQ, CM_180DAYS_DELQ, CM_210DAYS_DELQ, CM_RESTRUCTURE_FLAG, CM_DTE_RESTRUCTURE, CM_DTE_LST_PYMT, CM_STATUS_DESC, CM_LST_PYMT_AMNT, CM_PAID_PRICIPAL, CM_PAID_INTEREST, CM_PAID_CHARGE, CM_SOURCE_CODE, CR_ACCT_NBR, AGENT_ID, CM_BUCKET
					  FROM cpcrd_new_upload_temp
					  WHERE file_upload_id = ?";
			$this->db->query($query, [$uploadId]);
			
			$query = "INSERT INTO cms_predictive_address (CM_CARD_NMBR,CR_NAME_1,ADDRESS_TYPE,CM_PROVINCE,CM_CITY,CM_KEC,CM_KEL,ADDRESS,ZIP_CODE,LATITUDE,LONGITUDE,PRIORITY)
						SELECT CM_CARD_NMBR,CR_NAME_1,ADDRESS_TYPE,CM_PROVINCE,CM_CITY,CM_KEC,CM_KEL,ADDRESS,ZIP_CODE,LATITUDE,LONGITUDE,PRIORITY FROM cms_predictive_address_upload_tmp
						WHERE file_upload_id = ?";
			$this->db->query($query, [$uploadId]);
			
			$query = "INSERT INTO cms_predictive_phone (CM_CARD_NMBR,PHONE_TYPE,CONTENT,PRIORITY,PERCENTAGE)
						SELECT CM_CARD_NMBR,PHONE_TYPE,CONTENT,PRIORITY,PERCENTAGE 
						FROM cms_predictive_phone_upload_tmp
						WHERE file_upload_id = ?";
			$this->db->query($query, [$uploadId]);

			$this->db->table('upload_account_data')
				->where('id', $uploadId)
				->update(['approvedBy' => session()->get('USER_ID'), 'approvedTime' => date('Y-m-d H:i:s')]);

			$rs = ['success' => true, 'message' => 'File approved and data moved to cpcrd_new'];
			return $this->response->setStatusCode(200)->setJSON($rs);
		} else {
			$rs = ['success' => false, 'message' => 'Failed to truncate cpcrd_new'];
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	
	function reject_file(){
		$uploadId = $this->request->getPost('id');

		$fileRow = $this->db->table('upload_account_data')->select('fullPath')->where('id', $uploadId)->get()->getRow();
		$filePath = $fileRow ? $fileRow->fullPath : null;
		$delete = $this->db->table('cpcrd_new_upload_temp')->where('file_upload_id', $uploadId)->delete();
		$delete= $this->db->table('upload_account_data')->where('id', $uploadId)->delete();
		if ($delete) {
			if ($filePath && file_exists($filePath)) {
				unlink($filePath);
			}
			$rs = ['success' => true, 'message' => 'File rejected and data deleted'];
			return $this->response->setStatusCode(200)->setJSON($rs);
		} else {
			$rs = ['success' => false, 'message' => 'Failed to delete data'];
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
	}
}