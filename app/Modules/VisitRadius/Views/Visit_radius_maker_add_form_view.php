<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="form-field-select-2" class="fs-6 text-capitalize">ID</label>
                <input type="text" id="txt-id" name="txt-id" class="form-control form-control-sm mandatory" required />
            </div>


            <div class="mb-3 ">
                <label for="form-field-select-2" class="fs-6 text-capitalize">LABEL</label>
                <input type="text" id="txt-label" name="txt-label" class="form-control form-control-sm mandatory"
                    required />
            </div>
            <div class="mb-3 ">
                <label for="form-field-select-2" class="fs-6 text-capitalize">FC NAME</label>
                <!-- <input type="text" id="txt-fcname" name="txt-fcname" class="form-control form-control-sm mandatory"
                required /> -->
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="txt-fc-name" data-placeholder ="[select]" required';
                    echo form_dropdown('txt-fc-name', $list_fc,'', $attributes);
                    ?>
            </div>

            <div class="mb-3 ">
                <label for="form-field-select-2" class="fs-6 text-capitalize">RADIUS (KM)</label>
                <input type="text" id="txt-radius" name="txt-radius" class="form-control form-control-sm mandatory"
                    required />
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
<script src="<?= base_url(); ?>modules/visit_radius/js/script_add_form.js?v=<?= rand() ?>"></script>