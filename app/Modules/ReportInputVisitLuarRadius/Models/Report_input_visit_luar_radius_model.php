<?php
namespace App\Modules\ReportInputVisitLuarRadius\models;
use CodeIgniter\Model;

Class Report_input_visit_luar_radius_model Extends Model 
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
    function get_report_visit_luar_radius(){
        $this->builder = $this->db->table('cms_contact_history a');
        $select = array(
            'a.created_time', 
            'a.latitude', 
            'a.longitude', 
            'a.id', 
            'a.address_type', 
            'a.account_no', 
            'a.user_id', 
            'b.CR_NAME_1', 
            '\'\' AS track'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cpcrd_new b', 'a.account_no = b.cm_card_nmbr');  
        $this->builder->where('in_radius', '0');
		$this->builder->where('input_source', 'MOBCOLL');
        switch(session()->get('GROUP_LEVEL')){
			case "AGENT" :
					$this->builder->like('user_id',session()->get('USER_ID'));	
					break;
			case "TEAM_LEADER" :
				$agent_list = $this->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "team_leader = '".session()->get('USER_ID')."'");		
				$arr_agent = explode("|",$agent_list);
				$arr_agent[] = session()->get('USER_ID');
				$this->builder->whereIn("user_id",$arr_agent);
			
			break;
			case "SUPERVISOR" :
				$agent_list = $this->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "supervisor = '".session()->get('USER_ID')."'");		
				$arr_agent = explode("|",$agent_list);
				$arr_agent[] = session()->get('USER_ID');
				$arr_agent[] = $this->get_record_value(" team_leader", "cms_team", "supervisor = '".session()->get('USER_ID')."'");		
				$this->builder->whereIn("user_id",$arr_agent);
			break;
			default:
			
			break;
		}
        $this->builder->orderBy('created_time', 'DESC');
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
            foreach ($return as &$row) {
                $row['track'] = '<a href="javascript:void(0);" onClick="TrackingAgent(\'' . $row['latitude'] . '\',\'' . $row['longitude'] . '\',\'' . $row['user_id'] . '\',\'' . $row['id'] . '\',\'' . $row['account_no'] . '\',\'' . $row['address_type'] . '\')"><img src="assets/map-icon2.png" width="30" height="30"></img></a>';
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
    }
    function tracking_field_visit($data){
        $user_id = $data['user_id'];
        $id = $data['id'];
        $card = $data['card'];
        $addr_type = $data['addr_type'];

        $builder = $this->db->table('cms_contact_history');
        $builder->select('latitude, longitude, created_by as agent_id, created_time as time');
        $builder->where('id', $id);
        $res = $builder->get();

        $builder2 = $this->db->table('cms_contact_history a');
        $builder2->select('b.latitude, b.longitude, "" as agent_id, b.ADDRESS_TYPE as time');
        $builder2->join('cms_predictive_address b', 'a.address_type=b.ADDRESS_TYPE');
        $builder2->where('cm_card_nmbr', $card);
        $builder2->where('b.ADDRESS_TYPE', $addr_type);
        $builder2->orderBy('b.cm_card_nmbr');
        $res2 = $builder2->get();

        if ($res->getNumRows() > 0) {
            $data = $res->getResultArray();
			$data = array_merge($res->getResultArray(), $res2->getResultArray());
			foreach($data as $row){
				$val[]=$row;
			}
        }
        return json_encode($val);
    }

}