<?php
namespace App\Modules\AgentMonitoring\models;
use CodeIgniter\Model;
use App\Models\Common_model;

Class Agent_monitoring_model Extends Model 
{
    protected $Common_model;
    function __construct(){
        parent::__construct();
        $this->Common_model = new Common_model();
    }
    function get_activity_file_list(){
        $this->builder = $this->db->table('cms_agency_upload_activity');
        $select = array(
            'id',
            'agency_id',
            'file_name',
            'upload_time',
            'upload_by',
            'upload_status');
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
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
    function save_agency_upload_file($data){
        $this->builder = $this->db->table('cms_agency_upload_activity');
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_activity_from_file($data){
        $this->builder = $this->db->table('tmp_upload_activity_collector');
        $return = $this->builder->insertBatch($data);
        return $return;
    }
    function show_activity_by_file($id){
        $this->builder = $this->db->table('tmp_upload_activity_collector');
        $this->builder->select('*');
        $this->builder->where('file_upload_id', $id);
        $result = $this->builder->get()->getResultArray();
        return $result;
    }
    function upload_activity_by_file($id){
		$upload_status = $this->Common_model->get_record_value("upload_status", "cms_agency_upload_activity", "id='$id'");
        if($upload_status != "NOT LOADED"){
			echo "Already loaded";
			return false;
		}

        //KODE DI BAWAH AKU COMMENT DULU KARENA DATA YANG DI SELECT KOSONG
        // $this->builder = $this->db->table('tmp_upload_activity_collector a');
        // $this->builder->select(
        //     'UUID() as id,
        //     card_number as account_no,
        //     CM_CUSTOMER_NMBR as customer_no,
        //     collector_name as user_id,
        //     '.$this->db->escape('VISIT').' as input_source,
        //     visit_place as place_code,
        //     contact_with as contact_code,
        //     collection_result as action_code,
        //     remarks as notes,
        //     ptp_date as ptp_date,
        //     ptp_amount as ptp_amount,
        //     collector_name as created_by,
        //     visit_time as created_time'
        // );
        // $this->builder->join('cpcrd_new b', 'a.card_number = b.CM_CARD_NMBR');
        // $this->builder->where('file_upload_id', $id);
        // $data = $this->builder->get()->getResultArray();

        // $this->builder = $this->db->table('cms_contact_history');
        // $this->builder->insertBatch($data);

        $this->builder = $this->db->table('cms_agency_upload_activity');
        $this->builder->where('id', $id);
        $this->builder->set('upload_status', 'UPLOADED');
        $result = $this->builder->update();

        return $result;
        
    }
}