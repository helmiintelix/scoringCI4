<?php
namespace App\Modules\SuratPeringatanSPTemplate\models;
use CodeIgniter\Model;

Class Surat_peringatan_sp_template_temp_model Extends Model 
{
    function get_letter_list_temp(){
        $this->builder = $this->db->table('cms_letter_template_tmp');
        $select = array(
            'letter_id', 
            'info', 
            'dpd_from', 
            'dpd_to', 
            'content', 
            'DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time', 
            'IF(flag_tmp = "1", "<span class=\"badge text-bg-success\">APPROVED</span>", 
            IF(flag_tmp = "2", "<span class=\"badge text-bg-danger\">REJECTED</span>", 
            "<span class=\"badge text-bg-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
      
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
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
    function save_letter_edit_temp($id){
        $this->builder = $this->db->table('cms_letter_template_tmp');
        $this->builder->whereIn('letter_id', [$id]);
        $query = $this->builder->get();
        $data = $query->getResultArray();

        $this->db->table('cms_letter_template')->delete(['letter_id' => $id]); // Delete data from temporary table

        $this->db->table('cms_letter_template')->insertBatch($data); // Insert data to main table

        $this->db->table('cms_letter_template_tmp')->delete(['letter_id' => $id]); // Delete data from temporary table

        $builder = $this->db->table('cms_letter_template_tmp');
        $builder->where('letter_id', $id);
        $builder->set('flag_tmp', '1');
        $builder->set('updated_time', date('Y-m-d H:i:s'));
        $builder->set('updated_by', session()->get('USER_ID'));
        $return = $builder->update();

        $builder = $this->db->table('cms_letter_template');
        $builder->where('letter_id', $id);
        $builder->set('flag_tmp', '1');
        $builder->set('updated_time', date('Y-m-d H:i:s'));
        $builder->set('updated_by', session()->get('USER_ID'));
        $return = $builder->update();

        return $return;
    }
    function delete_letter_edit_temp($id){
        $this->builder = $this->db->table('cms_letter_template_tmp');
        $return = $this->builder->where('letter_id', $id)->delete();
        return $return;
    }
}