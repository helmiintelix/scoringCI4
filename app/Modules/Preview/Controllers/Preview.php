<?php

namespace App\Modules\Preview\Controllers;

use App\Modules\Preview\Models\PreviewModel;

class Preview extends \App\Controllers\BaseController
{
    protected $PreviewModel;

    public function __construct()
    {
        $this->PreviewModel = new PreviewModel();
    }

    public function preview()
    {
        $data["scheme_list"] = $this->PreviewModel->get_scheme_list();
        // echo "<pre>";
        // print_r($data["scheme_list"]);
        // echo "</pre>";
        return view('App\Modules\Preview\Views\PreviewView', $data);
    }
}
