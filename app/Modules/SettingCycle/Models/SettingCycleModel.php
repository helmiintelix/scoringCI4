<?php

namespace App\Modules\SettingCycle\Models;

use CodeIgniter\Model;

class SettingCycleModel extends Model
{
    protected $table = 'sc_scoring_cycle';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'cycle_name',
        'cycle_from',
        'cycle_to',
        'is_active',
        'updated_time',
        'updated_by'
    ];

    public function get_cycle_management_list()
    {
        $request = service('request');

        // DataTables params
        $draw   = $request->getVar('draw');
        $start  = $request->getVar('start');
        $length = $request->getVar('length');

        $order  = $request->getVar('order');
        $search = $request->getVar('search')['value'] ?? '';

        // Custom filter
        $search_by = $request->getVar('search_by');
        $keyword   = $request->getVar('keyword');

        // kolom sesuai urutan tabel di view
        $columns = [
            0 => 'id',
            1 => 'cycle_name',
            2 => 'cycle_from',
            3 => 'cycle_to',
            4 => 'is_active',
            5 => 'updated_time',
            6 => 'updated_by'
        ];

        $builder = $this->db->table($this->table);

        // Global search
        if (!empty($search)) {
            $builder->groupStart()
                ->like('cycle_name', $search)
                ->orLike('cycle_from', $search)
                ->orLike('cycle_to', $search)
                ->orLike('updated_by', $search)
                ->groupEnd();
        }

        // Custom filter (dropdown + keyword)
        if (!empty($search_by) && !empty($keyword)) {
            $builder->like($search_by, $keyword);
        }

        // Total filtered
        $totalFiltered = $builder->countAllResults(false);

        // Ordering
        if (!empty($order)) {
            $colIndex = $order[0]['column'];
            $colName  = $columns[$colIndex] ?? 'id';
            $dir      = $order[0]['dir'] ?? 'asc';
            $builder->orderBy($colName, $dir);
        } else {
            $builder->orderBy('id', 'DESC');
        }

        // Paging
        if ($length != -1) {
            $builder->limit($length, $start);
        }

        $query = $builder->get();
        $data  = [];

        foreach ($query->getResult() as $row) {
            $status = $row->is_active == 1
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-danger">Inactive</span>';

            $data[] = [
                $row->id,
                $row->cycle_name,
                $row->cycle_from,
                $row->cycle_to,
                $status,
                $row->updated_time,
                $row->updated_by
            ];
        }

        // Total semua data (tanpa filter)
        $totalData = $this->countAll();

        return [
            "draw"            => intval($draw),
            "recordsTotal"    => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        ];
    }
}
