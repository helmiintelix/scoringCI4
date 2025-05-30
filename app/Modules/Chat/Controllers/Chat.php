<?php 
namespace App\Modules\Chat\Controllers;
use CodeIgniter\Cookie\Cookie;
use App\Modules\Chat\Models\ChatModel;
use MathPHP\Probability\Distribution\Continuous\F;

class Chat extends \App\Controllers\BaseController
{
	protected $ChatModel;

	function __construct()
	{
		$this->ChatModel = new ChatModel();
	}

    function index()
    {
        $data = [
            'title' => 'Chat Module',
            'message' => $this->ChatModel->test(),
        ];
        
        var_dump($data);
    }

    function chatListView()
    {
        $data = [
            'title' => 'Chat Module',
            'message' => $this->ChatModel->test(),
        ];

        $data['chatList'] = $this->ChatModel->getChatList(); 
        return view('App\Modules\Chat\Views\ChatListView', $data); 
        
    }

    function conversationChatView()
    {
        $data['userTarget'] = $this->request->getGet('userId', FILTER_SANITIZE_STRING);
        $data['userLogin'] = session()->get('USER_ID');
        $data['conversation'] = $this->ChatModel->getConversation($data['userTarget'], $data['userLogin']);
        
        return view('App\Modules\Chat\Views\ConversationChatView', $data); 
    }

    function sendMessage()
    {
        $data['messageId'] = uniqid('chat_');
        $data['messageFrom'] = session()->get('USER_ID', FILTER_SANITIZE_STRING); 
        $data['messageTo'] = $this->request->getPost('messageTo', FILTER_SANITIZE_STRING); // Default to 'admin' if not provided
        $data['messageType'] = 'text';
        $data['messageContent'] = $this->request->getPost('messageContent', FILTER_SANITIZE_STRING);
        $data['created_time'] = date('Y-m-d H:i:s');
        $data['sentAt'] = date('Y-m-d H:i:s');
        $data['created_by'] = session()->get('USER_ID');
        $data['conversationId'] = $this->request->getPost('conversationId', FILTER_SANITIZE_STRING) == '' ? uniqid('conv_') : $this->request->getPost('conversationId', FILTER_SANITIZE_STRING);

        $insertResult = $this->ChatModel->insertChat($data);
        if (!$insertResult) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to send message']);
        }
        $data['userName'] = $this->Common_model->get_record_value("name", "cc_user", "id=?", [$data['messageFrom']]);
        $data['socketIdTarget'] = $this->Common_model->get_record_value("socket_id", "cc_user", "id=?", [$data['messageTo']]);
        $data['seenAt'] = '';

        return $this->response->setJSON(['success' => true, 'message' => $data]);
    }

    function updateSeenAt()
    {
        $data['messageId'] = filter_var($this->request->getPost('messageId'), FILTER_SANITIZE_STRING);
        $data['status'] = filter_var($this->request->getPost('status'), FILTER_SANITIZE_STRING);
        if (empty($data['messageId'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid message ID']);
        }

        $this->ChatModel->updateSeenAt($data['messageId'], $data['status']);

        return $this->response->setJSON(['success' => true, 'message' => 'Seen at updated successfully']);
    }
}