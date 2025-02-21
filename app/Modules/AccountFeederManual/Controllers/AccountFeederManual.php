<?php 
namespace App\Modules\AccountFeederManual\Controllers;
use App\Modules\AccountFeederManual\Models\AccountFeederManualModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use CodeIgniter\Cookie\Cookie;
use DateTime;

class AccountFeederManual extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->AccountFeederManualModel = new AccountFeederManualModel();
	}

    function getAccount(){
        $agentId = $this->input->getGet('agentId');
        $classId = $this->input->getGet('classId');

        if($agentId==''){
            $rs = array('status'=>false , 'message'=>'AgentID empty!','data'=>array());
            return $this->response->setStatusCode(400)->setJSON($rs);
        }
        if($classId==''){
            $rs = array('status'=>false , 'message'=>'classId empty!','data'=>array());
            return $this->response->setStatusCode(400)->setJSON($rs);
        }

        $total = $db->table('acs_user')
                    ->where('contract_number_handling IS NULL')
                    ->orWhere('contract_number_handling', '')
                    ->countAllResults();
        if($total==0){
            // $res = $this->processingAccount($classId , $agentId);
            $res = $this->processingAccountLockRow($classId , $agentId);
        }else{
            $sql = "SELECT 
                        a.contract_number, 
                        a.customer_id, 
                        a.class_id 
                    FROM acs_predictive_queue a
                    JOIN acs_user b on contract_number=b.contract_number_handling
                    WHERE 
                        b.id = ? and a.class_id = ? ";
            $rs = $this->db->query($sql,array($agentId,$classId))->getResultArray();

            if(isset($rs[0]['contract_number'])){
                $dataContract = array();
                foreach ($rs as $key => $value) {
                    $dataContract['contract_number'] = $value['contract_number'];
                    $dataContract['customer_id'] = $value['customer_id'];
                    $dataContract['class_id'] = $value['class_id'];
                }
                $res = array('status'=>true , 'message'=>'account from account handling','data'=>$dataContract);
            }else{
                // $res = $this->processingAccount($classId , $agentId);
                $data = $this->processingAccountLockRow($classId , $agentId);
                $res = array('status'=>true , 'message'=>'account from accfeeder','data'=>$data);
            }
        }


        return $this->response->setStatusCode(200)->setJSON($res);
    }

 
    //locking table
    function processingAccount($classId , $agentId){
        $dataContract = array();
        $sql = "LOCK TABLES asc_predictive_queue WRITE;";
        $this->db->query($sql);

        $count = $this->db->table('asc_predictive_queue')
        ->where('status', '0')
        ->where('class', $classId)
        ->countAllResults();

        if($count==0){
            $rs = array('status'=>false , 'message'=>'account in class empty!');
            return $this->response->setStatusCode(200)->setJSON($rs);
        }


        $query = $this->db->table('acs_predictive_queue')
        ->select('contract_number, customer_id, class_id')
        ->where('class_id', $classId)
        ->where('status', '0')
        ->orderBy('id', 'asc')
        ->limit(1);

        $rs = $query->getResultArray();
        foreach ($rs as $key => $value) {
           $this->db->table('acs_predictive_queue')->where('contract_number', $value['contract_number'])->update(array('status'=>'2'));
           $this->db->table('acs_user')->where('id', $agentId)->update(array('contract_number_handling'=>$value['contract_number']));

           $dataContract['contract_number'] = $value['contract_number'];
           $dataContract['customer_id'] = $value['customer_id'];
           $dataContract['class_id'] = $value['class_id'];
        }

        $this->db->query('UNLOCK TABLES;');

        return $dataContract;
    }

    //locking row
    function processingAccountLockRow($classId , $agentId){
        $dataContract = array();
        $sql = "START TRANSACTION;";
        $this->db->query($sql);

        $count = $this->db->table('asc_predictive_queue')
        ->where('status', '0')
        ->where('class', $classId)
        ->countAllResults();

        if($count==0){
            $rs = array('status'=>false , 'message'=>'account in class empty!');
            return $this->response->setStatusCode(200)->setJSON($rs);
        }


        $query = $this->db->table('acs_predictive_queue')
        ->select('id, contract_number, customer_id, class_id')
        ->where('class_id', $classId)
        ->where('status', '0')
        ->orderBy('id', 'asc')
        ->limit(1);

        $rs = $query->getResultArray();
        foreach ($rs as $key => $value) {
           $res = $this->db->table('acs_predictive_queue')
           ->where('id', $value['id'])
           ->where('status', 0)
           ->update(array('status'=>'2'));

           $dataContract['contract_number'] = $value['contract_number'];
           $dataContract['customer_id'] = $value['customer_id'];
           $dataContract['class_id'] = $value['class_id'];
        }

        $this->db->query('COMMIT;');

        if($res->affectedRows()==0) {
            $this->processingAccountLockRow($classId , $agentId);            
        }else{
            $this->db->table('acs_user')->where('id', $agentId)->update(array('contract_number_handling'=>$dataContract['contract_number']));
        }

        return $dataContract;
    }
}