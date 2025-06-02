<style>
    .dot-pulse {
      position: relative;
      width: 15px;
      height: 15px;
      border-radius: 50%;
      background: #ffc107;
      flex-shrink: 0; 
    }

    .dot-pulse::after {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border-radius: 50%;
      background: #ffc107;
      opacity: 0.6;
      animation: pulse 1.5s infinite ease-in-out;
    }

    @keyframes pulse {
      0% {
        transform: scale(1);
        opacity: 0.6;
      }
      100% {
        transform: scale(2);
        opacity: 0;
      }
    }

    

    
</style>

<div class="container">
    
    <div class="row">
        <div class="col-sm-12">
            <input class="form-control form-control-sm" onkeyup="cariListChat(this)" type="text" placeholder="search..." id="searchchatlist" aria-label=".form-control-sm example">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="list-group" id="list-group-chat">
                <i>[empty]</i>
                
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function cariListChat(input) {
        const searchValue = input.value.trim().toLowerCase();
        // Ambil semua card chat
        $("#list-group-chat .card").each(function () {
            const userId = $(this).find("p").first().text().trim().toLowerCase();
            if (userId.includes(searchValue)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
    
    function limitChar(str, max = 50) {
        if (typeof str !== 'string') return '';
        return str.length > max ? str.substring(0, max) + '...' : str;
    }

    function chatHistoryAgent (userId){
        console.log("chatHistoryAgent",userId);
        $("#chatCanvas").html('').load(GLOBAL_MAIN_VARS["BASE_URL"] + 'Chat/conversationChatView?userId='+userId, function (responseTxt, statusTxt, xhr) {
            if (statusTxt == "success") {
                changeTheme(GLOBAL_THEME_MODE);
                $("#chatCanvas").show();
            }
            else if (statusTxt == "error") {
                $("#chatCanvas").html('<i>something wrong</i>');
            }
        })

    }

    $(document).ready(()=>{
        console.log("first load chat list", <?= json_encode($chatList ?? []) ?>);
        dataListChat = <?= json_encode($chatList ?? []) ?>;
        if (dataListChat.length > 0) {
            generateListChat(dataListChat);
        } 
        checkSeenAt()

        // console.log('WA2WAYREADY!');
        // updateShowTime();
        $("#canvasLinkBackChat").hide();
        $("#canvasIconChat").show();
    })
</script>