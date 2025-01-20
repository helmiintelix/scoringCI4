<div class="row">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-warning" id="btn-approve">APPROVE</button>
            <button type="button" class="btn btn-outline-danger" id="btn-reject">REJECT</button>
        </div>
    </div>
</div>

<div class="card">

    <div class="card-header">
        Set Up Deviation Approval Approval
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridTemp" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/setup_deviation_approval/js/setup_deviation_approval_temp.js?v=<?= rand() ?>">
</script>