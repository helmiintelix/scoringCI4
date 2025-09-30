<?php
namespace App\Modules\ScoringDataSummary\models;
use CodeIgniter\Model;
use App\Models\Common_model;


Class ScoringDataSummaryModel Extends Model 
{
    protected $Common_model;
    function __construct(){
        parent::__construct();
        $this->Common_model = new Common_model();
	 	$this->crm  = db_connect('crm');

    }

    function getScoringDataSummary(){
        $builderSc = $this->db->table('sc_scoring_tiering a');
        $builderSc->select("
            a.id,
            a.name,
        ");
        $rResultTiering = $builderSc->get();
        $returnrTiering = $rResultTiering->getResultArray();
        
        $select = [
            "a.id AS tiering_id", 
            "a.name AS tiering_name", 
            "0 AS score_from", 
            "0 AS score_to", 
            "0 AS score_from2", 
            "0 AS score_to2", 
            "b.score_tiering",
            "b.score_tiering2",
            "b.score_type",
            "'' AS owner",
            "0 AS total_data",
            "b.product",
            "b.bucket",
            "CASE
                WHEN b.bucket = 'CA1 & CA2' THEN 'BUCKET 1'
                WHEN b.bucket = 'CA3' THEN 'BUCKET 2'
                WHEN b.bucket = 'EARLY' THEN 'BUCKET 3'
                WHEN b.bucket = 'MID' THEN 'BUCKET 4'
                WHEN b.bucket = 'NPL' THEN \"('BUCKET 5', 'BUCKET 6', 'BUCKET 7')\"
                WHEN b.bucket = 'WO' THEN 'REMEDIAL'
                ELSE 'UNKNOWN'
            END AS bucket_label",
            "b.cycle",
            "b.lob",
            "b.dpd",
            "b.assign_to",
            "c.cycle_from",
            "c.cycle_to",
            "c.cycle_name"
        ];

        $builder = $this->db->table('sc_scoring_tiering a');
        $builder->select('SQL_CALC_FOUND_ROWS ' . implode(', ', $select), false);
        $builder->join('sc_scoring_tiering_setup b', 'b.id = a.id');
        $builder->join('sc_scoring_cycle c', 'c.id = b.cycle', 'left');

        $rResult = $builder->get();
        $return = $rResult->getResultArray();
        $result = array();
		$grand_total_data = 0;

        if ($rResult->getNumRows() > 0) {
            foreach ($rResult->getResultArray()[0] as $key => $value) {
                $result['header'][] = array('field' => $key);
            }
            $result['data'] = $return;
            
            foreach ($result['data'] as $key => $value) {
                if(!empty($value['score_tiering'])){
                    $arr_score_tiering = json_decode($value["score_tiering"]);
                    $value['score_from'] = $arr_score_tiering[0];
                    $value['score_to'] = $arr_score_tiering[1];

                    if (empty($value['cycle_from'])||empty($value['cycle_to'])){
                        $value["total_data"]=0;	
                    }else{
                        if($value['bucket']=='NPL'){
                            $value["total_data"] = $this->Common_model->get_record_value_crm("COUNT(*)", "cpcrd_new", $value['score_type'].">=".$value["score_from"]." and ".$value['score_type']."<=".$value["score_to"]." and cm_lob_code='".$value['lob']."' and cm_bucket in ".$value['bucket_label']." and day(cm_dte_pymt_due) BETWEEN ".$value['cycle_from']." and ".$value['cycle_to']);
                        }else{
                            $value["total_data"] = $this->Common_model->get_record_value_crm("COUNT(*)", "cpcrd_new", $value['score_type'].">=".$value["score_from"]." and ".$value['score_type']."<=".$value["score_to"]." and cm_lob_code='".$value['lob']."' and cm_bucket='".$value['bucket_label']."' and day(cm_dte_pymt_due) BETWEEN ".$value['cycle_from']." and ".$value['cycle_to']);
                        }
                    }	
				    $grand_total_data += $value["total_data"];
                }else{
                    $value["score_from"] = "";
                    $value["score_to"] = "";
                    $total_all_data = $this->Common_model->get_record_value_crm("COUNT(*)", "cpcrd_new_upload", "CM_CARD_NMBR<>''");
                    $value["total_data"] = $total_all_data - $grand_total_data;
                }
            }
            $rs =  $result;
            return $rs;
        } else {
            $rs =  $result;
            return $rs;
        }
    }
}