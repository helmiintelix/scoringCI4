<style>
.page {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.marvel-device .screen {
  text-align: left;
}

.screen-container {
  height: 100%;
}
/* Chat */

.chat {
  height: calc(100% - 69px);
}

.chat-container {
  height: 100%;
}

/* User Bar */

.user-bar {
  height: 55px;
  background: #005e54;
  color: #fff;
  padding: 0 8px;
  font-size: 24px;
  position: relative;
  z-index: 1;
}

.user-bar:after {
  content: "";
  display: table;
  clear: both;
}

.user-bar div {
  float: left;
  transform: translateY(-50%);
  position: relative;
  top: 50%;
}

.user-bar .actions {
  float: right;
  margin: 0 0 0 20px;
}

.user-bar .actions.more {
  margin: 0 12px 0 32px;
}

.user-bar .actions.attachment {
  margin: 0 0 0 30px;
}

.user-bar .actions.attachment i {
  display: block;
  transform: rotate(-45deg);
}

.user-bar .avatar {
  margin: 0 0 0 5px;
  width: 36px;
  height: 36px;
}

.user-bar .avatar img {
  border-radius: 50%;
  box-shadow: 0 1px 0 rgba(255, 255, 255, 0.1);
  display: block;
  width: 100%;
}

.user-bar .name {
  font-size: 17px;
  font-weight: 600;
  text-overflow: ellipsis;
  letter-spacing: 0.3px;
  margin: 0 0 0 8px;
  overflow: hidden;
  white-space: nowrap;
}

.user-bar .status {
  display: block;
  font-size: 13px;
  font-weight: 400;
  letter-spacing: 0;
}

/* Conversation */

.conversation {
  height: calc(100% - 12px);
  position: relative;
  background: #efe7dd url("https://cloud.githubusercontent.com/assets/398893/15136779/4e765036-1639-11e6-9201-67e728e86f39.jpg") repeat;
  z-index: 0;
}

.conversation ::-webkit-scrollbar {
  transition: all .5s;
  width: 5px;
  height: 1px;
  z-index: 10;
}

.conversation ::-webkit-scrollbar-track {
  background: transparent;
}

.conversation ::-webkit-scrollbar-thumb {
  background: #b3ada7;
}

.conversation .conversation-container {
  height: calc(100% - 68px);
  box-shadow: inset 0 10px 10px -10px #000000;
  
  padding: 0 16px;
  margin-bottom: 5px;
}

/*.conversation .conversation-container {
  height: calc(100% - 68px);
  box-shadow: inset 0 10px 10px -10px #000000;
  overflow-x: hidden;
  padding: 0 16px;
  margin-bottom: 5px;
}

.conversation .conversation-container:after {
  content: "";
  display: table;
  clear: both;
}*/

/* Messages */

.message {
  color: #000;
  clear: both;
  line-height: 18px;
  font-size: 15px;
  padding: 8px;
  position: relative;
  margin: 8px 0;
  max-width: 85%;
  word-wrap: break-word;
  z-index: -1;
}

.message:after {
  position: absolute;
  content: "";
  width: 0;
  height: 0;
  border-style: solid;
}

.metadata {
  display: inline-block;
  float: right;
  padding: 0 0 0 7px;
  position: relative;
  bottom: -4px;
}

.metadata .time {
  color: rgba(0, 0, 0, .45);
  font-size: 11px;
  display: inline-block;
}

.metadata .tick {
  display: inline-block;
  margin-left: 2px;
  position: relative;
  top: 4px;
  height: 16px;
  width: 16px;
}

.metadata .tick svg {
  position: absolute;
  transition: .5s ease-in-out;
}

.metadata .tick svg:first-child {
  -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
  -webkit-transform: perspective(800px) rotateY(180deg);
          transform: perspective(800px) rotateY(180deg);
}

.metadata .tick svg:last-child {
  -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
  -webkit-transform: perspective(800px) rotateY(0deg);
          transform: perspective(800px) rotateY(0deg);
}

.metadata .tick-animation svg:first-child {
  -webkit-transform: perspective(800px) rotateY(0);
          transform: perspective(800px) rotateY(0);
}

.metadata .tick-animation svg:last-child {
  -webkit-transform: perspective(800px) rotateY(-179.9deg);
          transform: perspective(800px) rotateY(-179.9deg);
}

.message:first-child {
  margin: 16px 0 8px;
}

.message.received {
  background: #fff;
  border-radius: 0px 5px 5px 5px;
  float: left;
}

.message.received .metadata {
  padding: 0 0 0 16px;
}

.message.received:after {
  border-width: 0px 10px 10px 0;
  border-color: transparent #fff transparent transparent;
  top: 0;
  left: -10px;
}

.message.sent {
  background: #e1ffc7;
  border-radius: 5px 0px 5px 5px;
  float: right;
}

.message.sent:after {
  border-width: 0px 0 10px 10px;
  border-color: transparent transparent transparent #e1ffc7;
  top: 0;
  right: -10px;
}

/* Compose */

.conversation-compose {
  display: flex;
  flex-direction: row;
  height: 50px;
  width: 100%;
  z-index: 2;
}

.conversation-compose div,
.conversation-compose input {
  background: #fff;
  height: 100%;
}

.conversation-compose .emoji {
  display: flex;
  align-items: center;
  justify-content: center;
  background: white;
  border-radius: 5px 0 0 5px;
  flex: 0 0 auto;
  margin-left: 8px;
  width: 48px;
}

.conversation-compose .input-msg {
  border: 0;
  flex: 1 1 auto;
  font-size: 16px;
  margin: 0;
  outline: none;
  min-width: 50px;
}

.conversation-compose .photo {
  flex: 0 0 auto;
  border-radius: 0 0 5px 0;
  text-align: center;
  position: relative;
  width: 48px;
}

.conversation-compose .photo:after {
  border-width: 0px 0 10px 10px;
  border-color: transparent transparent transparent #fff;
  border-style: solid;
  position: absolute;
  width: 0;
  height: 0;
  content: "";
  top: 0;
  right: -10px;
}

.conversation-compose .photo i {
  display: block;
  color: #7d8488;
  font-size: 24px;
  transform: translate(-50%, -50%);
  position: relative;
  top: 50%;
  left: 50%;
}

.conversation-compose .send {
  background: transparent;
  border: 0;
  cursor: pointer;
  flex: 0 0 auto;
  margin-left: 8px;
  margin-right: 8px;
  padding: 0;
  position: relative;
  outline: none;
}

.conversation-compose .send .circle {
  background: #008a7c;
  border-radius: 50%;
  color: #fff;
  position: relative;
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-left:15px;
}

.conversation-compose .send .circle i {
  font-size: 24px;
}

/* Small Screens */

@media (max-width: 768px) {
  .marvel-device.nexus5 {
    border-radius: 0;
    flex: none;
    padding: 0;
    max-width: none;
    overflow: hidden;
    height: 100%;
    width: 100%;
  }

  .marvel-device > .screen .chat {
    visibility: visible;
  }

  .marvel-device {
    visibility: hidden;
  }

  .marvel-device .status-bar {
    display: none;
  }

  .screen-container {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
  }

  .conversation {
    height: calc(100vh - 55px);
  }
  .conversation .conversation-container {
    height: calc(100vh - 120px);
  }
  .conversation-compose {
    display: flex;
    align-items: center;
    padding: 10px;
    background: #f1f1f1; /* Latar belakang input */
    border-top: 1px solid #ccc; /* Batas atas */
  }

  .input-msg {
    flex: 1; /* Membuat input mengisi ruang yang tersisa */
    border: none; /* Menghilangkan batas */
    border-radius: 20px; /* Menambahkan sudut melengkung */
    padding: 10px; /* Padding dalam input */
    outline: none; /* Menghilangkan outline */
  }

  .send {
    background: #008a7c; /* Warna latar tombol kirim */
    border: none; /* Menghilangkan batas */
    border-radius: 50%; /* Membuat tombol bulat */
    width: 40px; /* Lebar tombol */
    height: 40px; /* Tinggi tombol */
    display: flex; /* Menggunakan flexbox */
    align-items: center; /* Pusatkan item di dalam */
    justify-content: center; /* Pusatkan item di dalam */
    cursor: pointer; /* Kursor pointer saat hover */
  }

  .send .circle {
    background: #008a7c; /* Warna lingkaran */
    border-radius: 50%; /* Membuat lingkaran */
    width: 100%; /* Mengisi tombol */
    height: 100%; /* Mengisi tombol */
    display: flex; /* Menggunakan flexbox */
    align-items: center; /* Pusatkan ikon */
    justify-content: center; /* Pusatkan ikon */
  }

  .send:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Bayangan saat hover */
    background: #000000; /* Warna sedikit lebih gelap saat hover */
  }
  tooltip {
      position: fixed;
      background: #fff;
      color: #333;
      border: 2px solid #333;
      font-size: 15px;
  }
  .zoomable-image {
    cursor: pointer; /* Menunjukkan bahwa gambar dapat diklik */
  }

}    
</style>
<!--<input type="hidden" id="agent_id" value="<?php echo $username;?>">
<input type="hidden" id="group_id" value="<?php echo $group;?>">
-->
<input type="hidden" id="installment_number" value="">
<input type="hidden" id="is_multi_contract" value="">

