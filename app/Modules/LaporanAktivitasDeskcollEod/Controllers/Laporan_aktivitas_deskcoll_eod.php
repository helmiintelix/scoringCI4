<?php 
namespace App\Modules\LaporanAktivitasDeskcollEod\Controllers;
use App\Modules\LaporanAktivitasDeskcollEod\Models\Laporan_aktivitas_deskcoll_eod_model;
use CodeIgniter\Cookie\Cookie;
use DateTime;

class Laporan_aktivitas_deskcoll_eod extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Laporan_aktivitas_deskcoll_eod_model = new Laporan_aktivitas_deskcoll_eod_model();
	}

	function index(){
		$data = $this->get_search_list();
		$data['classification'] = $this->input->getPost('classification');
		// dd($data);
		return view('\App\Modules\LaporanAktivitasDeskcollEod\Views\Report_activity_dc_view', $data);
	}
	function get_list_report_aktifitas_dc(){
		$dataInput['tgl_from'] = '';
		$dataInput['tgl_to'] = '';
		$tgl = $this->input->getGet('tgl');
		$dataInput['agent'] = $this->input->getGet('agent');
		$date = explode(' - ', $tgl);
		if (count($date) == 2) {
			$dataInput['tgl_from'] = DateTime::createFromFormat('d/m/Y', $date[0])->format('Y-m-d');
			
			// Ubah format tanggal dari dd/mm/yyyy ke dd-mm-yyyy untuk tanggal akhir
			$dataInput['tgl_to']= DateTime::createFromFormat('d/m/Y', $date[1])->format('Y-m-d');
		}
		// print_r($dataInput);
		// exit();
		$data = $this->Laporan_aktivitas_deskcoll_eod_model->get_list_report_aktifitas_dc($dataInput);
	    if ($data) {
			$cacheKey = session()->get('USER_ID') . '_list_report_aktifitas_dc';
			$this->cache->delete($cacheKey);
			$this->cache->save($cacheKey, json_encode($data), env('TIMECACHE_1'));
	
			$rs = ['success' => true, 'message' => 'Success to apply filter', 'data' => $data];
		} else {
			$rs = ['success' => false, 'message' => 'failed', 'data' => null];
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function get_search_list()
	{

		// var_dump(session()->get('GROUP_LEVEL'));die;
		switch (session()->get('GROUP_LEVEL')) {
			case "SUPERVISOR":
				$data["group_kcu"] = array(""=>"--PILIH--")+ $this->Common_model->get_record_list("kcu_id AS value, concat(kcu_id,' - ',kcu_name) AS item", "cms_kcu", "flag = '1' AND flag_tmp = '1' ", "kcu_name" );			
				$data["team"] = array(""=>"--PILIH--")+ $this->Common_model->get_record_list("team_id AS value, team_name AS item", "cms_team", "", "team_name" );			;

				$arr_agent = $this->Common_model->get_agent_member();

				$data['petugas'] = array(""=>"--PILIH--") + $this->Common_model->get_record_list("a.id AS value, concat(a.id,' - ',a.name) AS item", "cc_user a join cc_user_group b on( a.group_id = b.id and b.level_group in('TELECOLL'))", "a.is_active = '1' and a.id in('".str_replace(",","','", implode(",",$arr_agent)) ."')", "a.name" );			
				// echo $this->db->last_query();
				break;

			case "TEAM_LEADER":
				$data["group_kcu"] = array(""=>"--PILIH--")+ $this->Common_model->get_record_list("kcu_id AS value, concat(kcu_id,' - ',kcu_name) AS item", "cms_kcu", "flag = '1' AND flag_tmp = '1' ", "kcu_name" );			
				$data["team"] = array(""=>"--PILIH--")+ $this->Common_model->get_record_list("team_id AS value, team_name AS item", "cms_team", "", "team_name" );			;

				$arr_agent = $this->Common_model->get_agent_member();

				$data['petugas'] = array(""=>"--PILIH--") + $this->Common_model->get_record_list("a.id AS value, concat(a.id,' - ',a.name) AS item", "cc_user a join cc_user_group b on( a.group_id = b.id and b.level_group in('TELECOLL'))", "a.is_active = '1' and a.id in('".str_replace(",","','", implode(",",$arr_agent)) ."')", "a.name" );			;
				break;

			case "ARCO":
			case "AGENT":
				$data["group_kcu"] = array(""=>"--PILIH--")+ $this->Common_model->get_record_list("kcu_id AS value, concat(kcu_id,' - ',kcu_name) AS item", "cms_kcu", "flag = '1' AND flag_tmp = '1' ", "kcu_name" );			
				$data["team"] = array(""=>"--PILIH--")+ $this->Common_model->get_record_list("team_id AS value, team_name AS item", "cms_team", "", "team_name" );			;
				$data['petugas'] = array(""=>"--PILIH--") + $this->Common_model->get_record_list("a.id AS value, concat(a.id,' - ',a.name) AS item", "cc_user a join cc_user_group b on( a.group_id = b.id and b.level_group in('TELECOLL'))", "a.is_active = '1' and a.id='".session()->get("USER_ID") ."'", "a.name" );			;
				break;
			case "FC":
				$data["group_kcu"] = array(""=>"--PILIH--")+ $this->Common_model->get_record_list("kcu_id AS value, concat(kcu_id,' - ',kcu_name) AS item", "cms_kcu", "flag = '1' AND flag_tmp = '1'  and kcu_id='".session()->get('KCU')."'", "kcu_name");			
				$data["kcu"] = array(""=>"--PILIH--");
				$data["area"] = array(""=>"--PILIH--");
				$data['petugas'] = array(""=>"--PILIH--");
				break;
			default:
				$data["group_kcu"] = array(""=>"--PILIH--")+ $this->Common_model->get_record_list("kcu_id AS value, concat(kcu_id,' - ',kcu_name) AS item", "cms_kcu", "flag = '1' AND flag_tmp = '1' ", "kcu_name" );			
				$data["team"] = array(""=>"--PILIH--")+ $this->Common_model->get_record_list("team_id AS value, team_name AS item", "cms_team", "", "team_name" );			;
				$data['petugas'] = array(""=>"--PILIH--") + $this->Common_model->get_record_list("a.id AS value, concat(a.id,' - ',a.name) AS item", "cc_user a join cc_user_group b on( a.group_id = b.id and b.level_group in('TELECOLL'))", "a.is_active = '1' ", "a.name" );			;
		
				break;
		}
		return $data;
	}
}