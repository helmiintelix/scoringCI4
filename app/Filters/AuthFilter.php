<?php 
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // echo "<pre>";
        // print_r($request->getBody());

        $router = service('router');

        if(session()->get('logged_in')!==null){
            if(!session()->get('logged_in')){
                // $routes->get('/', 'Login::index');  
                return redirect()->to(base_url('/login'))->with('error', "Invalid Credential");
            }else{
       
            }
        }else{
            return redirect()->to(base_url('/login'))->with('error', "Invalid Credential");
        }


      

    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)

    {
        // print_r($request);
        // Do something here
    }
}

?>