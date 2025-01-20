<?php
namespace App\Modules\FieldcollAndAgencyApprovalReassignment\models;
use CodeIgniter\Model;

Class Fieldcoll_and_agency_approval_reassignment_model Extends Model 
{
    function get_approval_reassignment_temp(){
        $this->builder = $this->db->table('cms_reassignment_temp a');
        $select = array(
            '*', 
            'IF(a.is_active = "1", "<span class=\"badge text-bg-success\">WAITING APPROVAL</span>", 
            "<span class=\"badge text-bg-danger\">REJECTED</span>") AS status_pengajuan', 
            'IF(a.flag = "1", "<span class=\"badge text-bg-success\">Re-Assignment</span>", 
            "<span class=\"badge text-bg-info\">EDIT</span>") AS jenis_pengajuan');
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->where('flag', '1');
        $this->builder->orderBy('a.from_date', 'DESC');
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
    function save_reassignment_approval($id){
        $this->builder = $this->db->table('cms_reassignment_temp');
        $this->builder->select("*");
        $this->builder->where('id', $id);
        $query = $this->builder->get();
        $data = $query->getResultArray();
        foreach ($data as $row) {
            $groupAssignment = $row['group_assignment'];
			$idAgency = $row['id_agency'];
			$customers = $row['customers'];
			$id_coll = $row['id_coll'];
			$id_field_coll = $row['id_field_coll'];
        }
		$list_customer = explode("|", $customers);
        switch ($groupAssignment) {
            case 'TEAM':
                $this->builder = $this->db->table('cms_team');
                $this->builder->select('agent_list');
                $this->builder->where('team_leader', $id_coll);
                $query = $this->builder->get()->getRow();
                if (!empty($query->agent_list)) {
                    $dataAgent = explode("|", $query->agent_list);
                } else {
                    $dataAgent = '';
                }
                

                $list_agent = array();
                foreach ($list_customer as $value) {
                    $this->builder = $this->db->table('cms_account_last_status');
                    $this->builder->set('assigned_agent_old', 'assigned_agent', false);
                    $this->builder->where('account_no', $value);
                    $this->builder->update();
                    if (count($list_agent) == 0) {
                        $list_agent = $dataAgent;
                    }
                    $assigned_agent = array_shift($list_agent);
                    $this->builder = $this->db->table('cpcrd_new');
                    $this->builder->where('CM_CARD_NMBR', $value);
                    $this->builder->set('AGENT_ID', $assigned_agent);
                    $this->builder->update();
                    
                    $this->builder = $this->db->table('cms_account_last_status');
                    $this->builder->where('account_no', $value);
                    $this->builder->set('assigned_agent', $assigned_agent);
                    $return = $this->builder->update();

                }
                break;
            case 'FC':
                $this->builder = $this->db->table('cpcrd_new');
                $this->builder->whereIn('CM_CARD_NMBR', $list_customer);
                $this->builder->set("AGENT_ID", $id_field_coll);
                $this->builder->update();

                foreach ($list_customer as $value) {
                    $this->builder = $this->db->table('cms_account_last_status');
                    $this->builder->set('assigned_agent_old', 'assigned_agent', false);
                    $this->builder->where('account_no', $value);
                    $this->builder->update();
                }

                $this->builder = $this->db->table('cms_account_last_status');
                $this->builder->whereIn('account_no', $list_customer);
                $this->builder->set('assigned_agent', $id_field_coll);
                $return = $this->builder->update();
            case 'AGENCY':
                $this->builder = $this->db->table('cpcrd_new a');;
                $this->builder->select("CM_CARD_NMBR as no_rekening, '$idAgency' as assigned_fc, AGENCY_ID as assigned_fc_old,  now() as assigned_fc_time, 'agency' as assign_type");
                $this->builder->join('cms_account_last_status b', 'a.CM_CARD_NMBR = b.account_no');
                $this->builder->whereIn('account_no', explode("|", $customers));
                $result = $this->builder->get()->getResultArray();

                foreach ($result as $row) {
                    $data2 = [
                        'no_rekening' => $row['no_rekening'],
                        'assigned_fc' => $row['assigned_fc'],
                        'assigned_fc_old' => $row['assigned_fc_old'],
                        'assigned_fc_time' => $row['assigned_fc_time'],
                        'assign_type' => $row['assign_type']
                    ];
                    $isExist = $this->isExist($row['no_rekening']);
                    $this->builder = $this->db->table('cms_assignment');
                    if ($isExist) {
                        $this->builder->where('no_rekening', $row['CM_CARD_NMBR']);
                        $this->builder->replace($data2);
                    } else {
                        $this->builder->insert($data2);
                    }
                }
                $this->builder = $this->db->table('cpcrd_new');
                $this->builder->whereIn('CM_CARD_NMBR', $list_customer);
                $this->builder->set('AGENCY_ID', $idAgency);
                $return = $this->builder->update();
            default:
                # code...
                break;
        }

        $this->builder = $this->db->table('cms_reassignment_temp');
        $this->builder->set('flag', '0');
        $this->builder->where('id', $id);
        $return = $this->builder->update();

        return $return;
    }
    function reject_reassignment($id){
        $this->builder = $this->db->table('cms_reassignment_temp');
        $return = $this->builder->where('id', $id)->delete();
        return $return;
    }
    function isExist($no_rekening){
        $this->builder = $this->db->table('cms_assignment');
        $this->builder->select('*');
        $this->builder->where('no_rekening', $no_rekening);
        $query = $this->builder->get();
        if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
    }
}