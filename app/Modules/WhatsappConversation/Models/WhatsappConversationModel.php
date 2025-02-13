<?php
namespace App\Modules\WhatsappConversation\models;
use CodeIgniter\Model;

Class WhatsappConversationModel Extends Model 
{
	function setDataWa2Way(){
        $table = $this->db->table('cpcrd_new');

        $rs = $table->select('CM_CARD_NMBR, CR_NAME_1, CM_OS_BALANCE, DPD , CR_HANDPHONE, CM_TYPE, lastAttempt, lastMessage, lastMessageFrom')
                ->join('wa_list_conversation','CM_CARD_NMBR=contractNumber','left')
                ->where('AGENT_ID is not null')
                ->orderBy('lastAttempt','DESC')
                ->get()
                ;
        foreach ($rs as $key => $value) {
            // $sql = $this->Common_model->get_record_value('');
            // $rs[$key]['last_contact'] = $this->Common_model->get_record_value('');
        }

        return $data = $rs->getResultArray();
    }

    function send_reply_wa($data)
	{
        $this->builder = $this->db->table('wa_conversation_details');
		$return = $this->builder->insert($data);
		return $return;
	}
	function blast_template_by_agent($data)
	{
        $this->builder = $this->db->table('wa_outbound');
		$return = $this->builder->insert($data);
		return $return;
	}

    function updateLastMessage($cm_card_nmbr,$data, $from){
        

        $builder = $this->db->table('wa_list_conversation');
        $count = $builder->where('contractNumber',$cm_card_nmbr)->get();
        $jumlah = $count->getNumRows();
        if($jumlah>0){
          
            $dataUpdate['lastAttempt'] = date('Y-m-d H:i:s');
            $dataUpdate['lastMessage'] = $data['message'];
            $dataUpdate['lastMessageFrom'] = $from;
                        
            $builder->where('contractNumber',$cm_card_nmbr)->update($dataUpdate);
            
            $dataUpdate['contractNumber'] = $cm_card_nmbr;
        }else{
            $dataUpdate = array(
                    'contractNumber'=>$cm_card_nmbr,
                    'lastAttempt'=>date('Y-m-d H:i:s'),
                    'lastMessage'=>$data['message'],
                    'lastMessageFrom' =>$from,
                    'createdTime' =>date('Y-m-d H:i:s')
            );
            $builder->insert( $dataUpdate);
        }

        return $dataUpdate;
    }

    
}