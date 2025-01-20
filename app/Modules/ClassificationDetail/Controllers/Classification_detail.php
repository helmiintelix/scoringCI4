<?php 
namespace App\Modules\ClassificationDetail\Controllers;
use App\Modules\ClassificationDetail\Models\Classification_detail_model;
use CodeIgniter\Cookie\Cookie;

class Classification_detail extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Classification_detail_model = new Classification_detail_model();
	}

	function get_parameter_list(){
		$data['param'] = $this->input->getGet('param');
		$data['table'] = $this->input->getGet('table_name');
		$data['where'] = $this->input->getGet('where');
		$data['type'] = $this->input->getGet('type');
		$data["field_name"] = $this->input->getGet("name");
        $data["type"] = $this->input->getGet("type");

		// print_r($data);

		switch ($data['type']) {
			case 'tree':
				$data["param_list"] = $this->Classification_detail_model->get_parameter_list_tree($data);
				break;
			
			default:
			$data["param_list"] = $this->Classification_detail_model->get_parameter_list($data);
				break;
		}
		return view('\App\Modules\ClassificationDetail\Views\Parameter_list_view', $data);
	}
	function get_parameter_list_khusus_lov()
    {
		$data['param'] = $this->input->getGet('param');
		$data['table'] = $this->input->getGet('table_name');
		$data['where'] = $this->input->getGet('where');
		$data['type'] = $this->input->getGet('type');
		$data["field_name"] = $this->input->getGet("name");
        $data["type"] = $this->input->getGet("type");
        // $param = $this->input->getGet('param');
        // $table = $this->input->getGet('table_name');
        // $where = $this->input->getGet('where');
        // var_dump($param);
        // die;

        $data["param_list"] = $this->Classification_detail_model->get_parameter_list_khusus_lov($data);
        return view('\App\Modules\ClassificationDetail\Views\Parameter_list_view', $data);
    }
}