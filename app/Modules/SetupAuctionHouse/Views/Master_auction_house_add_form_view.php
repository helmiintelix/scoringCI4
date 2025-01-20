<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="txt-nama-balai" class="fs-6 text-capitalize">Auction House Name</label>
                <input type="text" id="txt-nama-balai" name="txt-nama-balai"
                    class="form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="txt-alamat" class="fs-6 text-capitalize">Address</label>
                <!-- <input type="text" id="txt-name-branch" name="name-branch"
                    class="form-control form-control-sm mandatory" required /> -->
                <textarea class="form-control form-control-sm mandatory" name="txt-alamat" id="txt-alamat" cols="30"
                    rows="10" required></textarea>
            </div>
        </div>
    </div>

</form>