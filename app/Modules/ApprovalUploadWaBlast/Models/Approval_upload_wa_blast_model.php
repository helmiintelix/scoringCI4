<?php
namespace App\Modules\ApprovalUploadWaBlast\models;
use CodeIgniter\Model;
use App\Models\Common_model;

Class Approval_upload_wa_blast_model Extends Model 
{
    protected $Common_model;
    function __construct(){
        parent::__construct();
        $this->Common_model = new Common_model();
    }
    function get_pengiriman_surat_file_list(){
        $this->builder = $this->db->table('wa_upload_data');
        $this->builder->where('status', 'NEED APPROVAL');
        $select = array(
            'id',
            'file_name',
            'template_name',
            'total_data',
            'failed_data',
            'success_data',
            'notes',
            'status',
            'created_by',
            'created_time',
            'approval_pic',
            'approval_time',
            'approval_notes'
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
        $this->builder = $this->db->table('wa_upload_data_detail');
        $this->builder->select('*');
        $this->builder->where('upload_id', $id);
        $result = $this->builder->get()->getResultArray();
        return $result;
    }

    function approve_upload_file($data){
        
        foreach ($data['id'] as $key => $value) {
            $this->builder = $this->db->table('wa_upload_data_detail');
            $this->builder->where('id', $value);
            $this->builder->set('status_description', 'APPROVED');
            $result = $this->builder->update();

            $data_detail = $this->Common_model->get_record_values('*', 'wa_upload_data_detail', 'id="'.$value.'"');
            
            $content=array(
                'templateName' =>$data_detail['template_id'],
                'templateData' => array(
                    'body'=> array(
                        "placeholders" => explode("|",$data_detail['json_data'])
                    )
                ),
                'language' => 'id'
            );
            $data2 = array( 
                'messages'  => array(
                    array(
                        "from" => $this->Common_model->get_record_value('phone', 'wa_devices', 'id="Ecentrix_demo"'),
                        "to" => $data_detail['phone_number'],
                        "messageId" => uuid(),
                        "content" => $content
                    )
                )       
            );
            $data_template = json_encode($data2);  
            $count_blast=$this->Common_model->get_record_value('count(*) total','wa_outbound a','a.template_name="'.$data_detail['template_id'].'" and a.ref_id="'.trim($data_detail['account_number']).'" and date(created_time)=curdate() ');

            $data_insert = array(
                'id' => uuid(), 
                'ref_id' => $data_detail['account_number'],
                'to_number' => $data_detail['phone_number'], 
                'template_name' => $data_detail['template_id'], 
                'template_data' => $data_template, 
                'json_data' => json_encode($_REQUEST), 
                'message' => addslashes($data_detail['message']), 
                'created_by' => session()->get('USER_ID'), 
                'created_time' => date('Y-m-d H:i:s'), 
                'is_approved' => 'APPROVED', 
                'status' => 'NEW', 
                'tenant' => 'ecentrix',
                'source' => 'upload'
            );

            if ($count_blast < 1) {
                $this->builder = $this->db->table('wa_outbound');
                $return = $this->builder->insert($data_insert);
            }
        }

       
        $this->builder = $this->db->table('wa_upload_data');
        $this->builder->where('id', $data['upload_id']);
        $this->builder->set('status', 'APPROVED');
        $this->builder->set('approval_pic', session()->get('USER_ID'));
        $this->builder->set('approval_time', date('Y-m-d H:i:s'));
        $this->builder->set('approval_notes', 'APPROVED');

        $result = $this->builder->update();
        
        return $result;
    }

    function reject_upload_file($data){
        if (!empty($data['id'])) {
            foreach ($data['id'] as $key => $value) {
                $this->builder = $this->db->table('wa_upload_data_detail');
                $this->builder->where('id', $value);
                $this->builder->set('status_description', 'REJECT');
                $result = $this->builder->update();
            }
        } 
        $this->builder = $this->db->table('wa_upload_data');
        $this->builder->where('id', $data['upload_id']);
        $this->builder->set('status', 'REJECT');
        $this->builder->set('approval_pic', session()->get('USER_ID'));
        $this->builder->set('approval_time', date('Y-m-d H:i:s'));
        $this->builder->set('approval_notes', 'REJECT');

        $result = $this->builder->update();
        
        return $result;
    }
}