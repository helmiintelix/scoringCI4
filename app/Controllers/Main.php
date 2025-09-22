<?php

namespace App\Controllers;
use CodeIgniter\Model;
use CodeIgniter\Cookie\Cookie;

class Main extends BaseController
{
    function index()
    {

        log_message('info', session()->get('USER_ID').' LOGIN');
		$data["session_expire"] = $this->Common_model->get_record_value('value', 'cc_app_configuration', "parameter='SYSTEM' AND id='FORCED_EXIT'");
		$data['menus'] = explode(";", $this->Common_model->get_record_value('authority AS menu_list', 'cc_user_group', 'id="'.session()->get('GROUP_ID').'"'));
        $data['group'] = '';//$this->common_model->get_record_value('group_concat(a.channel_module)', 'cc_user_group_detail a', 'a.group_id="'.session()->get('GROUP_ID').'" group by a.group_id');
		$data['running_text'] = $this->Common_model->get_running_text();

        $data["password_status"] = $this->Common_model->get_record_value('password_status', 'cc_user', "id='".session()->get('USER_ID')."'");
		session()->set("password_status", $data["password_status"]);
		$data["password_age"] = $this->Common_model->get_record_value('DATEDIFF(CURDATE(), DATE(password_date))', 'cc_user', "id='".session()->get('USER_ID')."'");
		session()->set("password_age", $data["password_age"]);
		$data["session_expire"] = $this->Common_model->get_record_value('value', 'cc_app_configuration', "parameter='SYSTEM' AND id='FORCED_EXIT'");
		$data["max_failed_attempts"] = $this->Common_model->get_record_value('value', 'cc_app_configuration', "parameter='SYSTEM' AND id='PASSWORD_MAX_FAILED_ATTEMPS'");
		session()->set("max_failed_attempts", $data["max_failed_attempts"]);
		
		$data["authorities"] = explode("|", $this->Common_model->get_record_value('authority', 'cc_user', "id='".session()->get('USER_ID')."'"));

        $data['csrf_hash'] = csrf_hash();
        $data['csrf_token'] = csrf_token();
        
        // return view('nice_admin_view',$data,['cache' => 60]); 
        return view('nice_admin_view',$data); 
    }



    function setMenu(){
        $cache = session()->get('USER_GROUP');

        if($this->cache->get($cache)){
            $rs = json_decode($this->cache->get($cache));
            return $this->response->setStatusCode(200)->setJSON($rs);
        }

        $builder = $this->db->table('cc_menu');
        $i = 0;
        $menu_arr = array();
    
        if (session()->get('USER_GROUP') == "ROOT") {
            // Jika user group adalah ROOT, ambil semua menu
            $main_menu = $builder->select('menu_id, menu_desc, parent_id, order_num, url, icon')
                                 ->where('parent_id', '')
                                 ->where('menu_type', 'url')
                                 ->orderBy('order_num')
                                 ->get();
        } else {
            // Ambil menu_list dari cc_user_group berdasarkan USER_GROUP selain ROOT
            $user_group_builder = $this->db->table('cc_user_group');
            $menu_list_query = $user_group_builder->select('authority')
                                                  ->where('id', session()->get('USER_GROUP'))
                                                  ->get();
        
            $row = $menu_list_query->getRow();
            if ($row) {
                // Format ulang menu_list yang dipisahkan oleh '|'
                $menu_list = explode('|', $row->authority);
        
                // Query untuk mendapatkan menu yang ada dalam menu_list
                $main_menu = $builder->select('menu_id, menu_desc, parent_id, order_num, url, icon')
                                     ->where('parent_id', '')
                                     ->whereIn('menu_id', $menu_list)  // Menggunakan whereIn untuk array
                                     ->where('menu_type', 'url')
                                     ->orderBy('order_num')
                                     ->get();
            }
        }
    
        foreach ($main_menu->getResultArray() as $aRow) {
            $menu_arr[$i] = $aRow;
            $href = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $aRow["menu_desc"]));
    
            // Query sub-menu berdasarkan parent_id menggunakan Query Builder CI4
            if (session()->get('USER_GROUP') == "ROOT") {
                $sub_menu = $builder->resetQuery()  // Reset query builder untuk melakukan query baru
                ->select('menu_id, menu_desc, parent_id, order_num, url, icon')
                ->where('parent_id', $aRow['menu_id'])
                ->where('menu_type', 'url')
                ->orderBy('order_num')
                ->get();
            }else{
                $sub_menu = $builder->resetQuery()  // Reset query builder untuk melakukan query baru
                ->select('menu_id, menu_desc, parent_id, order_num, url, icon')
                ->where('parent_id', $aRow['menu_id'])
                ->whereIn('menu_id', $menu_list)
                ->where('menu_type', 'url')
                ->orderBy('order_num')
                ->get();
            }
           
    
            if ($sub_menu->getNumRows() > 0) {
                $ii = 0;
                foreach ($sub_menu->getResultArray() as $bRow) {
                    $menu_arr[$i]['children'][$ii] = $bRow;
                    $href_sub = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $bRow["menu_desc"]));
    
                    // Query sub-sub-menu berdasarkan parent_id menggunakan Query Builder CI4
                    if (session()->get('USER_GROUP') == "ROOT") {
                        $sub_sub_menu = $builder->resetQuery()  // Reset query builder untuk melakukan query baru
                                            ->select('menu_id, menu_desc, parent_id, order_num, url, icon')
                                            ->where('parent_id', $bRow['menu_id'])
                                            ->where('menu_type', 'url')
                                            ->orderBy('order_num')
                                            ->get();
                    }else{
                        $sub_sub_menu = $builder->resetQuery()  // Reset query builder untuk melakukan query baru
                                            ->select('menu_id, menu_desc, parent_id, order_num, url, icon')
                                            ->where('parent_id', $bRow['menu_id'])
                                            ->whereIn('menu_id', $menu_list)
                                            ->where('menu_type', 'url')
                                            ->orderBy('order_num')
                                            ->get();
                    }
    
                    if ($sub_sub_menu->getNumRows() > 0) {
                        $iii = 0;
                        foreach ($sub_sub_menu->getResultArray() as $cRow) {
                            $menu_arr[$i]['children'][$ii]['children'][$iii] = $cRow;
                            $iii++;
                        }
                    }
                    $ii++;
                }
            }
            $i++;
        }

        $JSON_MENU = $builder->select('a.menu_id, c.menu_desc AS menu_1, b.menu_desc AS menu_2, a.menu_desc AS menu_3')
                ->from('cc_menu a')  // Alias untuk tabel utama
                ->join('cc_menu b', 'b.menu_id = a.parent_id', 'left')  // LEFT JOIN dengan tabel b
                ->join('cc_menu c', 'b.parent_id = c.menu_id', 'left')  // LEFT JOIN dengan tabel c
                ->where('a.url !=', '#')  // Kondisi where untuk url
                ->get()
                ->getResultArray();  // Mengambil hasil sebagai array
        $arr_menu = array();
        foreach ($JSON_MENU as $key => $value) {
            $arr_menu[$value['menu_id']] = array('menu_1'=>$value['menu_1'],'menu_2'=>$value['menu_2'],'menu_3'=>$value['menu_3']);
        }
        $JSON_MENU = $arr_menu;

        $response = array('MENU_ARR'=>$menu_arr,'JSON_MENU'=>$JSON_MENU);
        $this->cache->save($cache,json_encode($response),env('TIMECACHE_2'));
        
        return $this->response->setStatusCode(200)->setJSON($response);
    }
}
?>