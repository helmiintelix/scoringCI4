<?php
namespace App\Modules\Holiday\models;
use CodeIgniter\Model;

Class Holiday_temp_model Extends Model 
{
    function get_holiday_list_temp(){
        $this->builder = $this->db->table('cc_holiday_temp a');
        $select = array(
            'a.id',
            'a.holiday_name',
            'a.holiday_date',
            'a.remark',
            'IF(a.is_active = "1", "WAITING APPROVAL", "REJECTED") AS status_approval',
            'IF(a.flag = "1", "ADD", "EDIT") AS jenis_request',
            'a.created_by',
            'a.created_time',
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
    function approve_request_holiday($id){
        $this->builder = $this->db->table('cc_holiday_temp');
        $this->builder->whereIn('id', [$id]);
        $query = $this->builder->get();
        $data = $query->getResultArray();

        $this->db->table('cc_holiday')->delete(['id' => $id]); // Delete data from temporary table

        $this->db->table('cc_holiday')->insertBatch($data); // Insert data to main table

        $this->db->table('cc_holiday_temp')->delete(['id' => $id]); // Delete data from temporary table

        return $data;
    }
    function delete_data_request($id){
        $this->builder = $this->db->table('cc_holiday_temp');
        $return = $this->builder->where('id', $id)->delete();
        return $return;
    }
}