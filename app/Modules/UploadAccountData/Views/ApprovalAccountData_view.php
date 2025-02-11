<div class="row">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-secondary" id="btn-view">View Data</button>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
       Approval Account Data
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridLp" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>

<script src="<?= base_url(); ?>modules/upload_account_data/js/approval_account_data.js?v=<?= rand() ?>">
</script>