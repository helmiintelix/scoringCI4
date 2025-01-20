<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <input type="hidden" name="id" id="id" value="<?= $data['id']; ?>">
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="status" class="fs-6 text-capitalize">Status</label>
                <?php
				$options = array(
					"approved"  => "APPROVED",
                    "reject"    => "REJECT",
                    "release"    => "RELEASE"
				);
				$attributes = 'class="form-control form-control-sm  mandatory" id="status" data-placeholder ="[select]"';
				echo form_dropdown('status', $options, $data['status'], $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="reason" class="fs-6 text-capitalize">REMARK</label>
                <textarea class="form-control form-control-sm mandatory" name="reason" id="reason" cols="30"
                    rows="5"></textarea>
            </div>
        </div>
    </div>

</form>