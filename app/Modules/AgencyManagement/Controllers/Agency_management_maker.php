<?php 
namespace App\Modules\AgencyManagement\Controllers;
use App\Modules\AgencyManagement\Models\Agency_management_maker_model;
use CodeIgniter\Cookie\Cookie;


class Agency_management_maker extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Agency_management_maker_model = new Agency_management_maker_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\AgencyManagement\Views\Settings_am_list_view', $data);
	}
	function settings_am_list(){
		$cache = session()->get('USER_ID').'_settings_am_list';
		// $data = $this->Visit_radius_maker_model->get_visit_radius_list();
		// dd($data);
		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Agency_management_maker_model->get_settings_am_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function am_add_form() {
		if($this->cache->get('PROVINSI')){
			$data['province']  =  array("" => "[SELECT DATA]") + json_decode($this->cache->get('PROVINSI'),true);
		}else{
			$data['province'] = $this->Common_model->get_record_list("provinsi value, provinsi AS item", "cms_zip_reg", "provinsi is not null", "provinsi");
			$this->cache->save('PROVINSI', json_encode($data['province']), env('TIMECACHE_2')); 
		} 

		$data["kcu_id"] = $this->Common_model->get_record_list("kcu_id AS value, kcu_name AS item", "cms_kcu", "", "");
		$data["kcu_id"] = array("" => "[SELECT DATA]") + $data["kcu_id"];
		//$data["arco_id"] = $this->Common_model->get_record_list("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on(a.group_id = b.id)", "group_id='COORDINATOR'", "level_group='ARCO'");
		$data["arco_id"] = $this->Common_model->get_record_list("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on(a.group_id = b.id) left join aav_configuration aav on aav.id=b.id", "group_id = 'AGENT_FIELD_COLLECTOR'", "a.name");
		$data["arco_id"] = array("" => "[SELECT DATA]") + $data["arco_id"];
		$data["agency_list"] = $this->Common_model->get_record_list("a.id AS value, a.name AS item", "cc_user a ", "group_id = 'AGENCY'", "a.name");
		$data["agency_list"] = array("" => "[SELECT DATA]") + $data["agency_list"];
		return view('App\Modules\AgencyManagement\Views\Am_add_form_view', $data);
	}

	function getCity()
	{
		$provinsi = $this->input->getGet('provinsi');
        $this->builder = $this->db->table('cms_zip_reg a');
        $select = array(
            'kabupaten as value',
            'kabupaten as item',
        );
        $this->builder->where('provinsi', $provinsi);
        $this->builder->orderBy('kabupaten', 'asc');
        $this->builder->groupBy('kabupaten');
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();

        // $query = $this->builder->getLastQuery(); // Mendapatkan query terakhir
        $data = '';
		$data .= '<option value="">[SELECT DATA]</option>';
		foreach ($return as $row) {
			$data .= '<option value="' . $row['value'] . '">' . $row['item'] . '</option>';
		}

        $rs = array('success' => true, 'message' => '', 'data' => $data);
        return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function getKecamatan()
	{
		$provinsi = $this->input->getGet('provinsi');
		$kota = $this->input->getGet('kota');

        $this->builder = $this->db->table('cms_zip_reg a');
        $select = array(
            'kecamatan as value',
            'kecamatan as item',
        );
        $this->builder->where('provinsi', $provinsi);
        $this->builder->where('kabupaten', $kota);
        $this->builder->orderBy('kecamatan', 'asc');
		$this->builder->groupBy('kecamatan');
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();

        // $query = $this->builder->getLastQuery(); // Mendapatkan query terakhir
        $data = '';
		$data .= '<option value="">[SELECT DATA]</option>';
		foreach ($return as $row) {
			$data .= '<option value="' . $row['value'] . '">' . $row['item'] . '</option>';
		}

        $rs = array('success' => true, 'message' => '', 'data' => $data);
        return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function getKelurahan()
	{
		$provinsi = $this->input->getGet('provinsi');
		$kota = $this->input->getGet('kota');
		$kecamatan = $this->input->getGet('kecamatan');

        $this->builder = $this->db->table('cms_zip_reg a');
        $select = array(
            'kelurahan as value',
            'kelurahan as item',
        );
        $this->builder->where('provinsi', $provinsi);
        $this->builder->where('kabupaten', $kota);
        $this->builder->where('kecamatan', $kecamatan);
        $this->builder->orderBy('kelurahan', 'asc');
		$this->builder->groupBy('kelurahan');
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();

        // $query = $this->builder->getLastQuery(); // Mendapatkan query terakhir
        $data = '';
		$data .= '<option value="">[SELECT DATA]</option>';
		foreach ($return as $row) {
			$data .= '<option value="' . $row['value'] . '">' . $row['item'] . '</option>';
		}

        $rs = array('success' => true, 'message' => '', 'data' => $data);
        return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function save_am_add(){
		$data["agency_id"]	 = $this->input->getPost("txt-am-id");
		$data["agency_name"] = $this->input->getPost("txt-am-name");
		$data["agency_address"]	 = $this->input->getPost("txt-am-address");
		$data["agency_pic"] = $this->input->getPost("txt-am-pic");
		$data["pic_email"] = $this->input->getPost("txt-am-pic-email");
		$data["spv_email"] = $this->input->getPost("txt-am-spv-email");
		$data["agency_phone"] = $this->input->getPost("txt-am-phone");
		$data["agency_prov"]	 = $this->input->getPost("opt-agency-province");
		$data["agency_city"]	 = $this->input->getPost("opt-agency-city");
		$data["agency_kec"]	 = $this->input->getPost("opt-agency-district");
		$data["agency_kel"]	 = $this->input->getPost("opt-agency-sub-district");
		$data["arco_id"]	 = $this->input->getPost("opt-am-coordinator");
		// $data["agency_fieldcoll"]	 = implode(",", $this->input->getPost("product"));
		$data["agency_contract_start"] = $this->input->getPost('datepicker');
		$data["agency_contract_end"]	 = $this->input->getPost('datepicker2');

		$data["proposal_date"]	 = $this->input->getPost('proposal_datepicker');
		$data["assessment_letter_date"]	 = $this->input->getPost('assessment_datepicker');
		$data["assessment_letter_no"]	 = $this->input->getPost('txt-assessment-no');
		$data["no_notaris"]	 = $this->input->getPost('txt-notaris-no');
		$data["tgl_notaris"]	 = $this->input->getPost('notaris_datepicker');
		$data["tgl_penawaran"]	 = $this->input->getPost('offering_datepicker');
		$data['created_by'] = session()->get('USER_ID');
		$data['created_time'] = date('Y-m-d H:i:s');
		$data["approval_notes"]	= "Add";
		$data['flag_tmp'] = '0';
		// $data['flag'] = $this->input->getPost('txt-id');
		$return = $this->Agency_management_maker_model->save_am_add($data);
		if ($return) {
			$this->Common_model->data_logging('KCU Template', $data["agency_name"], 'SUCCESS', 'Set ' . $data["agency_id"] . ' = ' . $data["agency_name"]);
			$cache = session()->get('USER_ID').'_settings_am_list_temp';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$this->Common_model->data_logging('KCU Template', $data["agency_name"], 'FAILED', 'Set ' . $data["agency_id"] . ' = ' . $data["agency_name"]);
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function am_edit_form(){
		$data["agency_id"]	= $this->input->getGet('id');

		// kirim area
		$data["kcu_id"] = $this->Common_model->get_record_list("kcu_id AS value, kcu_name AS item", "cms_kcu", "", "");
		$data["kcu_id"] = array("" => "[SELECT DATA]") + $data["kcu_id"];

		$data["arco_id"] = $this->Common_model->get_record_list("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on(a.group_id = b.id) left join aav_configuration aav on aav.id=b.id", "aav.parameter='LEVEL_GROUP'", "a.name");
		$data["arco_id"] = array("" => "[SELECT DATA]") + $data["arco_id"];

		$data["agency_data"] = $this->Common_model->get_record_values("*", "cms_agency", "agency_id = '" . $data["agency_id"] . "'");
		$data["agency_list"] = $this->Common_model->get_record_list("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on(a.group_id = b.id) left join aav_configuration aav on aav.id=b.id", "b.id = 'AGENCY'", "a.name");
		$data["agency_list"] = array("" => "[SELECT DATA]") + $data["agency_list"];
		$data["agency_data_fieldcoll"] = $this->Common_model->get_record_values("*", "cc_user", "id in ('" . str_replace(",", "','", $data["agency_data"]["agency_fieldcoll"] ?? '') . "')");
		
		if($this->cache->get('PROVINSI')){
			$data['data_province']  =  array("" => "[SELECT DATA]") + json_decode($this->cache->get('PROVINSI'),true);
		}else{
			$data['data_province'] = $this->Common_model->get_record_list("provinsi value, provinsi AS item", "cms_zip_reg", "provinsi is not null", "provinsi");
			$this->cache->save('PROVINSI', json_encode($data['data_province']), env('TIMECACHE_2')); 
		} 

		$data['data_city'] = $this->Common_model->get_record_list("kabupaten value, kabupaten AS item", "cms_zip_reg", "kabupaten='" . $data["agency_data"]["agency_city"] . "'", "kabupaten") + $this->Common_model->get_record_list("kabupaten value, kabupaten AS item", "cms_zip_reg", "provinsi='" . $data["agency_data"]["agency_prov"] . "'", "kabupaten");  
		$data['data_kecamatan'] = $this->Common_model->get_record_list("kecamatan value, kecamatan AS item", "cms_zip_reg", "kecamatan='" . $data["agency_data"]["agency_kec"] . "'", "kecamatan") +  $this->Common_model->get_record_list("kecamatan value, kecamatan AS item", "cms_zip_reg", "kabupaten='" . $data["agency_data"]["agency_city"] . "' and provinsi='" . $data["agency_data"]["agency_prov"] . "'", "kecamatan"); 
		$data['data_kelurahan'] = $this->Common_model->get_record_list("kelurahan value, kelurahan AS item", "cms_zip_reg", "kelurahan='" . $data["agency_data"]["agency_kel"] . "'", "kelurahan") +  $this->Common_model->get_record_list("kelurahan value, kelurahan AS item", "cms_zip_reg", "kecamatan='" . $data["agency_data"]["agency_kec"] . "' and kabupaten='" . $data["agency_data"]["agency_city"] . "' ", "kelurahan"); 
		return view('App\Modules\AgencyManagement\Views\Am_edit_form_view', $data);
	}
	function save_am_edit(){
		$data["agency_id"]	 = $this->input->getPost("txt-am-id");
		$data["agency_name"] = $this->input->getPost("txt-am-name");
		$data["agency_address"]	 = $this->input->getPost("txt-am-address");
		$data["agency_pic"] = $this->input->getPost("txt-am-pic");
		$data["pic_email"] = $this->input->getPost("txt-am-pic-email");
		$data["spv_email"] = $this->input->getPost("txt-am-spv-email");
		$data["agency_phone"] = $this->input->getPost("txt-am-phone");
		$data["agency_prov"]	 = $this->input->getPost("opt-agency-province");
		$data["agency_city"]	 = $this->input->getPost("opt-agency-city");
		$data["agency_kec"]	 = $this->input->getPost("opt-agency-district");
		$data["agency_kel"]	 = $this->input->getPost("opt-agency-sub-district");
		$data["arco_id"]	 = $this->input->getPost("opt-am-coordinator");
		// $data["agency_fieldcoll"]	 = implode(",", $this->input->getPost("product"));
		$data["agency_contract_start"] = $this->input->getPost('datepicker');
		$data["agency_contract_end"]	 = $this->input->getPost('datepicker2');

		$data["proposal_date"]	 = $this->input->getPost('proposal_datepicker');
		$data["assessment_letter_date"]	 = $this->input->getPost('assessment_datepicker');
		$data["assessment_letter_no"]	 = $this->input->getPost('txt-assessment-no');
		$data["no_notaris"]	 = $this->input->getPost('txt-notaris-no');
		$data["tgl_notaris"]	 = $this->input->getPost('notaris_datepicker');
		$data["tgl_penawaran"]	 = $this->input->getPost('offering_datepicker');
		$data['created_by'] = session()->get('USER_ID');
		$data['created_time'] = date('Y-m-d H:i:s');
		$data["approval_notes"]	= "Edit";
		$data['flag_tmp'] = '0';

		$return = $this->Agency_management_maker_model->save_am_edit($data);
		if ($return) {
			$this->Common_model->data_logging('KCU Template', $data["agency_name"], 'SUCCESS', 'Set ' . $data["agency_id"] . ' = ' . $data["agency_name"]);
			$cache = session()->get('USER_ID').'_settings_am_list_temp';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$this->Common_model->data_logging('KCU Template', $data["agency_name"], 'FAILED', 'Set ' . $data["agency_id"] . ' = ' . $data["agency_name"]);
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function delete_agency_to_waiting_approval(){
		$id	 = $this->input->getGet("id");

		$return = $this->Agency_management_maker_model->delete_agency_to_waiting_approval($id);
		if ($return) {
			$cache = session()->get('USER_ID').'_settings_am_list';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to delete data', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

}