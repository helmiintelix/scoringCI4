<?php 
namespace App\Modules\WaDashboard\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\WaDashboard\Models\Wa_dashboard_model;


class Wa_dashboard extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Wa_dashboard_model = new Wa_dashboard_model();
	}

	function index()
	{
		
		$data['classification'] = $this->input->getPost('classification');

		$data['redy']= $this->Wa_dashboard_model->getRedy();

		$data['valid']= $this->Wa_dashboard_model->getValid();

		$data['invalid']= $this->Wa_dashboard_model->getInvalid();

		$data['pending']= $this->Wa_dashboard_model->getPending();

		$data['sent']= $this->Wa_dashboard_model->getSent();

		$data['read']= $this->Wa_dashboard_model->getread();

		$data['void']= $this->Wa_dashboard_model->getVoid();

		$data['expired']= $this->Wa_dashboard_model->getExpired();

		$data['reject']= $this->Wa_dashboard_model->getreject();
		
		return view('\App\Modules\WaDashboard\Views\Main_view',$data);
	}
    
}