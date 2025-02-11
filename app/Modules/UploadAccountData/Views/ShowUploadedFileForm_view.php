<div class="card">
    <div class="card-body">
        <input type="text" id="uploadId" value="<?= $id?>" hidden>
        <div class="col-xs-12" id="parent">
            <div id="myGridLpShow" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>

<script src="<?= base_url(); ?>modules/upload_account_data/js/show_upload_data.js?v=<?= rand() ?>">
</script>