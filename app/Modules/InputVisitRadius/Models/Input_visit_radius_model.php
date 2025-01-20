<?php
namespace App\Modules\InputVisitRadius\models;
use CodeIgniter\Model;

Class Input_visit_radius_model Extends Model 
{
   

    function get_visit_radius_all_list()
    {
        $this->builder = $this->db->table('cms_visit_radius_all a');

        $select = array(
            'IF(a.is_active = "1", "Active", "Not Active") AS status',
            'a.id',
            'a.radius as Radius',
            'a.created_by',
            'a.created_date'
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

    function isExist($id){
        $this->builder = $this->db->table('cms_visit_radius_all');
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

    function save_visit_radius_all_add($data)
	{
        $this->builder = $this->db->table('cms_visit_radius_all');
		$return = $this->builder->insert($data);
        
        $cacheKey = session()->get('USER_ID') . '_visit_radius_all_list';
        cache()->delete($cacheKey);

		return $return;
	}

	function save_visit_radius_all_update($data)
	{
		$data_update = array(
			'radius' => $data['radius'],
			'is_active' => $data['is_active'],
			'created_date' => $data['created_date'],
			'created_by' => $data['created_by']
		);

		$this->builder = $this->db->table('cms_visit_radius_all');
        $this->builder->where('id', $data['id']); 
        $this->builder->set($data_update); // Atur data update
        // $query = $this->builder->getCompiledUpdate(); // Ambil query terakhir
        $return = $this->builder->update($data_update); // Eksekusi update
        // echo $query;
        $cacheKey = session()->get('USER_ID') . '_visit_radius_all_list';
        cache()->delete($cacheKey);
		return $return;
	}
  
}
