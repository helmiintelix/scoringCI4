<?php

namespace App\Controllers;
use CodeIgniter\Model;
use CodeIgniter\Cookie\Cookie;

class Test extends BaseController
{
    function index()
    {
        echo "test";
       $data =  $this->input->getPost('data');
       print_r($data);
    }

}
?>