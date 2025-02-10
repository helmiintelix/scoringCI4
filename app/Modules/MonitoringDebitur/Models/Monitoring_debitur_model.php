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
            }
            // unset($row);
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

    function get_petugas_list(){
        $this->builder = $this->db->table('cc_user a');
        $select = array(
            'a.id',
            'a.id user_id',
            'a.bucket arh',
            'DATE(DATE(NOW())) tanggal_data','a.supervisor_name',
            'concat(a.id, " - ", a.name) as nameFieldColl',
            'concat(e.id, " - ", e.name) as leader',
            '"" location',
            '"" totalAssignment',
            '"" totalVisit',
            '"" totalPtp',
            '"" ptpAmount',
            '"" totalPayment',
            '"" paymentAmount',
            '"" firstLogin',
            '"" firstLogout',
            '"" lastLogin',
            '"" lastLogout'

        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cc_user_group c', 'a.group_id = c.id and c.id="AGENT_FIELD_COLLECTOR"');  
        $this->builder->join('cc_user e', 'a.supervisor_name = e.id','left');  
		
        $this->builder->where('a.is_active', '1');
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
            foreach ($return as &$row) {

                $row['location'] = '<a href="javascript:void(0);" onClick="TrackingAgent(\'' . $row['id'] . '\')"><img src="assets/map-icon2.png" width="30" height="30"></img></a>';

                $sql1 = $this->db->table('cpcrd_new m')
                ->select('count(*) as account_no')
                ->where('m.agent_id', $row['id'])
                ->get();
                $row1 = $sql1->getRowArray()['account_no'];
                $row['totalAssignment'] = $row1;
                $row['totalAssignment'] = '<a href="javascript:void(0);" onClick="popupdatadetail( \'jumlahAssignment\', \'' . $row['id'] . '\')">'.$row1.'</a>';

                $builder3 = $this->db->table('cms_contact_history a');
                $builder3->select('COUNT(DISTINCT account_no) as dikunjungi');
                $builder3->join('cpcrd_new b', 'b.CM_CARD_NMBR = a.account_no');
                $builder3->where('created_by', $row['user_id']);
                $builder3->where('input_source', 'MOBCOLL');
                $builder3->where('DATE(created_time)', 'CURDATE()');
                $res3 = $builder3->get();
                $row3 = $res3->getRowArray()['dikunjungi'];
                $row['totalVisit']=$row3;
                $row['totalVisit']='<a href="javascript:void(0);" onClick="popupdatadetail( \'totalVisit\', \'' . $row['id'] . '\')">'.$row3.'</a>';

                $builder4 = $this->db->table('cms_contact_history');
                $builder4->select('COUNT(DISTINCT account_no) as total_janji_bayar, ifnull(SUM(ptp_amount),0) as janji_bayar');
                $builder4->where('created_by', $row['user_id']);
                $builder4->where('input_source', 'MOBCOLL');
                $builder4->where('lov3', 'PTP');
                $builder4->where('DATE(created_time)', 'CURDATE()');
                $builder4->where('ptp_date >=', 'CURDATE()');
                $res4 = $builder4->get();
                $row4 = $res4->getRowArray();
                $row['totalPtp']=$row4['total_janji_bayar'];
                $row['totalPtp']='<a href="javascript:void(0);" onClick="popupdatadetail( \'totalPtp\', \'' . $row['id'] . '\')">'.$row4['total_janji_bayar'].'</a>';
                $row['ptpAmount']=$row4['janji_bayar'];

                $builder6 = $this->db->table('cms_contact_history');
                $builder6->select('COUNT(DISTINCT account_no) as total_payment, ifnull(SUM(ptp_amount),0) as payment');
                $builder6->where('created_by', $row['user_id']);
                $builder6->where('input_source', 'MOBCOLL');
                $builder6->where('DATE(created_time)', 'CURDATE()');
                $builder6->where('lov3', 'PAY VIA FC');
                $builder6->where('ptp_date >=', 'CURDATE()');
                $res6 = $builder6->get();
                $row6 = $res6->getRowArray();
                $row['totalPayment']=$row6['total_payment'];
                $row['totalPayment']='<a href="javascript:void(0);" onClick="popupdatadetail( \'totalPayment\', \'' . $row['id'] . '\')">'.$row6['total_payment'].'</a>';
                $row['paymentAmount']=$row6['payment'];

                $builder8 = $this->db->table('cc_app_log');
                $builder8->select('MIN(created_time) as first_login');
                $builder8->where('DATE(created_time)', 'CURDATE()');
                $builder8->where('ACTION', 'LOGIN');
                $builder8->where('created_by', $row['user_id']);
                $res8 = $builder8->get();
                $row8 = $res8->getRowArray();
                $row['firstLogin'] = $row8['first_login'];

                $builder9 = $this->db->table('cc_app_log');
                $builder9->select('MIN(created_time) as first_logout');
                $builder9->where('DATE(created_time)', 'CURDATE()');
                $builder9->where('ACTION', 'LOGOUT');
                $builder9->where('created_by', $row['user_id']);
                $res9 = $builder9->get();
                $row9 = $res9->getRowArray();
                $row['firstLogout'] = $row9['first_logout'];

                $builder10 = $this->db->table('cc_app_log');
                $builder10->select('MAX(created_time) as last_login');
                $builder10->where('DATE(created_time)', 'CURDATE()');
                $builder10->where('ACTION', 'LOGIN');
                $builder10->where('created_by', $row['user_id']);
                $res10 = $builder10->get();
                $row10 = $res10->getRowArray();
                $row['lastLogin'] = $row10['last_login'];

                $builder11 = $this->db->table('cc_app_log');
                $builder11->select('MAX(created_time) as last_logout');
                $builder11->where('DATE(created_time)', 'CURDATE()');
                $builder11->where('ACTION', 'LOGOUT');
                $builder11->where('created_by', $row['user_id']);
                $res11 = $builder11->get();
                $row11 = $res11->getRowArray();
                $row['lastLogout'] = $row11['last_logout'];

            }
            // unset($row);
            foreach ($rResult->getResultArray()[0] as $key => $value) {
                if($key !='id' && $key !='user_id' && $key !='arh' && $key !='tanggal_data')
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

    function get_jumlahAssignment_list($userId){
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
            'a.cm_customer_nmbr as cifNo',
            'a.cm_card_nmbr as cardNumber',
            'a.cr_name_1 as nameCustomer',
            'a.cm_bucket as bucket',
            'a.dpd as dpd',
            'a.cm_os_balance as outstandingBalance',
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		
        $this->builder->where('a.agent_id',$userId );
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
            
            // unset($row);
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

    function get_totalVisit_list($userId){
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
            'a.cm_customer_nmbr as cifNo',
            'a.cm_card_nmbr as cardNumber',
            'a.cr_name_1 as nameCustomer',
            'a.cm_bucket as bucket',
            'a.dpd as dpd',
            'a.cm_os_balance as outstandingBalance',
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->join("cms_contact_history b","a.cm_card_nmbr=b.account_no and b.created_time between concat(curdate(),' 00:00:00') and concat(curdate(),' 23:59:00') ");
        $this->builder->where('b.created_by',$userId );
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
            
            // unset($row);
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
    
    function get_totalPtp_list($userId){
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
            'a.cm_customer_nmbr as cifNo',
            'a.cm_card_nmbr as cardNumber',
            'a.cr_name_1 as nameCustomer',
            'a.cm_bucket as bucket',
            'a.dpd as dpd',
            'a.cm_os_balance as outstandingBalance',
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->join("cms_contact_history b","a.cm_card_nmbr=b.account_no and b.created_time between concat(curdate(),' 00:00:00') and concat(curdate(),' 23:59:00') ");
        $this->builder->where('b.created_by',$userId );
        $this->builder->where('b.lov3',"ptp" );
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
            
            // unset($row);
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
    
    function get_totalPayment_list($userId){
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
            'a.cm_customer_nmbr as cifNo',
            'a.cm_card_nmbr as cardNumber',
            'a.cr_name_1 as nameCustomer',
            'a.cm_bucket as bucket',
            'a.dpd as dpd',
            'a.cm_os_balance as outstandingBalance',
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
		$this->builder->join("cms_contact_history b","a.cm_card_nmbr=b.account_no and b.created_time between concat(curdate(),' 00:00:00') and concat(curdate(),' 23:59:00') ");
        $this->builder->where('b.created_by',$userId );
        $this->builder->where('b.lov3',"PAY VIA FC" );
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
            
            // unset($row);
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

}