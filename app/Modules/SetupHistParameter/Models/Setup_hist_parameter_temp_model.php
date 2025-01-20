<?php
namespace App\Modules\SetupHistParameter\models;
use CodeIgniter\Cookie\Cookie;
use CodeIgniter\Model;

Class Setup_hist_parameter_temp_model Extends Model 
{
   
    function get_setup_angsuran_temp(){
        $this->builder = $this->db->table('aav_configuration_tmp a');
        $select = array(
            'a.id', 
            'a.name', 
            'a.value', 
            'concat(b.id," - ",b.name) created_by', 
            'date_format(a.created_time,"%d %b %Y") created_time'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cc_user b', 'a.created_by=b.id', FALSE);
        $this->builder->where('a.add_field1', 'APPROVAL');
        $this->builder->orderBy('a.id', 'asc');
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
    function approve_system_setting($data){
        $this->builder = $this->db->table('aav_configuration_tmp');
        $this->builder->where('parameter', 'SYSTEM');
        $this->builder->where('add_field1', 'APPROVAL');
        $this->builder->where('id', $data['id']);
        $query = $this->builder->get();
        $data2 = $query->getResultArray();

        if (!empty($data2)) {
            foreach ($data2 as $row) {
                $builder = $this->db->table('aav_configuration');
                $builder->where('id', $row['id']);
                $builder->delete();
                $builder->insert($row);
            }
        }

        $this->builder = $this->db->table('aav_configuration_tmp');
        $this->builder->where('parameter', 'SYSTEM');
        $this->builder->where('id', $data['id']);
        $return = $this->builder->update($data);

        return $return;
    }
    function reject_system_setting($data){
        $this->builder = $this->db->table('aav_configuration_tmp');
        $this->builder->where('parameter', 'SYSTEM');
        $this->builder->where('id', $data['id']);
        $return = $this->builder->update($data);
        return $return;
    }
}