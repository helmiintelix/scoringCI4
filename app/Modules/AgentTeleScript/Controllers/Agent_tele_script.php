<?php 
namespace App\Modules\AgentTeleScript\Controllers;
use App\Modules\AgentTeleScript\Models\Agent_tele_script_model;
use CodeIgniter\Cookie\Cookie;



class Agent_tele_script extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Agent_tele_script_model = new Agent_tele_script_model();

	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		return view('\App\Modules\AgentTeleScript\Views\Agent_script_view', $data);
	}
    function get_agent_script_list(){
        $cache = session()->get('USER_ID').'_agent_script_list';
        if($this->cache->get($cache)){
			$data = json_decode($this->cache->get($cache));
			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}else{
			$data = $this->Agent_tele_script_model->get_agent_script_list();
			$this->cache->save($cache, json_encode($data), env('TIMECACHE_1')); 

			$rs = array('success' => true, 'message' => '', 'data' => $data);
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
    }
	function add_script(){
        return view('\App\Modules\AgentTeleScript\Views\Add_script_view');
    }
    function new_script(){
        $data['id'] = uuid(false);
        $data['subject'] = $this->input->getPost('subject');
        $data['script'] = $this->input->getPost('script_content');
        $data['created_by'] = session()->get('USER_ID');
        $data['created_time'] = date('Y-m-d H:i:s');
        $return = $this->Agent_tele_script_model->insert_agent_script($data);
        if ($return) {
            $cache = session()->get('USER_ID').'_agent_script_list';
            $this->cache->delete($cache);
            $rs = array('success' => true, 'message' => 'Success to save data', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }else{
            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }
    }
    function update_script(){
        $data['id'] = $this->input->getGet('id');
        $data['data'] = $this->Common_model->get_record_values("*", "acs_agent_script", "id = '" . $data["id"] . "'");
        return view('\App\Modules\AgentTeleScript\Views\Update_script_view', $data);
    }
    function update_agent_script(){
        $data['id'] = $this->input->getPost("txt-id");
        $data['subject'] = $this->input->getPost('subject');
        $data['script'] = $this->input->getPost('script_content');
        $data['created_by'] = session()->get('USER_ID');
        $data['created_time'] = date('Y-m-d H:i:s');
        $return = $this->Agent_tele_script_model->update_agent_script($data);
        if ($return) {
            $cache = session()->get('USER_ID').'_agent_script_list';
            $this->cache->delete($cache);
            $rs = array('success' => true, 'message' => 'Success to update data', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }else{
            $rs = array('success' => false, 'message' => 'failed', 'data' => null);
            return $this->response->setStatusCode(200)->setJSON($rs);
        }
    }

}