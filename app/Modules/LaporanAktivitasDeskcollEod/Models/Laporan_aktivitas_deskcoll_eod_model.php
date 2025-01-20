<?php
namespace App\Modules\LaporanAktivitasDeskcollEod\models;
use CodeIgniter\Model;

Class Laporan_aktivitas_deskcoll_eod_model Extends Model 
{
    function get_list_report_aktifitas_dc($data){
        $this->builder = $this->db->table('cms_report_activity_deskcoll');
        $select = array(
            "TANGGAL",
			"'' ROW_NUM",
			"DC_ID",
			"DC_NAME",
			"FIRST_LOGIN",
			"LAST_LOGOUT",
			"AVG_TALKTIME",
			"AVG_ACW",
			"LUNCH",
			"BREAK",
			"PRAY",
			"COACHING",
			"OTHER",
			"EFFECTIVE_WORKING",
			"CONTRACT",
			"NOT_CONTRACT",
			"PTP",
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        if ($data['tgl_from']) {
            $this->builder->where('TANGGAL >=', $data['tgl_from']);
        }
        if ($data['tgl_to']) {
            $this->builder->where('TANGGAL <=', $data['tgl_to']);
        }
        if ($data['agent']) {
            $this->builder->where('DC_ID', $data['agent']);
        }
        $this->builder->orderBy('TANGGAL', 'DESC');
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