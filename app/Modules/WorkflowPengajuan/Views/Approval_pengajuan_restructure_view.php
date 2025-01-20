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
$DB = \Config\Database::connect();
$this->db = db_connect();
?>
	<!-- #PINDAH DI VIEW detail_contract_request_program_view.php -->
	<!-- <form class="form-horizontal" id="formDiscountRequest" name="formDiscountRequest" method="POST"> -->
	<input type="hidden" name="<?= csrf_token() ?>" id="token_csrf" value="<?= csrf_hash() ?>" />
	<input type="hidden" id="txt_screen_level" name="txt_screen_level" value="<?=$screen_level?>">
	<input type="hidden" id="approval_notes" name="approval_notes" value="">
	<input type="hidden" id="approval_status" name="approval_status" value="">
	<input type="hidden" id="txt_card_number" name="txt_card_number" value="<?=$credit_data["CM_CARD_NMBR"]?>">
	<input type="hidden" id="txt_mob" name="txt_mob" value="<?=$credit_data["MOB"]?>">
	<input type="hidden" id="txt_bucket" name="txt_bucket" value="<?=$credit_data["CM_BUCKET"]?>">
	<input type="hidden" id="txt_product_id" name="txt_product_id" value="<?=$credit_data["CM_TYPE"]?>">
	<input type="hidden" id="txt_product_code" name="txt_product_code" value="<?=$credit_data["CM_PRODUCT_TYPE"]?>">
	<input type="hidden" id="txt_block_code" name="txt_block_code" value="<?=$credit_data["CM_BLOCK_CODE"]?>">
	<input type="hidden" id="APPROVAL_LEVEL" name="APPROVAL_LEVEL" value="<?=@$APPROVAL_LEVEL?>">
	<input type="hidden" id="restructure_rate" name="restructure_rate">
	<input type="hidden" id="txt_hirarki" name="txt_hirarki">
	<input type="hidden" id="id" name="id">
	<input type="hidden" id="source" name="source" value="<?=$source?>">
	<input type="hidden" id="agent_id" name="agent_id" value="<?=$agent_id?>">
	<input type="hidden" name="dpd" id="dpd" value="<?=$credit_data["DPD"]?>">
	<input type="hidden" id="submit_id" name="submit_id" value="<?=date('YmdHis')?>">
	<input type="hidden" id="approval_id" name="approval_id" value="<?=@$APPROVAL_ID?>">

	<input type="hidden" id="txt_id_pengajuan" name="txt_id_pengajuan" value="<?=$data_pengajuan['id']?>">
	<input type="hidden" id="jenis_pengajuan" name="jenis_pengajuan" value="<?=@$discount_data["jenis_pengajuan"] ?>">


	<br>
	<div class="row">
		<div class="col-sm-12">
			<i><small id='alert_parameter' style="font-size: 12px;">-</small></i><br>
			<span id="lbl_nama_parameter"></span>
			<a href="#" class="pull-right">
				<span class="glyphicon glyphicon-search" onClick="search_param()"></span>
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
					<input class="form-control" type="text" name="is_deviation" id="is_deviation"
						value="<?php if($data_pengajuan['deviation_flag']=='1') {echo "yes"; }else{ echo "no";} ?>"
						readonly>
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="txt_deviation_reason"> Deviation Reason</span>
					<?php
								$attributes = 'class="" id="txt_deviation_reason" style="display:block;max-width:200px" disabled';
								echo form_dropdown('txt_deviation_reason', $deviation_reason, $data_pengajuan['deviation_reason'], $attributes);
							?>
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> PTP Date</span>
					<input class="form-control" type="text" name="txt_ptp_date_for_rst" id="txt_ptp_date_for_rst"
						value="<?=$data_pengajuan['ptp_date']?>" readonly>
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
			<img src="<?=base_url()?>/assets/img/loading.gif" class="pull-right" id="loading_kotak_merah"
				style="margin-top: 10px;display:none;margin-right: 10px;">
			<div class="" id='kotak_merah'>
				<!-- start 11-->
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Outstanding Principal</span>
					<input class="form-control" type="text" name="txt-principle-balance" id="txt-principle-balance"
						value='<?=number_format($data_pengajuan['principle_balance'],2)?>' disabled>
					<input class="form-control" type="hidden" name="txt-principle-balance-val"
						id="txt-principle-balance-val" value='<?=$data_pengajuan['principle_balance']?>'>
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Outstanding Balance</span>
					<input class="form-control" type="text" name="txt-total-due-balance-installment"
						id="txt-total-due-balance-installment"
						value='<?=number_format($data_pengajuan['total_due_balance_installment'],2)?>' disabled>
					<input class="form-control" type="hidden" name="txt-total-due-balance-installment-val"
						id="txt-total-due-balance-installment-val" value='0'>
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Due Interest</span>
					<input class="form-control" type="text" name="txt-due-interest" id="txt-due-interest"
						value='<?=number_format($data_pengajuan['due_interest'],2)?>' disabled>
					<input class="form-control" type="hidden" name="txt-due-interest-val" id="txt-due-interest-val"
						value='0'>
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Late Charge</span>
					<input class="form-control" type="text" name="txt-late-charge" id="txt-late-charge"
						value='<?=number_format($data_pengajuan['late_charge'],2)?>' disabled>
					<input class="form-control" type="hidden" name="txt-late-charge-val" id="txt-late-charge-val"
						value='0'>
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Penalty</span>
					<input class="form-control" type="text" name="txt-penalty" id="txt-penalty"
						value='<?=number_format($data_pengajuan['penalty'],2)?>' disabled>
					<input class="form-control" type="hidden" name="txt-penalty-val" id="txt-penalty-val" value='0'>
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Stamp Duty</span>
					<input class="form-control" type="text" name="txt-stamp-duty" id="txt-stamp-duty"
						value='<?=number_format($data_pengajuan['stamp_duty'],2)?>' disabled>
					<input class="form-control" type="hidden" name="txt-stamp-duty-val" id="txt-stamp-duty-val"
						value='0'>
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> <b>Total</b></span>
					<input class="form-control" type="text" name="txt-total" id="txt-total"
						value='<?=number_format($data_pengajuan['total'],2)?>' disabled>
					<input class="form-control" type="hidden" name="txt-total-val" id="txt-total-val" value='0'>
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Amount PTP (DP) </span>
					<input type="text" class="form-control" name="txt-amount-ptp-dp" id="txt-amount-ptp-dp"
						placeholder='0' value='<?=number_format($data_pengajuan['amount_ptp'],2)?>' readonly>
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Payment Bunga </span>
					<input type="text" class="form-control" name="txt-payment-bunga" id="txt-payment-bunga"
						placeholder='0' value='<?=number_format($data_pengajuan['payment_bunga'],2)?>' disabled>
					<input class="form-control" type="hidden" name="txt-payment-bunga-val" id="txt-payment-bunga-val"
						value='0'>
				</div>
				<div class="input-group mb-1">
					<span class="input-group-text widthLabel" id="basic-addon2"> Payment Denda </span>
					<input type="text" class="form-control" name="txt-payment-denda" id="txt-payment-denda"
						placeholder='0' value='<?=number_format($data_pengajuan['payment_denda'],2)?>' disabled>
					<input class="form-control" type="hidden" name="txt-payment-denda-val" id="txt-payment-denda-val"
						value='0'>
				</div>

				<!-- start 11-->
			</div>
		</div>
		<div class="col-sm-6">
			<h4 class="">-</h4>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Current Due Amt</span>
				<input type="text" class="form-control" name="span-curr-due-amnt" id="span-curr-due-amnt"
					value="<?=number_format($credit_data["CM_CURR_DUE"], 2)?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Pass Due Amt</span>
				<input type="text" class="form-control" name="span-pass-due-amnt" id="span-pass-due-amnt"
					value="<?=number_format($credit_data["CM_PAST_DUE"], 2)?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> 30 Days Due Amt</span>
				<input type="text" class="form-control" name="span-30-days-due-amnt" id="span-30-days-due-amnt"
					value="<?=number_format($credit_data["CM_30DAYS_DELQ"], 2)?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> 60 Days Due Amt</span>
				<input type="text" class="form-control" name="span-60-days-due-amnt" id="span-60-days-due-amnt"
					value="<?=number_format($credit_data["CM_60DAYS_DELQ"], 2)?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> 90 Days Due Amt</span>
				<input type="text" class="form-control" name="span-90-days-due-amnt" id="span-90-days-due-amnt"
					value="<?=number_format($credit_data["CM_90DAYS_DELQ"], 2)?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> 120 Days Due Amt</span>
				<input type="text" class="form-control" name="span-120-days-due-amnt" id="span-120-days-due-amnt"
					value="<?=number_format($credit_data["CM_120DAYS_DELQ"], 2)?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> 150 Days Due Amt</span>
				<input type="text" class="form-control" name="span-150-days-due-amnt" id="span-150-days-due-amnt"
					value="<?=number_format($credit_data["CM_150DAYS_DELQ"], 2)?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> 180 Days Due Amt</span>
				<input type="text" class="form-control" name="span-180-days-due-amnt" id="span-180-days-due-amnt"
					value="<?=number_format($credit_data["CM_180DAYS_DELQ"], 2)?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> 210 Days Due Amt</span>
				<input type="text" class="form-control" name="span-210-days-due-amnt" id="span-210-days-due-amnt"
					value="<?=number_format($credit_data["CM_210DAYS_DELQ"], 2)?>" placeholder='0' disabled>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Total Due Balance/Installment</span>
				<input type="text" class="form-control" name="span-210-days-due-amnt" id="span-210-days-due-amnt"
					value="<?=number_format($credit_data["CM_CURR_DUE"]+$credit_data["CM_PAST_DUE"]+$credit_data["CM_30DAYS_DELQ"]+$credit_data["CM_60DAYS_DELQ"]+$credit_data["CM_90DAYS_DELQ"]+$credit_data["CM_120DAYS_DELQ"]+$credit_data["CM_150DAYS_DELQ"]+$credit_data["CM_180DAYS_DELQ"]+$credit_data["CM_210DAYS_DELQ"],  2);?>"
					placeholder='0' disabled>
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
					value='<?=number_format($data_pengajuan['amount_principle_balance_discount'],2)?>' readonly />
				<span class="input-group-text">%</span>
				<input type="text" class="form-control" name="txt-principle-balance-discount-2"
					id="txt-principle-balance-discount-2"
					oninput="validationparam(this,'principle-balance','txt-principle-balance-discount')"
					onblur="validasi2(this)" placeholder='...'
					value='<?=number_format($data_pengajuan['discount_amount_principle_balance'],2)?>' readonly />
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Late Charge Discount</span>
				<span class="input-group-text">Rp</span>
				<input type="text" class="form-control" name="txt-late-charge-discount" id="txt-late-charge-discount"
					oninput="currencyformat(this)" onblur="validasi2(this)" placeholder='...' style="width: 150px;"
					value='<?=number_format($data_pengajuan['amount_late_charge_discount'],2)?>' readonly />
				<span class="input-group-text">%</span>
				<input type="text" class="form-control" name="txt-late-charge-discount-2"
					id="txt-late-charge-discount-2"
					oninput="validationparam(this,'late-charge','txt-late-charge-discount')" onblur="validasi2(this)"
					value='<?=number_format($data_pengajuan['discount_amount_late_charge'],2)?>' placeholder='...'
					readonly />
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Interest Discount</span>
				<span class="input-group-text">Rp</span>
				<input type="text" class="form-control" name="txt-interest-discount" id="txt-interest-discount"
					oninput="currencyformat(this)" onblur="validasi2(this)" placeholder='...' style="width: 150px;"
					value='<?=number_format($data_pengajuan['amount_interest_discount'],2)?>' readonly />
				<span class="input-group-text">%</span>
				<input type="text" class="form-control" name="txt-interest-discount-2" id="txt-interest-discount-2"
					oninput="validationparam(this,'interest','txt-interest-discount')" onblur="validasi2(this)"
					value='<?=number_format($data_pengajuan['discount_amount_interest'],2)?>' placeholder='...'
					readonly />
			</div>
			<div class="input-group mb-1" id="div_moratorium" style="display:none">
				<span class="input-group-text widthLabel" id="basic-addon2">moratorium </span>
				<input type="hidden" name="txt-payment-pokok-val" id='txt-payment-pokok-val'>
				<input type="text" class="form-control" name="txt-moratorium" id="txt-moratorium"
					oninput="currencyformat(this)" value='<?=number_format($data_pengajuan['moratorium'],2)?>'
					placeholder='' readonly>
				<input type="hidden" class="form-control" name="txt-moratorium-val" id="txt-moratorium-val"
					placeholder='0'>
			</div>

			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Payment Pokok</span>
				<input type="text" class="form-control" name="txt-payment-pokok-val" id="txt-payment-pokok-val"
					value='<?=number_format($data_pengajuan['payment_pokok'],2)?>' disabled>
				<input type="hidden" class="form-control" name="txt-payment-pokok" id="txt-payment-pokok"
					placeholder='0'>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Sisa Pokok Pinjaman Baru</span>
				<input type="text" class="form-control" name="txt-sisa-pokok-pinjaman-baru"
					id="txt-sisa-pokok-pinjaman-baru"
					value='<?=number_format($data_pengajuan['sisa_pokok_pinjaman_baru'],2)?>' disabled>
				<input type="hidden" class="form-control" name="txt-sisa-pokok-pinjaman-baru-val"
					id="txt-sisa-pokok-pinjaman-baru-val" placeholder='0'>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Tenor</span>
				<input type="text" class="form-control" name="txt_tenor_val" id="txt_tenor_val"
					oninput="numberOnly(this)" value='<?=$data_pengajuan['tenor']?>' readonly>
				<i><small style="color:red;display:none" id="alert_input_tenor">* max tenor not found</small></i>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Interest</span>
				<div id="html_txt_interest_val"></div>
				<input type="text" class="form-control" name="txt_interest_val" id="txt_interest_val"
					oninput="numberOnly(this)" value='<?=number_format($data_pengajuan['interest'],2)?>' readonly>
				<input type="text" class="form-control" name="txt_interest_id" id="txt_interest_id"
					style="display:none">
				&nbsp;<i id='alert_interest_val'></i>
				<span class="input-group-text">%</span>
			</div>

			<div class="panel panel-default" id="body-math-counting" style="display: none;margin-bottom: 1px;">
				<div class="panel-body" id="panel-math-counting" style="font-size: 10px;margin: -10px;">

				</div>
			</div>
			<i id="alert_ratio" style="display:none">*ratio cicilan : <span id="current_ratio"></span> status : <span
					id="status_ratio"></span></i>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Suku Bunga</span>
				<input type="text" class="form-control" name="txt_tipe_suku_bunga" id="txt_tipe_suku_bunga"
					oninput="numberOnly(this)" value='<?=$data_pengajuan['tipe_suku_bunga']?>' readonly>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Jadwal Pembayaran</span>
				<a href="#" onClick="show_jadwal_pembayaran()">view</a>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> New Installment Amount</span>
				<input type="text" class="form-control" name="txt_new_installment_amount"
					id="txt_new_installment_amount" value='<?=number_format($data_pengajuan['new_installment'],2)?>'
					disabled>
				<input type="hidden" class="form-control" name="txt_new_installment_amount_val"
					id="txt_new_installment_amount_val" />
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12" style=" margin-top: 30px;">
			<center>Remark<br><textarea cols="100" rows="5" name='txt-remark'
					readonly><?=$data_pengajuan['notes']?></textarea></center>
		</div>
	</div>

