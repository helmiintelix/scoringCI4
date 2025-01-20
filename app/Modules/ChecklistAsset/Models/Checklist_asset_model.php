<?php
namespace App\Modules\ChecklistAsset\models;
use CodeIgniter\Model;

Class Checklist_asset_model Extends Model 
{

    function save_field_checklist($data)
	{
        $return = $this->db->table('cc_matrix_field')->delete(['asset_type' => $data['asset_type']]); // Delete data from temporary table
        $field_order = $data['field_order'];
        foreach ($data['field_name'] as $a => $b) {
            // $field_id = $this->Common_model->clean_string2($b);
            $string = preg_replace('/[^A-Za-z0-9\-]/', '_', $b); 
            $data = [
                'id' => uuid(),
                'asset_type' => $data['asset_type'],
                'field_name' => $b,
                'field_name_id' => $string,
                'order_by' => $field_order[$a],
                'created_by' => $data['created_by'],
                'created_time' => date('Y-m-d H:i:s')
            ];
            $this->builder = $this->db->table('cc_matrix_field');
		    $return = $this->builder->insert($data);
        }

        return $return;

		// return $return;
	}

  
}
