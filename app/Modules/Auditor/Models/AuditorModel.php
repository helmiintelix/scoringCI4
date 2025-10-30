<?php
namespace App\Modules\Auditor\models;
use CodeIgniter\Model;

Class AuditorModel Extends Model 
{
   

    function getAuditorList()
    {
      
        $select = [
            'a.action',
            'a.id',
            'a.id_scheme',
            'a.id_upload',
            'a.name_scheme',
            'a.score_value',
            'a.score_value2',
            'a.created_by',
            'a.created_time',
            'a.scheme_detail_before',
            'a.scheme_detail_after',
        ];

        $this->builder = $this->db->table('sc_scoring_log a');
        $this->builder->select(implode(', ', $select), false);
        $this->builder->orderBy('a.created_time', 'DESC');

        $rResult = $this->builder->get();

        $return = $rResult->getResultArray();

        $result = array();

        if ($rResult->getNumRows() > 0) {
            foreach ($rResult->getResultArray()[0] as $key => $value) {
                $exclude = ["scheme_detail_before", "scheme_detail_after"];

                if (!in_array($key, $exclude)) {
                    $result['header'][] = array('field' => $key, 'width'=> 350 );
                } 
            }

            
            foreach ($return as $key => $value) {
                $temp='';
                $temp2='';

                $buff  = json_decode($value['scheme_detail_before'],true);
                $buff2 = json_decode($value['scheme_detail_after'],true);
                if (!empty($buff)) {
                    foreach ($buff as $key1 => $value1) {
                        $temp.='<span>Parameter '.($key1+1).'</span><p>- id :'.@$value1["id"].'</p><p>- parameter :'.@$value1["parameter"].'</p><p>- parameter_id :'.@$value1["parameter_id"].'</p><p>- parameter_function :'.@$value1["parameter_function"].'</p><p>- parameter_value :'.@$value1["parameter_value"].'</p><p>- parameter_function_month :'.@$value1["parameter_function_month"].'</p><p>- parameter_value_month :'.@$value1["parameter_value_month"].'</p><p>- parameter_value_month_tmp :'.@$value1["parameter_value_month_tmp"].'</p><p>- mapping_reference :'.@$value1["mapping_reference"].'</p><p>- mapping_parameter_function :'.@$value1["mapping_parameter_function"].'</p><p>- mapping_parameter_value :'.@$value1["mapping_parameter_value"].'</p><p>- score_value :'.@$value1["score_value"].'</p><p>- score_value2 :'.@$value1["score_value2"].'</p><p>- client_id :'.@$value1["client_id"].'</p><p>- created_time :'.@$value1["created_time"].'</p><p>- created_by :'.@$value1["created_by"].'</p>';	 
                    }
                    $return[$key]['scheme_detail_before'] = $temp;
                }

                if (!empty($buff2)) {
                    foreach ($buff2 as $key2 => $value2) {
                        $temp2.='<span>Parameter '.($key2+1).'</span><p>- id :'.@$value2["id"].'</p><p>- parameter :'.@$value2["parameter"].'</p><p>- parameter_id :'.@$value2["parameter_id"].'</p><p>- parameter_function :'.@$value2["parameter_function"].'</p><p>- parameter_value :'.@$value2["parameter_value"].'</p><p>- parameter_function_month :'.@$value2["parameter_function_month"].'</p><p>- parameter_value_month :'.@$value2["parameter_value_month"].'</p><p>- parameter_value_month_tmp :'.@$value2["parameter_value_month_tmp"].'</p><p>- mapping_reference :'.@$value2["mapping_reference"].'</p><p>- mapping_parameter_function :'.@$value2["mapping_parameter_function"].'</p><p>- mapping_parameter_value :'.@$value2["mapping_parameter_value"].'</p><p>- score_value :'.@$value2["score_value"].'</p><p>- score_value2 :'.@$value2["score_value2"].'</p><p>- client_id :'.@$value2["client_id"].'</p><p>- created_time :'.@$value2["created_time"].'</p><p>- created_by :'.@$value2["created_by"].'</p>';	 
                    }
                    $return[$key]['scheme_detail_after'] = $temp2;
                }   
            }
            $result['data'] = $return;

            $rs =  $result;
            return $rs;
        } else {
            $rs =  $result;
            return $rs;
        }
        
    }


  
}
