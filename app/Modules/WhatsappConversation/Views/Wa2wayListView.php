<div class="container">
    <div class="row">
        <div class="col-sm-12">
        
            <span class="badge rounded-pill text-bg-primary">account number</span>
            <span class="badge rounded-pill text-bg-success">OS balance</span>
            <span class="badge rounded-pill text-bg-warning">DPD</span>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <input class="form-control form-control-sm" onkeyup="cariListwa2way(this)" type="text" placeholder="search..." id="searchwa2waylist" aria-label=".form-control-sm example">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="list-group" id="list-group-wa2way">
                <i>[empty]</i>
            </div>
        </div>
    </div>
</div>

        

<script type="text/javascript">
var Listwa2way = <?=json_encode($wa2way,true);?> ;
var template_wa = <?=json_encode($template_wa,true);?> ;


function cariListwa2way(param){
    findwa2way(param.value);
}

function findwa2way(param) {

    let tmp = Listwa2way;
    const options = {
        threshold: 0.1,
        ignoreLocation: true,
        location: 1,
        keys: ['CR_NAME_1', 'CM_CARD_NMBR','DPD']

    }

    // Create the Fuse index
    const myIndex = Fuse.createIndex(options.keys, tmp)
    // initialize Fuse with the index
    var fuse = new Fuse(tmp, options, myIndex)

    // const fuse = new Fuse(menu_arr, options)

    var result = fuse.search(param);


    var arr = new Array();
    $.each(result, function (i, val) {
        arr.push(val.item);
        if (val.item.children) {
            fuse = new Fuse(val.item.children, options, myIndex)
            var result2 = fuse.search(param);
        }
    });
    
    if (param == '') {
        generateListWa2way(Listwa2way);
    } else {

        generateListWa2way(arr);
    }
}

function generateListWa2way(param){
    $("#list-group-wa2way").html('');
 

    $.each(param,(i,val)=>{
        let cm_os_balance = parseInt(val['CM_OS_BALANCE']).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
        let lstMsg = '';
        if (val['lastMessage'] === null){
            lstMsg = '<i>empty</i>';
        }else{
            lstMsg = val['lastMessage'];

        }

        var optTemplate = '';
        $.each(template_wa,(ii,vall)=>{
            optTemplate += '<li><a class="dropdown-item" onClick="wa_eskalasi(\''+val['CM_CARD_NMBR']+'\',\''+ii+'\',\''+val['CR_HANDPHONE']+'\')" href="#">'+vall+'</a></li>';
        })

        
        let html = ''+
                '<div class="list-group-item list-group-item-action">'+
                '    <div class="d-flex w-100 justify-content-between">'+
                '    <h5 class="mb-1">'+val['CR_NAME_1']+'</h5>'+
                '    <small class="text-muted" id="show_time_'+val['CM_CARD_NMBR']+'">'+val['lastAttempt']+'</small>'+
                '<input type="hidden" class="time_notification" id = "id_show_time_' + val['CM_CARD_NMBR'] + '"  show_time = "show_time_' + val['CM_CARD_NMBR'] + '" value="' + val['lastAttempt'] + '" >' +
                '    </div>'+
                '<div class="text-truncate">'+
                '    <small class="text-muted" id="show_msg_'+val['CM_CARD_NMBR']+'">'+lstMsg+'</small>'+
                '</div>'+
                '    <p class="mb-1">'+
                ' <span class="badge rounded-pill text-bg-primary">'+val['CM_CARD_NMBR']+'</span>' +
                ' <span class="badge rounded-pill text-bg-success">'+cm_os_balance+'</span>' +
                ' <span class="badge rounded-pill text-bg-warning">'+val['DPD']+'</span>' +
                '</p>'+
                '<div class="btn-group" role="group" aria-label="Basic mixed styles example">'+
                '    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="dropdown" aria-expanded="false" ><i class="bi bi-send" data-bs-toggle="tooltip" data-bs-placement="left" title="conversation initiate"></i></button>'+
                '    <button type="button" class="btn btn-sm btn-outline-primary" onClick="chatHistory(\''+val['CM_CARD_NMBR']+'\')"><i class="bi bi-chat-left-text" data-bs-toggle="tooltip" data-bs-placement="left" title="history conversation"></i></button>'+
                    '<div class="dropdown">'+
                        '<ul class="dropdown-menu">'+
                        optTemplate+
                        '</ul>'+
                    '</div>'+
                '</div>'+
                
                
                '</div>';
        $("#list-group-wa2way").append(html);
    })
}

