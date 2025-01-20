<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <input type="hidden" name="txt-id" value="<?= $data['bidder_id']; ?>">
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="txt-id-card" class="fs-6 text-capitalize">ID Card No.</label>
                <input type="text" id="txt-id-card" name="txt-id-card" class="form-control form-control-sm mandatory"
                    required value="<?= $data['id_card']; ?>" />
            </div>
            <div class="mb-3 ">
                <label for="txt-nama-bidder" class="fs-6 text-capitalize">Bidder Name</label>
                <input type="text" id="txt-nama-bidder" name="txt-nama-bidder"
                    class="form-control form-control-sm mandatory" required value="<?= $data['name']; ?>" />
            </div>
            <div class="mb-3 ">
                <label for="txt-phone-1" class="fs-6 text-capitalize">Phone No.</label>
                <input type="text" id="txt-phone-1" name="txt-phone-1" class="form-control form-control-sm mandatory"
                    required value="<?= $data['phone_1']; ?>" />
            </div>
            <div class="mb-3 ">
                <label for="txt-alamat-bidder" class="fs-6 text-capitalize">Address</label>
                <textarea class="form-control form-control-sm mandatory" name="txt-alamat-bidder" id="txt-alamat-bidder"
                    cols="30" rows="10" required><?= $data['address']; ?></textarea>
            </div>
            <div class="mb-3 ">
                <label for="opt-area" class="fs-6 text-capitalize">Area</label>
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-area" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-area', $area, $data['area_id'], $attributes);
                ?>
            </div>
            <div class="mb-3 ">
                <label for="opt-cabang" class="fs-6 text-capitalize">Address</label>
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="opt-cabang" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-cabang', $cabang,  $data['branch_id'], $attributes);
                ?>
            </div>
            <div class="mb-3 ">
                <label for="flexSwitchCheckChecked" class="fs-6 text-capitalize">Status</label>
                <div class="form-check form-switch">
                    <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
                    <input onChange="isActive(this)" class="form-check-input" type="checkbox" role="switch"
                        id="flexSwitchCheckChecked" <?= $data['is_active'] == '1' ? 'checked' : ''; ?>>
                </div>
            </div>

            <div class="mb-3 " style="display:none">
                <label for="opt-active-flag" class="fs-6 text-capitalize">Is Active</label>
                <?php
				$options = array(
					'1' => 'ENABLE',
					'0'	=> 'DISABLE',
				);
				$attributes = 'class="form-control form-control-sm  mandatory" id="opt-active-flag" data-placeholder ="[select]" required';
				echo form_dropdown('opt-active-flag', $options, '1', $attributes);
				?>
            </div>
        </div>
    </div>

</form>
<script type="text/javascript">
    function isActive(elm) {
        if ($(elm)[0].checked) {
            $("#opt-active-flag").val('1').change();
            //$("label[for='flexSwitchCheckChecked']").text('Active');
        } else {
            $("#opt-active-flag").val('0').change();
            //$("label[for='flexSwitchCheckChecked']").text('Not Active');
        }
    }
</script>