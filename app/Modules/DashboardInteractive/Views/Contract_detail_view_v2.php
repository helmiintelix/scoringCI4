<?php 
            use App\Models\Common_model;
            $this->Common_model = new Common_model();

            $DB = \Config\Database::connect();
            $this->db = db_connect();
        ?>
			<div class="nav nav-tabs" style="font-size: 10px;">
				<?
					$i = 1;
					$total_outstanding = 0;
					foreach ($contracts as $row) {
						$is_active = ($i == 1 ? 'class="active"' : '');
				?>
				<div class="nav-item" id="contract_<?=$row["CM_CARD_NMBR"];?>" <?= $is_active; ?>>
					<a class="nav-link active" aria-current="page" data-toggle="tab">
						<strong>
							<?= $row["CM_CARD_NMBR"] ?>
						</strong> </br>
						BAL :
						<strong>
							<?=number_format($row["CM_OS_BALANCE"]);?>
						</strong> </br>
						DPD :
						<strong>
							<?=$row["DPD"] ?>
						</strong> </br>

					</a>
				</div>
				<?
						$i++;
					}
				?>
				<input type="hidden" id="total_outstanding_hide" value="<?=number_format($total_outstanding);?>">
			</div>
			<!-- Profile -->
			<div class="" style="height: auto; color: black;">
				<?php 
			foreach($contracts as $row){
			?>
				<div class="container mb-3" style="height: 700px; overflow: auto;">
					<div class="row p-2 d-flex flex-wrap">
						<div class="mb-2 fs-5 fw-bold text-uppercase">
							<center>
								Profile
							</center>
						</div>
						<hr>
						<div class="col-6">
							<?php 
						$data_profile_print = $data_profile;
						$total = count($data_profile_print);
						$total = ceil($total / 2);
						$i = 0;
						foreach($data_profile_print as $key => $field) {
							if($field['section'] == 'customer_data') {
								if($i == $total) {
									break;
								}
								unset($data_profile_print[$key]);
					?>
							<div class="mb-2">
								<label class="fs-6 text-capitalize">
									<?=$field['field_label']?>
								</label>
								<?php 
							if($field['is_currency'] == '1') {
								$sql = "select format('".$row[$field['field_name']]."', 0) as hasil";
								$result = $this->db->query($sql)->getResultArray();
								$row[$field['field_name']] = $result[0]['hasil'];
							}
							if($field['is_masking'] == '1') {
								$row[$field['field_name']] = $this->Common_model->masking_data($row[$field['field_name']]);
							}
						?>
								<input type="text" class="form-control form-control-sm"
									name="<?=$row[$field['field_name']];?>" id="<?=$row[$field['field_name']];?>"
									value="<?=$row[$field['field_name']];?>" readonly
									style="pointer-events: none; background-color: #E0E8F0;">
							</div>
							<?php 
								$i++;
							}
						}
					?>
						</div>
						<div class="col-6">
							<?php 
						foreach($data_profile_print as $field) {
							if($field['section'] == 'customer_data') {
					?>
							<div class="mb-2">
								<label class="fs-6 text-capitalize">
									<?=$field['field_label']?>
								</label>
								<?php 
							if($field['is_currency'] == '1') {
								$sql = "select format('".$row[$field['field_name']]."', 0) as hasil";
								$result = $this->db->query($sql)->getResultArray();
								$row[$field['field_name']] = $result[0]['hasil'];
							}
							if($field['is_masking'] == '1') {
								$row[$field['field_name']] = $this->Common_model->masking_data($row[$field['field_name']]);
							}
						?>
								<input type="text" class="form-control form-control-sm"
									name="<?=$row[$field['field_name']];?>" id="<?=$row[$field['field_name']];?>"
									value="<?=$row[$field['field_name']];?>" readonly
									style="pointer-events: none; background-color: #E0E8F0;">
							</div>
							<?php 
							}
						}
					?>
						</div>
					</div>
					<div class="row p-2 d-flex flex-wrap">
						<div class="mb-2 fs-5 fw-bold text-uppercase">
							<center>
								Loans
							</center>
						</div>
						<hr>
						<div class="col-6">
							<?php 
						$data_contract_print = $data_contract;
						$total = count($data_contract_print);
						$total = ceil($total / 2);
						$i = 0;
						foreach($data_contract_print as $key => $field) {
							if($field['section'] == 'account_data') {
								if($i == $total) {
									break;
								}
								unset($data_contract_print[$key]);
					?>
							<div class="mb-2">
								<label class="fs-6 text-capitalize">
									<?=$field['field_label']?>
								</label>
								<?php 
							if($field['is_currency'] == '1' && $row[$field['field_name']] != '') {
								$sql = "select format(".$row[$field['field_name']].", 0) as hasil";
								$result = $this->db->query($sql)->getResultArray();
								$row[$field['field_name']] = $result[0]['hasil'];
							}
							if($field['is_masking'] == '1') {
								$row[$field['field_name']] = $this->Common_model->masking_data($row[$field['field_name']]);
							}
						?>
								<input type="text" class="form-control form-control-sm"
									name="<?=$row[$field['field_name']];?>" id="<?=$row[$field['field_name']];?>"
									value="<?=$row[$field['field_name']];?>" readonly
									style="pointer-events: none; background-color: #E0E8F0;">
							</div>
							<?php 
								$i++;
							}
						}
					?>
						</div>
						<div class="col-6">
							<?php 
						foreach($data_contract_print as $key => $field) {
							if($field['section'] == 'account_data') {
					?>
							<div class="mb-2">
								<label class="fs-6 text-capitalize">
									<?=$field['field_label']?>
								</label>
								<?php 
							if($field['is_currency'] == '1' && $row[$field['field_name']] != '') {
								$sql = "select format(".$row[$field['field_name']].", 0) as hasil";
								$result = $this->db->query($sql)->getResultArray();
								$row[$field['field_name']] = $result[0]['hasil'];
							}
							if($field['is_masking'] == '1') {
								$row[$field['field_name']] = $this->Common_model->masking_data($row[$field['field_name']]);
							}
						?>
								<input type="text" class="form-control form-control-sm"
									name="<?=$row[$field['field_name']];?>" id="<?=$row[$field['field_name']];?>"
									value="<?=$row[$field['field_name']];?>" readonly
									style="pointer-events: none; background-color: #E0E8F0;">
							</div>
							<?php 
							}
						}
					?>
						</div>
					</div>

					<div class="row p-2 d-flex flex-wrap">
						<div class="mb-2 fs-5 fw-bold text-uppercase">
							<center>
								Contacts
							</center>
						</div>
						<hr>
						<div class="col-12">
							<?php 
						$data_profile_print = $contact_data;
						$total = count($data_profile_print);
						$total = ceil($total / 2);
						$i = 0;
						foreach($data_profile_print as $key => $field) {
							if($field['section'] == 'contact') {
								if($i == $total) {
									break;
								}
								unset($data_profile_print[$key]);
					?>
							<div class="mb-2">
								<label class="fs-6 text-capitalize">
									<?=$field['field_label']?>
								</label>
								<?php 
							if($field['is_currency'] == '1') {
								$sql = "select format('".$row[$field['field_name']]."', 0) as hasil";
								$result = $this->db->query($sql)->getResultArray();
								$row[$field['field_name']] = $result[0]['hasil'];
							}
							if($field['is_masking'] == '1') {
								$row[$field['field_name']] = $this->Common_model->masking_data($row[$field['field_name']]);
							}
						?>
								<input type="text" class="form-control form-control-sm"
									name="<?=$row[$field['field_name']];?>" id="<?=$row[$field['field_name']];?>"
									value="<?=$row[$field['field_name']];?>" readonly
									style="pointer-events: none; background-color: #E0E8F0;">
							</div>
							<?php 
								$i++;
							}
						}
					?>
						</div>
					</div>
				</div>
				<!-- <hr> -->
				<div class="row">
					<div class="col-sm-12 mb-3">
						<div class="btn-group" role="group" aria-label="Basic outlined example">
							<button type="button" class="btn btn-outline-secondary"
								id="btn-action-code-history_<?=$row["CM_CARD_NMBR"];?>">Letter Hist.</button>
							<button type="button" class="btn btn-outline-secondary"
								id="btn-call-result-history_<?=$row["CM_CARD_NMBR"];?>">Contact Result</button>
							<button type="button" class="btn btn-outline-warning"
								id="btn-payment-history_<?=$row["CM_CARD_NMBR"];?>">View Transaction</button>
							<button type="button" class="btn btn-outline-success"
								id="btn-note-history_<?=$row["CM_CARD_NMBR"];?>">Hot Note Hist.</button>
							<button type="button" class="btn btn-outline-info"
								id="btn-ptp-history_<?=$row["CM_CARD_NMBR"];?>">PTP Hist</button>
							<button type="button" class="btn btn-outline-info"
								id="btn-payment-schedule_<?=$row["CM_CARD_NMBR"];?>">Payment Schedule</button>
							<button type="button" class="btn btn-outline-info"
								id="btn-agent-script_<?=$row["CM_CARD_NMBR"];?>">Script</button>
							<button type="button" class="btn btn-outline-info"
								id="btn-dpd-history_<?=$row["CM_CARD_NMBR"];?>">DPD/delq History</button>
							<button type="button" class="btn btn-outline-info"
								id="btn-collateral_<?=$row["CM_CARD_NMBR"];?>">Collateral Detail</button>
						</div>
					</div>
				</div>
				<?php 
			}
			?>
			</div>


	</div>
	<script type="text/javascript">
	jQuery(function($) {
		<?
		$j = 1;
		foreach($contracts as $row){
			$row["CM_CUSTOMER_NMBR"] = str_replace('/','-sls-',$row["CM_CUSTOMER_NMBR"]);
			?>
			var count_approval_discount = "<?=$this->Common_model->get_record_value("count(*)", "cms_discount_request", "card_nmbr='".$row["CM_CARD_NMBR"]."' AND STATUS <> '4'");?>";
			var count_approval_restructure = "<?=$this->Common_model->get_record_value("count(*)", "cms_payment_restructure", "card_nmbr='".$row['CM_CARD_NMBR']."'");?>";
			var status_approval_restructure = "<?=$this->Common_model->get_record_value("status", "cms_payment_restructure", "card_nmbr='".$row['CM_CARD_NMBR']."'");?>";
			var cm_block_code = "<?=$this->Common_model->get_record_value("cm_block_code", "cpcrd_new", "cm_card_nmbr='".$row['CM_CARD_NMBR']."'");?>";
			if(count_approval_discount > 0) {
				$("#btn-payment-restructure_<?=$row["CM_CARD_NMBR"];?>").prop("disabled",true);
				$("#btn-discount-request_<?=$row["CM_CARD_NMBR"];?>").prop("disabled",true);
			}
			if(count_approval_restructure > 0){
				$("#btn-payment-restructure_<?=$row["CM_CARD_NMBR"];?>").prop("disabled",true);
				$("#btn-discount-request_<?=$row["CM_CARD_NMBR"];?>").prop("disabled",true);
				if(status_approval_restructure == 'APPROVE'){
					$("#btn-discount-request_<?=$row["CM_CARD_NMBR"];?>").prop("disabled",false);
				}
			}
			if(cm_block_code == 'M'){
				$("#btn-payment-restructure_<?=$row["CM_CARD_NMBR"];?>").prop("disabled",false);
			}
			$("#btn-payment-restructure_<?=$row["CM_CARD_NMBR"];?>").click(function() {
				var buttons = {
					"save" : {
						"label" : "Save",
						"className" : "btn-sm btn-primary",
						"callback": function() {
							if(parseInt($("#txt_os_angsuran").val().replace(/,/g, '')) > 0){
								showInfo("Mohon lakukan reterminate cicilan terlebih dahulu");
								return false;
							}

							if(parseInt($("#txt_discount").val().replace(/,/g, '')) >  (parseInt($("#txt_total_pinjaman").val().replace(/,/g, ''))  - parseInt($("#txt_pinjaman_pokok").val().replace(/,/g, '')) )){
								showInfo("Restructure tidak bisa diajukan karena discount memotong pokok");
								return false;
							}
							
							$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "detail_account/detail_account/payment_restructure_save/", $('#formPaymentRestructure').serialize(),function(data) {
								if(data.success==true) {
									showInfo("Payment reschedule berhasil.");
									$("#btn-payment-restructure_<?=$row["CM_CARD_NMBR"];?>").prop("disabled",true);
								} else{
									showInfo("Payment reschedule gagal.");
								}
							}, "json");
						}
					},
					"button" : {
						"label" : "Close",
						"className" : "btn-sm btn-danger"
					}
				}

				showCommonDialog3(900, 700, 'Payment Reschedule', GLOBAL_MAIN_VARS["SITE_URL"] + 'detail_account/detail_account/payment_restructure/<?=$row["CM_CUSTOMER_NMBR"];?>/<?=$row["CM_CARD_NMBR"];?>' , buttons);	
			});

			$("#btn-call-history_<?=$row["CM_CARD_NMBR"];?>").click(function() {
				var buttons = {

					"button" : {
						"label" : "Close",
						"className" : "btn-sm btn-danger"
					}
				}

				showCommonDialog3(1000, 800, 'Call History', GLOBAL_MAIN_VARS["SITE_URL"] + 'detail_account/detail_account/call_history/<?=$row["CM_CUSTOMER_NMBR"];?>/<?=$row    ["CM_CARD_NMBR"];?>' , buttons);
			});
			$("#btn-call-result-history_<?=$row["CM_CARD_NMBR"];?>").click(function() {
				var buttons = {

					"button" : {
						"label" : "Close",
						"className" : "btn-sm btn-danger"
					}
				}

				showCommonDialog3(1000, 800, 'Contact History', GLOBAL_MAIN_VARS["SITE_URL"] + 'detail_account/detail_account/call_result_history?customer_id=<?=$row["CM_CUSTOMER_NMBR"];?>&card_no=<?=$row["CM_CARD_NMBR"];?>' , buttons);
			});
			$("#btn-action-code-history_<?=$row["CM_CARD_NMBR"];?>").click(function() {
				var buttons = {

					"button" : {
						"label" : "Close",
						"className" : "btn-sm btn-danger"
					}
				}

				showCommonDialog3(1000, 800, 'Letter History', GLOBAL_MAIN_VARS["SITE_URL"] + 'detail_account/detail_account/action_code_history?customer_id=<?=$row["CM_CUSTOMER_NMBR"];?>&card_no=<?=$row["CM_CARD_NMBR"];?>' , buttons);
			});
			
			$("#btn-trans-history_<?=$row["CM_CARD_NMBR"];?>").click(function() {
				var buttons = {
					
					"button" : {
						"label" : "Close",
						"className" : "btn-sm btn-danger"
					}
				}

				showCommonDialog3(900, 700, 'Transaction History', GLOBAL_MAIN_VARS["SITE_URL"] + 'detail_account/detail_account/transaction_history?customer_id=<?=$row["CM_CUSTOMER_NMBR"];?>&card_no=<?=$row["CM_CARD_NMBR"];?>' , buttons);	
			});

			$("#btn-payment-history_<?=$row["CM_CARD_NMBR"];?>").click(function() {
				var buttons = {

					"button" : {
						"label" : "Close",
						"className" : "btn-sm btn-danger"
					}
				}

				showCommonDialog3(1300, 1000, 'Payment History', GLOBAL_MAIN_VARS["SITE_URL"] + 'detail_account/detail_account/payment_history?customer_id=<?=$row["CM_CUSTOMER_NMBR"];?>&card_no=<?=$row["CM_CARD_NMBR"];?>' , buttons);	
			});

			$("#btn-note-history_<?=$row["CM_CARD_NMBR"];?>").click(function() {
				var buttons = {

					"button" : {
						"label" : "Close",
						"className" : "btn-sm btn-danger"
					}
				}

				showCommonDialog3(1200, 1000, 'Note History', GLOBAL_MAIN_VARS["SITE_URL"] + 'detail_account/detail_account/note_history?customer_id=<?=$row["CM_CUSTOMER_NMBR"];?>&card_no=<?=$row["CM_CARD_NMBR"];?>' , buttons);	
			});

			$("#btn-payment-schedule_<?=$row["CM_CARD_NMBR"];?>").click(function() {
				var buttons = {

					"button" : {
						"label" : "Close",
						"className" : "btn-sm btn-danger"
					}
				}

				showCommonDialog3(1200, 1000, 'Payment Schedule', GLOBAL_MAIN_VARS["SITE_URL"] + 'detail_account/detail_account/payment_schedule?customer_id=<?=$row["CM_CUSTOMER_NMBR"];?>&card_no=<?=$row["CM_CARD_NMBR"];?>' , buttons);	
			});

			$("#btn-ptp-history_<?=$row["CM_CARD_NMBR"];?>").click(function() {
				var buttons = {

					"button" : {
						"label" : "Close",
						"className" : "btn-sm btn-danger"
					}
				}

				showCommonDialog3(1200, 1000, 'PTP History', GLOBAL_MAIN_VARS["SITE_URL"] + 'detail_account/detail_account/ptp_history?fin_account=<?=$row["fin_account"];?>&card_no=<?=$row["CM_CARD_NMBR"];?>' , buttons);
			});

			$("#btn-dpd-history_<?=$row["CM_CARD_NMBR"];?>").click(function() {
				var buttons = {

					"button" : {
						"label" : "Close",
						"className" : "btn-sm btn-danger"
					}
				}

				showCommonDialog3(500, 500, 'DPD History', GLOBAL_MAIN_VARS["SITE_URL"] + 'detail_account/detail_account/dpd_history?card_no=<?=$row["CM_CARD_NMBR"];?>' , buttons);
			});

			$("#btn-collateral_<?=$row["CM_CARD_NMBR"];?>").click(function() {
				var buttons = {

					"button" : {
						"label" : "Close",
						"className" : "btn-sm btn-danger"
					}
				}

				showCommonDialog3(900, 500, 'Collateral Detail', GLOBAL_MAIN_VARS["SITE_URL"] + 'detail_account/detail_account/collateral_detail?card_no=<?=$row["CM_CARD_NMBR"];?>&customer_id=<?=$row["CM_CUSTOMER_NMBR"];?>' , buttons);
			});
			
			$("#btn-agent-script_<?=$row["CM_CARD_NMBR"];?>").click(function() {
				var current_contract_no="<?=$row["CM_CARD_NMBR"];?>";
				// console.log("current contract_no"+current_contract_no);
				var buttons = {
					"button" : {
						"label" : "Close",
						"className" : "btn-sm btn-danger"
					}
				}

				$.ajax({

					url: 'detail_account/detail_account/agent_script_guide',
					data: { "current_contract_no": current_contract_no },
					dataType:"json",
					type: "get",
					success: function(data){
						if(data.success){
							//Ini Data nya
							console.log(data)
							// showInfo(data.data,10000);
							showCommonDialogScript(450,'auto','Script Information',data.data, buttons)
							
						}else{
							showWarning(data.message);

						}
					}
				});
			});

			
			$("#btn-discount-request_<?=$row["CM_CARD_NMBR"];?>").click(function() {
				var buttons = {
					"save" : {
						"label" : "Save",
						"className" : "btn-sm btn-primary",
						"callback": function() {
						
							if($("#txt_total_payoff").val() == '' || $("#txt_total_payoff").val() == '' || $("#txt_exec_date").val() == '' || $("#opt_reason").val() == '' || $("#description").val() == ''){
								showInfo("Mohon isi field yang tersedia terlebih dahulu");
								return false;
							}
							if($("#txt_discount").val() == ''){
								showInfo("Mohon lakukan perhitungan terlebih dahulu");
								return false;
							}
							$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "detail_account/detail_account/discount_request_save/", $('#formDiscountRequest').serialize(),function(data) {
								if(data.success==true) {
									showInfo("Discount request berhasil.");
									$("#btn-discount-request_<?=$row["CM_CARD_NMBR"];?>").prop("disabled",true);
								} else{
									showInfo("Discount request gagal.");
								}
							}, "json");
						}
					},
					"button" : {
						"label" : "Close",
						"className" : "btn-sm btn-danger"
					}
				}

				showCommonDialog3(900, 500, 'Discount Request', GLOBAL_MAIN_VARS["SITE_URL"] + 'detail_account/detail_account/discount_request/<?=$row["CM_CUSTOMER_NMBR"];?>/<?=$row["CM_CARD_NMBR"];?>' , buttons);	
			});
			<?
		}
		?>
		// scrollables
		// $('.slim-scroll').each(function () {
		// 	var $this = $(this);
		// 	// alert($this.attr('height'));
		// 	$this.slimScroll({
		// 		// height: $this.attr('height'),
		// 		railVisible:false
		// 	});
		// });
		
		$("a[data-toggle='tab']").click(function() {
			var active_tab = $(this).attr("href").substring(9);
			var agency = $('#agency_status'+active_tab).val();
			card_number = active_tab;
			//	console.log(active_tab);
			status_pengajuan = $('#status_pengajuan'+active_tab).val() ;
			tgl_pengajuan = $('#tgl_pengajuan'+active_tab).val() ;

			if(status_pengajuan != ''){
				// bootbox.alert({
				// 	message: "Nasabah ini dalam proses pengajuan "+status_pengajuan +" tertanggal " +tgl_pengajuan,
				// 	centerVertical: true,
				// 	backdrop: true
				// });

			}
			$('#account_status [value='+agency+']').prop('selected', true);

			if($('#select_call_status').val() == "CONNECTED") {
				$(".connected_result").slideUp( "fast", function() {
					$("#connected_result_" + active_tab).slideDown("slow");
				});
			}
			$("#txt_hot_note").html($("#hotNotes" + active_tab).val());
			$("#ag_id").html($("#assignedAgent" + active_tab).val());
			$("#txt_agent_notepad_not_connected").html($("#lastNotes" + active_tab).val());
		});
		
		$("#total_outstanding").html($("#total_outstanding_hide").val());
		/*
		$('#payment-history-feed').slimScroll({
			height: '155px',
			alwaysVisible : false
		});

		$('#call-history-feed').slimScroll({
			height: '350px',
			alwaysVisible : false
		});
		
		
		*/
	});
</script>