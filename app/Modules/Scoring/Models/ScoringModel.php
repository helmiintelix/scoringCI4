<?php
namespace App\Modules\Scoring\models;
use CodeIgniter\Model;
use App\Models\Common_model;


Class ScoringModel Extends Model 
{
    protected $Common_model;
    function __construct(){
        parent::__construct();
        $this->Common_model = new Common_model();
    }

    function getSchedulerSetting()
    {
        $builder = $this->db->table('sc_scheduler');
        $builder->select('id, value');

        $query = $builder->get();
        $rows  = $query->getResultArray();

        $arrData = [];
        foreach ($rows as $row) {
            $arrData[$row['id']] = $row['value'];
        }

        return $arrData;
    }

    function updateSystemSetting($param_data){
        $this->builder = $this->db->table('sc_scheduler');

		$this->builder->where('parameter', $param_data["parameter"]);
		$this->builder->where('id', $param_data["id"]);
		$return = $this->builder->update($param_data);
		

		return $return;
    }
}