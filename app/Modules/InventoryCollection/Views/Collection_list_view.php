<div class="row mb-3">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-success" id="btn-export-excel">Export to XLS</button>
            <button type="button" class="btn btn-outline-primary" id="btn-inventory-address">Address</button>
            <button type="button" class="btn btn-outline-warning" id="btn-inventory-email">Email</button>
            <button type="button" class="btn btn-outline-danger" id="btn-inventory-phone">Phone</button>
        </div>
    </div>
</div>
<div class="card">

    <div class="card-header">
        Customer Collection
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridIc" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/inventory_collection/js/inventory_collection.js?v=<?= rand() ?>">
</script>