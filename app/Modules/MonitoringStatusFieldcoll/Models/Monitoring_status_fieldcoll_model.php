<?php
namespace App\Modules\MonitoringStatusFieldcoll\models;
use CodeIgniter\Model;

Class Monitoring_status_fieldcoll_model Extends Model 
{
    function get_monitor_field_coll_list(){
        $this->builder = $this->db->table('cms_fc_location_history b');
        $select = array(
            'b.created_time',
            'a.handphone',
            'a.name',
			'b.speed',
			'b.heading',
			'b.agent_id',
			'b.longitude',
			'b.latitude',
			'"" maps',
			'"" oStatus',
            'case 
                when (SELECT c.module FROM cc_app_log c WHERE module IN ("login-Mobile","logout-Mobile") AND c.created_by=b.agent_id order BY c.created_time desc LIMIT 1) = "Login-Mobile" then "login"
                when (SELECT c.module FROM cc_app_log c WHERE module IN ("login-Mobile","logout-Mobile") AND c.created_by=b.agent_id order BY c.created_time desc LIMIT 1) = "Logout-Mobile" then "logout"
                ELSE ""
            END status',
            '(SELECT c.created_time FROM cc_app_log c WHERE module IN ("login-Mobile","logout-Mobile") AND c.created_by=b.agent_id order BY c.created_time desc LIMIT 1) as time',
            'b.latitude'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cc_user a', 'b.agent_id=a.id');	
        $this->builder->groupBy('b.agent_id');
        $this->builder->orderBy('b.created_time', 'DESC');
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
            foreach ($return as &$row) {
                $this->builder = $this->db->table('aav_configuration');
                $this->builder->select('value');
                $this->builder->where('parameter', 'mobcoll');
                $this->builder->where('id', 'INTERVAL_LOCATION');
                $interval = $this->builder->get()->getResultArray();
                if(!empty($interval)){
                    $interval=$interval[0]['value'];
                }else{
                    $interval='600';
                }
                $this->builder = $this->db->table('cms_fc_location_history');
                $this->builder->select('concat(longitude, latitude) longlat');
                $this->builder->where('agent_id', $row['agent_id']);
                $this->builder->orderBy('created_time', 'DESC');
                $this->builder->limit(2);
                $movement = $this->builder->get()->getResultArray();
                if(!empty($movement)){
                    if($row['status']=='login'){
                        if(substr($movement[0]['longlat'],0,-3) == substr($movement[1]['longlat'],0,-3)){
                            $row["status"] = 'No Movement';
                        }
                    }
                }
                $sql='SELECT created_time + interval '.$interval.' second as datetime,
                DATE(created_time + INTERVAL '.$interval.' SECOND ) DATE,
                HOUR(created_time + INTERVAL '.$interval.' SECOND ) HOUR,
                MINUTE(created_time + INTERVAL '.$interval.' SECOND ) MINUTE, now() as currTime FROM cms_fc_location_history WHERE agent_id="'.$row['agent_id'].'" ORDER BY created_time DESC limit 1';
                $times = $this->db->query($sql)->getResultArray();
                $datetime=$times[0]['datetime'];
                $date=$times[0]['DATE'];
                $hour=$times[0]['HOUR'];
                $minute=$times[0]['MINUTE'];
                $currTime=$times[0]['currTime'];
                if ($currTime>$datetime) {
                    if ($row['status']=='login') {
                        $this->builder = $this->db->table('cms_fc_location_history');
                        $this->builder->select('created_time as in_active');
                        $this->builder->where('agent_id', $row['agent_id']);
                        $this->builder->where('date(created_time)', $date);
                        $this->builder->where('hour(created_time)', $hour);
                        $this->builder->where('minute(created_time)', $minute);
                        $this->builder->orderBy('created_time', 'DESC');
                        $this->builder->limit(1);
                        $is_active = $this->builder->get()->getResultArray();
                        if(empty($is_active)){
                            $row["status"] = 'In Active';
                        }
                    }
                }
                $row['maps'] = '<a href="javascript:void(0);" onClick="TrackingAgent(\'' . $row['latitude'] . '\',\'' . $row['longitude'] . '\',\'' . $row['agent_id'] . '\')"><img src="assets/map-icon2.png" width="30" height="30"></img></a>';
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
    function tracking_history($data){
        $val = array();
        $user_id = $data['user_id'];

        $this->builder = $this->db->table('cms_fc_location_history');
        $this->builder->select('latitude, longitude, agent_id, created_time as time');
        $this->builder->where('agent_id', $user_id);
        $this->builder->where('longitude !=', '0');
        $this->builder->orderBy('created_time', 'DESC');
        $this->builder->limit(1);
        $res = $this->builder->get();
        if($res->getNumRows()>0){
			$data = $res->getResultArray();
			foreach($data as $row){
				$val[]=$row;
			}
		}
        return json_encode($val);
    }

}