</div>
</form>


</div>


<!-- ---------------------------------------------------------------history call------------------------------------------------------------ -->
<div class="row">
	<div class="col-sm-12">
		<hr>
		<h4 class="">Verifikasi Call</h4>
		<div class="col-sm-12">
			<table class="table table-striped table-hover">
				<thead>
					<th class="table-success">#</th>
					<th class="table-success">Call Date</th>
					<th class="table-success">Call Time</th>
					<th class="table-success">Call by</th>
					<th class="table-success">Contact Result</th>
					<th class="table-success">Result Call</th>
					<th class="table-success">Remark</th>
					<th class="table-success">recording</th>
				</thead>
				<tbody>
					<?php
								$no = 1;
								foreach ($history_call as $key => $value) {
									echo "<tr>";
										echo "<td>".$no."</td>";
										echo "<td>".$value['date']."</td>";
										echo "<td>".$value['time']."</td>";
										echo "<td>".$value['name']."</td>";
										echo "<td>".$value['call_status']."</td>";
										echo "<td>".$value['call_result']."</td>";
										echo "<td>".$value['notes']."</td>";
										echo "<td> - </td>";
									echo "</tr>";
									$no++;
								}
							?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- ---------------------------------------------------------------history uplaod------------------------------------------------------------ -->
<div class="row">
	<div class="col-sm-12">
		<hr>
		<h4 class="">Verifikasi Document</h4>
		<div class="col-sm-12">
			<table class="table table-striped table-hover">
				<thead>
					<th class="table-primary">#</th>
					<th class="table-primary">Document</th>
					<th class="table-primary">Last upload</th>
					<th class="table-primary">Jumlah Data</th>
					<th class="table-primary">Action</th>
				</thead>
				<tbody>
					<?php
								$no = 1;
								foreach ($history_upload as $key => $value) {	
							?>
					<tr>
						<td><?=$no?></td>
						<td><?=$value['id_upload']?></td>
						<td><?=$this->Common_model->get_record_value('created_time','cms_upload_document','id_pengajuan="'.$value['id_pengajuan'].'" and id_upload="'.$value['id_upload'].'" order by created_time desc')?>
						</td>
						<td><?=$value['jumlah_data']?></td>
						<td><a href='#' onClick="get_detail_view('<?=$value['id_upload']?>')"><b>view</b></a></td>
					</tr>
					<?php
									$no++;
								}
							?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- ---------------------------------------------------------------approval------------------------------------------------------------ -->
