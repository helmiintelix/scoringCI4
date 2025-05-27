<div class="col-sm-6 mb-3">
	<div class="card">
		<div class="card-body">
			<form>
				<div class="mb-3">
					<label for="opt-source" class="form-label">SOURCE</label>
					<select name="opt-source" class="form-select" id="opt-source">
						<option>--PILIH DATA--</option>
						<option value="SURAT_PERINGATAN">SURAT PERINGATAN</option>
						<option value="SURAT_PENARIKAN">SURAT PENARIKAN (RAL)</option>
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
	<div class="card-body">
		<div class="col-xs-12" id="parent">
			<div id="myGrid" style="height: 450px;" class="ag-theme-alpine"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var currCustId = '<?=$customer_id;?>';
	var currCardNo = '<?=$card_no;?>';
</script>
<script src="<?= base_url(); ?>modules/detail_account/js/action_code_history.js?v=<?= rand() ?>">
</script>