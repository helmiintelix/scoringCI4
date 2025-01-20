<?php
namespace App\Modules\PengirimanSurat\models;
use CodeIgniter\Model;

Class Pengiriman_surat_model Extends Model 
{
    function get_record_value($fieldName, $tableName, $criteria)
	{
	
		$builder = $this->db->table($tableName);
		$builder->select($fieldName)->where($criteria);
		
		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			$data = $query->getRowArray();
			
			return array_pop($data);
		}

		return null;
	}
    function get_report_pengiriman_surat_list($data){
        $this->builder = $this->db->table('cms_letter_history a');
        $select = array(
            'a.id',
			'a.no_cif',
			'a.outstanding',
			'no_sp',
			'tgl_kirim',
			'nama_kurir',
			'no_resi',
			'nama_penerima',
			'tgl_terima',
			'remarks',
			'date_format(a.created_time,"%d-%b-%Y : %T")created_time',
			'CR_NAME_1',
			'concat("\'",a.account_no)Card_number',
			'user_id',
			'jenis_sp',
			// 'branch_name',
			'CM_DOMICILE_BRANCH'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cpcrd_new c', 'account_no = CM_CARD_NMBR');
        switch (session()->get('GROUP_LEVEL')) {
			case "AGENT":
			case "ARCO":
				$this->builder->where('user_id', session()->get('USER_ID'));
				break;

			case "SUPERVISOR":
				$agent_list = $this->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "supervisor = '" . session()->get('USER_ID') . "'");
				$arr_agent = explode("|", $agent_list);
				$arr_agent[] = session()->get('USER_ID');
				$arr_agent[] = $this->get_record_value("team_leader", "cms_team", "supervisor = '" . session()->get('USER_ID') . "'");
				$this->builder->whereIn("user_id", $arr_agent);

				break;


			case "TEAM_LEADER":
				$agent_list = $this->get_record_value(" GROUP_CONCAT(agent_list separator '|')", "cms_team", "team_leader = '" . session()->get('USER_ID') . "'");
				$arr_agent = explode("|", $agent_list);
				$arr_agent[] = session()->get('USER_ID');
				$this->builder->whereIn("user_id", $arr_agent);

				break;
		}
        if ($data['tgl_from']) {
            $this->builder->where('date(a.created_time) >=', $data['tgl_from']);
        }
        if ($data['tgl_to']) {
            $this->builder->where('date(a.created_time) <=', $data['tgl_to']);
        }
        $this->builder->orderBy('created_time', 'DESC');
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