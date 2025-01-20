<form role="form" class="needs-validation" id="form_edit" name="form_edit" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

    <div class="row">
        <div class="col col-sm-12 table-responsive" id="parent">
            <?= $table; ?>
        </div>
    </div>
</form>