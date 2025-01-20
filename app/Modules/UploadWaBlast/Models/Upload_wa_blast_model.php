<?php
namespace App\Modules\UploadWaBlast\models;
use CodeIgniter\Model;
use App\Models\Common_model;

Class Upload_wa_blast_model Extends Model 
{
    protected $Common_model;
    function __construct(){
        parent::__construct();
        $this->Common_model = new Common_model();
    }
    function get_pengiriman_surat_file_list(){
        $this->builder = $this->db->table('wa_upload_data');
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
    function upload_activity_by_file($id){
		$upload_status = $this->Common_model->get_record_value("upload_status", "cms_upload_file", "id='$id'");
        if($upload_status != "NOT LOADED"){
			echo "Already loaded";
			return false;
		}

        $this->builder = $this->db->table('cms_upload_file');
        $this->builder->where('id', $id);
        $this->builder->set('upload_status', 'UPLOADED');
        $result = $this->builder->update();

        return $result;
        
    }
}