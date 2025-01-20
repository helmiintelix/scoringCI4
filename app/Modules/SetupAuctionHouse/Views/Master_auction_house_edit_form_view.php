<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <input type="hidden" name="txt-balai-id" value="<?= $data['balai_id']; ?>">
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="txt-nama-balai" class="fs-6 text-capitalize">Auction House Name</label>
                <input type="text" id="txt-nama-balai" name="txt-nama-balai"
                    class="form-control form-control-sm mandatory" required value="<?= $data['name']; ?>" />
            </div>
            <div class="mb-3 ">
                <label for="txt-alamat" class="fs-6 text-capitalize">Address</label>
                <!-- <input type="text" id="txt-name-branch" name="name-branch"
                    class="form-control form-control-sm mandatory" required /> -->
                <textarea class="form-control form-control-sm mandatory" name="txt-alamat" id="txt-alamat" cols="30"
                    rows="10" required><?= $data['address']; ?></textarea>
            </div>
            <div class="mb-3 ">
                <label for="flexSwitchCheckChecked" class="fs-6 text-capitalize">Status</label>
                <div class="form-check form-switch">
                    <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
                    <input onChange="isActive(this)" class="form-check-input" type="checkbox" role="switch"
                        id="flexSwitchCheckChecked" <?= $is_active == '1' ? 'checked' : ''; ?>>
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