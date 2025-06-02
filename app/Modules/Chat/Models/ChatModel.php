<?php
namespace App\Modules\Chat\models;
use CodeIgniter\Model;
use App\Models\Common_model;


Class ChatModel Extends Model 
{
      protected $Common_model;
    function __construct(){
        parent::__construct();
        $this->Common_model = new Common_model();
    }
    function test(){
        return "Hello from ChatModel!";
    }

    function insertChat($data)
    {
        $result = $this->db->table('cms_chat_conversation')->insert($data);
       
        return $result; // Simulate successful insert
    }

    function getChatList()
    {

        $level_group = $this->Common_model->get_record_value("level_group", "cc_user_group", "id=?", [session()->get('USER_GROUP')]);
        $userId = session()->get('USER_ID');
        // var_dump("level_group: ", session()->get('USER_GROUP'), $level_group, $this->db->getLastQuery());
        switch ($level_group) {
            case 'TEAM_LEADER':    
                $builder = $this->db->table('cms_team t');
                $builder->select('t.agent_list');
                $builder->where('t.team_leader', session()->get('USER_ID'));
                $query = $builder->get();
                $result = $query->getResultArray();

                $agentLists = array_column($result, 'agent_list');
                $userIds = [];

                foreach ($agentLists as $list) {
                    // Pisahkan berdasarkan '|'
                    $ids = explode('|', $list);
                    foreach ($ids as $id) {
                        $id = trim($id);
                        if ($id !== '') {
                            $userIds[] = $id;
                        }
                    }
                }
                // var_dump("userIds: ", $userIds, $this->db->getLastQuery());

                // Hilangkan duplikat
                $userIds = array_unique($userIds);
                $whereUser = $userIds;
                break;

            case 'TELECOLL':
                $builder = $this->db->table('cms_team t');
                $builder->select('t.team_leader');
                $builder->like('t.agent_list', session()->get('USER_ID'));
                $query = $builder->get();
                $result = $query->getResultArray();
                $whereUser = array_column($result, 'team_leader');
                break;

            default:
                $whereUser = [''];
                break;
        }
        
        // var_dump("whereUser: ", $whereUser);
        // Subquery: pesan terakhir antara TL dan masing-masing agent di bawahnya
        $subQuery = $this->db->table('cms_chat_conversation')
            ->select('
                IF(messageFrom = '.$this->db->escape($userId).', messageTo, messageFrom) as agent_id,
                MAX(sentAt) as latest
            ')
            ->groupStart()
                ->where('messageFrom', $userId)
                ->whereIn('messageTo', $whereUser)
            ->groupEnd()
            ->orGroupStart()
                ->whereIn('messageFrom', $whereUser)
                ->where('messageTo', $userId)
            ->groupEnd()
            ->groupBy('agent_id')
            ->getCompiledSelect();

        // Join ke user dan ambil pesan terakhir (jika ada)
        $builder = $this->db->table('cc_user u');
        $builder->select('
            u.id AS userId,
            u.name AS userName,
            c.messageContent,
            c.seenAt AS seenAt,
            c.messageTo,
            c.messageFrom,
            c.sentAt AS createdTime
        ');
        $builder->whereIn('u.id', $whereUser);
        $builder->join("($subQuery) conv", 'u.id = conv.agent_id', 'left');
        $builder->join('cms_chat_conversation c', 'c.sentAt = conv.latest AND ((c.messageFrom = '.$this->db->escape($userId).' AND c.messageTo = u.id) OR (c.messageTo = '.$this->db->escape($userId).' AND c.messageFrom = u.id))', 'left');
        $builder->orderBy('c.sentAt', 'DESC');
        $builder->groupBy('u.id');

        $query = $builder->get();
        $result = $query->getResultArray();

// var_dump("result: ", $result, $this->db->getLastQuery());


        return $result;
    }
    
    function getConversation($userTarget, $userLogin)
    {
        $builder = $this->db->table('cms_chat_conversation');

        $builder->groupStart()
            ->where('messageFrom', $userLogin)
            ->where('messageTo', $userTarget)
        ->groupEnd()
        ->orGroupStart()
            ->where('messageFrom', $userTarget)
            ->where('messageTo', $userLogin)
        ->groupEnd();

        $builder->orderBy('sentAt', 'ASC');

        $query = $builder->get();
        $chatHistory = $query->getResultArray();

        $idsToUpdate = [];
        foreach ($chatHistory as $chat) {
            if ($chat['messageTo'] == $userLogin && is_null($chat['seenAt'])) {
                $idsToUpdate[] = $chat['messageId'];
            }
        }
        if (!empty($idsToUpdate)) {
            $this->updateSeenAt($idsToUpdate, 'READ');
        }


        return $chatHistory;
    }

    function updateSeenAt($messageId, $status)
    {
        $builder = $this->db->table('cms_chat_conversation');
        if (is_array($messageId)) {
            $builder->whereIn('messageId', $messageId);
        } else {
            $builder->where('messageId', $messageId);
        }

        if ($status === 'SENT') {
            $builder->update(['status_message' => 'SENT', 'sentAt' => date('Y-m-d H:i:s')]);
        } 
        elseif ($status === 'READ') {
            $builder->update([
                'status_message' => 'READ',
                'seenAt' => date('Y-m-d H:i:s')
            ]);
        } else {
            $builder->update(['seenAt' => date('Y-m-d H:i:s'), 'sentAt' => date('Y-m-d H:i:s')]);
        }
    }
}