<?php
namespace App\Modules\SetupDeviationApproval\models;
use CodeIgniter\Model;

Class Setup_deviation_approval_maker_model Extends Model 
{
    function get_deviation_approval_list(){
        $this->builder = $this->db->table('cms_deviation_approval a');
        $select = array(
            'a.id', 
            'a.dev_app_id', 
            'a.dev_app_name', 
            'a.dev_ref_id dev_ref_id', 
            'b.name nameapp1user1', 
            'c.name nameapp1user2', 
            'd.name nameapp1user3', 
            'e.name nameapp2user1', 
            'f.name nameapp2user2', 
            'g.name nameapp2user3', 
            'h.name nameapp3user1', 
            'i.name nameapp3user2', 
            'j.name nameapp3user3', 
            'k.name nameapp4user1', 
            'l.name nameapp4user2', 
            'm.name nameapp4user3', 
            'a.created_time', 
            'o.name created_by',
            // 'IF(a.is_active = "1", "<span class=\"badge bg-success\">ACTIVE</span>", "<span class=\"badge bg-danger\">INACTIVE</span>") as is_active',
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cc_user b', 'a.app_1_user_1 = b.id', 'left');
		$this->builder->join('cc_user c', 'a.app_1_user_2 = c.id', 'left');
		$this->builder->join('cc_user d', 'a.app_1_user_3 = d.id', 'left');
		$this->builder->join('cc_user e', 'a.app_2_user_1 = e.id', 'left');
		$this->builder->join('cc_user f', 'a.app_2_user_2 = f.id', 'left');
		$this->builder->join('cc_user g', 'a.app_2_user_3 = g.id', 'left');
		$this->builder->join('cc_user h', 'a.app_3_user_1 = h.id', 'left');
		$this->builder->join('cc_user i', 'a.app_3_user_2 = i.id', 'left');
		$this->builder->join('cc_user j', 'a.app_3_user_3 = j.id', 'left');
		$this->builder->join('cc_user k', 'a.app_4_user_1 = k.id', 'left');
		$this->builder->join('cc_user l', 'a.app_4_user_2 = l.id', 'left');
		$this->builder->join('cc_user m', 'a.app_4_user_3 = m.id', 'left');
		$this->builder->join('cc_user o', 'a.created_by = o.id', 'left');
        $this->builder->where('a.is_active', '1');
        $this->builder->orderBy('a.created_time', 'desc');
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
    function isExistdeviation_approvalId($id){
        $this->builder = $this->db->table('cms_deviation_approval');
        $this->builder->where(array(
            'dev_app_id' => $id
        ));
        $query = $this->builder->get();
        if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
    }
    function isExistDeviationApprovalId($id){
        $this->builder = $this->db->table('cms_deviation_approval_temp');
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
    function save_deviation_approval_add($data){
        $this->builder = $this->db->table('cms_deviation_approval_temp');
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_deviation_approval_edit($data){
        $this->builder = $this->db->table('cms_deviation_approval');
        $this->builder->where('id', $data['id']);
        $data2 = $this->builder->get()->getResultArray();
        $query = $this->db->table('cms_deviation_approval_temp')->insertBatch($data2);
        if ($query) {
            $builder = $this->db->table('cms_deviation_approval_temp');
            $builder->where('id', $data['id']);
            $return = $builder->update($data);
        }
        return $return;
    }
    function delete_deviation_approval($id){
        $this->builder = $this->db->table('cms_deviation_approval');
        $this->builder->where('dev_app_id', $id);
        $return = $this->builder->delete();
        return $return;
    }
}