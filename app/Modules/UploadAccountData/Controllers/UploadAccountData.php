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
					"loan_code" => $allDataInSheet[$i]["A"], 
					"branch" => $allDataInSheet[$i]["B"], 
					"user_code" => $allDataInSheet[$i]["C"], 
					"product_code" => $allDataInSheet[$i]["D"], 
					"user_limit" => $allDataInSheet[$i]["E"], 
					"tenure" => $allDataInSheet[$i]["F"], 
					"mob" => $allDataInSheet[$i]["G"], 
					"borrower_interest_rate" => $allDataInSheet[$i]["H"], 
					"monthly_installment_amount" => $allDataInSheet[$i]["I"], 
					"due_date" => $allDataInSheet[$i]["J"], 
					"credit_agreement_number" => $allDataInSheet[$i]["K"], 
					"credit_agreement_date" => $allDataInSheet[$i]["L"], 
					"disbursement_date" => $allDataInSheet[$i]["M"], 
					"principal_overdue" => $allDataInSheet[$i]["N"], 
					"interest_overdue" => $allDataInSheet[$i]["O"], 
					"late_overdue" => $allDataInSheet[$i]["P"], 
					"total_outstanding" => $allDataInSheet[$i]["Q"], 
					"principal_outstanding" => $allDataInSheet[$i]["R"], 
					"interest_outstanding" => $allDataInSheet[$i]["S"], 
					"outstanding_ar" => $allDataInSheet[$i]["T"], 
					"outstanding_installment" => $allDataInSheet[$i]["U"], 
					"late_outstanding" => $allDataInSheet[$i]["V"], 
					"dpd" => $allDataInSheet[$i]["W"], 
					"collectibility" => $allDataInSheet[$i]["X"], 
					"last_repayment_date" => $allDataInSheet[$i]["Y"], 
					"npl_date" => $allDataInSheet[$i]["Z"], 
					"bank_account_number" => $allDataInSheet[$i]["AA"], 
					"available_cash_balance" => $allDataInSheet[$i]["AB"], 
					"principal_paid" => $allDataInSheet[$i]["AC"], 
					"interest_paid" => $allDataInSheet[$i]["AD"], 
					"late_paid" => $allDataInSheet[$i]["AE"], 
					"account_status" => $allDataInSheet[$i]["AF"], 
					"maturity_date" => $allDataInSheet[$i]["AG"], 
					"is_charged_off" => $allDataInSheet[$i]["AH"], 
					"charged_off_date" => $allDataInSheet[$i]["AI"], 
					"hold_amount" => $allDataInSheet[$i]["AJ"], 
					"ao_code" => $allDataInSheet[$i]["AK"], 
					"account_officer_name" => $allDataInSheet[$i]["AL"], 
					"sector_code" => $allDataInSheet[$i]["AM"], 
					"description_sector" => $allDataInSheet[$i]["AN"], 
					"credit_line_number" => $allDataInSheet[$i]["AO"], 
					"oldest_emi" => $allDataInSheet[$i]["AP"], 
					"total_overdue" => $allDataInSheet[$i]["AQ"], 
					"minimum_payment" => $allDataInSheet[$i]["AR"], 
					"current_counter_eom" => $allDataInSheet[$i]["AS"], 
					"xdays_counter_eom" => $allDataInSheet[$i]["AT"], 
					"dpd30_counter_eom" => $allDataInSheet[$i]["AU"], 
					"dpd60_counter_eom" => $allDataInSheet[$i]["AV"], 
					"dpd90_counter_eom" => $allDataInSheet[$i]["AW"], 
					"dpd120_counter_eom" => $allDataInSheet[$i]["AX"], 
					"dpd150_counter_eom" => $allDataInSheet[$i]["AY"], 
					"dpd180_counter_eom" => $allDataInSheet[$i]["AZ"], 
					"dpd210_counter_eom" => $allDataInSheet[$i]["BA"], 
					"current_total" => $allDataInSheet[$i]["BB"], 
					"xdays_total" => $allDataInSheet[$i]["BC"], 
					"dpd30_total" => $allDataInSheet[$i]["BD"], 
					"dpd60_total" => $allDataInSheet[$i]["BE"], 
					"dpd90_total" => $allDataInSheet[$i]["BF"], 
					"dpd120_total" => $allDataInSheet[$i]["BG"], 
					"dpd150_total" => $allDataInSheet[$i]["BH"], 
					"dpd180_total" => $allDataInSheet[$i]["BI"], 
					"dpd210_total" => $allDataInSheet[$i]["BJ"], 
					"open_date" => $allDataInSheet[$i]["BK"], 
					"print_billing_date" => $allDataInSheet[$i]["BL"], 
					"is_restructured" => $allDataInSheet[$i]["BM"], 
					"last_restructuring_date" => $allDataInSheet[$i]["BN"], 
					"block_code" => $allDataInSheet[$i]["BO"], 
					"block_code_date" => $allDataInSheet[$i]["BP"], 
					"is_fpd" => $allDataInSheet[$i]["BQ"], 
					"credit_segment" => $allDataInSheet[$i]["BR"], 
					"cycle" => $allDataInSheet[$i]["BS"], 
					"os_at_wo" => $allDataInSheet[$i]["BT"], 
					"next_installment_principal" => $allDataInSheet[$i]["BU"], 
					"application_origin" => $allDataInSheet[$i]["BV"], 
					"application_type" => $allDataInSheet[$i]["BW"], 
					"transaction_type" => $allDataInSheet[$i]["BX"], 
					"sales_manager_code" => $allDataInSheet[$i]["BY"], 
					"sales_manager_name" => $allDataInSheet[$i]["BZ"], 
					"insurance_flag" => $allDataInSheet[$i]["CA"], 
					"funding_amount" => $allDataInSheet[$i]["CB"], 
					"age" => $allDataInSheet[$i]["CC"], 
					"alternative_name" => $allDataInSheet[$i]["CD"], 
					"approval_type" => $allDataInSheet[$i]["CE"], 
					"bo_input_system" => $allDataInSheet[$i]["CF"], 
					"borrower_effective_rate" => $allDataInSheet[$i]["CG"], 
					"borrower_grade" => $allDataInSheet[$i]["CH"], 
					"borrower_grade_category" => $allDataInSheet[$i]["CI"], 
					"db_first_restructuring_dpd" => $allDataInSheet[$i]["CJ"], 
					"db_first_restructuring_dpd_bucket" => $allDataInSheet[$i]["CK"], 
					"db_last_restructuring_dpd" => $allDataInSheet[$i]["CL"], 
					"db_last_restructuring_dpd_bucket" => $allDataInSheet[$i]["CM"], 
					"delinq_string_due_date" => $allDataInSheet[$i]["CN"], 
					"delinq_string_eom" => $allDataInSheet[$i]["CO"], 
					"disburse_system" => $allDataInSheet[$i]["CP"], 
					"dpd_bucket" => $allDataInSheet[$i]["CQ"], 
					"dpd120_interest" => $allDataInSheet[$i]["CR"], 
					"dpd120_late" => $allDataInSheet[$i]["CS"], 
					"dpd120_principal" => $allDataInSheet[$i]["CT"], 
					"dpd150_interest" => $allDataInSheet[$i]["CU"], 
					"dpd150_late" => $allDataInSheet[$i]["CV"], 
					"dpd150_principal" => $allDataInSheet[$i]["CW"], 
					"dpd180_interest" => $allDataInSheet[$i]["CX"], 
					"dpd180_late" => $allDataInSheet[$i]["CY"], 
					"dpd180_principal" => $allDataInSheet[$i]["CZ"], 
					"dpd210_interest" => $allDataInSheet[$i]["DA"], 
					"dpd210_late" => $allDataInSheet[$i]["DB"], 
					"dpd210_principal" => $allDataInSheet[$i]["DC"], 
					"dpd30_interest" => $allDataInSheet[$i]["DD"], 
					"dpd30_late" => $allDataInSheet[$i]["DE"], 
					"dpd30_principal" => $allDataInSheet[$i]["DF"], 
					"dpd60_interest" => $allDataInSheet[$i]["DG"], 
					"dpd60_late" => $allDataInSheet[$i]["DH"], 
					"dpd60_principal" => $allDataInSheet[$i]["DI"], 
					"dpd90_interest" => $allDataInSheet[$i]["DJ"], 
					"dpd90_late" => $allDataInSheet[$i]["DK"], 
					"dpd90_principal" => $allDataInSheet[$i]["DL"], 
					"first_ballooning_amount" => $allDataInSheet[$i]["DM"], 
					"first_ballooning_date" => $allDataInSheet[$i]["DN"], 
					"first_restructuring_date" => $allDataInSheet[$i]["DO"], 
					"first_restructuring_mob" => $allDataInSheet[$i]["DP"], 
					"first_restructuring_remark" => $allDataInSheet[$i]["DQ"], 
					"first_restructuring_type" => $allDataInSheet[$i]["DR"], 
					"group_name" => $allDataInSheet[$i]["DS"], 
					"has_siup" => $allDataInSheet[$i]["DT"], 
					"highest_interest_overdue" => $allDataInSheet[$i]["DU"], 
					"highest_late_overdue" => $allDataInSheet[$i]["DV"], 
					"highest_principal_overdue" => $allDataInSheet[$i]["DW"], 
					"highest_total_overdue" => $allDataInSheet[$i]["DX"], 
					"institutional_prop" => $allDataInSheet[$i]["DY"], 
					"interest_accrual" => $allDataInSheet[$i]["DZ"], 
					"interest_billed" => $allDataInSheet[$i]["EA"], 
					"interest_paid_ltd" => $allDataInSheet[$i]["EB"], 
					"interest_unbilled" => $allDataInSheet[$i]["EC"], 
					"is_balance_enough_for_m1_hor" => $allDataInSheet[$i]["ED"], 
					"is_balance_enough_for_m1_ver" => $allDataInSheet[$i]["EE"], 
					"is_balance_enough_for_oldest_unpaid_billing" => $allDataInSheet[$i]["EF"], 
					"is_balance_enough_for_overdue" => $allDataInSheet[$i]["EG"], 
					"is_bank_connect" => $allDataInSheet[$i]["EH"], 
					"is_eligible_npl" => $allDataInSheet[$i]["EI"], 
					"is_eom" => $allDataInSheet[$i]["EJ"], 
					"is_fraud" => $allDataInSheet[$i]["EK"], 
					"is_npl" => $allDataInSheet[$i]["EL"], 
					"is_pefindo_submitted" => $allDataInSheet[$i]["EM"], 
					"rollover_loan" => $allDataInSheet[$i]["EN"], 
					"l3m_repayment_amount" => $allDataInSheet[$i]["EO"], 
					"l3m_repayment_pct" => $allDataInSheet[$i]["EP"], 
					"l3m_total_overdue" => $allDataInSheet[$i]["EQ"], 
					"last_ballooning_amount" => $allDataInSheet[$i]["ER"], 
					"last_ballooning_date" => $allDataInSheet[$i]["ES"], 
					"last_deposit_amount" => $allDataInSheet[$i]["ET"], 
					"last_deposit_date" => $allDataInSheet[$i]["EU"], 
					"last_deposit_remark" => $allDataInSheet[$i]["EV"], 
					"last_repayment_amount" => $allDataInSheet[$i]["EW"], 
					"last_repayment_amount_after_wo" => $allDataInSheet[$i]["EX"], 
					"last_repayment_date_after_wo" => $allDataInSheet[$i]["EY"], 
					"last_repayment_type" => $allDataInSheet[$i]["EZ"], 
					"last_restructuring_mob" => $allDataInSheet[$i]["FA"], 
					"last_restructuring_remark" => $allDataInSheet[$i]["FB"], 
					"last_restructuring_type" => $allDataInSheet[$i]["FC"], 
					"late_billed" => $allDataInSheet[$i]["FD"], 
					"late_fee_calc" => $allDataInSheet[$i]["FE"], 
					"late_paid_ltd" => $allDataInSheet[$i]["FF"], 
					"latest_due_date_overdue" => $allDataInSheet[$i]["FG"], 
					"latest_installment_number_overdue" => $allDataInSheet[$i]["FH"], 
					"lender_effective_rate" => $allDataInSheet[$i]["FI"], 
					"lender_grade" => $allDataInSheet[$i]["FJ"], 
					"lender_grade_category" => $allDataInSheet[$i]["FK"], 
					"lender_interest_rate" => $allDataInSheet[$i]["FL"], 
					"loan_amount" => $allDataInSheet[$i]["FM"], 
					"business_pic" => $allDataInSheet[$i]["FN"], 
					"longest_dpd" => $allDataInSheet[$i]["FO"], 
					"m1_hor" => $allDataInSheet[$i]["FP"], 
					"mia" => $allDataInSheet[$i]["FQ"], 
					"net_principal_outstanding" => $allDataInSheet[$i]["FR"], 
					"net_principal_outstanding_after_wo" => $allDataInSheet[$i]["FS"], 
					"net_principal_outstanding_at_wo" => $allDataInSheet[$i]["FT"], 
					"next_ballooning_amount" => $allDataInSheet[$i]["FU"], 
					"next_ballooning_date" => $allDataInSheet[$i]["FV"], 
					"npl_dpd" => $allDataInSheet[$i]["FW"], 
					"npl_dpd_bucket" => $allDataInSheet[$i]["FX"], 
					"npl_mob" => $allDataInSheet[$i]["FY"], 
					"number_of_installment_billed" => $allDataInSheet[$i]["FZ"], 
					"number_of_installment_overdue" => $allDataInSheet[$i]["GA"], 
					"number_of_installment_unbilled" => $allDataInSheet[$i]["GB"], 
					"oldest_due_date_overdue" => $allDataInSheet[$i]["GC"], 
					"oldest_installment_number_overdue" => $allDataInSheet[$i]["GD"], 
					"oldest_unpaid_due_date_billing" => $allDataInSheet[$i]["GE"], 
					"oldest_unpaid_interest_billing" => $allDataInSheet[$i]["GF"], 
					"oldest_unpaid_late_billing" => $allDataInSheet[$i]["GG"], 
					"oldest_unpaid_principal_billing" => $allDataInSheet[$i]["GH"], 
					"oldest_unpaid_total_billing" => $allDataInSheet[$i]["GI"], 
					"partner_name" => $allDataInSheet[$i]["GJ"], 
					"pefindo_submission_date" => $allDataInSheet[$i]["GK"], 
					"pic_lead" => $allDataInSheet[$i]["GL"], 
					"pic_sub_lead" => $allDataInSheet[$i]["GM"], 
					"premature_clearance" => $allDataInSheet[$i]["GN"], 
					"principal_billed" => $allDataInSheet[$i]["GO"], 
					"principal_loss_non_retail" => $allDataInSheet[$i]["GP"], 
					"principal_loss_retail" => $allDataInSheet[$i]["GQ"], 
					"principal_outstanding_institutional" => $allDataInSheet[$i]["GR"], 
					"principal_outstanding_non_retail" => $allDataInSheet[$i]["GS"], 
					"principal_outstanding_retail" => $allDataInSheet[$i]["GT"], 
					"principal_outstanding_robo" => $allDataInSheet[$i]["GU"], 
					"principal_paid_ltd" => $allDataInSheet[$i]["GV"], 
					"principal_unbilled" => $allDataInSheet[$i]["GW"], 
					"provision_fund_institutional" => $allDataInSheet[$i]["GX"], 
					"provision_fund_institutional_by_accounting" => $allDataInSheet[$i]["GY"], 
					"provision_fund_non_retail" => $allDataInSheet[$i]["GZ"], 
					"provision_fund_retail" => $allDataInSheet[$i]["HA"], 
					"provision_fund_robo" => $allDataInSheet[$i]["HB"], 
					"provision_fund_robo_by_accounting" => $allDataInSheet[$i]["HC"], 
					"restructuring_counter" => $allDataInSheet[$i]["HD"], 
					"retail_prop" => $allDataInSheet[$i]["HE"], 
					"robo_prop" => $allDataInSheet[$i]["HF"], 
					"snapshot_date" => $allDataInSheet[$i]["HG"], 
					"total_billed" => $allDataInSheet[$i]["HH"], 
					"total_paid" => $allDataInSheet[$i]["HI"], 
					"total_paid_ltd" => $allDataInSheet[$i]["HJ"], 
					"total_repayment_after_wo" => $allDataInSheet[$i]["HK"], 
					"total_unbilled" => $allDataInSheet[$i]["HL"], 
					"vendor_name" => $allDataInSheet[$i]["HM"], 
					"website" => $allDataInSheet[$i]["HN"], 
					"wo_dpd" => $allDataInSheet[$i]["HO"], 
					"wo_dpd_bucket" => $allDataInSheet[$i]["HP"], 
					"xdays_interest" => $allDataInSheet[$i]["HQ"], 
					"xdays_late" => $allDataInSheet[$i]["HR"], 
					"xdays_principal" => $allDataInSheet[$i]["HS"], 
					"is_ever_fpd" => $allDataInSheet[$i]["HT"], 
					"va_number_bca" => $allDataInSheet[$i]["HU"], 
					"va_number_bri" => $allDataInSheet[$i]["HV"], 
					"va_number_cimb" => $allDataInSheet[$i]["HW"], 
					"va_number_mandiri" => $allDataInSheet[$i]["HX"], 
					"va_number_scb" => $allDataInSheet[$i]["HY"], 
					"service_fee" => $allDataInSheet[$i]["HZ"], 
					'file_upload_id' => $data_exclude_file['id'],
				];
			}
			$return = $this->db->table('cpcrd_new_temp')->insertBatch($data_batch);

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
	
}