<?php 
namespace App\Modules\SetupBroadcastMessage\Controllers;
use CodeIgniter\Cookie\Cookie;

class Setup_broadcast_message extends \App\Controllers\BaseController
{
	function index(){
		$data['classification'] = $this->input->getPost('classification');
		$data['broadcastMsg']	= $this->Common_model->get_record_value("value", "aav_configuration", "parameter = 'BROADCAST' and id='BROADCAST' ");
		return view('\App\Modules\SetupBroadcastMessage\Views\Setup_broadcast_message_view', $data);
	}
	function save_broadcast_message_setup(){
		$data['message'] = $this->input->getPost('message');

		$builder = $this->db->table('aav_configuration');
		$builder->where('parameter', 'BROADCAST');
		$builder->where('id', 'BROADCAST');
		$builder->set('value', $data['message']);
		$return = $builder->update();

		if ($return) {
			$newCsrfToken = csrf_hash();
			$rs = array('success' => true, 'message' => 'Success to update broadcast message', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON(array_merge($rs, ['newCsrfToken' => $newCsrfToken]));
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

}