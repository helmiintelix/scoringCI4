<div class="row">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-primary" id="btn-upload">Upload File</button>
            <button type="button" class="btn btn-outline-secondary" id="btn-view">View Data</button>
            <button type="button" class="btn btn-outline-success" id="btn-download">Download Format</button>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
       Upload Account Data
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridLp" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>

<script src="<?= base_url(); ?>modules/upload_account_data/js/upload_account_data.js?v=<?= rand() ?>">
</script>