<?php
namespace App\Modules\Holiday\models;
use CodeIgniter\Model;

Class Holiday_maker_model Extends Model 
{
    function get_holiday_list(){
        $this->builder = $this->db->table('cc_holiday a');
        $select = array(
            'a.id',
            'a.holiday_name',
            'a.holiday_date',
            'a.remark',
            // 'IF(a.is_active = "1", "ENABLE", "DISABLE") AS is_active',
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
        // return $return;
    }
    function save_holiday_add($data){
        $this->builder = $this->db->table('cc_holiday_temp');
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_holiday_edit($data){
        $this->builder = $this->db->table('cc_holiday_temp');
        $return = $this->builder->insert($data);
        return $return;
    }
    function delete_holiday($id){
        $this->builder = $this->db->table('cc_holiday');
        $return = $this->builder->where('id', $id)->delete();
        return $return;
    }
}