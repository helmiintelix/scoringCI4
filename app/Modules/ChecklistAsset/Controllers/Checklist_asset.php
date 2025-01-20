<?php 
namespace App\Modules\ChecklistAsset\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\ChecklistAsset\Models\Checklist_asset_model;


class Checklist_asset extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Checklist_asset_model = new Checklist_asset_model();
	}

	function add_field_checklist()
	{
		
		$data['classification'] = $this->input->getPost('classification');
        $data['asset_type'] = array(''=>'--PILIH--')+$this->Common_model->get_record_list("collateral_type AS value, collateral_type AS item", "cms_collateral", " 1=1", "collateral_type");
		
		foreach($data['asset_type'] as $a=>$b){

            $this->builder = $this->db->table('cc_matrix_field');

            $select = array(
                'field_name',
                'field_name_id',
                'is_mandatory',
                'order_by',
            );
            $this->builder->select(implode(', ', $select));
            $this->builder->where('asset_type', $a); // Menambahkan klausa where
            $this->builder->orderBy('order_by', 'ASC'); // Mengurutkan berdasarkan order_by secara ascending
            $rResult = $this->builder->get();
            $data['matrix'][$a] = $rResult->getResultArray();	
		}
		
		return view('\App\Modules\ChecklistAsset\Views\Checklist_asset_view',$data);
	}

    // function save_field_checklist(){
	// 	$asset_type = $this->input->getPost('asset_type');
	// 	$field_name = $this->input->getPost('txtFieldName');
	// 	$field_order = $this->input->getPost('txtFieldOrder');
		
	// 	$this->db->where('asset_type',$asset_type);
	// 	$this->db->delete('cc_matrix_field');
        
	// 	foreach($field_name as $a=>$b){
				
    //         $field_id = $this->common_model->clean_string2($b);
    //         $sql = "insert into cc_matrix_field (id , asset_type,field_name,field_name_id,order_by,created_by,created_time)values(uuid() ,'".$asset_type."','".$b."' , '".$field_id."' , '".$field_order[$a]."' , '".$this->session->userdata('USER_ID')."' , now()) ";
    //         $this->db->query($sql);				
    //         $data = array("success" => true, "message" => "Field Saved!");
    //         $rs = array('success' => true, 'message' => 'Success', 'data' => null);
    //         return $this->response->setStatusCode(200)->setJSON($rs);
	// 	}
	// }

    function save_field_checklist(){
        $data['asset_type'] = $this->input->getPost('asset_type');
        $data['field_name'] = $this->input->getPost('txtFieldName');
        $data['field_order'] = $this->input->getPost('txtFieldOrder');
        $data["created_by"]	= session()->get('USER_ID');
        
        $return	= $this->Checklist_asset_model->save_field_checklist($data);
        
        if($return){
            $rs = array('success' => true, 'message' => 'Success', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }else{
            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }
	}
    

}
