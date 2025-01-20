<?php 
namespace App\Modules\DownloadAccountHandling\Controllers;
use App\Modules\DownloadAccountHandling\Models\Download_account_handling_model;
use CodeIgniter\Cookie\Cookie;
use DateTime;

class Download_account_handling extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Download_account_handling_model = new Download_account_handling_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		$data['petugas'] = array("" => "[SELECT DATA]")
		+ $this->Common_model->get_record_list("a.id AS value, concat(a.id,' - ',a.name) AS item", "cc_user a join cc_user_group b on a.group_id=b.id", "a.is_active = '1' and b.level = '4' ", "a.name")
		+ $this->Common_model->get_record_list("a.agency_id AS value, concat(a.agency_id,' - ',a.agency_name) AS item", "cms_agency a", "a.flag_tmp = '1'", "a.agency_name");
		
		$data['branch_list'] =  $this->Common_model->get_record_list("branch_id value, concat(branch_id,'-',branch_name) item", "cms_branch", "branch_id is not null", "branch_id");
		return view('\App\Modules\DownloadAccountHandling\Views\Download_account_handling_view', $data);
	}
	function get_crprd(){
		
		$dataInput['tgl'] = $this->input->getGet('tgl');
		$dataInput['petugas'] = $this->input->getGet('petugas');
		
		$data = $this->Download_account_handling_model->get_crprd_list($dataInput);
	    if ($data) {
			$cacheKey = session()->get('USER_ID') . '_crprd_list';
			$this->cache->delete($cacheKey);
			$this->cache->save($cacheKey, json_encode($data), env('TIMECACHE_1'));
	
			$rs = ['success' => true, 'message' => 'Success to apply filter', 'data' => $data];
		} else {
			$rs = ['success' => false, 'message' => 'failed', 'data' => null];
		}
		
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
	function send_to_email_form(){
		$data['cd_customers'] = $this->input->getGet('customer_id');
		$cd_customers = str_replace("|", "' ,'", $data["cd_customers"]);
		$res = preg_replace('/[^a-zA-Z0-9_ -]/s', ' ', $cd_customers);
		$array = explode(' ', $res);
		$number = array_filter($array);
		// dd($number);
		$data['sendto'] = array();
		$builder = $this->db->table('cc_user a');
		foreach ($number as $num) {
			$builder->select('b.agent_id AS item, a.email AS value, a.name');
			$builder->join('cpcrd_new b', 'a.id=b.AGENT_ID');
			$builder->where('a.is_active', '1');
			$builder->where("email is not null and email!='' ");
			$builder->where('cm_card_nmbr', $num);
			$builder->orderBY('a.name');
			$builder->distinct();
			$query = $builder->get();
			if ($query->getNumRows())
			{
				foreach ($query->getResult() as $row)
				{
					$data['sendto'][$row->value] = $row->item;
				}
			}
		}
		$data["cd_customer_list"]	= $this->Common_model->get_record_list("CM_CARD_NMBR AS value, CM_CARD_NMBR AS item", "cpcrd_new", "CM_CARD_NMBR IN ('" . $cd_customers . "')", "CM_CARD_NMBR");
		$data['user'] =  $this->Common_model->get_record_list("email value, concat(id,'-',name) AS item", "cc_user", "is_active='1' and (email is not null or email!='')", "name");
		return view('\App\Modules\DownloadAccountHandling\Views\Form_send_contract_master_view', $data);
	}
	function send_to_email(){
		$email = \Config\Services::email();
		$data['customers'] = $this->input->getPost('txt-cd-customers');
		$data['opt_users'] = $this->input->getPost("opt_users");
		$message = "Berikut terlampir Contract Master untuk No. Pinjaman:<br>" . $data['customers'];
		foreach ($data['opt_users'] as $value) {
			// var_dump($key);
			$email->setFrom("ecentrix@intelix.co.id", 'CMS'); 
			$email->setTo("helmi@intelix.co.id");
			$email->setSubject("Contract Master");
			$email->setMessage($message);
			$statusSend = $email->send();
			//die;

			if (!$statusSend) {
				// echo "Mailer Error: " . $email->print_debugger();
				$rs		= array("success" => false, "message" => "Sending Email Failed: ".$email->printDebugger());
			} else {
				// echo "Message sent!";
				$rs		= array("success" => true, "message" => "Sending Email Success");
			}

			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}
}