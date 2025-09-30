<?php 
namespace App\Modules\ScoringDataDetail\Controllers;
use App\Modules\ScoringDataDetail\Models\ScoringDataDetailModel;

class ScoringDataDetail extends \App\Controllers\BaseController
{

    protected $ScoringDataDetailModel;

	function __construct()
	{
		$this->ScoringDataDetailModel = new ScoringDataDetailModel();
	}

    function ScoringDataDetail(){
		return view('\App\Modules\ScoringDataDetail\Views\scoring_data_detail_view');
    }

    function getScoringDataDetail(){

        $data = $this->ScoringDataDetailModel->getScoringDataDetail();

        $rs = array('success' => true, 'message' => '', 'data' => $data);
		
		return $this->response->setStatusCode(200)->setJSON($rs);
    }
}