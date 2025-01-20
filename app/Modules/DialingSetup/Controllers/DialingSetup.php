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
            $raw[$i]['classification_name']				= $result['classification_name'];
            $raw[$i]['can_call']						= $result['can_call'];
            $raw[$i]['dialing_mode_id']					= $result['dialing_mode_id'];
            $raw[$i]['auto_disconect']					= $format_auto_disconect;
            $raw[$i]['max_ptp_days']					= $result['max_ptp_days'];
            $raw[$i]['formula_factor']					= $result['formula_factor'];
            $raw[$i]['call_timeout']					= $result['call_timeout'];
            $raw[$i]['try_again_after']					= $result['try_again_after'];
            $raw[$i]['max_call_attempt']				= $result['max_call_attempt'];
            $raw[$i]['call_priority_1']					= $arrPhoneType[$result['call_priority_1']];
            $raw[$i]['call_priority_2']					= $arrPhoneType[$result['call_priority_2']];
            $raw[$i]['call_priority_3']					= $arrPhoneType[$result['call_priority_3']];
            $raw[$i]['call_priority_4']					= $arrPhoneType[$result['call_priority_4']];
            $raw[$i]['call_priority_5']					= $arrPhoneType[$result['call_priority_5']];
            $raw[$i]['call_priority_6']					= $arrPhoneType[$result['call_priority_6']];
            $raw[$i]['call_priority_7']					= $arrPhoneType[$result['call_priority_7']];
            $raw[$i]['call_priority_8']					= $arrPhoneType[$result['call_priority_8']];


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
                    cls.classification_id AS id,
                    cls.classification_name,
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
                FROM cms_classification AS cls
                LEFT JOIN (
                    SELECT 
                        a.class_id,
                        c.classification_name,
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
                    LEFT JOIN cms_classification AS c ON a.class_id = c.classification_id
                    GROUP BY a.class_id
                ) AS dt
                ON cls.classification_id = dt.class_id
                ORDER BY classification_name ASC;
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
            LEFT JOIN cms_classification AS b ON b.classification_id =a.class_id", "a.class_id='" . $class_id . "'", "");
        }else{
            // echo "masuk false";
            $data['dialing_mode'] = $this->Common_model->get_record_values("*", "cms_classification", "classification_id='" . $class_id . "'", "");

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

        return view('\App\Modules\DialingSetup\Views\UpdateDialingModeCallView', $data);
    }

    function update_dialing_mode_call_status()
		{
			$post = array();
			
			try {
				if ($this->DialingSetupModel->found_class_dialing_mode_call_status($this->input->getPost('class_id'))) {
					// echo 'one';
					$data = array(
						"class_id"								=> $this->input->getPost('class_id'),
						"dialing_mode_id"					=> $this->input->getPost('dialing_select'),
						"formula_factor"		=> $this->input->getPost('formula_factor'),
						"call_timeout"					=> $this->input->getPost('call_timeout'),
						//"left_message_call_back"	=> $getPost['left_message_callback_select'],
						"try_again_after"					=> $this->input->getPost('try_again_after'),
						"max_call_attempt"				=> $this->input->getPost('max_call_attempt'),
						"call_per_cycle"			=>  8, //$getPost['call_per_cycle'],
						"call_priority_1"						=> $this->input->getPost('call_priority_1'),
						"call_priority_2"						=> $this->input->getPost('call_priority_2'),
						"call_priority_3"						=> $this->input->getPost('call_priority_3'),
						"call_priority_4"						=> $this->input->getPost('call_priority_4'),
						"call_priority_5"						=> $this->input->getPost('call_priority_5'),
						"call_priority_6"						=> $this->input->getPost('call_priority_6'),
						"call_priority_7"						=> $this->input->getPost('call_priority_7'),
						"call_priority_8"						=> $this->input->getPost('call_priority_8'),
						"can_call"								=> $this->input->getPost('can_call_select')
					);
					$post['call_per_cycle'] = 8;
					
					$this->DialingSetupModel->update_dialing_mode_call_status_predictive($data);
					$response = array("success" => true, "message" => "Update Berhasil");
				} else {
					// echo 'two';

					$data = array(
						"class_id"								=> $this->input->getPost('class_id'),
						"dialing_mode_id"					=> $this->input->getPost('dialing_select'),
						"formula_factor"		=> $this->input->getPost('formula_factor'),
						"call_timeout"					=> $this->input->getPost('call_timeout'),
						"try_again_after"					=> $this->input->getPost('try_again_after'),
						"max_call_attempt"				=> $this->input->getPost('max_call_attempt'),
						"call_priority_1"						=> $this->input->getPost('call_priority_1'),
						"call_priority_2"						=> $this->input->getPost('call_priority_2'),
						"call_priority_3"						=> $this->input->getPost('call_priority_3'),
						"call_priority_4"						=> $this->input->getPost('call_priority_4'),
						"call_priority_5"						=> $this->input->getPost('call_priority_5'),
						"call_priority_6"						=> $this->input->getPost('call_priority_6'),
						"call_priority_7"						=> $this->input->getPost('call_priority_7'),
						"call_priority_8"						=> $this->input->getPost('call_priority_8'),
						"can_call"								=> $this->input->getPost('can_call_select')
					);
					$this->DialingSetupModel->insert_dialing_mode_call_status($data);
					$response = array("success" => true, "message" => "Insert Berhasil");
				}
			} catch (exception $e) {
				$response = array("success" => false, "message" => "Update Gagal", "error" => $e);
			}
			
			return $this->response->setStatusCode(200)->setJSON($response);
		}

}