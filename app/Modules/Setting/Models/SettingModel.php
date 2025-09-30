<?php

namespace App\Modules\Setting\Models;

use CodeIgniter\Model;
use App\Models\Common_model;

class SettingModel extends Model
{
    protected $Common_model;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new Common_model();
    }

    public function get_scheme_data($scheme_id)
    {
        $builder = $this->db->table('sc_scoring_scheme');
        $builder->select('*');
        $builder->where('id', $scheme_id);

        $query    = $builder->get();
        $arr_data = $query->getResultArray();

        return $arr_data;
    }

    public function get_setting($scheme_id, $parameter)
    {
        $builder = $this->db->table('sc_scoring_parameter AS a');

        if ($scheme_id === 'NEW') {
            $builder->select('UUID() AS obj_id, a.id AS param_id, a.name, a.is_include, a.is_primary, a.is_sum, a.is_monthly, a.value_type, a.value_content, a.map_reference, a.reference_table AS reference_table, a.reference AS parameter_reference, "" AS parameter_function, "[]" AS parameter_value, "" AS parameter_function_month, "[]" AS parameter_value_month, "[]" AS parameter_value_month_tmp, "" AS mapping_reference, "" AS mapping_parameter_function, "" AS mapping_parameter_value, "" AS score_value, "" AS score_value2', false);
            $builder->where('a.is_include', 'YES');
            $builder->where('a.is_active', 'Y');
            $builder->where('a.name <>', '');
            $builder->where('a.parameter', $parameter);
            $builder->orderBy('a.order_num', 'ASC');
        } else {
            $builder->select('\'' . $scheme_id . '\' AS obj_id, a.id AS param_id, a.name, a.is_include, a.is_primary, a.is_sum, a.is_monthly, a.value_type, a.value_content, a.map_reference, a.reference_table AS reference_table, a.reference AS parameter_reference, b.parameter_function, b.parameter_value, b.parameter_function_month, b.parameter_value_month, b.parameter_value_month_tmp, b.mapping_reference, b.mapping_parameter_function, b.mapping_parameter_value, b.score_value, b.score_value2', false);
            $builder->join('sc_scoring_scheme_setup b', 'a.parameter = b.parameter AND a.id = b.parameter_id AND b.id = "' . $scheme_id . '"', 'LEFT');
            $builder->where('a.is_include', 'YES');
            $builder->where('a.is_active', 'Y');
            $builder->where('a.name <>', '');
            $builder->where('a.parameter', $parameter);
            $builder->orderBy('a.order_num', 'ASC');
        }

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_included_parameter($parameter)
    {
        $builder = $this->db->table('sc_scoring_parameter');
        $builder->select('id, name, is_include, is_primary, is_sum, is_monthly, value_content, map_reference', false);
        $builder->where('parameter', $parameter);
        $builder->where('is_active', 'Y');
        $builder->where('name <>', '');
        $builder->where('is_include', 'YES');
        $builder->orderBy('order_num', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function set_setting($form_mode, $scheme_data, $setting_data)
    {
        $user_id = session()->get('USER_ID');
        $created_time = date('Y-m-d H:i:s');

        $builder_scheme = $this->db->table('sc_scoring_scheme');
        $builder_setup  = $this->db->table('sc_scoring_scheme_setup');
        $builder_log    = $this->db->table('sc_scoring_log');

        if ($form_mode == "ADD") {
            // Hapus dulu jika ada
            $builder_scheme->where('id', $scheme_data['id'])->delete();
            // Insert scheme baru
            $builder_scheme->insert($scheme_data);

            // Insert log
            $arr = [
                'id' => uuid(false),
                'id_scheme' => $scheme_data['id'],
                'id_upload' => null,
                'name_scheme' => $scheme_data['name'],
                'score_value' => $scheme_data['score_value'],
                'score_value2' => $scheme_data['score_value2'],
                'scheme_detail_before' => null,
                'scheme_detail_after' => json_encode($setting_data),
                'action' => 'ADD',
                'created_by' => $user_id,
                'created_time' => $created_time
            ];
            $builder_log->insert($arr);
        } else {
            // Ambil data sebelum update
            $scheme_before = $this->db->table('sc_scoring_scheme_setup')
                ->where('id', $scheme_data['id'])
                ->get()
                ->getResultArray();

            // Insert log
            $arr = [
                'id' => uuid(false),
                'id_scheme' => $scheme_data['id'],
                'id_upload' => $scheme_data['upload_id'] ?? null,
                'name_scheme' => $scheme_data['name'],
                'score_value' => $scheme_data['score_value'],
                'score_value2' => $scheme_data['score_value2'],
                'scheme_detail_before' => json_encode($scheme_before),
                'scheme_detail_after' => json_encode($setting_data),
                'action' => 'EDIT',
                'created_by' => $user_id,
                'created_time' => $created_time
            ];
            $builder_log->insert($arr);

            $builder_scheme->where('id', $scheme_data['id'])->update($scheme_data);
        }

        $builder_setup->where('id', $scheme_data['id'])->delete();
        $return = $builder_setup->insertBatch($setting_data);

        return $return;
    }

    public function upload_file($scorring_file)
    {
        $this->db->table('sc_setting_upload_activity')->insert($scorring_file);

        $dbConfig = config('Database');
        $db_group = $dbConfig->defaultGroup ?? 'default';
        $db_array = $dbConfig->{$db_group};
        $db_host  = $db_array['hostname'];
        $db_user  = $db_array['username'];
        $db_pass  = $db_array['password'];
        $db_name  = $db_array['database'];

        $this->db->query("TRUNCATE sc_scoring_scheme_setup_temp");
        $this->db->query("TRUNCATE sc_scoring_scheme_upload");
        $this->db->query("TRUNCATE sc_scoring_scheme_temp");

        $db = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
        if (!$db) {
            throw new \Exception("MySQL Connect Error: " . mysqli_connect_error());
        }
        mysqli_options($db, MYSQLI_OPT_LOCAL_INFILE, true);

        $filePath = str_replace("\\", "/", $scorring_file['full_path']);

        $sql = "LOAD DATA LOCAL INFILE '" . addslashes($filePath) . "' 
        INTO TABLE sc_scoring_scheme_upload
        CHARACTER SET utf8mb4
        FIELDS TERMINATED BY '\t'
        ENCLOSED BY '\"'
        LINES TERMINATED BY '\n'
        IGNORE 1 LINES
        (@col1,@col2,@col3,@col4,@col5,@col6)
        SET id=UUID(),
            sequence_no=@col1,
            parameter='SCORING_PURPLE',
            parameter_id=@col2,
            parameter_function='IN',
            parameter_value=@col3,
            score=CAST(REPLACE(@col4,',','.') AS DECIMAL(18,8)),
            score2=CAST(REPLACE(@col5,',','.') AS DECIMAL(18,8)),
            created_by = '" . $scorring_file['upload_by'] . "',
            created_time = DATE('" . $scorring_file['upload_time'] . "')";

        $tmp_arr_data = $this->db->table('sc_scoring_scheme_upload')->get()->getResultArray();

        $request   = \Config\Services::request();
        $lob_code  = $request->getPost('opt_lob');
        $bucket    = $request->getPost('opt_bucket');
        $is_active = $request->getPost('is_active');

        $user_id = session()->get('USER_ID');
        $now     = date('Y-m-d H:i:s');

        foreach ($tmp_arr_data as $aRow) {
            $auto_name = $aRow['sequence_no'] . "_" . $lob_code . "_" . str_replace(" ", "_", $bucket) . "_" . $aRow['parameter_id'];
            $id_all    = uuid(false);

            $data_schme = [
                'id'          => $id_all,
                'name'        => $auto_name,
                'score_value' => $aRow['score'],
                'score_value2' => $aRow['score2'],
                'method'      => 'METHOD2',
                'group_by'    => 'CM_CARD_NMBR',
                'client_id'   => 'BAF',
                'relate_id'   => $auto_name,
                'upload_id'   => $scorring_file['id'],
                'is_active'   => $is_active,
                'created_by'  => $user_id,
                'created_time' => $now
            ];
            $this->db->table('sc_scoring_scheme_temp')->insert($data_schme);

            $data_schme_setup1 = [
                'id'                => $id_all,
                'parameter'         => $aRow['parameter'],
                'parameter_id'      => $aRow['parameter_id'],
                'parameter_function' => $aRow['parameter_function'],
                'parameter_value'   => json_encode([$aRow['parameter_value']]),
                'score_value'       => $aRow['score'],
                'score_value2'      => $aRow['score2'],
                'client_id'         => 'BAF',
                'created_by'        => $user_id,
                'created_time'      => $now
            ];
            $this->db->table('sc_scoring_scheme_setup_temp')->insert($data_schme_setup1);

            $data_schme_setup2 = $data_schme_setup1;
            $data_schme_setup2['parameter_id']    = 'LOBCODE';
            $data_schme_setup2['parameter_value'] = json_encode([$lob_code]);
            $this->db->table('sc_scoring_scheme_setup_temp')->insert($data_schme_setup2);

            $data_schme_setup3 = $data_schme_setup1;
            $data_schme_setup3['parameter_id']    = 'BUCKET';
            $data_schme_setup3['parameter_value'] = json_encode([$bucket]);
            $this->db->table('sc_scoring_scheme_setup_temp')->insert($data_schme_setup3);

            $setting_data = [$data_schme_setup1, $data_schme_setup2, $data_schme_setup3];
            $log_data = [
                'id'                  => uuid(false),
                'id_scheme'           => $data_schme['id'],
                'id_upload'           => $scorring_file['id'],
                'name_scheme'         => $data_schme['name'],
                'score_value'         => $data_schme['score_value'],
                'score_value2'        => $data_schme['score_value2'],
                'scheme_detail_before' => null,
                'scheme_detail_after' => json_encode($setting_data),
                'action'              => 'UPLOAD',
                'created_by'          => $user_id,
                'created_time'        => $now
            ];
            $this->db->table('sc_scoring_log')->insert($log_data);
        }

        $this->db->query("INSERT INTO sc_scoring_scheme SELECT * FROM sc_scoring_scheme_temp");
        $this->db->query("INSERT INTO sc_scoring_scheme_setup SELECT * FROM sc_scoring_scheme_setup_temp");

        return true;
    }
}
