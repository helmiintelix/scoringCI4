<style>
    .chat-section {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    #chatForm {
        margin-bottom: 0 !important;
    }
</style>
<div class="tes" style="height: 100%;">

    <div class="chat-section d-flex flex-column" >
        <!-- Chat bubbles -->
        <div class="flex-grow-1 overflow-auto" id="chatMessages_<?=$userTarget?>">
            <!-- Contoh bubble pesan -->
            <!-- <div class="d-flex mb-2">
                <div class="bg-warning text-white rounded px-3 py-2 ms-auto" style="max-width: 70%;">
                    <div class="fw-bold small mb-1">Admin</div>
                    Halo, ada yang bisa saya bantu?
                    <div class="text-end small mt-1" style="font-size: 0.60em; opacity: 0.7;"><?= date("Y-m-d H:i:s")?></div>
                </div>
            </div>
            <div class="d-flex mb-2">
                <div class="bg-light text-dark rounded px-3 py-2 me-auto" style="max-width: 70%;">
                    <div class="fw-bold small mb-1">User</div>
                    Ya, saya ingin bertanya tentang produk.
                    <div class="text-end small mt-1" style="font-size: 0.60em; opacity: 0.7;"><?= date("Y-m-d H:i:s")?></div>
                </div>
            </div> -->
            <!-- Bubble pesan berikutnya akan ditambahkan di sini -->
        </div>
        <!-- Input pesan -->
        <form id="chatForm" class="d-flex border-top pt-2 mt-auto" style="margin-bottom:0;">
            <input type="text" class="form-control me-2" id="chatInput" placeholder="Ketik pesan..." autocomplete="off">
            <button type="submit" class="btn btn-warning" style="color: white;">Kirim</button>
        </form>
    </div>
</div>

<script type="text/javascript">
    var userLogin = '<?= session()->get('USER_ID') ?>';
    var userTarget = '<?= $userTarget ?>';
    var conversation = <?= json_encode($conversation) ?>;
    var firstSeenAtFound = false;

    function generateChatBubbles(conversation) {
        conversation.forEach(msg => { 
           if (!msg.seenAt && !firstSeenAtFound) {
            firstSeenAtFound = true;
                addChatBubble(msg.messageContent,  msg.messageFrom, 'newMessage', userTarget, msg.sentAt );
            }   
            addChatBubble(msg.messageContent,  msg.messageFrom, userLogin, userTarget, msg.sentAt );
        });
    }

    console.log(conversation, "conversation");
   
    $('#chatForm').on('submit', function(e) {
        e.preventDefault();
        const $btn = $(this).find('button[type="submit"]');
        if ($btn.prop('disabled')) return; // Jika tombol sudah disable, abaikan

        const message = $('#chatInput').val().trim();
        if (message) {
            $btn.prop('disabled', true); // Disable tombol
            $.ajax({
                url: '<?= base_url('Chat/sendMessage') ?>',
                type: 'POST',
                data: { messageContent: message, messageTo: userTarget, conversationId: conversation.length > 0 ? conversation[0].conversationId : '' },
                dataType: 'json',
                success: function(response) {
                    if (!response.success) {
                        showWarning('Failed to send message!');
                        $btn.prop('disabled', false); // Enable lagi jika gagal
                        return;
                    }
                    socket.emit('chatMessage', response.message, (response) => {
                        console.log("send message socket response:", response);
                    });
                    addChatBubble(message, userLogin, userLogin, userTarget);
                    $('#chatInput').val('');
                    console.log('Message sent:', response);

                    $('#chatMessages_' + userTarget + ' .newMessageChat').remove();
                },
                error: function(xhr, status, error) {
                    console.error('Error sending message:', error);
                    $btn.prop('disabled', false); // Enable lagi jika error
                },
                complete: function() {
                    setTimeout(() => {
                        $btn.prop('disabled', false); // Enable lagi setelah 1 detik
                    }, 1000);
                }
            });
        }
    });
    $(document).ready(()=>{
        generateChatBubbles(conversation);
        console.log('CHATYREADY!');
        $("#canvasLinkBackChat").show();
        $("#canvasIconChat").hide();
    })
</script>