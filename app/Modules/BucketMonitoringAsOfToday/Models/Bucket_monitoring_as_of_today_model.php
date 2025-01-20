<?php
namespace App\Modules\BucketMonitoringAsOfToday\models;
use CodeIgniter\Model;

Class Bucket_monitoring_as_of_today_model Extends Model 
{
    function get_bucket_monitoring_as_of_today($data){
        $this->builder = $this->db->table('acs_bucket_monitoring_as_of_today_view');
        $select = array(
			'*'
        );
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        if ($data['bucket_id']) {
            $this->builder->where('bucket_id', $data['bucket_id']);
        }
        $this->builder->orderBy('bucket_name');
        $return = $this->builder->get()->getResultArray();

        $rs = array(
            'status' => true,
            'rows' => $return
        );
        return $rs;
        // return $return;
    }
    
}