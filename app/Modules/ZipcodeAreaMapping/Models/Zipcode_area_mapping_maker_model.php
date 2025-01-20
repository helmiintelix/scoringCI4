<?php
namespace App\Modules\ZipcodeAreaMapping\models;
use CodeIgniter\Model;

Class Zipcode_area_mapping_maker_model Extends Model 
{
    function get_zipcode_area_mapping_list(){
        $this->builder = $this->db->table('cms_zipcode_area_mapping a');
        $select = array(
            'a.id',
            'a.sub_area_id',
            'a.sub_area_name',
            'a.area_tagih',
            'a.product',
            'a.zip_code',
            'a.created_time',
            'a.created_by',
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->where('a.is_active', '1');
        $this->builder->orderBy('a.created_time', 'desc');
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
    function isExistzipcode_area_mappingId($id){
        $this->builder = $this->db->table('cms_zipcode_area_mapping');
        $this->builder->where(array(
            'sub_area_id' => $id
        ));
        $query = $this->builder->get();
        if ($query->getNumRows() > 0) {
    		print_r($query);

			return true;
		} else {
			return false;
		}
    }
    function save_zipcode_area_mapping_add($data){
        $this->builder = $this->db->table('cms_zipcode_area_mapping_temp');
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_zipcode_area_mapping_edit($data){
        $this->builder = $this->db->table('cms_zipcode_area_mapping');
        $this->builder->where('id', $data['id']);
        $data2 = $this->builder->get()->getResultArray();
        $query = $this->db->table('cms_zipcode_area_mapping_temp')->insertBatch($data2);
        if ($query) {
            $builder = $this->db->table('cms_zipcode_area_mapping_temp');
            $builder->where('id', $data["id"]);
            $return = $builder->update($data);
        }
        return $return;
    }
}