<?php
namespace App\Modules\InventoryCollection\models;

use App\Models\Common_model;
use CodeIgniter\Model;

Class Inventory_collection_model Extends Model 
{
    protected $Common_model;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new Common_model;
    }
    function get_collection_list(){
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
            'CM_CUSTOMER_NMBR','CM_CYCLE','CM_BUCKET','DPD','CM_DOMICILE_BRANCH','CR_NAME_1','CM_TYPE',
            'a.CM_CARD_NMBR','a.fin_account','CUST_CIF','DATE_FORMAT(CURMTH_PAYDUE_DATE,"%d-%M-%Y")CURMTH_PAYDUE_DATE','CM_DTE_PYMT_DUE',
            'CM_OS_BALANCE','CM_CRLIMIT','CM_TOT_BALANCE','CR_HOME_PHONE','CR_CO_OFFICE_PHONE','CR_ZIP_CODE','CR_C_ADDR_L1','CR_C_ADDR_L2','CR_C_ADDR_L3','CR_C_ADDR_L4','CR_C_ZIP_CODE','CR_EMPLOYER','AGENT_ID','a.CLASS','CURRMTH_BAL_X','CM_OS_BALANCE'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->where('CM_CHGOFF_STATUS_FLAG in("No","") and DPD < 90 ');
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