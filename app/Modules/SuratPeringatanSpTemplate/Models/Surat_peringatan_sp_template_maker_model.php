<?php
namespace App\Modules\SuratPeringatanSPTemplate\models;
use CodeIgniter\Model;

Class Surat_peringatan_sp_template_maker_model Extends Model 
{
    function get_letter_list(){
        $this->builder = $this->db->table('cms_letter_template');
        $select = array(
            'DATE_FORMAT(updated_time,"%d-%b-%Y") updated_time', 
            'letter_id', 
            'info', 
            'dpd_from', 
            'dpd_to', 
            'content', 
            'IF(flag_tmp = "1", "<span class=\"badge bg-success\">APPROVED</span>", 
            IF(flag_tmp = "2", "<span class=\"badge bg-danger\">REJECTED</span>", 
            "<span class=\"badge bg-warning\">WAITING APPROVAL</span>")) AS flag_tmp');
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
        // return $return;
    }
    function isExistSP_templateUpdateId($id){
        $this->builder = $this->db->table('cms_letter_template_tmp');
        $this->builder->where('letter_id', $id);
        $this->builder->where('flag_tmp', '0');
        $query = $this->builder->get()->getNumRows();
        if ($query > 0) {
			return true;
		} else {
			return false;
		}

    }
    function save_letter_add($data){
        $this->builder = $this->db->table('cms_letter_template_tmp');
        $this->builder->where('letter_id', $data['letter_id'])->delete();
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_letter_edit($data){
        $this->builder = $this->db->table('cms_letter_template');
        $this->builder->where('letter_id', $data['letter_id']);
        $data2 = $this->builder->get()->getResultArray();
        $query = $this->db->table('cms_letter_template_tmp')->insertBatch($data2);
        if ($query) {
            $this->builder = $this->db->table('cms_letter_template_tmp');
            $this->builder->where('letter_id', $data['letter_id']);
            $return = $this->builder->update($data);
        }
        return $return;
    }
}