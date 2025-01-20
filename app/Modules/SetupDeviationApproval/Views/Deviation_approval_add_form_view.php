<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="txt-deviation_approval-dev_app_id" class="fs-6 text-capitalize">DEV/APP ID</label>
                <input type="text" id="txt-deviation_approval-dev_app_id" name="txt-deviation_approval-dev_app_id"
                    class="form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="txt-deviation_approval-dev_app_name" class="fs-6 text-capitalize">DEVIATION APPROVAL
                    NAME</label>
                <input type="text" id="txt-deviation_approval-dev_app_name" name="txt-deviation_approval-dev_app_name"
                    class="form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="opt-deviation_approval-dev_ref_id" class="fs-6 text-capitalize">DEVIATION REFERENCE</label>
                <?php
                    $attributes = 'class="chosen-select form-control form-control-sm mandatory" id="opt-deviation_approval-dev_ref_id" name="opt-deviation_approval-dev_ref_id[]"  multiple data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-deviation_approval-dev_ref_id[]', $deviation_reference,  "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="opt-deviation_approval-app_1_user_1" class="fs-6 text-capitalize">APPROVAL 1</label>
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-deviation_approval-app_1_user_1" name="opt-deviation_approval-app_1_user_1" data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-deviation_approval-app_1_user_1', $userlist,  "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-deviation_approval-app_1_user_2" name="opt-deviation_approval-app_1_user_2" data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-deviation_approval-app_1_user_2', $userlist,  "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-deviation_approval-app_1_user_3" name="opt-deviation_approval-app_1_user_3" data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-deviation_approval-app_1_user_3', $userlist,  "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="opt-deviation_approval-app_2_user_1" class="fs-6 text-capitalize">APPROVAL 2</label>
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-deviation_approval-app_2_user_1" name="opt-deviation_approval-app_2_user_1" data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-deviation_approval-app_2_user_1', $userlist,  "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-deviation_approval-app_2_user_2" name="opt-deviation_approval-app_2_user_2" data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-deviation_approval-app_2_user_2', $userlist,  "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-deviation_approval-app_2_user_3" name="opt-deviation_approval-app_2_user_3" data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-deviation_approval-app_2_user_3', $userlist,  "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="opt-deviation_approval-app_3_user_1" class="fs-6 text-capitalize">APPROVAL 3</label>
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-deviation_approval-app_3_user_1" name="opt-deviation_approval-app_3_user_1" data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-deviation_approval-app_3_user_1', $userlist,  "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-deviation_approval-app_3_user_2" name="opt-deviation_approval-app_3_user_2" data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-deviation_approval-app_3_user_2', $userlist,  "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-deviation_approval-app_3_user_3" name="opt-deviation_approval-app_3_user_3" data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-deviation_approval-app_3_user_3', $userlist,  "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="opt-deviation_approval-app_4_user_1" class="fs-6 text-capitalize">APPROVAL 4</label>
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-deviation_approval-app_4_user_1" name="opt-deviation_approval-app_4_user_1" data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-deviation_approval-app_4_user_1', $userlist,  "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-deviation_approval-app_4_user_2" name="opt-deviation_approval-app_4_user_2" data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-deviation_approval-app_4_user_2', $userlist,  "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-deviation_approval-app_4_user_3" name="opt-deviation_approval-app_4_user_3" data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-deviation_approval-app_4_user_3', $userlist,  "", $attributes);
				?>
            </div>
        </div>
    </div>
</form>
<script src="<?= base_url(); ?>modules/setup_deviation_approval/js/setup_deviation_approval_add.js?v=<?= rand() ?>">
</script>