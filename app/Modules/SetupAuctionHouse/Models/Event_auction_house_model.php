<?php
namespace App\Modules\SetupAuctionHouse\models;
use CodeIgniter\Model;

Class Event_auction_house_model Extends Model 
{
    function get_event_balai_lelang_list(){
        $this->builder = $this->db->table('cms_balai_lelang a');
        $select = array(
            'b.id',
            'a.name as balai_name',
            'b.location',
            'b.description',
            'b.name',
            'b.event_date',
            'IF(b.is_active = "1", "ENABLE", "DISABLE") AS is_active',
            'a.created_by',
            'a.created_time'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cms_balai_lelang_event b', 'a.balai_id=b.balai_id');
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
        $this->builder = $this->db->table('cms_balai_lelang_event');
        $this->builder->where(array(
            'id' => $id
        ));
        $query = $this->builder->get();
        if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
    }
    function save_event_balai_lelang($data){
        $this->builder = $this->db->table('cms_balai_lelang_event');
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_edit_event_balai_lelang($data){
        $this->builder = $this->db->table('cms_balai_lelang_event');
        $return = $this->builder->where('id', $data['id'])->update($data);
        return $return;
    }
}