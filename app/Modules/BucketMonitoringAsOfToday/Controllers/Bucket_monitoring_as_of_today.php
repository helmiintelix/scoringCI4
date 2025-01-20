<?php 
namespace App\Modules\BucketMonitoringAsOfToday\Controllers;
use App\Modules\BucketMonitoringAsOfToday\Models\Bucket_monitoring_as_of_today_model;
use CodeIgniter\Cookie\Cookie;
use DateTime;

class Bucket_monitoring_as_of_today extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->Bucket_monitoring_as_of_today_model = new Bucket_monitoring_as_of_today_model();
	}

	function index(){
		$data['classification'] = $this->input->getPost('classification');
		$builder = $this->db->table('cms_bucket');
		$builder->select('bucket_id as value , bucket_label name');
		$builder->where('flag', '1');
		$builder->where('is_active', '1');
		$builder->orderBy('flag', 'ASC');
		$result = $builder->get()->getResultArray();
		$bucket_data = array(''=>"--ALL--");
		foreach($result as $a=>$b){
			$bucket_data[$b['value']] = $b['name'];
		}
		$data['bucket_data'] = $bucket_data;
		$data['product_data'] = array(''=>'');
		return view('\App\Modules\BucketMonitoringAsOfToday\Views\Bucket_monitoring_as_of_today_view', $data);
	}
	function get_bucket_monitoring_as_of_today(){
		$dataInput['bucket_id'] = $this->input->getGet('bucket_name');
		// $dataInput['tgl_to'] = '';
		// print_r($dataInput);
		// exit();
		$data = $this->Bucket_monitoring_as_of_today_model->get_bucket_monitoring_as_of_today($dataInput);
	    if ($data) {
			$cacheKey = session()->get('USER_ID') . '_bucket_monitoring_as_of_today';
			$this->cache->delete($cacheKey);
			$this->cache->save($cacheKey, json_encode($data), env('TIMECACHE_1'));
	
			$rs = ['success' => true, 'message' => 'Success to apply filter', 'data' => $data];
		} else {
			$rs = ['success' => false, 'message' => 'failed', 'data' => null];
		}
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
}