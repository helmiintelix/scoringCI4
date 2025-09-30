<?php 
namespace App\Modules\ScoringDataSummary\Controllers;
use App\Modules\ScoringDataSummary\Models\ScoringDataSummaryModel;

class ScoringDataSummary extends \App\Controllers\BaseController
{

    protected $ScoringDataSummaryModel;

	function __construct()
	{
		$this->ScoringDataSummaryModel = new ScoringDataSummaryModel();
	}

    function ScoringDataSummary(){
		return view('\App\Modules\ScoringDataSummary\Views\ScoringDataSummaryView');
    }

    function getScoringDataSummary(){

        $data = $this->ScoringDataSummaryModel->getScoringDataSummary();

        $rs = array('success' => true, 'message' => '', 'data' => $data);
		
		return $this->response->setStatusCode(200)->setJSON($rs);
    }
}