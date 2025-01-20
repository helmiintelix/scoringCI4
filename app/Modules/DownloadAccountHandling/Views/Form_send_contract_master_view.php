<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <input type="hidden" name="txt-cd-customers" id="txt-cd-customers" value="<?= $cd_customers; ?>">
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="opt-cd-customer" class="fs-6 text-capitalize">Customer No</label>
                <?php
				$attributes = 'class="form-control form-control-sm  mandatory" id="opt-cd-customer" data-placeholder ="[select]"';
				echo form_dropdown('opt-cd-customer', $cd_customer_list, '', $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="opt_users" class="fs-6 text-capitalize">Select User
                </label>
                <?php
				$attributes = 'class="chosen-select form-control form-control-sm  mandatory" id="opt_users" data-placeholder ="[select]" multiple';
				echo form_dropdown('opt_users[]', $sendto, '', $attributes);
				?>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    function first_load() {
        $(".chosen-select").chosen();
    }

    setTimeout(first_load, 300);
</script>