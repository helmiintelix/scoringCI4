<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Modules\TeamManagement\Models\Team_work_model;
use App\Models\Common_model;

helper('uuid');
helper('form');
// use App\Modules\Common\Models\Common_model;
// use App\models\Common_model;
/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session()
        
        
        $this->session = \Config\Services::session(); 
        $this->cache = \Config\Services::cache();
        $this->input = \Config\Services::request();
        
  
        $DB = \Config\Database::connect();
        $this->db = db_connect();
        $this->cti = db_connect('cti');
        $this->crm = db_connect('crm');
        $this->DBDRIVER = $this->db->DBDriver;
        
        $this->Common_model = new Common_model();
        
        $this->Login_model = model('App\Models\Login_model');
        $this->TeamWorkModel = new Team_work_model();
       

        // $this->input = \Config\Services::request();

        
    }

    public function responseJSON($cacheName,$ttl = 'TIMECACHE_1'){
        $cache = \Config\Services::cache();
        $response = \Config\Services::response();
       
        if($cacheName==''){
            
        }else{
   
            $data = json_decode($cache->get($cacheName));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
            return $response->setStatusCode(200)->setJSON($rs); 
              
        }
    }

    public function checkCache($cacheName){

    }
}
