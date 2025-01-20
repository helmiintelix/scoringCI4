<?php
namespace App\Modules\ReportWa\models;
use CodeIgniter\Model;
use App\Models\Common_model;

Class Report_wa_model Extends Model 
{
    protected $Common_model;
    function __construct(){
        parent::__construct();
        $this->Common_model = new Common_model();
    }

    function get_data_wa_list($id=null){
        $data = $this->Common_model->get_record_values('*', 'wa_master_report', 'id="'.$id.'"');
        $this->builder = $this->db->table($data['table_name']);
        $this->builder->select( str_replace(' , ', ' ', implode(', ', json_decode($data['select_condition']))), false);
        
        if (!empty($data['join_condition'])) {
            foreach (json_decode($data['join_condition'],true) as $key => $value) {
                $this->builder->join($value['table'], $value['on'], $value['type']);
            }
        }

        if (!empty($data['where_condition'])) {
		  $this->builder->where($data['where_condition']);  
        }

        
        if (!empty($data['order_condition'])) {
            foreach (json_decode($data['order_condition'],true) as $key => $value) {
                $this->builder->orderBy($value['order'], $value['type']);
            }
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
        // return $return;
    }

    function insert_report()
    {
        $this->builder = $this->db->table('wa_master_report');
     
        //------------select condition----------
        $s = array(
            'a.ticket_id',
            'a.ref_id account_number',
            'a.group_context group_collection',
            'a.financier_id',
            'a.receivedAt',
            'a.fromNumber',
            'a.pickup_by',
            'a.pickup_time'
        );
        
        //------------------------------------
        //------------join condition----------
        $i=array(
            'table'=>'cms_account_last_status b',
            'on'=>'b.account_no = a.CM_CUSTOMER_NMBR',
            'type'=>'left'
        );
        $j=[];
        array_push($j, $i);

        //------------------------------------
        //------------other condition----------
        $d=array(
            'order'=>'a.receivedAt',
            'type'=>'desc'
        );
        $e=[];
        array_push($e, $d);
        //------------------------------------

        $data_insert=array(
            'id' => 'report_wa_inbound',
            'report_name' => 'Report Whatsapp Inbound',
            'select_condition' => json_encode($s),

            'where_condition' => null,
            //'where_condition' => 'a.direction="OUTB"',
            
            //'join_condition' => json_encode($j),
            'join_condition' => null, //jika null nyalain
            
            'order_condition' => json_encode($e),
            'table_name' => 'wa_inbox a',
            'created_by' => session()->get('USER_ID'), 
            'created_time' => date('Y-m-d H:i:s')
        );

        /*echo "<pre>";
        print_r($data_insert);*/
        $return = $this->builder->insert($data_insert);
        //var_dump($return);
        return $return;
    }

}