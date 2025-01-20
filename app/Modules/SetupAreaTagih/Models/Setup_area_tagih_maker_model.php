<?php
namespace App\Modules\SetupAreaTagih\models;
use CodeIgniter\Model;

Class Setup_area_tagih_maker_model Extends Model 
{
    function get_area_tagih_list(){
        $this->builder = $this->db->table('cms_area_tagih a');
        $select = array(
            'a.id',
            'a.area_tagih_id',
            'a.area_tagih_name',
            'a.created_by',
            'a.created_time',
            'IF(a.is_active = "1", "ENABLE", "DISABLE") AS is_active',
            'IF(a.flag = "1", "ADD", "EDIT") AS jenis_pengajuan',
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
    function isExistarea_tagihId($id){
        $this->builder = $this->db->table('cms_area_tagih');
        $this->builder->where(array(
            'area_tagih_id' => $id
        ));
        $query = $this->builder->get();
        if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
    }
    function save_area_tagih_add($data){
        $this->builder = $this->db->table('cms_area_tagih_temp');
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_area_tagih_edit($data){
        $this->builder = $this->db->table('cms_area_tagih');
        $this->builder->where('id', $data['id']);
        $data2 = $this->builder->get()->getResultArray();
        $query = $this->db->table('cms_area_tagih_temp')->insertBatch($data2);
        if ($query) {
            $builder = $this->db->table('cms_area_tagih_temp');
            $builder->where('id', $data['id']);
            $builder->set('area_tagih_id', $data["area_tagih_id"]);
            $builder->set('area_tagih_name', $data["area_tagih_name"]);
            $builder->set('created_by', $data["created_by"]);
            $builder->set('created_time', $data["created_time"]);
            $builder->set('is_active', $data['is_active']);
            $builder->set('flag', $data['flag']);
            $return = $builder->update();
        }
        return $return;
    }
}