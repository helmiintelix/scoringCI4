<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="bucket_id" class="fs-6 text-capitalize">BUCKET ID</label>
                <input type="text" id="bucket_id" name="bucket_id" class="form-control form-control-sm mandatory"
                    required value="<?= $data['bucket_id']; ?>" readonly />
            </div>
            <div class="mb-3 ">
                <label for="bucket_label" class="fs-6 text-capitalize">BUCKET LABEL</label>
                <input type="text" id="bucket_label" name="bucket_label" class="form-control form-control-sm mandatory"
                    required value="<?= $data['bucket_label']; ?>" />
            </div>
            <div class="mb-3 ">
                <label for="product" class="fs-6 text-capitalize">PRODUCT</label>
                <? foreach ($product as $value) {
                    $dataProd[$value["productsubcategory"]] = $value["productsubcategory"];
                }; 
                    $attributes = 'class="chosen-select form-control form-control-sm mandatory" id="product[]" name="product[]"  multiple data-placeholder="-Please Select Product-"';
                    echo form_dropdown('product[]', $dataProd, $dataProduct, $attributes);
                ?>
            </div>
            <div class="mb-3 ">
                <label for="dpd_from" class="fs-6 text-capitalize">DPD FROM</label>
                <input type="text" step="any" id="dpd_from" name="dpd_from"
                    class="form-control form-control-sm mandatory number" required value="<?= $data['dpd_from']; ?>" />
            </div>
            <div class="mb-3 ">
                <label for="dpd_until" class="fs-6 text-capitalize">DPD UNTIL</label>
                <input type="text" step="any" id="dpd_until" name="dpd_until"
                    class="form-control form-control-sm mandatory number" required value="<?= $data['dpd_until']; ?>" />
            </div>
            <div class="mb-3 ">
                <label for="amount_to_be_paid_to_keep_promise" class="fs-6 text-capitalize">Min amount to be paid to
                    keep the
                    promise</label>
                <?php
				$options = array(
                    ""	=> "-PLEASE SELECT-",
                    'MP' => 'MP',
                    'M1'	=> 'M1',
                    'M2'	=> 'M2',
                    'M3'	=> 'M3',
                    'M4'	=> 'M4',
                    'M5'	=> 'M5',
                    'M6'	=> 'M6',
                    'M7'	=> 'M7',
                    'Any Amount'=> 'Any Amount'
                );
				$attributes = 'class="form-control form-control-sm  mandatory" id="amount_to_be_paid_to_keep_promise" name="amount_to_be_paid_to_keep_promise" data-placeholder ="[select]" required';
				echo form_dropdown('amount_to_be_paid_to_keep_promise', $options, $data['amount_to_be_paid_to_keep_promise'], $attributes);
				?>
            </div>
            <div class="mb-3">
                <!-- <label for="any_amount_keep_promise" class="fs-6 text-capitalize"></label> -->
                <input type="text" id="any_amount_keep_promise" step="any" name="any_amount_keep_promise"
                    class="form-control form-control-sm mandatory number" placeholder="Please input amount"
                    value="<?= $data["any_amount_keep_promise"]; ?>" />
            </div>
            <div class="mb-3 ">
                <label for="min_amount_acceptable_promise" class="fs-6 text-capitalize">Min amount acceptable
                    promised</label>
                <?php
				$options = array(
                    ""	=> "-PLEASE SELECT-",
                    'MP' => 'MP',
                    'M1'	=> 'M1',
                    'M2'	=> 'M2',
                    'M3'	=> 'M3',
                    'M4'	=> 'M4',
                    'M5'	=> 'M5',
                    'M6'	=> 'M6',
                    'M7'	=> 'M7',
                    'Any Amount'=> 'Any Amount'
                );
				$attributes = 'class="form-control form-control-sm  mandatory" id="min_amount_acceptable_promise" name="min_amount_acceptable_promise" data-placeholder ="[select]" required';
				echo form_dropdown('min_amount_acceptable_promise', $options, $data['min_amount_acceptable_promise'], $attributes);
				?>
            </div>
            <div class="mb-3">
                <!-- <label for="any_amount_acceptable_promise" class="fs-6 text-capitalize"></label> -->
                <input type="text" id="any_amount_acceptable_promise" step="any" name="any_amount_acceptable_promise"
                    class="form-control form-control-sm mandatory number" placeholder="Please input amount"
                    value="<?=$data["any_amount_acceptable_promise"]?>" />
            </div>
            <div class="mb-3">
                <label for="ptp_grace_period" class="fs-6 text-capitalize">PTP PERIOD</label>
                <input type="text" step="any" id="ptp_grace_period" name="ptp_grace_period"
                    class="form-control form-control-sm mandatory number" required
                    value="<?=$data['ptp_grace_period']?>" />
            </div>
            <div class="mb-3" id="hide">
                <label for="txt-bucket-order" class="fs-6 text-capitalize">INCLUDE HOLIDAY</label>
                <?php
				$options = array(
                    ""	=> "-PLEASE SELECT-",
                    '1' => 'YES',
                    '0'	=> 'NO'
                );
				$attributes = 'class="form-control form-control-sm  mandatory" id="include_holiday" name="include_holiday" data-placeholder ="[select]"';
				echo form_dropdown('include_holiday', $options, '', $attributes);
				?>
            </div>
            <div class="mb-3" id="hide2">
                <label for="join_program" class="fs-6 text-capitalize">Join program</label>
                <?php
				$options = array(
                    ""	=> "-PLEASE SELECT-",
                    '1' => 'YES',
                    '0'	=> 'NO'
                );
				$attributes = 'class="form-control form-control-sm  mandatory" id="join_program" name="join_program" data-placeholder ="[select]"';
				echo form_dropdown('join_program', $options, '', $attributes);
				?>
            </div>
        </div>
    </div>

</form>

<script src="<?= base_url(); ?>modules/bucket/js/script_edit_form.js?v=<?= rand() ?>"></script>