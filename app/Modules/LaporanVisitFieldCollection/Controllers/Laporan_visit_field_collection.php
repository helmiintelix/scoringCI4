<?php 
namespace App\Modules\LaporanVisitFieldCollection\Controllers;
use App\Modules\LaporanVisitFieldCollection\Models\Laporan_visit_field_collection_model;
use CodeIgniter\Cookie\Cookie;
use DateTime;

class Laporan_visit_field_collection extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Laporan_visit_field_collection_model = new Laporan_visit_field_collection_model();
	}

	function index(){
		$data = $this->get_search_list();
		$data['classification'] = $this->input->getPost('classification');
		$builder = $this->db->table('acs_cms_product');
        $builder->select('productsubcategory');
        $builder->orderBy('productsubcategory');
        $temp = $builder->get()->getResultArray();
		array_unshift($temp, array("productsubcategory"=>"ALL"));
		$data["product"] = $temp;
		$data['bucket'] = array(""=>"--PILIH--")+ $this->Common_model->get_record_list("bucket_id AS value, bucket_label item", "cms_bucket", "is_active = '1' ", "bucket_label" );	
		$data['user'] = array(""=>"--PILIH--")+ $this->Common_model->get_record_list("id AS value, name item", "cc_user", "group_id = 'AGENT_FIELD_COLLECTOR' AND is_active = '1' ", "name" );	
		// dd($data);
		return view('\App\Modules\LaporanVisitFieldCollection\Views\Report_visit_field_view', $data);
	}
	function get_report_visit_field_list(){
		$dataInput['tgl_from'] = '';
		$dataInput['tgl_to'] = '';
		$tgl = $this->input->getGet('tgl');
		$dataInput['user'] = $this->input->getGet('user');
		$dataInput['bucket'] = $this->input->getGet('bucket');
		$dataInput['product'] = $this->input->getGet('product');
		$dataInput['no_pinjaman'] = $this->input->getGet('no_pinjaman');
		$date = explode(' - ', $tgl);
		if (count($date) == 2) {
			$dataInput['tgl_from'] = DateTime::createFromFormat('d/m/Y', $date[0])->format('Y-m-d');
			
			// Ubah format tanggal dari dd/mm/yyyy ke dd-mm-yyyy untuk tanggal akhir
			$dataInput['tgl_to']= DateTime::createFromFormat('d/m/Y', $date[1])->format('Y-m-d');
		}
		// print_r($dataInput);
		// exit();
		$data = $this->Laporan_visit_field_collection_model->get_report_visit_field_list($dataInput);
	    if ($data) {
			$cacheKey = session()->get('USER_ID') . '_report_visit_field_list';
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