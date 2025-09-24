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

    public function delete_scheme()
    {
        $scheme_id = $this->request->getPost('scheme_id');

        $return = $this->PreviewModel->delete_scheme($scheme_id);

        if ($return) {
            $data = ["success" => true, "message" => "Success"];
        } else {
            $data = ["success" => false, "message" => "Failed"];
        }

        return $this->response->setJSON($data);
    }
}
