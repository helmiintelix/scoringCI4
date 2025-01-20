<?php
namespace App\Modules\EscalationMonitoringDetail\models;
use App\Models\Common_model;
use CodeIgniter\Model;


Class Escalation_monitoring_detail_model Extends Model 
{
    protected $Common_model;
    function __construct(){
        parent::__construct();
        $this->Common_model = new Common_model();
    }
    function getEscalationMonitoringDetail($data){
        $this->builder = $this->db->table('cms_contact_history a');
        $select = array(
			'f.team_leader spv_id, "" as spv_name, c.`status` spcl_status, a.account_no contract_number, d.CR_NAME_1 customer_name, 
            a.bucket, null aging_eom, a.class_id, a.lov1 contact_status, a.lov3 call_result, a.notes notepad1, b.name first_name, a.created_time call_time, 
            datediff(a.created_time, c.updated_time) as duration_escalation'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cc_user b', 'a.created_by = b.id');
		$this->builder->join('acs_coordinator_task c', 'a.id=c.collection_history_id');
		$this->builder->join('cpcrd_new d', 'a.account_no=d.CM_CARD_NMBR');
		$this->builder->join('acs_agent_assignment e', 'e.user_id=b.id');
		$this->builder->join('cms_team f', 'f.team_id=e.team');
        $this->builder->where('a.escalation', 'TL');
        if(isset($data['start']) && $data['start'] != ''){
			$this->builder->where('DATE(a.created_time) >= "'.$data['start'].'"');
		}
		
		if(isset($data['end']) && $data['end'] != ''){
			$this->builder->where('DATE(a.created_time) <= "'.$data['end'].'"');
		}
		
		if(isset($data['status']) && $data['status'] != 'All'){
			$this->builder->where('c.status', $data['status']);
		}
		
		if(isset($data['team_leader_id']) && $data['team_leader_id'] != ''){
			$this->builder->where('f.team_leader', $data['team_leader_id']);
		}
        $this->builder->orderBy('a.created_time', 'asc');
        $rResult = $this->builder->get();
		$output = array();
        foreach ($rResult->getResultArray() as $aRow) {
			$aRow['spv_name'] = $this->Common_model->get_record_value('name', 'cc_user', 'id="'.$aRow['spv_id'].'"');
			$aRow['class_name'] = $this->Common_model->get_record_value('classification_name', 'cms_classification', 'classification_id="'.$aRow['class_id'].'"');
            $output[] = $aRow;
        }

        return json_encode($output);
    }
    
}