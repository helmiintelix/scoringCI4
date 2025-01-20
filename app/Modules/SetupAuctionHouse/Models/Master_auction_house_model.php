<?php
namespace App\Modules\SetupAuctionHouse\models;
use CodeIgniter\Model;

Class Master_auction_house_model Extends Model 
{
    function get_balai_lelang_list(){
        $this->builder = $this->db->table('cms_balai_lelang a');
        $select = array(
            'a.balai_id',
            'a.name',
            'a.address',
            'IF(a.is_active = "1", "ENABLE", "DISABLE") AS is_active',
            'a.created_by',
            'a.created_time'
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
    function isExist($id){
        $this->builder = $this->db->table('cms_balai_lelang');
        $this->builder->where(array(
            'balai_id' => $id
        ));
        $query = $this->builder->get();
        if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
    }
    function save_balai_lelang($data){
        $this->builder = $this->db->table('cms_balai_lelang');
        $return = $this->builder->insert($data);
        return $return;
    }
    function edit_balai_lelang($data){
        $this->builder = $this->db->table('cms_balai_lelang');
        $return = $this->builder->where('balai_id', $data['balai_id'])->update($data);
        return $return;
    }
}