<?php
namespace App\Modules\Auditor\Controllers;
use App\Controllers\BaseController;
use App\Modules\Auditor\Models\AuditorModel;

class Auditor extends \App\Controllers\BaseController
{
     function __construct()
	{
		$this->AuditorModel = new AuditorModel();
	}
    function index()
    {
        return view('App\Modules\Auditor\Views\AuditorView');
    }

    function auditor_list()
    {
 
        $cache = 'auditor_list';

		if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
            $data = $this->AuditorModel->getAuditorList();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
	
		return $this->response->setStatusCode(200)->setJSON($rs);
    }
}
