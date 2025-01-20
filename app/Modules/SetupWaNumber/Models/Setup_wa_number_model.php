<?php
namespace App\Modules\SetupWaNumber\models;
use CodeIgniter\Model;

Class Setup_wa_number_model Extends Model 
{
    function get_device_list(){
        $this->builder = $this->db->table('wa_devices a');
		$this->builder->orderBy('last_update', 'desc');
        $select = array(
            'id',
            // 'IF(a.status = "1", "<span class=\"label label-sm label-success\">Ready</span>", IF(a.status = "0", "<span class=\"label label-sm label-primary\">Idle</span>","<span class=\"label label-sm label-danger\">Disconnect</span>")) AS status',
           'IF(a.status = "1", "Ready", IF(a.status = "0","Idle","Disconnect")) AS status',
            'phone as wa_phone_number',
            'expired_date',
            'agent_list',
            'url',
            'last_update as created_time',
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

    function isExistVRId($id){
        $this->builder = $this->db->table('wa_devices');
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

    function save_device_add($data)
	{
        $this->builder = $this->db->table('wa_devices');
		$return = $this->builder->insert($data);
        
        $cacheKey = session()->get('USER_ID') . '_setup_wa_number';
        cache()->delete($cacheKey);

		return $return;
	}

    function save_device_edit($data)
	{
        $data_update = array(
			'id' => $data['id'],
			'phone' => $data['phone'],
			'expired_date' => $data['expired_date'],
			'token' => $data['token'],
			'last_update' => $data['last_update'],
			'status' => $data['status'],
            'passwd' => $data['passwd'],
            'agent_list' => $data['agent_list'],
            'url' => $data['url'],
            'type' => $data['type']
		);

		$this->builder = $this->db->table('wa_devices');
        $this->builder->where('id', $data['id']); 
        $return = $this->builder->update($data_update); 
        $cacheKey = session()->get('USER_ID') . '_setup_wa_number';
        cache()->delete($cacheKey);
		return $return;
	}
  
}
