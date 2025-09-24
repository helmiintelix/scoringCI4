<?php
namespace App\Modules\TieringPreview\models;
use CodeIgniter\Model;
use App\Models\Common_model;


Class TieringPreviewModel Extends Model 
{
    protected $Common_model;
    function __construct(){
        parent::__construct();
        $this->Common_model = new Common_model();
    }

    function getTieringList()
    {

        $builder = $this->db->table('sc_scoring_tiering a');
        $builder->select("
            a.id AS tiering_id,
            a.name AS tiering_name,
            b.score_tiering,
            b.score_type,
            b.product,
            b.bucket,
            CASE
                WHEN b.bucket = 'CA1 & CA2' THEN 'BUCKET 1'
                WHEN b.bucket = 'CA3' THEN 'BUCKET 2'
                WHEN b.bucket = 'EARLY' THEN 'BUCKET 3'
                WHEN b.bucket = 'MID' THEN 'BUCKET 4'
                WHEN b.bucket = 'NPL' THEN \"('BUCKET 5', 'BUCKET 6', 'BUCKET 7')\"
                WHEN b.bucket = 'WO' THEN 'REMEDIAL'
                ELSE 'UNKNOWN'
            END AS bucket_label,
            b.cycle,
            b.lob,
            b.dpd,
            b.assign_to,
            a.total_data,
            c.cycle_from,
            c.cycle_to,
            c.cycle_name
        ", false);

        $builder->join('sc_scoring_tiering_setup b', 'a.id=b.id');
        $builder->join('sc_scoring_cycle c', 'c.id=b.cycle', 'left');

        $query = $builder->get();
        $tmp_arr_data = $query->getResultArray();

        $arr_data = [];

        if (!empty($tmp_arr_data)) {
            foreach ($tmp_arr_data as $row) {
                $scoreStart = json_decode($row['score_tiering'])[0] ?? 0;
                $scoreEnd   = json_decode($row['score_tiering'])[1] ?? 0;

                if (empty($row['cycle_from']) || empty($row['cycle_to'])) {
                    $total_data = 0;
                } else {
                    $where = $row['score_type'] . " >= " . $scoreStart .
                            " AND " . $row['score_type'] . " <= " . $scoreEnd .
                            " AND cm_lob_code = '" . $row['lob'] . "'" .
                            " AND day(cm_dte_pymt_due) BETWEEN " . $row['cycle_from'] . " AND " . $row['cycle_to'];

                    if ($row['bucket'] === 'NPL') {
                        $where .= " AND cm_bucket IN " . $row['bucket_label'];
                    } else {
                        $where .= " AND cm_bucket = '" . $row['bucket_label'] . "'";
                    }

                    // 🔹 ganti common_model -> bikin helper atau model method di CI4
                    $total_data = $this->Common_model->get_record_value_crm("COUNT(*)", "cpcrd_new", $where);
                }

                $arr_data[] = [
                    "tiering_id"    => $row['tiering_id'],
                    "tiering_name"  => $row['tiering_name'],
                    "score_tiering" => $row['score_tiering'],
                    "score_type"    => $row['score_type'],
                    "product"       => $row['product'],
                    "bucket"        => $row['bucket'],
                    "cycle"         => $row['cycle'],
                    "cycle_name"    => $row['cycle_name'],
                    "lob"           => $row['lob'],
                    "dpd"           => $row['dpd'],
                    "assign_to"     => $row['assign_to'],
                    "total_data"    => $total_data,
                ];
            }
        } else {
            $arr_data = [];
        }

        return $arr_data;

    }
}