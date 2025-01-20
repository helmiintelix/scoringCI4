<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
	<div class="row">
		<div class="col col-sm-12">
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">TEMPLATE ID </label>
				<input type="text" id="txt_wa_template_template_id" name="txt_wa_template_template_id" class="form-control form-control-sm mandatory" required/>
			</div>
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">TEMPLATE NAME </label>
				<input type="text" id="txt_wa_template_template_name" name="txt_wa_template_template_name" class="form-control form-control-sm mandatory" required/>
			</div>
			<div class="mb-3 ">
				<label for="txt-template-input-times1" class="fs-6 text-capitalize">SEND TIME </label>
				<input style="width: 20%;" type="time" id="txt-template-input-times1" name="txt-template-input-times1" class="form-control form-control-sm mandatory" required/>
			</div>
			<div class="mb-3">
                <label for="form-field-select-2" class="fs-6 text-capitalize">PARAMETER</label>
                <div style="display: flex; align-items: center;">
                    <?php
                        $attributes = 'class="form-control form-control-sm mandatory" id="txt-template-input-param1" name="txt-template-input-param[]" data-placeholder="[select]" required';
                        echo form_dropdown('txt-template-input-param[]', $value_mapping, '', $attributes);
                    ?>
                    <i id="btn_add_param1" class="bi bi-plus-square btn_add_param" style="cursor: pointer;font-size:25px; margin-left:8px;"></i>
                </div>
                <div id="dynamic_field" ></div>
            </div>
			<div class="mb-3">
                <label for="txt_wa_template_template_design" class="fs-6 text-capitalize">TEMPLATE DESIGN</label>
                <textarea id="txt_wa_template_template_design" name="txt_wa_template_template_design" class="form-control form-control-sm mandatory" rows="4" required></textarea>
            </div>
            
			<div class="mb-3 ">
				<label for="txt_wa_max_retry" class="fs-6 text-capitalize"> MAX RETRY </label>
				<input type="text" id="txt_wa_max_retry" name="txt_wa_max_retry" class="form-control form-control-sm mandatory" required/>
			</div>
			
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Status</label>
				<div class="form-check form-switch">
					<label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
					<input onChange="isActive(this)" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
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
<script type="text/javascript">
var arrSelect = <?= json_encode($value_mapping) ?>;
</script>

<script src="<?= base_url(); ?>modules/setup_wa_template/js/setup_wa_template_add.js?v=<?= rand() ?>"></script>