<div class="row">

	<div class="col-xs-12">
		<div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
			<button type="button" class="btn btn-outline-danger" id="btn-del">Delete</button>
		</div>
		<div class="btn-group  btn-group-sm float-end" role="group" aria-label="Basic outlined example">
			<button type="button" class="btn btn-outline-success" id="btn-export-excel">Download Excel</button>
		</div>
	</div>
</div>

<div class="card">

	<div class="card-header">
		History Upload
	</div>

	<div class="card-body">
		<div class="col-xs-12" id="parent">
			<div id="myGrid" style="height: 450px;" class="ag-theme-alpine"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
</script>
<script src="<?= base_url(); ?>modules/HistoryUpload/js/HistoryUpload.js?v=<?= rand() ?>"></script>