<?php
namespace App\Modules\TeamManagement\Controllers;


class TeamWork extends \App\Controllers\BaseController
{
	function index()
	{
		$test= array();
		$builder = $this->db->table('cc_user');

		// Kolom avatar berdasarkan DB driver
		if (in_array($this->DBDRIVER, ['SQLSRV', 'Postgre'])) {
			$avatarColumn = "SUBSTRING(name, 1, 1) AS avatar"; // SQL Server menggunakan SUBSTRING()
		} else {
			$avatarColumn = "SUBSTR(name, 1, 1) AS avatar"; // MySQL menggunakan SUBSTR()
		}

		// Query Builder
		$builder->select([
			'id',
			'name',
			$avatarColumn,
		]);

		$query = $builder->get();
		$result = $query->getResultArray();

		foreach($result as $value)
		{
			$test[$value['id']] = array(
					"name" => $value["name"],
					"avatar" => $value["avatar"],
				);
		}

		// var_dump($test); die;
		$data["avatar"] = $test;

		return view('\App\Modules\TeamManagement\Views\Team_work_view',$data,['cache' => 60]);
	}

	function get_team_work_list_ava()
	{

		$cache = session()->get('USER_ID').'_get_team_work_list_ava';

		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data =   $this->TeamWorkModel->get_team_work_list_ava();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function get_team_work_list()
	{
		$data = $this->team_work_model->get_team_work_list();
		echo $data;
	}

	function add_team_form()
	{
		if (in_array($this->DBDRIVER, ['SQLSRV', 'Postgre'])) {
			$groupConcat = "STRING_AGG(team_leader, '|') AS leader_not_in";
		}else{
			$groupConcat = "GROUP_CONCAT(team_leader SEPARATOR '|') AS leader_not_in";
		}
		$leader_not = $this->Common_model->get_record_value($groupConcat, "cms_team", "team_id != ''", "name");

		$not_agent = $this->Common_model->get_record_list(" team_id as value, agent_list as item", "cms_team", " team_id != '' ", "");
		$agent_not = implode('|', $not_agent);
		
		
		
		$builder = $this->db->table('cc_user a');
		$builder->select([
			'a.id AS id',
			'a.name AS name',
		]);

		// Handle CONCAT function for different databases
		if ($this->DBDRIVER === 'SQLSRV') {
			$builder->select("'' + a.image AS image", false); // SQL Server concatenation
		} elseif ($this->DBDRIVER === 'Postgre') {
			$builder->select("CONCAT('', a.image) AS image", false); // PostgreSQL CONCAT
		} else {
			$builder->select("CONCAT('', a.image) AS image", false); // MySQL CONCAT
		}

		$builder->select([
			'a.email',
			'a.type_collection AS type_collection',
			'a.handphone',
		]);

		// Add JOIN
		$builder->join('cc_user_group b', 'b.id = a.group_id');

		// Add WHERE conditions
		$builder->whereIn('b.level_group', ['TELECOLL', 'COLLECTOR', 'AGENT_FIELD_COLLECTOR']);
		if (!empty($agent_not)) {
			$builder->whereNotIn('a.id', explode('|', $agent_not));
		}
		$builder->where([
			'a.type_collection' => 'TeleColl',
			'a.is_active'       => '1',
		]);

		// Add ORDER BY
		$builder->orderBy('a.name', 'ASC');

		// Execute query
		$query = $builder->get();
		$result = $query->getResultArray();

		foreach ($result as $key => $value) {
			if($value['image'] == ''){
				$result[$key]['image'] = base_url().'assets/profilePicture/person-circle.svg';
			}else{
				if(file_exists('./uploads/user/'.$value['image'])){
					$result[$key]['image'] = base_url().'/uploads/user/'.$value['image'];
				}else{
					$result[$key]['image'] = base_url().'assets/profilePicture/person-circle.svg';
				}
			}
		}
		
		$data['agent_list'] = $result;

		$data['fildcoll_list'] = $this->Common_model->get_record_list("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on (b.id = a.group_id)", "level_group in('FIELD_COLL') AND a.id NOT IN ('".str_replace("|", "','", $agent_not)."') and a.is_active='1'", "a.name");
		
		$sql = "SELECT a.id as value, a.name as item FROM cc_user a JOIN cc_user_group b ON (b.id = a.group_id) 
		WHERE level_group = 'TEAM_LEADER' 
		AND b.id NOT IN ('".str_replace("|", "','", $leader_not)."') and a.is_active = '1'";
		
		$data['coord_list'] = $this->db->query($sql)->getResultArray();
		
		if(session()->get("LEVEL_GROUP") == "SUPERVISOR"){
			$data['spv_list'] = $this->Common_model->get_ref_master("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on (b.id = a.group_id)", "level_group = 'SUPERVISOR' AND a.id = '".$this->session->userdata("USER_ID")."'", "a.name");
		} else {
			$data['spv_list'] = $this->Common_model->get_ref_master("a.id AS value, a.name AS item", "cc_user a JOIN cc_user_group b ON (b.id = a.group_id) JOIN aav_configuration aa ON b.level_group=aa.name", "aa.parameter='LEVEL_GROUP' AND aa.VALUE > '3'", "a.name");
		}
		return view('\App\Modules\TeamManagement\Views\add_team_form_view', $data);
	}
	

	function save_team()
	{
		$data['team_name'] 	 = $this->input->getPost('team_name');
		$data['coord_list']  = $this->input->getPost('coord_list');
		$data['tom_agent'] 	 = $this->input->getPost('tom_agent');
		$data['description'] = $this->input->getPost('description');
		$data['type_collection']	 = $this->input->getPost('opt-type_collection');
		$data['flag_team']	 = $this->input->getPost('opt_coll_type');
		$data['spv']  = $this->input->getPost('spv_list');
			

		$return = $this->TeamWorkModel->save_team($data);
		
		// return $this->response->setStatusCode(200)->setJSON($return);
	}

}