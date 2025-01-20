<?php
namespace App\Modules\Bucket\models;
use CodeIgniter\Model;

Class Bucket_maker_model Extends Model 
{
    function get_bucket_list(){
        $this->builder = $this->db->table('cms_bucket a');
        $select = array(
            '*'
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
    function isExistbucketId($id){
        $this->builder = $this->db->table('cms_bucket_temp');
        $this->builder->where(array(
            'bucket_id' => $id
        ));
        $query = $this->builder->get();
        if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
    }
    function save_bucket_add($data){
        $this->builder = $this->db->table('cms_bucket_temp');
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