<?php 
namespace App\Modules\SetUpBranch\Controllers;
use App\Modules\SetUpBranch\Models\Set_up_branch_maker_model;
use App\Modules\SetUpBranch\Models\Set_up_branch_temp_model;
use CodeIgniter\Cookie\Cookie;


class Set_up_branch_maker extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Set_up_branch_maker_model = new Set_up_branch_maker_model();
		$this->Set_up_branch_temp_model = new Set_up_branch_temp_model();
		// $this->Set_up_branch_temp_model = new Set_up_branch_temp_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\SetUpBranch\Views\Setup_branch_maker_view', $data);
	}
	function branch_list(){
		$cache = session()->get('USER_ID').'_branch_list';
		
		$data = $this->Set_up_branch_maker_model->get_branch_list();
		$rs = array('success' => true, 'message' => '', 'data' => $data);
		// if($this->cache->get($cache)){
		// 	$data = json_decode($this->cache->get($cache));
		// 	$rs = array('success' => true, 'message' => '', 'data' => $data);
		// }else{
		// 	$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

		// 	$rs = array('success' => true, 'message' => '', 'data' => $data);
		// }
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function branch_add_form() {
		
		if($this->cache->get('PROVINSI')){
			$data['province']  = json_decode($this->cache->get('PROVINSI'),true);
		}else{
			$data['province'] = $this->Common_model->get_record_list("provinsi value, provinsi AS item", "cms_zip_reg", "provinsi is not null", "provinsi");
			$this->cache->save('PROVINSI', json_encode($data['province']), env('TIMECACHE_2')); 
		} 
		$data['province']  = array("" => "[SELECT DATA]") +  $data['province'] ;
		$data["manager"] = array("" => "[SELECT USER]") +  $this->Common_model->get_record_list("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on a.group_id=b.id and b.level='1'", "a.is_active = '1'", "item");
		return view('App\Modules\SetUpBranch\Views\Branch_add_form_view', $data);
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

	function save_branch_add(){
		$is_exist = $this->Set_up_branch_maker_model->isExist($this->input->getPost('txt-id-branch'));
		if (!$is_exist) {
			$data['id'] = uuid();
			$data['branch_id'] = $this->input->getPost('txt-id-branch');
			$data['branch_name'] = $this->input->getPost('name-branch');
			$data['branch_prov'] = $this->input->getPost('opt-branch-province');
			$data['branch_city'] = $this->input->getPost('opt-branch-city');
			$data['branch_kec'] = $this->input->getPost('opt-branch-district');
			$data['branch_kel'] = $this->input->getPost('opt-branch-sub-district');
			$data['branch_address'] = $this->input->getPost('txt-address');
			$data['branch_no_telp'] = $this->input->getPost('txt-phone-number');
			$data['branch_manager'] = $this->input->getPost('opt-branch-manager');
			$data['created_by'] = session()->get('USER_ID');
			$data['created_time'] = date('Y-m-d H:i:s');
			$data['is_active'] = $this->input->getPost('opt-active-flag');
			$data['flag'] = '1';
			// $data['flag'] = $this->input->getPost('txt-id');
			$return = $this->Set_up_branch_maker_model->save_branch_add($data);
			if ($return) {
				$cache = session()->get('USER_ID').'_branch_list_temp';
				$this->cache->delete($cache);
				$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
		} else {
			$rs = array('success' => false, 'message' => 'Data ID Already Registered. Please insert another ID.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
	function branchEditForm(){
		
		if($this->cache->get('PROVINSI')){
			$data['province']  = json_decode($this->cache->get('PROVINSI'),true);
		}else{
			$data['province'] = $this->Common_model->get_record_list("provinsi value, provinsi AS item", "cms_zip_reg", "provinsi is not null", "provinsi");
			$this->cache->save('PROVINSI', json_encode($data['province']), env('TIMECACHE_2')); 
		} 

		$data['id'] = $this->input->getGet('id');
		$data["branch_data"] = $this->Common_model->get_record_values("*", "cms_branch", "id = '" . $data["id"] . "'");
		$data['provinsi'] = $this->Common_model->get_record_list("provinsi value, provinsi AS item", "cms_zip_reg", "provinsi='" . $data["branch_data"]["branch_prov"] . "'", "provinsi") +  $this->Common_model->get_record_list("provinsi value, provinsi AS item", "cms_zip_reg", "provinsi is not null ", "provinsi");
		$data['kota'] = $this->Common_model->get_record_list("kabupaten value, kabupaten AS item", "cms_zip_reg", "kabupaten='" . $data["branch_data"]["branch_city"] . "'", "kabupaten") +  $this->Common_model->get_record_list("kabupaten value, kabupaten AS item", "cms_zip_reg", "provinsi='" . $data["branch_data"]["branch_prov"] . "'", "kabupaten");
		$data['kecamatan'] = $this->Common_model->get_record_list("kecamatan value, kecamatan AS item", "cms_zip_reg", "kecamatan='" . $data["branch_data"]["branch_kec"] . "'", "kecamatan") +  $this->Common_model->get_record_list("kecamatan value, kecamatan AS item", "cms_zip_reg", "kabupaten='" . $data["branch_data"]["branch_city"] . "' and provinsi='" . $data["branch_data"]["branch_prov"] . "'", "kecamatan");
		$data['kelurahan'] = $this->Common_model->get_record_list("kelurahan value, kelurahan AS item", "cms_zip_reg", "kelurahan='" . $data["branch_data"]["branch_kel"] . "'", "kelurahan") +  $this->Common_model->get_record_list("kelurahan value, kelurahan AS item", "cms_zip_reg", "kecamatan='" . $data["branch_data"]["branch_kec"] . "' and kabupaten='" . $data["branch_data"]["branch_city"] . "' ", "kelurahan");
		$data["manager_val"] = $this->Common_model->get_record_list("id AS value, name AS item", "cc_user", "id ='" . $data["branch_data"]["branch_manager"] . "'", "item") + $this->Common_model->get_record_list("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on a.group_id=b.id and b.level='1'", "a.is_active = '1'", "item");
		$data['is_active'] = $data['branch_data']['is_active'];
		return view('App\Modules\SetUpBranch\Views\Branch_edit_form_view', $data);
	}
	function save_branch_edit(){
		$data['id'] = $this->input->getPost('txt-id');
		$data['branch_id'] = $this->input->getPost('txt-branch-id');
		$data['branch_name'] = $this->input->getPost('name-branch');
		$data['branch_prov'] = $this->input->getPost('opt-branch-province');
		$data['branch_city'] = $this->input->getPost('opt-branch-city');
		$data['branch_kec'] = $this->input->getPost('opt-branch-district');
		$data['branch_kel'] = $this->input->getPost('opt-branch-sub-district');
		$data['branch_address'] = $this->input->getPost('txt-address');
		$data['branch_no_telp'] = $this->input->getPost('txt-phone-number');
		$data['branch_manager'] = $this->input->getPost('opt-branch-manager');
		$data['created_by'] = session()->get('USER_ID');
		$data['created_time'] = date('Y-m-d H:i:s');
		$data['is_active'] = $this->input->getPost('opt-active-flag');
		$data['flag'] = '2';

		$return = $this->Set_up_branch_maker_model->save_branch_edit($data);
		if ($return) {
			$cache = session()->get('USER_ID').'_branch_list_temp';
			$this->cache->delete($cache);
			$rs = array('success' => true, 'message' => 'Success to next approval', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

}