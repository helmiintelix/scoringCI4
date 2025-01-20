<?php
namespace App\Modules\SetupVoiceBlast\models;
use CodeIgniter\Model;

Class Setup_voice_blast_maker_model Extends Model 
{
    function get_campaign_list(){
        $this->builder = $this->db->table('voice_blast_campaign a');
        $select = array(
            'a.id',
            'a.campaign_description',
            'GROUP_CONCAT(b.name) AS days',
            'a.start_time',
            'a.end_time',
            'a.max_retry',
            'a.voice_type',
            'a.call_timeout',
            'a.interval_next_dial_not_connect',
            'a.priority',
            'a.call_script',
            // 'IF(a.flag = "2", "APPROVED", IF(a.flag = "0", "REJECTED", IF(a.flag = "1", "WAITING APPROVAL"))) flag_tmp',
            'CASE 
                WHEN a.flag = "2" THEN "APPROVED"
                WHEN a.flag = "0" THEN "REJECTED"
                WHEN a.flag = "1" THEN "WAITING APPROVAL"
            ELSE ""
                END AS flag_tmp',
            'IF(a.is_active = "1", "ENABLE", "DISABLE") AS is_active',
            'a.created_by',
            'a.created_time'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->where('is_active !=', '2');
        $this->builder->join('aav_configuration b', 'FIND_IN_SET(b.id, a.days)', 'left'); // Join tabel_b berdasarkan a.days
        $this->builder->groupBy('a.id');
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

    function add_campaign($data){
        $this->builder = $this->db->table('voice_blast_campaign');
        $return = $this->builder->insert($data);
        return $return;
    }
    function save_campaign($data){
        $this->builder = $this->db->table('voice_blast_campaign_tmp');
        $return = $this->builder->insert($data);
        return $return;
    }
    function update_campaign($data){
        $this->builder = $this->db->table('voice_blast_campaign_tmp');
        $return = $this->builder->where('id', $data['id'])->update($data);
        if ($return) {
            $this->builder = $this->db->table('voice_blast_campaign');
            $this->builder->where('id', $data['id'])->update(['flag' => $data['flag']]);
        }
        return $return;
    }

    function delete_campaign($id){
        $this->builder = $this->db->table('voice_blast_campaign');
        $this->builder->select("*");
        $this->builder->where('id', $id);
        $updated_data = $this->builder->get()->getRowArray();
        
        $this->builder = $this->db->table('voice_blast_campaign_tmp');
        $this->builder->replace($updated_data);
        $this->builder->where('id', $id);
        $this->builder->set('flag', '1');
        $this->builder->set('action', 'DELETE');
        $this->builder->set('is_active', '2');
        $this->builder->set('created_by', session()->get('USER_ID'));
        $this->builder->set('created_time', date('Y-m-d H:i:s'));
        $hasil = $this->builder->update();
        
        // Replace data tersebut ke voice_blast_campaign_tmp
        if ($updated_data) {
            $this->builder = $this->db->table('voice_blast_campaign');
            $this->builder->where('id', $id);
            $return = $this->builder->update(['flag' => '1']);
            return $return;
        }
    }
    function get_test_data($id){
        $builder = $this->db->table('voice_blast_campaign');
        $builder->select('voiceblast_detail'); // Mengubah string menjadi array
        $builder->where('id', [$id]);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $row = $query->getRowArray();
            $sql_detail = isset($row['voiceblast_detail']) ? $row['voiceblast_detail'] : null;
        } else {
            $sql_detail = null;
        }

        // print_r($sql_detail);
        // exit();


        $this->builder = $this->db->table('cpcrd_new a');
        $select = array('*,CONVERT(LAMA_HARI_NUNGGAK,char) AS LM_NUNGGAK');
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->join('cpcus b', 'a.CM_CUSTOMER_NMBR = b.CR_ACCT_NBR', 'left');
        $this->builder->where("a.CM_CUSTOMER_NMBR <> ''");
        if (!empty($sql_detail)) {
            $this->builder->where($sql_detail);
        }
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
    
}