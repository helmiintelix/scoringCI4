<div class="row mb-3">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-success" id="btn-export-excel">Export to XLS</button>
        </div>
    </div>
</div>
<div class="card">

    <div class="card-header">
        Customer WO
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridIw" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/inventory_wo/js/inventory_wo.js?v=<?= rand() ?>">
</script>