<?php
namespace App\Modules\SetupFieldcollAreaMapping\models;
use CodeIgniter\Model;

Class Setup_fieldcoll_area_mapping_maker_model Extends Model 
{
    function get_fieldcoll_area_mapping_list(){
        $this->builder = $this->db->table('cms_fieldcoll_area_mapping a');
        $select = array(
            'a.id',
            'b.name AS field_collector_name',
            'a.sub_area_id',
            'a.created_time',
            'a.created_by',
            'IF(a.is_active = "1", "ENABLE", "DISABLE") AS is_active',
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cc_user b', 'a.collector_id = b.id', 'left');
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
    function isExistfieldcoll_area_mappingId($id){
        $this->builder = $this->db->table('cms_fieldcoll_area_mapping_temp');
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
    function save_fieldcoll_area_mapping_add($data){
        $this->builder = $this->db->table('cms_fieldcoll_area_mapping_temp');
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_fieldcoll_area_mapping_edit($data){
        $this->builder = $this->db->table('cms_fieldcoll_area_mapping');
        $this->builder->where('id', $data['id']);
        $data2 = $this->builder->get()->getResultArray();
        $query = $this->db->table('cms_fieldcoll_area_mapping_temp')->insertBatch($data2);
        if ($query) {
            $builder = $this->db->table('cms_fieldcoll_area_mapping_temp');
            $builder->where('id', $data["id"]);
            $builder->set('collector_id', $data["collector_id"]);
            $builder->set('sub_area_id', $data["sub_area_id"]);
            $builder->set('created_by', $data["created_by"]);
            $builder->set('created_time', $data["created_time"]);
            $builder->set('is_active', $data["is_active"]);
            $builder->set('flag', $data['flag']);
            $return = $builder->update();
        }
        return $return;
    }
}