function chatHistory(contractNumber){
    console.log('chatHistory',contractNumber);
    $("#wa2wayCanvas").html('').load(GLOBAL_MAIN_VARS["BASE_URL"] + 'whatsappConversation/conversationWaView?account_id='+contractNumber, function (responseTxt, statusTxt, xhr) {

    if (statusTxt == "success") {
      changeTheme(GLOBAL_THEME_MODE);
      $("#wa2wayCanvas").show();
    }
    else if (statusTxt == "error") {
      $("#wa2wayCanvas").html('<i>something wrong</i>');
    }

    })
}

function wa_eskalasi(card_number,templateId,hp1){
    let template =templateId;
    let phone = hp1;
    if (template != "" && phone != undefined) {

      var buttons = {
        save: {
          label: "Send",
          className: "btn-sm btn-primary btn-save-update-data",
          style: "disabled",
          callback: function () {
            var options = {
              url:
                GLOBAL_MAIN_VARS["SITE_URL"] +
                "whatsappConversation/whatsappConversation/blast_template_by_agent",
              data: {
                account_no: card_number,
                type: "wa",
                template_id: template,
              },
              type: "post",
              success: function (data) {

      
                showInfo("Berhasil");
                let contractNumber = data.updateData.contractNumber;
                let lastAttempt = data.updateData.lastAttempt;
                let lastMsg = data.updateData.lastMessage;

                console.log("#id_show_time_",contractNumber);
                console.log("lastAttempt",lastAttempt);
                console.log("lastMsg",lastMsg);
                
                $("#id_show_time_"+contractNumber).val(lastAttempt);
                $("#show_msg_"+contractNumber).html(lastMsg);

                if(data.success){


                  $(".maskName").html(data.data['to_number']);
                  $(".status").html(data.data['template_name']);
                  $(".conversation-container").html('');
                  let html = '<div class="message sent"><span id="template">'+data.data['message']+'</span><br><span class="metadata">'+
                          '<span class="time" id="show_time_blast">'+data.data['created_time']+'</span>'+
                          '<input type="hidden" class="time_notification" show_time = "show_time_blast" value="' +data.data['created_time'] + '" >' +
                          '</span><span class="metadata" style="float: left;">'+
                          '<span class="time"><i  class="bi bi-clock-history tooltip_all" id="tooltip_all0"></i></span></span></div>'
                  $(".conversation-container").append(html);
                  $("#txt_wa").val('')
                }
                
                return true;
              },
              dataType: "json",
            };
            $("#messaging_preview").ajaxSubmit(options);

            //return false;
          },
        },
        button: {
          label: "Close",
          className: "btn-sm btn-danger",
        },
      };

      showCommonDialog3(
        500,
        500,
        "WA PREVIEW",
        GLOBAL_MAIN_VARS["SITE_URL"] +
        "detail_account/detail_account/messaging_preview/?type=wa&account_id=" +
        card_number +
        "&template=" +
        template +
        "&phone=" +
        phone,
        buttons
      );
    } else {
      if (phone == undefined) {
        showInfo("Silahkan Pilih Phone tujuan!");
      } else {
        showInfo("Silahkan Pilih Template WA!");
      }
    }
}
  

$(document).ready(()=>{
    generateListWa2way(Listwa2way);
    console.log('WA2WAYREADY!');
    updateShowTime();
    $("#canvasLinkBackWa").hide();
    $("#canvasIconWa").show();
})

</script>