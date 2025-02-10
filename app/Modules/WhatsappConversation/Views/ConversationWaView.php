
      <div class="col-12 ui_agent_wa" id="">
       
                <div class="text-center fw-bold ">
                        <span style="color: red;<?=  (empty($data_inb['pickup_time']) && !empty($data_inb['messageId']) ) ? "display: show;" : "display: none;" ; ?>">*</span>
                </div>
                <div id="waConversationActivity" class="card-body p-0">
                    <div class="container" style="background-color: ; color: black; max-height: 600px; overflow-y: auto;">
                        <div class="mb-12">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="page">
                                    <div class="marvel-device nexus5" style="width:100%; background-color: #ece5dd;">
                                        <div class="screen">
                                            <div class="screen-container">
                                                <div class="chat">
                                                    <div class="chat-container">
                                                        <div class="user-bar">
                                                            <div class="back">
                                                                <i class="zmdi zmdi-arrow-left"></i>
                                                            </div>
                                                            <div class="avatar">
                                                                <img src="<?=base_url()?>assets/nice_admin/img/whatsapp.png" alt="Avatar">
                                                            </div>
                                                            <div class="name">
                                                                <span class="maskName"><?= $data_blast['to_number']; ?></span>
                                                                <span class="status"><?= $data_blast['template_name']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="conversation">
                                                            <div class="conversation-container" id="coba_tooltips<?= $CM_CARD_NMBR; ?>" >
                                                                <div class="message sent" style="<?=  (empty($data_blast['to_number'])) ? "display: none;" : "display: show;" ; ?>">
                                                                    <span id="template"><?= $data_blast['message_blast']; ?></span><br>
                                                                    <span class="metadata"><span class="time" id="show_time_blast"><?= $data_blast['created_time']; ?></span></span>
                                                                    <input type="hidden" class="time_notification" id = "id_show_time_blast" show_time = "show_time_blast" value="<?= $data_blast['created_time']; ?>" >
                                                                    <?= $data_blast['approval_status']; ?>
                                                                    <!-- <span class="metadata" style="float: left;"><span class="time"><i  class="bi bi-clock-history tooltip_all" id="tooltip_all"></i></span></span> -->
                                                                </div>
                                                                
                                                                <?php 
                                                                $lastKeyMsg = 0;
                                                                if (!empty($data_convertation)) {
                                                                    foreach ($data_convertation as $key => $value) {
                                                                        if ($value['direction']=='OUTB') {
                                                                            $class_type_message='sent';
                                                                        }else if($value['direction']=='INB'){
                                                                            $class_type_message='received';
                                                                        }
                                                                        //generate content message 
                                                                        ?><div class="message <?= $class_type_message; ?>"> 
                                                                            <?php if ($value['messageType']=='IMAGE') { ?>
                                                                              <img src="<?= $value['link_attachment']; ?>" id="<?= $value['pairedMessageId']; ?>" alt="" width="185" height="200" ondblclick="download_file(this)" ><br>
                                                                            <?php } else if($value['messageType']=='VIDEO'){?>
                                                                              <video width="185" height="200" controls><source src="<?= $value['link_attachment']; ?>" type="<?= $value['callbackData']; ?>">Your browser does not support the video tag.</video><br>
                                                                            <?php } else if($value['messageType']=='AUDIO'){?>
                                                                              <video width="185" height="200" controls><source src="<?= $value['link_attachment']; ?>" type="<?= $value['callbackData']; ?>">Your browser does not support the audio element.</video><br>
                                                                            <?php } else if($value['messageType']=='DOCUMENT'){?>
                                                                              <a href="<?= $value['link_attachment']; ?>" target="_blank"><br><i class="bi bi-file-earmark-pdf"></i> <?= $value['pairedMessageId']; ?></a><br>
                                                                            <?php } ?>  
                                                                            <span id="random"><?= $value['messageText'] ?></span><br>
                                                                                <span class="metadata"><span class="time" id="show_time_<?=$key?>"><?= $value['created_time'] ?></span></span>
                                                                                <input type="hidden" class="time_notification" id = "id_show_time_<?=$key?>" show_time = "show_time_<?=$key?>" value="<?= $value['created_time'] ?>" >
                                                                            <?= $value['approval_status']; ?>
                                                                        </div><?php
                                                                        $lastKeyMsg = $key;
                                                                    }
                                                                }
                                                                if (empty($data_inb['ticket_id'])) {
                                                                    $status_ticket='display:none;';
                                                                    $status_reply='';
                                                                    if (empty($data_blast['message_id'])) {
                                                                        $status_reply='disabled';
                                                                    }
                                                                    if (@$data_inb['pickup_by']=='CHATBOT') {
                                                                        $status_reply='disabled';
                                                                    }
                                                                }else{
                                                                    $status_ticket='display:flex;';
                                                                    $status_reply='disabled';
                                                                }?>
                                                                
                                                                <span class="message" style="<?= $status_ticket; ?>justify-content: center;font-size: 11px;"><span class="">[ Ticket is created <?= $data_inb['ticket_id']; ?> ]</span></span>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row" style="margin-right: 10px;margin-left: 0px;">
                      <div class="mb-1 btn-group btn-group-sm" role="group" aria-label="Basic mixed styles example">
                          <div class="col-sm-3">
                            <small>Quick Reply</small>
                          </div>
                          <div class="col-sm-7" >
                            <?
                                $attributes = 'class="form-select" id="select_template_quick"';
                                echo form_dropdown('select_template_quick', $list_quick_template, "", $attributes);    
                            ?>
                        </div>
                        <div class="col-sm-1">
                          <button type="button" class="btn btn-info" id="btnAddQuick">Apply</button>
                        </div>
                      </div>  
                    </div>
                    <div class="row" style="margin-right: 10px;margin-left: 0px;">
                      <div class="mb-1 btn-group btn-group-sm" role="group" aria-label="Basic mixed styles example">
                          <div class="col-sm-3">
                            <label for="userfile" class="fs-6 text-capitalize">Upload File</label>
                          </div>
                          <div class="col-sm-9" >
                            <input type="file" id="file_attachment" name="file" class="form-control form-control-sm mandatory" required
                                accept=".jpg,.jpeg,.png,.mp4,.webm,.mp3,.wav,.txt,.docx,.doc,.pdf,.xlsx,.xls,.pptx,.ppt" />
                        </div>

                      </div>  
                    </div>
                    <div class="conversation-compose" style="margin-bottom: 15px;margin-top: 15px;">
                        <form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
                            <input type="hidden" id="token" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <input type="hidden" id="to_number" name="to_number" value="<?= $data_blast['to_number']; ?>" />
                            <input type="hidden" id="cm_card_nmbr" name="cm_card_nmbr" value="<?= $CM_CARD_NMBR; ?>">
                            <input type="hidden" id="inbound_message_id" name="inbound_message_id" value="<?= $data_inb['messageId']; ?>">
                            <textarea class="form-control" id="txt_wa" name="txt_wa"maxlength="320" style="height: 60px;margin-left: 15px;" placeholder="Type a message..." <?= $status_reply; ?>></textarea>
                        </form>
                        <button class="send" id="btn_send_wa" <?= $status_reply; ?>>
                            <div class="circle">
                                <i class="bi bi-send"></i>
                            </div>
                        </button>
                    </div>
                </div>
           
        </div>


