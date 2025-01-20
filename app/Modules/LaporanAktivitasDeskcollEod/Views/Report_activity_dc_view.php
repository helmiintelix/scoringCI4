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
                    <label for="agent" class="form-label">SELECT DC</label>
                    <?
                        $attributes = 'class="form-select" id="agent"';
                        echo form_dropdown('agent', $petugas, "", $attributes);
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
        Laporan Aktifitas Agent (eod)
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridLaa" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script
    src="<?= base_url(); ?>modules/laporan_aktivitas_deskcoll_eod/js/laporan_aktivitas_deskcoll_eod.js?v=<?= rand() ?>">
</script>