<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="txt-holiday-name" class="fs-6 text-capitalize">HOLIDAY NAME</label>
                <input type="text" id="txt-holiday-name" name="txt-holiday-name"
                    class="form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="txt-holiday-date" class="fs-6 text-capitalize">HOLIDAY DATE</label>
                <input type="text" id="txt-holiday-date" name="txt-holiday-date"
                    class="form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="txt-remark" class="fs-6 text-capitalize">REMARK</label>
                <input type="text" id="txt-remark" name="txt-remark" class="form-control form-control-sm mandatory"
                    required />
            </div>
        </div>
    </div>

</form>
<script src="<?= base_url(); ?>modules/holiday/js/script_add_form.js?v=<?= rand() ?>"></script>