<script>
var lastKeyMsg = <?=$lastKeyMsg?>;
$("#btnAddQuick").click(function () {
  
  var options = {
    url:GLOBAL_MAIN_VARS["SITE_URL"] +"whatsappConversation/whatsappConversation/get_quick_reply?template_id="+$("#select_template_quick").val(),
    type: 'get',
    dataType: 'json',     
    success: function (data) {
      if(data.success === true){ 
        var msg=$("#txt_wa").val()+data.data;
        $("#txt_wa").val('');
        $("#txt_wa").val(msg);
      }
      return false;
    },
    error: function (a) {
      //backend.notify('error', a.responseText, 'bar', 'jelly');
    }
  };
  $('#form_add').ajaxSubmit(options);
});


$("#btn_send_wa").click(function () {
  // Membuat FormData

  // Mendapatkan token menggunakan request AJAX
  $.ajax({
    type: "GET",
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "whatsappConversation/whatsappConversation/get_token",
    dataType: "json",
    success: function (msg) {
      // Mengubah nama dan value untuk token
      var inputElement = document.getElementById("token");
      inputElement.name = msg.data['name']; // Ubah nama
      inputElement.value = msg.data['value']; // Ubah value

      var formData = new FormData($("#form_add")[0]); // Ambil semua data dari form, termasuk file

      var inputElementFile = document.getElementById("file_attachment");
      // Menambahkan file upload baru ke FormData

      if (inputElementFile.files[0]) {
        formData.append(inputElementFile.name, inputElementFile.files[0]);
      } else {
        formData.append(inputElementFile.name, null);
      }

      // Mengirimkan form data menggunakan AJAX
      $.ajax({
        url: GLOBAL_MAIN_VARS["SITE_URL"] + "whatsappConversation/whatsappConversation/reply_wa",
        type: 'POST',
        data: formData,
        dataType: 'json',
        processData: false, // Jangan proses data menjadi string
        contentType: false, // Jangan set Content-Type, FormData akan melakukannya
        success: function (data) {
          if (data.success === true) {
            lastKeyMsg ++;
            let htmlAppend = '';
            if (data.data['messageType'] == 'IMAGE') {
              $("#coba_tooltips" + $('#cm_card_nmbr').val()).append('<div class="message sent">' + '<img src="' + GLOBAL_MAIN_VARS["SITE_URL"] + 'file_upload/wa_blast_conversation/' + data.data['pairedMessageId'] + '" ondblclick="download_file(this)" id="' + data.data['pairedMessageId'] + '" alt="" width="185" height="200"><br>' + '<span id="random">' + data.data['message'] + '<br></span><span class="metadata"><span class="time">' + data.data['created_time'] + '</span></span><span class="metadata" style="float: left;"><span class="time"><i class="bi bi-check2-all tooltip_all" id="tooltip_all' + $('#coba_tooltips' + $('#cm_card_nmbr').val() + ' .message.sent').length + '"></i></span></span></div>');
            } else if (data.data['messageType'] == 'VIDEO') {
              $("#coba_tooltips" + $('#cm_card_nmbr').val()).append('<div class="message sent">' + '<video width="185" height="200" controls>' + '<source src="' + GLOBAL_MAIN_VARS["SITE_URL"] + 'file_upload/wa_blast_conversation/' + data.data['pairedMessageId'] + '" type="' + data.data['callbackData'] + '">' + 'Your browser does not support the audio element.' + '</video><br>' + '<span id="random">' + data.data['message'] + '<br></span><span class="metadata"><span class="time">' + data.data['created_time'] + '</span></span><span class="metadata" style="float: left;"><span class="time"><i class="bi bi-check2-all tooltip_all" id="tooltip_all' + $('#coba_tooltips' + $('#cm_card_nmbr').val() + ' .message.sent').length + '"></i></span></span></div>');
            } else if (data.data['messageType'] == 'AUDIO') {
              $("#coba_tooltips" + $('#cm_card_nmbr').val()).append('<div class="message sent">' + '<video width="185" height="200" controls>' + '<source src="' + GLOBAL_MAIN_VARS["SITE_URL"] + 'file_upload/wa_blast_conversation/' + data.data['pairedMessageId'] + '" type="' + data.data['callbackData'] + '">' + 'Your browser does not support the audio element.' + '</video><br>' + '<span id="random">' + data.data['message'] + '<br></span><span class="metadata"><span class="time">' + data.data['created_time'] + '</span></span><span class="metadata" style="float: left;"><span class="time"><i class="bi bi-check2-all tooltip_all" id="tooltip_all' + $('#coba_tooltips' + $('#cm_card_nmbr').val() + ' .message.sent').length + '"></i></span></span></div>');
            } else if (data.data['messageType'] == 'DOCUMENT') {
              $("#coba_tooltips" + $('#cm_card_nmbr').val()).append('<div class="message sent">' + '<a href="' + GLOBAL_MAIN_VARS["SITE_URL"] + 'file_upload/wa_blast_conversation/' + data.data['pairedMessageId'] + '" target="_blank"><br>' + '<i class="bi bi-file-earmark-pdf"></i> ' + data.data['pairedMessageId'] + '</a><br>' + '<span id="random">' + data.data['message'] + '<br></span><span class="metadata"><span class="time">' + data.data['created_time'] + '</span></span><span class="metadata" style="float: left;"><span class="time"><i class="bi bi-check2-all tooltip_all" id="tooltip_all' + $('#coba_tooltips' + $('#cm_card_nmbr').val() + ' .message.sent').length + '"></i></span></span></div>');
            } else {
              htmlAppend = '<div class="message sent"><span id="random">' + data.data['message'] + '<br></span>'+
                            '<span class="metadata">'+
                              '<span class="time" id="show_time_'+lastKeyMsg+'">' + data.data['created_time'] + '</span></span>'+
                              '<input type="hidden" class="time_notification" id = "id_show_time_'+lastKeyMsg+'" show_time = "show_time_'+lastKeyMsg+'" value="'+data.data['created_time']+'" >'+
                              '<span class="metadata" style="float: left;"><span class="time"><i class="bi bi-check2-all tooltip_all" id="tooltip_all' + $('#coba_tooltips' + $('#cm_card_nmbr').val() + ' .message.sent').length + '"></i></span></span></div>';
              $("#coba_tooltips" + $('#cm_card_nmbr').val()).append(htmlAppend);
            }


            $("#txt_wa").val(''); // Kosongkan textarea setelah mengirim
            $("#file_attachment").val(''); // Kosongkan textarea setelah mengirim
          } else {
            alert(data.message);
          }
        },
        error: function (a) {
          // Menangani error
          alert('Terjadi kesalahan saat mengirim data.');
        }
      });
    },
    error: function (error) {
      // Menangani error jika ada masalah dalam request untuk mendapatkan token
      alert('Terjadi kesalahan saat mendapatkan token.');
    }
  });
});

$(document).ready(()=>{
    $("#canvasLinkBackWa").show();
    $("#canvasIconWa").hide();
    updateShowTime();
})

</script>