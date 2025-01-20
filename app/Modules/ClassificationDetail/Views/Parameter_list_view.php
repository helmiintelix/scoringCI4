<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="opt-param" class="fs-6 text-capitalize">SELECT PARAMETER</label>
                <div class="col-sm-9">
                    <?php
                        $attributes = 'class="col-xs-10 col-sm-2" id="opt-param" multiple';
                        echo form_dropdown('opt-param', $param_list , '', $attributes);
                        ?>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="txt-field-name" name="txt-field-name" value="<?=$field_name ?>">


</form>
<script src="<?= base_url(); ?>modules/classification_detail/js/classification_detail.js?v=<?= rand() ?>"></script>

<!-- <script type="text/javascript">
    jQuery(function ($) {
        $('#opt-param').chosen({
            "width": "400px",
            "search_contains": "true"
        });
        $('#opt-param').val($('[name=<?=$field_name ?>]').val().split(","));
        $("#opt-param").trigger('chosen:updated');
    });
</script> -->