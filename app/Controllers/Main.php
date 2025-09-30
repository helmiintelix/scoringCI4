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

    public function CreateCcMenu()
    {

        if (! $this->db->tableExists('cc_menu')) {
            // return "GAADA";

            $this->db->query("
                CREATE TABLE cc_menu (
                    menu_id VARCHAR(36) PRIMARY KEY,
                    menu_desc VARCHAR(128) NOT NULL,
                    parent_id VARCHAR(36) NULL,
                    order_num INT NULL,
                    url VARCHAR(255) NULL,
                    icon VARCHAR(64) NULL,
                    menu_type VARCHAR(32) NULL,
                    menu_group VARCHAR(32) NULL
                )
            ");
        }
        // return "ADA";
        $count = $this->db->table('cc_menu')->countAllResults();
        if ($count == 0) {
            $data = [
                ['menu_id' => '1',  'menu_desc' => 'scoring',         'parent_id' => null, 'order_num' => 1, 'url' => '#', 'icon' => null, 'menu_type' => 'url', 'menu_group' => null],
                ['menu_id' => '10', 'menu_desc' => 'scheduler',       'parent_id' => '1',  'order_num' => 1, 'url' => 'scoring/scheduler', 'icon' => null, 'menu_type' => 'url', 'menu_group' => null],
                ['menu_id' => '11', 'menu_desc' => 'parameters',      'parent_id' => '1',  'order_num' => 2, 'url' => 'scoring/parameters', 'icon' => null, 'menu_type' => 'url', 'menu_group' => null],
                ['menu_id' => '12', 'menu_desc' => 'settings',        'parent_id' => '1',  'order_num' => 3, 'url' => 'scoring/setting',   'icon' => null, 'menu_type' => 'url', 'menu_group' => null],
                ['menu_id' => '13', 'menu_desc' => 'preview',         'parent_id' => '1',  'order_num' => 4, 'url' => 'scoring/preview',   'icon' => null, 'menu_type' => 'url', 'menu_group' => null],
                ['menu_id' => '14', 'menu_desc' => 'setting cycle',   'parent_id' => '1',  'order_num' => 5, 'url' => 'scoring/setting_cycle', 'icon' => null, 'menu_type' => 'url', 'menu_group' => null],
                ['menu_id' => '15', 'menu_desc' => 'tiering',         'parent_id' => '1',  'order_num' => 6, 'url' => 'scoring/tiering',   'icon' => null, 'menu_type' => 'url', 'menu_group' => null],
                ['menu_id' => '16', 'menu_desc' => 'tiering preview', 'parent_id' => '1',  'order_num' => 7, 'url' => 'scoring/tieringPreview', 'icon' => null, 'menu_type' => 'url', 'menu_group' => null],

                ['menu_id' => '2',  'menu_desc' => 'scoring result',  'parent_id' => null, 'order_num' => 2, 'url' => '#', 'icon' => null, 'menu_type' => 'url', 'menu_group' => null],
                ['menu_id' => '20', 'menu_desc' => 'detail data',     'parent_id' => '2',  'order_num' => 1, 'url' => 'scoring/scoringDataDetail', 'icon' => null, 'menu_type' => 'url', 'menu_group' => null],
                ['menu_id' => '21', 'menu_desc' => 'summary data',    'parent_id' => '2',  'order_num' => 2, 'url' => 'scoring/scoringDataSummary', 'icon' => null, 'menu_type' => 'url', 'menu_group' => null],

                ['menu_id' => '3',  'menu_desc' => 'history upload',  'parent_id' => null, 'order_num' => 3, 'url' => '#', 'icon' => null, 'menu_type' => 'url', 'menu_group' => null],
                ['menu_id' => '30', 'menu_desc' => 'history upload data', 'parent_id' => '3', 'order_num' => 1, 'url' => 'historyUpload/historyUpload', 'icon' => null, 'menu_type' => 'url', 'menu_group' => null],

                ['menu_id' => '4',  'menu_desc' => 'auditor',         'parent_id' => null, 'order_num' => 4, 'url' => '#', 'icon' => null, 'menu_type' => 'url', 'menu_group' => null],
                ['menu_id' => '40', 'menu_desc' => 'auditor data',    'parent_id' => '4',  'order_num' => 1, 'url' => 'auditor/dataAuditor', 'icon' => null, 'menu_type' => 'url', 'menu_group' => null],

                ['menu_id' => '5',  'menu_desc' => 'settings',        'parent_id' => null, 'order_num' => 5, 'url' => '#', 'icon' => null, 'menu_type' => 'url', 'menu_group' => null],
                ['menu_id' => '50', 'menu_desc' => 'general settings','parent_id' => '5',  'order_num' => 1, 'url' => 'setting/general', 'icon' => null, 'menu_type' => 'url', 'menu_group' => null],

                ['menu_id' => '6',  'menu_desc' => 'user and group',  'parent_id' => null, 'order_num' => 6, 'url' => '#', 'icon' => null, 'menu_type' => 'url', 'menu_group' => null],
                ['menu_id' => '60', 'menu_desc' => 'user management', 'parent_id' => '6',  'order_num' => 1, 'url' => 'user_and_group/user_management', 'icon' => null, 'menu_type' => 'url', 'menu_group' => null],
            ];

            $this->db->table('cc_menu')->insertBatch($data);
            return "cc_menu berhasil dibuat & data default dimasukkan.";
        }

        return "cc_menu sudah ada.";
    }

    function setMenu(){ 
        $cache = session()->get('USER_GROUP');
 
        $create  = $this->CreateCcMenu();

        // if($this->cache->get($cache)){
        //     $rs = json_decode($this->cache->get($cache));
        //     return $this->response->setStatusCode(200)->setJSON($rs);
        // }

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