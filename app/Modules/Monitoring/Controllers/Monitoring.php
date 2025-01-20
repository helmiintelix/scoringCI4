<?php 
namespace App\Modules\Monitoring\Controllers;
use App\Modules\Monitoring\Models\Monitoring_model;
use CodeIgniter\Cookie\Cookie;

class Monitoring extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->monitoring_model = new Monitoring_model();
	}

    function class_monitoring_as_of_today(){
		$team_id = $this->Common_model->get_record_value('team_id','cms_team','team_leader="'.session()->get('USER_ID').'" ');

		$sql = "select bucket_id as value , bucket_name name from acs_bucket where flag = '1' order by flag asc";
		$result = $this->db->query($sql)->getResultArray();
		$bucket_data = array(''=>"--ALL--");
		foreach($result as $a=>$b){
			$bucket_data[$b['value']] = $b['name'];
		}
		$data['bucket_data'] = $bucket_data;
		
		if(session()->get('LEVEL_GROUP')=='TEAM_LEADER' || session()->get('LEVEL_GROUP')=='SUPERVISOR'){
			$class_data = array();
			$team_id = $this->Common_model->get_record_value('team_id','cms_team','team_leader="'.session()->get('USER_ID').'" ');
			$class_handling = $this->Common_model->get_record_value('class_mst_id','acs_class_work_assignment','outbound_team="'.$team_id.'" ');

			$sql = "select  a.classification_id as value , a.classification_name as name  from cms_classification a  where a.account_handling LIKE '%telecoll%' and a.classification_id = '".$class_handling."' order by a.classification_name";
			
		}else if(session()->get('LEVEL_GROUP')=='ADMIN' || session()->get('LEVEL_GROUP')=='MANAGER' || session()->get('LEVEL_GROUP')=='ROOT'){
			$class_data = array(''=>"--ALL--");
			$sql = "select  a.classification_id as value , a.classification_name as name  from cms_classification a  where a.account_handling LIKE '%telecoll%'  order by classification_name";
		}
		
	
		$result = $this->db->query($sql)->getResultArray();
		
		foreach($result as $a=>$b){
			$class_data[$b['value']] = $b['name'];
		}
		$data['class_data'] = $class_data;
	
		// $language = json_decode(session()->get('LANGUAGE'));

		// $data["language"] 			= $language->monitoring_as_today;
		// $data["label"] 			= $language->label;
		// $data["button"] 			= $language->button;
	
		return view('\App\Modules\Monitoring\Views\class_monitoring_as_of_today_view', $data);

	}

    function get_class_monitoring_as_of_today(){
		$class_id = $this->input->getPost('class_id');
		$bucket_name = $this->input->getPost('bucket_name');
		
		$sql = "select * from acs_class_monitoring_as_of_today";
		$count_tmp = $this->db->query($sql)->getNumRows();
		$get_content = false;
		
		$sql = "select * from acs_class_monitoring_as_of_today a where 
				a.data_assigned !='0' 
				";
		$count_view = $this->db->query($sql)->getNumRows();
		
		// if($count_tmp==$count_view){
			$sql = "truncate acs_class_monitoring_as_of_today_view";
			$this->db->query($sql);
			
			$sql = "insert into acs_class_monitoring_as_of_today_view select * from acs_class_monitoring_as_of_today";
			// $this->db->query($sql);
			
			$get_content = true;
		// }
		
		$where = '';
		if($class_id != '' && $bucket_name==''){
			$where = ' where ';
			$where .= ' class_id = "'.$class_id.'" ';
		}
		else if($class_id == '' && $bucket_name!=''){
			$where = ' where ';
			$where .= ' bucket_name = "'.$bucket_name.'" ';
		}
		else if($class_id != '' && $bucket_name!=''){
			$where = ' where ';
			$where .= ' bucket_name = "'.$bucket_name.'" and  class_id = "'.$class_id.'" ';
		}
		
		
		$sql = "select * from acs_class_monitoring_as_of_today ".$where." order by data_assigned desc";
		$result = $this->db->query($sql)->getResultArray();
		
		echo json_encode($result);	
	}

    function agent_monitoring_as_of_today(){
		$where = '';
		$team_list = array(""=>"[SELECT DATA]")+$this->Common_model->get_record_list('team_id as value,team_name as item','cms_team','1=1','team_name');
		if(session()->get('LEVEL_GROUP')=='TEAM_LEADER'){
			$agent_list = $this->Common_model->get_record_value('agent_list','cms_team','team_leader="'.session()->get('USER_ID').'" ');
			$agent_list = str_replace('|','","',$agent_list);

			$team_list = array(""=>"[SELECT DATA]")+$this->Common_model->get_record_list('team_id as value,team_name as item','cms_team','team_leader="'.session()->get('USER_ID').'" ','team_name');

			$where .= 'AND a.id IN ("'.$agent_list.'")';
		}
		$sql = "SELECT distinct b.agent_id as value , a.name as name 
				FROM cc_user a 
				LEFT join acs_agent_monitoring_as_of_today b on b.agent_id = a.id  
				WHERE 1=1 ".$where."
				ORDER BY a.name";
		$result = $this->db->query($sql)->getResultArray();
		$agent_data = array(""=>"[SELECT DATA]");
		foreach($result as $a=>$b){
			$agent_data[$b['value']] = $b['name'];
		}
		$data['agent_data'] = $agent_data;
		$data['team_data']	= $team_list;

		// $language = json_decode(session()->get('LANGUAGE'));

		// $data["language"] 			= $language->monitoring_as_today;
		// $data["label"] 			= $language->label;
		// $data["button"] 			= $language->button;
		
		// $this->load->view('agent_monitoring_as_of_today_v2_view',$data);
		return view('\App\Modules\Monitoring\Views\agent_monitoring_as_of_today_v2_view', $data);
    }

    function get_agent_monitoring_as_of_today(){
        $agent_id = $this->input->getPost('agent_id');
        $team_id = $this->input->getPost('team_id');
    
        
        $where = ' where date(a.created_time) = curdate() ';
        if($agent_id != ''){
            // $where = ' where ';
            $where .= ' and agent_id = "'.$agent_id.'" ';
        }
        if($team_id != ''){
            $cekAgentTeam = "select agent_list from cms_team where team_id = '".$team_id."'";
            $r = str_replace("|","','",$this->db->query($cekAgentTeam)->getRow()->agent_list ?? '');
            
            $where .= "and agent_id in ('".$r."')";
        }
        $sql = "select 
                    a.* 
                from acs_agent_monitoring_as_of_today a
                ".$where."
                order by a.name asc";
        $result = $this->db->query($sql)->getResultArray();
        echo json_encode($result);	
    }

    function team_monitoring_as_of_today(){
		
		if(session()->get('LEVEL_GROUP')=='TEAM_LEADER' || session()->get('LEVEL_GROUP')=='SUPERVISOR'){
			$team_data = array();
			$team_id = $this->Common_model->get_record_value('team_id','cms_team','team_leader="'.session()->get('USER_ID').'" ');
			$sql = "select team_id as value , team_name as name from cms_team where flag_team='1' AND team_id='".$team_id."' order by team_name";
		}else{
			$team_data = array(''=>"--ALL--");
			$sql = "select team_id as value , team_name as name from cms_team where flag_team='1' order by team_name";
		}

		
		$result = $this->db->query($sql)->getResultArray();
		
		foreach($result as $a=>$b){
			$team_data[$b['value']] = $b['name'];
		}
		$data['team_data'] = $team_data;

		// $language = json_decode(session()->get('LANGUAGE'));

		// $data["language"] 			= $language->monitoring_as_today;
		// $data["label"] 			= $language->label;
		// $data["button"] 			= $language->button;
	
		return view('\App\Modules\Monitoring\Views\team_monitoring_as_of_today_view', $data);

	}

    function get_team_monitoring_as_of_today(){

		$sql = "select * from acs_team_monitoring_as_of_today";
		$count_tmp = $this->db->query($sql)->getNumRows();
		$get_content = false;
		
		$sql = "select * from acs_team_monitoring_as_of_today a 
				";
		$count_view = $this->db->query($sql)->getNumRows();
		
		// if($count_tmp==$count_view){
			$sql = "truncate acs_team_monitoring_as_of_today_view";
			$this->db->query($sql);
			
			$sql = "insert into acs_team_monitoring_as_of_today_view select * from acs_team_monitoring_as_of_today";
			$this->db->query($sql);
			
			$get_content = true;
		// }
		
		$team_id = $this->input->getPost('team_id');
		
		
		
		$where = ' where team_name is not null ';
		if($team_id != ''){
			$where = ' where ';
			$where .= ' team_id = "'.$team_id.'" and team_name is not null ';
		}
		
		$sql = "select * from acs_team_monitoring_as_of_today_view ".$where." order by team_name";
		$result = $this->db->query($sql)->getResultArray();
		
		echo json_encode($result);	
	}	
}