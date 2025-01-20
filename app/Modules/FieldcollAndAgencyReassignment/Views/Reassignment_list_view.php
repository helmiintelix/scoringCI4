<div class="row">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-primary" id="btn-assign-to">Assign To</button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridFar" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script
    src="<?= base_url(); ?>modules/fieldcoll_and_agency_reassignment/js/fieldcoll_and_agency_reassignment.js?v=<?= rand() ?>">
</script>