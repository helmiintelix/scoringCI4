<?php
namespace App\Modules\HistoryUpload\models;
use CodeIgniter\Model;

Class HistoryUploadModel Extends Model 
{

    function get_summary_upload_list(){
        $builder = $this->db->table('sc_setting_upload_activity a');

        $select = [
            'a.id',
            'a.file_name',
            'a.lob',
            'a.bucket',
            'a.upload_time',
            'a.upload_by',
            'a.upload_status'
        ];

        // tambahkan select
        $builder->select(implode(',', $select), false);
        $builder->orderBy('a.upload_time', 'DESC');
        $where = '';

        if (!empty($where))
             $builder->where($where);

        $rResult =  $builder->get();

        $return = $rResult->getResultArray();

        $result = array();

        if ($rResult->getNumRows() > 0) {
            foreach ($rResult->getResultArray()[0] as $key => $value) {
                $result['header'][] = array('field' => $key);
            }
            $result['data'] = $return;

            $rs =  $result;
            return $rs;
        } else {
            $rs =  $result;
            return $rs;
        }
    }


    function delete_scheme_upload($id_upload)
    {
        $builder = $this->db->table('sc_scoring_scheme a');

        // ambil data scheme by upload_id
        $tmp_arr_data = $builder->where('upload_id', $id_upload)
                                ->get()
                                ->getResultArray();

        foreach ($tmp_arr_data as $aRow) {
            // ambil detail sebelum dihapus
            $scheme_before = $this->db->table('sc_scoring_scheme_setup a')
                                ->where('a.id', $aRow['id'])
                                ->get()
                                ->getResultArray();

            $scheme = $this->db->table('sc_scoring_scheme a')
                        ->where('a.id', $aRow['id'])
                        ->get()
                        ->getResultArray();

            if (!empty($scheme)) {
                $arr = [
                    'id'                   => uuid(false),
                    'id_scheme'            => $scheme[0]['id'],
                    'id_upload'            => $scheme[0]['upload_id'],
                    'name_scheme'          => $scheme[0]['name'],
                    'score_value'          => $scheme[0]['score_value'],
                    'score_value2'         => $scheme[0]['score_value2'],
                    'scheme_detail_before' => json_encode($scheme_before),
                    'scheme_detail_after'  => null,
                    'action'               => 'DELETE UPLOAD',
                    'created_by'           => session()->get('USER_ID'),
                    'created_time'         => date('Y-m-d H:i:s')
                ];

                $this->db->table('sc_scoring_log')->insert($arr);

                $this->db->table('sc_scoring_scheme_setup')
                ->where('id', $aRow['id'])
                ->delete();

                $return = $this->db->table('sc_scoring_scheme')
                            ->where('id', $aRow['id'])
                            ->delete();
            }
        }

        return $return ?? false;
    }

    function check_parameter_status($id_upload)
    {
        $query = $this->db->table('sc_scoring_scheme')
                    ->select('id')
                    ->where('upload_id', $id_upload)
                    ->get()
                    ->getResultArray();

        return count($query) === 0;
    }


}

?>