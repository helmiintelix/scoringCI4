<div class="card">
    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridCtfv" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
    var classification_id = '<?=$classification_id ?>';
</script>
<script
    src="<?= base_url(); ?>modules/fieldcoll_and_agency_class_assignment/js/classification_test_form.js?v=<?= rand() ?>">
</script>