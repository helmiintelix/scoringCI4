<?php
namespace App\Modules\SetupDeviationReference\models;
use CodeIgniter\Model;

Class Setup_deviation_reference_maker_model Extends Model 
{
    function get_deviation_reference_list(){
        $this->builder = $this->db->table('cms_deviation_reference a');
        $select = array(
            'a.id',
            'a.deviation_id',
            'a.deviation_name',
            'a.product',
            'a.type',
            'IF(a.is_active = "1", "<span class=\"badge bg-success\">ACTIVE</span>", "<span class=\"badge bg-danger\">INACTIVE</span>") as is_active',
            'a.created_by',
            'a.created_time',
            'a.flag',
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->orderBy('a.created_time', 'desc');
        $this->builder->where('a.is_active', '1');
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
    function isExistdeviation_referenceId($id){
        $this->builder = $this->db->table('cms_deviation_reference');
        $this->builder->where(array(
            'deviation_id' => $id
        ));
        $query = $this->builder->get();
        if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
    }
    function save_deviation_reference_add($data){
        $this->builder = $this->db->table('cms_deviation_reference_temp');
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_deviation_reference_edit($data){
        $this->builder = $this->db->table('cms_deviation_reference');
        $this->builder->where('id', $data['id']);
        $data2 = $this->builder->get()->getResultArray();
        $query = $this->db->table('cms_deviation_reference_temp')->insertBatch($data2);
        if ($query) {
            $builder = $this->db->table('cms_deviation_reference_temp');
            $builder->where('id', $data['id']);
            $return = $builder->update($data);
        }
        return $return;
    }
    function delete_deviation_reference($id){
        $this->builder = $this->db->table('cms_deviation_reference');
        $this->builder->where('id', $id);
        $return = $this->builder->delete();
        return $return;
    }
}