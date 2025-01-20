<?php
namespace App\Modules\UploadPengirimanSurat\models;
use CodeIgniter\Model;
use App\Models\Common_model;

Class Upload_pengiriman_surat_model Extends Model 
{
    protected $Common_model;
    function __construct(){
        parent::__construct();
        $this->Common_model = new Common_model();
    }
    function get_pengiriman_surat_file_list(){
        $this->builder = $this->db->table('cms_upload_file');
        $select = array(
            'id',
            'tipe_upload',
            'file_name',
            'upload_time',
            'upload_by',
            'upload_status'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->where("tipe_upload", "pengiriman_surat");
        $this->builder->orderBy('upload_time', 'DESC');
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
    function save_pengiriman_surat_upload_file($data){
        $this->builder = $this->db->table('cms_upload_file');
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_pengiriman_surat_from_file($data){
        $this->builder = $this->db->table('tmp_upload_pengiriman_surat');
        $return = $this->builder->insertBatch($data);
        return $return;
    }
    function show_activity_by_file($id){
        $this->builder = $this->db->table('tmp_upload_pengiriman_surat');
        $this->builder->select('*');
        $this->builder->where('file_upload_id', $id);
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