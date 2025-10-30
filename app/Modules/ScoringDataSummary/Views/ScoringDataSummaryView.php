<div class="row">

	<div class="col-xs-12">
		<div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
			<button type="button" class="btn btn-outline-success" id="btn-export-excel">Download Excel</button>
		</div>
	</div>
</div>

<div class="card">

	<div class="card-header">
		Summary Data
	</div>

	<div class="card-body">
		<div class="col-xs-12" id="parent">
			<div id="myGrid" style="height: 450px;" class="ag-theme-alpine"></div>
		</div>
	</div>
</div>

<script src="<?= base_url(); ?>modules/ScoringDataSummary/js/ScoringDataSummary.js?v=<?= rand() ?>"></script>
