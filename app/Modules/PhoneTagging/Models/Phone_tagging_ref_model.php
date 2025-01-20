<?php
namespace App\Modules\PhoneTagging\models;
use CodeIgniter\Model;

Class Phone_tagging_ref_model Extends Model 
{
    function get_phone_tagging_ref(){
        $this->builder = $this->db->table('cms_phonetag_ref a');
        $select = array(
            '*',
            'IF(a.status = "1", "ACTIVE", "NOT ACTIVE") AS status',
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
    function save_phone_tagging_ref($data){
        $this->builder = $this->db->table('cms_phonetag_ref');
        $return = $this->builder->insert($data);

        $this->builder->select([
            "'ACCOUNT_TAGGING' AS reference",
            'id AS value',
            'description',
            'status AS flag',
            'created_time',
            'created_by',
            'updated_time',
            'updated_by'
        ]);
        $this->builder->where('id', $data['id']);
        $result = $this->builder->get()->getResultArray();
        $builder = $this->db->table('cms_reference');
        foreach ($result as $key => $value) {
            $data_ref['reference']		 = $value['reference'];
            $data_ref['value']	 = $value['value'];
            $data_ref['description']	 = $value['description'];
            $data_ref['flag']		 = $value['flag'];
            $data_ref['created_time']		 = $value['created_time'];
            $data_ref['created_by']	 =  $value['created_by'];
            $data_ref['updated_time']	 =  $value['updated_time'];
            $data_ref['updated_by']			 = $value['updated_by'];
            $builder->replace($data_ref);
        }

        return $return;
    }
    function save_phone_tagging_ref_edit($data){
        $this->builder = $this->db->table('cms_phonetag_ref');
        $this->builder->where('id', $data['id']);
        $return = $this->builder->update($data);

        $this->builder->select([
            "'ACCOUNT_TAGGING' AS reference",
            'id AS value',
            'desc AS description',
            'status AS flag',
            'created_time',
            'created_by',
            'updated_time',
            'updated_by'
        ]);
        $this->builder->where('id', $data['id']);
        $result = $this->builder->get()->getResultArray();
        $builder = $this->db->table('cms_reference');
        foreach ($result as $key => $value) {
            $data_ref['reference']		 = $value['reference'];
            $data_ref['value']	 = $value['value'];
            $data_ref['description']	 = $value['description'];
            $data_ref['flag']		 = $value['flag'];
            $data_ref['created_time']		 = $value['created_time'];
            $data_ref['created_by']	 =  $value['created_by'];
            $data_ref['updated_time']	 =  $value['updated_time'];
            $data_ref['updated_by']			 = $value['updated_by'];
            $builder->replace($data_ref);
        }

        return $return;
    }
}