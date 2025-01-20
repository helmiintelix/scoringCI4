<?php
namespace App\Modules\AgencyManagement\models;
use CodeIgniter\Model;

Class Agency_management_temp_model Extends Model 
{
    function get_settings_am_list_temp(){
        $DBDRIVER = $this->db->DBDriver;

        if ($DBDRIVER === 'SQLSRV') {
            // SQL Server
            $status_approval = " CASE 
                                    WHEN a.flag_tmp = '1' THEN 'APPROVED'
                                    WHEN a.flag_tmp = '2' THEN 'REJECTED'
                                    ELSE 'WAITING APPROVAL' END ";
        } elseif ($DBDRIVER === 'Postgre') {
            // PostgreSQL
            $status_approval = "  CASE 
                                    WHEN a.flag_tmp = '1' THEN 'APPROVED'
                                    WHEN a.flag_tmp = '2' THEN 'REJECTED'
                                    ELSE 'WAITING APPROVAL'
                                END ";
        } else {
            // MySQL
            $status_approval = "IF(a.flag_tmp = '1', 'APPROVED', 
                            IF(a.flag_tmp = '2', 'REJECTED', 
                                'WAITING APPROVAL'))";
        }
        
        $this->builder = $this->db->table('cms_agency_tmp a');
        
        $select = array(
            'a.agency_id',
            'a.arco_id',
            'a.agency_phone',
            'a.agency_name',
            'a.agency_address',
            'a.agency_pic',
            'a.pic_email',
            'a.spv_email',
            'a.agency_prov',
            'a.agency_city',
            'a.agency_kec',
            'a.agency_kel',
            'a.agency_contract_start',
            'a.agency_contract_end',
            'a.created_time',
            'a.created_by',
            'a.updated_by',
            'a.updated_time',
            $status_approval." AS status_approval", 
            "approval_notes");
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $this->builder->where('a.flag_tmp', '0');
        $this->builder->orderBy('a.created_time', 'desc');
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
    function approve_agency($id){
        $this->builder = $this->db->table('cms_agency_tmp');
        $this->builder->where('agency_id', $id);
        $query = $this->builder->get();
        $data = $query->getRowArray();


        $this->builder = $this->db->table('cms_agency');
        $this->builder->where('agency_id',  $id)->delete();
        $this->builder->insert($data);

        $this->builder = $this->db->table('cms_agency_tmp');
        $this->builder->where('agency_id', $id)->delete();

        $this->builder = $this->db->table('cms_agency');
        $this->builder->where('agency_id', $id);
        $this->builder->set('flag_tmp', '1');
		$this->builder->set('deleted_flag', NULL);
		$this->builder->set('updated_time', date('Y-m-d H:i:s'));
        $return = $this->builder->update();
        return $return;
    }
    function delete_agency($id){
        $this->builder = $this->db->table('cms_agency_tmp');
        $this->builder->where('agency_id', $id);
        $this->builder->set('flag_tmp', '2');
		$this->builder->set('updated_time', date('Y-m-d H:i:s'));
		$return = $this->builder->update();
        return $return;
    }
}