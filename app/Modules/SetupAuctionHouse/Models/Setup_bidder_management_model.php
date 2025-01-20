<?php
namespace App\Modules\SetupAuctionHouse\models;
use CodeIgniter\Model;

Class Setup_bidder_management_model Extends Model 
{
    function get_bidder_list(){
        $this->builder = $this->db->table('cms_bidder a');
        $select = array(
            'a.bidder_id as id',
            'a.name',
            'b.branch_name',
            'c.area_name',
            'a.address',
            'a.phone_1',
            'a.id_card',
            'IF(a.is_active = "1", "ENABLE", "DISABLE") AS is_active',
            'a.created_by',
            'a.created_time'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cms_branch b', 'a.branch_id=b.branch_id');
        $this->builder->join('cms_area_branch c', 'a.area_id=c.area_id');
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
        $this->builder = $this->db->table('cms_bidder');
        $this->builder->where(array(
            'bidder_id' => $id
        ));
        $query = $this->builder->get();
        if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
    }
    function save_bidder($data){
        $this->builder = $this->db->table('cms_bidder');
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_bidder_edit($data){
        $this->builder = $this->db->table('cms_bidder');
        $return = $this->builder->where('bidder_id', $data['bidder_id'])->update($data);
        return $return;
    }
}