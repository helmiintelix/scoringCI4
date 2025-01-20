<?php
namespace App\Modules\VisitRadius\models;
use CodeIgniter\Model;

Class Visit_radius_temp_model Extends Model 
{
    function get_visit_radius_list_temp(){
        $this->builder = $this->db->table('cms_visit_radius_temp a');
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
    }
    function approve_request_visit_radius($id){
        $this->builder = $this->db->table('cms_visit_radius_temp');

        $this->builder->whereIn('id', [$id]);
        $query = $this->builder->get();
        $data = $query->getResultArray();

        $this->db->table('cms_visit_radius')->delete(['id' => $id]); // Delete data from temporary table

        $this->db->table('cms_visit_radius')->insertBatch($data); // Insert data to main table

        $this->db->table('cms_visit_radius_temp')->delete(['id' => $id]); // Delete data from temporary table

        return $data;
    }
    function delete_data_request($id){
        $this->builder = $this->db->table('cms_visit_radius_temp');
        $return = $this->builder->where('id', $id)->delete();
        return $return;
    }
}