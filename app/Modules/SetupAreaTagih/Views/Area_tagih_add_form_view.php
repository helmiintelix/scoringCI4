<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="txt-area_tagih-area_tagih_id" class="fs-6 text-capitalize">AREA TAGIH ID</label>
                <input type="text" id="txt-area_tagih-area_tagih_id" name="txt-area_tagih-area_tagih_id"
                    class="form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="txt-area_tagih-area_tagih_name" class="fs-6 text-capitalize">AREA TAGIH NAME</label>
                <input type="text" id="txt-area_tagih-area_tagih_name" name="txt-area_tagih-area_tagih_name"
                    class="form-control form-control-sm mandatory" required />
            </div>
        </div>
    </div>
</form>