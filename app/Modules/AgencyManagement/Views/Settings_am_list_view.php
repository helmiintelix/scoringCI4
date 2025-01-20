<div class="row">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-primary" id="btn-add">Add</button>
            <button type="button" class="btn btn-outline-success" id="btn-edit">Edit</button>
            <!-- <button type="button" class="btn btn-outline-danger" id="btn-del">Delete</button> -->
            <!-- <button type="button" class="btn btn-outline-dark" id="btn-force-logout">Force Logout</button> -->
        </div>
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-success" id="btn-export-excel">Download Excel</button>
        </div>
    </div>
</div>

<div class="card">

    <div class="card-header">
        AGENCY MANAGEMENT
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridAm" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Waiting Approval
    </div>
    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridApproval" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/agency_management/js/agency_management.js?v=<?= rand() ?>"></script>