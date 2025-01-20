<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="txt-am-id" class="fs-6 text-capitalize">VENDOR ID</label>
                <input type="text" id="txt-am-id" name="txt-am-id" class="form-control form-control-sm mandatory"
                    required value="<?=$agency_data["agency_id"]?>" readonly />
            </div>
            <div class="mb-3 ">
                <label for="txt-am-name" class="fs-6 text-capitalize">VENDOR NAME</label>
                <input type="text" id="txt-am-name" name="txt-am-name" class="form-control form-control-sm mandatory"
                    required value="<?=$agency_data["agency_name"]?>" />
            </div>
            <div class="mb-3 ">
                <label for="txt-am-address" class="fs-6 text-capitalize">VENDOR ADDRESS</label>
                <input type="text" id="txt-am-address" name="txt-am-address"
                    class="form-control form-control-sm mandatory" required
                    value="<?=$agency_data["agency_address"]?>" />
            </div>
            <div class="mb-3 ">
                <label for="txt-am-phone" class="fs-6 text-capitalize">VENDOR PHONE</label>
                <input type="text" id="txt-am-phone" name="txt-am-phone"
                    class="input-number form-control form-control-sm mandatory" required
                    value="<?=$agency_data["agency_phone"]?>" />
            </div>
            <div class="mb-3 ">
                <label for="txt-am-pic" class="fs-6 text-capitalize">VENDOR PIC</label>
                <input type="text" id="txt-am-pic" name="txt-am-pic" class="form-control form-control-sm mandatory"
                    required value="<?=$agency_data["agency_pic"]?>" />
            </div>
            <div class="mb-3 ">
                <label for="txt-am-pic-email" class="fs-6 text-capitalize">VENDOR PIC EMAIL</label>
                <input type="text" id="txt-am-pic-email" name="txt-am-pic-email"
                    class="form-control form-control-sm mandatory" required value="<?=$agency_data["pic_email"]?>" />
            </div>
            <div class="mb-3 ">
                <label for="txt-am-spv-email" class="fs-6 text-capitalize">VENDOR SPV EMAIL</label>
                <input type="text" id="txt-am-spv-email" name="txt-am-spv-email"
                    class="form-control form-control-sm mandatory" required value="<?=$agency_data["spv_email"]?>" />
            </div>
            <div class="mb-3 ">
                <label for="notaris_datepicker" class="fs-6 text-capitalize">NOTARIS DATE</label>
                <input type="text" id="notaris_datepicker" name="notaris_datepicker"
                    class="form-control form-control-sm mandatory" required value="<?=$agency_data["tgl_notaris"]?>" />
            </div>
            <div class="mb-3 ">
                <label for="txt-notaris-no" class="fs-6 text-capitalize">NOTARIS NO</label>
                <input type="text" id="txt-notaris-no" name="txt-notaris-no"
                    class="input-number form-control form-control-sm mandatory" required
                    value="<?=$agency_data["no_notaris"]?>" />
            </div>
            <div class="mb-3 ">
                <label for="opt-agency-province" class="fs-6 text-capitalize">VENDOR PROVINCE</label>
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-agency-province" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-agency-province', $data_province, $agency_data['agency_prov'], $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="opt-agency-city" class="fs-6 text-capitalize">VENDOR CITY</label>
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-agency-city" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-agency-city', $data_city,  $agency_data['agency_city'], $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="opt-agency-district" class="fs-6 text-capitalize">VENDOR DISTRICT</label>
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-agency-district" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-agency-district', $data_kecamatan,  $agency_data['agency_kec'], $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="opt-agency-sub-district" class="fs-6 text-capitalize">VENDOR SUB DISTRICT</label>
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-agency-sub-district" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-agency-sub-district', $data_kelurahan, $agency_data['agency_kel'], $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="datepicker" class="fs-6 text-capitalize">START CONTRACT</label>
                <input type="text" id="datepicker" name="datepicker" class="form-control form-control-sm mandatory"
                    required value="<?=$agency_data["agency_contract_start"]?>" />
            </div>
            <div class="mb-3 ">
                <label for="datepicker2" class="fs-6 text-capitalize">END CONTRACT</label>
                <input type="text" id="datepicker2" name="datepicker2" class="form-control form-control-sm mandatory"
                    required value="<?=$agency_data["agency_contract_end"]?>" />
            </div>
            <div class="mb-3 ">
                <label for="proposal_datepicker" class="fs-6 text-capitalize">PROPOSAL DATE</label>
                <input type="text" id="proposal_datepicker" name="proposal_datepicker"
                    class="form-control form-control-sm mandatory" required
                    value="<?=$agency_data["proposal_date"]?>" />
            </div>
            <div class="mb-3 ">
                <label for="assessment_datepicker" class="fs-6 text-capitalize">ASSESSMENT LETTER DATE</label>
                <input type="text" id="assessment_datepicker" name="assessment_datepicker"
                    class="form-control form-control-sm mandatory" required
                    value="<?=$agency_data["assessment_letter_date"]?>" />
            </div>
            <div class="mb-3 ">
                <label for="txt-assessment-no" class="fs-6 text-capitalize">ASSESSMENT LETTER NO</label>
                <input type="text" id="txt-assessment-no" name="txt-assessment-no"
                    class="input-number form-control form-control-sm mandatory" required
                    value="<?=$agency_data["assessment_letter_no"]?>" />
            </div>
            <div class="mb-3 ">
                <label for="offering_datepicker" class="fs-6 text-capitalize">OFFERING LETTER DATE</label>
                <input type="text" id="offering_datepicker" name="offering_datepicker"
                    class="form-control form-control-sm mandatory" required
                    value="<?=$agency_data["tgl_penawaran"]?>" />
            </div>
            <div class="mb-3 ">
                <label for="opt-am-coordinator" class="fs-6 text-capitalize">VENDOR COORDINATOR</label>
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-am-coordinator data-placeholder ="[select]" required';
                    echo form_dropdown('opt-am-coordinator', $arco_id, $agency_data["arco_id"], $attributes);
				?>
            </div>
        </div>
    </div>

</form>

<script src="<?= base_url(); ?>modules/agency_management/js/script_edit_form.js?v=<?= rand() ?>"></script>