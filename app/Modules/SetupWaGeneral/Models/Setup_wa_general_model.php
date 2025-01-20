<?php
namespace App\Modules\SetupWaGeneral\models;
use CodeIgniter\Model;

Class Setup_wa_general_model Extends Model 
{
    function get_wa_general_list(){
        $this->builder = $this->db->table('wa_general_setup');
		$this->builder->orderBy('updated_time', 'desc');
        $select = array(
            'id',
            'attemp_per_agent',
            'attemp_per_cust',
            'from_wa_outgoing',
            'to_wa_outgoing',
            'from_office_hour',
            'to_office_hour',
            'holiday_content',
            'updated_time created_time',
            'updated_by created_by',
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $rResult = $this->builder->get();

        $return = $rResult->getResultArray();

        $result = array();

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

    function save_wa_general_add($data)
	{
        $this->builder = $this->db->table('wa_general_setup');
		$return = $this->builder->insert($data);
        
        $cacheKey = session()->get('USER_ID') . '_setup_wa_general';
        cache()->delete($cacheKey);

		return $return;
	}

    function save_wa_general_edit($data)
	{
        $this->builder = $this->db->table('wa_general_setup');
        $return = $this->builder->replace($data);
        
        $cacheKey = session()->get('USER_ID') . '_setup_wa_general';
        cache()->delete($cacheKey);

		return $return;
	}
  
}