<div class="row">
	<div class="col-sm-6">

		<div class="list-group">
			<?php
				$is_active = 'active';
				
					foreach ($verif_data as $key => $value) {
						if( $value['level_num']==@$APPROVAL_LEVEL && !in_array($data_pengajuan['status'],array('APPROVE','REJECT'))){
							echo '<button type="button" class="list-group-item list-group-item-action active" aria-current="true">';
							echo $value['level'];
							echo '</button>';
						}else{
							echo '<button type="button" class="list-group-item list-group-item-action list-group-item-secondary" aria-current="true">';
							echo $value['level'];
							echo '</button>';
						}
					

						foreach ($value['list_approval'] as $keyx => $valuex) {
							$builder = $this->db->table('cms_approval');
							$builder->select('*');
							$builder->where('id_pengajuan', $data_pengajuan['id']);
							$builder->where('created_by', $valuex['user_id']);
							$builder->where('approval_level', $value['level_num']);
							$builder->where('action', 'APPROVAL');
							$res = $builder->get()->getResultArray();
							// $sql = "SELECT * FROM cms_approval WHERE id_pengajuan = ? and created_by = ? and approval_level = ? and action='APPROVAL' ";
							// $res = $this->db->query($sql,array($data_pengajuan['id'],$valuex['user_id'],$value['level_num']))->result_array();
							$command='';
							foreach ($res as $keyz => $valuez) {
								$command = '<i class="float-end">['.$valuez['status'].'] - '.$valuez['notes'].' - '.$valuez['created_time'].'</i>';
							}
							
							if($valuex['user_id']==session()->get('user_id') && $value['level_num']==$APPROVAL_LEVEL ){
								echo '<a href="#" class="list-group-item list-group-item-action ">'. $valuex['name'].'<i class="bi bi-check-square-fill float-end" style="color:blue"></i> </a>';
	
							}else{

								
								
								echo '<a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">'.$valuex['name'].' '.$command.'</a>';
							}
							
						}
					}
				?>
			<!-- <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
					The current link item
				</a>
				<a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">A disabled link item</a> -->
		</div>
	</div>

	<!-- $view adalah param yg di lempar dari menu report pengajuan restructure , jika di buka dari menu pengajuan restructure $view bernilai 1 -->
	<div class="col-sm-6" <?php if($view=='1')echo "style='display:none'"; ?>>
		<form role="form" class="needs-validation" id="form_approval" name="form_approval" novalidate>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> ACTION </span>
				<?
							$action_list = array(''=>'[SELECT DATA]','APPROVE'=>'APPROVE','REJECT'=>'REJECT');
							$attributes = 'class="form-control" id="action-approval" required';
							echo form_dropdown('action-approval', $action_list, "", $attributes);
						?>
			</div>
			<div class="input-group mb-1">
				<span class="input-group-text widthLabel" id="basic-addon2"> Remark </span>
				<textarea class="form-control" cols="45" rows="3" name='remark-approval' id='remark-approval'
					required></textarea>
			</div>
		</form>
		<div class="input-group mb-1">

			<button class='btn btn-success btn-sm pull-right' id="saveApproval" onClick="saveApproval_rstr()"
				style="margin: 10px;"
				<?php if(in_array($data_pengajuan['status'],array('APPROVE','REJECT')))echo "disabled"; ?>>SAVE</button>

		</div>
	</div>
</div>


