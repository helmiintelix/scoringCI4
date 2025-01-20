<div class="col-sm-6 mb-3">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">SEARCH CRITERIA</h5>
        </div>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="date-range-picker" class="form-label">SELECT DATE</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi-calendar"></i>
                        </span>
                        <input class="form-control" type="text" name="date-range-picker" id="date-range-picker" />
                    </div>
                </div>
                <div class="mb-3">
                    <label for="txt-no-pinjaman" class="form-label">No. Pinjaman</label>
                    <input class="form-control" type="text" name="txt-no-pinjaman" id="txt-no-pinjaman" />
                </div>

                <div class="mb-3">
                    <label for="opt-product" class="form-label">PRODUCT</label>
                    <?
                        foreach ($product as $value) {
                            $data[$value["productsubcategory"]] = $value["productsubcategory"];
                        }
                        $attributes = 'class="form-select" id="opt-product"';
                        echo form_dropdown('opt-product', $data, "", $attributes);
                    ?>
                </div>
                <div class="mb-3">
                    <label for="opt-bucket" class="form-label">BUCKET</label>
                    <?
                        $attributes = 'class="form-select" id="opt-bucket"';
                        echo form_dropdown('opt-bucket', $bucket, "", $attributes);
                    ?>
                </div>
                <div class="mb-3">
                    <label for="opt-user" class="form-label">SELECT FC</label>
                    <?
                        $attributes = 'class="form-select" id="opt-user"';
                        echo form_dropdown('opt-user', $user, "", $attributes);
                    ?>
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
<div class="card">
    <div class="card-header">
        Laporan Visit Field Collection
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridLvfc" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script
    src="<?= base_url(); ?>modules/laporan_visit_field_collection/js/laporan_visit_field_collection.js?v=<?= rand() ?>">
</script>