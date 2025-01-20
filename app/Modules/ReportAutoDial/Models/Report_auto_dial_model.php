<?php
namespace App\Modules\ReportAutoDial\models;

use Config\Database;
use CodeIgniter\Model;

Class Report_auto_dial_model Extends Model 
{
    protected $cti;
    public function __construct()
    {
        parent::__construct();

        // Menginisialisasi koneksi database 'cti'
        $this->cti = Database::connect('cti');
    }
    function get_report_auto_dial(){
        $this->builder = $this->cti->table('session_log a');
        $select = array(
            "a.*",
			"DATE(a.start_time) call_date",
			"TIME(a.start_time) time_start",
			"TIME(a.end_time) time_end",
			"SEC_TO_TIME(ROUND(a.duration/1000)) as duration",
			"b.description"
        );
        $this->builder->join('status_reference b', 'a.last_status = b.code AND b.type = "CALL"');
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->where('a.type', 'INBOUND');
        $this->builder->orderBy('a.start_time', 'DESC');
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