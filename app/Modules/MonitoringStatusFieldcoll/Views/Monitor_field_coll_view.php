<div class="card">

    <div class="card-header">
        Monitor Status Fieldcoll
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridMsf" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/monitoring_status_fieldcoll/js/monitoring_status_fieldcoll.js?v=<?= rand() ?>">
</script>