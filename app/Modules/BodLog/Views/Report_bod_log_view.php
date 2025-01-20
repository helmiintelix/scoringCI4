<div class="card">

    <div class="card-header">
        BOD Log
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridBl" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/bod_log/js/bod_log.js?v=<?= rand() ?>">
</script>