<?php
namespace App\Modules\ParameterPengajuanDiskon\models;
use CodeIgniter\Model;

Class Parameter_pengajuan_diskon_model Extends Model 
{
    function get_discount_parameter_list(){
        $this->builder = $this->db->table('cms_discount_parameter');
        $select = array(
            'discount_parameter_id',
            'discount_parameter_name',
            'discount_parameter_detail',
            'updated_by',
            'bucket_list',
            'concat(max_normal_discount_rate,"%") max_normal_discount_rate',
            'concat(max_normal_discount_principal_rate,"%") max_normal_discount_principal_rate',
            'concat(max_normal_discount_interest_rate,"%") max_normal_discount_interest_rate',
            'concat(max_permanent_discount_rate,"%") max_permanent_discount_rate',
            'concat(max_kondisi_khusus_discount_rate,"%") max_kondisi_khusus_discount_rate',
            'date_format(updated_time,"%d-%m-%Y")updated_time',
            'IF(is_active = "1", "ENABLE", "DISABLE") AS is_active',
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->orderBy('discount_parameter_name', 'ASC');
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
            foreach ($return as &$row) {
                $array_data = json_decode($row['bucket_list']);
                $html_list = '<ul>';
                foreach ($array_data as $item) {
                    $html_list .= '<li>' . $item . '</li>';
                }
                $html_list .= '</ul>';
                $row['bucket_list'] = $html_list;
            }
            unset($row);
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
        // return $return;
    }

    function isExistDiscountParam($name){
        $this->builder = $this->db->table('cms_discount_parameter');
        $this->builder->where('discount_parameter_name', $name);
        $query = $this->builder->get();
        if ($query->getNumRows()) {
            return true;
        } else {
            return false;
        }
    }
    function save_discount_parameter_add($data){
        $this->builder = $this->db->table('cms_discount_parameter');
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_discount_parameter_edit($data){
        $this->builder = $this->db->table('cms_discount_parameter');
        $return = $this->builder->where('discount_parameter_id', $data['discount_parameter_id'])->update($data);
        return $return;
    }
}
