<?php
namespace App\Modules\MonitoringDebitur\models;
use CodeIgniter\Model;

Class Monitoring_debitur_model Extends Model 
{
    function get_customer_list(){
        $this->builder = $this->db->table('cms_contact_history a');
        $select = array(
            'distinct a.id id',
            'c.id id_user',
            'c.name name',
            'a.account_no account_no',
            'd.CM_CUSTOMER_NMBR CM_CUSTOMER_NMBR',
            'd.CR_NAME_1 CR_NAME_1',
            'd.CR_ADDR_1 CR_ADDR_1',
            'a.input_source input_source',
            'a.call_status call_status',
            'a.notes notes',
            'a.ptp_date ptp_date',
            'FORMAT(a.ptp_amount,0) ptp_amount',
            'a.created_by',
            'other_phone',
            '(SELECT category_name FROM cms_lov_registration WHERE id = lov1) AS lov1',
            '(SELECT category_name FROM cms_lov_registration WHERE id = lov2) AS lov2',
            '(SELECT category_name FROM cms_lov_registration WHERE id = lov3) AS lov3',
            '(SELECT category_name FROM cms_lov_registration WHERE id = lov4) AS lov4',
            '(SELECT category_name FROM cms_lov_registration WHERE id = lov5) AS lov5',
            'a.latitude',
            'a.longitude',
            'picture1', 
            'picture2',
            'picture3',
            'picture4',
            '"" as location',
            '(
                SELECT 
                    CONCAT(borrower_alamat,", PROVINSI ", borrower_provinsi,", KOTA " ,borrower_kota,", KECAMATAN " ,borrower_kecamatan,", KELURAHAN " ,borrower_kelurahan)
                FROM cms_temp_phone b 
                WHERE contract_number = a.account_no 
                ORDER BY created_time DESC LIMIT 1
            ) AS alamat',
            '(
                SELECT 
                    borrower_phone
                FROM cms_temp_phone b 
                WHERE contract_number = a.account_no 
                ORDER BY created_time DESC LIMIT 1
            ) AS borrower_phone',
            '(
                SELECT 
                    borrower_hp1
                FROM cms_temp_phone b 
                WHERE contract_number = a.account_no 
                ORDER BY created_time DESC LIMIT 1
            ) AS borrower_hp1',
            '(
                SELECT 
                    borrower_hp2
                FROM cms_temp_phone b 
                WHERE contract_number = a.account_no 
                ORDER BY created_time DESC LIMIT 1
            ) AS borrower_hp2',
            '(
                SELECT 
                    borrower_office1
                FROM cms_temp_phone b 
                WHERE contract_number = a.account_no 
                ORDER BY created_time DESC LIMIT 1
            ) AS borrower_office1',
            '(
                SELECT 
                    borrower_office2
                FROM cms_temp_phone b 
                WHERE contract_number = a.account_no 
                ORDER BY created_time DESC LIMIT 1
            ) AS borrower_office2',
            '(
                SELECT 
                    borrower_home1
                FROM cms_temp_phone b 
                WHERE contract_number = a.account_no 
                ORDER BY created_time DESC LIMIT 1
            ) AS borrower_home1',
            '(
                SELECT 
                    borrower_home2
                FROM cms_temp_phone b 
                WHERE contract_number = a.account_no 
                ORDER BY created_time DESC LIMIT 1
            ) AS borrower_home2',
            '(
                SELECT 
                    address_type
                FROM cms_temp_phone b 
                WHERE contract_number = a.account_no 
                ORDER BY created_time DESC LIMIT 1
            ) AS address_type',
            'a.created_time'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cc_user c', 'c.id = a.user_id');  
        $this->builder->join('cpcrd_new d', 'd.CM_CARD_NMBR = a.account_no');  
		// $this->builder->whereIn('input_source', ['MOBCOLL', 'TELE']);
        // switch(session()->get('GROUP_LEVEL')){
		// 	case "AGENT" :
		// 			$this->builder->like('user_id',session()->get('USER_ID'));	
		// 			break;
		// 	case "TEAM_LEADER" :
		// 		$agent_list = $this->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "team_leader = '".session()->get('USER_ID')."'");		
		// 		$arr_agent = explode("|",$agent_list);
		// 		$arr_agent[] = session()->get('USER_ID');
		// 		$this->builder->whereIn("user_id",$arr_agent);
			
		// 	break;
		// 	case "SUPERVISOR" :
		// 		$agent_list = $this->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "supervisor = '".session()->get('USER_ID')."'");		
		// 		$arr_agent = explode("|",$agent_list);
		// 		$arr_agent[] = session()->get('USER_ID');
		// 		$arr_agent[] = $this->get_record_value(" team_leader", "cms_team", "supervisor = '".session()->get('USER_ID')."'");		
		// 		$this->builder->whereIn("user_id",$arr_agent);
		// 	break;
		// 	default:
			
		// 	break;
		// }
        $this->builder->orderBy('a.created_time', 'ASC');
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
            foreach ($return as &$row) {
                $row['location'] = '<a href="javascript:void(0);" onClick="TrackingAgent(\'' . $row['latitude'] . '\',\'' . $row['longitude'] . '\',\'' . $row['id_user'] . '\')"><img src="assets/map-icon2.png" width="30" height="30"></img></a>';
            }
            if($row["picture1"] == '' || $row["picture1"] == null)
			{
				$row["picture1"] = "NO PICTURE FOUND";
			}else{
				$row["picture1"] = "<a href='../data/MOBCOLL-".$row["picture1"]."-picture1.jpg' download>Download</a>";	
			}
			
			if($row["picture2"] == '' || $row["picture2"] == null)
			{
				$row["picture2"] = "NO PICTURE FOUND";
			}else{
				$row["picture2"] = "<a href='../data/MOBCOLL-".$row["picture2"]."-picture2.jpg' download>Download</a>";	
			}
			
			if($row["picture3"] == '' || $row["picture3"] == null)
			{
				$row["picture3"] = "NO PICTURE FOUND";
			}else{
				$row["picture3"] = "<a href='../data/MOBCOLL-".$row["picture3"]."-picture3.jpg' download>Download</a>";
			}
			
			if($row["picture4"] == '' || $row["picture4"] == null)
			{
				$row["picture4"] = "NO PICTURE FOUND";
			}else{
				$row["picture4"] = "<a href='../data/MOBCOLL-".$row["picture4"]."-picture4.jpg' download>Download</a>";
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
        // $id = $data['id'];
        // $card = $data['card'];
        // $addr_type = $data['addr_type'];

        // $builder = $this->db->table('cms_contact_history');
        // $builder->select('latitude, longitude, created_by as agent_id, created_time as time');
        // $builder->where('id', $id);
        // $res = $builder->get();
        $val = array();
        $builder2 = $this->db->table('cms_fc_location_history a');
        $builder2->select('latitude, longitude, agent_id, time(created_time) as time');
        // $builder2->join('cms_predictive_address b', 'a.address_type=b.ADDRESS_TYPE');
        $builder2->where('agent_id', $user_id);
        $builder2->where('date(created_time)', 'CURDATE()');
        $builder2->limit(1);
        $res = $builder2->get();

        if ($res->getNumRows() > 0) {
            $data = $res->getResultArray();
			// $data = array_merge($res->getResultArray(), $res2->getResultArray());
			foreach($data as $row){
				$val[]=$row;
			}
        }
        return json_encode($val);
    }

}