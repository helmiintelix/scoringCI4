<?php 
namespace App\Modules\UserManagementApproval\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\UserManagementApproval\Models\User_management_approval_model;


class User_management_approval extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->User_management_approval_model = new User_management_approval_model();
	}

	function index()
	{
		
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\UserManagementApproval\Views\User_management_view_temp',$data);
	}

	function user_management_list_temp()
	{

			$data = $this->User_management_approval_model->get_user_management_list_temp();

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function user_view_detail_form_approval(){
		$data["id_user"]	= $this->input->getGet("id");
		$data["flag"]	= $this->input->getGet("flag");

		if(getenv('TOKEN')){
			$data['NEED_TOKEN'] = '1';
		}else{
			$data['NEED_TOKEN'] = '0';
		}

		if($data["flag"]=="ADD"){
			$data["user_data"] = $this->Common_model->get_record_values("id AS id_user, nik, employeeid, address, name AS name_user, password AS id_user_password, group_id AS user_level, report_to, is_active AS flag_active, kcu, area, agent_bucket, bisnis_unit, kcu_list,kuota_request_extend, kuota_keep_extend, email,handphone,template_email,template_sms,bucket ,receive_assigment,realocate_account,leave_start_date,image,supervisor_name,imei,join_date,type_collection,token", "cc_user_tmp", "id = '" . $data["id_user"] . "'");
			$data["group_name"] = $this->Common_model->get_record_value("name", "cc_user_group", "id = '" . $data['user_data']["user_level"] . "'");
			$data["supervisor_name"] = $this->Common_model->get_record_value("name", "cc_user", "id = '" . $data['user_data']["supervisor_name"] . "'");
			return view('\App\Modules\UserManagementApproval\Views\Approval_user_add_form_view',$data);

		}else if($data["flag"]=='EDIT'){
			$builder = $this->db->table("cc_user_tmp a");
			$builder->select("
				a.id AS id_user,
				a.nik,
				a.employeeid,
				a.address,
				a.name AS name_user,
				a.password AS id_user_password,
				b.name AS user_level,
				a.report_to,
				a.is_active AS flag_active,
				a.kcu,
				a.area,
				a.agent_bucket,
				a.bisnis_unit,
				a.email,
				a.handphone,
				a.image,
				c.name AS supervisor_name,
				a.imei,
				a.join_date,
				a.type_collection,
				a.token
			");
			$builder->join("cc_user_group b", "b.id=a.group_id");
			$builder->join("cc_user c", "a.supervisor_name=c.id", "left");
			$builder->where("a.id", $data["id_user"]);
			$query = $builder->get();
			$arr_data = array();
			if ($query->getNumRows())
			{
				foreach ($query->getResult() as $row)
				{
				foreach ($row as $key => $value){
				$arr_data[$key] = $value;  
					}
				}
			}

			$data["after"] = $arr_data;

			$builder = $this->db->table("cc_user a");
			$builder->select("
				a.id AS id_user, 
				a.nik, 
				a.employeeid, 
				a.address, 
				a.name AS name_user, 
				a.password AS id_user_password, 
				b.name AS user_level, 
				a.report_to, 
				a.is_active AS flag_active, 
				a.kcu, 
				a.area, 
				a.agent_bucket, 
				a.bisnis_unit, 
				a.email,
				a.handphone,
				a.image,
				c.name supervisor_name,
				a.imei,
				a.join_date,
				a.type_collection,
				a.token
			");
			$builder->join("cc_user_group b", "b.id=a.group_id ");
			$builder->join("cc_user c", "a.supervisor_name=c.id", "left");
			$builder->where("a.id", $data["id_user"]);
			$query = $builder->get();
			$arr_data2 = array();
			if ($query->getNumRows())
			{
				foreach ($query->getResult() as $row)
				{
				foreach ($row as $key => $value){
				$arr_data2[$key] = $value;  
					}
				}
			}
			$data["before"] = $arr_data2;
					
			$diff1 = array_diff($data["before"],$data["after"]);
			$diff2 = array_diff($data["after"],$data["before"]);
			$data['diff'] = json_encode(array_merge($diff1,$diff2));
			return view('\App\Modules\UserManagementApproval\Views\Approval_user_edit_form_view',$data);
		}
		
	}

	function save_user_edit_temp(){
		$user_data["id"]		= $this->input->getGet('id');
		$status		= $this->input->getGet('status');
		$return	= $this->User_management_approval_model->save_user_edit_temp($user_data,$status);
		if($return){
			$cache = session()->get('USER_ID').'_user_management_list_temp';
			$this->cache->delete($cache);

			$cache = session()->get('USER_ID').'_user_management_list';
			$this->cache->delete($cache);

			$rs = array('success' => true, 'message' => 'Success', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

	function delete_user()
	{
		$user_data['id']	= $this->input->getGet('id_user');
		$user_data['note']		= $this->input->getGet('note');

		$return	= $this->User_management_approval_model->delete_user($user_data);
		$this->Common_model->data_logging('User Management', "Delete user", 'SUCESS', 'User ID: ' . $user_data['id']);

		if($return){
			$cache = session()->get('USER_ID').'_user_management_list_temp';
			$this->cache->delete($cache);

			$this->Common_model->data_logging('User Management', 'Delete user', 'SUCCESS', 'User ID: ' . $user_data["id"]);
			$rs = array('success' => true, 'message' => "REJECT {$user_data['id']} is Success", 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$this->Common_model->data_logging('User Management', 'Delete user', 'SUCCESS', 'User ID: ' . $user_data["id"]);
			$rs = array('success' => false, 'message' => "REJECT {$user_data['id']} is Failed", 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
	}
}