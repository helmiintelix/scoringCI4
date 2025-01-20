<?php
namespace App\Modules\PhoneTagging\models;
use CodeIgniter\Model;

Class Phone_tagging_list_model Extends Model 
{
    function get_phone_tagging_list(){
        $this->builder = $this->db->table('cms_phonetag_list');
        $select = array(
            '*',
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
    function save_phone_tagging($data){
        $this->builder = $this->db->table('cms_phonetag_list');
        $this->builder->where('id', $data['id']);
        $return = $this->builder->update($data);
        return $return;
    }
}