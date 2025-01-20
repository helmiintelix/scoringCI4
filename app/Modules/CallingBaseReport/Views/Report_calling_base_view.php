<div class="row">
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            Search Panel
        </div>
        <div class="card-body">
            <!-- Start Date -->
            <div class="row mb-2">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control form-control-sm" placeholder="Start Date" id="start_date" />
                </div>
            </div>

            <!-- End Date -->
            <div class="row mb-2">
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control form-control-sm" placeholder="End Date" id="end_date" />
                </div>
            </div>

            <!-- Product -->
            <div class="row mb-2">
                <div class="col-md-4">
                    <label for="opt-sub-product" class="form-label">Product</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control form-control-sm" id="opt-sub-product">
                        <option value="">[ All ]</option>
                        <option value="PTMKREGR">PTMKREGR</option>
                        <option value="KMG4">KMG4</option>
                    </select>
                </div>
            </div>

            <!-- Search By -->
            <div class="row mb-2">
                <div class="col-md-4">
                    <label for="opt-search-by" class="form-label">Search By</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control form-control-sm" id="opt-search-by">
                        <option value="f.id">Agent ID</option>
                        <option value="f.first_name">Agent Name</option>
                        <option value="a.contract_number">No Kontrak</option>
                    </select>
                </div>
            </div>

            <!-- Keyword -->
            <div class="row mb-2">
                <div class="col-md-4">
                    <label for="txt-keyword" class="form-label">Type your query</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control form-control-sm" placeholder="Type your query" id="txt-keyword" />
                </div>
            </div>

            <!-- Buttons -->
            <div class="row">
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary btn-sm w-100" id="btn-search">
                        Search <i class="bi bi-search"></i>
                    </button>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-success btn-sm w-100" id="btn-export-excel">
                        Save to Excel <i class="bi bi-table"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<div class="card">
    <div class="card-header">
        Calling Base Report
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridCbr" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/calling_base_report/js/calling_base_report.js?v=<?= rand() ?>">
</script>