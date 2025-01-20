<?php
namespace App\Modules\Bucket\models;
use App\Models\Common_model;
use CodeIgniter\Model;

Class Bucket_temp_model Extends Model 
{
    function get_bucket_list_temp(){
        $this->builder = $this->db->table('cms_bucket_temp a');
        $DBDRIVER = $this->db->DBDriver;
        if ($DBDRIVER === 'SQLSRV') {
            $status_approval = "CASE 
                                WHEN a.is_active = '1' THEN 'WAITING APPROVAL'
                                ELSE 'REJECTED'
                            END";

            $jenis_request = "CASE 
                                WHEN a.flag = '1' THEN 'ADD'
                                ELSE 'EDIT'
                            END";
        }elseif ($DBDRIVER === 'Postgre') {
            $status_approval = "CASE 
                                WHEN a.is_active = '1' THEN 'WAITING APPROVAL'
                                ELSE 'REJECTED'
                            END";

            $jenis_request = "CASE 
                                    WHEN a.flag = '1' THEN 'ADD'
                                    ELSE 'EDIT'
                            END";
        }else{
            $status_approval = 'IF(a.is_active = "1", "WAITING APPROVAL", "REJECTED")';
            $jenis_request = 'IF(a.flag = "1", "ADD", "EDIT")';
        }

        $select = array(
            '*',
             $status_approval.' AS status_approval',
            $jenis_request.' AS jenis_request'
            
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
    function approve_request_bucket($id){
        $this->builder = $this->db->table('cms_bucket_temp');
        $this->builder->whereIn('bucket_id', [$id]);
        $query = $this->builder->get();
        $data = $query->getResultArray();

        $this->db->table('cms_bucket')->delete(['bucket_id' => $id]); 

        $this->db->table('cms_bucket')->insertBatch($data); 

        $return = $this->db->table('cms_bucket_temp')->delete(['bucket_id' => $id]); 

        $builder2 = $this->db->table('cms_bucket');
        $builder2->select('dpd_from, dpd_until, product , flag_wo_co');
        $builder2->where('bucket_id', $id);
        $dataparam = $builder2->get()->getRowArray();
        $product = str_replace(',','","',$dataparam['product']);

        // print_r($dataparam['product']);

        $this->db->table('acs_bucket')->delete(['bucket_id' => $id]);
        $builder = $this->db->table('cms_bucket');
        $builder->whereIn('bucket_id', [$id]);
        $result = $builder->get()->getResultArray();

        $builder3 = $this->db->table('acs_bucket');
        foreach ($result as $key => $value) {
            $data_tele['bucket_id']		 = $value['bucket_id'];
            $data_tele['bucket_name']	 = $value['bucket_label'];
            $data_tele['product_id']	 = $value['product'];
            $data_tele['from_ovd']		 = $value['dpd_from'];
            $data_tele['to_ovd']		 = $value['dpd_until'];
            $data_tele['operator_from']	 = ">=";
            $data_tele['operator_to']	 = "<=";
            $data_tele['flag']			 = $value['is_active'];
            $data_tele['created_time']	 = date('Y-m-d H:i:s');
            $data_tele['created_by'] 	 = session()->get('USER_ID');
            $builder3->replace($data_tele);
        }

        $flag = ($dataparam['flag_wo_co'] == '1') ? 'Y' : 'N';
        

        $query = $this->db->table('cpcrd_new');
        $query->select('*');
        $query->join('cpcrd_ext', 'cpcrd_new.CM_CUSTOMER_NMBR = cpcrd_ext.CM_CUST_NMBR');
        $query->where('DPD BETWEEN ' . $this->db->escape($dataparam['dpd_from']) . ' AND ' . $this->db->escape($dataparam['dpd_until']));
        $query->whereIn('CM_SUB_PRDK_CTG', [$product]);
        $query->where('CM_CHGOFF_STATUS_FLAG', $flag);

        // Eksekusi kueri select
        $results = $query->get()->getResult();

        // Loop melalui hasil select
        foreach ($results as $result) {
            // Buat kueri update untuk setiap baris hasil
            $updateQuery = $this->db->table('cpcrd_new');
            $updateQuery->set('cm_bucket', $id);
            $updateQuery->where('CM_CUSTOMER_NMBR', $result->CM_CUSTOMER_NMBR); // Sesuaikan kondisi update sesuai kebutuhan
            $updateQuery->update();
        }

        return $return;
    }
    
    function delete_data_request($id){
        $this->builder = $this->db->table('cms_bucket_temp');
        $return = $this->builder->where('bucket_id', $id)->delete();
        return $return;
    }
}