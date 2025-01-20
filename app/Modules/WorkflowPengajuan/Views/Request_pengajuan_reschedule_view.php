<style>
	.profile-info-name {
		width: 150px;
		padding-top: 1px;
		padding-bottom: 1px;
	}

	.profile-info-value {
		margin-left: 150px;
		padding-top: 1px;
		padding-bottom: 1px;
	}

	.input_lebel {
		background-color: lightgreen;
		color: black;
		width: 150px;
		padding-top: 1px;
		padding-bottom: 1px;
		border-top: 1px solid #f7fbff;
	}
</style>
<div id="payment-restructure-dom" height="800">
	<? 

$approval_list = array();		
$show = "";
if(session()->get('USER_GROUP') == "TEAM_LEADER"){
	$disabled="";
}else{
	//$disabled="disabled";
	$disabled="";
}
if($screen_level == 'VERIFICATION'){
	$disabled="disabled";
	//$approval_list = $this->detail_account_model->get_approval_discount_list($card_no);
}else if($screen_level == 'new'){
	$show="hide";
	//$approval_list = $this->detail_account_model->get_approval_discount_list($card_no);
}
?>
	<!-- #PINDAH DI VIEW detail_contract_request_program_view.php -->
	<!-- <form class="form-horizontal" id="formDiscountRequest" name="formDiscountRequest" method="POST"> -->
	<input type="hidden" name="<?= csrf_token() ?>" id="token_csrf" value="<?= csrf_hash() ?>" />
	<input type="hidden" id="txt_screen_level" name="txt_screen_level" value="<?= $screen_level ?>">
	<input type="hidden" id="approval_notes" name="approval_notes" value="">
	<input type="hidden" id="approval_status" name="approval_status" value="">
	<!-- belum ada workflow_pengajuan_reschedule/padapengajuan_program_form/$data["credit_data"]["CM_CARD_NMBR"] -->
	<input type="hidden" id="txt_card_number" name="txt_card_number" value="<?= $credit_data["CM_CARD_NMBR"] ?>">
	<input type="hidden" id="txt_mob" name="txt_mob" value="<?= $credit_data["MOB"] ?>">
	<input type="hidden" id="txt_bucket" name="txt_bucket" value="<?= $credit_data["CM_BUCKET"] ?>">
	<input type="hidden" id="txt_product_id" name="txt_product_id" value="<?= $credit_data["CM_TYPE"] ?>">
	<!-- belum ada workflow_pengajuan_reschedule/padapengajuan_program_form/$data["credit_data"]["PRODUCT_ID"-->
	<input type="hidden" id="txt_product_code" name="txt_product_code" value="<?= $credit_data["CM_TYPE"] ?>">
	<input type="hidden" id="txt_block_code" name="txt_block_code" value="<?= $credit_data["CM_BLOCK_CODE"] ?>">
	<input type="hidden" id="APPROVAL_LEVEL" name="APPROVAL_LEVEL" value="<?= @$discount_data["APPROVAL_LEVEL"] ?>">
	<input type="hidden" id="reschedule_rate" name="reschedule_rate">
	<input type="hidden" id="txt_hirarki" name="txt_hirarki">
	<!-- <input type="hidden" id="id" name="id"> -->
	<input type="hidden" id="source" name="source" value="<?= $source ?>">
	<input type="hidden" id="agent_id" name="agent_id" value="<?= $agent_id ?>">
	<input type="hidden" name="dpd" id="dpd" value="<?= $credit_data["DPD"] ?>">
	<input type="hidden" id="submit_id" name="submit_id" value="<?=date('YmdHis')?>">
	<input type="hidden" id="txt_id_pengajuan" name="txt_id_pengajuan" value="<?=@$pengajuan_data['id']?>">
	<input type="hidden" id="jenis_pengajuan" name="jenis_pengajuan" value="<?= @$discount_data["jenis_pengajuan"] ?>">


	<br>
	<div class="row">
		<div class="col-sm-12">
			<i><small id='alert_parameter' style="font-size: 12px;">-</small></i><br>
			<span id="lbl_nama_parameter"></span>
			<a href="#" class="pull-right">
				<span class="glyphicon glyphicon-search" onClick="search_param('NEW')"></span>
			</a>
			<table style="text-align: center" class="table table-bordered col-sm-12" id='tbl_filter_parameter'>
				<tbody>
					<th>Bucket</th>
					<th>Min. Outstanding</th>
					<th>Max. principle Rate %</th>
					<th>Max. Interest Rate %</th>
					<th>Max. late charge %</th>
					<th>Max. Tenor</th>
					<th>Ratio Cicilan %</th>
				</tbody>
				<tr>
					<td id="param_bucket"><?=$credit_data["CM_BUCKET"]?></td>
					<td id="param_min_outstanding">xxx</td>
					<td id="param_max_normal_disc_rate">xxx</td>
					<td id="param_max_interest_disc_rate">xxx</td>
					<td id="param_max_late_charge_disc_rate">xxx</td>
					<td id="param_max_tenor">xxx</td>
					<td id="param_cicilan">xxx</td>
				</tr>
			</table>

		</div>
	</div>
	<br>
	<br>
	<div class="row">
		<div class="col-sm-12" style="margin-top: 10px;">
			<div class="col-sm-6">
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Deviation</span>
					<?
						$opt = array('0'=>'NO','1'=>'YES');
						$attributes = 'class="form-control" id="flag_deviation" ';
						echo form_dropdown('flag_deviation', $opt, @$pengajuan_data['deviation_flag'], $attributes);
					?>
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="txt_deviation_reason_x"> Deviation Reason</span>
					<?
						$attributes = 'class="form-control" id="txt_deviation_reason" style="display:block;" disabled';
						echo form_dropdown('txt_deviation_reason', $deviation_reason, @$pengajuan_data['deviation_reason'], $attributes);
					?>
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> PTP Date</span>
					<input class="form-control" type="text" name="txt_ptp_date_for_rsc" id="txt_ptp_date_for_rsc" value="<?php if(isset($pengajuan_data['ptp_date'])) echo $pengajuan_data['ptp_date']; else echo date('Y-m-d') ;?>" >
				</div>
			</div>
			<div class="col-sm-6">

			</div>

		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-sm-6">
			<h4 class="">Input Perhitungan</h4>
			<div class="spinner-border spinner-border-sm" id="loading_kotak_merah" role="status"
				style="margin-top: 10px;display:none;margin-right: 10px;">
				<span class="visually-hidden" id="loading_kotak_merah">Loading...</span>
			</div>
			<div class="" id='kotak_merah'>
				<!-- start 11-->
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Outstanding Principal</span>
					<input class="form-control" type="text" name="txt-principle-balance" id="txt-principle-balance" value="<?php if(isset($pengajuan_data['principle_balance'])) echo number_format($pengajuan_data['principle_balance']); else echo '0' ;?>"   disabled>
					<input class="form-control" type="hidden" name="txt-principle-balance-val" id="txt-principle-balance-val"  value="<?php if(isset($pengajuan_data['principle_balance'])) echo $pengajuan_data['principle_balance']; else echo '0' ;?>"  >
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Outstanding Balance</span>
					<input class="form-control" type="text" name="txt-total-due-balance-installment" id="txt-total-due-balance-installment" value="<?php if(isset($pengajuan_data['total_due_balance_installment'])) echo number_format($pengajuan_data['total_due_balance_installment']); else echo '0' ;?>" disabled>
					<input class="form-control" type="hidden" name="txt-total-due-balance-installment-val" id="txt-total-due-balance-installment-val" value="<?php if(isset($pengajuan_data['total_due_balance_installment'])) echo $pengajuan_data['total_due_balance_installment']; else echo '0' ;?>" >
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Due Interest</span>
					<input class="form-control" type="text" name="txt-due-interest" id="txt-due-interest" value="<?php if(isset($pengajuan_data['due_interest'])) echo number_format($pengajuan_data['due_interest']); else echo '0' ;?>" disabled>
					<input class="form-control" type="hidden" name="txt-due-interest-val" id="txt-due-interest-val" value="<?php if(isset($pengajuan_data['due_interest'])) echo $pengajuan_data['due_interest']; else echo '0' ;?>" >
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Late Charge</span>
					<input class="form-control" type="text" name="txt-late-charge" id="txt-late-charge" value="<?php if(isset($pengajuan_data['late_charge'])) echo number_format($pengajuan_data['late_charge']); else echo '0' ;?>" disabled>
					<input class="form-control" type="hidden" name="txt-late-charge-val" id="txt-late-charge-val" value="<?php if(isset($pengajuan_data['late_charge'])) echo $pengajuan_data['late_charge']; else echo '0' ;?>" >
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Penalty</span>
					<input class="form-control" type="text" name="txt-penalty" id="txt-penalty" value="<?php if(isset($pengajuan_data['penalty'])) echo number_format($pengajuan_data['penalty']); else echo '0' ;?>" disabled>
					<input class="form-control" type="hidden" name="txt-penalty-val" id="txt-penalty-val" value="<?php if(isset($pengajuan_data['penalty'])) echo $pengajuan_data['penalty']; else echo '0' ;?>" >
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Stamp Duty</span>
					<input class="form-control" type="text" name="txt-stamp-duty" id="txt-stamp-duty" value="<?php if(isset($pengajuan_data['stamp_duty'])) echo number_format($pengajuan_data['stamp_duty']); else echo '0' ;?>" disabled>
					<input class="form-control" type="hidden" name="txt-stamp-duty-val" id="txt-stamp-duty-val" value="<?php if(isset($pengajuan_data['stamp_duty'])) echo $pengajuan_data['stamp_duty']; else echo '0' ;?>" >
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> <b>Total</b></span>
					<input class="form-control" type="text" name="txt-total" id="txt-total" value="<?php if(isset($pengajuan_data['total'])) echo number_format($pengajuan_data['total']); else echo '0' ;?>" disabled>
					<input class="form-control" type="hidden" name="txt-total-val" id="txt-total-val" value="<?php if(isset($pengajuan_data['total'])) echo $pengajuan_data['total']; else echo '0' ;?>"  >
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Amount PTP (DP) </span>
					<input type="text" class="form-control" name="txt-amount-ptp-dp" id="txt-amount-ptp-dp" placeholder='0' oninput="countAmountPTP(this)" onblur="validasi2(this)" value="<?=@number_format($pengajuan_data['amount_ptp']);?>"  />
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Payment Bunga </span>
					<input type="text" class="form-control" name="txt-payment-bunga" id="txt-payment-bunga" placeholder='0'  value="<?=@number_format($pengajuan_data['payment_bunga']);?>"  disabled>
					<input class="form-control" type="hidden" name="txt-payment-bunga-val" id="txt-payment-bunga-val" value="<?php if(isset($pengajuan_data['payment_bunga'])) echo $pengajuan_data['payment_bunga']; else echo '0' ;?>" >
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Payment Denda </span>
					<input type="text" class="form-control" name="txt-payment-denda" id="txt-payment-denda" placeholder='0' value="<?=@number_format($pengajuan_data['payment_denda']);?>"  disabled>
					<input class="form-control" type="hidden" name="txt-payment-denda-val" id="txt-payment-denda-val"  value="<?php if(isset($pengajuan_data['payment_denda'])) echo $pengajuan_data['payment_denda']; else echo '0' ;?>"  >
				</div>

				<!-- start 11-->
			</div>
		</div>
		<div class="col-sm-6">
			<h4 class="">-</h4>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Current Due Amt</span>
				<input type="text" class="form-control" name="span-curr-due-amnt" id="span-curr-due-amnt" value="<?= number_format(@$credit_data["CM_CURR_DUE"], 2) ?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Pass Due Amt</span>
				<input type="text" class="form-control" name="span-pass-due-amnt" id="span-pass-due-amnt" value="<?= number_format($credit_data["CM_PAST_DUE"], 2) ?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> 30 Days Due Amt</span>
				<input type="text" class="form-control" name="span-30-days-due-amnt" id="span-30-days-due-amnt" value="<?= number_format($credit_data["CM_30DAYS_DELQ"], 2) ?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> 60 Days Due Amt</span>
				<input type="text" class="form-control" name="span-60-days-due-amnt" id="span-60-days-due-amnt" value="<?= number_format($credit_data["CM_60DAYS_DELQ"], 2) ?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> 90 Days Due Amt</span>
				<input type="text" class="form-control" name="span-90-days-due-amnt" id="span-90-days-due-amnt" value="<?= number_format($credit_data["CM_90DAYS_DELQ"], 2) ?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> 120 Days Due Amt</span>
				<input type="text" class="form-control" name="span-120-days-due-amnt" id="span-120-days-due-amnt" value="<?= number_format($credit_data["CM_120DAYS_DELQ"], 2) ?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> 150 Days Due Amt</span>
				<input type="text" class="form-control" name="span-150-days-due-amnt" id="span-150-days-due-amnt" value="<?= number_format($credit_data["CM_150DAYS_DELQ"], 2) ?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> 180 Days Due Amt</span>
				<input type="text" class="form-control" name="span-180-days-due-amnt" id="span-180-days-due-amnt" value="<?= number_format($credit_data["CM_180DAYS_DELQ"], 2) ?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> 210 Days Due Amt</span>
				<input type="text" class="form-control" name="span-210-days-due-amnt" id="span-210-days-due-amnt" value="<?= number_format($credit_data["CM_210DAYS_DELQ"], 2) ?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Total Due Balance/Installment</span>
				<input type="text" class="form-control" name="span-210-days-due-amnt" id="span-210-days-due-amnt" value="<?= number_format($credit_data["CM_CURR_DUE"] + $credit_data["CM_PAST_DUE"] + $credit_data["CM_30DAYS_DELQ"] + $credit_data["CM_60DAYS_DELQ"] + $credit_data["CM_90DAYS_DELQ"] + $credit_data["CM_120DAYS_DELQ"] + $credit_data["CM_150DAYS_DELQ"] + $credit_data["CM_180DAYS_DELQ"] + $credit_data["CM_210DAYS_DELQ"], 2); ?>" placeholder='0' disabled>
			</div>
		</div>
	</div>
	<div class="row" style="margin-top: 10px;">
		<div class="col-sm-6">
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Outstanding Principal Discount</span>
				<span class="input-group-text">Rp</span>
				<input type="text" class="form-control" name="txt-principle-balance-discount"
					id="txt-principle-balance-discount" oninput="currencyformat(this)" onblur="validasi2(this)"
					placeholder='...' style="width: 150px;"
					value="<?=@number_format($pengajuan_data['amount_principle_balance_discount']);?>">
				<span class="input-group-text">%</span>
				<input type="text" class="form-control" name="txt-principle-balance-discount-2"
					id="txt-principle-balance-discount-2"
					oninput="validationparam(this,'principle-balance','txt-principle-balance-discount')"
					onblur="validasi2(this)" placeholder='...'
					value="<?=@$pengajuan_data['discount_amount_principle_balance'];?>">
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Late Charge Discount</span>
				<span class="input-group-text">Rp</span>
				<input type="text" class="form-control" name="txt-late-charge-discount" id="txt-late-charge-discount"
					oninput="currencyformat(this)" onblur="validasi2(this)" placeholder='...' style="width: 150px;"
					value="<?=@number_format($pengajuan_data['amount_late_charge_discount']);?>">
				<span class="input-group-text">%</span>
				<input type="text" class="form-control" name="txt-late-charge-discount-2" id="txt-late-charge-discount-2"
					oninput="validationparam(this,'late-charge','txt-late-charge-discount')" onblur="validasi2(this)"
					placeholder='...' value="<?=@$pengajuan_data['discount_amount_late_charge'];?>">
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Interest Discount</span>
				<span class="input-group-text">Rp</span>
				<input type="text" class="form-control" name="txt-interest-discount" id="txt-interest-discount"
					oninput="currencyformat(this)" onblur="validasi2(this)" placeholder='...' style="width: 150px;"
					value="<?=@number_format(@$pengajuan_data['amount_interest_discount']);?>">
				<span class="input-group-text">%</span>
				<input type="text" class="form-control" name="txt-interest-discount-2" id="txt-interest-discount-2"
					oninput="validationparam(this,'interest','txt-interest-discount')" onblur="validasi2(this)"
					placeholder='...' value="<?=@$pengajuan_data['discount_amount_interest'];?>">
			</div>
			<div class="input-group mb-1" id="div_moratorium" style="display:none">
				<span class="input-group-text widthLabel" id="basic-addon2">moratorium </span>
				<!-- <input type="hidden" name="txt-payment-pokok-val" id='txt-payment-pokok-val' > -->
				<input type="text" class="form-control" value="<?=@$pengajuan_data['moratorium'];?>" name="txt-moratorium"
					id="txt-moratorium" oninput="currencyformat(this)" placeholder=''>
				<input type="hidden" class="form-control" value="<?=@$pengajuan_data['moratorium'];?>"
					name="txt-moratorium-val" id="txt-moratorium-val" placeholder='0'>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Payment Pokok</span>
				<input type="text" class="form-control" name="txt-payment-pokok" id="txt-payment-pokok"
					value="<?=@number_format($pengajuan_data['payment_pokok']);?>" disabled>
				<input type="hidden" class="form-control" name="txt-payment-pokok-val" id="txt-payment-pokok-val"
					value="<?=@$pengajuan_data['payment_pokok'];?>" placeholder='0'>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Sisa Pokok Pinjaman Baru</span>
				<input type="text" class="form-control" name="txt-sisa-pokok-pinjaman-baru"
					id="txt-sisa-pokok-pinjaman-baru"
					value="<?=@number_format($pengajuan_data['sisa_pokok_pinjaman_baru']);?>" disabled>
				<input type="hidden" class="form-control" name="txt-sisa-pokok-pinjaman-baru-val"
					id="txt-sisa-pokok-pinjaman-baru-val" value="<?=@$pengajuan_data['sisa_pokok_pinjaman_baru'];?>"
					placeholder='0'>

			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Tenor</span>
				<input type="text" class="form-control" name="txt_tenor_val" id="txt_tenor_val"
					value="<?=@$pengajuan_data['tenor'];?>" oninput="numberOnly(this)">
				<i><small style="color:red;display:none" id="alert_input_tenor">* max tenor not found</small></i>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Interest</span>
				<div id="html_txt_interest_val"></div>
				<input type="text" class="form-control" name="txt_interest_val" id="txt_interest_val"
					value="<?=@$pengajuan_data['interest'];?>" oninput="numberOnly(this)">
				<input type="text" class="form-control" name="txt_interest_id" id="txt_interest_id" style="display:none">
				&nbsp;<i id='alert_interest_val'></i>
				<span class="input-group-text">%</span>
			</div>
			<div class="panel panel-default" id="body-math-counting" style="display: none;margin-bottom: 1px;">
				<div class="panel-body" id="panel-math-counting" style="font-size: 10px;margin: -10px;">

				</div>
			</div>

			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Suku Bunga</span>
				<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
					<input type="radio" class="btn-check" value="EFFECTIVE" name="btnradiotipesukubunga" id="btnradio1"
						autocomplete="off" <?php if(@$pengajuan_data['tipe_suku_bunga']=='EFFECTIVE')echo "checked";?>>
					<label class="btn btn-outline-primary" for="btnradio1">Effective</label>

					<input type="radio" class="btn-check" value="FLAT" name="btnradiotipesukubunga" id="btnradio2"
						autocomplete="off" <?php if(@$pengajuan_data['tipe_suku_bunga']=='FLAT')echo "checked";?>>
					<label class="btn btn-outline-primary" for="btnradio2">Flat</label>

					<input type="radio" class="btn-check" value="SLIDING" name="btnradiotipesukubunga" id="btnradio3"
						autocomplete="off" <?php if(@$pengajuan_data['tipe_suku_bunga']=='SLIDING')echo "checked";?>>
					<label class="btn btn-outline-primary" for="btnradio3">Slinding</label>
				</div>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2">Jadwal Pembayaran</span>
				<!-- <input type="text" class="form-control" readonly> -->
				<div class="input-group-append">
					<button class="btn btn-outline-primary" type="button" onClick="jadwal_pebayaran()">View</button>
				</div>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> New Installment Amount</span>
				<input type="text" class="form-control" name="txt_new_installment_amount" id="txt_new_installment_amount"
					value='<?=@number_format($pengajuan_data['new_installment']);?>' disabled>
				<input type="hidden" class="form-control" name="txt_new_installment_amount_val"
					id="txt_new_installment_amount_val" value='<?=@$pengajuan_data['new_installment'];?>' />
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12" style=" margin-top: 30px;">
			<center>Remark<br><textarea cols="100" rows="5" name='txt-remark'><?=@$pengajuan_data['notes'];?></textarea>
			</center>
		</div>
	</div>

	</div>
</form>
<div class="row">
	<div class="col-sm-12">
		<hr>
		<div class="row">
			<div id="call-verif-process" class="col-sm-6"
				<?php if(@$pengajuan_data['status']=='NEW' || $screen_level=='NEW'){ echo 'style="display:none"';} ?>>
				<!-- start col-sm-6 -->

				<!-- <h4 class="">Verifikasi By Call  <?php if(in_array(@$pengajuan_data['call_verification_status'],array('FINISH','DRAFT'))){echo "[".@$pengajuan_data['call_verification_status']."]";}?> </h4> -->
				<h4 class="">Verifikasi By Call </h4>
				<i style="font-size: 12px;display:none;color:red" id="alert_verif_call">*anda telah menyelesaikan verification By
					Call</i>
				<div class="profile-user-info profile-user-info-striped" style="padding: 15px;">
					<div class="profile-info-row" style="margin-bottom: 20px;">

						<input type="radio" name="phone-owner" id="hp_phone" phone_type="handphone"
							value="<?=$customer_data['handphone']?>" />
						<label for="hp_phone">HP : <?=$customer_data['handphone']?></label><br>

						<input type="radio" name="phone-owner" id="home_phone" phone_type="home_phone"
							value="<?=$customer_data['home_phone']?>" />
						<label for="home_phone">HOME : <?=$customer_data['home_phone']?></label><br>

						<input type="radio" name="phone-owner" id="home_phone_2" phone_type="home_phone_2"
							value="<?=$customer_data['home_phone_2']?>" />
						<label for="home_phone_2">HOME 2 : <?=$customer_data['home_phone_2']?></label><br>

						<input type="radio" name="phone-owner" id="mother_handphone"
							value="<?=$customer_data['mother_handphone']?>" />
						<label for="mother_handphone">Mother Handphone : <?=$customer_data['mother_handphone']?></label><br>

						<input type="radio" name="phone-owner" id="office_phone" value="<?=$customer_data['office_phone']?>" />
						<label for="office_phone">Office Phone : <?=$customer_data['office_phone']?></label><br>

						<!--  -->
						<input type="radio" name="phone-owner" id="spouse_phone" value="<?=$customer_data['spouse_phone']?>" />
						<label for="spouse_phone">Spouse Phone : <?=$customer_data['spouse_phone']?></label><br>

						<!--  -->
						<input type="radio" name="phone-owner" id="other_phone" value="other_phone" />
						<label for="other_phone">other</label><br>
						<!--  -->

						<input type="text" name="txt_other_phone" id="txt_other_phone" value="<?=@$other_phone?>"
							oninput="numberOnly_all(this);" onkeyup="numberOnly_all(this)" disabled>

						<button type="button" id='btn-to-call' class="btn btn-success btn-sm pull-right btn-form-call"><span
								class="glyphicon glyphicon-earphone"></span> Call</button>
						<i id="id_status_telephony"></i>
					</div>

					<div class="input-group mb-1">
						<span class="input-group-text widthLabel" id="basic-addon2"> Contact Result </span>
						<?php
								$attributes = 'class="form-control" id="contact_result"';
								echo form_dropdown('contact_result', @$contact_result, '' , $attributes);
						?>
					</div>
					<div class="input-group mb-1">
						<span class="input-group-text widthLabel" id="basic-addon2"> Result Call </span>
						<?
							$selected = '';
						
							$attributes = 'class="form-control" id="result_call"';
							echo form_dropdown('result_call', @$result_call,'', $attributes);
						?>
					</div>
					<div class="input-group mb-1">
						<span class="input-group-text widthLabel" id="basic-addon2"> Remark </span>
						<textarea class="form-control" cols="45" rows="3" name='call-remark'
							id='call-remark'><?=@$call_history_data['notes'];?></textarea>
					</div>

					<div class="profile-info-row" style="margin-top: 10px;height: 35px;">
						<button class='btn btn-warning btn-sm pull-right btn-form-call' onClick='save_call_history("FINISH")'
							<?php if(@$pengajuan_data['call_verification_status']=='FINISH'){echo "disabled";}?>>save</button>
						<!-- <button class='btn btn-warning btn-sm  pull-right btn-form-call' style='margin-right:5px' onClick='save_call_history("DRAFT")' <?php if(@$pengajuan_data['call_verification_status']=='FINISH'){echo "disabled";}?>>save</button> -->
						<!-- <button class='btn btn-danger btn-sm btn-form-call'>cancel</button> -->
						<div class="spinner-border spinner-border-sm" id="loading_call" role="status" style="margin-left: 55%;display:none">
							<span class="visually-hidden" id = "loading_call">Loading...</span>
						</div>
					</div>

				</div>
			</div><!-- end col-sm-6 -->


			<div class="col-sm-6">
				<!-- start col-sm-6 -->
				<form role="form" class="needs-validation" id="form_upload_doc" name="form_upload_doc" novalidate>
					<!-- <h4 class="">Upload Document <?php if(in_array(@$pengajuan_data['document_upload_status'],array('FINISH','DRAFT'))){echo "[".@$pengajuan_data['document_upload_status']."]";}?> </h4> -->
					<h4 class="">Upload Document </h4>

					<?php
						foreach ($master_document as $key => $value) {
						
						$jumlah = $this->Common_model->get_record_value('count(*)','cms_upload_document','id_pengajuan="'.@$pengajuan_data['id'].'" and id_upload="'.$value['id'].'" ');
						$jumlah_other = $this->Common_model->get_record_value('count(*)','cms_upload_document','id_pengajuan="'.@$pengajuan_data['id'].'" and id_upload="other" ');
					?>
					<div class="input-group mb-1">
							
							<span class="input-group-text widthLabel" id="basic-addon2"><?php if($value['is_mandatory']=='Y') echo "*";?> <?=$value['nama_document']?> (<span id="jumlah-uploaded-<?=$value['id']?>"><?=$jumlah;?></span>) </span>
							<input type='file' class='form-control doUpload' accept="" data-label='<?=$value['id']?>' name='<?=$value['id']?>' id='<?=$value['id']?>' style="max-width: 230px;" <?php if($value['is_mandatory']=='Y' && $jumlah == 0) echo "required";?> >
						</div>
						<div class="progress" role="progressbar" aria-label="Success example" style="display:none" aria-valuenow="25" id='div-progress-bar-<?=$value['id']?>' aria-valuemin="0" aria-valuemax="100">
							<div class="progress-bar bg-success" id="progress-bar-<?=$value['id']?>" style="width: 0%"><i><?=$value['nama_document']?> uploading...</i></div>
						</div>
					<?php
						}
						$jumlah = $this->Common_model->get_record_value('count(*)','cms_upload_document','id_pengajuan="'.@$pengajuan_data['id'].'" and id_upload="other" ');
					?>
						<div class="input-group mb-1">
							<span class="input-group-text widthLabel" id="basic-addon2">Other file (<span id="jumlah-uploaded-other"><?=$jumlah;?></span>) </span>
							<input type='file' class='form-control doUpload' accept="" data-label='other' name='other' id='other' style="max-width: 230px;" />
						</div>
						<div class="progress" role="progressbar" aria-label="Success example" style="display:none" aria-valuenow="25" id='div-progress-bar-other' aria-valuemin="0" aria-valuemax="100">
							<div class="progress-bar bg-success" id="progress-bar-other" style="width: 0%"><i>other uploading...</i></div>
						</div>
						<div class="input-group mb-1">
							<span class="input-group-text widthLabel" id="basic-addon2">Other info</span>
							<input type='text' class='form-control' accept="" data-label='file-other-doc-info' name='file-other-doc-info' id='file-other-doc-info' style="max-width: 230px;" />
						</div>
						<div class="input-group mb-1">
							<span class="input-group-text widthLabel" id="basic-addon2">Remark</span>
							<textarea class="form-control" cols="45" rows="3" name='upload-data-remark' id='upload-data-remark' ></textarea>
						</div>

						</form>

				<div class="row">
					<div class="profile-info-row" style="margin-top: 10px;height: 35px;">
						<!-- <button class='btn btn-warning btn-sm pull-right btn-form-upload' id='btn-form-upload' onClick='upload_doc("DRAFT")' >upload</button> -->
						<button class='btn btn-warning btn-sm pull-right btn-form-upload' id='btn-form-upload'
							onClick='upload_doc("FINISH")'>upload</button>
						<!-- <button class='btn btn-danger btn-sm'>cancel</button>  -->
					</div>
				</div>






			</div><!-- end col-sm-6 -->

		</div>


	</div>
</div>

</div>


<div class="row">
	<div class="col-sm-12">
		<hr>
		&nbsp;&nbsp;&nbsp;&nbsp;

		<button id='btn-save-finish' class='btn btn-sm btn-success pull-right' style="margin-left: 5px;"
			onClick="save('1')">SAVE AND FINISH</button>&nbsp;
		<!-- <button  id='btn-save-draft'  class='btn btn-sm btn-primary pull-right'  onClick="save('0')">SAVE AS DRAFT</button>&nbsp; -->
		<img src="<?=base_url()?>/assets/img/loading.gif" class="pull-right" id="loading_save"
			style="margin-top: 10px;display:none;margin-right: 10px;">
	</div>
</div>


</div>

<script src="<?=base_url();?>modules/workflow_pengajuan/js/new_installment_amount_perhitungan.js"></script>
<script type="text/javascript">
	var STATUS_DOC = '<?=@$pengajuan_data['document_upload_status ']?>';
	var tipe_suku_bunga = '';
	var URL_DATA = "<?=site_url()?>";
	var product_id = $('#txt_product_id').val();
	var product_type = "<?=$credit_data["CM_TYPE"]?>";
	var due_date = "<?=$credit_data["CM_DTE_PYMT_DUE"]?>";
	var bucket = "<?=$credit_data["CM_BUCKET"]?>";
	var disabled_color = '#D3D3D3';
	var max_normal_disc_rate = 0;
	var max_block_status_disc_rate = 0;
	var max_principal_disc_rate = 0;
	var max_interest_disc_rate = 0;
	var max_interest_disc_rate_2 = 100;
	var max_late_charge_disc_rate = 100;
	var max_penalty_disc_rate = 100;

	var limit_max_normal_disc_rate = 0;
	var limit_max_block_status_disc_rate = 0;
	var limit_max_principal_disc_rate = 0;
	var limit_max_interest_disc_rate = 0;
	var limit_max_interest_disc_rate_2 = 100;
	var limit_max_late_charge_disc_rate = 100;
	var limit_max_penalty_disc_rate = 100;

	var special_disc_limit_valid = 0;
	var hirarki;

	var ptp_grace_period = '<?= $grace_period ?>';

	var OngoingInstallmentList = <?= $OngoingInstallmentList['total'] ?>;

	var date = '<?=date('Y-m-d');?>';

	
</script>
<script src="<?= base_url(); ?>modules/workflow_pengajuan/js/request_pengajuan_reschedule.js?v=<?= rand() ?>"></script>