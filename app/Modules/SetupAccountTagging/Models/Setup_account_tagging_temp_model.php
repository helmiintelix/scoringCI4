<?php
namespace App\Modules\SetupAccountTagging\models;
use App\Models\Common_model;
use CodeIgniter\Model;

Class Setup_account_tagging_temp_model Extends Model 
{
    protected $Common_model;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new Common_model;
    }
    function get_collection_list_temp(){
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
            'a.CM_CUSTOMER_NMBR', 'a.CR_NAME_1', 'a.CM_CARD_NMBR', 'b.ACCOUNT_TAGGING_TMP', 'a.CM_STATUS_DESC', 'a.CM_TYPE', 'a.CM_DOMICILE_BRANCH', 'a.CM_CYCLE', 'DATE_FORMAT(a.CM_DTE_PYMT_DUE,"%d-%M-%Y")CM_DTE_PYMT_DUE', 'a.CM_BUCKET', 'a.CM_TOT_BALANCE', 'a.CM_BLOCK_CODE', 'DATE_FORMAT(a.CM_DTE_BLOCK_CODE,"%d-%M-%Y")CM_DTE_BLOCK_CODE', 'a.CLASS', 'IF(b.flag_tmp = "1", "<span class=\"badge bg-success\">APPROVED</span>", IF(b.flag_tmp = "2", "<span class=\"badge bg-danger\">REJECTED</span>", IF(b.flag_tmp = "0", "<span class=\"badge bg-warning\">WAITING APPROVAL</span>", ""))) AS flag_tmp'
            
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cms_account_last_status b', 'b.account_no = a.CM_CARD_NMBR');
        $this->builder->where('flag_tmp', '0');
        $this->builder->where("(b.ACCOUNT_TAGGING_TMP is not null and  b.ACCOUNT_TAGGING_TMP != '') OR (`flag_tmp` = '0' and  `b`.`ACCOUNT_TAGGING_TMP` = '')");
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
    }
    function set_account_tagging_approve($account_no){
        $flag_tmp = '1';
		$arrAccount = $account_no;
        $this->builder = $this->db->table('cms_account_last_status');
        foreach ($arrAccount as $no) {
			$new_account_tagging = $this->Common_model->get_record_values("count(*) tot,account_tagging_tmp", "cms_account_last_status", "account_no = '" . $no . "'");
            $parameters_data = array(
				'account_tagging' => $new_account_tagging['account_tagging_tmp'],
				'flag_tmp' 		=> $flag_tmp,
				'account_tagging_approval_time' 	=> date('Y-m-d H:i:s'),
				'account_tagging_approval_by' 	=> session()->get('USER_ID')
			);
            if ($new_account_tagging['tot'] > 0) {
				$this->builder->where('account_no', $no);
				$return = $this->builder->update($parameters_data);
			} else {
				$return = $this->builder->insert($parameters_data);
			}
			$data = array(
				'ACCOUNT_TAGGING' 		=> $new_account_tagging['account_tagging_tmp']
			);
            $builder2 = $this->db->table('cpcrd_new');
            $builder2->where('CM_CARD_NMBR', $no);
            $builder2->update($data);
        }

        return $return;
    }
    
    function set_account_tagging_reject($account_no){
        $flag_tmp = '2';
        $parameters_data = array(
			'flag_tmp' 				=> $flag_tmp,
			'account_tagging_approval_time' 	=> date('Y-m-d H:i:s'),
			'account_tagging_approval_by' 	=> session()->get('USER_ID')
		);
        $this->builder = $this->db->table('cms_account_last_status');
        $this->builder->whereIn('account_no', $account_no);
        $return = $this->builder->update($parameters_data);
        return $return;
    }
}