<div class="row">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-warning" id="btn-print">Print</button>
            <button type="button" class="btn btn-outline-secondary" id="btn-preview">Preview</button>
        </div>
    </div>
</div>
<input type="hidden" id="token" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

<div class="card">

    <div class="card-header">
        Surat Peringatan
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridSp" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/surat_peringatan/js/surat_peringatan.js?v=<?= rand() ?>">
</script>