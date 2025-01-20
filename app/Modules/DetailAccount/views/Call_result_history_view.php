<div class="col-sm-6 mb-3">
	<div class="card">
		<div class="card-body">
			<form>
				<div class="mb-3">
					<label for="opt-source" class="form-label">SOURCE</label>
					<select name="opt-source" class="form-select" id="opt-source">
						<option value="PHONE">CALL</option>
						<option value="MOBCOLL">FIELDCOLL</option>
						<option value="VISIT">AGENCY</option>
						<option value="SMS">SMS</option>
						<option value="EMAIL">EMAIL</option>
						<option value="BR">BUSINESS RULE</option>
					</select>
				</div>
				<div class="d-grid gap-2">
					<button type="button" class="btn btn-success" id="btn-search">SEARCH <i
							class="bi bi-table"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="card">
	<div class="card-body" id="call_history" style="display: block;">
		<div class="col-xs-12" id="parent">
			<div id="myGridCh" style="height: 450px;" class="ag-theme-alpine"></div>
		</div>
	</div>
</div>
<div class="card">
	<div class="card-body" id="sms_history" style="display: none;">
		<div class="col-xs-12" id="parent">
			<div id="myGridSh" style="height: 450px;" class="ag-theme-alpine"></div>
		</div>
	</div>
</div>
<div class="card">
	<div class="card-body" id="email_history" style="display: none;">
		<div class="col-xs-12" id="parent">
			<div id="myGridEh" style="height: 450px;" class="ag-theme-alpine"></div>
		</div>
	</div>
</div>
<div class="card">
	<div class="card-body" id="agency_history" style="display: none;">
		<div class="col-xs-12" id="parent">
			<div id="myGridAh" style="height: 450px;" class="ag-theme-alpine"></div>
		</div>
	</div>
</div>
<div class="card">
	<div class="card-body" id="br_history" style="display: none;">
		<div class="col-xs-12" id="parent">
			<div id="myGridBh" style="height: 450px;" class="ag-theme-alpine"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var currCustId = '<?=$customer_id;?>';
	var currCardNo = '<?=$card_no;?>';
</script>
<script src="<?= base_url(); ?>modules/detail_account/js/call_result_history.js?v=<?= rand() ?>">
</script>