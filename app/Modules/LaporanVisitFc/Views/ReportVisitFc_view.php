<div class="col-sm-6 mb-3">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">SEARCH CRITERIA</h5>
        </div>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="loan_number" class="form-label">LOAN NUMBER</label>
                    <input class="form-control" type="text" name="loan_number" id="loan_number" />
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
        Report Visit Fieldcoll
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridLp" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/laporan_visit_fc/js/laporan_visit_fc.js?v=<?= rand() ?>">
</script>