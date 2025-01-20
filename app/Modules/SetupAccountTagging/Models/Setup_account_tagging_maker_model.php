<?php
namespace App\Modules\SetupAccountTagging\models;
use App\Models\Common_model;
use CodeIgniter\Model;

Class Setup_account_tagging_maker_model Extends Model 
{
    protected $Common_model;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new Common_model;
    }
    function get_collection_list(){
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
            'a.CM_CUSTOMER_NMBR', 'a.CR_NAME_1', 'a.CM_CARD_NMBR', 'b.ACCOUNT_TAGGING', 'a.CM_STATUS_DESC', 'a.CM_TYPE', 'a.CM_DOMICILE_BRANCH', 'a.CM_CYCLE', 'DATE_FORMAT(a.CM_DTE_PYMT_DUE,"%d-%M-%Y")CM_DTE_PYMT_DUE', 'a.CM_BUCKET', 'a.CM_TOT_BALANCE', 'a.CM_BLOCK_CODE', 'DATE_FORMAT(a.CM_DTE_BLOCK_CODE,"%d-%M-%Y")CM_DTE_BLOCK_CODE', 'a.CLASS', 'IF(b.flag_tmp = "1", "<span class=\"badge bg-success\">APPROVED</span>", IF(b.flag_tmp = "2", "<span class=\"badge bg-danger\">REJECTED</span>", IF(b.flag_tmp = "0", "<span class=\"badge bg-warning\">WAITING APPROVAL</span>", ""))) AS flag_tmp'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cms_account_last_status b', 'b.account_no = a.CM_CARD_NMBR');
        $this->builder->orderBy('CR_NAME_1', 'ASC');
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
    function set_account_tagging($data){
        // print_r($data);
        $flag_tmp = $data['mode'] == 'edit' ? '1' : '0';
        $arrAccount = $data['mode'] == 'edit' ? $data['account'] : explode(",", $data['account']);
        $account_tagging = $data['account_tagging'];
        // $this->db->transBegin();
        $this->builder = $this->db->table('cms_account_last_status');
        foreach ($arrAccount as $no) {
			$count = $this->Common_model->get_record_value("count(*)", "cms_account_last_status", "account_no = '" . $no . "'");
            $parameters_data = array(
				'account_no' 			=> $no,
				'flag_tmp' 				=> $flag_tmp,
				'account_tagging_tmp' 	=> $account_tagging,
				'account_tagging_time' 	=> date('Y-m-d H:i:s'),
				'account_tagging_by' 	=> session()->get('USER_ID')
			);
            if ($count > 0) {
                $this->builder->where('account_no', $no);
                $return = $this->builder->update($parameters_data);
            } else {
                $return = $this->builder->insert($parameters_data);
            }
            if ($return) {
                $data = array(
                    'ACCOUNT_TAGGING' => $account_tagging
                );
            }
        }
        // $query = $this->db->getLastQuery();
        // print_r($query);
        // if ($this->db->transStatus() === FALSE) {
        //     $this->db->transRollback();

        //     return false;
        // }
        return $return;
    }
    function save_holiday_edit($data){
        $this->builder = $this->db->table('cc_holiday_temp');
        $return = $this->builder->insert($data);
        return $return;
    }
    function delete_holiday($id){
        $this->builder = $this->db->table('cc_holiday');
        $return = $this->builder->where('id', $id)->delete();
        return $return;
    }
}