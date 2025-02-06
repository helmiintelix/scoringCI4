<?php 
namespace App\Modules\DialingSetup\Controllers;
use App\Modules\DialingSetup\Models\DialingSetupModel;
use CodeIgniter\Cookie\Cookie;
use Config\Database;

class DialingSetup extends \App\Controllers\BaseController
{
    
	function __construct()
	{
		$this->DialingSetupModel = new DialingSetupModel();
	}

    function index()
    {
        
        return view('\App\Modules\DialingSetup\Views\DialingModeCallStatusView');
    }

    function getDialingModeCallStatusList()
    {
        $data = $this->geDataDialing();
        $datamode = $this->getMasterModeDialing();

  

        $arrPhoneType = array(
            "" => "None",
            "0" => "None",
            "1" => "HP1",
            "2" => "HP2",
            "3" => "Home1",
            "4" => "Home2",
            "5" => "Office1",
            "6" => "Office2",
            "7" => "Bussiness1",
            "8" => "Bussiness2",

        );
        $raw = array();

        foreach ($data as $i => $result) {
            // for ($i=0;$i<count($result);$i++) {
            $format_auto_disconect				= $this->Common_model->get_record_value('description', 'acs_dialing_auto_disconect', "disconect='" . $result['auto_disconect'] . "'");

            $raw[$i]['id']								= $result['id'];
            // $raw[$i]['class_id']						= $result['class_id'];
            $raw[$i]['classification_name']		= $result['classification_name'];
            $raw[$i]['can_call']						= $result['can_call'];
            $raw[$i]['dialing_mode_id']					= $result['dialing_mode_id'];
            $raw[$i]['auto_disconect']					= $format_auto_disconect;
            $raw[$i]['max_ptp_days']					= $result['max_ptp_days'];
            $raw[$i]['formula_factor']					= $result['formula_factor'];
            $raw[$i]['call_timeout']					= $result['call_timeout'];
            $raw[$i]['try_again_after']					= $result['try_again_after'];
            $raw[$i]['max_call_attempt']				= $result['max_call_attempt'];
            $raw[$i]['call_priority_1']					= @$arrPhoneType[$result['call_priority_1']];
            $raw[$i]['call_priority_2']					= @$arrPhoneType[$result['call_priority_2']];
            $raw[$i]['call_priority_3']					= @$arrPhoneType[$result['call_priority_3']];
            $raw[$i]['call_priority_4']					= @$arrPhoneType[$result['call_priority_4']];
            $raw[$i]['call_priority_5']					= @$arrPhoneType[$result['call_priority_5']];
            $raw[$i]['call_priority_6']					= @$arrPhoneType[$result['call_priority_6']];
            $raw[$i]['call_priority_7']					= @$arrPhoneType[$result['call_priority_7']];
            $raw[$i]['call_priority_8']					= @$arrPhoneType[$result['call_priority_8']];


            if ($result['can_call'] == "1") {
                $raw[$i]['can_call'] = "<center><i class='bi bi-check-circle-fill text-success'></i></center>";
            } else {
                $raw[$i]['can_call'] = '<center><i class="bi bi-dash-circle-fill text-danger"></i></center>';
            }

            foreach ($datamode as $j => $res) {
                // for ($j=0;$j<count($res);$j++) {
                if ($result['dialing_mode_id'] == $res['dialing_mode_id']) {
                    $raw[$i]['' . $res['dialing_mode_name'] . ''] = $result['mode_' . ($j + 1)];
                    if ($raw[$i]['' . $res['dialing_mode_name'] . ''] == 1) {
                        $raw[$i]['' . $res['dialing_mode_name'] . ''] = "<center><i class='bi bi-check-circle-fill text-success'></i></center>";
                    }
                } else {
                    $raw[$i]['' . $res['dialing_mode_name'] . ''] = '<center><i class="bi bi-dash-circle-fill text-danger"></i></center>';
                }
            }
        }

        $rs = array('success' => true, 'message' => '', 'data' => $raw);
        return $this->response->setStatusCode(200)->setJSON($rs);
    }

