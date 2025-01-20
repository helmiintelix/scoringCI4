<div class="row">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-primary" id="btn-add">Add</button>
            <button type="button" class="btn btn-outline-success" id="btn-edit">Edit</button>
        </div>
    </div>
</div>
<input type="hidden" id="token" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

<div class="card">

    <div class="card-header">
        Parameter Pengajuan Diskon
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridPpd" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/parameter_pengajuan_diskon/js/parameter_pengajuan_diskon.js?v=<?= rand() ?>">
</script>