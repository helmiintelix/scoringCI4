<?php
namespace App\Modules\ListPtp\models;
use CodeIgniter\Model;

Class List_ptp_model Extends Model 
{
    function get_record_value($fieldName, $tableName, $criteria)
	{
	
		$builder = $this->db->table($tableName);
		$builder->select($fieldName)->where($criteria);
		
		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			$data = $query->getRowArray();
			
			return array_pop($data);
		}

		return null;
	}
    function get_report_janji_bayar_list($data){
        $this->builder = $this->db->table('cms_contact_history a');
        $select = array(
			'a.id', 'CR_NAME_1',
			'date_format(a.created_time,"%d-%b-%Y %T")created_time',
			'customer_no', 'account_no', 'user_id', 'notes', 'ptp_amount',
			'date_format(ptp_date,"%d-%b-%Y")ptp_date', 'ptp_status, cara_bayar'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cpcrd_new c', 'account_no = CM_CARD_NMBR');
        switch (session()->get('GROUP_LEVEL')) {
			case "AGENT":
			case "ARCO":
				$this->builder->where('user_id', session()->get('USER_ID'));
				break;

			case "SUPERVISOR":
				$agent_list = $this->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "supervisor = '" . session()->get('USER_ID') . "'");
				$arr_agent = explode("|", $agent_list);
				$arr_agent[] = session()->get('USER_ID');
				$arr_agent[] = $this->get_record_value("team_leader", "cms_team", "supervisor = '" . session()->get('USER_ID') . "'");
				$this->builder->whereIn("user_id", $arr_agent);

				break;


			case "TEAM_LEADER":
				$agent_list = $this->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "team_leader = '" . session()->get('USER_ID') . "'");
				$arr_agent = explode("|", $agent_list);
				$arr_agent[] = session()->get('USER_ID');
				$this->builder->whereIn("user_id", $arr_agent);

				break;
		}
        $this->builder->where("ptp_amount >", "0");
		$this->builder->where("ptp_date !=", "0000-00-00");
        if ($data['tgl_from']) {
            $this->builder->where('date(a.created_time) >=', $data['tgl_from']);
        }
        if ($data['tgl_to']) {
            $this->builder->where('date(a.created_time) <=', $data['tgl_to']);
        }
        if ($data['petugas']) {
            $this->builder->where('user_id', $data['petugas']);
        }
        $this->builder->orderBy('created_time', 'DESC');
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