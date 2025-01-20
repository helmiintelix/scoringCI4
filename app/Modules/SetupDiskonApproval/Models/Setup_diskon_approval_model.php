<?php
namespace App\Modules\SetupDiskonApproval\models;
use CodeIgniter\Cookie\Cookie;
use CodeIgniter\Model;

Class Setup_diskon_approval_model Extends Model 
{
   
    function get_list_approval_diskon()
    {
      
        $this->builder = $this->db->table('setup_approval_diskon a');

        $select = array(
            'a.id',
            'a.disc_approval_name',
            'FORMAT(amount_from,0) amtfromfrom',
            'FORMAT(amount_until,0) amtuntil',
            'a.json_checker',
            'a.json_approval',
            'c.name AS update_by',
            'a.updated_time',
            'a.created_time',
            'b.name AS create_by'
        );

        $this->builder->join('cc_user b ', 'a.created_by=b.id', 'left');
        $this->builder->join('cc_user c ', 'c.id=a.updated_by', 'left');
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
   

        $rResult = $this->builder->get();

        $return = $rResult->getResultArray();

        $result = array();

        if ($rResult->getNumRows() > 0) {
            foreach ($rResult->getResultArray()[0] as $key => $value) {
                $result['header'][] = array('field' => $key);
            }
            
            foreach ($return as &$row) {
                // Periksa keberadaan kunci 'json_checker' sebelum mengaksesnya
                if (isset($row['json_checker'])) {
                    $json_checker = json_decode($row['json_checker'], true);
                    
                    if ($json_checker !== null) {
                        $html_checker = '<ul>';
                        foreach ($json_checker as $id) {
                            // Query untuk mendapatkan nama dari cc_user berdasarkan id
                            $this->builder = $this->db->table('cc_user');
                            $select = array('name');
                            $this->builder->select(implode(', ', $select), false);
                            $condition = ['id' => $id];
                            $this->builder->where($condition);
                            $rResult = $this->builder->get();
                            $user_return = $rResult->getResultArray();
                            
                            $name = !empty($user_return) ? $user_return[0]['name'] : 'Nama tidak ditemukan';
                            
                            $html_checker .= '<li>' . htmlspecialchars($name) . '</li>';
                        }
                        $html_checker .= '</ul>';
                        
                        $row['json_checker'] = $html_checker;
                    } else {
                        $row['json_checker'] = '';
                    }
                } else {
                    $row['json_checker'] = '';
                }

                $row['json_approval'] = json_decode($row['json_approval'], true);
                $html = '';
                $x = 1;

                // Pastikan json_approval terdekode dengan benar dan merupakan array
                if (is_array($row['json_approval'])) {
                    foreach ($row['json_approval'] as $approval_key => $value) {
                        $html .= '<ul>';
                        $html .= '<b>Approval ' . $x . '</b>';

                        // Jika value adalah array, iterasi melalui setiap id dalam array
                        if (is_array($value)) {
                            foreach ($value as $key1 => $id) {
                                // Query untuk mendapatkan nama dari cc_user berdasarkan id
                                $this->builder = $this->db->table('cc_user');
                                $select = array('name');
                                $this->builder->select(str_replace(' , ', ' ', implode(', ', $select)), false);
                                $condition = ['id' => $id];
                                $this->builder->where($condition);
                                $rResult = $this->builder->get();
                                $user_return = $rResult->getResultArray();
                                
                                // Ambil nama dari hasil query
                                $name = !empty($user_return) ? $user_return[0]['name'] : 'Nama tidak ditemukan';
                                
                                // Tambahkan nama ke dalam HTML
                                $html .= '<li>' . htmlspecialchars($name) . '</li>';
                            }
                        }

                        $html .= '</ul>';
                        $x++;
                    }
                    
                    // Simpan hasil HTML ke dalam json_approval
                    $row['json_approval'] = $html;
                } else {
                    // Jika json_approval tidak valid, beri nilai default (misalnya string kosong)
                    $row['json_approval'] = '';
                }



                
            }

            $result['data'] = $return;
        } else {
            $result['header'] = array();
            $result['data'] = array();
        }
        
        $rs =  $result;
        return $rs;
        
    }

    // function get_area_branch_list_temp()
    // {
    //     $this->builder = $this->db->table('cms_area_branch_temp a');

    //     $select = array(
    //         'a.id',
    //         'a.area_id as area_id',
    //         'b.description AS Provinsi_Area',
    //         'c.description AS City_Area',
    //         'd.description AS Kecamatan_Area',
    //         'e.description AS Keluarahan_Area',
    //         'a.branch_list',
    //         'a.area_address AS Alamat_Area',
    //         'a.area_no_telp AS No_Telp_Area',
    //         'a.area_manager',
    //         'a.created_time as tgl_pengajuan',
    //         'a.created_by as diajukan_oleh',
    //         'IF(a.is_active = "1", "WAITING APPROVAL", "REJECTED") AS status_approval',
    //         'IF(a.flag = "1", "ADD", "EDIT") AS jenis_req',
    //     );

    //     $this->builder->join('cms_reference_region b ', 'a.area_prov = b.code', 'left');
    //     $this->builder->join('cms_reference_region c ', 'a.area_city = c.code', 'left');
    //     $this->builder->join('cms_reference_region d ', 'a.area_kec = d.code', 'left');
    //     $this->builder->join('cms_reference_region e ', 'a.area_kel = e.code', 'left');
    //     $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
    //     $rResult = $this->builder->get();

    //     $return = $rResult->getResultArray();

    //     $result = array();

    //     if ($rResult->getNumRows() > 0) {
    //         foreach ($rResult->getResultArray()[0] as $key => $value) {
    //             $result['header'][] = array('field' => $key);
    //         }
    //         $result['data'] = $return;
    //     } else {
    //         $result['header'] = array();
    //         $result['data'] = array();
    //     }
        
    //     $rs =  $result;
    //     return $rs;
    // }

    // function isExist($id){
    //     $this->builder = $this->db->table('cms_area_branch a');
    //     $this->builder->where(array(
	// 		'area_id' => $id
	// 	));
        
	// 	$query = $this->builder->get();

	// 	if ($query->getNumRows() > 0) {
	// 		return true;
	// 	} else {
	// 		return false;
	// 	}
    // }

    // function isExistarea_branch_tempId($id)
	// {
	// 	$this->builder = $this->db->table('cms_area_branch_temp a');
	// 	$this->builder->where(array(
	// 		'id' => $id
	// 	));
	// 	$query = $this->builder->get();

	// 	if ($query->getNumRows() > 0) {
	// 		return true;
	// 	} else {
	// 		return false;
	// 	}
	// }

    function saveApprovalDiskon_add($data)
	{
        $this->builder = $this->db->table('setup_approval_diskon');
		$return = $this->builder->insert($data);

        $cacheKey = session()->get('USER_ID') . '_setup_diskon_approval';
        cache()->delete($cacheKey);

		return $return;
	}

    // function save_area_branch_edit($area_branch_data)
	// {

    //     $this->builder = $this->db->table('cms_area_branch')
    //         ->where('id', $area_branch_data["id"])
    //         ->get();

    //     if ($this->builder->getNumRows() > 0) {
    //         $data = $this->builder->getRowArray();
            
    //         $this->builder = $this->db->table('cms_area_branch_temp');
    //         $return = $this->builder->insert($data);

    //         $data_update = array(
    //             'area_id' => $area_branch_data["area_id"],
    //             'area_name' => $area_branch_data["area_name"],
    //             'area_prov' => $area_branch_data["area_prov"],
    //             'area_city' => $area_branch_data["area_city"],
    //             'area_kec' => $area_branch_data["area_kec"],
    //             'area_kel' => $area_branch_data["area_kel"],
    //             'branch_list' => $area_branch_data["branch_list"],
    //             'area_address' => $area_branch_data["area_address"],
    //             'area_no_telp' => $area_branch_data["area_no_telp"],
    //             'area_manager' => $area_branch_data["area_manager"],
    //             'is_active' => $area_branch_data["is_active"],
    //             'flag' => $area_branch_data["flag"]
    //         );
    
    //         $this->builder = $this->db->table('cms_area_branch_temp');
    //         $this->builder->where('id', $area_branch_data['id']); 
    //         $this->builder->set($data_update); 
    //         $return = $this->builder->update($data_update);

    //         $cacheKey = session()->get('USER_ID') . '_area_branch_list';
    //         cache()->delete($cacheKey);
            
    //         $cacheKey = session()->get('USER_ID') . '_area_branch_list_temp';
    //         cache()->delete($cacheKey);
    //         return $return;

    //     } 
	// }

  
}
