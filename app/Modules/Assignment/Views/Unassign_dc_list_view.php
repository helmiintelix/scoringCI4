<div class="card">
    <div class="card-body">
        <input type="hidden" value="<?= $user_id?>" name="user_id" id="user_id">
        <div class="col-xs-12" id="parent">
            <div id="myGrid" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/assignment/js/unassign_dc_list.js?v=<?= rand() ?>"></script>