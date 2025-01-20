<div class="row">
    <div class="col-lg-12">
        <input type="hidden" id="csrf_token" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <textarea class="form-control mb-3" id="broadcastMsg" rows="4" cols="80"
            maxlength="255"><?=$broadcastMsg?></textarea>
        <button type="button" class="btn btn-success" id="saveMsg">save</button>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/setup_broadcast_message/js/setup_broadcast_message.js?v=<?= rand() ?>"></script>