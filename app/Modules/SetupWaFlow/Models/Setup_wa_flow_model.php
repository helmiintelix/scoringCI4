<?php
namespace App\Modules\SetupWaFlow\models;
use CodeIgniter\Model;

Class Setup_wa_flow_model Extends Model 
{
    function get_wa_flow_list(){
        $this->builder = $this->db->table('wa_template_flow');
		$this->builder->orderBy('created_time', 'desc');
        $select = array(
            'wa_id id',
            'template_name',
            'mask_name',
            'preview_message',
            'label_ops',
            'order_num',
            'parameter',
            'if(status="1","Active","Non Active") is_active',
            'is_routing',
            'created_time',
            'created_by',
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

    function save_wa_flow_add($data)
	{
        $this->builder = $this->db->table('wa_template_flow');
		$return = $this->builder->insert($data);
        
        $cacheKey = session()->get('USER_ID') . '_setup_wa_flow';
        cache()->delete($cacheKey);

		return $return;
	}

    function save_wa_flow_edit($data)
	{
        $this->builder = $this->db->table('wa_template_flow');
        $return = $this->builder->replace($data);
        
        $cacheKey = session()->get('USER_ID') . '_setup_wa_template';
        cache()->delete($cacheKey);

		return $return;
	}
  
}
