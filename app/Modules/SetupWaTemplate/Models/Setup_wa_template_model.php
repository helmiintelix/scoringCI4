<?php
namespace App\Modules\SetupWaTemplate\models;
use CodeIgniter\Model;

Class Setup_wa_template_model Extends Model 
{
    function get_wa_template_list(){
        $this->builder = $this->db->table('cms_wa_template');
		$this->builder->orderBy('created_time', 'desc');
        $select = array(
            'id',
            'template_id',
            'template_name',
            'template_design',
            'parameter',
            'select_time as send_time',
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

    function save_wa_template_add($data)
	{
        $this->builder = $this->db->table('cms_wa_template');
		$return = $this->builder->insert($data);
        
        $cacheKey = session()->get('USER_ID') . '_setup_wa_template';
        cache()->delete($cacheKey);

		return $return;
	}

    function save_wa_template_edit($data)
	{
        
        $this->builder = $this->db->table('cms_wa_template');
        
        $this->builder->where('id', $data['id']); 
        $this->builder->delete();
        
        $return = $this->builder->insert($data);
        
        $cacheKey = session()->get('USER_ID') . '_setup_wa_template';
        cache()->delete($cacheKey);

        return $return;
	}
  
}
