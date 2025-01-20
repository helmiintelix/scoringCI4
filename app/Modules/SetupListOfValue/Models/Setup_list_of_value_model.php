<?php
namespace App\Modules\SetupListOfValue\models;
use CodeIgniter\Model;

Class Setup_list_of_value_model Extends Model 
{
    function get_lov_registration(){
        $this->builder = $this->db->table('cms_lov_registration a');
        $DBDRIVER = $this->db->DBDriver;

        if ($DBDRIVER === 'SQLSRV') {
            // SQL Server
            $is_active = "CASE 
                        WHEN a.is_active = '1' THEN 'Yes'
                        ELSE 'No'
                    END ";
        } elseif ($DBDRIVER === 'Postgre') {
            // PostgreSQL
            $is_active = " CASE 
                            WHEN a.is_active = '1' THEN 'Yes'
                            ELSE 'No'
                        END  ";
        } else {
            // MySQL
            $is_active = "IF(a.is_active = '1', 'Yes', 'No')";
        }

        $select = array(
            'id as id_lov_category',
            'category_name as lov_description',
            'category_lov',
            'hirarki',
            $is_active.' AS is_active',
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

    function get_lov_list(){
        $this->builder = $this->db->table('cms_lov_relation a');

        $DBDRIVER = $this->db->DBDriver;

        if ($DBDRIVER === 'SQLSRV') {
            // SQL Server
            $is_active = "CASE 
                        WHEN a.is_active = '1' THEN 'Active'
                        ELSE 'Not Active'
                    END ";
        } elseif ($DBDRIVER === 'Postgre') {
            // PostgreSQL
            $is_active = " CASE 
                            WHEN a.is_active = '1' THEN 'Active'
                            ELSE 'Not Active'
                        END  ";
        } else {
            // MySQL
            $is_active = "IF(a.is_active = '1', 'Active', 'Not Active')";
        }

        $select = array(
            'lov_id',
            $is_active.' AS active',
            'lov1_label_name as lov_1_label_name',
            'lov1_category as lov_1_category',
            'lov2_label_name as lov_2_label_name',
            'lov2_category as lov_2_category',
            'lov3_label_name as lov_3_label_name',
            'lov3_category as lov_3_category',
            'lov4_label_name as lov_4_label_name',
            'lov4_category as lov_4_category',
            'lov5_label_name as lov_5_label_name',
            'lov5_category as lov_5_category',
            'created_by',
            'created_time',
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

    function isExistLovCategory($id)
	{
		$this->builder = $this->db->table('cms_lov_registration a');
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

    function save_lov_add($lov_data)
	{

        $this->builder = $this->db->table('cms_lov_registration');
		$return = $this->builder->insert($lov_data);

        $cacheKey = session()->get('USER_ID') . '_lov_registration';
        cache()->delete($cacheKey);

        $cacheKey = session()->get('USER_ID') . '_lov_list_old';
        cache()->delete($cacheKey);

		return $return;
	}

    function save_lov_edit($data)
	{
        $data_update = [
            'category_name' => $data["category_name"],
            'category_lov' => $data["category_lov"],
            'hirarki' => $data["hirarki"],
            'is_active' => $data["is_active"],
            'updated_by' => session()->get('USER_ID'),
            'updated_time' => date('Y-m-d H:i:s')
        ];

        $this->builder = $this->db->table('cms_lov_registration');
        $this->builder->where('id', $data['id']); 
        $this->builder->set($data_update); 
        $return = $this->builder->update($data_update);

        $cacheKey = session()->get('USER_ID') . '_lov_registration';
        cache()->delete($cacheKey);

        $cacheKey = session()->get('USER_ID') . '_lov_list_old';
        cache()->delete($cacheKey);

		return $return;
	}

    function save_lov_relation_edit($lov_data, $id)
	{

        $this->builder = $this->db->table('cms_lov_relation');
        $this->builder->where('lov_id', $id); 
        $this->builder->set($lov_data); 
        $return = $this->builder->update($lov_data);

        $cacheKey = session()->get('USER_ID') . '_lov_registration';
        cache()->delete($cacheKey);

        $cacheKey = session()->get('USER_ID') . '_lov_list_old';
        cache()->delete($cacheKey);

		return $return;
	}

  
}
