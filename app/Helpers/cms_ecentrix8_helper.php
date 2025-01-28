<?php
    use CodeIgniter\Config\Services;
    use CodeIgniter\Database\BaseConnection;
    global $SESSION,$DB;
  
    
    if (!function_exists('getSessionInstance')) {
        function getSessionInstance() {
            // Menggunakan service('session') untuk mendapatkan instance Session
            return service('session');
        }
    }

    if (!function_exists('getDatabaseInstance')) {
        function getDatabaseInstance(): BaseConnection {
            // Memuat instance database menggunakan service
            return \Config\Database::connect();
        }
    }
    $SESSION = getSessionInstance();
    $DB = getDatabaseInstance();

    function get_spv_token(){
        global $SESSION;
        

        // ambil token spv 
        $token = $_SESSION['SPV_TOKEN'];
        $expired = $_SESSION['SPV_TOKEN_EXPIRE'];
        
        if ($expired) {
            if ($expired > date('Y-m-d H:i:s')) {
                if ($token) {
                    return $token;
                }
            }
        }
        
        
        $curl = curl_init();
        $param = array(
            "client_id" => $_SESSION['ECX8_CLIENT_ID'],
            "supervisor_id" => $_SESSION['USER_ID'],
            "auth_key" => env('ECX8_AUTH_KEY')
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://'.env('APP_URL').'/ecentrix/auth/supervisor.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($param),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application\\/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        try {
            $response  = json_decode($response,true);
            $access_token = $response['access_token'];
            $expire_time = $response['expire_time'];
            
            $arr1 = explode("T",$expire_time);
            $arr2 = explode(".",$arr1[1]);
            $expire_time = $arr1[0] . " " . $arr2[0];
            
            $SESSION->set('SPV_TOKEN', $access_token);
            $SESSION->set('SPV_TOKEN_EXPIRE', $expire_time);
            
            return $access_token;
        } catch (Exception $e) {
            return false;
        }


    }

    function get_admin_token(){
        global $SESSION;
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://'.env('APP_URL').'/ecentrix/auth/token_generator.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        try {
            $res = json_decode($response,true);
            if (isset($res['X-Token'])) {
                $SESSION->set('ADMIN_TOKEN',$res['X-Token']);
            }
        } catch (Exception $e) {
            return false;
        }

    }

    function add_agent($param = array()){
        global $SESSION;

        if(is_array($param)){
             if(!isset($param['id'])){
                 return false;
             }
             if(!isset($param['group_id'])){
                 return false;
             }
        }else{
            return false;
        }
 
        get_admin_token();

        $HEADER = array(
         'X-Token: '. $_SESSION['ADMIN_TOKEN'],
         'Content-Type: text/plain'
        );
 
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://'.env('ECX8_URL').':8180/api/agent/add',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($param),
            CURLOPT_HTTPHEADER => $HEADER,
        ));

        $response = curl_exec($curl);

        log_api_ecx8('add_agent',$param,  $response);

        curl_close($curl);
    }

    function update_agent($agent_id='',$param=array()){
        global $SESSION;

       if($agent_id==''){
            return false;
       }
       if(is_array($param)){
            if(!isset($param['group_id'])){
                return false;
            }
            if(!isset($param['name'])){
                return false;
            }
       }else{
            return false;
        }

        get_admin_token();

       $HEADER = array(
            'X-Token: '. $_SESSION['ADMIN_TOKEN'],
            'Content-Type: text/plain'
       );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://'.env('ECX8_URL').':8180/api/agent/update/'.$agent_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($param),
            CURLOPT_HTTPHEADER => $HEADER,
        ));

        $response = curl_exec($curl);
        
        log_api_ecx8('update_agent',$param,  $response);

        curl_close($curl);
    }

    function update_agent_groupid($agent_id='',$groupid=''){
        global $SESSION;
        
        if($agent_id==''){
             return false;
        }
        if($groupid==''){
             return false;
        }
        get_admin_token();

        $curl = curl_init();

        $HEADER = array(
            'X-Token: '. $_SESSION['ADMIN_TOKEN']
        );
    
        $param = 'https://'.env('ECX8_URL').':8180/api/agent/update/'.$agent_id.'/group/'.$groupid;        
        curl_setopt_array($curl, array(
            CURLOPT_URL =>  $param,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $HEADER,
        ));
        
        $response = curl_exec($curl);

        log_api_ecx8('update_agent_groupid',$param,  $response);

        curl_close($curl);
        
    }

    function delete_agent($agent_id=''){
        global $SESSION;
        
        if($agent_id==''){
             return false;
        }
        get_admin_token();

        $curl = curl_init();

        $HEADER = array(
            'X-Token: '. $_SESSION['ADMIN_TOKEN']
        );
    
        $param = 'https://'.env('ECX8_URL').':8180/api/agent/delete/'.$agent_id;

        curl_setopt_array($curl, array(
          CURLOPT_URL => $param,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => $HEADER,
        ));
        
        $response = curl_exec($curl);

        log_api_ecx8('delete_agent',$param,  $response);
        
        curl_close($curl);
        
    }

    function add_supervisor($param = array()){
        global $SESSION;

        /*
            {
                "id" : "id",
                "pbx_id" : "pbx_id",
                "name" : "name",
                "all_group" : true/false
            }
        */
        if(is_array($param)){
             if(!isset($param['id'])){ //string
                 return false;
             }

             if(!isset($param['pbx_id'])){ // boolean true/false
                return false;
             }
             
             if(!isset($param['name'])){ // string
                return false;
            }
        }else{
            return false;
        }
 
        get_admin_token();

        $HEADER = array(
         'X-Token: '. $_SESSION['ADMIN_TOKEN'],
         'Content-Type: application/json'
        );
 
 
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://'.env('ECX8_URL').':8180/api/supervisor/add',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($param),
            CURLOPT_HTTPHEADER => $HEADER,
        ));

        $response = curl_exec($curl);

        log_api_ecx8('add_supervisor',$param,  $response);

        curl_close($curl);
    }

    function update_supervisor($user_id,$param = array()){
        global $SESSION;

        /*
            {
                "id" : "id",
                "pbx_id" : "pbx",
                "name" : "name",
                "all_group" : false,
                "group_ids" : ["ecentrix-inbound"]
        
            }
        */

        if($user_id==''){
            return false;
        }
        if(is_array($param)){
            if(!isset($param['id'])){ //string
                 return false;
            }
            if(!isset($param['pbx_id'])){ //  string
                return false;
            }
            if(!isset($param['name'])){ // string
                return false;
            }
            if(!isset($param['all_group'])){ //boolean true/false
                return false;
            }
        }else{
            return false;
        }
 
        get_admin_token();

        $HEADER = array(
         'X-Token: '. $_SESSION['ADMIN_TOKEN'],
         'Content-Type: application/json'
        );
 
 
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://'.env('ECX8_URL').':8180/api/supervisor/update/'.$user_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($param),
            CURLOPT_HTTPHEADER => $HEADER,
        ));

        $response = curl_exec($curl);

        log_api_ecx8('add_supervisor',$param,  $response);

        curl_close($curl);
    }

    function list_group_context($pbx_id = 'pbx'){
        global $SESSION;
      
 
        get_admin_token();

        $HEADER = array(
            'X-Token: '. $_SESSION['ADMIN_TOKEN']
        );
 
 
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://'.env('ECX8_URL').':8180//api/agent/group/list/'.$pbx_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $HEADER,
        ));

        $response = curl_exec($curl);

        log_api_ecx8('group_list',$pbx_id,  $response);

        curl_close($curl);

        return $response;
    }
    
    function delete_supervisor($user_id=''){
        global $SESSION;
        
        if($user_id==''){
             return false;
        }
        get_admin_token();

        $curl = curl_init();

        $HEADER = array(
            'X-Token: '. $_SESSION['ADMIN_TOKEN']
        );
    
        $param = 'https://'.env('ECX8_URL').':8180/api/supervisor/delete/'.$user_id;

        curl_setopt_array($curl, array(
          CURLOPT_URL => $param,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => $HEADER,
        ));
        
        $response = curl_exec($curl);

        log_api_ecx8('delete_agent',$param,  $response);
        
        curl_close($curl);
        
    }

    function log_api_ecx8($action,$result,$param){
        global $SESSION,$DB;

        $desc['param'] = $param;
        $desc['result'] = $param;

        $data['id'] = uuid(false);
        $data['module'] = 'API_ECX8';
        $data['action'] = $action;
        $data['result'] = 'SENDAPI';
        $data['description'] = json_encode($desc);
        $data['created_by'] = 'SYSTEM';
        $data['created_time'] = date('Y-m-d H:i:s');
        $DB->table('cc_app_log')->insert($data);
    }

?>
