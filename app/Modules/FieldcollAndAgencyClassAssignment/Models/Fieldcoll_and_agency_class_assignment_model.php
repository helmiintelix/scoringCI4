<?php
namespace App\Modules\FieldcollAndAgencyClassAssignment\models;
use CodeIgniter\Model;
use App\Models\Common_model;

Class Fieldcoll_and_agency_class_assignment_model Extends Model 
{
    protected $Common_model;
    function __construct(){
        parent::__construct();
        $this->Common_model = new Common_model();
    }
    function get_classification_list_assignment(){
        $this->builder = $this->db->table('cms_classification');
        $select = array(
            'update_detail', 
            'description', 
            'classification_id', 
            'class_category', 
            'allocation_type', 
            'class_priority', 
            'assigned_agent', 
            'classification_name', 
            'classification_detail', 
            'updated_by', 
            'updated_time', 
            'account_handling'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->like('account_handling', 'Fieldcoll', 'both');
        $this->builder->orderBy('classification_name', 'ASC');
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
    function get_classification_test_current($classification_id){
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
            'a.CM_CUSTOMER_NMBR,
            a.AGENT_ID,
            a.CLASS,
            a.BILL_BAL,
            a.CM_BUCKET,
            a.CM_CARD_NMBR,
            a.CM_DTE_PYMT_DUE,
            a.DPD,
            a.CM_CARD_EXPIR_DTE,
            a.CM_DTE_LST_PYMT,
            a.CM_TOT_BALANCE,
            a.CM_CYCLE,
            a.AGENT_ID,
            a.CM_CRLIMIT,
            a.CM_TENOR,
            a.CM_INSTALLMENT_AMOUNT,
            a.CM_OS_PRINCIPLE,
            a.CM_COLLECTIBILITY,
            a.CM_CHGOFF_STATUS_FLAG,
            a.CM_INSTALLMENT_NO,
            a.CR_OCCUPATION,
            a.CM_DOMICILE_BRANCH,
            b.ptp_date,b.CLASS_RECALL'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cms_account_last_status b', 'a.CM_CARD_NMBR=b.account_no');
		
        $data_class = $this->Common_model->get_record_values("classification_name,classification_detail,classification_id,classification_json", "cms_classification", "classification_id='$classification_id'");
        
        $sql_detail = $data_class["classification_detail"];
		$sql_detail = str_replace("\\", '', $sql_detail);
		$sql_detail = str_replace("''", "'", $sql_detail);
		$sql_detail = str_ireplace("CLASS", "a.CLASS", $sql_detail);
		$data_class["classification_detail"] = $sql_detail; 

        $this->builder->where($data_class["classification_detail"]);
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
    function class_agent_assignment_form_save_waiting_approval($data){
        $data2 = array(
			'classification_id' => $data['class_id'],
			'distribution_method' => $data['dm'],
			'assigned_agent' => $data['assigned_agent'],
			'assigned_time' => $data['assigned_time'],
			'assignment_weight' => $data['assignment_weight'],
			'assigned_by' =>$data['assigned_by'],
			'updated_by' => $data['created_by'],
			'updated_time' => $data['created_time']
		);
        $this->builder = $this->db->table('cms_classification');
        $this->builder->where('classification_id', $data['class_id']);
        $return = $this->builder->update($data2);
        if ($return) {
            // $subQuery = $this->db->table('cpcrd_new')->select('CM_CARD_NMBR')->where('CLASS', $data['class_id']);
            // $this->builder = $this->db->table('cms_assignment');
            // $this->builder->whereIn('no_rekening', $subQuery);
            // $this->builder->delete();

            // $this->builder = $this->db->table('tmp_agent_account_assignment');
            // $this->builder->truncate();

            // $this->builder = $this->db->table('cpcrd_new');
            // $this->builder->select(' CM_CARD_NMBR,CLASS');
            // $this->builder->where('CLASS', $data['class_id']);
            // $this->builder->orderBy('CM_AMOUNT_DUE');
            // $results = $this->builder->get()->getResultArray();

            // $data2 = [];
            // foreach ($results as $row) {
            //     $data2[] = [
            //         'account_no' => $row['CM_CARD_NMBR'],
            //         'class_id' => $row['CLASS'],
            //     ];
            // }
            // $this->builder = $this->db->table('tmp_agent_account_assignment');
            // $this->builder->insertBatch($data2);

            $arr_agent = explode('|', $data['assigned_agent'] ?? '');
            $count = count($arr_agent);
            
            for ($i = 0; $i < $count; $i++) {
                $agent = trim($arr_agent[$i]); // Menghapus whitespace dari awal dan akhir string
                $where = "id % {$count} = {$i}";
            
                $this->builder = $this->db->table('tmp_agent_account_assignment');
                $this->builder->set('user_id', $agent);
                $this->builder->where($where, null, false); // Menonaktifkan escapement otomatis
                $this->builder->update();
            }
            

            $sql = "update tmp_agent_account_assignment a,cpcrd_new b set AGENT_ID = user_id where CM_CARD_NMBR = account_no";
			$this->db->query($sql);
        }
        return $return;
    }
    function save_area_tagih_edit($data){
        $this->builder = $this->db->table('cms_area_tagih');
        $this->builder->where('id', $data['id']);
        $data2 = $this->builder->get()->getResultArray();
        $query = $this->db->table('cms_area_tagih_temp')->insertBatch($data2);
        if ($query) {
            $builder = $this->db->table('cms_area_tagih_temp');
            $builder->where('id', $data['id']);
            $builder->set('area_tagih_id', $data["area_tagih_id"]);
            $builder->set('area_tagih_name', $data["area_tagih_name"]);
            $builder->set('created_by', $data["created_by"]);
            $builder->set('created_time', $data["created_time"]);
            $builder->set('is_active', $data['is_active']);
            $builder->set('flag', $data['flag']);
            $return = $builder->update();
        }
        return $return;
    }
}