    function geDataDialing()
	{
        $sql = "SELECT 
                    cls.class_id id,
                    cls.name classification_name,
                    dt.can_call,
                    dt.dialing_mode_id,
                    dt.mode_1,
                    dt.mode_2,
                    dt.mode_3,
                    dt.busy_call_back,
                    dt.left_message_call_back,
                    dt.auto_disconect,
                    dt.no_ans_call_back,
                    dt.daily_dial_limiter,
                    dt.max_ptp_days,							
                    dt.formula_factor,
                    dt.call_timeout,
                    dt.try_again_after,
                    dt.max_call_attempt,
                    dt.call_priority_1,
                    dt.call_priority_2,
                    dt.call_priority_3,
                    dt.call_priority_4,
                    dt.call_priority_5,
                    dt.call_priority_6,
                    dt.call_priority_7,
                    dt.call_priority_8,
                    dt.call_per_cycle
                FROM acs_class AS cls
                LEFT JOIN (
                    SELECT 
                        a.class_id,
                        c.name classification_name,
                        a.can_call,
                        a.dialing_mode_id,
                        CASE WHEN a.dialing_mode_id = 1 THEN 1 ELSE 0 END AS mode_1,
                        CASE WHEN a.dialing_mode_id = 2 THEN 1 ELSE 0 END AS mode_2,
                        CASE WHEN a.dialing_mode_id = 3 THEN 1 ELSE 0 END AS mode_3,
                        a.busy_call_back,
                        a.left_message_call_back,
                        a.auto_disconect,
                        a.no_ans_call_back,
                        a.daily_dial_limiter,
                        a.max_ptp_days,
                        a.formula_factor,
                        a.call_timeout,
                        a.try_again_after,
                        a.max_call_attempt,
                        a.call_priority_1,
                        a.call_priority_2,
                        a.call_priority_3,
                        a.call_priority_4,
                        a.call_priority_5,
                        a.call_priority_6,
                        a.call_priority_7,
                        a.call_priority_8,
                        a.call_per_cycle
                    FROM acs_dialing_mode_call_status AS a
                    LEFT JOIN acs_master_dialing_mode AS b ON a.dialing_mode_id = b.dialing_mode_id
                    LEFT JOIN acs_class AS c ON a.class_id = c.class_id 
                    GROUP BY a.class_id
                ) AS dt
                ON cls.class_id  = dt.class_id
                ORDER BY name ASC;
                ";
		$res	= $this->db->query($sql);
		$data	= $res->getResultArray();

		return $data;
	}

    function getMasterModeDialing(){
        $sql	= "SELECT * FROM acs_master_dialing_mode";
		$res	= $this->db->query($sql);
        $datamode	= $res->getResultArray();

		return $datamode;
    }

