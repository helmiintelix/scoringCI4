<?php
namespace App\Modules\SetUpRestructureApproval\models;
use CodeIgniter\Cookie\Cookie;
use CodeIgniter\Model;

Class Setup_restructure_approval_model Extends Model 
{
   
    function get_list_approval_restructure()
    {
      
        $this->builder = $this->db->table('setup_approval_restructure a');

        $select = array(
            'a.id',
            'a.disc_approval_name',
            'FORMAT(amount_from,0) amtfrom',
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
    function saveApprovalRestructure_add($data)
	{
        $this->builder = $this->db->table('setup_approval_restructure');
		$return = $this->builder->insert($data);

        $cacheKey = session()->get('USER_ID') . '_list_approval_restructure';
        cache()->delete($cacheKey);

		return $return;
	}
    function saveApprovalRestructure_edit($data)
	{
        $this->builder = $this->db->table('setup_approval_restructure');
        $this->builder->where('id', $data['id']);
		$return = $this->builder->update($data);

        $cacheKey = session()->get('USER_ID') . '_list_approval_restructure';
        cache()->delete($cacheKey);

		return $return;
	}

  
}