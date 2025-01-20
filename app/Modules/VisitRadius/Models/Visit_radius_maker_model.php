<?php
namespace App\Modules\VisitRadius\models;
use CodeIgniter\Model;

Class Visit_radius_maker_model Extends Model 
{
    function get_visit_radius_list(){
        $this->builder = $this->db->table('cms_visit_radius a');
        $select = array(
            'a.id',
            'a.label',
            'a.fc_name',
            'a.radius',
            'IF(a.is_active = "1", "ENABLE", "DISABLE") AS is_active',
            'a.created_by',
            'a.created_date'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
      
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
            foreach ($rResult->getResultArray()[0] as $key => $value) {
                $result['header'][] = array('field' => $key);
            }
            $result['data'] = $return;
        } else {
            $result['header'] = array();
            $result['data'] = array();
        }
        
        $rs =  $result;
        return $rs;
        // return $return;
    }
    function isExist($id){
        $this->builder = $this->db->table('cms_visit_radius a');
        $this->builder->where(array(
            'id' => $id
        ));
        $query = $this->builder->get();
        if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
    }
    function save_visit_radius_add($data){
        $this->builder = $this->db->table('cms_visit_radius_temp');
        $return = $this->builder->insert($data);
        return $return;
    }
    // function save_visit_radius_edit($data){
    //     $this->builder = $this->db->table('cms_visit_radius_temp');
    //     $return = $this->builder->insert($data);
    //     return $return;
    // }
}