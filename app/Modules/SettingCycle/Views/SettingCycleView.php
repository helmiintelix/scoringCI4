<!-- Bootstrap CSS-->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
    .dataTables_wrapper .dataTables_filter {
        display: none !important;
    }

    .table thead th {
        background-color: #004085;
        color: #fff;
        text-align: center;
    }

    tr.selected {
        background-color: #b8daff !important;
    }

    .container {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
</style>

<div class="container">
    <div class="row mb-3 align-items-center">
        <div class="col-auto">
            <button class="btn btn-success" id="btn-add">Add</button>
            <button class="btn btn-warning" id="btn-edit">Edit</button>
            <button class="btn btn-danger" id="btn-delete">Delete</button>
        </div>
        <div class="col d-flex justify-content-end gap-2">
            <select id="searchBy" class="form-select" style="max-width: 150px;">
                <option value="cycle_name">Cycle</option>
                <option value="cycle_from">From</option>
                <option value="cycle_to">To</option>
            </select>
            <input type="text" id="searchValue" class="form-control" placeholder="Type your query here"
                style="max-width: 250px;">
            <button class="btn btn-secondary" id="btnFilter">Search</button>
            <button class="btn btn-outline-secondary" id="btnReset">Reset</button>
        </div>
    </div>

    <table id="cycleTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cycle Ke</th>
                <th>From</th>
                <th>To</th>
                <th>Active Status</th>
                <th>Update Time</th>
                <th>Update By</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<div class="modal fade" id="modalAddCycle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" id="modalAddCycleContent"></div>
    </div>
</div>

<div class="modal fade" id="modalDeleteCycle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="deleteMessage">Are you sure to delete this cycle?</p>
                <input type="hidden" id="deleteCycleId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery, Bootstrap JS, DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="<?= base_url(); ?>modules/SettingCycle/js/SettingCycle.js?v=<?= rand() ?>"></script>