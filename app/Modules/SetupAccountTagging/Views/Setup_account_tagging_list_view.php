<div class="row">
    <form role="form" class="needs-validation" id="form_set_account_tagging" name="form_set_account_tagging" novalidate>
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
        <div class="row">
            <div class="col col-sm-12">
                <div class="mb-3 ">
                    <label for="opt-account-tagging" class="fs-6 text-capitalize">Set Account Tagging</label>
                    <?php
                        $attributes = 'class="form-control form-control-sm mandatory" id="opt-account-tagging"';
                        echo form_dropdown('opt-account-tagging', $account_tagging, '', $attributes);
                    ?>
                </div>
            </div>
        </div>
        <button class="mb-3 btn tbn-sm btn-primary" id="btn-set-account-tagging">Set Account Tagging</button>
        <button class="mb-3 btn tbn-sm btn-warning" id="btn-del-account-tagging">Remove Account Tagging</button>
    </form>
</div>

<div class="card">

    <div class="card-header">
        Setup Account Tagging Maker
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridSatm" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var classification = '<?= $classification ?>';
    var rules_new;
</script>
<script src="<?= base_url(); ?>modules/setup_account_tagging/js/setup_account_tagging.js?v=<?= rand() ?>"></script>