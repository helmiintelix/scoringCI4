<div class="container">
    <div class="row">
        <input class="form-control form-control-sm" onkeyup="cariListwa2way(this)" type="text" placeholder="search..." id="searchwa2waylist" aria-label=".form-control-sm example">
    </div>
    <div class="row">
        <div class="list-group" id="list-group-wa2way">
            <i>[empty]</i>
        </div>
    </div>
</div>
        

<script type="text/javascript">
var Listwa2way = <?=json_encode($wa2way,true);?> ;

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
            lstMsg = '';
        }else{
            lstMsg = val['lastMessage'];

        }

        
        let html = ''+
                '<div class="list-group-item list-group-item-action">'+
                '    <div class="d-flex w-100 justify-content-between">'+
                '    <h5 class="mb-1">'+val['CR_NAME_1']+'</h5>'+
                '    <small class="text-muted">3 days ago</small>'+
                '    </div>'+
                '    <small class="text-muted">'+lstMsg+'</small>'+
                '    <p class="mb-1">'+val['CM_CARD_NMBR']+' | '+cm_os_balance+'|'+val['DPD']+'</p>'+
                '<button type="button" class="btn btn-sm btn-success" onClick="chatHistory(\''+val['CM_CARD_NMBR']+'\')"><i class="bi bi-chat-left-text"></i></button>'+
                '<button type="button" class="btn btn-sm btn-primary" onClick="sendFirst(\''+val['CM_CARD_NMBR']+'\')"><i class="bi bi-send"></i></button>'+
                '</div>';
        $("#list-group-wa2way").append(html);
    })
}

function sendFirst(contractNumber){
    console.log('sendFirst',contractNumber);
}

function chatHistory(contractNumber){
    console.log('chatHistory',contractNumber);
}

function wa_eskalasi(){
    let template = $("#wa_template").val();
    let phone = $("#phone-owner").val();
    if (template != "" && phone != undefined) {

      //validasi harus ke handphone 1 (soalnya di mapping ke situ)
      if ($('option[phonetype="hp1"]').val()!=phone) {
        showInfo("Silahkan Pilih Phone tujuan Handphone 1");
        return false;
      }

      if (phone == "Other") {
        phone = $("#other_phone").val();
      } else {
        phone = $("#phone-owner").val();
      }
      var buttons = {
        save: {
          label: "Send",
          className: "btn-sm btn-primary btn-save-update-data",
          style: "disabled",
          callback: function () {
            var options = {
              url:
                GLOBAL_MAIN_VARS["SITE_URL"] +
                "detail_account/detail_account/blast_template_by_agent",
              data: {
                account_no: card_number,
                type: "wa",
                template_id: template,
              },
              type: "post",
              success: function (data) {

      
                showInfo("Berhasil");
                if(data.success === true){
                  
                  $(".maskName").html(data.data['to_number']);
                  $(".status").html(data.data['template_name']);
                  $(".conversation-container").html('');
                  $(".conversation-container").append('<div class="message sent"><span id="template">'+data.data['message']+'</span><br><span class="metadata"><span class="time">'+data.data['created_time']+'</span></span><span class="metadata" style="float: left;"><span class="time"><i  class="bi bi-clock-history tooltip_all" id="tooltip_all0"></i></span></span></div>');
                  $("#txt_wa").val('')
                }2
                
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
})
</script>