<div class="row">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-primary" id="btn-upload">Upload File</button>
            <button type="button" class="btn btn-outline-secondary" id="btn-view">View Data</button>
            <!-- <button type="button" class="btn btn-outline-success" id="btn-download">Download Format</button> -->
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridAcu" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var classification = '<?= $classification ?>';
    var basePath = '<?= base_url() ?>'
</script>
<script src="<?= base_url(); ?>modules/upload_wa_blast/js/upload_wa_blast.js?v=<?= rand() ?>"></script>