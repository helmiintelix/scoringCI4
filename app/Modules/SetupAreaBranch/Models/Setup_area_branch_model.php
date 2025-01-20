<?php
namespace App\Modules\SetupAreaBranch\models;
use CodeIgniter\Model;

Class Setup_area_branch_model Extends Model 
{
   

    function get_area_branch_list()
    {
      
        $this->builder = $this->db->table('cms_area_branch a');

        $select = array(
            'a.id',
            'a.area_id as area_id',
            'b.description AS Provinsi_Area',
            'c.description AS City_Area',
            'd.description AS Kecamatan_Area',
            'e.description AS Keluarahan_Area',
            'a.branch_list',
            'a.area_address AS Alamat_Area',
            'a.area_no_telp AS No_Telp_Area',
            'a.area_manager',
            'a.created_time',
            'a.created_by'
        );

        $this->builder->join('cms_reference_region b ', 'a.area_prov = b.code', 'left');
        $this->builder->join('cms_reference_region c ', 'a.area_city = c.code', 'left');
        $this->builder->join('cms_reference_region d ', 'a.area_kec = d.code', 'left');
        $this->builder->join('cms_reference_region e ', 'a.area_kel = e.code', 'left');
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

    function get_area_branch_list_temp()
    {
        $this->builder = $this->db->table('cms_area_branch_temp a');

        $select = array(
            'a.id',
            'a.area_id as area_id',
            'b.description AS Provinsi_Area',
            'c.description AS City_Area',
            'd.description AS Kecamatan_Area',
            'e.description AS Keluarahan_Area',
            'a.branch_list',
            'a.area_address AS Alamat_Area',
            'a.area_no_telp AS No_Telp_Area',
            'a.area_manager',
            'a.created_time as tgl_pengajuan',
            'a.created_by as diajukan_oleh',
            'IF(a.is_active = "1", "WAITING APPROVAL", "REJECTED") AS status_approval',
            'IF(a.flag = "1", "ADD", "EDIT") AS jenis_req',
        );

        $this->builder->join('cms_reference_region b ', 'a.area_prov = b.code', 'left');
        $this->builder->join('cms_reference_region c ', 'a.area_city = c.code', 'left');
        $this->builder->join('cms_reference_region d ', 'a.area_kec = d.code', 'left');
        $this->builder->join('cms_reference_region e ', 'a.area_kel = e.code', 'left');
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
        $this->builder = $this->db->table('cms_area_branch a');
        $this->builder->where(array(
			'area_id' => $id
		));
        
		$query = $this->builder->get();

		if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
    }

    function isExistarea_branch_tempId($id)
	{
		$this->builder = $this->db->table('cms_area_branch_temp a');
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

    function save_area_branch_add($data)
	{
        $this->builder = $this->db->table('cms_area_branch_temp');
		$return = $this->builder->insert($data);

        $cacheKey = session()->get('USER_ID') . '_area_branch_list';
        cache()->delete($cacheKey);
        
        $cacheKey = session()->get('USER_ID') . '_area_branch_list_temp';
        cache()->delete($cacheKey);

		return $return;
	}

    function save_area_branch_edit($area_branch_data)
	{

        $this->builder = $this->db->table('cms_area_branch')
            ->where('id', $area_branch_data["id"])
            ->get();

        if ($this->builder->getNumRows() > 0) {
            $data = $this->builder->getRowArray();
            
            $this->builder = $this->db->table('cms_area_branch_temp');
            $return = $this->builder->insert($data);

            $data_update = array(
                'area_id' => $area_branch_data["area_id"],
                'area_name' => $area_branch_data["area_name"],
                'area_prov' => $area_branch_data["area_prov"],
                'area_city' => $area_branch_data["area_city"],
                'area_kec' => $area_branch_data["area_kec"],
                'area_kel' => $area_branch_data["area_kel"],
                'branch_list' => $area_branch_data["branch_list"],
                'area_address' => $area_branch_data["area_address"],
                'area_no_telp' => $area_branch_data["area_no_telp"],
                'area_manager' => $area_branch_data["area_manager"],
                'is_active' => $area_branch_data["is_active"],
                'flag' => $area_branch_data["flag"]
            );
    
            $this->builder = $this->db->table('cms_area_branch_temp');
            $this->builder->where('id', $area_branch_data['id']); 
            $this->builder->set($data_update); 
            $return = $this->builder->update($data_update);

            $cacheKey = session()->get('USER_ID') . '_area_branch_list';
            cache()->delete($cacheKey);
            
            $cacheKey = session()->get('USER_ID') . '_area_branch_list_temp';
            cache()->delete($cacheKey);
            return $return;

        } 
	}

  
}
