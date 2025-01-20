<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
	<input type="hidden" name="id" id ="id" value="<?= $data_template['wa_id'] ?>" />
	<div class="row">
		<div class="col col-sm-12">
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">CHILD NAME </label>
				<input type="text" id="txt_wa_child_name" name="txt_wa_child_name" class="form-control form-control-sm mandatory" value="<?= $data_template['template_name']?>" required/>
			</div>
			<div class="mb-3">
                <label for="form-field-select-2" class="fs-6 text-capitalize">Relation Template Blast</label>
                <div style="display: flex; align-items: center;">
                    <?php
                        $attributes = 'class="form-control form-control-sm mandatory" id="opt_wa_template" name="opt_wa_template[]" data-placeholder="[select]" required';
                        echo form_dropdown('opt_wa_template[]', $list_template, $data_template['mask_name'], $attributes);
                    ?>
            </div>
            <div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Label Option</label>
				<input type="text" id="txt_wa_label_name" name="txt_wa_label_name" class="form-control form-control-sm mandatory" value="<?= $data_template['label_ops']?>" required/>
			</div>
			<div class="mb-3">
                <label for="form-field-select-2" class="fs-6 text-capitalize">Number Option</label>
                <div style="display: flex; align-items: center;">
                    <?php
                        $attributes = 'class="form-control form-control-sm mandatory" id="opt_wa_label_number" name="opt_wa_label_number[]" data-placeholder="[select]" required';
                        echo form_dropdown('opt_wa_label_number[]', $list_order, $data_template['order_num'], $attributes);
                    ?>
            </div>
            <div class="mb-3">
                <label for="txt_wa_template_template_design" class="fs-6 text-capitalize">Message</label>
                <textarea id="txt_wa_template_template_design" name="txt_wa_template_template_design" class="form-control form-control-sm mandatory" rows="4" placeholder="pakai {{1}} dan seterusnya untuk menggunakan parameter" required><?= $data_template['preview_message']?></textarea>
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
var params = "<?= $data_template["parameter"] ?>";
var select = <?= json_encode($value_mapping);?>;
</script>

<script src="<?= base_url(); ?>modules/setup_wa_flow/js/setup_wa_flow_edit.js?v=<?= rand() ?>"></script>