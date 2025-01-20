<?php
namespace App\Modules\MyAccount\models;
use App\Models\Common_model;
use CodeIgniter\Model;

Class My_account_model Extends Model 
{
	protected $Common_model;
    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new Common_model;
    }
    function get_assigned_account(){
        $this->builder = $this->db->table('cpcrd_new a');
		$select = array(
			'c.name as AGENT_ID', 'a.CM_CUSTOMER_NMBR', 'CM_USER_CODE_2', 'assignment_start_date', 'DPD',
			'b.last_contact_time',
			'b.assignment_start_date',
			'b.assignment_end_date',
			'b.last_response',
			'a.CR_NAME_1', 'a.CM_CARD_NMBR', 'a.CM_STATUS_DESC', 'a.CM_TYPE', 'a.FLD_CHAR_2 as CM_CYCLE', 'a.CM_BUCKET', 'a.CM_TOT_BALANCE', 'a.fin_account as fin_account', 'a.CM_CUSTOMER_NMBR', 'BILL_BAL', 'CM_AMOUNT_DUE as CURRMTH_BAL_X', 'if(d.status="approved","Y","N") as is_phone_tag', 'a.ACCOUNT_TAGGING',
			'DATEDIFF(CURDATE(),DATE(b.last_contact_time)) contact_aging_day',
			'if(date(b.ptp_date)>=curdate(), DATEDIFF(CURDATE(),DATE(b.ptp_date)), null ) ptp_aging_day'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->join('cms_account_last_status b', 'b.account_no = a.CM_CARD_NMBR');
		$this->builder->join('cms_phonetag_list d', 'a.cm_card_nmbr=d.cm_card_nmbr', 'left');
		$this->builder->join('cc_user c', 'a.AGENT_ID=c.id','left');
		$this->builder->where("CF_AGENCY_STATUS_ID !='2'");
	
		switch (session()->get('LEVEL_GROUP')) {
			case 'TEAM_LEADER':
				$agent_list = $this->Common_model->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "team_leader = '" . session()->get('USER_ID') . "'");
				$arr_agent = explode("|", $agent_list);
				$arr_agent[] = session()->get('USER_ID');
				$this->builder->whereIn("AGENT_ID", $arr_agent);
				break;
			case 'SUPERVISOR':
				$agent_list = $this->Common_model->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "supervisor = '" . session()->get('USER_ID') . "'");
				$arr_agent = explode("|", $agent_list);
				$arr_agent[] = session()->get('USER_ID');
				$arr_agent[] = $this->Common_model->get_record_value(" team_leader", "cms_team", "supervisor = '" . session()->get('USER_ID') . "'");
				$this->builder->whereIn("AGENT_ID", $arr_agent);
				break;
			case 'ARCO':
				$this->builder->where('AGENT_ID', session()->get('USER_ID'));
				break;
			case 'ROOT':
				break;
			case 'TELECOLL':
				$this->builder->where('AGENT_ID', session()->get('USER_ID'));
				break;
			default:
				break;
		}
		$team = $this->Common_model->get_record_value("team_id", "cms_team", "agent_list like '%" . session()->get('USER_ID') . "%'");
		if (!empty($team)) {
			$filter_account = $this->Common_model->get_record_values("filter_detail,order_by_detail", "cms_team_filter",  "team_id = '" . $team . "'", "filter_detail");
			if (!empty($filter_account)) {
				if (!empty(trim($filter_account["filter_detail"]))) {
					$this->builder->where($filter_account["filter_detail"]);
				}

				if (!empty(trim($filter_account["order_by_detail"]))) {
					$this->builder->orderBy($filter_account["order_by_detail"]);
				}
			}
		}
		$this->builder->orderBy('CR_NAME_1', 'ASC');
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
    function isExistzipcode_area_mappingId($id){
        $this->builder = $this->db->table('cms_zipcode_area_mapping');
        $this->builder->where(array(
            'sub_area_id' => $id
        ));
        $query = $this->builder->get();
        if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
    }
    function save_zipcode_area_mapping_assign($data){
        $this->builder = $this->db->table('cms_zipcode_area_mapping_temp');
        $return = $this->builder->insert($data);
        return $return;
    }
}