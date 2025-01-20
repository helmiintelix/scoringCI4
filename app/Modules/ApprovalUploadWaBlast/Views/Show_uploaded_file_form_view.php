<form role="form" class="needs-validation" id="form_edit" name="form_edit" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <input type="hidden" name="upload_id" value="<?= $upload_id; ?>" />
    <div class="row">
        <div class="col col-sm-12 table-responsive" id="parent">
            <?= $table; ?>
        </div>
    </div>
</form>