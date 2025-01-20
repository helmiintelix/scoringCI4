<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3">
                <label for="txt-collector-name" class="fs-6 text-capitalize"> FIELD COLLECTOR NAME</label>
                <?php
				$attributes = 'class="form-control form-control-sm  mandatory" id="txt-collector-name" name="txt-collector-name" data-placeholder ="[select]"';
				echo form_dropdown('txt-collector-name', $collector, '', $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="txt-sub_area-name" class="fs-6 text-capitalize">SUB AREA NAME</label>
                <?php
                    $attributes = 'class="chosen-select form-control form-control-sm mandatory" id="txt-sub_area-name" name="txt-sub_area-name[]"  multiple data-placeholder="-Please Select Product-"';
                    echo form_dropdown('txt-sub_area-name[]', $sub_area,  "", $attributes);
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

<script src="<?= base_url(); ?>modules/setup_fieldcoll_area_mapping/js/script_add_form.js?v=<?= rand() ?>"></script>