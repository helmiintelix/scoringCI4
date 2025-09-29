<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cycle Management</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
    </style>
</head>

<body class="container mt-4">
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

    <!-- Modal Add/Edit Cycle -->
    <div class="modal fade" id="modalAddCycle" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" id="modalAddCycleContent"></div>
        </div>
    </div>

    <!-- Modal Delete -->
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

    <script>
        $(document).ready(function() {
            console.log('jQuery version:', $.fn.jquery);
            console.log('DataTable loaded:', $.fn.DataTable ? 'yes' : 'no');

            var table = $('#cycleTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/setting_cycle/setting_cycle_list",
                    type: "POST",
                    data: function(d) {
                        d.search_by = $('#searchBy').val();
                        d.keyword = $('#searchValue').val();
                    }
                },
                columns: [{
                        data: 0,
                        visible: false
                    },
                    {
                        data: 1
                    },
                    {
                        data: 2
                    },
                    {
                        data: 3
                    },
                    {
                        data: 4
                    },
                    {
                        data: 5
                    },
                    {
                        data: 6
                    }
                ],
                columnDefs: [{
                    targets: [2, 3, 4, 5, 6],
                    orderable: false
                }],
                responsive: true,
                paging: true,
                ordering: true,
                order: []
            });

            // Highlight row on click
            $('#cycleTable tbody').on('click', 'tr', function() {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });

            // Filter
            $('#btnFilter').on('click', function() {
                table.ajax.reload();
            });

            // Reset
            $('#btnReset').on('click', function() {
                $('#searchBy').val('cycle_name');
                $('#searchValue').val('');
                table.ajax.reload();
            });

            // Add Cycle
            $('#btn-add').click(function() {
                $('#modalAddCycle').modal('show');
                $('#modalAddCycleContent').load(
                    GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/setting_cycle/cycle_add_form",
                    function() {
                        $('#form_add_cycle').submit(function(e) {
                            e.preventDefault();

                            let from = parseInt($('#txt-cycle-from').val()) || 0;
                            let to = parseInt($('#txt-cycle-to').val()) || 0;

                            if (from > to) {
                                showWarning("Please check your input: From cannot be greater than To");
                                return false;
                            }

                            $.ajax({
                                url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/setting_cycle/save_cycle_add",
                                type: "POST",
                                data: $(this).serialize(),
                                dataType: "json",
                                success: function(resp) {
                                    if (resp.success) {
                                        showInfo("Success add cycle");
                                        $('#modalAddCycle').modal('hide');
                                        table.ajax.reload();
                                    } else {
                                        showWarning("Failed add cycle");
                                    }
                                }
                            });
                        });
                    }
                );
            });

            // Edit Cycle
            $('#btn-edit').click(function() {
                var data = table.row('.selected').data();
                if (data) {
                    $('#modalAddCycle').modal('show');
                    $('#modalAddCycleContent').load(
                        GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/setting_cycle/cycle_edit_form/" + data[0],
                        function() {
                            $('#form_edit_cycle').submit(function(e) {
                                e.preventDefault();
                                $.ajax({
                                    url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/setting_cycle/save_cycle_edit",
                                    type: "POST",
                                    data: $(this).serialize(),
                                    dataType: "json",
                                    success: function(resp) {
                                        if (resp.success) {
                                            showInfo("Success update cycle");
                                            $('#modalAddCycle').modal('hide');
                                            table.ajax.reload();
                                        } else {
                                            showWarning("Failed update cycle");
                                        }
                                    }
                                });
                            });
                        }
                    );
                } else {
                    showWarning('Please select a row first');
                }
            });

            // Delete Cycle
            $('#btn-delete').click(function() {
                var data = table.row('.selected').data();
                if (data) {
                    $('#deleteCycleId').val(data[0]);
                    $('#deleteMessage').text('Are you sure to delete this cycle "' + data[1] + '" ?');
                    $('#modalDeleteCycle').modal('show');
                } else {
                    showWarning('Please select a row first');
                }
            });

            // Confirm Delete
            $('#confirmDelete').click(function() {
                var id_user = $('#deleteCycleId').val();

                $.ajax({
                    url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/setting_cycle/delete_cycle",
                    type: "POST",
                    data: {
                        id_user: id_user
                    },
                    dataType: "json",
                    success: function(resp) {
                        if (resp.success) {
                            showInfo("Success delete cycle");
                            $('#modalDeleteCycle').modal('hide');
                            table.ajax.reload();
                        } else {
                            $('#modalDeleteCycle').modal('hide');
                            showWarning(resp.message || "Failed delete cycle");
                        }
                    },
                    error: function() {
                        showWarning("Error while deleting cycle");
                    }
                });
            });
        });
    </script>
</body>

</html>