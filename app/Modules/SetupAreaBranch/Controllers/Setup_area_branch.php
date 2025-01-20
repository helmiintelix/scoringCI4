<?php 
namespace App\Modules\SetupAreaBranch\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\SetupAreaBranch\models\Setup_area_branch_model;


class Setup_area_branch extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_area_branch_model = new Setup_area_branch_model();
	}

	function branch()
	{
		
		$data['classification'] = $this->input->getPost('classification');
		
		return view('\App\Modules\SetupAreaBranch\Views\Setup_area_branch_view',$data);
	}

    function getDescent()
	{
		$param = $this->input->getGet('value');
        $this->builder = $this->db->table('cms_reference_region a');
        $select = array(
            'code as value',
            'description as item',
        );
        $this->builder->where('parent_code', $param);
        $this->builder->orderBy('description', 'asc');
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();

        // $query = $this->builder->getLastQuery(); // Mendapatkan query terakhir
        $data = '';
		$data .= '<option value="">SELECT DATA</option>';
		foreach ($return as $row) {
			$data .= '<option value="' . $row['value'] . '">' . $row['item'] . '</option>';
		}

        $rs = array('success' => true, 'message' => '', 'data' => $data);
        return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function area_branch_list()
	{
		
		$cache = session()->get('USER_ID').'_area_branch_list';

		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_area_branch_model->get_area_branch_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function area_branch_list_temp(){
		$cache = session()->get('USER_ID').'_area_branch_list_temp';

        if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_area_branch_model->get_area_branch_list_temp();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
		
	}

	function area_branch_add_form(){
	
		$cache = session()->get('USER_ID').'_area_branch_add_form';

		$data['province'] = array("" => "SELECT DATA") +  $this->Common_model->get_record_list("code value, description AS item", "cms_reference_region", "level=0", "description");
		$data["manager"] = array("" => "SELECT DATA") +  $this->Common_model->get_record_list("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on a.group_id=b.id and b.level='ADMIN'", "a.is_active = '1'", "item");
		$data['branch_list'] =  $this->Common_model->get_record_list("branch_id value, branch_name item", "cms_branch", "is_active='1'", "branch_name");
		
		return view('\App\Modules\SetupAreaBranch\Views\setup_area_branch_add_form_view',$data);
	}

	function area_branchEditForm()
	{
        $uri = current_url(true);
        $data["id"] = $this->input->getGet('id');
		$data["branch_data"] = $this->Common_model->get_record_values("*", "cms_area_branch", "id = '" . $data["id"] . "'");

		$data['province'] = $this->Common_model->get_record_list("code value, description AS item", "cms_reference_region", "code='" . $data["branch_data"]["area_prov"] . "'", "description") +  $this->Common_model->get_record_list("code value, description AS item", "cms_reference_region", "level=0", "description");
		$data['city'] = $this->Common_model->get_record_list("code value, description AS item", "cms_reference_region", "code='" . $data["branch_data"]["area_city"] . "'", "description") +  $this->Common_model->get_record_list("code value, description AS item", "cms_reference_region", "parent_code='" . $data["branch_data"]["area_prov"] . "'", "description");
		$data['kecamatan'] = $this->Common_model->get_record_list("code value, description AS item", "cms_reference_region", "code='" . $data["branch_data"]["area_kec"] . "'", "description") +  $this->Common_model->get_record_list("code value, description AS item", "cms_reference_region", "parent_code='" . $data["branch_data"]["area_city"] . "'", "description");
		$data['kelurahan'] = $this->Common_model->get_record_list("code value, description AS item", "cms_reference_region", "code='" . $data["branch_data"]["area_kel"] . "'", "description") +  $this->Common_model->get_record_list("code value, description AS item", "cms_reference_region", "parent_code='" . $data["branch_data"]["area_kec"] . "'", "description");
		$data["manager"] = $this->Common_model->get_record_list("id AS value, name AS item", "cc_user", "id ='" . $data["branch_data"]["area_manager"] . "'", "item") + $this->Common_model->get_record_list("a.id AS value, a.name AS item", "cc_user a join cc_user_group b on a.group_id=b.id and b.level='MANAGER'", "a.is_active = '1'", "item");

		$data_branch = $this->Common_model->get_record_values("branch_list", "cms_area_branch", "id='" . $data["id"] . "'", "lov_id");
		$data['data_branch_list'] = explode(',', $data_branch['branch_list']);
		$data['branch_list'] =  $this->Common_model->get_record_list("branch_id value, branch_name item", "cms_branch", "is_active='1'", "branch_name");

		if(empty($data["branch_data"])){
			echo "NOT FOUND";
		}else{
			return view('\App\Modules\SetupAreaBranch\Views\setup_area_branch_edit_form_view',$data);
		}
		
	}

	function save_area_branch_add(){
		
		$is_exist = $this->Setup_area_branch_model->isExist($this->input->getPost('txt-branch-id'));
	
		if(!$is_exist){
            $branch_list = $this->input->getPost("opt-area_branch-branch_list") == null ? '' : implode(",", $this->input->getPost("opt-area_branch-branch_list"));
            
            $data['id'] = UUID();
			$data['area_id'] = $this->input->getPost('txt-branch-id');
			$data['area_name'] = $this->input->getPost('txt-name-id');
			$data['area_prov'] = $this->input->getPost('opt-area_branch-province');
			$data['area_city'] = $this->input->getPost('opt-area_branch-city');
			$data['area_kec'] = $this->input->getPost('opt-area_branch-kecamatan');
			$data['area_kel'] = $this->input->getPost('opt-area_branch-kelurahan');
			$data['area_address'] = $this->input->getPost('txt-branch-address');
			$data['area_no_telp'] = $this->input->getPost('txt-branch-telp');
			$data['area_manager'] = $this->input->getPost('opt-area_branch-manager');
			$data['branch_list'] = $branch_list;
            $data['created_by'] = session()->get('USER_ID');
            $data['created_time'] = date('Y-m-d H:i:s');
            $data['is_active'] = $this->input->getPost('opt-active-flag');
            $data['flag'] = '1';
			
			$return	= $this->Setup_area_branch_model->save_area_branch_add($data);
			
			if($return){
				$rs = array('success' => true, 'message' => 'Success', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}

		}else{
			$rs = array('success' => false, 'message' => 'Area Branch Already Registered. Please insert another ID.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

    function save_area_branch_edit(){
		
		$is_exist = $this->Setup_area_branch_model->isExistarea_branch_tempId($this->input->getPost('txt-id'));
        
		if(!$is_exist){
            $branch_list = $this->input->getPost("opt-area_branch-branch_list") == null ? '' : implode(",", $this->input->getPost("opt-area_branch-branch_list"));
            
			$area_branch_data["id"]		= $this->input->getPost('txt-id');
			$area_branch_data["area_id"]		= strtoupper($this->input->getPost('txt-branch-id'));
			$area_branch_data["area_name"]	= strtoupper($this->input->getPost("txt-branch-name"));
			$area_branch_data["area_prov"]	= $this->input->getPost("opt-area_branch-province");
			$area_branch_data["area_city"]	= $this->input->getPost("opt-area_branch-city");
			$area_branch_data["area_kec"]	= $this->input->getPost("opt-area_branch-kecamatan");
			$area_branch_data["area_kel"]	= $this->input->getPost("opt-area_branch-kelurahan");
			$area_branch_data["branch_list"]	= $branch_list;
			$area_branch_data["area_address"]	= $this->input->getPost("txt-branch-address");
			$area_branch_data["area_no_telp"]	= $this->input->getPost("txt-branch-telp");
			$area_branch_data["area_manager"]	= $this->input->getPost("opt-area_branch-manager");
			$area_branch_data["created_by"]	= session()->get('USER_ID');
			$area_branch_data["created_time"]	= date('Y-m-d H:i:s');
			$area_branch_data["is_active"]	= $this->input->getPost("opt-active-flag");
			$area_branch_data["flag"]	= '2';

			$return	= $this->Setup_area_branch_model->save_area_branch_edit($area_branch_data);
			
			if($return){
				$rs = array('success' => true, 'message' => 'Success', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}else{
				$rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}

		}else{
			$rs = array('success' => false, 'message' => 'AREA BRANCH ID Already Requested for Some Updates. Please Contact Your System Administrator.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
    }

}
