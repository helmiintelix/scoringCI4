<?php
namespace App\Modules\VoiceBlastReport\models;
use CodeIgniter\Model;

Class Voice_blast_report_model Extends Model 
{
    function get_report_voice_blast_list($data){
        $this->builder = $this->db->table('call_history a');
        $select = array(
			'date_format(a.call_time,"%d-%b-%Y %T") call_time',
			'ref_number',
			'CR_NAME_1',
			'call_status',
			'time_to_sec(timediff(TIME(a.end_time),TIME(a.call_time))) as time',
			'phone_no',
			'phone_type'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('call_blaster b', 'b.id = a.caller_id');
        $this->builder->join('cpcrd_new c', 'ref_number = CM_CARD_NMBR');
		$this->builder->join('cms_predictive_phone d', 'c.cm_card_nmbr = d.cm_card_nmbr AND a.phone_no = content', 'left');
        if ($data['tgl_from']) {
            $this->builder->where('date(a.call_time) >=', $data['tgl_from']);
        }
        if ($data['tgl_to']) {
            $this->builder->where('date(a.call_time) <=', $data['tgl_to']);
        }
        $this->builder->orderBy('a.call_time', 'DESC');
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