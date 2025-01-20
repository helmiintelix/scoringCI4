<?php 
namespace App\Modules\ReportVisitLuarRadiusGeofencing\Controllers;
use App\Modules\ReportVisitLuarRadiusGeofencing\Models\Report_visit_luar_radius_geofencing_model;
use CodeIgniter\Cookie\Cookie;


class Report_visit_luar_radius_geofencing extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Report_visit_luar_radius_geofencing_model = new Report_visit_luar_radius_geofencing_model();
	}

	function index(){
		$data = $this->get_search_list();
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\ReportVisitLuarRadiusGeofencing\Views\Report_visit_radius_view', $data);
	}
	function get_search_list()
	{
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
	function get_report_visit_radius(){
		$cache = session()->get('USER_ID').'_report_visit_radius';
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Report_visit_luar_radius_geofencing_model->get_report_visit_radius();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 
			
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function tracking_history(){
		$dataInput['user_id'] = $this->input->getGet('user_id');
		$data['gmapApiKey'] = getenv('gmap_apikey');
		$data["history"] = $this->Report_visit_luar_radius_geofencing_model->tracking_history($dataInput);
		return view('\App\Modules\ReportVisitLuarRadiusGeofencing\Views\Tracking_history_visit_view', $data);

	}
}