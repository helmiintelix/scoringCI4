<?php
namespace App\Modules\Settings\models;
use CodeIgniter\Model;
use App\Models\Common_model;


Class SettingsModel Extends Model 
{
    protected $Common_model;
    function __construct(){
        parent::__construct();
        $this->Common_model = new Common_model();
    }

    function get_system_configuration()
    {
       

        $builder = $this->db->table('cc_app_configuration');
        $builder->select('id, value');

        $query = $builder->get();
        $rows  = $query->getResultArray();

        

        return $rows;
    }
}