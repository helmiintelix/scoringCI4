<?php
namespace App\Modules\RecordingVoice\models;
use Config\Database;
use CodeIgniter\Model;

Class Recording_voice_model Extends Model 
{
	protected $Common_model,$cti;
    public function __construct()
    {
        parent::__construct();
		$this->cti = Database::connect('cti');
    }
	function get_recording_list(){
		$this->builder = $this->cti->table("recording as a");
        $select = array(
            'a.id',
			'"-" `action`',
			'a.customer_data',
			'a.extension_id',
			'a.phone_number `number`',
			'TIME(a.start_time) start_time',
			'TIME(a.end_time) end_time',
			'SEC_TO_TIME(ROUND(a.duration/1000)) duration',
			'a.user_id agent_id',
			'a.context',
			'a.create_time'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->orderBy('a.create_time', 'DESC');
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
		
        if ($rResult->getNumRows() > 0) {
			foreach ($return as &$row) {
                $row['action'] = '<a href="#" onClick="get_path_recording(\''.$row['id'].'\',\''.$row['context'].'\')" >play</a> | <a href="#" onClick="get_path_download(\''.$row['id'].'\', \''.$row['context'].'\')" >download</a>';
            }
            unset($row);
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
}