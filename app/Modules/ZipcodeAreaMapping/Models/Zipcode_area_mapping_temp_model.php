<?php
namespace App\Modules\ZipcodeAreaMapping\models;
use App\Models\Common_model;
use CodeIgniter\Model;

Class Zipcode_area_mapping_temp_model Extends Model 
{
    function get_zipcode_area_mapping_list_temp(){
        $this->builder = $this->db->table('cms_zipcode_area_mapping_temp a');
        $select = array(
            'a.id',
            'a.sub_area_id',
            'a.sub_area_name',
            'a.area_tagih',
            'a.product',
            'a.zip_code',
            'a.created_time',
            'a.created_by',
            'IF(a.is_active = "1", "<span class=\"badge bg-success\">WAITING APPROVAL</span>", "<span class=\"badge bg-danger\">REJECTED</span>") AS status_pengajuan',
            'IF(a.flag = "1", "<span class=\"badge bg-success\">ADD</span>", "<span class=\"badge bg-info\">EDIT</span>") AS jenis_pengajuan'
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
    function save_zipcode_area_mapping_edit_temp($data){
        $this->builder = $this->db->table('cms_zipcode_area_mapping_temp');
        $this->builder->whereIn('id', [$data['id']]);
        $query = $this->builder->get();
        $data2 = $query->getResultArray();

        $this->db->table('cms_zipcode_area_mapping')->delete(['sub_area_id' => $data['sub_area_id']]); // Delete data from temporary table

        $this->db->table('cms_zipcode_area_mapping')->insertBatch($data2); // Insert data to main table

        $return = $this->db->table('cms_zipcode_area_mapping_temp')->delete(['id' => $data['id']]); // Delete data from temporary table

        return $return;
    }
    
    function reject_zipcode_area_mapping($id){
        $this->builder = $this->db->table('cms_zipcode_area_mapping_temp');
        $return = $this->builder->where('id', $id)->delete();
        return $return;
    }
}