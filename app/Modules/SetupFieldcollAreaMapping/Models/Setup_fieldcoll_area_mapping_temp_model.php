<?php
namespace App\Modules\SetupFieldcollAreaMapping\models;
use App\Models\Common_model;
use CodeIgniter\Model;

Class Setup_fieldcoll_area_mapping_temp_model Extends Model 
{
    function get_fieldcoll_area_mapping_list_temp(){
        $this->builder = $this->db->table('cms_fieldcoll_area_mapping_temp a');
        $select = array(
            'a.id',
            'b.name AS field_collector_name',
            'a.sub_area_id',
            'a.created_time AS tgl_pengajuan',
            'a.created_by',
            'IF(a.is_active = "1", "WAITING APPROVAL", "REJECTED") AS status_pengajuan',
            'IF(a.flag = "1", "ADD", "EDIT") AS jenis_pengajuan',
            
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
    }
    function save_fieldcoll_area_mapping_edit_temp($id){
        $this->builder = $this->db->table('cms_fieldcoll_area_mapping_temp');
        $this->builder->whereIn('id', [$id]);
        $data = $this->builder->get()->getResultArray();
        $this->db->table('cms_fieldcoll_area_mapping')->delete(['id' => $id]);
        $this->db->table('cms_fieldcoll_area_mapping')->insertBatch($data);
        $return = $this->db->table('cms_fieldcoll_area_mapping_temp')->delete(['id' => $id]);

        return $return;
    }
    
    function reject_fieldcoll_area_mapping($id){
        $this->builder = $this->db->table('cms_fieldcoll_area_mapping_temp');
        $return = $this->builder->where('id', $id)->delete();
        return $return;
    }
}