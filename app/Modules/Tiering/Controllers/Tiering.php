<?php

namespace App\Modules\Tiering\Controllers;

use App\Modules\Tiering\Models\TieringModel;
use App\Models\Common_model;
use App\Controllers\BaseController;

class Tiering extends BaseController
{
    protected $TieringModel;
    protected $Common_model;

    public function __construct()
    {
        $this->TieringModel  = new TieringModel();
        $this->Common_model  = new Common_model();
    }

    public function tiering()
    {
        $uri        = service('uri');
        $tiering_id = $uri->getSegment(3);

        $data['tiering_id'] = $tiering_id;

        $data['BUCKET_SC'] = $this->Common_model->get_ref_master_df(
            "value AS value, concat(value,' - ',description) AS item",
            "cms_reference",
            "status='1' and reference='BUCKET_SC'",
            "description",
            false
        );

        $data['LOB_CODE'] = $this->Common_model->get_ref_master_df(
            "value AS value, concat(value,' - ',description) AS item",
            "cms_reference",
            "status='1' and reference='LOB_CODE'",
            "value",
            false
        );

        $data['CYCLE_CODE'] = $this->Common_model->get_ref_master(
            "id AS value, cycle_name AS item",
            "sc_scoring_cycle",
            "is_active='1'",
            "cycle_name",
            false
        );

        if (!empty($tiering_id)) {
            $data['form_mode']   = 'EDIT';
            $data['tiering_data'] = $this->TieringModel->get_tiering_data($tiering_id);
        } else {
            $data['form_mode']   = 'ADD';
            $data['tiering_data'] = $this->TieringModel->get_tiering_data("NEW");
        }

        return view('App\Modules\Tiering\Views\TieringView', $data);
    }

    public function save_tiering()
    {
        $post = $this->request->getPost();

        try {
            $form_mode = $post['form_mode'];

            $tiering_data = [
                "id"           => $post['tiering-id'],
                "name"         => $post['tiering-label'],
                "total_data"   => '0',
                "created_by"   => session()->get('USER_ID'),
                "created_time" => date('Y-m-d H:i:s'),
            ];

            $cycle_from = $this->Common_model->get_record_value(
                'cycle_from',
                'sc_scoring_cycle',
                'id="' . $post['opt_cycle'] . '"'
            );

            $cycle_to = $this->Common_model->get_record_value(
                'cycle_to',
                'sc_scoring_cycle',
                'id="' . $post['opt_cycle'] . '"'
            );

            $tiering_setting = [
                "id"           => $post['tiering-id'],
                "score_tiering" => json_encode([$post['score-tiering-start'], $post['score-tiering-end']]),
                //"score_tiering2"=> json_encode([$post['score-tiering-start2'], $post['score-tiering-end2']]),
                "score_type"   => $post['opt_type'],
                //"product"     => json_encode([$post['product']]),
                //"bucket"      => json_encode([$post['bucket']]),
                //"dpd"         => json_encode([$post['dpd-start'], $post['dpd-end']]),
                "bucket"       => $post['opt_bucket'],
                "lob"          => $post['opt_lob'],
                "cycle"        => $post['opt_cycle'],
                "cycle_range"  => json_encode([$cycle_from, $cycle_to]),
                "assign_to"    => json_encode([$post['assign-to']]),
                "created_by"   => session()->get('USER_ID'),
                "created_time" => date('Y-m-d H:i:s'),
            ];

            $return = $this->TieringModel->set_tiering($form_mode, $tiering_data, $tiering_setting);

            if ($return) {
                $response = ["success" => true, "message" => "Save berhasil."];
            } else {
                $response = ["success" => false, "message" => "Save gagal."];
            }
        } catch (\Exception $e) {
            $response = [
                "success" => false,
                "message" => "Save Gagal",
                "error"   => $e->getMessage()
            ];
        }

        return $this->response->setJSON($response);
    }

    public function scoring_result()
    {
        $param_data = [
            "score_start"  => $this->request->getPost('score_start'),
            "score_end"    => $this->request->getPost('score_end'),
            "opt_type"     => $this->request->getPost('score_type'),
            "lob"          => $this->request->getPost('lob'),
            "bucket"       => $this->request->getPost('bucket'),
            "cycle"        => $this->request->getPost('cycle'),
            "operScoring"  => $this->request->getPost('operScoring'),
            // "score_end2" => $request->getPost('score_end2'), // kalau dipakai bisa diaktifkan
        ];

        $scoring_result_list = $this->TieringModel->get_scoring_result_list($param_data);

        return $this->response->setJSON($scoring_result_list);
    }
}
