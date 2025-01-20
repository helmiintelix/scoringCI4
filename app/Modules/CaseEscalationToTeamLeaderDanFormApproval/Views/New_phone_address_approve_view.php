<div class="row" style="margin-right: 5px;">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6">
                <label class="col-sm-4 col-form-label"><small>Request By</small></label>
                <div class="col-sm-8">
                    <small><strong><span id="cd_contract_no1">&nbsp;<?=$new['id'];?> -
                                <?=$new['name'];?></span></strong></small>
                </div>

                <label class="col-sm-4 col-form-label"><small>Request Time</small></label>
                <div class="col-sm-8">
                    <small><strong><span id="cd_contract_no1">&nbsp;<?=$new['created_time'];?></span></strong></small>
                </div>

                <label class="col-sm-4 col-form-label"><small>Customer Name</small></label>
                <div class="col-sm-8">
                    <small><strong><span id="cd_contract_no1">&nbsp;<?=$old['cr_name_1'];?></span></strong></small>
                </div>

                <label class="col-sm-4 col-form-label"><small>Loan No.</small></label>
                <input type='hidden' value='<?=$old['cm_card_nmbr'];?>' id='contract_number_new_data' />
                <div class="col-sm-8">
                    <small><strong><span id="cd_contract_no1">&nbsp;<?=$old['cm_card_nmbr'];?></span></strong></small>
                </div>
            </div>
        </div>


        <div class="row">
            <?php if($type=='NPH') {?>
            <div class="col-6">
                <h4 class="blue">Current Phone</h4>
                <div class="row slim-scroll" data-height="225" id="new_phone_address_form">
                    <form class="form-horizontal" role="form" id="fNewPhone">

                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>PHONE 1</small></label>
                            <div class="col-sm-6 input-group">
                                <input class="form-control" type="text" readonly style="width: 140px;"
                                    onKeypress="return numbersonly(this, event)" value="<?=$old["CR_HANDPHONE"];?>" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>PHONE 2</small></label>
                            <div class="col-sm-6 input-group">
                                <input class="form-control" type="text" readonly style="width: 140px;"
                                    onKeypress="return numbersonly(this, event)" value="<?=$old["CR_HANDPHONE2"];?>" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>PHONE 3</small></label>
                            <div class="col-sm-6 input-group">
                                <input class="form-control" type="text" readonly style="width: 140px;"
                                    onKeypress="return numbersonly(this, event)" value="<?=$old["CR_HANDPHONE3"];?>" />
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <div class="col-6">
                <h4 class="blue">New Phone</h4>
                <div class="row slim-scroll" data-height="225" id="new_phone_address_form">
                    <form class="form-horizontal" role="form" id="fNewPhone">
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>PHONE 1</small></label>
                            <div class="col-sm-6 input-group">
                                <input class="form-control" type="text" readonly name="borrower_hp1" id="borrower_hp1"
                                    style="width: 140px;" onKeypress="return numbersonly(this, event)"
                                    value="<?=$new["borrower_hp1"];?>" />
                                <button class="btn btn-sm btn-success" href="#" ref="borrower_hp1"
                                    id="btn_dial_borrower_hp1" onClick="dialtoCs(this)" tobe='dial'>dial</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>PHONE 2</small></label>
                            <div class="col-sm-6 input-group">
                                <input class="form-control" type="text" readonly name="borrower_hp2" id="borrower_hp2"
                                    style="width: 140px;" onKeypress="return numbersonly(this, event)"
                                    value="<?=$new["borrower_hp2"];?>" />
                                <button class="btn btn-sm btn-success" href="#" ref="borrower_hp2"
                                    id="btn_dial_borrower_hp2" onClick="dialtoCs(this)" tobe='dial'>dial</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>PHONE 3</small></label>
                            <div class="col-sm-6 input-group">
                                <input class="form-control" type="text" readonly name="borrower_hp3" id="borrower_hp3"
                                    style="width: 140px;" onKeypress="return numbersonly(this, event)"
                                    value="<?=$new["borrower_hp3"];?>" />
                                <button class="btn btn-sm btn-success" href="#" ref="borrower_hp3"
                                    id="btn_dial_borrower_hp3" onClick="dialtoCs(this)" tobe='dial'>dial</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php } else if($type=='NAD') {?>
            <div class="col-6">
                <h4 class="blue">Current Address</h4>
                <div class="row slim-scroll" data-height="225" id="new_phone_address_form">
                    <form class="form-horizontal" role="form" id="fNewAddress">

                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>Province</small></label>
                            <div class="col-sm-6 input-group">
                                <input class="form-control" type="text" readonly style="width: 140px;"
                                    onKeypress="return numbersonly(this, event)" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>City</small></label>
                            <div class="col-sm-6 input-group">
                                <input class="form-control" type="text" readonly style="width: 140px;"
                                    onKeypress="return numbersonly(this, event)" value="<?=$old['cr_city']?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>District</small></label>
                            <div class="col-sm-6 input-group">
                                <input class="form-control" type="text" readonly style="width: 140px;"
                                    onKeypress="return numbersonly(this, event)" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>Sub-district</small></label>
                            <div class="col-sm-6 input-group">
                                <input class="form-control" type="text" readonly style="width: 140px;"
                                    onKeypress="return numbersonly(this, event)" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>ZIPCODE</small></label>
                            <div class="col-sm-6 input-group">
                                <input class="form-control" type="text" readonly style="width: 140px;"
                                    onKeypress="return numbersonly(this, event)" value="<?=$old['cr_zip_code']?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>Address</small></label>
                            <div class="col-sm-6 input-group">
                                <textarea class="form-control" disabled><?=$old['cr_addr_1']?></textarea>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <div class="col-6">
                <h4 class="blue">New Address</h4>
                <div class="row slim-scroll" data-height="225" id="new_phone_address_form">
                    <form class="form-horizontal" role="form" id="fNewPhone">
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>Province</small></label>
                            <div class="col-sm-6 input-group">
                                <input class="form-control" type="text" readonly style="width: 140px;"
                                    onKeypress="return numbersonly(this, event)"
                                    value="<?=$new['borrower_provinsi']?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>City</small></label>
                            <div class="col-sm-6 input-group">
                                <input class="form-control" type="text" readonly style="width: 140px;"
                                    onKeypress="return numbersonly(this, event)" value="<?=$new['borrower_kota']?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>District</small></label>
                            <div class="col-sm-6 input-group">
                                <input class="form-control" type="text" readonly style="width: 140px;"
                                    onKeypress="return numbersonly(this, event)"
                                    value="<?=$new['borrower_kecamatan']?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>Sub-district</small></label>
                            <div class="col-sm-6 input-group">
                                <input class="form-control" type="text" readonly style="width: 140px;"
                                    onKeypress="return numbersonly(this, event)"
                                    value="<?=$new['borrower_kelurahan']?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>ZIPCODE</small></label>
                            <div class="col-sm-6 input-group">
                                <input class="form-control" type="text" readonly style="width: 140px;"
                                    onKeypress="return numbersonly(this, event)" value="<?=$new['borrower_zip']?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label no-padding-right"><small>Address</small></label>
                            <div class="col-sm-6 input-group">
                                <textarea class="form-control" disabled><?=$new['borrower_alamat']?></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php } ?>
        </div>

    </div>
