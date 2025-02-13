<?php 
namespace App\Modules\WhatsappConversation\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\WhatsappConversation\Models\WhatsappConversationModel;


class WhatsappConversation extends \App\Controllers\BaseController
{

	function __construct()
	{
		$this->WhatsappConversationModel = new WhatsappConversationModel();
	}

	function index(){
		echo "WhatsappConversation"; 
	}

	function wa2wayListView()
	{
		$data['wa2way'] = $this->WhatsappConversationModel->setDataWa2Way();
        $data["template_wa"] = $this->Common_model->get_record_list("template_id value, template_name AS item", "cms_wa_template", "is_active=1", "template_name");

        return view('App\Modules\WhatsappConversation\Views\Wa2wayListView', $data); 
	}

	function conversationWaView()
	{
		$account_id = $this->input->getGet('account_id');
		if($account_id==''){
			echo "account_id EMPTY!";
			return false;
		}

		$data["data_blast"] = $this->Common_model->get_record_values(
		    "to_number, ref_id, template_name, message_id,message message_blast, created_time,
		    IF(is_approved = 'NEED APPROVAL', 
		        '<span class=\"metadata\" style=\"float: left;\"><span class=\"time\"><i class=\"bi bi-clock-history tooltip_all\" id=\"tooltip_all0\"></i></span></span>', 
		        IF(status = 'SENT' and status_data is null, '<span class=\"metadata\" style=\"float: left;\"><span class=\"time\"><i class=\"bi bi-check2 tooltip_all\" id=\"tooltip_all0\"></i></span></span>', '<span class=\"metadata\" style=\"float: left;\"><span class=\"time\"><i class=\"bi bi-check2-all tooltip_all\" id=\"tooltip_all0\"></i></span></span>')) AS approval_status", 
		    "wa_outbound", 
		    "ref_id = '".$account_id."' AND created_time >= CONCAT(CURDATE(), ' 00:00:00')"
		);

		if (empty($data["data_blast"])) {
			$data["data_blast"]["to_number"]="The template blast";
			$data["data_blast"]["ref_id"]="";
			$data["data_blast"]["template_name"]="has not been sent yet";
			$data["data_blast"]["message_id"]="";
			$data["data_blast"]["message_blast"]="";
			$data["data_blast"]["created_time"]="";
			$data["data_blast"]["approval_status"]="";
		}

		$data["data_inb"] =   $this->Common_model->get_record_values("messageId,ticket_id,messageText,receivedAt created_time,pickup_by,pickup_time", "wa_inbox", " ref_id='".$account_id."' and receivedAt >= concat(curdate(),' 00:00:00')");
		if (empty($data["data_inb"])) {
			$data["data_inb"]["messageId"]="";
			$data["data_inb"]["ticket_id"]="";
			$data["data_inb"]["messageText"]="";
			$data["data_inb"]["created_time"]="";
		}

		
		$builderWaConv = $this->db->table('wa_conversation_details');
		$builderWaConv->select("
			callbackData,
			pairedMessageId,
			if(
				direction = 'OUTB',concat('".base_url()."file_upload/wa_blast_conversation/',pairedMessageId),concat('https://democoll74.ecentrix.net/webhook_cms_ci4/api/file_upload/',pairedMessageId)
			)  link_attachment,
		    messageType,
		    messageText,
		    direction,
		    receivedAt AS created_time,
		    is_approved,
		    status_message,
		    status_json,
		    IF(direction = 'OUTB', ROW_NUMBER() OVER (PARTITION BY direction ORDER BY insert_time), NULL) AS row_num,
		    IF(
		        is_approved = 'NEED APPROVAL' AND direction = 'OUTB', 
		        CONCAT('<span class=\"metadata\" style=\"float: left;\"><span class=\"time\"><i class=\"bi bi-clock-history tooltip_all\" id=\"tooltip_all', ROW_NUMBER() OVER (PARTITION BY direction ORDER BY insert_time), '\" ></i></span></span>'), 
		        IF(
		            status_message = 'SENT' AND status_json IS NOT NULL AND direction = 'OUTB',
		            CONCAT('<span class=\"metadata\" style=\"float: left;\"><span class=\"time\"><i class=\"bi bi-check2-all tooltip_all\" id=\"tooltip_all', ROW_NUMBER() OVER (PARTITION BY direction ORDER BY insert_time), '\" ></i></span></span>'),
		            ''
		        )
		    ) AS approval_status
		");


		$builderWaConv->where('inbound_message_id', $data["data_inb"]['messageId']);
		$builderWaConv->where('insert_time >= concat(curdate() ," 00:00:00")');
		$builderWaConv->orderBy('insert_time','asc');
		$data["data_convertation"] = $builderWaConv->get()->getResultArray();

	

		$data["list_quick_template"] = array(''=>'[select data]')+$this->Common_model->get_record_list("a.id value, a.template_name AS item", "wa_quick_reply a", " is_active ='Y' ", " a.template_name");
		$data['CM_CARD_NMBR'] = $account_id;

		return view('App\Modules\WhatsappConversation\Views\ConversationWaView', $data);
	}

	function reply_wa(){
		date_default_timezone_set('Asia/Jakarta');
		$message_reply=$this->input->getPost('txt_wa');
		$to_number=$this->input->getPost('to_number');
		$cm_card_nmbr=$this->input->getPost('cm_card_nmbr');
		$inbound_message_id=$this->input->getPost('inbound_message_id');
		$file = $this->input->getFile('file');
		$now = date('Y-m-d H:i:s');

		$builder = $this->db->table("wa_filter_word a");
        $builder->select('a.word');
		$builder->where('a.is_active', '1');
		$builder->where('a.is_approved', 'APPROVED');        
		$listWord = $builder->get()->getResultArray();

		$arrlistWord=array();
		foreach ($listWord as $key => $value) {
			$arrlistWord[]=strtoupper($value['word']);
		}


		$arrMessage=explode(" ",$message_reply);
		foreach ($arrMessage as $key2 => $value2) {
			$arrMessage[$key2]=strtoupper($value2);
		}

		$result = array_intersect($arrMessage,$arrlistWord);

		if(empty($result)){

			//pengecekan ada file attchment tidak
			if (!empty($file)) { 
				$validationRule = [
		            'file' => [
		                'label' => 'File',
		                'rules' => 'uploaded[file]|ext_in[file,jpg,jpeg,png,mp4,webm,mp3,wav,txt,docx,doc,pdf,xlsx,xls,pptx,ppt]|max_size[file,2048]',
		            ],
		        ];

		        if (!$this->validate($validationRule)) {
					$rs = array('success' => false, 'message' => 'File must be of type jpg or jpeg or png or mp4 or webm or mp3 or wav or txt or docx or doc or pdf or xlsx or xls or pptx or ppt', 'data' => null);
					return $this->response->setStatusCode(200)->setJSON($rs);
		        } else {

		        	$originalName = $file->getClientName();
		        	$contentType =$file->getMimeType();
					$uploadPath = ROOTPATH . 'file_upload/wa_blast_conversation/';  // pastikan direktori ini ada dan memiliki izin tulis

					// Cek apakah direktori sudah ada, jika tidak, buat direktori
					if (!is_dir($uploadPath)) {
					    mkdir($uploadPath, 0755, true); // 0755 adalah izin untuk direktori, true untuk membuat direktori secara rekursif
					}

					$filePath = $uploadPath . $originalName;
					$file->move($uploadPath, $originalName);

					if (DIRECTORY_SEPARATOR == '\\') {
					    $filePath = str_replace('/', '\\', $filePath);
					}
					
					//print_r($file['originalMimeType']) ;
					switch ($contentType) {
					    case 'image/jpeg':
					        $ext = 'IMAGE';
					        break;
					    case 'image/png':
					        $ext = 'IMAGE';
					        break;
					    case 'application/pdf':
					        $ext = 'DOCUMENT';
					        break;
					    case 'application/msword':
					        $ext = 'DOCUMENT';
					        break;
					    case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
					        $ext = 'DOCUMENT';
					        break;
					    case 'application/vnd.ms-excel':
					        $ext = 'DOCUMENT';
					        break;
					    case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
					        $ext = 'DOCUMENT';
					        break;
					    case 'application/vnd.ms-powerpoint':
					        $ext = 'DOCUMENT';
					        break;
					    case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
					        $ext = 'DOCUMENT';
					        break;
					    case 'text/plain':
					        $ext = 'DOCUMENT';
					        break;
					    case 'video/mp4':
					        $ext = 'VIDEO';
					        break;
					    case 'video/x-matroska':
					        $ext = 'VIDEO';
					        break;
					    case 'audio/mpeg':
					        $ext = 'AUDIO';
					        break;
					    case 'audio/wav':
					        $ext = 'AUDIO';
					        break;
					    case 'audio/ogg':
					        $ext = 'AUDIO';
					        break;
					    default:
					    	$ext = 'TEXT';
					        //echo "Unsupported content type.\n";
					        exit;
					}

					$data = array(
						'messageId' => uuid(),
						'receivedAt' => $now,
						'fromNumber' => session()->get('USER_ID'),
						'toNumber' => $to_number,
						'messageType' => $ext,
						'pairedMessageId' => $originalName,
						'callbackData' => $contentType,
						'messageText' => $message_reply,
						'data_json' => json_encode($_REQUEST),
						'is_ticket' => 'N',
						'direction' => 'OUTB',
						'status_message' => 'QUEUE',
						'insert_time' => $now,
						'unit' => 'ecentrix',
						'inbound_message_id' => $inbound_message_id,
						//'is_approved' => 'NEED APPROVAL' //jika mau dikasih cheker maker
						'is_approved' => 'APPROVED'
					);

				}
			} else{ //untuk TEXT

				$data = array(
					'messageId' => uuid(),
					'receivedAt' => $now,
					'fromNumber' => session()->get('USER_ID'),
					'toNumber' => $to_number,
					'messageType' => 'TEXT',
					'pairedMessageId' => null,
					'callbackData' => null,
					'messageText' => $message_reply,
					'data_json' => json_encode($_REQUEST),
					'is_ticket' => 'N',
					'direction' => 'OUTB',
					'status_message' => 'QUEUE',
					'insert_time' => $now,
					'unit' => 'ecentrix',
					'inbound_message_id' => $inbound_message_id,
					//'is_approved' => 'NEED APPROVAL' //jika mau dikasih cheker maker
					'is_approved' => 'APPROVED'
				);
			}
			
			$return = $this->WhatsappConversationModel->send_reply_wa($data);
			$dataDetail['message'] = $data["messageText"];
			$updateData = $this->WhatsappConversationModel->updateLastMessage($cm_card_nmbr,$dataDetail,'AGENT');

			$data["message"] = $message_reply;
			$data['created_time'] = $now;
			$data['name']=csrf_token();
			$data['value']=csrf_hash();
		}else{
			$data['name']=csrf_token();
			$data['value']=csrf_hash();
			$rs = array('success' => false, 'message' => 'pesan mengandung kata-kata kurang pantas!', 'data' => $data);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
		
		if ($return) {
		

			$rs = array('success' => true, 'message' => 'Success', 'data' => $data , "updateData"=>$updateData);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null ,"updateData"=>null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

	function blast_template_by_agent(){
		date_default_timezone_set('Asia/Jakarta');
		$optTemplate=$this->input->getPost('template_id');
		$account_number=$this->input->getPost('account_no');
		$phone=$this->input->getPost('to');
		$templateType='TEXT';
		$templateAttchment='';
		$arr=explode("|",$this->input->getPost('param_value'));
		if ($templateType=='TEXT') {
	        $content=array(
		        'templateName' =>$optTemplate,
		        'templateData' => array(
		         	'body'=> array(
		            	"placeholders" => $arr
		        	)
		        ),
		        'language' => 'id'
	        );	
	        
        }else if($templateType=='IMAGE' ||$templateType=='VIDEO'){
        	$content=array(
		        'templateName' =>$optTemplate,
		        'templateData' => array(
		         	'body'=> array(
		            	"placeholders" => $arr
		        	),
		        	'header'=> array(
		            	"type" => $templateType,
		            	"mediaUrl" => base_url().'uploads/whatsapp/attachment/'.$templateAttchment
		        	)
		        ),
		        'language' => 'id'
	        );	
        } else if($templateType=='DOCUMENT'){
        	$content=array(
		        'templateName' =>$optTemplate,
		        'templateData' => array(
		         	'body'=> array(
		            	"placeholders" => $arr
		        	),
		        	'header'=> array(
		            	"type" => $templateType,
		            	"mediaUrl" => base_url().'uploads/whatsapp/attachment/'.$templateAttchment,
		            	"filename" => $templateAttchment
		        	)
		        ),
		        'language' => 'id'
	        );	
        }
        
        $data = array( 
        	'messages'	=> array(
        		array(
	        		"from" => $this->Common_model->get_record_value("phone", 'wa_devices', 'id="Ecentrix_demo"'),
	            	"to" => $phone,
	            	"messageId" => uuid(),
	            	"content" => $content
	        	)
        	)		
        );

        $data_template = json_encode($data);		


		$data = array(
			'id' => uuid(false), 
			'ref_id' => $account_number,
			'to_number' => $phone, 
			'template_name' => $optTemplate, 
			'template_data' => $data_template, 
			'json_data' => json_encode($_REQUEST), 
			'message' => $this->input->getPost('message'), 
			'created_by' => session()->get('USER_ID'),
			'created_time' => date('Y-m-d H:i:s'), 
			'is_approved' => 'NEED APPROVAL', 
			'tenant' => 'ecentrix'
		);

		$return = $this->WhatsappConversationModel->blast_template_by_agent($data);
		if ($return) {
			$updateData = $this->WhatsappConversationModel->updateLastMessage($account_number,$data,'AGENT');
			
			$rs = array('success' => true, 'message' => 'success! please check on approval menu', 'data' => $data ,'updateData'=>$updateData);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}else{
			$rs = array('success' => false, 'message' => 'failed', 'data' => null,'updateData'=>null);
			return $this->response->setStatusCode(200)->setJSON($rs);
		}
	}

	function get_quick_reply(){
		$optTemplate=$this->input->getGet('template_id');
		$data = $this->Common_model->get_record_value('message','wa_quick_reply','id="'.$optTemplate.'" ');
		$rs = array('success' => true, 'message' => 'Success', 'data' => $data);
		return $this->response->setStatusCode(200)->setJSON($rs);
	}

	function get_token(){
		$data['name']=csrf_token();
		$data['value']=csrf_hash();
		$rs = array('success' => true, 'message' => 'Success', 'data' => $data);
		return $this->response->setStatusCode(200)->setJSON($rs);
	}
    
    
}