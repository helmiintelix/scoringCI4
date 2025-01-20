<?php 
namespace App\Modules\CallingBaseReport\Controllers;
use App\Modules\CallingBaseReport\Models\Calling_base_report_model;
use CodeIgniter\Cookie\Cookie;
use DateTime;

class Calling_base_report extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Calling_base_report_model = new Calling_base_report_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		// dd($data);
		return view('\App\Modules\CallingBaseReport\Views\Report_calling_base_view', $data);
	}
	function calling_base_data(){
		$dataInput['start_date'] = $this->input->getGet('start_date');
		$dataInput['end_date'] = $this->input->getGet('end_date');
		$dataInput['bucket'] = $this->input->getGet('bucket');
		$dataInput['class_id'] = $this->input->getGet('class_id');
		$dataInput['sub_product'] = $this->input->getGet('sub_product');
		$dataInput['keyword'] = $this->input->getGet('keyword');
		$dataInput['search_by'] = $this->input->getGet('search_by');
		// print_r($dataInput);
		// exit();
		$data = $this->Calling_base_report_model->get_calling_base_data($dataInput);
	    if ($data) {
			$cacheKey = session()->get('USER_ID') . '_calling_base_data_list';
			$this->cache->delete($cacheKey);
			$this->cache->save($cacheKey, json_encode($data), env('TIMECACHE_1'));
	
			$rs = ['success' => true, 'message' => 'Success to apply filter', 'data' => $data];
		} else {
			$rs = ['success' => false, 'message' => 'failed', 'data' => null];
		}
		// if($this->cache->get($cache)){
		// 	$data = json_decode($this->cache->get($cache));
		// 	$rs = array('success' => true, 'message' => '', 'data' => $data);
		// }else{
		// 	$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

		// 	$rs = array('success' => true, 'message' => '', 'data' => $data);
		// }
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function get_search_list()
	{

		// var_dump($this->session->userdata('GROUP_LEVEL'));die;
		switch (session()->get('GROUP_LEVEL')) {
			case "SUPERVISOR":
				$data["group_kcu"] = array("" => "--PILIH--") + $this->Common_model->get_record_list("kcu_id AS value, concat(kcu_id,' - ',kcu_name) AS item", "cms_kcu", "flag = '1' AND flag_tmp = '1' ", "kcu_name");
				$data["team"] = array("" => "--PILIH--") + $this->Common_model->get_record_list("team_id AS value, team_name AS item", "cms_team", "", "team_name");;

				$arr_agent = $this->Common_model->get_agent_member();

				$data['petugas'] = array("" => "--PILIH--") + $this->Common_model->get_record_list("a.id AS value, concat(a.id,' - ',a.name) AS item", "cc_user a join cc_user_group b on( a.group_id = b.id and b.level_group in('FIELD_COLL','COLLECTOR', 'TELECOLL'))", "a.is_active = '1' and a.id in('" . str_replace(",", "','", implode(",", $arr_agent)) . "')", "a.name");
				// var_dump($data['petugas']);die;
				break;

			case "TEAM_LEADER":
				$data["group_kcu"] = array("" => "--PILIH--") + $this->Common_model->get_record_list("kcu_id AS value, concat(kcu_id,' - ',kcu_name) AS item", "cms_kcu", "flag = '1' AND flag_tmp = '1' ", "kcu_name");
				$data["team"] = array("" => "--PILIH--") + $this->Common_model->get_record_list("team_id AS value, team_name AS item", "cms_team", "", "team_name");;

				$arr_agent = $this->Common_model->get_agent_member();

				$data['petugas'] = array("" => "--PILIH--") + $this->Common_model->get_record_list("a.id AS value, concat(a.id,' - ',a.name) AS item", "cc_user a join cc_user_group b on( a.group_id = b.id and b.level_group in('FIELD_COLL','COLLECTOR', 'TELECOLL'))", "a.is_active = '1' and a.id in('" . str_replace(",", "','", implode(",", $arr_agent)) . "')", "a.name");;
				break;

			case "ARCO":
			case "AGENT":
				$data["group_kcu"] = array("" => "--PILIH--") + $this->Common_model->get_record_list("kcu_id AS value, concat(kcu_id,' - ',kcu_name) AS item", "cms_kcu", "flag = '1' AND flag_tmp = '1' ", "kcu_name");
				$data["team"] = array("" => "--PILIH--") + $this->Common_model->get_record_list("team_id AS value, team_name AS item", "cms_team", "", "team_name");;
				$data['petugas'] = array("" => "--PILIH--") + $this->Common_model->get_record_list("a.id AS value, concat(a.id,' - ',a.name) AS item", "cc_user a join cc_user_group b on( a.group_id = b.id and b.level_group in('FIELD_COLL','COLLECTOR', 'TELECOLL'))", "a.is_active = '1' and a.id='" . $this->session->userdata("USER_ID") . "'", "a.name");;
				break;

			case "FC":
				$data["group_kcu"] = array("" => "--PILIH--") + $this->Common_model->get_record_list("kcu_id AS value, concat(kcu_id,' - ',kcu_name) AS item", "cms_kcu", "flag = '1' AND flag_tmp = '1'  and kcu_id='" . $this->session->userdata('KCU') . "'", "kcu_name");
				$data["kcu"] = array("" => "--PILIH--");
				$data["area"] = array("" => "--PILIH--");
				$data['petugas'] = array("" => "--PILIH--");
				break;
			default:
				$data["group_kcu"] = array("" => "--PILIH--") + $this->Common_model->get_record_list("kcu_id AS value, concat(kcu_id,' - ',kcu_name) AS item", "cms_kcu", "flag = '1' AND flag_tmp = '1' ", "kcu_name");
				$data["team"] = array("" => "--PILIH--") + $this->Common_model->get_record_list("team_id AS value, team_name AS item", "cms_team", "", "team_name");;
				$data['petugas'] = array("" => "--PILIH--") + $this->Common_model->get_record_list("a.id AS value, concat(a.id,' - ',a.name) AS item", "cc_user a join cc_user_group b on( a.group_id = b.id and b.level_group in('FIELD_COLL', 'TELECOLL'))", "a.is_active = '1' ", "a.name");;

				break;
		}
		return $data;
	}
}