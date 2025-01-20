<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="subject" class="fs-6 text-capitalize">Subject</label>
                <input type="text" id="subject" name="subject" class="form-control form-control-sm mandatory"
                    required />
            </div>
            <div class="mb-3 ">
                <label for="script_content" class="fs-6 text-capitalize">Script</label>
                <textarea class="form-control form-control-sm mandatory" name="script_content" id="script_content"
                    cols="30" rows="10" required mandatory></textarea>
            </div>
        </div>
    </div>
</form>