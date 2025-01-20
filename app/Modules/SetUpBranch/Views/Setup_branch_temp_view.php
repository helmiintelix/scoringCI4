<div class="row">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-warning" id="btn-approve">APPROVE</button>
            <button type="button" class="btn btn-outline-danger" id="btn-reject">REJECT</button>
            <!-- <button type="button" class="btn btn-outline-dark" id="btn-force-logout">Force Logout</button> -->
        </div>
        <!-- <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-success" id="btn-export-excel">Download Excel</button>
        </div> -->
    </div>
</div>

<div class="card">

    <div class="card-header">
        Set Up Branch Approval
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridTemp" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>

<!-- <div class="card">
	<div class="card-header">
		User Management Approval
	</div>
	<div class="card-body">
		<div class="col-xs-12" id="parent">
			<div id="myGridApproval" style="height: 450px;" class="ag-theme-alpine"></div>
		</div>
	</div>
</div> -->
<script type="text/javascript">
    var classification = '<?= $classification ?>';
    var csrf = {
        name: '<?= csrf_token() ?>',
        value: '<?= csrf_hash() ?>'
    };
</script>
<script src="<?= base_url(); ?>modules/set_up_branch/js/set_up_branch_temp.js?v=<?= rand() ?>"></script>