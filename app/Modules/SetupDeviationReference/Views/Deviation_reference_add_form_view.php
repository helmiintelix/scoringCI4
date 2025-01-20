<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="txt-deviation_reference-deviation_id" class="fs-6 text-capitalize">DEVIATION ID</label>
                <input type="text" id="txt-deviation_reference-deviation_id" name="txt-deviation_reference-deviation_id"
                    class="form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="txt-deviation_reference-deviation_name" class="fs-6 text-capitalize">DEVIATION NAME</label>
                <input type="text" id="txt-deviation_reference-deviation_name"
                    name="txt-deviation_reference-deviation_name" class="form-control form-control-sm mandatory"
                    required />
            </div>
            <div class="mb-3 ">
                <label for="opt-deviation_reference-product" class="fs-6 text-capitalize">PRODUCT</label>
                <?php
                    $attributes = 'class="chosen-select form-control form-control-sm mandatory" id="opt-deviation_reference-product" name="opt-deviation_reference-product[]"  multiple data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-deviation_reference-product[]', $product,  "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="opt-deviation_reference-type" class="fs-6 text-capitalize">TYPE</label>
                <?php
                    $options = array(
                        'DISKON PELUNASAN' => 'DISKON PELUNASAN',
                        'RESTRUCTURE' => 'RESTRUCTURE',
                        'RESCHEDULE' => 'RESCHEDULE'
                    );
                    $attributes = 'class="chosen-select form-control form-control-sm mandatory" id="opt-deviation_reference-type" name="opt-deviation_reference-type[]"  multiple data-placeholder="-Please Select Product-"';
                    echo form_dropdown('opt-deviation_reference-type[]', $options,  "", $attributes);
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
<script src="<?= base_url(); ?>modules/setup_deviation_reference/js/setup_deviation_reference_add.js?v=<?= rand() ?>">
</script>