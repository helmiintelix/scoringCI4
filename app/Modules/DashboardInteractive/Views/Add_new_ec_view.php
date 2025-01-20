<input type="hidden" name="<?= csrf_token(); ?>" id="csrf_token" value="<?= csrf_hash(); ?>">
<?php if ($is_save == '1' || $is_save == 1) : ?>
<div class="text-center"><i>[PREVIEW]</i></div>
<?php endif; ?>

<div class="row mt-2">
	<label class="col-sm-3 col-form-label"><small>Loan No.</small></label>
	<div class="col-sm-9">
		<input type="hidden" name="call_id_new_ec" id="call_id_new_ec" value="<?= $collection_history_id ?>" readonly />
		<input type="text" name="contract_number_new_ec" id="contract_number_new_ec" value="<?= $contract_number ?>"
			readonly class="form-control" />
	</div>
</div>

<div class="row mt-2">
	<label class="col-sm-3 col-form-label"><small>EC Name</small></label>
	<div class="col-sm-9">
		<input type="text" name="ecName" id="ecName" value="<?= @$acs_temp_phone[0]['borrower_ecName'] ?>"
			class="form-control" />
	</div>
</div>
<div class="row mt-2">
	<label class="col-sm-3 col-form-label"><small>EC Phone</small></label>
	<div class="col-sm-9">
		<input type="text" name="ecPhone" id="ecPhone" value="<?= @$acs_temp_phone[0]['borrower_ecPhone'] ?>"
			class="form-control" />
	</div>
</div>
<div class="row mt-2">
	<label class="col-sm-3 col-form-label"><small>EC Address</small></label>
	<div class="col-sm-9">
		<input type="text" name="ecAddress" id="ecAddress" value="<?= @$acs_temp_phone[0]['borrower_ecAddress'] ?>"
			class="form-control" />
	</div>
</div>

<script>
	var is_save = "<?= $is_save; ?>";

	$(document).ready(() => {
		if (is_save == '1') {
			$(".btn-save-AddNewPhone, #ecName, #ecPhone, #ecAddress").prop('disabled', true);
		} else {
			$(".btn-save-AddNewPhone").prop('disabled', false);
		}
	});
</script>