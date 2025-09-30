<?php

namespace App\Modules\Tiering\Models;

use CodeIgniter\Model;
use App\Models\Common_model;

class TieringModel extends Model
{
    protected $Common_model;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new Common_model();
    }

    public function get_tiering_data($tiering_id)
    {
        if ($tiering_id == 'NEW') {
            $builder = $this->db->table('sc_scoring_tiering_setup a')
                ->select("UUID() AS id, '' AS name, '' AS score_tiering, '' AS score_type, '' AS product, '' AS bucket, '' as cycle, '' AS dpd, '' AS assign_to", false)
                ->where('a.id', $tiering_id);
        } else {
            $builder = $this->db->table('sc_scoring_tiering a')
                ->select("a.id AS id, a.name, b.score_tiering, b.score_type, b.product, b.bucket, b.cycle, b.lob, b.dpd, b.assign_to", false)
                ->join('sc_scoring_tiering_setup b', 'a.id=b.id')
                ->where('a.id', $tiering_id);
        }

        $result = $builder->get()->getResultArray();
        $arr_data = [];

        if (count($result) > 0) {
            $row = $result[0];

            $arr_data['id']         = $row['id'] ?? '';
            $arr_data['name']       = $row['name'] ?? '';
            $arr_data['score_type'] = $row['score_type'] ?? '';
            $arr_data['bucket']     = $row['bucket'] ?? '';
            $arr_data['lob']        = $row['lob'] ?? '';
            $arr_data['cycle']      = $row['cycle'] ?? '';

            $tmp_score_tiering = json_decode($row['score_tiering'] ?? '[]');
            $arr_data['score_tiering_start'] = $tmp_score_tiering[0] ?? '';
            $arr_data['score_tiering_end']   = $tmp_score_tiering[1] ?? '';

            $tmp_assign_to = json_decode($row['assign_to'] ?? '[]');
            $arr_data['assign_to'] = $tmp_assign_to[0] ?? '';
        } else {
            $arr_data = [
                'id'                  => '',
                'name'                => '',
                'score_tiering_start' => '',
                'score_tiering_end'   => '',
                'score_type'          => '',
                'product'             => '',
                'bucket'              => '',
                'lob'                 => '',
                'cycle'               => '',
                'dpd_start'           => '',
                'dpd_end'             => '',
                'assign_to'           => '',
            ];
        }

        return $arr_data;
    }

    public function set_tiering($form_mode, $tiering_data, $tiering_setting)
    {
        $db = \Config\Database::connect();
        $return = false;

        // Hapus dan insert ulang sc_scoring_tiering
        $db->table('sc_scoring_tiering')
            ->where('id', $tiering_data['id'])
            ->delete();

        $db->table('sc_scoring_tiering')
            ->insert($tiering_data);

        // Hapus dan insert ulang sc_scoring_tiering_setup
        $db->table('sc_scoring_tiering_setup')
            ->where('id', $tiering_setting['id'])
            ->delete();

        $return = $db->table('sc_scoring_tiering_setup')
            ->insert($tiering_setting);

        return $return;
    }

    public function get_scoring_result_list($param_data)
    {
        $request = service('request');
        $crm = \Config\Database::connect('crm');

        $iDisplayStart   = $request->getVar('page') ?? 1;
        $iDisplayLength  = $request->getVar('rows') ?? 10;
        $iSortCol_0      = $request->getVar('sidx');
        $iSortingCols    = $request->getVar('sord');
        $sSearch         = $request->getVar('_search');
        $sEcho           = $request->getVar('sEcho');

        $builder = $crm->table('cpcrd_new AS a');

        $select = [
            "a.CM_CARD_NMBR AS no_pinjaman",
            "a.CR_NAME_1 AS nama_debitur",
            "a.CM_PRODUCT_TYPE AS product",
            "a.DPD AS dpd",
            "a.CM_TOTAL_OS_AR AS ar_balance",
            "a.CM_AMOUNT_DUE AS tunggakan_cicilan",
            "'' AS denda",
            "'' AS penalty",
            "a.CM_TOT_BALANCE AS total_billing",
            "a.score_value AS score",
            "a.score_value2 AS score2",
            "a.tiering_id AS tiering_label"
        ];
        $builder->select($select);

        // Filter Score
        if ($param_data["opt_type"] == 'score_value') {
            $builder->where('a.score_value >=', $param_data["score_start"]);
            $builder->where('a.score_value <=', $param_data["score_end"]);
        } else {
            $builder->where('a.score_value2 >=', $param_data["score_start"]);
            $builder->where('a.score_value2 <=', $param_data["score_end"]);
        }

        $builder->where('a.cm_lob_code', $param_data["lob"]);

        // Filter Cycle
        if (!empty($param_data["cycle"])) {
            $cycle_from = $this->Common_model->get_record_value('cycle_from', 'sc_scoring_cycle', 'id="' . $param_data["cycle"] . '"');
            $cycle_to   = $this->Common_model->get_record_value('cycle_to', 'sc_scoring_cycle', 'id="' . $param_data["cycle"] . '"');

            $builder->where('DAY(CM_DTE_PYMT_DUE) >=', $cycle_from);
            $builder->where('DAY(CM_DTE_PYMT_DUE) <=', $cycle_to);
        }

        // Filter Bucket
        if ($param_data["operScoring"] == 'equivalent') {
            $builder->where('a.cm_bucket', $param_data["bucket"]);
        } else {
            $bucket = explode("|", $param_data["bucket"]);
            $builder->whereIn('a.cm_bucket', $bucket);
        }

        // Paging - hanya jika ada parameter pagination
        if (isset($iDisplayStart) && $iDisplayLength > 0 && $iDisplayLength != '-1') {
            $offset = ($iDisplayStart - 1) * $iDisplayLength;
            $builder->limit($iDisplayLength, $offset);
        }

        // Ordering
        if (!empty($iSortCol_0)) {
            $builder->orderBy($iSortCol_0, $iSortingCols);
        }

        // Get Result
        $rResult = $builder->get()->getResultArray();

        // Hitung total
        $totalQuery = $crm->table('cpcrd_new AS a');
        $totalQuery->select('COUNT(*) as cnt');
        $iTotal = $totalQuery->get()->getRow()->cnt;

        $iFilteredTotal = count($rResult);

        // Cek $iDisplayLength sebelum pembagian    
        $total_pages = 0;
        if ($iDisplayLength > 0) {
            $total_pages = ceil($iFilteredTotal / $iDisplayLength);
        }

        // Format Output
        $list = [];
        foreach ($rResult as $aRow) {
            $aRow["tunggakan_cicilan"] = number_format($aRow['tunggakan_cicilan'], 0, ',', '.');
            $aRow["total_billing"] = number_format($aRow['total_billing'], 0, ',', '.');
            $aRow["ar_balance"] = number_format($aRow['ar_balance'], 0, ',', '.'); // Format juga AR Balance
            $aRow["tiering_label"] = $this->Common_model->get_record_value('name', 'sc_scoring_tiering', 'id="' . $aRow["tiering_label"] . '"');

            $list[] = [
                "id"   => $aRow['no_pinjaman'],
                "cell" => $aRow
            ];
        }

        $output = [
            'page'    => (int) $iDisplayStart,
            'total'   => $total_pages,
            'records' => $iFilteredTotal,
            'rows'    => $list,
        ];

        return $output;
    }
}
