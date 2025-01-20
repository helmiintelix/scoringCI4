<?php
    if($is_save=='1' || $is_save==1){
        echo "<center><i>[PREVIEW]</i></center>";
    }
?>
<input type="hidden" name="<?= csrf_token(); ?>" id="csrf_token" value="<?= csrf_hash(); ?>">
<div class="row mb-2">
	<label class="form-label fs-6" for="newhp1"><small>Loan No.</small></label>
	<div class="col-sm-12 ">
		<input type='hidden' name='call_id_new_phone' id='call_id_new_phone' value="<?=$collection_history_id?>"
			readonly />
		<input type='text' class="form-control" name='contract_number_new_phone' id='contract_number_new_phone'
			value="<?=$contract_number?>" readonly />
	</div>
</div>

<div class="row mb-2">
	<label class="form-label fs-6" for="newhp1"><small>PHONE 1</small></label>
	<div class="col-sm-12 ">
		<input type='text' class="form-control" name='newhp1' id='newhp1' onkeydown="return numbOnly(event);"
			value="<?=@$acs_temp_phone[0]['borrower_hp1']?>" />
	</div>
</div>
<div class="row mb-2">
	<label class="form-label fs-6" for="newhp2"><small>PHONE 2</small></label>
	<div class="col-sm-12 ">
		<input type='text' class="form-control" name='newhp2' id='newhp2' onkeydown="return numbOnly(event);"
			value="<?=@$acs_temp_phone[0]['borrower_hp2']?>" />
	</div>
</div>
<div class="row mb-2">
	<label class="form-label fs-6" for="newhp3"><small>PHONE 3</small></label>
	<div class="col-sm-12 ">
		<input type='text' class="form-control" name='newhp3' id='newhp3' onkeydown="return numbOnly(event);"
			value="<?=@$acs_temp_phone[0]['borrower_hp3']?>" />
	</div>
</div>

<script>
	var is_save = "<?=$is_save;?>";
</script>
<script src="<?= base_url(); ?>modules/detail_account/js/add_new_phone.js?v=<?= rand() ?>">
</script>