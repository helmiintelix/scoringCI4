<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
	<div class="row">
		<div class="col col-sm-12">
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Template Name</label>
				<input type="text" id="txt_wa_template_template_name" name="txt_wa_template_template_name" class="form-control form-control-sm mandatory" required/>
			</div>
			<div class="mb-3">
                <label for="txt_wa_template_template_design" class="fs-6 text-capitalize">Message</label>
                <textarea id="txt_wa_template_template_design" name="txt_wa_template_template_design" class="form-control form-control-sm mandatory" rows="4" required></textarea>
            </div>
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">List Group</label>
		
				<div style="display: flex; align-items: center;">
                    <?php
                        $attributes = 'class="form-control form-control-sm mandatory" id="list_group" name="list_group[]" required multiple="multiple"';
                        echo form_dropdown('list_group[]', $list_group, '', $attributes);
                    ?>
                </div>
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
					'Y' => 'ENABLE',
					'N'	=> 'DISABLE',
				);
				$attributes = 'class="form-control form-control-sm  mandatory" id="opt-active-flag" data-placeholder ="[select]" required';
				echo form_dropdown('opt-active-flag', $options, 'Y', $attributes);
				?>
			</div>

		</div>
	</div>
	
</form>

<script src="<?= base_url(); ?>modules/setup_wa_quick_reply/js/setup_wa_quick_reply_add.js?v=<?= rand() ?>"></script>