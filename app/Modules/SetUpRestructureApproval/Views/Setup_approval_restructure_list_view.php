<div class="row">

	<div class="col-xs-12">
		<div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
			<button type="button" class="btn btn-outline-primary" id="btn-add">Add</button>
			<button type="button" class="btn btn-outline-success" id="btn-edit">Edit</button>
		</div>
	</div>
</div>

<div class="card">

	<div class="card-header">
		Setup Approval
	</div>

	<div class="card-body">
		<div class="col-xs-12" id="parent">
			<div id="myGridSa" style="height: 450px;" class="ag-theme-alpine"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/setup_restructure_approval/js/setup_restructure_approval.js?v=<?= rand() ?>">
</script>