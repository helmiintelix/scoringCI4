<?php
namespace App\Modules\SetupDeviationReference\models;
use CodeIgniter\Model;

Class Setup_deviation_reference_temp_model Extends Model 
{
    function get_deviation_reference_list_temp(){
        $this->builder = $this->db->table('cms_deviation_reference_temp a');
        $select = array(
            'a.id',
            'a.deviation_id',
            'a.deviation_name',
            'a.product',
            'a.type',
            'IF(a.is_active = "1", "<span class=\"badge bg-success\">WAITING APPROVAL</span>", "<span class=\"badge bg-danger\">INACTIVE</span>") AS status_approval,
            IF(a.flag = "1", "<span class=\"badge bg-success\">ADD</span>", "<span class=\"badge bg-info\">EDIT</span>") AS jenis_request',
            'a.created_by',
            'a.created_time',
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
    function save_deviation_reference_edit_temp($data){
        $this->builder = $this->db->table('cms_deviation_reference_temp');
        $this->builder->whereIn('id', [$data['id']]);
        $query = $this->builder->get();
        $data2 = $query->getResultArray();

        $this->db->table('cms_deviation_reference')->delete(['deviation_id' => $data['deviation_reference_id']]); // Delete data from temporary table

        $this->db->table('cms_deviation_reference')->insertBatch($data2); // Insert data to main table

        $this->db->table('cms_deviation_reference_temp')->delete(['id' => $data['id']]); // Delete data from temporary table

        return $data;
    }
    function reject_deviation_reference($id){
        $this->builder = $this->db->table('cms_deviation_reference_temp');
        $return = $this->builder->where('id', $id)->delete();
        return $return;
    }
}