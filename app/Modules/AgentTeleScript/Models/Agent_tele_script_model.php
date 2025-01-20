<?php
namespace App\Modules\AgentTeleScript\models;
use CodeIgniter\Model;

Class Agent_tele_script_model Extends Model 
{
    function get_agent_script_list(){
        $this->builder = $this->db->table('acs_agent_script');
        $DBDRIVER = $this->db->DBDriver;

        if ($DBDRIVER === 'SQLSRV') {
            // SQL Server
            $created_time = " FORMAT(created_time, 'dd-MM-yyyy HH:mm:ss')";
        } elseif ($DBDRIVER === 'Postgre') {
            // PostgreSQL
            $created_time = "  TO_CHAR(created_time, 'DD-MM-YYYY HH24:MI:SS') ";
        } else {
            // MySQL
            $created_time = 'DATE_FORMAT(created_time, "%d-%m-%Y %H:%i:%s")';
        }
       
       
        $select = array(
            'id, 
            subject, 
            script, 
            created_by, 
            '.$created_time.' created_time'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
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
    function insert_agent_script($data){
        $this->builder = $this->db->table('acs_agent_script');
        $return = $this->builder->insert($data);
        return $return;
    }
    function update_agent_script($data){
        $this->builder = $this->db->table('acs_agent_script');
        $return = $this->builder->where('id', $data['id'])->update($data);
        return $return;
    }
}