<?php
namespace App\Modules\SetupWaFilterWord\models;
use CodeIgniter\Model;

Class Setup_wa_filter_word_model Extends Model 
{
    function get_wa_filter_word_list(){
        $this->builder = $this->db->table('wa_filter_word');
		$this->builder->orderBy('created_time', 'desc');
        $select = array(
            'id',
            'word',
            'created_time',
            'created_by',
            'if(is_active="1","Active","Non Active") is_active'
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

    function save_wa_filter_word_add($data)
	{
        $this->builder = $this->db->table('wa_filter_word');
		$return = $this->builder->insert($data);
        
        $cacheKey = session()->get('USER_ID') . '_setup_wa_filter_word';
        cache()->delete($cacheKey);

		return $return;
	}

    function save_wa_filter_word_edit($data)
	{
        $this->builder = $this->db->table('wa_filter_word');
        $return = $this->builder->replace($data);
        
        $cacheKey = session()->get('USER_ID') . '_setup_wa_filter_word';
        cache()->delete($cacheKey);

		return $return;
	}
  
}