</div>
<!--
<button class="btn btn-sm btn-primary" id="btn_lookup_contract">View Contract</button>
<div style="clear: both;">&nbsp;</div>
-->
<script type="text/javascript">
    var GLOBAL_NEW_PHONE_ADDRESS_VARS = new Array();


    $(document).ready(function () {
        outbound();

        if ($("#borrower_hp1").val() == '') {
            $("#btn_dial_borrower_hp1").attr('disabled', true);
        }
        if ($("#borrower_hp2").val() == '') {
            $("#btn_dial_borrower_hp2").attr('disabled', true);
        }
        if ($("#borrower_hp3").val() == '') {
            $("#btn_dial_borrower_hp3").attr('disabled', true);
        }
        $("#btn_lookup_contract").click(function () {
            showCommonDialog2(1024, 490, 'Contract Detail', site_url + '/contract_detail_lookup');
        });
    });

    function dialtoCs(elm) {
        event.preventDefault();
        console.log('elm', elm.id);
        let id = elm.id;
        let ref = $("#" + id).attr('ref');
        let tobe = $("#" + id).attr('tobe');

        console.log('tobe...', tobe);
        if (tobe == 'dial') {

            let contract_number_new_data = $("#contract_number_new_data").val();
            let phone = $("#" + ref).val();
            console.log("#" + ref);
            if (phone != '') {

                setTimeout(() => {
                    console.log('dialling...', phone);
                    originateCall(phone, contract_number_new_data);
                }, 1000);
                $("#" + id).attr('tobe', 'dc');
                $("#" + id).removeClass('btn-success');
                $("#" + id).addClass('btn-danger');
            } else {
                showWarning('phone empty');
            }
        } else {
            $("#" + id).attr('tobe', 'dial');
            disconnectCall();
            $("#" + id).addClass('btn-success');
            $("#" + id).removeClass('btn-danger');
        }

    }
</script>