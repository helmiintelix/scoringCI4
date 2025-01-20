<div class="col-sm-6 mb-3">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">SEARCH CRITERIA</h5>
        </div>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <div class="mb-3">
                        <label for="opt-report-type" class="fs-6 text-capitalize">TYPE</label>
                        <?
                            $options = array(
                                'Email' => 'EMAIL',
                                'Wa' => 'WA',
                                'Sms' => 'SMS'
                            );
                            $attributes = 'class="form-control form-control-sm mandatory" id="opt-report-type"';
                            echo form_dropdown('opt-report-type', $options, "Email", $attributes);
                        ?>
                    </div>
                    <div class="mb-3">
                        <label for="date-range-picker" class="fs-6 text-capitalize">TANGGAL</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi-calendar"></i>
                            </span>
                            <input class="form-control form-control-sm mandatory" type="text" name="date-range-picker"
                                id="date-range-picker" />
                        </div>
                    </div>
                    <div class="mb-3 ">
                        <label for="opt-product-report" class="fs-6 text-capitalize">PRODUCT</label>
                        <div class="row">
                            <?php
                                $attributes = 'class="col-sm-12 chosen-select form-control form-control-sm mandatory" id="opt-product-report" name="opt-product-report[]"  multiple data-placeholder="-Please Select Product-"';
                                echo form_dropdown('opt-product-report[]', $product,  "", $attributes);
                            ?>

                        </div>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success" id="btn-search">SEARCH <i
                            class="bi bi-table"></i></button>
                    <button type="reset" class="btn btn-secondary" id="btn-reset">CLEAR <i
                            class="bi bi-x-circle"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row mb-3">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-success" id="btn-export-excel">Export to XLS</button>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        Report Generate Email,WA,SMS
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridRgews" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/report_generate_email_wa_sms/js/report_generate_email_wa_sms.js?v=<?= rand() ?>">
</script>