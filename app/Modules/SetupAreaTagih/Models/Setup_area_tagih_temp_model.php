<?php
namespace App\Modules\SetupAreaTagih\models;
use CodeIgniter\Model;

Class Setup_area_tagih_temp_model Extends Model 
{
    function get_area_tagih_list_temp(){
        $this->builder = $this->db->table('cms_area_tagih_temp a');
        $select = array(
            'a.id',
            'a.area_tagih_id',
            'a.area_tagih_name',
            'a.created_by',
            'a.created_time AS TGL_pengajuan',
            'IF(a.is_active = "1", "WAITING APPROVAL", "REJECTED") AS status_pengajuan',
            'IF(a.flag = "1", "ADD", "EDIT") AS jenis_pengajuan',
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
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
    function save_area_tagih_edit_temp($data){
        $this->builder = $this->db->table('cms_area_tagih_temp');
        $this->builder->whereIn('id', [$data['id']]);
        $query = $this->builder->get();
        $data2 = $query->getResultArray();

        $this->db->table('cms_area_tagih')->delete(['area_tagih_id' => $data['area_tagih_id']]); // Delete data from temporary table

        $this->db->table('cms_area_tagih')->insertBatch($data2); // Insert data to main table

        $this->db->table('cms_area_tagih_temp')->delete(['id' => $data['id']]); // Delete data from temporary table

        return $data;
    }
    function reject_area_tagih($id){
        $this->builder = $this->db->table('cms_area_tagih_temp');
        $return = $this->builder->where('id', $id)->delete();
        return $return;
    }
}