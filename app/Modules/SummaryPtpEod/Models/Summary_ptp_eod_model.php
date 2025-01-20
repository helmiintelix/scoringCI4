<?php
namespace App\Modules\SummaryPtpEod\models;
use CodeIgniter\Model;

Class Summary_ptp_eod_model Extends Model 
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
    function get_report_sum_janji_bayar_list($data){
        $this->builder = $this->db->table('cms_report_ptp');
        $select = array(
			'date_format(report_date,"%d-%b-%Y")report_date',
			'agent_id', 'ptp_kp', 'ptp_bp', 'total_ptp', 'ptp_in_progress', 'amount_collected'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        switch (session()->get('GROUP_LEVEL')) {
			case "AGENT":
				$this->builder->like('agent_id', session()->get('USER_ID'));

				break;
			case "TEAM_LEADER":
				$agent_list = $this->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "team_leader = '" . session()->get('USER_ID') . "'");
				$arr_agent = explode("|", $agent_list);
				$arr_agent[] = session()->get('USER_ID');
				$this->builder->where("agent_id", $arr_agent);

				break;
			case "SUPERVISOR":
				$agent_list = $this->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "supervisor = '" . session()->get('USER_ID') . "'");
				$arr_agent = explode("|", $agent_list);
				$arr_agent[] = session()->get('USER_ID');
				$arr_agent[] = $this->get_record_value(" team_leader", "cms_team", "supervisor = '" . session()->get('USER_ID') . "'");
				$this->builder->where("agent_id", $arr_agent);
				break;
			default:

				break;
		}
        if ($data['tgl_from']) {
            $this->builder->where('report_date >=', $data['tgl_from']);
        }
        if ($data['tgl_to']) {
            $this->builder->where('report_date <=', $data['tgl_to']);
        }
        if ($data['petugas']) {
            $this->builder->like('agent_id', $data['petugas']);
        }
        $this->builder->orderBy('report_date', 'DESC');
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