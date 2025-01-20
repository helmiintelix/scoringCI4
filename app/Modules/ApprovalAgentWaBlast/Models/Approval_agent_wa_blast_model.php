<?php
namespace App\Modules\ApprovalAgentWaBlast\models;
use CodeIgniter\Model;
use App\Models\Common_model;

Class Approval_agent_wa_blast_model Extends Model 
{
    protected $Common_model;
    function __construct(){
        parent::__construct();
        $this->Common_model = new Common_model();
    }
    function get_pengiriman_surat_file_list(){
        $this->builder = $this->db->table('wa_outbound');
        $this->builder->where('is_approved', 'NEED APPROVAL');
        $this->builder->where('source', 'agent');
        $select = array(
            'id',
            'ref_id account_number',
            'to_number',
            'template_name template_id',
            'template_data',
            'message',
            'created_by',
            'created_time',
            'is_approved',
            'approved_by',
            'approved_time'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->orderBy('created_time', 'DESC');
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
    function save_wa_blast_upload($data){
        $this->builder = $this->db->table('wa_upload_data');
        $return = $this->builder->insert($data);
        return $return;
    }

    function save_wa_blast_upload_detail($data){
        $this->builder = $this->db->table('wa_upload_data_detail');
        $return = $this->builder->insert($data);
        return $return;
    }

    function save_pengiriman_surat_from_file($data){
        $this->builder = $this->db->table('tmp_upload_pengiriman_surat');
        $return = $this->builder->insertBatch($data);
        return $return;
    }
    function show_activity_by_file($id){
        $this->builder = $this->db->table('wa_outbound');
        $this->builder->select('id,ref_id account_number,to_number phone_number,template_name,message,is_approved status');
        $this->builder->where('is_approved', 'NEED APPROVAL');
        $this->builder->where('source', 'agent');
        $result = $this->builder->get()->getResultArray();
        return $result;
    }

    function approve_upload_file($data){
               
        $this->builder = $this->db->table('wa_outbound');
        $this->builder->where('id', $data['upload_id']);
        $this->builder->set('is_approved', 'APPROVED');
        $this->builder->set('approved_by', session()->get('USER_ID'));
        $this->builder->set('approved_time', date('Y-m-d H:i:s'));
        //$this->builder->set('notes', 'APPROVED');

        $result = $this->builder->update();
        
        return $result;
    }

    function reject_upload_file($data){
         
        $this->builder = $this->db->table('wa_outbound');
        $this->builder->where('id', $data['upload_id']);
        $this->builder->set('is_approved', 'REJECT');
        $this->builder->set('approved_by', session()->get('USER_ID'));
        $this->builder->set('approved_time', date('Y-m-d H:i:s'));
        //$this->builder->set('notes', 'REJECT');

        $result = $this->builder->update();
        
        return $result;
    }
}