<?php

namespace App\Controllers;
use CodeIgniter\Model;

class PushNotif extends BaseController
{
    function getFirst(){
        $this->builder = $this->db->table('cms_notification a');
        $this->builder->select(
                    'a.notification_id, 
                    a.notification_type, 
					b.is_read,
					a.title,
					a.message, 
					a.data, 
					a.menu_id, 
					b.to, 
					c.socketID,
					d.id fromId,
					d.NAME fromName,
					e.menu_desc,
					e.url,
					a.created_time,
					0 total_unread'
                );
		
		$this->builder->join('cms_notification_destination b', 'a.notification_id=b.notification_id');
		$this->builder->join('cms_notification_registered c', 'b.to=c.user_id');
		$this->builder->join('cc_user d', 'd.id=a.created_by');
		$this->builder->join('cc_menu e', 'e.menu_id = a.menu_id');
		$this->builder->where('b.to', $user);
		$this->builder->orderBy('a.created_time', 'ASC');
        $query =  $this->builder->get();
        
        $res = $query->getResult();
        foreach ($res as $key=>$row) {
            $res[$key]['total_unread'] = 0;
        }
	
    }

    function getUserAccountNo(){

    	$no_hp = $this->input->getGet('no_hp'); // Mendapatkan nomor telepon dari POST
    	$messageId = $this->input->getGet('messageId'); // Mendapatkan nomor telepon dari POST
    	
		// Memastikan nomor telepon diawali dengan '62' dan menggantinya menjadi '0'
		if (substr($no_hp, 0, 2) === '62') {
		    $no_hp = '0' . substr($no_hp, 2); // Ganti '62' menjadi '0'
		}

		$list_no_hp = array();
		array_push($list_no_hp, $this->input->getGet('no_hp'));
		array_push($list_no_hp, $no_hp);
		sleep(5); // file belum siap makanya di delay dulu biar sudah insert ke db
		$this->builder = $this->db->table('wa_conversation_details a');
        $this->builder->select('a.messageId, a.pairedMessageId,a.callbackData');
		$this->builder->where('a.messageId', $messageId);
        $query3 =  $this->builder->get();
        $res3 = $query3->getResult();
        
        if (!empty($res3)) {
	        $pairedMessageId = $res3[0]->pairedMessageId; // Ambil pairedMessageId dari hasil query
	        $callbackData = $res3[0]->callbackData; 
	        
	    } else {
	        $pairedMessageId = null; // Jika tidak ada, set null
	        $callbackData = null; 
	    }

		
    	$this->builder = $this->db->table('cpcrd_new a');
        $this->builder->select('a.CR_HANDPHONE, a.CM_CARD_NMBR');
		$this->builder->whereIn('a.CR_HANDPHONE', $list_no_hp);
		
        $query =  $this->builder->get();
        
        $res = $query->getResult();
        foreach ($res as $key=>$row) {
           
	        $this->builder = $this->db->table('cc_user a');
	        $this->builder->select(
	            'a.id, 
	            a.contract_number_handling'
	        );
			$this->builder->where('a.contract_number_handling', $row->CM_CARD_NMBR);
			$this->builder->where('a.id', $this->input->getGet('USER_ID'));
	        $query2 =  $this->builder->get();   
	        $res2 = $query2->getResult();
	        if (!empty($res2)) {
	            // Mengambil contract_number_handling
	            $contract_number_handling = $res2[0]->contract_number_handling;

	            // Menyusun hasil yang berisi contract_number_handling dan pairedMessageId
	            $result = json_encode([
	                'contract_number_handling' => $contract_number_handling,
	                'pairedMessageId' => $pairedMessageId,
	                'callbackData' => $callbackData
	            ]);
	            return $result;
	        } else {
	            return false;
	        }
            
        }
    }

    function getInboundId(){

    	$no_hp = $this->input->getGet('no_hp'); // Mendapatkan nomor telepon dari POST
    	$account_no = $this->input->getGet('account_no'); // Mendapatkan nomor telepon dari POST
   		
		$this->builder = $this->db->table('wa_inbox a');
        $this->builder->select('a.messageId');
		$this->builder->where('a.fromNumber', $no_hp);
		$this->builder->where('a.is_ticket', 'N');
		$this->builder->where('a.unit', 'ecentrix');
		$this->builder->where("a.insert_time > concat(curdate(), ' 00:00:00')");
        $query3 =  $this->builder->get();
        $res3 = $query3->getResult();
        
        if (!empty($res3)) {
        	$result = json_encode([
                'inbound_message_id' => $res3[0]->messageId
            ]);
	    } else {
	    	$result = json_encode([
                'inbound_message_id' => null
            ]);
	    }

		return $result;
    }
}
?>