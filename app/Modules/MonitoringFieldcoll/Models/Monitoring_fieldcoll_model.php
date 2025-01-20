<?php
namespace App\Modules\MonitoringFieldcoll\models;
use CodeIgniter\Model;

Class Monitoring_fieldcoll_model Extends Model 
{
   

    function get_fc_monitoring_list()
    {
        $this->builder = $this->db->table('cc_user a');
        $select = array(
            'a.id',
            'concat(a.id, " - ", a.name) as fc_id',
            'concat(e.id, " - ", e.name) as spv_id',
            'a.id user_id',
            'a.name',
            'a.image',
            'a.bucket arh',
            'DATE(DATE(NOW())) tanggal_data',
            'a.supervisor_name'
        );

        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cc_user_group  AS c', 'a.group_id = c.id and c.id="AGENT_FIELD_COLLECTOR"');
		$this->builder->join('cc_user AS e', 'a.supervisor_name = e.id','left');;
        // $this->builder->join('cc_user_group d','a.group_id=d.id');
        $this->builder->where('a.is_active', '1');
        // $this->builder->where('a.flag_status', NULL);
        $this->builder->orderBy('a.name', 'ASC');

        $rResult = $this->builder->get();

        $return = $rResult->getResultArray();

        $result = array();

        if ($rResult->getNumRows() > 0) {
            foreach ($return as &$row) {
                if ($row['image'] == '') {
                    $row['image'] = base_url().'/assets/profilePicture/person-circle.svg';
                } else {
                    if(file_exists('./uploads/user/'.$row['image'])){
                        $row['image'] = base_url().'/uploads/user/'.$row['image'];
                    }else{
                        $row['image'] = base_url().'/assets/profilePicture/person-circle.svg';
                    }
                }
                
                $row['chat'] = '<a href="javascript:void(0);" onClick="chatWithAgent(\''.$row['id'].'\')"><image src="assets/images/user1_message.png"></a>';
                $row['tracking'] = '<a href="javascript:void(0);" onClick="TrackingAgent(\''.$row['id'].'\')"><image src="assets/map-icon2.png" width="30" height="30"></a>';

                $this->builder = $this->db->table("cpcrd_new m");
                $this->builder->select("count(*) account_no");
                $this->builder->where("m.agent_id", $row['user_id']);
                $res1 = $this->builder->get();
                $row1 = $res1->getRowArray();
                
                $this->builder = $this->db->table("cms_contact_history a");
                $this->builder->select("COUNT(DISTINCT account_no) dikunjungi");
                $this->builder->join('cpcrd_new b', 'b.CM_CARD_NMBR=a.account_no');
                $this->builder->where('created_by', $row['user_id']);
                $this->builder->where('input_source', 'MOBCOLL');
                $this->builder->where('DATE(created_time)',  date('Y-m-d'));
                $res3 = $this->builder->get();
                $row3 = $res3->getRowArray();
                
                $this->builder = $this->db->table("cms_contact_history");
                $this->builder->select("COUNT(DISTINCT account_no) total_janji_bayar, SUM(ptp_amount) janji_bayar");
                $this->builder->where('created_by', $row['user_id']);
                $this->builder->where('input_source', 'MOBCOLL');
                $this->builder->where('lov3', 'PTP');
                $this->builder->where('DATE(created_time)',  date('Y-m-d'));
                $this->builder->where('ptp_date >= CURDATE()');
                $res4 = $this->builder->get();
                $row4 = $res4->getRowArray();
                
                $this->builder = $this->db->table("cms_contact_history");
                $this->builder->select("COUNT(DISTINCT account_no) total_payment,SUM(ptp_amount) payment");
                $this->builder->where('created_by', $row['user_id']);
                $this->builder->where('input_source', 'MOBCOLL');
                $this->builder->where('DATE(created_time)',  date('Y-m-d'));
                $this->builder->where('lov3', 'PAY VIA FC');
                $this->builder->where('ptp_date >= CURDATE()');
                $res6=$this->builder->get();
			    $row6=$res6->getRowArray();
                
                $this->builder = $this->db->table("cc_app_log");
                $this->builder->select("MIN(created_time) first_login");
                $this->builder->where("DATE(created_time)",  date('Y-m-d'));
                $this->builder->where("ACTION", "LOGIN");
                $this->builder->where('created_by', $row['user_id']);
                $res8=$this->builder->get();
			    $row8=$res8->getRowArray();

                $this->builder = $this->db->table("cc_app_log");
                $this->builder->select("MIN(created_time) first_logout");
                $this->builder->where("DATE(created_time)",  date('Y-m-d'));
                $this->builder->where("ACTION", "LOGOUT");
                $this->builder->where('created_by', $row['user_id']);
                $res9=$this->builder->get();
			    $row9=$res9->getRowArray();
                
                $this->builder = $this->db->table("cc_app_log");
                $this->builder->select("MAX(created_time) last_login");
                $this->builder->where("DATE(created_time)",  date('Y-m-d'));
                $this->builder->where("ACTION", "LOGIN");
                $this->builder->where('created_by', $row['user_id']);
                $res10=$this->builder->get();
			    $row10=$res10->getRowArray();
                
                $this->builder = $this->db->table("cc_app_log");
                $this->builder->select("MAX(created_time) last_logout");
                $this->builder->where("DATE(created_time)",  date('Y-m-d'));
                $this->builder->where("ACTION", "LOGOUT");
                $this->builder->where('created_by', $row['user_id']);
                $res11=$this->builder->get();
			    $row11=$res11->getRowArray();

                $row["account_no"]="<a href='#' onclick=popupdatadetail('jumlah_assignment','".$row['arh']."','".$row['tanggal_data']."','".$row['user_id']."')>".$row1['account_no']."</a>";
                $row["dikunjungi"]="<a href='#' onclick=popupdatadetail('jumlah_dikunjungi','".$row['arh']."','".$row['tanggal_data']."','".$row['user_id']."')>".$row3['dikunjungi']."</a>";
                $row["total_janji_bayar"]="<a href='#' onclick=popupdatadetail('jumlah_janji_bayar','".$row['arh']."','".$row['tanggal_data']."','".$row['user_id']."')>".$row4['total_janji_bayar']."</a>";
                $row["janji_bayar"]=$row4['janji_bayar'] ?? 0;
                $row["total_payment"]="<a href='#' onclick=popupdatadetail('jumlah_payment','".$row['arh']."','".$row['tanggal_data']."','".$row['user_id']."')>".$row6['total_payment']."</a>";
                $row["payment"]=$row6['payment'] ?? 0;
                $row["first_login"]=$row8['first_login'];
                $row["first_logout"]=$row9['first_logout'];
                $row["last_login"]=$row10['last_login'];
                $row["last_logout"]=$row11['last_logout'];
            }
            unset($row);
            $result['data'] = $return;

            foreach ($rResult->getResultArray()[0] as $key => $value) {
                $result['header'][] = array('field' => $key);
            }

            $rs =  $result;
            return $rs;
        } else {
            $rs =  $result;
            return $rs;
        }
        
    }

    function tracking_history($data){
        $this->builder = $this->db->table("cms_fc_location_history");
        $this->builder->select("latitude, longitude, agent_id, created_time as time");
        $this->builder->where("agent_id", $data['user_id']);
        $this->builder->where("longitude != '0'");
        $this->builder->orderBy("created_time", "ASC");
        $this->builder->limit(10);
        $res = $this->builder->get();
		$val = array();
        if($res->getNumRows()>0){
			$data=$res->getResultArray();
			foreach($data as $row){
				$val[]=$row;
			}
		}
		return json_encode($val);
    }

  
}