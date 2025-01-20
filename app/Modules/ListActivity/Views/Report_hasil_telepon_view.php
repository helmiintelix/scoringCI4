<div class="col-sm-6 mb-3">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">SEARCH CRITERIA</h5>
        </div>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="date-range-picker" class="form-label">TANGGAL</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi-calendar"></i>
                        </span>
                        <input class="form-control" type="text" name="date-range-picker" id="date-range-picker" />
                    </div>
                </div>

                <div class="mb-3">
                    <label for="opt-petugas" class="form-label">PETUGAS</label>
                    <?
                        $attributes = 'class="form-select" id="opt-petugas"';
                        echo form_dropdown('opt-petugas', $petugas, "", $attributes);
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
<div class="row mb-3">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-success" id="btn-export-excel">Export to XLS</button>
            <!-- <button type="button" class="btn btn-outline-warning" id="btn-export-rtf">Export to RTF</button>
            <button type="button" class="btn btn-outline-danger" id="btn-export-csv">Export to CSV</button> -->
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        List Activity
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridLa" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/list_activity/js/list_activity.js?v=<?= rand() ?>">
</script>