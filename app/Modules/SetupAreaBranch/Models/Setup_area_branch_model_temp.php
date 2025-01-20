<?php
namespace App\Modules\SetupAreaBranch\models;
use CodeIgniter\Model;

Class Setup_area_branch_model_temp Extends Model 
{
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

    function save_area_branch_edit_temp($area_branch_data)
	{
        $this->builder = $this->db->table('cms_area_branch_temp');

        $this->builder->whereIn('id', [$area_branch_data['id']]);
        $query = $this->builder->get();
        $data = $query->getResultArray();

        $return=$this->db->table('cms_area_branch')->delete(['area_id' => $area_branch_data['area_id']]); // Delete data from temporary table

        $return=$this->db->table('cms_area_branch')->insertBatch($data); // Insert data to main table

        $return=$this->db->table('cms_area_branch_temp')->delete(['id' => $area_branch_data['id']]); // Delete data from temporary table

        $cacheKey = session()->get('USER_ID') . '_area_branch_list';
        cache()->delete($cacheKey);
        
        $cacheKey = session()->get('USER_ID') . '_area_branch_list_temp';
        cache()->delete($cacheKey);

		return $return;
	}

    function save_note_reject_area_branch($area_branch_data)
	{

        $return=$this->db->table('cms_area_branch_temp')->delete(['id' => $area_branch_data['id']]); // Delete data from temporary table

        $cacheKey = session()->get('USER_ID') . '_area_branch_list_temp';
        cache()->delete($cacheKey);
        return $return;
	}

  
}
