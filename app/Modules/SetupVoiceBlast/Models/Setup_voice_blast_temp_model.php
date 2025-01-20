<?php
namespace App\Modules\SetupVoiceBlast\models;
use CodeIgniter\Model;

Class Setup_voice_blast_temp_model Extends Model 
{
    function get_campaign_list_tmp(){
        $this->builder = $this->db->table('voice_blast_campaign_tmp a');
        $select = array(
            'a.action',
            'a.id',
            'a.campaign_description',
            'GROUP_CONCAT(b.name) AS days',
            'a.start_time',
            'a.end_time',
            'a.max_retry',
            'a.call_timeout',
            'a.interval_next_dial_not_connect',
            'a.priority',
            'a.call_script',
            'a.interval_next_dial_not_connect',
            'IF(a.is_active = "1", "ENABLE", "DISABLE") AS is_active',
            'a.created_by',
            'a.created_time'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->where('flag', '1');
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
    }
    public function approve_campaign_delete($id)
    {
        $this->builder = $this->db->table('voice_blast_campaign');        
        $this->builder->where('id', $id);
        $this->builder->set('is_active', '2');
        $this->builder->set('flag', '2');
        $this->builder->set('created_by', session()->get('USER_ID'));
        $this->builder->set('created_time', date('Y-m-d H:i:s'));
        $return = $this->builder->update();
        return $return;
    }
    
    public function approve_campaign($id)
    {
        $this->builder = $this->db->table('voice_blast_campaign_tmp');
        $this->builder->where('id', $id);
        $this->builder->set('flag', '2');
        $this->builder->set('created_by', session()->get('USER_ID'));
        $this->builder->set('created_time', date('Y-m-d H:i:s'));
        $return = $this->builder->update();

        $this->builder->select("*");
        $this->builder->where('id', $id);
        $updated_data = $this->builder->get()->getRow();

        // Replace data tersebut ke voice_blast_campaign
        if ($updated_data) {
            // Mengubah objek stdClass menjadi array
            $updated_data_array = (array) $updated_data;
            // Replace data ke voice_blast_campaign
            $this->builder = $this->db->table('voice_blast_campaign');
            $this->builder->replace($updated_data_array);
        }
        return $return;
    }
    public function reject_campaign($id)
    {
        $this->builder = $this->db->table('voice_blast_campaign_tmp');
        $this->builder->where('id', $id);
        $this->builder->set('flag', '0');
        $this->builder->set('created_by', session()->get('USER_ID'));
        $this->builder->set('created_time', date('Y-m-d H:i:s'));
        $return = $this->builder->update();

        $this->builder->select("*");
        $this->builder->where('id', $id);
        $updated_data = $this->builder->get()->getRow();

        // Replace data tersebut ke voice_blast_campaign
        if ($updated_data) {
            // Mengubah objek stdClass menjadi array
            $updated_data_array = (array) $updated_data;
            // Replace data ke voice_blast_campaign
            $this->builder = $this->db->table('voice_blast_campaign');
            $this->builder->replace($updated_data_array);
        }
        return $return;
    }
    // function reject_campaign($id){
    //     $this->builder = $this->db->table('voice_blast_campaign');
    //     $this->builder->where('id', $id);
    //     $this->builder->set('flag', '0');
    //     $this->builder->set('created_by', session()->get('USER_ID'));
    //     $this->builder->set('created_time', date('Y-m-d H:i:s'));
    //     $return = $this->builder->update();
    //     if ($return) {
    //         $this->builder = $this->db->table('voice_blast_campaign_tmp');
    //         $this->builder->where('id', $id);
    //         $this->builder->delete();
    //     }
    //     return $return;
    // }
    
}