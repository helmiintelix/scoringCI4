<?php 
namespace App\Modules\SetupListOfValue\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\SetupListOfValue\Models\Setup_list_of_value_model;


class Setup_list_of_value extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Setup_list_of_value_model = new Setup_list_of_value_model();
	}

	function lov()
	{
		
		$data['classification'] = $this->input->getPost('classification');
		
		return view('\App\Modules\SetupListOfValue\Views\Setup_list_of_value_view',$data);
	}

	function lov_registration()
	{
		
		$cache = session()->get('USER_ID').'_lov_registration';

		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_list_of_value_model->get_lov_registration();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

    function lov_list_old()
	{
		
		$cache = session()->get('USER_ID').'_lov_list_old';

		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Setup_list_of_value_model->get_lov_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

    function lov_add_form()
	{

        $data['get_category'] = $this->Common_model->get_record_list("value value, description AS item", "cms_reference b", "b.status = '1' AND b.reference='CONTACT_STATUS'", "order_num");
        $num = array();
		for ($i=1; $i <=20 ; $i++) { 
			$num[$i] = $i;
		}
		$data['get_hirarki_list'] = $num;

		return view('\App\Modules\SetupListOfValue\Views\Setup_list_of_value_add_form_view',$data);
	}

    function lov_edit_form()
	{
		$id	= $this->input->getGet('id');
        
		$data['lov_category'] =  $this->Common_model->get_record_list("id value, category_name AS item", "cms_lov_registration", "is_active='1'", "category_name");
		$data["lov_data"] = $this->Common_model->get_record_values("*", "cms_lov_relation", "lov_id = '" . $id . "'", "lov_id");
        $lov1_category = $this->Common_model->get_record_values("lov1_category", "cms_lov_relation", "lov_id='" . $data["lov_data"]["lov_id"] . "'", "lov_id");
		$data['lov1_category'] = explode(',', $lov1_category['lov1_category']);
		$lov2_category = $this->Common_model->get_record_values("lov2_category", "cms_lov_relation", "lov_id='" . $data["lov_data"]["lov_id"] . "'", "lov_id");
		$data['lov2_category'] = explode(',', $lov2_category['lov2_category']);
		$lov3_category = $this->Common_model->get_record_values("lov3_category", "cms_lov_relation", "lov_id='" . $data["lov_data"]["lov_id"] . "'", "lov_id");
		$data['lov3_category'] = explode(',', $lov3_category['lov3_category']);
		$lov4_category = $this->Common_model->get_record_values("lov4_category", "cms_lov_relation", "lov_id='" . $data["lov_data"]["lov_id"] . "'", "lov_id");
		$data['lov4_category'] = explode(',', $lov4_category['lov4_category']);
		$lov5_category = $this->Common_model->get_record_values("lov5_category", "cms_lov_relation", "lov_id='" . $data["lov_data"]["lov_id"] . "'", "lov_id");
		$data['lov5_category'] = explode(',', $lov5_category['lov5_category']);
        return view('\App\Modules\SetupListOfValue\Views\Setup_list_of_value_edit_relation_form_view',$data);

	}

    function lov_edit_category()
	{
		$id	= $this->input->getGet('id');
		$data["lov_data"] = $this->Common_model->get_record_values("*", "cms_lov_registration", "id = '" . $id . "'");
        $data['get_category'] = $this->Common_model->get_record_list("value value, description AS item", "cms_reference b", "b.status = '1' AND b.reference='CONTACT_STATUS'", "order_num");
		$num = array();
		for ($i=1; $i <=20 ; $i++) { 
			$num[$i] = $i;
		}
		$data['get_hirarki_list'] = $num;

        return view('\App\Modules\SetupListOfValue\Views\Setup_list_of_value_edit_form_view',$data);

	}

    function save_lov_add()
	{
		$is_exist = $this->Setup_list_of_value_model->isExistLovCategory($this->input->getPost('txt-lov-id'));
		if (!($is_exist)) {
			$lov_data["id"]	= strtoupper($this->input->getPost("txt-lov-id"));
			$lov_data["category_name"]	= strtoupper($this->input->getPost("txt-lov-name"));
			$lov_data["is_active"]	= $this->input->getPost("opt-active-flag");
			$lov_data["category_lov"]	= $this->input->getPost("opt-lov_category");
			$lov_data["hirarki"]	= $this->input->getPost("opt-lov_hirarki");
			$lov_data["created_by"]	= session()->get('USER_ID');
			$lov_data["created_time"]	= date('Y-m-d H:i:s');


			$return	= $this->Setup_list_of_value_model->save_lov_add($lov_data);

			if ($return) {
				$return=$this->sand_lov_to_tele();
				$return=$this->send_lov_to_reference();
				$this->Common_model->data_logging('Add LOV Category', $lov_data["category_name"], 'SUCCESS', 'Set  = ' . $lov_data["category_name"]);
				
                $rs = array('success' => true, 'message' => 'Success', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			} else {
				$this->Common_model->data_logging('Add LOV Category', $lov_data["category_name"], 'FAILED', 'Set = ' . $lov_data["category_name"]);
				
                $rs = array('success' => false, 'message' => 'failed', 'data' => null);
				return $this->response->setStatusCode(200)->setJSON($rs);
			}
		} else {
            $rs = array('success' => false, 'message' => 'LOV Category ID Already Registered. Please insert another ID.', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
        }
	}

    function sand_lov_to_tele()
	{
		$cms_lov_relation1 = $this->Common_model->get_record_values('*', 'cms_lov_relation', 'type_collection="TeleColl" and is_active="1" ');

        $return=$this->db->table('cms_lov_relation')->delete(['type_collection' => 'TeleColl']); // Delete data from temporary table
        $return=$this->db->table('cms_lov_relation')->insertBatch($cms_lov_relation1);

		$result = $this->db->table('cms_lov_registration')->get()->getResult();
        $this->db->table('cms_lov_registration')->where('id IS NOT NULL')->delete();

        foreach ($result as $value) {
            $this->db->table('cms_lov_registration')->insert($value);
        }

        return true;
	}

    function send_lov_to_reference()
	{

		$cms_lov_relation_data = $this->Common_model->get_record_values('*', 'cms_lov_relation', 'type_collection="TeleColl" and is_active="1" ');
        if ($cms_lov_relation_data){
            $arr_lov_dc[0] = str_replace(",", "','", $cms_lov_relation_data['lov1_category']);
            $arr_lov_dc[1] = str_replace(",", "','", $cms_lov_relation_data['lov2_category']);
            $arr_lov_dc[2] = str_replace(",", "','", $cms_lov_relation_data['lov3_category']);
            $arr_lov_dc[3] = str_replace(",", "','", $cms_lov_relation_data['lov4_category']);
            foreach ($arr_lov_dc as $key => $value) { //loop lov 1 sd 4 dc
                // Hapus data dari tabel 'cms_reference' yang memiliki kolom 'reference' sesuai dengan 'lov' . ($key + 1) . '_dc'
                $this->db->table('cms_reference')->where('reference', 'lov' . ($key + 1) . '_dc')->delete();
            
                // Mengambil data dari tabel 'cms_lov_registration' berdasarkan id yang ada di $value
                $result = $this->db->table('cms_lov_registration')->whereIn('id', explode(',', $value))->get()->getResultArray();
            
                foreach ($result as $value2) {
                    $data_lov = array(
                        "reference" => 'lov' . ($key + 1) . '_dc',
                        "value" => $value2['id'],
                        "description" => $value2['category_name'],
                        "order_num" => 4,
                        "status" => "1",
                        "created_by" => "system",
                        "created_time" => date('Y-m-d H:i:s'),
                        "flag" => "1",
                        "flag_tmp" => "1"
                    );
                    // Menyisipkan data ke dalam tabel 'cms_reference'
                    $this->db->table('cms_reference')->insert($data_lov);
                }
            }
        }

		$cms_lov_relation_data = $this->Common_model->get_record_values('*', 'cms_lov_relation', 'type_collection="FieldColl" and is_active="1" ');
		
        if($cms_lov_relation_data){
            $arr_lov_fc[0] = str_replace(",", "','", $cms_lov_relation_data['lov1_category']);
            $arr_lov_fc[1] = str_replace(",", "','", $cms_lov_relation_data['lov2_category']);
            $arr_lov_fc[2] = str_replace(",", "','", $cms_lov_relation_data['lov3_category']);
            $arr_lov_fc[3] = str_replace(",", "','", $cms_lov_relation_data['lov4_category']);
            foreach ($arr_lov_fc as $key => $value) { //loop lov 1 sd 4 dc
                // Hapus data dari tabel 'cms_reference' yang memiliki kolom 'reference' sesuai dengan 'lov' . ($key + 1) . '_fc'
                $this->db->table('cms_reference')->where('reference', 'lov' . ($key + 1) . '_fc')->delete();

                // Mengambil data dari tabel 'cms_lov_registration' berdasarkan id yang ada di $value
                $result = $this->db->table('cms_lov_registration')->whereIn('id', explode(',', $value))->get()->getResultArray();
            
                foreach ($result as $value2) {
                    $data_lov = array(
                        "reference" => 'lov' . ($key + 1) . '_fc',
                        "value" => $value2['id'],
                        "description" => $value2['category_name'],
                        "order_num" => 4,
                        "status" => "1",
                        "created_by" => "system",
                        "created_time" => date('Y-m-d H:i:s'),
                        "flag" => "1",
                        "flag_tmp" => "1"
                    );
                    // Menyisipkan data ke dalam tabel 'cms_reference'
                    $this->db->table('cms_reference')->insert($data_lov);
                }
            }
        }

        return true;
	}

    function save_lov_edit()
	{
		$lov_data["id"]	= $this->input->getPost('txt-lov-id');
		$lov_data["category_name"]	= $this->input->getPost('txt-lov-name');
		$lov_data["category_lov"]	= $this->input->getPost('opt-lov_category');
		$lov_data["hirarki"]	= $this->input->getPost('opt-lov_hirarki');
		$lov_data["is_active"]	= $this->input->getPost('opt-active-flag');

		$return	= $this->Setup_list_of_value_model->save_lov_edit($lov_data);

        if ($return) {
            $this->Common_model->data_logging('Add LOV Category', $lov_data["category_name"], 'SUCCESS', 'Set  = ' . $lov_data["category_name"]);
            
            $rs = array('success' => true, 'message' => 'Success', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        } else {
            $this->Common_model->data_logging('Add LOV Category', $lov_data["category_name"], 'FAILED', 'Set = ' . $lov_data["category_name"]);
            
            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }

	}

    function save_lov_relation_edit()
	{
		$category1 = $this->input->getPost("opt-lov1-category") == null ? '' : implode(",", $this->input->getPost("opt-lov1-category"));
		$category2 = $this->input->getPost("opt-lov2-category") == null ? '' : implode(",", $this->input->getPost("opt-lov2-category"));
		$category3 = $this->input->getPost("opt-lov3-category") == null ? '' : implode(",", $this->input->getPost("opt-lov3-category"));
		$category4 = $this->input->getPost("opt-lov4-category") == null ? '' : implode(",", $this->input->getPost("opt-lov4-category"));
		$category5 = $this->input->getPost("opt-lov5-category") == null ? '' : implode(",", $this->input->getPost("opt-lov5-category"));
		$id	= $this->input->getPost("txt-id");
		$lov_data["lov1_label_name"]	= strtoupper($this->input->getPost("txt-lov1-label-name"));
		$lov_data["lov1_category"]		= $category1;
		$lov_data["lov2_label_name"]	= strtoupper($this->input->getPost("txt-lov2-label-name"));
		$lov_data["lov2_category"]		= $category2;
		$lov_data["lov3_label_name"]	= strtoupper($this->input->getPost("txt-lov3-label-name"));
		$lov_data["lov3_category"]		= $category3;
		$lov_data["lov4_label_name"]	= strtoupper($this->input->getPost("txt-lov4-label-name"));
		$lov_data["lov4_category"]		= $category4;
		$lov_data["lov5_label_name"]	= strtoupper($this->input->getPost("txt-lov5-label-name"));
		$lov_data["lov5_category"]		= $category5;
		$lov_data["type_collection"]	= $this->input->getPost("opt-type_collection");
		$lov_data["created_by"]			= session()->get('USER_ID');
		$lov_data["created_time"] 		= date('Y-m-d H:i:s');
		$lov_data["is_active"]			= $this->input->getPost("opt-active-flag");

		$return	= $this->Setup_list_of_value_model->save_lov_relation_edit($lov_data, $id);

        if ($return) {
            $return=$this->sand_lov_to_tele();
            $return=$this->send_lov_to_reference();
            $this->Common_model->data_logging('Edit LOV Category', $id, 'SUCCESS', 'Set  = ' . $id);
            
            $rs = array('success' => true, 'message' => 'Success', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        } else {
            $this->Common_model->data_logging('Edit LOV Category', $id, 'FAILED', 'Set = ' . $id);
            
            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }
	}

}
