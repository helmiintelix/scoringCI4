<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="txt-id-card" class="fs-6 text-capitalize">ID Card No.</label>
                <input type="text" id="txt-id-card" name="txt-id-card" class="form-control form-control-sm mandatory"
                    required />
            </div>
            <div class="mb-3 ">
                <label for="txt-nama-bidder" class="fs-6 text-capitalize">Bidder Name</label>
                <input type="text" id="txt-nama-bidder" name="txt-nama-bidder"
                    class="form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="txt-phone-1" class="fs-6 text-capitalize">Phone No.</label>
                <input type="text" id="txt-phone-1" name="txt-phone-1" class="form-control form-control-sm mandatory"
                    required />
            </div>
            <div class="mb-3 ">
                <label for="txt-alamat-bidder" class="fs-6 text-capitalize">Address</label>
                <textarea class="form-control form-control-sm mandatory" name="txt-alamat-bidder" id="txt-alamat-bidder"
                    cols="30" rows="10" required></textarea>
            </div>
            <div class="mb-3 ">
                <label for="opt-area" class="fs-6 text-capitalize">Area</label>
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-area" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-area', $area, "", $attributes);
                ?>
            </div>
            <div class="mb-3 ">
                <label for="opt-cabang" class="fs-6 text-capitalize">Address</label>
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-cabang" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-cabang', $cabang,  "", $attributes);
                ?>
            </div>
        </div>
    </div>

</form>
<script type="text/javascript">

</script>