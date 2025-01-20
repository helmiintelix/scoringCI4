<input type="hidden" name="<?= csrf_token(); ?>" id="csrf_token" value="<?= csrf_hash(); ?>">
<?php if ($is_save == '1' || $is_save == 1) : ?>
<div class="text-center"><i>[PREVIEW]</i></div>
<?php endif; ?>

<div class="row mt-2">
	<label class="col-sm-3 col-form-label"><small>Loan No.</small></label>
	<div class="col-sm-9">
		<input type="hidden" name="call_id_new_mail" id="call_id_new_mail" value="<?= $collection_history_id ?>"
			readonly />
		<input type="text" name="contract_number_new_mail" id="contract_number_new_mail" value="<?= $contract_number ?>"
			readonly class="form-control" />
	</div>
</div>

<div class="row mt-2">
	<label class="col-sm-3 col-form-label"><small>EMAIL 1</small></label>
	<div class="col-sm-9">
		<input type="text" name="newMail1" id="newMail1" value="<?= @$acs_temp_phone[0]['borrower_mail1'] ?>"
			class="form-control" />
	</div>
</div>
<div class="row mt-2">
	<label class="col-sm-3 col-form-label"><small>EMAIL 2</small></label>
	<div class="col-sm-9">
		<input type="text" name="newMail2" id="newMail2" value="<?= @$acs_temp_phone[0]['borrower_mail2'] ?>"
			class="form-control" />
	</div>
</div>
<div class="row mt-2">
	<label class="col-sm-3 col-form-label"><small>EMAIL 3</small></label>
	<div class="col-sm-9">
		<input type="text" name="newMail3" id="newMail3" value="<?= @$acs_temp_phone[0]['borrower_mail3'] ?>"
			class="form-control" />
	</div>
</div>

<script>
	var is_save = "<?= $is_save; ?>";

	$(document).ready(() => {
		if (is_save == '1') {
			$(".btn-save-AddNewPhone, #newMail1, #newMail2, #newMail3").prop('disabled', true);
		} else {
			$(".btn-save-AddNewPhone").prop('disabled', false);
		}
	});
</script>