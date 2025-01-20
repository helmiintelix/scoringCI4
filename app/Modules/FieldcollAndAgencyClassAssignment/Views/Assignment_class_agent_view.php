<div class="row">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-primary" id="btn-assign">Assign to Agent</button>
            <button type="button" class="btn btn-outline-success" id="btn-view">View Account</button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridFaca" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script
    src="<?= base_url(); ?>modules/fieldcoll_and_agency_class_assignment/js/fieldcoll_and_agency_class_assignment.js?v=<?= rand() ?>">
</script>