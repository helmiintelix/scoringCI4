<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="id" class="fs-6 text-capitalize">ID</label>
                <input type="text" id="id" name="id" class="form-control form-control-sm mandatory" required
                    value="<?= $data['id']; ?>" />
            </div>
            <div class="mb-3 ">
                <label for="reason" class="fs-6 text-capitalize">REASON</label>
                <textarea class="form-control form-control-sm mandatory" name="reason" id="reason" cols="30"
                    rows="5"><?= $data['desc']; ?></textarea>
            </div>
            <div class="mb-3 ">
                <label for="type" class="fs-6 text-capitalize">TYPE</label>
                <?php
				$options = array(
					"PHONE TAGGING"      => "PHONE TAGGING",
                    "ACCOUNT TAGGING"    => "ACCOUNT TAGGING"
				);
				$attributes = 'class="form-control form-control-sm  mandatory" id="type" data-placeholder ="[select]"';
				echo form_dropdown('type', $options, "", $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="form-field-select-2" class="fs-6 text-capitalize">STATUS</label>
                <div class="form-check form-switch">
                    <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
                    <input onChange="isActive(this)" class="form-check-input" type="checkbox" role="switch"
                        id="flexSwitchCheckChecked" <?= $data["status"]=='1'?  'checked' :  ''; ?>>
                </div>
            </div>

            <div class="mb-3 " style="display:none">
                <label for="form-field-select-2" class="fs-6 text-capitalize">Is Active</label>
                <?php
				$options = array(
					'1' => 'ACTIVE',
					'0'	=> 'NOT ACTIVE',
				);
				$attributes = 'class="form-control form-control-sm  mandatory" id="status" data-placeholder ="[select]" required';
				echo form_dropdown('status', $options, '1', $attributes);
				?>
            </div>
        </div>
    </div>

</form>
<script src="<?= base_url(); ?>modules/phone_tagging/js/phone_tagging_ref_edit.js?v=<?= rand() ?>"></script>