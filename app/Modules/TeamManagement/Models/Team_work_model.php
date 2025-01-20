<?php
namespace App\Modules\TeamManagement\models;
use App\Models\Common_model;
use CodeIgniter\Model;

Class Team_work_model Extends Model 
{
	function __construct()
	{
		parent::__construct();
		$this->Common_model = new Common_model();
	}

	function get_team_work_list_ava()
    {
       
        $this->builder = $this->db->table('cms_team');
        
		$select = array('*');
		$this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        // Select Data
        

        if (!empty($where)) {

			$this->builder->where($where);
        }
		$this->builder->orderBy('team_name desc');

        $rResult = $this->builder->get();

        $return = $rResult->getResultArray();

        $result = array();

        foreach ($rResult->getFieldNames() as $key => $value) {
            $result['header'][] = array('field' => $value);
        }
    
        $result['data'] = $return;

        
       return $result;
    }

    function save_team($param)
	{
    
			
		if(empty($param['team_name'])){
			return array('success'=>false, 'message' => 'team name is empty');
		}

		
		//$is_exist = $this->Common_model->get_record_value('count(*)', 'cms_team', 'team_name like "%'.$this->db->escapeLikeString($param['team_name']).'%"');
		$cm_customer_nmbr = $this->Common_model->get_record_value("CM_CUSTOMER_NMBR","cpcrd_new","CM_CARD_NMBR = '1111111111' ");
		echo "string2";
		/*
		if($is_exist > 0){
			return array('success'=>false, 'message' => 'Team already exist');
		}else{
			
			$data = array(
							'team_id' => uuid(false),
							'team_name' => $param['team_name'],
							'team_leader' => $param['coord_list'],
							'supervisor' => $param['spv'],
							'description' => $param['description'],
							'created_by'  => session()->get('USER_ID'),
							'created_time'  => date('Y-m-d H:i:s'),
							'flag_team' => $param['flag_team'],
					);
			
			
			if($param['type_collection'] == 'TeleColl')
			{
				foreach($param['tom_agent'] as $val){
					$agent_exist = $this->Common_model->get_record_value('count(*) jml', 'cms_team', 'agent_list like "%'.$this->db->escapeLikeString($val).'%"');
					if($agent_exist > 0){
						return array('success'=>false, 'message' => 'agent '.$this->Common_model->get_record_value('name', 'cc_user',"id = '".$this->db->escapeString($val)."'",'').' already to assignment');
						break;
					}
				}
				foreach($param['tom_agent'] as $val){
					$updateData = [
					   'team_id' => $data['team_id'],
					];
					$this->db->where('id', $val);
					$this->db->update('cc_user', $updateData); 

					$this->db->delete('acs_agent_assignment',array('user_id' => $val)); 

					$data_acs_agent_assignment['id'] 			= uuid(false);
					$data_acs_agent_assignment['user_id'] 		= $val;
					$data_acs_agent_assignment['team'] 			= $data['team_id'];
					$data_acs_agent_assignment['created_by'] 	= session()->get('USER_ID');
					$data_acs_agent_assignment['created_time'] 	= date('Y-m-d H:i:s');

					$this->db->insert('acs_agent_assignment',$data_acs_agent_assignment);
					
				}
				$data['agent_list'] = implode('|',$param['tom_agent']);
			}else{
				foreach($data['tom_agent'] as $val){
					$agent_exist = $this->Common_model->get_record_value('count(*) jml', 'cms_team', 'agent_list like "%'.$this->db->escapeLikeString($val).'%"');
					if($agent_exist > 0){
						return array('success'=>false, 'message' => 'agent '.$this->common_model->get_record_value('name', 'cc_user',"id = '".$this->db->escapeString($val)."'",'').' already to assignment');
						break;
					}
				}
				foreach($param['tom_agent'] as $val){
					$updateData = [
					   'team_id' => $data['team_id'],
					];
					$this->db->where('id', $val);
					$this->db->update('cc_user', $updateData); 
					
					
				}
				$data['agent_list'] = implode('|', $param['tom_agent']);
			}
			
			
			
			if($this->db->insert('cms_team', $data)){
				return array('success'=>true, 'message' => 'data has been saved');
			}else{
				return array('success'=>false, 'message' => 'data failed to saved');
			}
		}*/
	}
	
}