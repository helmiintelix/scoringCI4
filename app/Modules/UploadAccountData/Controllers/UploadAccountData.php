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
        	$arrayCount = count($allDataInSheet);
			$data_batch = [];
			for ($i = 2; $i <= $arrayCount; $i++) {
				
				//jenis_letter belum tau ambil field dari apa
				$data_batch[] = [	
					"CM_CUSTOMER_NMBR" => $allDataInSheet[$i]["A"],
					"CM_CARD_NMBR" => $allDataInSheet[$i]["B"],
					"CM_TYPE" => $allDataInSheet[$i]["C"],
					"CM_CRLIMIT" => $allDataInSheet[$i]["D"],
					"CM_CREDIT_LINE" => $allDataInSheet[$i]["E"],
					"CM_DTE_OPENED" => $allDataInSheet[$i]["F"],
					"CM_INTEREST" => $allDataInSheet[$i]["G"],
					"CM_TENOR" => $allDataInSheet[$i]["H"],
					"FLD_DATE_5" => $allDataInSheet[$i]["I"],
					"MOB" => $allDataInSheet[$i]["J"],
					"CM_DTE_LIQUIDATE" => $allDataInSheet[$i]["K"],
					"CM_CURRENCY_CODE" => $allDataInSheet[$i]["L"],
					"CM_HOLD_AMOUNT" => $allDataInSheet[$i]["M"],
					"CM_AO_CODE" => $allDataInSheet[$i]["N"],
					"CM_OFFICER_NAME" => $allDataInSheet[$i]["O"],
					"CM_SECTOR_CODE" => $allDataInSheet[$i]["P"],
					"CM_SECTOR_DESC" => $allDataInSheet[$i]["Q"],
					"CM_DTE_PK" => $allDataInSheet[$i]["R"],
					"CM_CARD_EXPIR_DTE" => $allDataInSheet[$i]["S"],
					"CM_INSTALLMENT_AMOUNT" => $allDataInSheet[$i]["T"],
					"CM_INSTL_BAL" => $allDataInSheet[$i]["U"],
					"CM_INTR_PER_DIEM" => $allDataInSheet[$i]["V"],
					"CM_INSTL_LIMIT" => $allDataInSheet[$i]["W"],
					"CM_AMNT_OUTST_INSTL" => $allDataInSheet[$i]["X"],
					"CM_TOTALDUE" => $allDataInSheet[$i]["Y"],
					"CM_CYCLE" => $allDataInSheet[$i]["Z"],
					"CM_INSTALLMENT_NO" => $allDataInSheet[$i]["AA"],
					"CM_DTE_PYMT_DUE" => $allDataInSheet[$i]["AB"],
					"DPD" => $allDataInSheet[$i]["AC"],
					"CM_BUCKET_PROGRAM" => $allDataInSheet[$i]["AD"],
					"FLD_CHAR_2" => $allDataInSheet[$i]["AE"],
					"CM_STATUS" => $allDataInSheet[$i]["AF"],
					"CM_COLLECTIBILITY" => $allDataInSheet[$i]["AG"],
					"CM_BLOCK_CODE" => $allDataInSheet[$i]["AH"],
					"CM_DTE_BLOCK_CODE" => $allDataInSheet[$i]["AI"],
					"CM_OS_BALANCE" => $allDataInSheet[$i]["AJ"],
					"CM_OS_PRINCIPAL" => $allDataInSheet[$i]["AK"],
					"CM_OS_INTEREST" => $allDataInSheet[$i]["AL"],
					"CM_RTL_MISC_FEES" => $allDataInSheet[$i]["AM"],
					"CM_TOTAL_OS_AR" => $allDataInSheet[$i]["AN"],
					"CM_CHGOFF_STATUS_FLAG" => $allDataInSheet[$i]["AO"],
					"CM_DTE_CHGOFF_STAT_CHANGE" => $allDataInSheet[$i]["AP"],
					"SUM_WO_BALANCE" => $allDataInSheet[$i]["AQ"],
					"CM_CHGOFF_PRICIPLE" => $allDataInSheet[$i]["AR"],
					"CM_ZIP_REC" => $allDataInSheet[$i]["AS"],
					"CM_DELQ_COUNTER1" => $allDataInSheet[$i]["AT"],
					"CM_DELQ_COUNTER2" => $allDataInSheet[$i]["AU"],
					"CM_DELQ_COUNTER3" => $allDataInSheet[$i]["AV"],
					"CM_DELQ_COUNTER4" => $allDataInSheet[$i]["AW"],
					"CM_DELQ_COUNTER5" => $allDataInSheet[$i]["AX"],
					"CM_DELQ_COUNTER6" => $allDataInSheet[$i]["AY"],
					"CM_DELQ_COUNTER7" => $allDataInSheet[$i]["AZ"],
					"CM_DELQ_COUNTER8" => $allDataInSheet[$i]["BA"],
					"CM_DELQ_COUNTER9" => $allDataInSheet[$i]["BB"],
					"CM_CURR_DUE" => $allDataInSheet[$i]["BC"],
					"CM_PAST_DUE" => $allDataInSheet[$i]["BD"],
					"CM_30DAYS_DELQ" => $allDataInSheet[$i]["BE"],
					"CM_60DAYS_DELQ" => $allDataInSheet[$i]["BF"],
					"CM_90DAYS_DELQ" => $allDataInSheet[$i]["BG"],
					"CM_120DAYS_DELQ" => $allDataInSheet[$i]["BH"],
					"CM_150DAYS_DELQ" => $allDataInSheet[$i]["BI"],
					"CM_180DAYS_DELQ" => $allDataInSheet[$i]["BJ"],
					"CM_210DAYS_DELQ" => $allDataInSheet[$i]["BK"],
					"CM_RESTRUCTURE_FLAG" => $allDataInSheet[$i]["BL"],
					"CM_DTE_RESTRUCTURE" => $allDataInSheet[$i]["BM"],
					"CM_DTE_LST_PYMT" => $allDataInSheet[$i]["BN"],
					"CM_STATUS_DESC" => $allDataInSheet[$i]["BO"],
					"CM_LST_PYMT_AMNT" => $allDataInSheet[$i]["BP"],
					"CM_PAID_PRICIPAL" => $allDataInSheet[$i]["BQ"],
					"CM_PAID_INTEREST" => $allDataInSheet[$i]["BR"],
					"CM_PAID_CHARGE" => $allDataInSheet[$i]["BS"],
					"CM_SOURCE_CODE" => $allDataInSheet[$i]["BT"],
					"CR_ACCT_NBR" => $allDataInSheet[$i]["BU"], 
					'file_upload_id' => $data_exclude_file['id'],
				];
			}
			// var_dump($data_batch);die;

			$return = $this->db->table('cpcrd_new_upload_temp')->insertBatch($data_batch);

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

		if ($truncate) {
			$query = "INSERT INTO cpcrd_new (CM_CUSTOMER_NMBR, CM_CARD_NMBR, CM_TYPE, CM_CRLIMIT, CM_CREDIT_LINE, CM_DTE_OPENED, CM_INTEREST, CM_TENOR, FLD_DATE_5, MOB, CM_DTE_LIQUIDATE, CM_CURRENCY_CODE, CM_HOLD_AMOUNT, CM_AO_CODE, CM_OFFICER_NAME, CM_SECTOR_CODE, CM_SECTOR_DESC, CM_DTE_PK, CM_CARD_EXPIR_DTE, CM_INSTALLMENT_AMOUNT, CM_INSTL_BAL, CM_INTR_PER_DIEM, CM_INSTL_LIMIT, CM_AMNT_OUTST_INSTL, CM_TOTALDUE, CM_CYCLE, CM_INSTALLMENT_NO, CM_DTE_PYMT_DUE, DPD, CM_BUCKET_PROGRAM, FLD_CHAR_2, CM_STATUS, CM_COLLECTIBILITY, CM_BLOCK_CODE, CM_DTE_BLOCK_CODE, CM_OS_BALANCE, CM_OS_PRINCIPAL, CM_OS_INTEREST, CM_RTL_MISC_FEES, CM_TOTAL_OS_AR, CM_CHGOFF_STATUS_FLAG, CM_DTE_CHGOFF_STAT_CHANGE, SUM_WO_BALANCE, CM_CHGOFF_PRICIPLE, CM_ZIP_REC, CM_DELQ_COUNTER1, CM_DELQ_COUNTER2, CM_DELQ_COUNTER3, CM_DELQ_COUNTER4, CM_DELQ_COUNTER5, CM_DELQ_COUNTER6, CM_DELQ_COUNTER7, CM_DELQ_COUNTER8, CM_DELQ_COUNTER9, CM_CURR_DUE, CM_PAST_DUE, CM_30DAYS_DELQ, CM_60DAYS_DELQ, CM_90DAYS_DELQ, CM_120DAYS_DELQ, CM_150DAYS_DELQ, CM_180DAYS_DELQ, CM_210DAYS_DELQ, CM_RESTRUCTURE_FLAG, CM_DTE_RESTRUCTURE, CM_DTE_LST_PYMT, CM_STATUS_DESC, CM_LST_PYMT_AMNT, CM_PAID_PRICIPAL, CM_PAID_INTEREST, CM_PAID_CHARGE, CM_SOURCE_CODE, CR_ACCT_NBR)
					  SELECT CM_CUSTOMER_NMBR, CM_CARD_NMBR, CM_TYPE, CM_CRLIMIT, CM_CREDIT_LINE, CM_DTE_OPENED, CM_INTEREST, CM_TENOR, FLD_DATE_5, MOB, CM_DTE_LIQUIDATE, CM_CURRENCY_CODE, CM_HOLD_AMOUNT, CM_AO_CODE, CM_OFFICER_NAME, CM_SECTOR_CODE, CM_SECTOR_DESC, CM_DTE_PK, CM_CARD_EXPIR_DTE, CM_INSTALLMENT_AMOUNT, CM_INSTL_BAL, CM_INTR_PER_DIEM, CM_INSTL_LIMIT, CM_AMNT_OUTST_INSTL, CM_TOTALDUE, CM_CYCLE, CM_INSTALLMENT_NO, CM_DTE_PYMT_DUE, DPD, CM_BUCKET_PROGRAM, FLD_CHAR_2, CM_STATUS, CM_COLLECTIBILITY, CM_BLOCK_CODE, CM_DTE_BLOCK_CODE, CM_OS_BALANCE, CM_OS_PRINCIPAL, CM_OS_INTEREST, CM_RTL_MISC_FEES, CM_TOTAL_OS_AR, CM_CHGOFF_STATUS_FLAG, CM_DTE_CHGOFF_STAT_CHANGE, SUM_WO_BALANCE, CM_CHGOFF_PRICIPLE, CM_ZIP_REC, CM_DELQ_COUNTER1, CM_DELQ_COUNTER2, CM_DELQ_COUNTER3, CM_DELQ_COUNTER4, CM_DELQ_COUNTER5, CM_DELQ_COUNTER6, CM_DELQ_COUNTER7, CM_DELQ_COUNTER8, CM_DELQ_COUNTER9, CM_CURR_DUE, CM_PAST_DUE, CM_30DAYS_DELQ, CM_60DAYS_DELQ, CM_90DAYS_DELQ, CM_120DAYS_DELQ, CM_150DAYS_DELQ, CM_180DAYS_DELQ, CM_210DAYS_DELQ, CM_RESTRUCTURE_FLAG, CM_DTE_RESTRUCTURE, CM_DTE_LST_PYMT, CM_STATUS_DESC, CM_LST_PYMT_AMNT, CM_PAID_PRICIPAL, CM_PAID_INTEREST, CM_PAID_CHARGE, CM_SOURCE_CODE, CR_ACCT_NBR
					  FROM cpcrd_new_upload_temp
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