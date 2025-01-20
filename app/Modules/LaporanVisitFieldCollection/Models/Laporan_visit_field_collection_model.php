<?php
namespace App\Modules\LaporanVisitFieldCollection\models;
use App\Models\Common_model;
use CodeIgniter\Model;

Class Laporan_visit_field_collection_model Extends Model 
{
    protected $Common_model;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new Common_model;
    }
    function get_report_visit_field_list($data){
        $this->builder = $this->db->table('cms_contact_history a');
        $select = array(
            'a.id','b.name user_id',
			'a.notes',
			'ifnull(c.description,a.action_code) action_code',
			'contact_code',
			'(select category_name from cms_lov_registration where id = lov1) as lov1',
			'(select category_name from cms_lov_registration where id = lov2) as lov2',
			'(select category_name from cms_lov_registration where id = lov3) as lov3',
			'(select category_name from cms_lov_registration where id = lov4) as lov4',
			'phone_type',
			'COALESCE(a.phone_no, (SELECT bb.borrower_phone from cms_temp_phone bb WHERE bb.source="mobcoll" AND bb.contract_number=a.account_no AND bb.created_by=a.created_by)) AS phone',
			'call_status',
			'ptp_date',
			'ptp_amount',
			'ptp_status',
			'a.input_source',
			'a.created_time',
			'a.phone_no',
			'ifnull(d.description,a.place_code) place_code',
            'ifnull(d.description,a.next_action) next_action',
            'ifnull(e.description,a.reason) reason',
            'CR_NAME_1',
            'account_no'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cc_user b',"a.user_id=b.id");
	//	$this->builder->join('cpcrd_new_history e',"a.account_no=e.CM_CARD_NMBR");
		$this->builder->join('cms_reference c',"a.action_code=c.value and c.reference='ACTION_CODE'","left");
		$this->builder->join('cms_reference d',"a.next_action=d.value and d.reference='NEXT_ACTION'","left");
		$this->builder->join('cms_reference e',"a.reason=e.value and e.reference='REASON_CODE'","left");
		$this->builder->join('cms_reference f',"a.reason=f.value and f.reference='PLACE_CODE'","left");
		$this->builder->join('cpcrd_new g', 'a.account_no = g.CM_CARD_NMBR');
		$this->builder->where('input_source', 'mobcoll');
        switch(session()->get('LEVEL_GROUP')){
			case "AGENT" :
			case "ARCO" :
				$this->builder->where('user_id',session()->get('USER_ID'));	
				break;
				
			case "SUPERVISOR" :
			$agent_list = $this->Common_model->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "supervisor = '".session()->get('USER_ID')."'");		
			$arr_agent = explode("|",$agent_list);
			$arr_agent[] = session()->get('USER_ID');
			$arr_agent[] = $this->Common_model->get_record_value(" team_leader", "cms_team", "supervisor = '".session()->get('USER_ID')."'");		
			$this->builder->whereIn("user_id",$arr_agent);
			break;
			case "TEAM_LEADER" :
			/*$agent_list = $this->Common_model->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "team_leader = '".session()->get('USER_ID')."'");		
			$arr_agent = explode("|",$agent_list);
			$arr_agent[] = session()->get('USER_ID');
			$this->builder->where_in("user_id",$arr_agent);*/
				break;
		}
        if ($data['tgl_from']) {
            $this->builder->where('date(a.created_time) >=', $data['tgl_from']);
        }
        if ($data['tgl_to']) {
            $this->builder->where('date(a.created_time) <=', $data['tgl_to']);
        }
        if ($data['user']) {
			$this->builder->where('b.id', $data['user']);
		}
        if ($data['bucket']) {
			$this->builder->where('g.cm_bucket', $data['bucket']);
		}
        if ($data['product']) {
			if($data['product']!="ALL"){
				$this->builder->where('g.product_id', $data['product']);
			}
		}
        if ($data['no_pinjaman']) {
			$this->builder->where('account_no', $data['no_pinjaman']);
		}
        $this->builder->orderBy('date(a.created_time)', 'DESC');
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
    
}