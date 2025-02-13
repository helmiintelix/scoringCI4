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
        <div class="col-7 p-0" id="multiple_contract2" name="multiple_contract2">
            <!--Contract Detail2-->
        </div>
        <div class="col-5" id="divTelephony">
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
            <div class="col-5" id="divWa">
                <div class="card" id="scrollspyHeading2">
                    <div class="card-header text-center fw-bold ">
                            <i class="bi bi-whatsapp"></i>
                            WA Activity
                    </div>
                    <div class="card-body p-0">
                        <div class="container" id="agentWaFitur" style="background-color: ; color: black; max-height: 600px; overflow-y: auto;">
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
    // $('#scrollspyHeading3 .container').scrollTop($('#scrollspyHeading3 .container')[0].scrollHeight);

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