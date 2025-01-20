<?php
namespace App\Modules\ListActivity\models;
use CodeIgniter\Model;

Class List_activity_model Extends Model 
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
    function get_report_hasil_telepon_list($data){
        $this->builder = $this->db->table('cms_contact_history a');
        $select = array(
            'a.id', 
            'lov1',
            'date_format(a.created_time, "%d-%b-%Y : %T") AS created_time',
            'CR_NAME_1',
            'a.account_no',
            'd.name AS user_id',
            'phone_no', 
            'phone_type', 
            'call_status', 
            'place_code',
            'contact_code', 
            'action_code', 
            'a.notes', 
            'ptp_date', 
            'ptp_amount', 
            'date_format(a.created_time, "%d-%b-%Y %T") AS created_time'
        );
        
        $select = array(
            'a.id',
            'lov1',
            'lov5',
            'lov4',
            'b.name AS user_id',
            'a.notes',
            'IFNULL(c.description, a.action_code) AS action_code',
            'contact_code',
            'phone_type',
            'call_status',
            'ptp_date',
            'ptp_amount',
            'ptp_status',
            'a.input_source',
            'a.created_time',
            'a.phone_no',
            'IFNULL(d.description, a.place_code) AS place_code',
            'IFNULL(d.description, a.next_action) AS next_action',
            'IFNULL(e.description, a.reason) AS reason',
            'CR_NAME_1',
            'account_no'
        );
        // $select = array(
        //     'a.id,lov1,lov5,lov4,b.name user_id,a.notes,ifnull(c.description,a.action_code)action_code,contact_code,phone_type,call_status,
        //     ptp_date,ptp_amount,ptp_status,a.input_source,a.created_time,a.phone_no,ifnull(d.description,a.place_code)place_code,ifnull(d.description,a.next_action)next_action,ifnull(e.description,a.reason)reason,CR_NAME_1,account_no '
        // );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->join('cc_user b', "a.user_id=b.id");
		$this->builder->join('cms_reference c', "a.action_code=c.value and c.reference='ACTION_CODE'", "left");
		$this->builder->join('cms_reference d', "a.next_action=d.value and d.reference='NEXT_ACTION'", "left");
		$this->builder->join('cms_reference e', "a.reason=e.value and e.reference='REASON_CODE'", "left");
		$this->builder->join('cms_reference f', "a.reason=f.value and f.reference='PLACE_CODE'", "left");
		$this->builder->join('cpcrd_new g', 'a.account_no = g.CM_CARD_NMBR');
        $this->builder->where('input_source in ("PHONE", "MOBCOLL")');
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