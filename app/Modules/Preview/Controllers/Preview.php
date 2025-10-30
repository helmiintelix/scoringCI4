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
            $data = ["success" => true, "message" => "Successfully deleted."];
        } else {
            $data = ["success" => false, "message" => "Failed to delete."];
        }

        return $this->response->setJSON($data);
    }

    public function active_parameter()
    {
        $param['scheme_id'] = $this->request->getPost('scheme_id');
        $param['parameter'] = $this->request->getPost('parameter');
        $param['id_parameter'] = $this->request->getPost('id_parameter');

        $return = $this->PreviewModel->active_parameter($param);

        if ($return) {
            $data = ["success" => true, "message" => "Successfully activated."];
        } else {
            $data = ["success" => false, "message" => "Failed to activate."];
        }

        return $this->response->setJSON($data);
    }
}
