<div class="row">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-primary" id="btn-add">Add</button>
            <button type="button" class="btn btn-outline-success" id="btn-edit">Edit</button>
            <button type="button" class="btn btn-outline-warning" id="btn-change">Aktivasi</button>
            <!-- <button type="button" class="btn btn-outline-dark" id="btn-force-logout">Force Logout</button> -->
        </div>
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-success" id="btn-export-excel">Download Excel</button>
        </div>
    </div>
</div>
<input type="hidden" id="token" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

<div class="card">

    <div class="card-header">
        Master Auction House
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridMah" style="height: 450px;" class="ag-theme-alpine"></div>
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
</script>
<script src="<?= base_url(); ?>modules/setup_auction_house/js/master_auction_house.js?v=<?= rand() ?>"></script>