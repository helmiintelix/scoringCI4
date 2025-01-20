<?php
namespace App\Modules\ParameterPengajuanRestructure\models;
use CodeIgniter\Model;

Class Parameter_pengajuan_restructure_model Extends Model 
{
    function get_restructure_parameter_list(){
        $this->builder = $this->db->table('cms_restructure_parameter a');
        $select = array(
            'a.restructure_parameter_id',
            'a.restructure_parameter_name',
            'a.restructure_parameter_detail',
            'CASE WHEN restructure_tipe = "RSTR" THEN "RESTRUCTURE" ELSE "RESCHEDULE" END AS restructure_tipe',
            'a.bucket_list',
            'concat(max_discount_rate,"%") max_discount_rate',
            'concat(max_interest_rate,"%") max_interest_rate',
            'concat(ratio_cicilan,"%") ratio_cicilan',
            'a.max_tenor',
            'a.updated_by',
            'a.updated_time',
            "CASE WHEN is_active = 1 THEN 'ENABLE' ELSE 'DISABLE' END AS is_active"
        );
       
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
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
    function save_restructure_parameter_add($data){
        $this->builder = $this->db->table('cms_restructure_parameter');
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_restructure_parameter_edit($data){
        $this->builder = $this->db->table('cms_restructure_parameter');
        $return = $this->builder->where('restructure_parameter_id', $data['restructure_parameter_id'])->update($data);
        return $return;
    }
}