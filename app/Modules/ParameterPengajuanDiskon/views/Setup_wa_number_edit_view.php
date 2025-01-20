<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
	<div class="row">
		<div class="col col-sm-12">
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">ID</label>
				<input type="text" id="id" name="id" class="form-control form-control-sm mandatory" value="<?=$data['id']?>"  readonly/>
			</div>
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">WA Phone Number</label>
				<input type="text" id="phone" name="phone" onkeydown="isNumber()" class="form-control form-control-sm mandatory" value="<?=$data['phone']?>" required/>
			</div>
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">EXPIRED DATE</label>
				<input type="text" id="expired_date" name="expired_date" class="form-control form-control-sm mandatory" value="<?=$data['expired_date']?>" required/>
			</div>
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">TOKEN</label>
				<input type="text" id="token" name="token" class="form-control form-control-sm mandatory" value="<?=$data['token']?>" required/>
			</div>
			<div class="mb-3 ">
				<label for="form-field-select-2" class="fs-6 text-capitalize">SERVER</label>
				<input type="text" id="server" name="server" class="form-control form-control-sm mandatory" value="<?=$data['group_name']?>" required/>
			</div>

            <div class="mb-3 ">
                <label for="form-field-select-2" class="fs-6 text-capitalize">Status</label>
                <div class="form-check form-switch">
                    <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
                    <input onChange="isActive(this)" class="form-check-input" type="checkbox" role="switch"
                        id="flexSwitchCheckChecked"
                        <?php echo $data["status"]=='1'?  'checked' :  ''; ?>>
                </div>
            </div>

			<div class="mb-3 " style="display:none">
				<label for="form-field-select-2" class="fs-6 text-capitalize">Is Active <?=$data["status"];?></label>
				<?php
				$options = array(
					'1' => 'ENABLE',
					'0'	=> 'DISABLE',
				);
				$attributes = 'class="form-control form-control-sm  mandatory" id="opt-active-flag" data-placeholder ="[select]" required';
				echo form_dropdown('opt-active-flag', $options, $data["status"], $attributes);
				?>
			</div>

		</div>
	</div>
	
</form>

<script src="<?= base_url(); ?>modules/setup_wa_number/js/setup_wa_number_edit.js?v=<?= rand() ?>"></script>