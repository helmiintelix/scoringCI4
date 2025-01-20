<div class="row">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-primary" id="btn-unassigned-add">Assign Area</button>
            <button type="button" class="btn btn-outline-success" id="btn-unassigned-print">Print</button>
            <!-- <button type="button" class="btn btn-outline-danger" id="btn-del">Delete</button> -->
            <!-- <button type="button" class="btn btn-outline-dark" id="btn-force-logout">Force Logout</button> -->
        </div>
        <!-- <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-success" id="btn-export-excel">Download Excel</button>
        </div> -->
    </div>
</div>

<div class="card">

    <div class="card-header">
        ZIPCODE
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridUz" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/unassigned_zipcode/js/unassigned_zipcode.js?v=<?= rand() ?>"></script>