<!--for testing environment only, should be romove if done-->

<input type="hidden" id="ProcessTimeStart" value="">
<input type="hidden" id="ProcessTimeEnd" value="">
<input type="hidden" id="ProcessDuration" value="">
<input type="hidden" id="DialTime" value="">
<input type="hidden" id="DialedPhoneNumber" value="">
<input type="hidden" id="DialModeID" value="">
<input type="hidden" id="ContactPersonType" value="CUSTOMER">
<input type="hidden" id="CallResult" value="">
<input type="hidden" id="#GlobalSubCallResult" value="">
<input type="hidden" id="CollectionID" value="">
<input type="hidden" id="no_kontrak" value="<?= $CM_CARD_NMBR; ?>">
<input type="hidden" id="curr_customer_id" value="<?= $CM_CUSTOMER_NMBR; ?>">
<!-- 
<a class="nav-link" href="#scrollspyHeading1">First</a>
<a class="nav-link" href="#scrollspyHeading2">second</a> -->

<div class="container p-0">
    <div class="row mb-2" id="multiple_contract" name="multiple_contract">
        <!--Contract Detail-->
    </div>
    <div class="row">
        <? if (getenv('USED_WA')==='false') {
          $col_ui='col-9 p-0';
        } else{
          $col_ui='col-6 p-0';
        } ?>
        <div class="<?=$col_ui;?>" id="multiple_contract2" name="multiple_contract2">
            <!--Contract Detail2-->
        </div>
        <div class="col-3">
            <div class="card" id="scrollspyHeading2">
                <div class="card-header text-center fw-bold ">
                        <i class="bi bi-telephone"></i>
                        Phone Activity
                </div>
                <div class="card-body p-0">
                    <div class="container" style="background-color: ; color: black; max-height: 600px; overflow-y: auto;">
                        <form class="form-horizontal" role="form" id="formCallMenu">
                            <!--<div class="row slim-scroll" data-height="225">-->
                            <input type="hidden" name="<?= csrf_token(); ?>" id="csrf_token" value="<?= csrf_hash(); ?>">
                            <input type="hidden" id="CallID" name="CallID" value="">
                            <input type="hidden" id="cm_card_nmbr" name="cm_card_nmbr"
                                value="<?= $CM_CARD_NMBR; ?>">
                            <input type="hidden" id="txt_customer_no" name="txt_customer_no"
                                value="<?= $CM_CUSTOMER_NMBR; ?>">
                            <input type="hidden" id="txt_TELEPHONY_CALLER_ID" name="txt_TELEPHONY_CALLER_ID">
                            <input type="hidden" id="txt_TELEPHONY_RECORDING_ID"
                                name="txt_TELEPHONY_RECORDING_ID">
                            <input type="hidden" id="DialedPhoneType" name="DialedPhoneType" value="">
                            <input type="hidden" id="dial_time" name="dial_time">
                            <div class="row justify-content-center mb-2">
                                <label class="col-sm-6 form-label text-right fs-6 " for="phone-owner">
                                    <small>PHONE :</small>
                                </label>
                                <div class="col-sm-6 text-end">
                                    <button class="btn btn-sm btn-sm-dial btn-success" type="button"
                                        id="btn-dial-Handphone" name="dial-button" value="dial"
                                        style="width: 60px; padding-left: 5px;">
                                        <i class="bi bi-telephone-outbound"></i>
                                        <span>Dial</span>
                                    </button>
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <select class="form-select fs-6" name="phone-owner"
                                    id="phone-owner">
                                    <option value=''>[select phone]</option>
                                        <option phonetype="hp1" value="<?= $CR_HANDPHONE; ?>" <?
                                            if($list_phone_no[0]['phone_type']=='hp1'
                                            ){echo "disabled='true' style='color: #ee9d3f;'" ;}?>>Handphone 1:
                                            <?= $CR_HANDPHONE; ?></option>
                                        <option phonetype="hp2" value="<?= $CR_HANDPHONE2; ?>" <?
                                            if($list_phone_no[0]['phone_type']=='hp2'
                                            ){echo "disabled='true' style='color: #ee9d3f;'" ;}?>>Handphone 2:
                                            <?= $CR_HANDPHONE2; ?></option>
                                        <option phonetype="Of1" value="<?= $CR_OFFICE_PHONE; ?>" <?
                                            if($list_phone_no[0]['phone_type']=='of1'
                                            ){echo "disabled='true' style='color: #ee9d3f;'" ;}?>>Office Phone
                                            1:
                                            <?= $CR_OFFICE_PHONE; ?></option>
                                        <option phonetype="Of2" value="<?= $CR_CO_OFFICE_PHONE; ?>" <?
                                            if($list_phone_no[0]['phone_type']=='of2'
                                            ){echo "disabled='true' style='color: #ee9d3f;'" ;}?>>Office Phone
                                            2:
                                            <?= $CR_CO_OFFICE_PHONE; ?></option>
                                        <option phonetype="guarantor" value="<?= $CR_GUARANTOR_PHONE; ?>" <?
                                            if($list_phone_no[0]['phone_type']=='guarantor'
                                            ){echo "disabled='true' style='color: #ee9d3f;'" ;}?>>Guarantor
                                            Phone:
                                            <?= $CR_GUARANTOR_PHONE; ?></option>
                                        <option phonetype="emergency" value="<?= $CR_EC_PHONE; ?>" <?
                                            if($list_phone_no[0]['phone_type']=='emergency'
                                            ){echo "disabled='true' style='color: #ee9d3f;'" ;}?>>Emergency
                                            Contact
                                            Phone: <?= $CR_EC_PHONE; ?></option>
                                        <option phonetype="spouse" value="<?= $CM_SPOUSE_PHONE; ?>" <?
                                            if($list_phone_no[0]['phone_type']=='spouse'
                                            ){echo "disabled='true' style='color: #ee9d3f;'" ;}?>>Spouse Phone:
                                            <?= $CM_SPOUSE_PHONE; ?></option>
                                        <!-- select new phone -->
                                        <?
                                                $style='';
                                                foreach ($new_phone as $key => $value) {
                                                    if($list_phone_no[0]['phone_type']==$value['phone_type']){$style = "disabled='true' style='color: #ee9d3f;'";} 
                                                    echo "<option phonetype='".$value['phone_type']."' value='".$value['phone_nmbr']."' ".$style." >".str_replace('-',' ',$value['phone_type']).": ".$value['phone_nmbr']."</option>";
                                                }
                                            ?>
                                        <option value="other">other phone</option>
                                    </select>
                                    <input type="text" class="form-control mt-2" name="other_phone" id="other_phone" readonly
                                        onkeydown="return numbOnly(event);" value=""
                                        onkeyup="submitHandler(this.value)"
                                        placeholder="input other here...">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="form-label fs-6"
                                    for="email_template"><small>EMAIL :</small></label>
                                <div class="col-sm-9">
                                <?php
                                    $attributes = 'class="form-select fs-6" id="email_template"';
                                    echo form_dropdown('email_template', $template_email, "", $attributes);
                                ?>
                                </div>
                                <div class="col-sm-3">
                                    <button class="btn btn-sm btn-success w-100" type="button" id="btn_eskalasi_email"
                                        name="email-button" value="eskalasi-email">
                                        <i class="bi bi-envelope"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="form-label fs-6"
                                    for="sms_template"><small>SMS :</small></label>
                                    <div class="col-sm-9">
                                    <?php
                                        $attributes = 'class="form-select fs-6" id="sms_template"';
                                        echo form_dropdown('sms_template', $template_sms, "", $attributes);
                                    ?>
                                    </div>
                                    <div class="col-sm-3">
                                        <button class="btn btn-sm btn-success w-100" type="button"
                                            id="btn_eskalasi_sms" name="sms-button" value="eskalasi-sms">
                                            <i class="bi bi-phone"></i>
                                        </button>
                                    </div>
                               
                                </div>
                                <div class="row mb-2">
                                    <label class="form-label fs-6"
                                        for="email_template"><small>EMAIL :</small></label>
                                    <div class="col-sm-9">
                                    <?php
                                        $attributes = 'class="form-select fs-6" id="email_template"';
                                        echo form_dropdown('email_template', $template_email, "", $attributes);
                                    ?>
                                    </div>
                                    <div class="col-sm-3">
                                        <button class="btn btn-sm btn-success w-100" type="button" id="btn_eskalasi_email"
                                            name="email-button" value="eskalasi-email">
                                            <i class="bi bi-envelope"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label class="form-label fs-6"
                                        for="sms_template"><small>SMS :</small></label>
                                        <div class="col-sm-9">
                                        <?php
                                            $attributes = 'class="form-select fs-6" id="sms_template"';
                                            echo form_dropdown('sms_template', $template_sms, "", $attributes);
                                        ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <button class="btn btn-sm btn-success w-100" type="button"
                                                id="btn_eskalasi_sms" name="sms-button" value="eskalasi-sms">
                                                <i class="bi bi-phone"></i>
                                            </button>
                                        </div>
                                </div>
                                <div class="row mb-2 ui_agent_wa" id="">
                                    <label class="form-label fs-6"
                                        for="wa_template"><small>WA :</small></label>
                                        <div class="col-sm-9">
                                        <?php
                                            $attributes = 'class="form-select fs-6" id="wa_template"';
                                            echo form_dropdown('wa_template', $template_wa, "", $attributes);
                                        ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <button class="btn btn-sm btn-success w-100" type="button"
                                                id="btn_eskalasi_wa" name="wa-button" value="eskalasi-wa">
                                                <i class="bi bi-chat-left-fill"></i>
                                            </button>
                                        </div>
                                </div>
                                <div class="row mb-2">
                                    <?php
                                        if(count($lov1)>0){
                                            $lov1 = array(''=>"LOV 1 : ")+$lov1;
                                    ?>
                                    <div class="form-floating">
                                        <?
                                                $attributes = 'class="form-select" id="lov1" aria-label="Floating label select" required';
                                                echo form_dropdown('lov1', $lov1, "", $attributes);
                                        ?>
                                        <label for="lov1"><?=$lov1_detail['label'];?></label>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <div class="row mb-2">
                                    <?php
                                        if(count($lov2)>0){
                                            $lov2 = array(''=>'LOV 2 : ')+$lov2;
                                    ?>
                                    
                                    <div class="form-floating">
                                        <?
                                            $attributes = 'class="form-select mandatory" id="lov2" aria-label="Floating label select" required';
                                            echo form_dropdown('lov2', $lov2, "", $attributes);
                                        ?>
                                        <label id="lbllov2" for="lov2"><?=$lov2_detail['label'];?></label>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <div class="row mb-2">
                                    <?php
                                        if(count($lov3)>0){
                                            $lov3 = array(''=>'LOV 3 : ')+$lov3;
                                    ?>
                                    <div class="form-floating">
                                        <?
                                            $attributes = 'class="form-select mandatory" id="lov3" aria-label="Floating label select" required';
                                            echo form_dropdown('lov3', $lov3, "", $attributes);
                                        ?>
                                        <label id="lbllov3" for="lov3"><?=$lov3_detail['label'];?></label>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <div class="row mb-2">
                                    <?php
                                        if(count($lov4)>0){
                                            $lov4 = array(''=>'LOV 4 :')+$lov4;
                                    ?>
                                    <div class="form-floating">
                                        <?
                                            $attributes = 'class="form-select mandatory" id="lov4" aria-label="Floating label select" required';
                                            echo form_dropdown('lov4', $lov4, "", $attributes);
                                        ?>
                                        <label id="lbllov4" for="lov4"><?=$lov4_detail['label'];?></label>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <div class="row mb-2">
                                    <?php
                                        if(count($lov5)>0){
                                            $lov5 = array(''=>'LOV 5 :')+$lov5;
                                    ?>
                                    <div class="form-floating">
                                        <?
                                            $attributes = 'class="form-select mandatory" id="lov5" aria-label="Floating label select" required';
                                            echo form_dropdown('lov5', $lov5, "", $attributes);
                                        ?>
                                        <label id="lbllov5" for="lov5"><?=$lov5_detail['label'];?></label>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <div class="row mb-2">
                                    
                                    <div class="form-floating" <?php if($approval=='1')echo "style='display:none' "; ?>>
                                        <?
                                            $options = array(''=>'[select data]','1'=>'Yes','0'=>'No');
                                            $attributes = 'class="form-select mandatory" id="select_escalate_to_tl"';
                                            if($approval=='1'){
                                                echo form_dropdown('select_escalate_to_tl', $options, "0", $attributes);
                                            }else{
                                                echo form_dropdown('select_escalate_to_tl', $options, "", $attributes);
                                            }
                                            ?>
                                            <label for="select_escalate_to_tl" <?php if($approval=='1') echo "style='display:none' "; ?>>ESCALATE TO TL :</label>
                                    </div>
                                </div>
                                <div class="row mb-2" style="display:none">
                                
                                    <div class="form-floating">
                                        <?php
                                            $attributes = 'class="form-select mandatory custom-width" id="select_join_program"';
                                            echo form_dropdown('select_join_program', $join_program, "NONE", $attributes);
                                        ?>
                                        <label for="select_join_program">JOIN PROGRAM : </label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    
                                    <div class="form-floating">
                                        <?php
                                            $options = array('0'=>'No', '1'=>'Yes');
                                            $attributes = 'class="form-select mandatory custom-width" id="opt_request_phone_tag"';
                                            echo form_dropdown('opt_request_phone_tag', $options, "0", $attributes);
                                        ?>
                                        <label for="opt_request_phone_tag">REQ. PHONE TAG : </label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    
                                    <div class="form-floating">
                                        <?php
                                            $attributes = 'class="form-select mandatory custom-width" id="opt_reason_phone_tag" disabled';
                                            echo form_dropdown('opt_reason_phone_tag', $phonetag_ref, "", $attributes);
                                        ?>
                                        <label for="opt_reason_phone_tag"><small>REASON PHONE TAG :</small></label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label class="form-label fs-6" for="status_escalate"
                                        <?php if($approval !='1' )echo "style='display:none' "; ?>>
                                        <small>STATUS ESCALATE</small>
                                    </label>
                                    <div class="col-sm-12" <?php if($approval!='1')echo "style='display:none' "; ?>>
                                        <input type="hidden" value="<?=$history_id?>" name="history_id"
                                            id="history_id" />
                                        <?
                                            $options = array('0'=>'NOT COMPLATE' ,'1'=>'COMPLATE');
                                            $attributes = 'class="form-select mandatory" id="status_escalate"';
                                            echo form_dropdown('status_escalate', $options, "0", $attributes);
                                        ?>
                                    </div>
                                </div>
                                <div class="row mb-2" id="connected_result">
                                    <!--Connected Result-->
                                </div>
                                <div class="row mb-2" id="appointment_result">
                                    <!--appointment Result-->
                                </div>
                                <div class="row mb-2">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a hot note here" id="txt_hot_note" name="txt_hot_note" maxlength="150" style="height: 100px"><?=$hot_notes;?></textarea>
                                        <label for="txt_hot_note">HOT NOTE :</label>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a note here" id="txt_agent_notepad" name="txt_agent_notepad" maxlength="320" style="height: 100px"></textarea>
                                        <label for="txt_agent_notepad">NOTE(max 320 chars)</label>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <input type="hidden" id="caller_id1" />
                                        <!-- format value :  callerId|number|parkId -->
                                        <input type="hidden" id="caller_id2" />
                                        <!-- format value :  callerId|number|parkId -->
                                        <div id="user_online" class="collapse"
                                            style="border: 0px solid;padding-left: 35px;">
                                        </div>
                                    </div>
                                    <div class="mb-1 btn-group btn-group-sm" role="group" aria-label="Basic mixed styles example">
                                        <button type="button" class="btn btn-info" id="btnAddNewPhone">Add New Phone</button>
                                        <button type="button" class="btn btn-warning"  id="btnAddNewAddress">Add New Address</button>
                                    </div>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic mixed styles example">
                                        <button type="button" class="btn btn-secondary" id="btnAddNewEc">Add New EC</button>
                                        <button type="button" class="btn btn-success"  id="btnAddNewEmail">Add New Email</button>
                                    </div>
                                </div>
                                <div class="row mb-2" >
                                    <div class="col-sm-12">
                                        <div class="text-center">
                                            <button class="btn btn-sm btn-danger" type="button"
                                                id="btnSaveAndbreakFollowup">
                                                <i class="bi bi-floppy"></i>
                                                Save and Break
                                            </button>
                                            <button class="btn btn-sm btn-success" type="button" id="btnSaveAndNext"
                                                <?php if($approval=='1') echo "style='display:none'"; ?>>
                                                <i class="bi bi-floppy"></i>
                                                Save and Next
                                            </button>
                                            <button class="btn btn-sm btn-info" type="button" id="btnSaveFollowup">
                                                <i class="bi bi-floppy"></i>
                                                Save
                                            </button><br>
                                            <button type="button" class="btn btn-secondary btn-sm mt-2" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <div class="col-3 ui_agent_wa" id="">
            <div class="card" id="scrollspyHeading3">
                <div class="card-header text-center fw-bold ">
                        <i class="bi bi-whatsapp"></i>
                        Whatsapp Activity 
                        <span style="color: red;<?=  (empty($data_inb['pickup_time']) && !empty($data_inb['messageId']) ) ? "display: show;" : "display: none;" ; ?>">*</span>
                </div>
                <div class="card-body p-0">
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
                                                                    <span class="metadata"><span class="time"><?= $data_blast['created_time']; ?></span></span>
                                                                    <?= $data_blast['approval_status']; ?>
                                                                    <!-- <span class="metadata" style="float: left;"><span class="time"><i  class="bi bi-clock-history tooltip_all" id="tooltip_all"></i></span></span> -->
                                                                </div>
                                                                
                                                                <? if (!empty($data_convertation)) {
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
                                                                            <span class="metadata"><span class="time"><?= $value['created_time'] ?></span></span>
                                                                            <?= $value['approval_status']; ?>
                                                                        </div><?
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
        </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    var lbl_lov1 = '<?=$lov1_detail['label'];?>';
    var lbl_lov2 = '<?=$lov2_detail['label'];?>';
    var lbl_lov3 = '<?=$lov3_detail['label'];?>';
    var lbl_lov4 = '<?=$lov4_detail['label'];?>';
    var lbl_lov5 = '<?=$lov5_detail['label'];?>';

    var lov1_status = '<?=$lov1_status;?>';
    var lov2_status = '<?=$lov2_status;?>';
    var lov3_status = '<?=$lov3_status;?>';
    var lov4_status = '<?=$lov4_status;?>';
    var lov5_status = '<?=$lov5_status;?>';

    var history_id = '<?=$history_id;?>';
    var approval = '<?=$approval;?>';
    var team_id = '<?=$team_id;?>';

    var predictive_phone = <?= $predictive_phone; ?>;
    var user_level_group = "<?= session()->get('LEVEL_GROUP')?>";
    var status_pengajuan = "<?= $status_pengajuan; ?>";
    var tgl_pengajuan = "<?= $tgl_pengajuan; ?>";
    var group_id = "<?= session()->get('LEVEL_GROUP')?>";
    var GLOBAL_VARS = "<?= session()->get('USER_ID')?>";
    var USED_WA="<?=getenv('USED_WA')?>";

    if (USED_WA=='false') {
      $(".ui_agent_wa").html('');
    }

    $( "#coba_tooltips<?= $CM_CARD_NMBR; ?>" ).hover(
      function() {
        
        let allElements = document.getElementsByTagName('i');
        let k=0;
        for (let i = 0; i < allElements.length; i++) {
            if (allElements[i].className === 'bi bi-clock-history tooltip_all') {
              $( "#tooltip_all"+k ).append( $( '<span style="font-size: 7px;">Need Approval</span>' ) );
              k++;
            } else if(allElements[i].className === 'bi bi-check2 tooltip_all'){
              $( "#tooltip_all"+k ).append( $( '<span style="font-size: 7px;">Send</span>' ) );
              k++;
            } else if(allElements[i].className === 'bi bi-check2-all tooltip_all'){
              $( "#tooltip_all"+k ).append( $( '<span style="font-size: 7px;">Delivered</span>' ) );
              k++;
            }
            
        }
        
      }, function() {
        
        let allElements = document.getElementsByTagName('i');
        let m=0; 
        for (let i = 0; i < allElements.length; i++) {
            if (allElements[i].className === 'bi bi-clock-history tooltip_all') {  
              $( "#tooltip_all"+m ).find( "span" ).last().remove();
              m++;
            } else if(allElements[i].className === 'bi bi-check2 tooltip_all'){
              $( "#tooltip_all"+m ).find( "span" ).last().remove();
              m++;
            } else if(allElements[i].className === 'bi bi-check2-all tooltip_all'){
              $( "#tooltip_all"+m ).find( "span" ).last().remove();
              m++;
            }
            

        }
        
      }
    );
    $('#scrollspyHeading3 .container').scrollTop($('#scrollspyHeading3 .container')[0].scrollHeight);

    /*document.querySelector('.zoomable-image').addEventListener('dblclick', function() {
      console.log("aaaaaaaaaaaaaaaaaa")
      const imgSrc = this.src; // Ambil URL gambar yang di-double-click
      const newTab = window.open(imgSrc, '_blank'); // Buka gambar di tab baru
      newTab.focus(); // Fokuskan tab baru yang dibuka
    });*/

    function download_file(tag) {
      const imgSrc = tag.src; // Ambil URL gambar yang di-double-click
      const newTab = window.open(imgSrc, '_blank'); // Buka gambar di tab baru
      newTab.focus(); // Fokuskan tab baru yang dibuka
    }

</script>
<script src="<?= base_url(); ?>modules/detail_account/js/detail_account.js?v=<?= rand() ?>">
</script>