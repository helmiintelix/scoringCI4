<?php 
namespace App\Modules\SetupAux\Controllers;
use App\Modules\SetupAux\Models\SetupAuxModel;
use CodeIgniter\Cookie\Cookie;

class SetupAux extends \App\Controllers\BaseController
{
    function __construct()
	{
		$this->SetupAuxModel = new SetupAuxModel();
	}

    function index()
	{
		$data["general_setting"] = $this->SetupAuxModel->get_general_setting_tele();
		$data["predictive_setting"] = $this->Common_model->get_record_values("*", "acs_predial_parameter", "id is not null");
		$data["aux_max_time"] = $this->Common_model->get_record_list("value, add_field1 AS item", "cms_reference", "reference='BREAK_REASON'", "order_num");
		$data["payment_check"] = $this->Common_model->get_record_value("value", "acs_reference", "reference='PAYMENT_CHECK'", "");
		return view('App\Modules\SetupAux\Views\SetupAuxView', $data);
	}

    function updateData(){
        $param = $this->input->getPost('param');
        $value = $this->input->getPost('value');

        $builder = $this->db->table('cms_reference');
        $builder->where('reference','BREAK_REASON');
        $builder->where('value',$param);
        $builder->set('add_field1',$value);

        $rs = $builder->update();

        if($rs){
            $res = array('success'=>true , 'message'=> $param.' update success!');
            return $this->response->setStatusCode(200)->setJSON($res);
        }else{
            $res = array('success'=>false , 'message'=> $param.' update failed!');
            return $this->response->setStatusCode(200)->setJSON($res);
        }

    }
}