<script src="<?=base_url();?>modules/workflow_pengajuan/js/new_installment_amount_perhitungan.js"></script>
<script type="text/javascript">
	var tipe_suku_bunga = '';
	var URL_DATA = "<?=site_url()?>";
	var product_id = $('#txt_product_id').val();
	var product_type = "<?=$credit_data["CM_PRODUCT_TYPE"]?>";
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

	var flag_status_ratio = false;
	var last_action = '';


	$('#flag_kondisi_khusus').on('change', function () {
		$('th').css('color', 'black');
		$('#txt_catatan_kondisi_khusus').html('');
		if ($(this).val() == '1') {
			$('#alert_pilih_salah_satu').show();
			$('#desc_kondisi_khusus,#txt_catatan_kondisi_khusus').attr('disabled', false);

			$('.tbl_block_code').hide();
			$('.tbl_kondisi_khusus').show();



			$('.tbl_block_code').css('color', disabled_color);
			$('.tbl_kondisi_khusus').attr('style', 'color:black');
			$('.tbl_spcl_kondisi').attr('style', 'color:black');


			$('.tbl_cc').css('color', disabled_color);
			$('.tbl_pl').css('color', disabled_color);
			// $('#desc_kondisi_khusus').change();

		} else {


			$("#txt_catatan_kondisi_khusus").val('');
			$('#desc_kondisi_khusus,#txt_catatan_kondisi_khusus').attr('disabled', true);
			$('#desc_kondisi_khusus').val('');
			// $('#desc_kondisi_khusus').val('').change();

			$('.tbl_block_code').show();
			$('.tbl_kondisi_khusus').hide();



			$('.tbl_block_code').attr('style', 'color:black');
			$('.tbl_kondisi_khusus').css('color', disabled_color);
			$('.tbl_spcl_kondisi').css('color', disabled_color);

			$('.tbl_cc').css('color', 'black');
			$('.tbl_pl').css('color', 'black');

			$("#alert_pilih_salah_satu").hide();


		}
		search_param();
	});

	$('#desc_kondisi_khusus').on('change', function () {
		// console.log('flag_kondisi_khusus',$(this).val());

		if ($(this).val() != '') {
			$('#alert_pilih_salah_satu').hide();
		} else {
			$('#alert_pilih_salah_satu').show();
		}
		$("#param_bucket").html(bucket);
		search_param();
	});

	function search_param() {
		$('#restructure_rate').val('');
		$("#param_max_normal_disc_rate").html('');
		$("#param_max_interest_disc_rate").html('');
		$("#param_max_tenor").html('');
		$("#param_cicilan").html('');
		$('#param_min_outstanding').html('');
		$("#lbl_nama_parameter").html('');
		$('#param_block_status').html('');
		$("#alert_parameter").html('<i>Loading...</i>');


		max_normal_disc_rate = null;
		max_interest_disc_rate = null;
		limit_max_normal_disc_rate = null;
		limit_max_interest_disc_rate = null;

		$.ajax({
			type: "GET",
			url: URL_DATA + "workflow_pengajuan/workflow_pengajuan_restructure/cek_config_rest",
			data: {
				id_pengajuan: $("#txt_id_pengajuan").val(),
				request_type: 'RSTR',
				flag_kondisi_khusus: $('#flag_kondisi_khusus').val(),
				desc_kondisi_khusus: $("#desc_kondisi_khusus").val(),
				agreement_no: $('#txt_card_number').val(),
				mob: $('#txt_mob').val(),
				bucket: $('#txt_bucket').val(),
				product_id: $('#txt_product_id').val(),
				block_code: $('#txt_block_code').val()
			},
			dataType: "json",
			//timeout: 3000,
			success: function (msg) {
				// $("#txt-late-charge-discount-2").val('0');
				// $('#txt-interest-discount-2').val('0');
				// $('#txt-late-charge-discount').val('0');
				// $('#txt-interest-discount').val('0');



				if (msg.success && msg.data != 'NOT_FOUND') {
					let json_param = JSON.parse(msg.data.parameter_json);
					// console.log('json_param',json_param);
					$.each(json_param.rules, function (i, val) {
						// console.log('val',val);

						if (val.id == 'outstanding_balance') {
							$('#param_min_outstanding').html(val.value);
						}
						if (val.id == 'MOB') {
							// $('#param_mob').html(val.value);
						}
						if (val.id == 'bucket') {
							// $('#param_bucket').html(val.value);
						}
						if (val.id == '') {
							block_code
							$('#param_block_status').html(val.value);
						}



					});
					$("#param_max_late_charge_disc_rate").html(msg.data.max_late_charge_rate.toString());

					$('#restructure_rate').val(msg.restructure_parameter);
					$("#param_max_normal_disc_rate").html(msg.data.max_discount_rate.toString());


					$("#param_max_interest_disc_rate").html(msg.data.max_interest_rate.toString());
					$("#param_cicilan").html(msg.data.ratio_cicilan.toString());



					$("#param_max_tenor").html(msg.data.max_tenor.toString());


					max_normal_disc_rate = parseFloat(msg.data.max_discount_rate);
					max_interest_disc_rate = parseFloat(msg.data.max_interest_rate);
					max_late_charge_disc_rate = parseFloat(msg.data.max_late_charge_rate);


					limit_max_normal_disc_rate = parseFloat(msg.data.max_discount_rate);
					limit_max_interest_disc_rate = parseFloat(msg.data.max_interest_rate);
					limit_max_late_charge_disc_rate = parseFloat(msg.data.max_late_charge_rate);

					$("#lbl_nama_parameter").html('Parameter Name : <b>' + msg.data.parameter_name + '</b>');
					$("#alert_parameter").html(msg.alert);


				} else {
					$("#alert_parameter").html(msg.alert);
					// showInfo("Parameter Not Found.");
				}
			},
			error: function (e) {
				showInfo(JSON.stringify(e));
				// $('#desc_kondisi_khusus').val('').change();
				$("#alert_parameter").html('<b style="color:red">ERROR</b>');
			}
		});
	}


	// $(document).ready(function(){
	// 	let screen_level = $('#txt_screen_level').val();

	// 	if(screen_level=='NEW'){
	// 		set_new_form();
	// 	}
	// 	else if(screen_level=='EDIT'){

	// 	}
	// });

	jQuery(function ($) {

		let screen_level = $('#txt_screen_level').val();

		if (screen_level == 'NEW') {
			set_new_form();
		} else if (screen_level == 'EDIT') {

		}

		if (product_type == 'CIMB-PL') {
			$("#div_moratorium").show();
		}
		search_param('EDIT');
	});


	function set_new_form() {
		let source = $("#source").val();
		var today = new Date();
		// var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
		var date = '<?=date('Y-m-d');?>';

		$('#flag_kondisi_khusus').change();

		if (source == 'CMS') {

			$('#txt_ptp_date_for_rst').daterangepicker({
				"singleDatePicker": true,
				"autoApply": true,
				"minDate": date,
				"maxDate": ptp_grace_period,
				"locale": {
					"format": "YYYY-MM-DD",
				},
			}, function (start, end, label) {
				console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format(
					'YYYY-MM-DD') + ' (predefined range: ' + label + ')');

				get_data_perhitungan_kotak_merah();
				$("#txt-late-charge-discount-2").val('');
				$('#txt-interest-discount-2').val('');
				$('#txt-late-charge-discount').val('');
				$('#txt-interest-discount').val('');

				$('#txt-total-bayar-diskon').html('');
				$('#txt-payment-pokok-val').val('');

				$('#txt-sisa-pokok-pinjaman-baru-val').val('');
				$('#txt-sisa-pokok-pinjaman-baru').val('');

				$('#txt-payment-pokok').html('');
				$('#txt-payment-pokok-val').val('');

				$('#txt_tenor_val').val('');
				$('#txt_interest_val').val('');

				$('#txt_new_installmetn_amount_val').val('');
				$('#txt_new_installmetn_amount').val('');
			});


		} else if (source == 'TELE') {

			$("#txt_ptp_date_for_rst").datepicker({
				dateFormat: 'yy-mm-dd',
				minDate: date,
				maxDate: ptp_grace_period
			}).on("change", function () {
				get_data_perhitungan_kotak_merah();
				$("#txt-late-charge-discount-2").val('');
				$('#txt-interest-discount-2').val('');
				$('#txt-late-charge-discount').val('');
				$('#txt-interest-discount').val('');

				$('#txt-total-bayar-diskon').html('');
				$('#txt-payment-pokok-val').val('');

				$('#txt-sisa-pokok-pinjaman-baru-val').val('');
				$('#txt-sisa-pokok-pinjaman-baru').val('');

				$('#txt-payment-pokok').html('');
				$('#txt-payment-pokok-val').val('');

				$('#txt_tenor_val').val('');
				$('#txt_interest_val').val('');

				$('#txt_new_installmetn_amount_val').val('');
				$('#txt_new_installmetn_amount').val('');
			});
		}



	}

	function set_edit_form() {

	}

	function validasi2(elm) {
		// countSisaPokokPinjaman();
		let val = parseFloat(elm.value);
		if (val <= 0 || elm.value == '') {
			// elm.value = '0';
		}
	}

	function validationparam(elm, name, elmTo) {


		elm.value = elm.value.replace(/[^0-9.]/g, "");

		let disc_late_charge = parseFloat($("#txt-late-charge-discount-2").val().replace(',', '.'));
		let disc_principal = parseFloat($("#txt-principle-balance-discount-2").val().replace(',', '.'));
		let disc_interest = parseFloat($('#txt-interest-discount-2').val().replace(',', '.'));
		// let disc_penalty  = parseFloat($('#txt-penalty-discount-2').val().replace(',','.'));
		if (isNaN(disc_principal)) disc_principal = 0;
		if (isNaN(disc_interest)) disc_interest = 0;
		// if(isNaN(disc_penalty))disc_penalty=0;



		if (name == 'principle-balance') {
			let current = 0
			current = max_normal_disc_rate - disc_principal;

			let max = max_normal_disc_rate;

			let sisa = 0
			sisa = limit_max_normal_disc_rate - disc_principal;
			sisa = sisa.toString();
			if (sisa < 0) {
				$(elm).val(limit_max_normal_disc_rate);
			}
		} else if (name == 'interest') {
			let curInterrest = parseFloat($("#txt-due-interest").val());
			if (curInterrest == 0) {
				$(elm).val('0');
			} else {
				let current = 0
				current = max_interest_disc_rate_2 - disc_interest;

				let max = max_interest_disc_rate_2;

				let sisa = 0
				sisa = limit_max_interest_disc_rate_2 - disc_interest;
				sisa = sisa.toString();
				if (sisa < 0) {
					$(elm).val(limit_max_interest_disc_rate_2);
				}
			}
		} else if (name == 'late-charge') { //late-charge
			let curlatecharge = parseFloat($("#txt-late-charge").val().replaceAll(',', ''));
			if (curlatecharge == 0) {
				$(elm).val('0');
			} else {
				let current = 0
				current = max_late_charge_disc_rate - disc_late_charge;

				let max = max_late_charge_disc_rate;

				let sisa = 0
				sisa = limit_max_late_charge_disc_rate - disc_late_charge;
				sisa = sisa.toString();
				if (sisa < 0) {
					$(elm).val(limit_max_late_charge_disc_rate);
				}
			}

		} else if (name == 'penalty') { //penalty
			let curPenalty = parseFloat($("#txt-penalty").val());
			if (curPenalty == 0) {
				$(elm).val('0');
			} else {
				let current = 0
				current = max_penalty_disc_rate - disc_penalty;

				let max = max_penalty_disc_rate;

				let sisa = 0
				sisa = max_penalty_disc_rate - disc_penalty;
				sisa = sisa.toString();
				if (sisa < 0) {
					$(elm).val(max_penalty_disc_rate);
				}
			}
		}


		if ($("#flag_deviation").val() == '0') {
			$("#txt_deviation_reason").val('').attr('disabled', true);
		} else {
			$("#txt_deviation_reason").val('').attr('disabled', false);
		}

		setTimeout(() => {
			diskon_to_amount(elm, elmTo);
		}, 600);

	}

	function countAmountPTP(elm) {

		if (elm.value == '') return false;
		let valAmnt = parseFloat(elm.value.replaceAll(',', ''));
		let payBunga = parseFloat($("#txt-payment-bunga").val().replaceAll(',', '').replace(/\s/g, ''));
		let payDenda = parseFloat($("#txt-payment-denda").val().replaceAll(',', '').replace(/\s/g, ''));
		let payPokok = valAmnt - payBunga - payDenda;

		payPokok = isNaN(payPokok) ? 0 : payPokok;

		let strPayPokok = addPeriodx(payPokok);

		$('#txt-payment-pokok').html(strPayPokok);
		$('#txt-payment-pokok-val').val(payPokok);



		elm.value = elm.value.replace(/[^0-9.,]/g, "");
		elm.value = addPeriod(elm.value.toString(), elm);

		countSisaPokokPinjaman();
		countNewInstallmanetAmount();
	}

	function currencyformat(elm) {

		if (elm.value == '') return false;
		elm.value = elm.value.replace(/[^0-9.,]/g, "");
		elm.value = addPeriod(elm.value.toString(), elm);
		countSisaPokokPinjaman();
		countNewInstallmanetAmount();
	}

	function numberOnly(elm) {
		let deviasi = $("#flag_deviation").val();
		if (elm.id == "txt_interest_val") {
			if (deviasi == '0') {
				if (elm.value == '') return false;
				elm.value = elm.value.replace(/[^0-9.,]/g, "");
				// if(parseFloat(elm.value)>max_interest_disc_rate){
				if (parseFloat(elm.value) > 100) {
					elm.value = 100;
				} else if (parseFloat(elm.value) < 0) {
					elm.value = '0';
				}
			} else {
				//deviasi ,maksimal 100
				if (elm.value == '') return false;
				elm.value = elm.value.replace(/[^0-9.,]/g, "");
				if (parseFloat(elm.value) > 100) {
					elm.value = 100;
				} else if (parseFloat(elm.value) < 0) {
					elm.value = '0';
				}
			}



		} else if (elm.id == "txt_tenor_val") {
			$("#alert_input_tenor").hide();
			if ($("#flag_deviation").val() == '1') {
				elm.value = elm.value.replace(/[^0-9.,]/g, "");
			} else {
				let max_tenor = parseFloat($("#param_max_tenor").html());
				if (isNaN(max_tenor)) {
					elm.value = '0';
					$("#alert_input_tenor").show();
				} else {
					elm.value = elm.value.replace(/[^0-9.,]/g, "");
					if (elm.value > max_tenor) {
						elm.value = max_tenor.toString();
					} else if (elm.value < 0) {
						elm.value = '0';
					}
				}
			}

			if (product_id == 'CC') {
				setTimeout(() => {
					get_interest_api(elm.value);
				}, 200);
			}

		} else {
			if (elm.value == '') return false;
			elm.value = elm.value.replace(/[^0-9.,]/g, "");
		}
		setTimeout(() => {
			countSisaPokokPinjaman();
			countNewInstallmanetAmount();
		}, 600);

	}


	function addPeriodx(nStr) {
		let currency = nStr;
		// currency = currency.replace(',','x');
		// currency = currency.replaceAll('.','');
		// currency = currency.replace('.',',');

		currency = (parseFloat(currency)).toLocaleString('en-US', {
			style: 'currency',
			currency: 'IDR',
			minimumFractionDigits: 2
		}).replace('IDR', '').trim();
		// currency=currency.replaceAll(',','.').replace('x',',');
		// console.log('currency result',currency);
		return currency;
	}

	function addPeriod(nStr, elm) {
		let currency = nStr;
		currency = currency.replaceAll(',', '');


		// currency = (parseFloat(currency)).toLocaleString('en-US', {
		// 	style: 'currency',
		// 	currency: 'idr',
		// 	minimumFractionDigits: 0
		// }).replace('IDR','').trim();
		// console.log('currency',currency);
		currency = addCommas(currency);
		amount_to_diskon(elm);
		return currency;
	}

	$('#flag_deviation').change(function () {
		// $("#txt-late-charge-discount-2").val('0');
		// $('#txt-interest-discount-2').val('0');
		// $('#txt-late-charge-discount').val('0');
		// $('#txt-interest-discount').val('0');
		$("#txt-late-charge-discount-2").val('');
		$('#txt-interest-discount-2').val('');
		$('#txt-late-charge-discount').val('');
		$('#txt-interest-discount').val('');

		$('#restructure_rate').val('');
		$('#txt_hirarki').val('');


		$('#txt-payment-pokok').html('');
		$('#txt-payment-pokok-val').val('');
		let val = $(this).val();
		if (val == '1') {
			$('#flag_kondisi_khusus').attr('disabled', true);
			$('#desc_kondisi_khusus').attr('disabled', true);
			$('#txt_deviation_reason').attr('disabled', false).val('').change();

			$("#lbl_nama_parameter").html('Parameter Name : -');
			$('#tbl_filter_parameter th').css('color', 'gray');
			$('#tbl_filter_parameter td').css('color', 'gray');
			$('#tbl_filter_parameter td').html('-');


			max_special_condition_disc_rate = 100;
			max_block_status_disc_rate = 100;
			max_principal_disc_rate = 100;
			max_normal_disc_rate = 100;
			max_interest_disc_rate = 100;

			limit_max_special_condition_disc_rate = 100;
			limit_max_block_status_disc_rate = 100;
			limit_max_principal_disc_rate = 100;
			limit_max_normal_disc_rate = 100;
			limit_max_interest_disc_rate = 100;
		} else {
			$('#flag_kondisi_khusus').attr('disabled', false);
			$('#flag_kondisi_khusus').change();
			$('#txt_deviation_reason').attr('disabled', true).val('').change();
		}
	});

	function get_data_perhitungan_kotak_merah() {
		$("#loading_kotak_merah").show();
		$('#kotak_merah [type=text]').val('0');
		$.ajax({
			type: "POST",
			url: URL_DATA + "workflow_pengajuan/Workflow_pengajuan_restructure/get_data_kotak_merah",
			data: {
				due_date: due_date,
				ptp_date: $('#txt_ptp_date_for_rst').val(),
				product_id: product_id,
				agreement_no: $('#txt_card_number').val(),
				tipe_pengajuan: "RSTR"
			},
			dataType: "json",
			//timeout: 3000,
			success: function (msg) {
				// console.log(msg);
				if (msg.success) {
					$("#txt-principle-balance").val(addPeriodx(msg.data.principal_amount_balance.toString()));
					$("#txt-principle-balance-val").val(msg.data.principal_amount_balance);

					$("#txt-total-due-balance-installment").val(addPeriodx(msg.data
						.total_due_balance_installemnt.toString()));
					$("#txt-total-due-balance-installment-val").val(msg.data.total_due_balance_installemnt);

					$("#txt-due-interest").val(addPeriodx(msg.data.interest_due.toString()));
					$("#txt-due-interest-val").val(msg.data.interest_due);

					$("#txt-late-charge").val(addPeriodx(msg.data.late_charge.toString()));
					$("#txt-late-charge-val").val(msg.data.late_charge);

					$("#txt-penalty").val(addPeriodx(msg.data.penalty.toString()));
					$("#txt-penalty-val").val(msg.data.penalty);

					$("#txt-stamp-duty").val(addPeriodx(msg.data.stamp_duty.toString()));
					$("#txt-stamp-duty-val").val(msg.data.stamp_duty);

					var total = parseFloat(msg.data.total);

					$("#txt-total").val(addPeriodx(total.toString()));
					$("#txt-total-val").val(total);

					$("#txt-payment-bunga").val(addPeriodx(msg.data.interest_due.toString()));
					$("#txt-payment-bunga-val").val(msg.data.interest_due);

					$("#txt-payment-denda").val(addPeriodx(msg.data.late_charge.toString()));
					$("#txt-payment-denda-val").val(msg.data.late_charge);


					$("#span-principle-balance").html(addPeriodx(msg.data.principal_amount_balance
						.toString()));
					$("#span-total-due-balance-installment").html(addPeriodx(msg.data
						.total_due_balance_installemnt.toString()));
					$("#span-due-interest").html(addPeriodx(msg.data.interest_due.toString()));
					$("#span-late-charge").html(addPeriodx(msg.data.late_charge.toString()));
					$("#span-penalty").html(addPeriodx(msg.data.penalty.toString()));
					$("#span-stamp-duty").html(addPeriodx(msg.data.stamp_duty.toString()));

					$("#span-total").html(addPeriodx(total.toString()));

					$("#span-payment-bunga").html(addPeriodx(msg.data.interest_due.toString()));
					$("#span-payment-denda").html(addPeriodx(msg.data.late_charge.toString()));


				} else {
					showWarning(msg.message);
				}
				$("#loading_kotak_merah").hide();
			},
			error: function (err) {
				// $('#kotak_merah [type=text]').val('0');

			}
		});
	}

	function diskon_to_amount(elm, toElm) {
		console.log('toElm', toElm);
		let val_diskon = elm.value;
		val_diskon = val_diskon.toString();
		if (toElm == 'txt-principle-balance-discount') {
			let principal_balance = $("#txt-principle-balance").val();
			principal_balance = principal_balance.replaceAll(",", "");

			if (isNaN(principal_balance)) principal_balance = 0;
			let counting_diskon = 0;

			principal_balance = parseFloat(principal_balance);
			counting_diskon = (val_diskon * principal_balance) / 100;
			counting_diskon = counting_diskon.toString();
			// console.log('counting_diskon',counting_diskon);
			// console.log('counting_diskon addPeriodx',addPeriodx(counting_diskon.toString()));


			setTimeout(() => {
				$('#' + toElm.trim()).val(addPeriod(counting_diskon.toString(), null));
			}, 100);
		} else if (toElm == 'txt-interest-discount') {
			let due_interest = $("#txt-due-interest").val();
			due_interest = due_interest.replaceAll(",", "");

			if (isNaN(due_interest)) due_interest = 0;
			let counting_diskon = 0;

			due_interest = parseFloat(due_interest);
			counting_diskon = (val_diskon * due_interest) / 100;
			counting_diskon = counting_diskon.toString();

			setTimeout(() => {
				$('#' + toElm.trim()).val(addPeriod(counting_diskon.toString(), null));
			}, 100);

		} else if (toElm == 'txt-late-charge-discount') {
			let late_charge = $("#txt-late-charge").val();
			late_charge = late_charge.replaceAll(",", "");

			if (isNaN(late_charge)) late_charge = 0;
			let counting_diskon = 0;

			late_charge = parseFloat(late_charge);
			counting_diskon = (val_diskon * late_charge) / 100;
			counting_diskon = counting_diskon.toString();
			console.log('counting_diskon', counting_diskon);
			console.log('counting_diskon addPeriodx', addPeriodx(counting_diskon.toString()));


			setTimeout(() => {
				$('#' + toElm.trim()).val(addPeriod(counting_diskon.toString(), null));
			}, 100);
		} else if (toElm == 'txt-penalty-discount') {
			let penalty = $("#txt-penalty").val();
			penalty = penalty.replaceAll(",", "");

			if (isNaN(penalty)) penalty = 0;
			let counting_diskon = 0;

			penalty = parseFloat(penalty);
			counting_diskon = (val_diskon * penalty) / 100;
			counting_diskon = counting_diskon.toString();
			// console.log('counting_diskon',counting_diskon);
			// console.log('counting_diskon addPeriodx',addPeriodx(counting_diskon.toString()));


			setTimeout(() => {
				$('#' + toElm.trim()).val(addPeriod(counting_diskon.toString(), null));
			}, 100);
		}

		setTimeout(() => {
			countSisaPokokPinjaman();
			countNewInstallmanetAmount();
		}, 200);


	}

	function geTotalBayar() {
		let totalkotakmerah = $('#txt-total').val();
		totalkotakmerah = parseFloat(totalkotakmerah.replaceAll(',', ''));

		let principalBalance = $('#txt-late-charge-discount').val();
		if (principalBalance == '') principalBalance = '0';
		principalBalance = parseFloat(principalBalance.replaceAll(',', ''));

		let interest = $('#txt-interest-discount').val();
		if (interest == '') interest = '0';
		interest = parseFloat(interest.replaceAll(',', ''));

		let penalty = $('#txt-penalty-discount').val();
		if (penalty == '') penalty = '0';
		penalty = parseFloat(penalty.replaceAll(',', ''));

		// console.log('interest',interest);
		// console.log('principalBalance',principalBalance);
		// console.log('totalkotakmerah',totalkotakmerah);
		let alldiskon = interest + principalBalance + penalty;
		let totaldiskon = totalkotakmerah - alldiskon;
		$('#txt-payment-pokok-val').val(totaldiskon.toString());
		// console.log('totalkotakmerah',totalkotakmerah);
		// console.log('totaldiskon',totaldiskon);
		totalkotakmerah = addPeriodx(totaldiskon.toString());


		$('#txt-total-bayar-diskon').html('<b>' + totalkotakmerah + '</b>');

	}

	function amount_to_diskon(elm) {
		if (elm == null) return false;

		let elmTo;
		let amount = parseFloat(elm.value.replaceAll(',', ''));
		let countingDiskon;
		if (elm.id == 'txt-late-charge-discount') {
			let late_charge = parseFloat($('#txt-late-charge').val().replaceAll(',', ''));
			elmTo = 'txt-late-charge-discount-2';
			if (amount == '') {
				$('#' + elmTo).val('');
				return false;
			}

			if (late_charge == 0) {
				$('#' + elmTo).val('0');
			} else {
				countingDiskon = (amount / late_charge) * 100;

				if (isNaN(countingDiskon)) countingDiskon = '';
				countingDiskon = countingDiskon.toString();


				$('#' + elmTo).val(countingDiskon);
			}

			validationparam($('#' + elmTo)[0], 'late-charge', elm.id);


		} else if (elm.id == 'txt-interest-discount') {
			let interest = parseFloat($('#txt-due-interest').val().replaceAll(',', ''));
			elmTo = 'txt-interest-discount-2';

			if (amount == '') {
				$('#' + elmTo).val('');
				return false;
			}

			if (interest == 0) {
				$('#' + elmTo).val('0');
			} else {
				countingDiskon = (amount / interest) * 100;

				if (isNaN(countingDiskon)) countingDiskon = '';
				countingDiskon = countingDiskon.toString();

				$('#' + elmTo).val(countingDiskon);

			}

			validationparam($('#' + elmTo)[0], 'interest', elm.id);
		} else if (elm.id == 'txt-principle-balance-discount') {
			let balance = parseFloat($('#txt-principle-balance').val().replaceAll(',', ''));


			elmTo = 'txt-principle-balance-discount-2';


			if (amount == '') {
				$('#' + elmTo).val('');
				return false;
			}

			if (balance == 0) {
				$('#' + elmTo).val('0');
			} else {

				countingDiskon = (amount / balance) * 100;

				if (isNaN(countingDiskon)) countingDiskon = '';
				countingDiskon = countingDiskon.toString();

				$('#' + elmTo).val(countingDiskon);
			}

			validationparam($('#' + elmTo)[0], 'principle-balance', elm.id);
		} else if (elm.id == 'txt-penalty-discount') {
			let penalty = parseFloat($('#txt-penalty').val().replaceAll(',', ''));


			elmTo = 'txt-penalty-discount-2';


			if (amount == '') {
				$('#' + elmTo).val('');
				return false;
			}

			if (penalty == 0) {
				$('#' + elmTo).val('0');
			} else {

				countingDiskon = (amount / penalty) * 100;

				if (isNaN(countingDiskon)) countingDiskon = '';
				countingDiskon = countingDiskon.toString();

				$('#' + elmTo).val(countingDiskon);
			}

			validationparam($('#' + elmTo)[0], 'penalty', elm.id);
		}
	}

	function countSisaPokokPinjaman() {
		let total = parseFloat($("#txt-total").val().replaceAll(',', ''));
		let payBunga = parseFloat($("#txt-payment-bunga").val().replaceAll(',', ''));
		let payDenda = parseFloat($("#txt-payment-denda").val().replaceAll(',', ''));
		let distPrincipleBalance = parseFloat($("#txt-principle-balance-discount").val().replaceAll(',', ''));
		let distLateCharge = parseFloat($("#txt-late-charge-discount").val().replaceAll(',', ''));
		let distInterest = parseFloat($("#txt-interest-discount").val().replaceAll(',', ''));
		let payPokok = parseFloat($("#txt-payment-pokok-val").val().replaceAll(',', ''));

		if (isNaN(total)) total = 0;
		if (isNaN(payBunga)) payBunga = 0;
		if (isNaN(payDenda)) payDenda = 0;
		if (isNaN(distPrincipleBalance)) distPrincipleBalance = 0;
		if (isNaN(distLateCharge)) distLateCharge = 0;
		if (isNaN(distInterest)) distInterest = 0;
		if (isNaN(payPokok)) payPokok = 0;

		let sisa = total - payBunga - payDenda - distLateCharge - distInterest - distPrincipleBalance - payPokok;
		$('#txt-sisa-pokok-pinjaman-baru').val(addPeriodx(sisa.toString()));
		$('#txt-sisa-pokok-pinjaman-baru-val').val(sisa);
	}

	function countNewInstallmanetAmount() {
		let sisapokokpinjamanbaru = parseFloat($('#txt-sisa-pokok-pinjaman-baru-val').val());
		let txt_interest_val = parseFloat($('#txt_interest_val').val());
		let txt_tenor_val = parseFloat($('#txt_tenor_val').val());


		if (isNaN(sisapokokpinjamanbaru)) sisapokokpinjamanbaru = 0;
		if (isNaN(txt_interest_val)) txt_interest_val = 0;
		if (isNaN(txt_tenor_val)) txt_tenor_val = 0;




		let cicilan = get_new_installment_amount();

		// cicilan = cicilan.toFixed(2);
		// $("#txt_new_installment_amount").html("<b>"+addPeriodx(cicilan.toString())+"</b>");
		// $("#txt_new_installment_amount_val").val(cicilan);


	}

	function save($flag) {
		// $flag = 1 = save and finish
		// $flag = 0 = save and draft

		let passes = true;



		if ($('#flag_kondisi_khusus').val() == '1') {
			if ($('#desc_kondisi_khusus').val() == '') {
				showWarning("Deskripsi Kondisi Khusus kosong!");
				return false;
			}
		}

		if ($('#flag_deviation').val() == '1') {
			if ($('#txt_deviation_reason').val() == '') {
				showWarning("Deviation Reason kosong!");
				return false;
			}
		}

		if ($('#txt_ptp_date_for_rst').val() == '') {
			showWarning("PTP Date kosong!");
			return false;
		}

		if ($('#txt-amount-ptp-dp').val() == '' || parseInt($('#txt-amount-ptp-dp').val()) <= 0) {
			showWarning("amount PTP kosong!");
			return false;
		}

		if ($('#txt_tenor_val').val() == '') {
			showWarning("Tenor kosong!");
			return false;
		}

		if ($('#txt_interest_val').val() == '') {
			showWarning("Interest kosong!");
			return false;
		}

		if (product_type == 'CIMB-PL') {
			if ($("#txt-moratorium-val").val() == '') {
				showWarning("moratorium kosong!");
				return false;
			}
		}

		if (!flag_status_ratio) {
			showWarning("Ratio cicilan melebihi limit!");
			return false;
		}

		if ($("#txt-principle-balance-discount").val() == '') {
			$("#txt-principle-balance-discount").val('0');
		}
		if ($("#txt-principle-balance-discount-2").val() == '') {
			$("#txt-principle-balance-discount-2").val('0');
		}

		if ($("#txt-late-charge-discount").val() == '') {
			$("#txt-late-charge-discount").val('0');
		}
		if ($("#txt-late-charge-discount-2").val() == '') {
			$("#txt-late-charge-discount-2").val('0');
		}

		if ($("#txt-interest-discount").val() == '') {
			$("#txt-interest-discount").val('0');
		}
		if ($("#txt-interest-discount-2").val() == '') {
			$("#txt-interest-discount-2").val('0');
		}


		$('#flag_kondisi_khusus').attr('disabled', false);
		$('#desc_kondisi_khusus').attr('disabled', false);

		$('#btn-save-finish ,#btn-save-draft').attr('disabled', true);
		$('#loading_save').show();
		$.ajax({
			url: URL_DATA +
				"workflow_pengajuan/workflow_pengajuan_restructure/save_pengajuan_restructure?status=" + $flag,
			type: "post",
			data: $('#formDiscountRequest').serialize(),
			success: function (msg) {


				if (msg.success) {
					showInfo(msg.message);
					getData();
					$("#id").val(msg.id);

					$('#txt_ptp_date').val($('#txt_ptp_date_for_rst').val());
					$('#txt_ptp_amount').val($("#txt-amount-ptp-dp").val());
					$('#txt_ptp_amount').attr('disabled', true);
					setTimeout(() => {
						$("#loading_save").hide();
						// $('#btn-save-finish ,#btn-save-draft').attr('disabled',false);
					}, 300);

					try {
						update_join_program('RESTRUCTURE'); //fungsi ada di panin_deskcoll agent_main.js
					} catch (error) {
						console.log(error);
					}

				} else {
					showWarning(msg.message);
					$('#btn-save-finish ,#btn-save-draft').attr('disabled', false);
					setTimeout(() => {
						$("#loading_save").hide();
						// $('#btn-save-finish ,#btn-save-draft').attr('disabled',false);
					}, 300);
				}
			},
			error: function (err) {
				showWarning('Gagal tersimpan, mohon ulangi kembali');

				console.log(err);

				setTimeout(() => {
					$("#loading_save").hide();
					$('#btn-save-finish ,#btn-save-draft').attr('disabled', false);
				}, 300);
			},
			dataType: 'json'
		});


	}


	function get_new_installment_amount(sisapokokpinjamanbaru, txt_tenor_val, txt_interest_val) {
		let tipe = $('input[name="btnradiotipesukubunga"]:checked').val();
		tipe_suku_bunga = tipe;
		let tenor = $("#txt_tenor_val").val();
		let hutang_pokok = $("#txt-sisa-pokok-pinjaman-baru-val").val();
		let interest = $("#txt_interest_val").val();
		let ptp = $("#txt_ptp_date_for_rst").val();
		let dp = $("#txt-amount-ptp-dp").val();
		let moratorium = $("#txt-moratorium-val").val();

		if (tenor == '') {

			return false;
		} else if (hutang_pokok == '') {

			return false;
		} else if (interest == '') {

			return false;
		} else if (ptp == '') {

			return false;
		} else if (dp == '') {

			return false;
		}

		if (product_type == 'CIMB-PL') {
			if ($("#txt-moratorium-val").val() == '') {

				return false;
			}
		}

		$.ajax({
			type: "GET",
			async: true,
			dataType: "json",
			url: URL_DATA + 'workflow_pengajuan/payment_plan/get_new_installment_amount?tipe=' + tipe + '&tenor=' +
				tenor + "&hutang=" + hutang_pokok + "&bunga=" + interest + "&moratorium=" + moratorium,
			success: function (msg) {
				console.log(msg);


				let cicilan = msg.new_installmet_amount;
				cicilan = cicilan.toFixed(2);
				$("#txt_new_installment_amount").val(addPeriodx(cicilan.toString()));
				$("#txt_new_installment_amount_val").val(cicilan);

				//cek ratio
				let current_ration = sisa_pokok_pinjaman_baru / os_balance;
				current_ration = current_ration.toFixed(2);


				param_cicilan =
					100; //UNTUK PRODUCT PL TIDAK ADA RATIO , DARI PADA DILEPAS MENDING BATASNYA DI BUAT LEBAR SUPAYA SEAKAN TIDAK ADA LIMIT RATIO CICILAN; BY HELMI
				flag_status_ratio = true;


				$("#current_ratio").html(current_ration.toString());

				$("#alert_ratio").hide();
				$("#body-math-counting").hide();
				flag_status_ratio = true;



			}
		});
	}

	function addCommas(nStr) {
		nStr += '';
		var x = nStr.split('.');
		var x1 = x[0];
		var x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}

	function show_jadwal_pembayaran() {
		let id = $("#txt_id_pengajuan").val();
		let tipe = $('#txt_tipe_suku_bunga').val();
		let product_code = "<?=$data_pengajuan['product_code']?>";

		var buttons = {
			"button": {
				"label": "Close",
				"className": "btn-sm"
			}
		}
		showCommonDialog3(1000, 700, "JADWAL PEMBAYARAN - " + tipe, URL_DATA +
			'workflow_pengajuan/workflow_pengajuan_restructure/show_payment_plan?id=' + id + "&tipe=" + tipe +
			"&product_code=" + product_code, buttons);

	}

	$('input[name=phone-owner]').click(function () {
		let selected = $('input[name=phone-owner]:checked').val();
		if (selected == 'other_phone') {
			$('#txt_other_phone').attr('disabled', false);
			$('#txt_other_phone').attr('readonly', false);
		} else {
			$('#txt_other_phone').attr('disabled', true);
			$('#txt_other_phone').attr('readonly', true);
		}
	});


	$("#btn-to-call").click(function () {
		if ($(this).hasClass('btn-success')) {

			let number = $("input[name=phone-owner]:checked").val();

			if (number != "other_phone") {
				if (number == "") {
					showWarning("Nomor Telpn kosong");
				} else {
					outbound();
					setTimeout(() => {
						$("#btn-to-call").removeClass('btn-success').addClass('btn-danger').attr(
							'disabled', true);
						$("#btn-to-call").children().removeClass("icon-phone").addClass("icon-ban-circle");

						myVar = setInterval(myTimer, 1000);
						originateCall(number, $("#txt_id_pengajuan").val());
					}, 500);

				}
			} else {
				outbound();
				setTimeout(() => {
					$("#btn-to-call").removeClass('btn-success').addClass('btn-danger').attr('disabled',
						true);
					$("#btn-to-call").children().removeClass("icon-phone").addClass("icon-ban-circle");

					myVar = setInterval(myTimer, 1000);
					originateCall($("#txt_other_phone").val(), $("#txt_id_pengajuan").val());
				}, 500);
			}
		} else {
			swal({
					title: "WARNING!",
					text: "Apakah ingin mengakhiri panggilan ?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDelete) => {
					if (willDelete) {

						disconnectCall();
						setTimeout(() => {
							$("#btn-to-call").removeClass('btn-danger').addClass('btn-success');
							$("#btn-to-call").children().removeClass("icon-ban-circle").addClass(
								"icon-phone");
						}, 300);
					} else {

					}
				});
		}
	});

	function upload_doc(status) {
		let isUpload = true;
		let submit_id = $("#submit_id").val();
		last_action = status;

		if (!checkValidate()) {
			return false;
		}


		let narasi = 'UPLOAD';


		swal({
				title: "Upload Data",
				text: narasi,
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					if (status == 'FINISH') {
						$('#alert_upload_document').show();
						$('input[type=file]').attr('disabled', true);
						$('#file-other-doc-info').attr('disabled', true);
						$('#upload-data-remark').attr('disabled', true);
						$('.btn-form-upload').attr('disabled', true);
					} else if (status == 'DRAFT') {

					}

					if ($("#upload-data-remark").val() != '') {
						let id = $("#txt_id_pengajuan").val();
						let remark = $("#upload-data-remark").val();

						$.ajax({
							type: "POST",
							url: GLOBAL_MAIN_VARS["SITE_URL"] +
								"workflow_pengajuan/workflow_pengajuan_restructure/save_remark_verif",
							data: {
								id_pengajuan: id,
								remark: remark,
								submit_id: submit_id
							},
							dataType: "json",
							success: function (msg) {
								console.log('save_remark_verif', msg);
								// $('input[type=text] , textarea').val('');
							},
							error: function (err) {
								console.log('save_remark_verif', err);
							}
						});
					}

					$.each($('.doUpload'), function (val, i) {
						// console.log('val',i.id);
						// console.log('i',i);
						let id_upload = i.id;
						let label = i.dataset.label;
						let isUpload = true;


						try {
							if ($("#" + id_upload).attr('data-upload') == "false" || $("#" + id_upload).attr(
									'data-upload') == false) {
								isUpload = false;
							} else if (typeof (document.querySelector("#" + id_upload).files[0]) ==
								'undefined') {
								showWarning(label + ' is empty!');
								isUpload = false;
								// return false;
							}
						} catch (error) {
							console.log('error', error);
							isUpload = false;
							showWarning(label + ' is empty!');
							// return false;
						}


						if (isUpload) {
							$("#" + id_upload).attr('disabled', true);
							$('#progress-bar-' + id_upload).show();

							var formdata = new FormData();
							// formdata.append('id-input-file-1',$("#id-input-file-1")[0].files[0]);
							formdata.append(id_upload, document.querySelector("#" + id_upload).files[0]);
							formdata.append('id_pengajuan', $("#txt_id_pengajuan").val());
							formdata.append('agreement_no', $("#txt_card_number").val());
							formdata.append('id_upload', id_upload);
							formdata.append('tipe_pengajuan', 'RSTR');
							formdata.append('status', status);
							formdata.append('submit_id', submit_id);
							$('#progress-bar-' + id_upload).css('width', '0%');
							$('#div-progress-bar-' + id_upload).show();
							$('#btn-' + id_upload).hide();
							$('#alert-' + id_upload).html('');


							setTimeout(() => {


								$.ajax({
									url: GLOBAL_MAIN_VARS["SITE_URL"] +
										"workflow_pengajuan/workflow_pengajuan_restructure/upload_document",
									data: formdata,
									async: true,
									type: "POST",
									cache: false,
									contentType: false,
									processData: false,
									enctype: 'multipart/form-data',
									xhr: function () {

										var xhr = new window.XMLHttpRequest();
										xhr.upload.addEventListener("progress", function (
											evt) {
											// console.log('upload..');
											if (evt.lengthComputable) {
												// console.log('lengthComputable..');
												var percentComplete = evt.loaded /
													evt.total;
												// console.log(percentComplete);
												var progress_persen = Math.round(
														percentComplete * 100) +
													'%';
												progress_persen = String(
													progress_persen);
												// console.log(id_prog);

												$('#progress-bar-' + id_upload)
													.css('width', progress_persen);
												$('#txt-progress-bar-' + id_upload)
													.html(progress_persen);
												// $('#status').html('<b> Uploading -> ' + (Math.round(percentComplete * 100)) + '% </b>');
											}
										}, false);
										return xhr;
									},
									success: function (msg) {
										// console.log('msg',msg);

										let jumlah_uploaded = parseInt($(
											"#jumlah-uploaded-" + id_upload).attr(
											'value'));
										jumlah_uploaded += 1;
										$("#jumlah-uploaded-" + id_upload).attr('value',
											jumlah_uploaded);
										$("#jumlah-uploaded-" + id_upload).html('(' +
											jumlah_uploaded + ')');

										setTimeout(() => {
											$("#" + id_upload).attr('disabled',
												false);
											$("#" + id_upload).val('');
											$('#div-progress-bar-' + id_upload)
												.hide();
											$('#btn-' + id_upload).show();
											$('#alert-' + id_upload).html(
												'Complete!');
											$('#alert-' + id_upload).css('color',
												'green');
											$('#btn-delete-' + id_upload).hide();
											$("#" + id_upload)[0]
												.nextElementSibling.innerHTML = '';
										}, 1000);
									},
									complete: function (data) {

									},
									error: function (err) {
										setTimeout(() => {
											$("#" + id_upload).attr('disabled',
												false);
											$('#div-progress-bar-' + id_upload)
												.hide();
											$('#btn-' + id_upload).show();

											showWarning(
												'Upload gagal, coba ulangi kembali'
											);
											$('#alert-' + id_upload).html(
												'Failed!');
											$('#alert-' + id_upload).css('color',
												'red');
											$('#reupload-' + id_upload).show();
										}, 1000);
									},
									dataType: 'json'
								});
							}, 1000);



						}

					});

					save_upload_document(status);

				} else {

				}
			});
	}


	function save_upload_document(status) {
		let submit_id = $("#submit_id").val();

		var formdata = new FormData();

		formdata.append('id_pengajuan', $("#txt_id_pengajuan").val());
		formdata.append('agreement_no', $("#txt_card_number").val());
		formdata.append('tipe_pengajuan', 'RSTR');
		formdata.append('status', status);
		formdata.append('submit_id', submit_id);
		$.ajax({
			url: GLOBAL_MAIN_VARS["SITE_URL"] +
				"workflow_pengajuan/workflow_pengajuan_restructure/save_document_verification?tipe=restructure",
			data: formdata,
			async: true,
			type: "POST",
			cache: false,
			contentType: false,
			processData: false,
			enctype: 'multipart/form-data',
			success: function (msg) {

			},
			err: function (err) {

			}
		});
	}

	function save_call_history(status) {
		if (typeof ($('input[name=phone-owner]:checked').val()) == 'undefined') {
			showWarning("Nomor Telfon Belum dipilih!");
			return false;
		}
		let phone = '';
		let tipe_contact = '';
		if ($('input[name=phone-owner]:checked').val() == 'other_phone') {
			phone = $('#txt_other_phone').val();
			tipe_contact = 'other';
		} else {
			phone = $('input[name=phone-owner]:checked').val();
			tipe_contact = $('input[name=phone-owner]:checked').attr('phone_type');
		}

		let agreement_no = $('#txt_card_number').val();
		let call_status = $('#contact_result').val();
		let call_result = $('#result_call').val();
		let remark = $('#call-remark').val();
		let id_pengajuan = $('#txt_id_pengajuan').val();
		let submit_id = $("#submit_id").val();
		if (call_status == '') {
			showWarning("Contact Result Belum dipilih!");
			return false;
		}
		if (call_result == '') {
			showWarning("Result Call Belum dipilih!");
			return false;
		}

		$('#loading_call').show();
		$('#contact_result').attr('disabled', true);
		$('#result_call').attr('disabled', true);
		$('#call-remark').attr('disabled', true);
		$('.btn-form-call').attr('disabled', true);
		var caller_id = null;
		try {
			caller_id = TELEPHONY_CALLER_ID;
		} catch (error) {
			caller_id = null;
		}
		$.ajax({
			type: "POST",
			url: GLOBAL_MAIN_VARS["SITE_URL"] +
				"workflow_pengajuan/workflow_pengajuan_restructure/save_call_history",
			data: {
				status: status,
				tipe_pengajuan: 'RSTR',
				phone: phone,
				tipe_contact: tipe_contact,
				agreement_no: agreement_no,
				call_status: call_status,
				call_result: call_result,
				remark: remark,
				id_pengajuan: id_pengajuan,
				submit_id: submit_id,
				caller_id: caller_id
			},
			dataType: "json",
			timeout: 10000, //10 detik
			success: function (msg) {
				if (msg.success) {
					setTimeout(() => {
						$('#contact_result').val('');
						$('#result_call').val('');
						$('#call-remark').val('');

						if (status != 'FINISH') {
							$('#contact_result').attr('disabled', false);
							$('#result_call').attr('disabled', false);
							$('#call-remark').attr('disabled', false);
							$('.btn-form-call').attr('disabled', false);
						}
						getData();
						$('#loading_call').hide();

					}, 1000);
					showInfo(msg.message);
				} else {
					setTimeout(() => {
						$('#contact_result').attr('disabled', false);
						$('#result_call').attr('disabled', false);
						$('#call-remark').attr('disabled', false);
						$('.btn-form-call').attr('disabled', false);
						$('#loading_call').hide();
					}, 1000);
					showWarning(msg.message);
					getData();
				}



			},
			error: function (e) {
				setTimeout(() => {
					showWarning("Penyimpanan Gagal!");
					$('#contact_result').attr('disabled', false);
					$('#result_call').attr('disabled', false);
					$('#call-remark').attr('disabled', false);
					$('.btn-form-call').attr('disabled', false);
					$('#loading_call').hide();
					getData();
				}, 1000);
			}
		});
	}

	function get_detail_view(id) {
		let id_pengajuan = $('#txt_id_pengajuan').val();
		var buttons = {
			"button": {
				"label": "Close",
				"className": "btn-sm"
			}
		}
		showCommonDialog3(1200, 700, 'DETAIL DOCUMENT', GLOBAL_MAIN_VARS["SITE_URL"] +
			'workflow_pengajuan/workflow_pengajuan_restructure/view_detail_upload/?id=' + id_pengajuan + '&doc=' + id,
			buttons);
	}

	function saveApproval_rstr() {
		event.preventDefault();
		let id_pengajuan = $('#txt_id_pengajuan').val();
		let action = $('#action-approval').val();
		let remark = $('#remark-approval').val();
		let approval_id = $('#approval_id').val();
		var approval_level = $("#APPROVAL_LEVEL").val();

		if (!checkValidate()) {
			return false;
		}

		swal({
				title: "INFO",
				text: "Apakah anda yakin melakukan " + action + " ?",
				icon: "info",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$('#loading_approval').show();

					$('#action-approval').attr('disabled', true);
					$('#remark-approval').attr('disabled', true);
					$('#saveApproval').attr('disabled', true);

					$.ajax({
						type: "POST",
						url: GLOBAL_MAIN_VARS["SITE_URL"] +
							"workflow_pengajuan/workflow_pengajuan_restructure/saveApprovalberjenjang",
						data: {
							csrf_security: $('#token_csrf').val(),
							id: id_pengajuan,
							action: action,
							remark: remark,
							approval_id: approval_id,
							approval_level: approval_level,
							tipe_pengajuan: "restructure"
						},
						dataType: "json",
						timeout: 30000,
						success: function (msg) {
							getData();
							if (msg.success) {
								$('#alert_save_approval').html(msg.message);
								$('#token_csrf').val(msg.newCsrfToken);
								showInfo(msg.message);
							}
							if (msg.isFinish) {
								swal("Selesai!", msg.finishMsg, "success");
							}



							// $('#saveApproval').attr('disabled',false);
							setTimeout(() => {
								$('.checked').remove();
								$('#loading_approval').hide();
							}, 500);

						},
						error: function (err) {
							console.log('saveApproval', err);
							showWarning('error!');

							$('#action-approval').attr('disabled', false);
							$('#remark-approval').attr('disabled', false);
							$('#saveApproval').attr('disabled', false);

							setTimeout(() => {
								$('#loading_approval').hide();
							}, 500);
						}
					});
				} else {
					// swal("Your imaginary file is safe!");
				}
			});
	}
</script>