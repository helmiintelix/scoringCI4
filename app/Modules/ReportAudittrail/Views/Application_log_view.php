<div class="card">

    <div class="card-header">
        Report Auditrail
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridRa" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/report_audittrail/js/report_audittrail.js?v=<?= rand() ?>">
</script>