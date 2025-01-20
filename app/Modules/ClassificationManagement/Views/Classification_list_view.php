<div class="row">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-primary" id="btn-add">Add</button>
            <button type="button" class="btn btn-outline-success" id="btn-edit">Edit</button>
            <button type="button" class="btn btn-outline-danger" id="btn-disable">Disable</button>
            <button type="button" class="btn btn-outline-secondary" id="btn-test-data">Test Data</button>
            <button type="button" class="btn btn-outline-warning" id="btn-apply-class">Apply Class</button>
        </div>
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-secondary" id="btn-export-campaign">Download Campaign</button>
            <button type="button" class="btn btn-outline-secondary" id="btn-export-excel">Download Excel</button>
        </div>
    </div>
</div>

<input type="hidden" id="token" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

<div class="card">

    <div class="card-header">
        Class Management
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridCm" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/class_management/js/class_management.js?v=<?= rand() ?>"></script>