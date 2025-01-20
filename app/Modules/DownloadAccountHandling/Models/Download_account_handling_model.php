<?php
namespace App\Modules\DownloadAccountHandling\models;
use CodeIgniter\Model;
use App\Models\Common_model;

Class Download_account_handling_model Extends Model 
{
    protected $Common_model;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new Common_model;
    }
    function get_crprd_list($data){
		$DBDRIVER = $this->db->DBDriver;

        if ($DBDRIVER === 'SQLSRV') {
            // SQL Server
            $CURMTH_PAYDUE_DATE = "FORMAT(CURMTH_PAYDUE_DATE, 'dd-MMMM-yyyy') ";
        } elseif ($DBDRIVER === 'Postgre') {
            // PostgreSQL
            $CURMTH_PAYDUE_DATE = "TO_CHAR(CURMTH_PAYDUE_DATE, 'DD-Month-YYYY') ";
        } else {
            // MySQL
            $CURMTH_PAYDUE_DATE = 'DATE_FORMAT(CURMTH_PAYDUE_DATE,"%d-%M-%Y") ';
        }

        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
			'AGENT_ID',
			'CM_CUSTOMER_NMBR',
			'CM_CYCLE',
			'CM_BUCKET',
			'CM_DOMICILE_BRANCH',
			'CR_NAME_1',
			'CM_TOTAL_OS_AR',
			'CM_DTE_PYMT_DUE',
			'CM_CARD_NMBR',
			'CM_OS_BALANCE',
			'CR_DTE_BIRTH',
			'CM_DTE_LIQUIDATE',
			'CM_CARD_EXPIR_DTE',
			'CR_OCCUPATION',
			'CR_MOTHER_NM',
			'CM_RTL_MISC_FEES',
			'CM_INSTALLMENT_AMOUNT',
			'CM_CREDIT_SEGMEN',
			'CM_FPD',
			'CM_AMOUNT_DUE',
			'CM_PAST_DUE',
			'CM_30DAYS_DELQ',
			'CM_60DAYS_DELQ',
			'CM_90DAYS_DELQ',
			'CM_120DAYS_DELQ',
			'CM_150DAYS_DELQ',
			'CM_180DAYS_DELQ',
			'CM_210DAYS_DELQ',
			'fin_account',
			'CUST_CIF',
			$CURMTH_PAYDUE_DATE .' as CURMTH_PAYDUE_DATE',
			'CM_CRLIMIT',
			'CM_TOT_BALANCE',
			'CR_HOME_PHONE',
			'CR_CO_OFFICE_PHONE',
			'CR_ADDR_1',
			'CR_ADDR_2',
			'CR_ADDR_3',
			'CR_ADDR_4',
			'CR_CITY',
			'CR_ZIP_CODE',
			'CR_C_ADDR_L1',
			'CR_C_ADDR_L2',
			'CR_C_ADDR_L3',
			'CR_C_ADDR_L4',
			'CR_C_ZIP_CODE',
			'a.CLASS',
			'CURRMTH_BAL_X'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cc_user b', 'a.AGENT_ID = b.id');
		$this->builder->join('cms_account_last_status c', 'a.CM_CARD_NMBR = c.account_no', 'left');
        switch (session()->get('LEVEL_GROUP')) {
			case "ARCO":
				$agent_list = $this->Common_model->get_record_value(" GROUP_CONCAT(agency_id separator '|')", "cms_agency", "arco_id = '" . session()->get('USER_ID') . "'");
				$arr_agent = explode("|", $agent_list);
				$this->builder->where("AGENCY_ID !=''");
				$this->builder->whereIn("AGENCY_ID", $arr_agent);
				break;
			case "2":
				$agent_list = $this->Common_model->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "supervisor = '" . session()->get('USER_ID') . "'");
				$arr_agent = explode("|", $agent_list);
				$arr_agent[] = session()->get('USER_ID');
				$arr_agent[] = $this->Common_model->get_record_value(" team_leader", "cms_team", "supervisor = '" . session()->get('USER_ID') . "'");
				$this->builder->whereIn("AGENT_ID", $arr_agent);
			break;
			case "3":
				$agent_list = $this->Common_model->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "team_leader = '" . session()->get('USER_ID') . "'");
				$arr_agent = explode("|", $agent_list);
				$arr_agent[] = session()->get('USER_ID');
				$this->builder->whereIn("AGENT_ID", $arr_agent);
				break;
			case "4":
				$this->builder->where("AGENT_ID", session()->get('USER_ID'));
				break;
		}
        if ($data['petugas'] != '') {
            $this->builder->where('a.AGENT_ID', $data['petugas']);
        }
        if ($data['tgl'] != '') {
            $tglArr = explode(" - ", $data['tgl']);
			$tglFrom = $this->changeFormat($tglArr[0]);
			$tglTo = $this->changeFormat($tglArr[1]);

			$this->builder->where("(assignment_start_date BETWEEN '$tglFrom' AND '$tglTo' or assignment_end_date BETWEEN '$tglFrom' AND '$tglTo' )");
        }
        $this->builder->orderBy('CM_CARD_NMBR', 'ASC');
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
            foreach ($rResult->getResultArray()[0] as $key => $value) {
                $result['header'][] = array('field' => $key);
            }
            $result['data'] = $return;
        } else {
            $result['header'] = array();
            $result['data'] = array();
        }
        
        $rs =  $result;
        return $rs;
    }

	function changeFormat($date){
		$dateString = $date; 
		$timestamp = strtotime(str_replace('/', '-', $dateString)); 
		$formattedDate = date('Y-m-d', $timestamp);

		return $formattedDate;
	}
    
}