    function updateDialingModeCallStatus()
    {
        $class_id = $this->input->getGet("id");
     

        if($this->DialingSetupModel->found_class_dialing_mode_call_status($class_id))
        {
            // echo "masuk true";
            $data['dialing_mode'] = $this->Common_model->get_record_values("*", "acs_dialing_mode_call_status AS a  LEFT JOIN acs_class_work_assignment AS c ON a.class_id = c.class_mst_id
            LEFT JOIN acs_class AS b ON b.class_id=a.class_id", "a.class_id='" . $class_id . "'", "");


        }else{
            // echo "masuk false";
            $data['dialing_mode'] = $this->Common_model->get_record_values("*", "acs_class", "class_id='" . $class_id . "'", "");

            $data['dialing_mode'] = array(
                "class_id" => $class_id,
                "classification_name" =>$data['dialing_mode']["classification_name"],
                "can_call" => "",
                "dialing_mode_id" => "",
                "formula_factor" => "",
                "call_timeout" => "",
                "try_again_after" => "",
                "max_call_attempt" => "",
                "call_priority_1" => "",
                "call_priority_2" => "",
                "call_priority_3" => "",
                "call_priority_4" => "",
                "call_priority_5" => "",
                "call_priority_6" => "",
                "call_priority_7" => "",
                "call_priority_8" => "",
            );

          
        }

        $data['mode'] =	$this->Common_model->get_record_list("dialing_mode_id as value, dialing_mode_name AS item", "acs_master_dialing_mode", "", "dialing_mode_id ASC");
        $data['contract_process_time']	= $this->Common_model->get_record_list('process_time as value, description AS item ','acs_dialing_contract_process_time','id is not null','process_time asc');
        $data['busy_callback']	= $this->Common_model->get_record_list('call_back_in as value, description AS item ','acs_dialing_call_back','id is not null','call_back_in asc');
        $data['auto_disconect']	= $this->Common_model->get_record_list('disconect as value, description AS item ','acs_dialing_auto_disconect','id is not null','disconect asc');
        $data['dial_limiter']	= $this->Common_model->get_record_list('id as value, max_dial AS item ','acs_dialing_dial_limiter','id is not null','max_dial asc');
        $data['max_ptp_days']	= $this->Common_model->get_record_list('id as value, max_ptp AS item ','acs_dialing_max_ptp_days','id is not null','max_ptp asc');
        $data['max_visit']	= $this->Common_model->get_record_list('id as value, max_visit AS item ','acs_dialing_max_visit','id is not null','max_visit asc');
        $data['ecentrix_group']	= $this->Common_model->get_record_list('context as value, context_name AS item ','acs_predictive_group','context is not null','context_name asc');
     
     
        return view('\App\Modules\DialingSetup\Views\UpdateDialingModeCallView', $data);
    }

    function update_dialing_mode_call_status()
    {
    
        $class_id = $this->input->getPost('class_id');        
        
        try {
            if ($this->DialingSetupModel->found_class_dialing_mode_call_status($class_id)) {
                // echo 'one';
                $data = array(
                    "class_id"                 => $this->input->getPost('class_id'),
                    "dialing_mode_id"         => $this->input->getPost('dialing_select'),
                    "contract_process_time"   => $this->input->getPost('process_select'),
                    "busy_call_back"          => $this->input->getPost('busy_callback_select'),
                    "left_message_call_back"  => $this->input->getPost('left_message_callback_select'),
                    "auto_disconect"          => $this->input->getPost('autodisconect_select'),
                    "no_ans_call_back"        => $this->input->getPost('noanswer_callback_select'),
                    "daily_dial_limiter"      => $this->input->getPost('ddl_select'),
                    "max_ptp_days"            => $this->input->getPost('maxptp_select'),
                    "max_req_visit"           => $this->input->getPost('maxvisit_select'),
                    "can_call"                => $this->input->getPost('can_call_select'),
                    "call_timeout"            => $this->input->getPost('call_timeout'),
                    "formula_factor"          => $this->input->getPost('formula_factor'),
                    "max_call_attempt"        => $this->input->getPost('max_call_attempt'),
                    "ecentrix_group"          => $this->input->getPost('ecentrix_group'),
                    "call_priority_1"         => $this->input->getPost('call_priority_1'),
                    "call_priority_2"         => $this->input->getPost('call_priority_2'),
                    "call_priority_3"         => $this->input->getPost('call_priority_3'),
                    "call_priority_4"         => $this->input->getPost('call_priority_4'),
                    "call_priority_5"         => $this->input->getPost('call_priority_5'),
                    "call_priority_6"         => $this->input->getPost('call_priority_6'),
                    "call_priority_7"         => $this->input->getPost('call_priority_7'),
                    "call_priority_8"         => $this->input->getPost('call_priority_8'),
                    "call_priority_9"         => $this->input->getPost('call_priority_9'),
                    "call_priority_10"        => $this->input->getPost('call_priority_10'),
                    "call_priority_11"        => $this->input->getPost('call_priority_11'),
                    "call_priority_12"        => $this->input->getPost('call_priority_12'),
                    "call_priority_13"        => $this->input->getPost('call_priority_13'),
                    "call_priority_14"        => $this->input->getPost('call_priority_14'),
                    "call_priority_15"        => $this->input->getPost('call_priority_15'),
                    "call_priority_16"        => $this->input->getPost('call_priority_16'),
                    "call_priority_17"        => $this->input->getPost('call_priority_17'),
                    "call_priority_18"        => $this->input->getPost('call_priority_18')
                );
                

                $post['call_per_cycle'] = 8;
                
                $this->DialingSetupModel->update_dialing_mode_call_status_predictive($data);
                $response = array("success" => true, "message" => "Update Berhasil");
            } else {
                // echo 'two';

                $data = array(
                    "class_id"                 => $this->input->getPost('class_id'),
                    "dialing_mode_id"         => $this->input->getPost('dialing_select'),
                    "contract_process_time"   => $this->input->getPost('process_select'),
                    "busy_call_back"          => $this->input->getPost('busy_callback_select'),
                    "left_message_call_back"  => $this->input->getPost('left_message_callback_select'),
                    "auto_disconect"          => $this->input->getPost('autodisconect_select'),
                    "no_ans_call_back"        => $this->input->getPost('noanswer_callback_select'),
                    "daily_dial_limiter"      => $this->input->getPost('ddl_select'),
                    "max_ptp_days"            => $this->input->getPost('maxptp_select'),
                    "max_req_visit"           => $this->input->getPost('maxvisit_select'),
                    "can_call"                => $this->input->getPost('can_call_select'),
                    "call_timeout"            => $this->input->getPost('call_timeout'),
                    "formula_factor"          => $this->input->getPost('formula_factor'),
                    "max_call_attempt"        => $this->input->getPost('max_call_attempt'),
                    "ecentrix_group"          => $this->input->getPost('ecentrix_group'),
                    "call_priority_1"         => $this->input->getPost('call_priority_1'),
                    "call_priority_2"         => $this->input->getPost('call_priority_2'),
                    "call_priority_3"         => $this->input->getPost('call_priority_3'),
                    "call_priority_4"         => $this->input->getPost('call_priority_4'),
                    "call_priority_5"         => $this->input->getPost('call_priority_5'),
                    "call_priority_6"         => $this->input->getPost('call_priority_6'),
                    "call_priority_7"         => $this->input->getPost('call_priority_7'),
                    "call_priority_8"         => $this->input->getPost('call_priority_8'),
                    "call_priority_9"         => $this->input->getPost('call_priority_9'),
                    "call_priority_10"        => $this->input->getPost('call_priority_10'),
                    "call_priority_11"        => $this->input->getPost('call_priority_11'),
                    "call_priority_12"        => $this->input->getPost('call_priority_12'),
                    "call_priority_13"        => $this->input->getPost('call_priority_13'),
                    "call_priority_14"        => $this->input->getPost('call_priority_14'),
                    "call_priority_15"        => $this->input->getPost('call_priority_15'),
                    "call_priority_16"        => $this->input->getPost('call_priority_16'),
                    "call_priority_17"        => $this->input->getPost('call_priority_17'),
                    "call_priority_18"        => $this->input->getPost('call_priority_18')
                );
                
                $this->DialingSetupModel->insert_dialing_mode_call_status($data);
                $response = array("success" => true, "message" => "Insert Berhasil");
            }

            $this->Common_model->setEcxGroupByClass($class_id);

            // ====================================================================
            $ecentrix_group = $this->input->getPost('ecentrix_group');
            $dialing_status = '0';
            $maxCCR = 100;
            $queueThreshold = 3;
            $dialtimeout = 3;
            $call_timeout = $this->input->getPost('call_timeout');
            $rampDownValue = 10;
            $rampUpValue = 10;
            // ====================================================================

            if ($dialing_status == '1') {
                $PREDIAL_SETUP['pauseCall'] = 'TRUE';
                $PREDIAL_SETUP['startCall'] = 'FALSE';
            }
    
            if ($dialing_status == '0') {
                $PREDIAL_SETUP['pauseCall'] = 'FALSE';
                $PREDIAL_SETUP['startCall'] = 'TRUE';
            }
    
            $PREDIAL_SETUP['configGroup'] = $ecentrix_group;
            $PREDIAL_SETUP['ccrMax'] = $maxCCR;
            $PREDIAL_SETUP['queueThreshold'] = $queueThreshold;
            $PREDIAL_SETUP['dialTimeoutMax'] = $dialtimeout;
            $PREDIAL_SETUP['ringTimeoutMax'] = $call_timeout;
            $PREDIAL_SETUP['rampDownValue'] = $rampDownValue;
            $PREDIAL_SETUP['rampUpValue'] = $rampUpValue;
            $this->updateSetupAutodial($PREDIAL_SETUP);

        } catch (exception $e) {
            $response = array("success" => false, "message" => "Update Gagal", "error" => $e);
        }
        
        return $this->response->setStatusCode(200)->setJSON($response);
    }

    

    function updateSetupAutodial($data)
	{
		$PREDIAL_SERVER = env('ECX8_PREDIAL');


		if ($data['configGroup'] == '') {
            $this->Common_model->data_logging('classification', 'Add classification ', 'FAILED', 'configGroup EMPTY');
			return false;
		}
		if ($data['ccrMax'] == '') {
            $this->Common_model->data_logging('classification', 'Add classification ', 'FAILED', 'ccrMax EMPTY');
			return false;
		}
		if ($data['queueThreshold'] == '') {
            $this->Common_model->data_logging('classification', 'Add classification ', 'FAILED', 'queueThreshold EMPTY');
			return false;
		}
		if ($data['dialTimeoutMax'] == '') {
            $this->Common_model->data_logging('classification', 'Add classification ', 'FAILED', 'dialTimeoutMax EMPTY');
			return false;
		}
		if ($data['ringTimeoutMax'] == '') {
            $this->Common_model->data_logging('classification', 'Add classification ', 'FAILED', 'ringTimeoutMax EMPTY');
			return false;
		}
		if ($data['rampDownValue'] == '') {
            $this->Common_model->data_logging('classification', 'Add classification ', 'FAILED', 'rampDownValue EMPTY');
			return false;
		}
		if ($data['rampUpValue'] == '') {
            $this->Common_model->data_logging('classification', 'Add classification ', 'FAILED', 'rampUpValue EMPTY');
			return false;
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://' . $PREDIAL_SERVER . '/dialer_producer.php',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);

		$LOG = array('param' => $data, 'result' => $response);
        $this->Common_model->data_logging('classification', 'Add classification ', 'SUCCESS', json_encode($LOG));

		curl_close($curl);
	}

}