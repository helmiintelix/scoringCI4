<input type="hidden" name="<?= csrf_token(); ?>" id="csrf_token" value="<?= csrf_hash(); ?>">
<?php
    if($is_save=='1' || $is_save==1){
        echo "<center><i>[PREVIEW]</i></center>";
    }
?>
<div class="row mb-2">
	<label class="form-label fs-6" for="newhp1"><small>No. Loan</small></label>
	<div class="col-sm-12">
		<input type='hidden' name='call_id_new_phone' id='call_id_new_phone' value="<?=$collection_history_id?>"
			readonly />
		<input type='text' class="form-control" name='contract_number_new_phone' id='contract_number_new_phone'
			value="<?=$contract_number?>" readonly />
	</div>
</div>
<div class="row mb-2">
	<label class="form-label fs-6" for="province"><small>Province</small></label>
	<div class="col-sm-12">
		<select class="form-select" name='provinsi' id='provinsi'>
			<option>[select data]</option>
			<?php 
                foreach ($provinsi as $key => $value) {
                    echo "<option value='".$value['provinsi']."'>".$value['provinsi']."</option>";
                }
            ?>
		</select>
	</div>
</div>
<div class="row mb-2">
	<label class="form-label fs-6" for="city"><small>City</small></label>
	<div class="col-sm-12">
		<select class="form-select" name='city' id='city'>
			<option>[select data]</option>
		</select>
	</div>
</div>
<div class="row mb-2">
	<label class="form-label fs-6" for="district"><small>District</small></label>
	<div class="col-sm-12">
		<select class="form-select" name='district' id='district'>
			<option>[select data]</option>
		</select>
	</div>
</div>
<div class="row mb-2">
	<label class="form-label fs-6" for="sub-district"><small>Sub-district</small></label>
	<div class="col-sm-12">
		<select class="form-select" name='sub-district' id='sub-district'>
			<option>[select data]</option>
		</select>
	</div>
</div>
<div class="row mb-2">
	<label class="form-label fs-6" for="zipcode"><small>ZIPCODE</small></label>
	<div class="col-sm-12">
		<select class="form-select" name='zipcode' id='zipcode'>
			<option>[select data]</option>
		</select>
	</div>
</div>
<div class="row mb-2">
	<label class="form-label fs-6" for="address"><small>Address</small></label>
	<div class="col-sm-12">
		<textarea class="form-control" id='address' name='address'></textarea>
	</div>
</div>
<script type="text/javascript">
	var is_save = "<?=$is_save;?>";
	var provinsiVal = "<?=@$acs_temp_phone[0]['borrower_provinsi'];?>";
	var cityVal = "<?=@$acs_temp_phone[0]['borrower_kota'];?>";
	var districtVal = "<?=@$acs_temp_phone[0]['borrower_kecamatan'];?>";
	var subdistrictVal = "<?=@$acs_temp_phone[0]['borrower_kelurahan'];?>";
	var zipcodeVal = "<?=@$acs_temp_phone[0]['borrower_zip'];?>";
	var addressVal = "<?=@$acs_temp_phone[0]['borrower_alamat'];?>";
</script>
<script src="<?= base_url(); ?>modules/detail_account/js/add_new_address.js?v=<?= rand() ?>">
</script>