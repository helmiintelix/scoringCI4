<?php
namespace App\Modules\ReportPembayaranDetail\models;
use CodeIgniter\Model;

Class Report_pmbayaran_detail_model Extends Model 
{
    function get_data_pembayaran_detail(){
        $this->builder = $this->db->table('cms_payment_history a');

        $this->builder->select([
            '"" AS id', 
            'b.CM_PRODUCT_TYPE',
            'f.cm_card_nmbr',
            'b.cr_name_1 AS nama_customer',
            'FORMAT(CM_AMOUNT_DUE,0) AS CM_AMOUNT_DUE',
            'f.bucket',
            'FORMAT(f.min_payment,0) AS min_payment',
            "' ' AS assignment_date",
            'e.name',
            "' ' AS collector_name",
            "' ' AS collector_id",
            'a.posting_date',
            'FORMAT(a.pay_amount,0) AS pay_amount',
            'f.final_status',
        ]);

        $this->builder->join('cpcrd_new b', 'a.cm_card_nmbr = b.CM_CARD_NMBR', 'left');
        $this->builder->join('tmp_ptp_daily d', 'd.cm_card_nmbr = a.cm_card_nmbr', 'left');
        $this->builder->join('cc_user e', 'd.user_id = e.id', 'left');
        $this->builder->join('tmp_last_payment_checking f', 'f.cm_card_nmbr = a.cm_card_nmbr', 'left');
        
        $this->builder->where('a.posting_date <=', date('Y-m-d')); 
        $this->builder->orderBy('a.posting_date');

        $rResult = $this->builder->get();
        $return = $rResult->getResultArray(); 

        if ($rResult->getNumRows() > 0) {
            $i = 1;
            foreach ($return as &$row) {
                $row['id'] = $i;
                $i++;
            }
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