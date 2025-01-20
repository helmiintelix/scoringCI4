<?php
namespace App\Modules\InventoryWo\models;

use App\Models\Common_model;
use CodeIgniter\Model;

Class Inventory_wo_model Extends Model 
{
    protected $Common_model;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new Common_model;
    }
    function get_wo_list(){
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
            'DPD','CM_CUSTOMER_NMBR','FLD_DEC_1 AS CM_CRLIMIT','CR_NAME_1','a.CM_CARD_NMBR','CM_STATUS AS CM_STATUS_DESC','CM_TYPE','CM_DOMICILE_BRANCH','FLD_CHAR_2 AS CM_CYCLE','CM_DTE_PYMT_DUE as CM_DTE_PYMT_DUE','CM_BUCKET','CM_TOT_AMNT_CHARGE_OFF AS CM_TOT_BALANCE','CM_BLOCK_CODE','DATE_FORMAT(CM_DTE_BLOCK_CODE,"%d-%M-%Y")CM_DTE_BLOCK_CODE','CLASS','a.fin_account','CM_CUSTOMER_NMBR',
            'CR_HOME_PHONE','CR_OFFICE_PHONE','CR_CO_OWNER','CR_CO_HOME_PHONE','CR_CO_OFFICE_PHONE',
            'CR_ADDR_1','CR_ADDR_2','CR_ADDR_3 AS CR_ADDL_ADDR_1','CR_ADDR_4 AS CR_ADDL_ADDR_2','CR_CITY','CR_EMPLOYER','CR_ADDL_EMAIL','CR_ZIP_CODE as CR_ZIP_CODE','CR_ADDL_ZIP_EXP','CR_C_ADDR_L1 AS CR_C_ADDR_L1','CR_C_ADDR_L2 AS CR_C_ADDR_L2','CR_C_ADDR_L3 AS  CR_C_ADDR_L3','CR_C_ADDR_L4 AS  CR_C_ADDR_L4','CR_C_ADDR_L5 AS CR_C_ADDR_L5','CR_C_ZIP_CODE','CM_OLC_REASON_DESC'
        ); 
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cpcrd_ext as b', 'a.cm_card_nmbr = b.cm_card_nmbr','LEFT');
		$this->builder->where('(CM_CHGOFF_STATUS_FLAG = "Yes" or DPD > 180)');
        switch(session()->get('LEVEL_GROUP')){
			case "TEAM_LEADER" :
				$agent_list = $this->Common_model->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "team_leader = '".session()->get('USER_ID')."'");		
				$arr_agent = explode("|",$agent_list);
				$arr_agent[] = session()->get('USER_ID');
				$this->builder->whereIn("AGENT_ID",$arr_agent);
			break;
			case "SUPERVISOR" :
				$agent_list = $this->Common_model->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "supervisor = '".session()->get('USER_ID')."'");		
				$arr_agent = explode("|",$agent_list);
				$arr_agent[] = session()->get('USER_ID');
				$arr_agent[] = $this->Common_model->get_record_value(" team_leader", "cms_team", "supervisor = '".session()->get('USER_ID')."'");		
				$this->builder->whereIn("AGENT_ID",$arr_agent);
			break;
			case "ARCO":
				$agent_list = $this->Common_model->get_record_value(" GROUP_CONCAT(agency_id separator '|')", "cms_agency", "arco_id = '".session()->get('USER_ID')."'");		
				$arr_agent = explode("|",$agent_list);
				$this->builder->where("AGENCY_ID !=''");
				$this->builder->whereIn("AGENCY_ID",$arr_agent);
			break;
			
			case "AGENT" :
				$this->builder->where("AGENT_ID",session()->get('USER_ID'));	
			break;
			
		}
        $this->builder->orderBy('CM_CARD_NMBR', 'ASC');
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
            foreach ($return as &$row) {
                $row['CM_DTE_PYMT_DUE'] = date('Y-m-d', mktime(0, 0, 0, 1, substr($row['CM_DTE_PYMT_DUE'], 4, 3), substr($row['CM_DTE_PYMT_DUE'], 0, 4)));
            }
            unset($row);
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
        // return $return;
    }

}