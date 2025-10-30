<?php
namespace App\Modules\ScoringDataDetail\models;
use CodeIgniter\Model;
use App\Models\Common_model;


Class ScoringDataDetailModel Extends Model 
{
    protected $Common_model;
    function __construct(){
        parent::__construct();
        $this->Common_model = new Common_model();
	 	$this->crm  = db_connect('crm');

    }

    function getScoringDataDetail(){
        $builderSc = $this->db->table('sc_scoring_tiering a');
        $builderSc->select("
            a.id,
            a.name,
        ");
        $rResultTiering = $builderSc->get();
        $returnrTiering = $rResultTiering->getResultArray();
        
        $builder = $this->crm->table('cpcrd_new a');
        $builder->select("
            a.CM_CARD_NMBR AS noPinjaman,
            a.CR_NAME_1 AS namaDebitur,
            a.CM_BUCKET AS bucket,
            a.CM_LOB_CODE AS lob,
            a.CM_PRODUCT_TYPE AS product,
            a.DPD AS dpd,
            a.CM_TOTAL_OS_AR AS arBalance,
            FORMAT(a.CM_AMOUNT_DUE,2) AS tunggakanCicilan,
            '' AS denda,
            '' AS penalty,
            FORMAT(a.CM_TOT_BALANCE, 2) AS totalBilling,
            a.score_value AS score,
            a.score_value2 AS score2,
            a.tiering_id AS tieringLabel,
            a.tiering_id2 AS tieringLabel2
        ");

        $rResult = $builder->get();
        $return = $rResult->getResultArray();
        $result = array();

        if ($rResult->getNumRows() > 0) {
            foreach ($rResult->getResultArray()[0] as $key => $value) {
                if($key=='noPinjaman'){
                    $result['header'][] = array('field' => $key,'cellDataType'=> 'text');
                }else{

                    $result['header'][] = array('field' => $key);
                }
            }
            $result['data'] = $return;
            foreach ($result['data'] as $key => $value) {
                foreach ($returnrTiering as $idx => $reference) {
                    if(empty($result['data'][$key]['tieringLabel'])){
                        $result['data'][$key]['tieringLabel']='NO SCORE';
                    }
                    if($result['data'][$key]['tieringLabel'] == $reference['id']){
                        $result['data'][$key]['tieringLabel'] = $reference['name'];
                    }
                    if(empty($result['data'][$key]['tieringLabel2'])){
                        $result['data'][$key]['tieringLabel2']='NO SCORE';
                    }
                    if($result['data'][$key]['tieringLabel2'] == $reference['id']){
                        $result['data'][$key]['tieringLabel2'] = $reference['name'];
                    }
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