<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate enctype="multipart/form-data">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="userfile" class="fs-6 text-capitalize">ACTIVITY FILE</label>
                <input type="file" id="file" name="file" class="form-control form-control-sm mandatory" required
                    accept=".xls, .xlsx" />
            </div>
        </div>
    </div>
</form>