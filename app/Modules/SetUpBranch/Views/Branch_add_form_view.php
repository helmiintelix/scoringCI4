<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="txt-id-branch" class="fs-6 text-capitalize">ID BRANCH</label>
                <input type="text" id="txt-id-branch" name="txt-id-branch"
                    class="form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="txt-name-branch" class="fs-6 text-capitalize">NAME BRANCH</label>
                <input type="text" id="txt-name-branch" name="name-branch"
                    class="form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="opt-branch-province" class="fs-6 text-capitalize">PROVINCE</label>
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-branch-province" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-branch-province', $province,'', $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="opt-branch-city" class="fs-6 text-capitalize">CITY</label>
                <?php
                    $options = array(
                        '' => '[SELECT DATA]');
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-branch-city" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-branch-city', $options, "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="opt-branch-district" class="fs-6 text-capitalize">DISTRICT</label>
                <?php
                    $options = array(
                        '' => '[SELECT DATA]');
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-branch-district" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-branch-district', $options, "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="opt-branch-sub-district" class="fs-6 text-capitalize">SUB DISTRICT</label>
                <?php
                    $options = array(
                        '' => '[SELECT DATA]');
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-branch-sub-district" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-branch-sub-district', $options, "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="txt-address" class="fs-6 text-capitalize">ADDRESS</label>
                <input type="text" id="txt-address" name="txt-address" class="form-control form-control-sm mandatory"
                    required />
            </div>
            <div class="mb-3 ">
                <label for="txt-phone-number" class="fs-6 text-capitalize">PHONE NUMBER</label>
                <input type="text" id="txt-phone-number" name="txt-phone-number"
                    class="form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="opt-branch-manager" class="fs-6 text-capitalize">MANAGER NAME</label>
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-branch-manager data-placeholder ="[select]" required';
                    echo form_dropdown('opt-branch-manager', $manager,'', $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="form-field-select-2" class="fs-6 text-capitalize">Status</label>
                <div class="form-check form-switch">
                    <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
                    <input onChange="isActive(this)" class="form-check-input" type="checkbox" role="switch"
                        id="flexSwitchCheckChecked" checked>
                </div>
            </div>

            <div class="mb-3 " style="display:none">
                <label for="form-field-select-2" class="fs-6 text-capitalize">Is Active</label>
                <?php
				$options = array(
					'1' => 'ENABLE',
					'0'	=> 'DISABLE',
				);
				$attributes = 'class="form-control form-control-sm  mandatory" id="opt-active-flag" data-placeholder ="[select]" required';
				echo form_dropdown('opt-active-flag', $options, '1', $attributes);
				?>
            </div>



        </div>
    </div>

</form>

<script src="<?= base_url(); ?>modules/set_up_branch/js/script_add_form.js?v=<?= rand() ?>"></script>