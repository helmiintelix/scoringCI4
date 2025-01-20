<div class="row">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-warning" id="btn-approve">APPROVE</button>
            <button type="button" class="btn btn-outline-danger" id="btn-reject">REJECT</button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridFaar" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script
    src="<?= base_url(); ?>modules/fieldcoll_and_agency_approval_reassignment/js/fieldcoll_and_agency_approval_reassignment.js?v=<?= rand() ?>">
</script>