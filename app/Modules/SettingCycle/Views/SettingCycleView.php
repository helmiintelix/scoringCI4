<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cycle Management</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
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
            <input type="text" id="searchValue" class="form-control" placeholder="Type your query here" style="max-width: 250px;">
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

    <!-- jQuery + Bootstrap + DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#cycleTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "scoring/setting_cycle/setting_cycle_list/",
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
                $('#searchBy').val('');
                $('#searchValue').val('');
                table.ajax.reload();
            });

            // CRUD actions
            $('#btn-add').click(function() {
                alert('Add cycle form here...');
            });

            $('#btn-edit').click(function() {
                var data = table.row('.selected').data();
                if (data) {
                    alert('Edit cycle with ID: ' + data[0]);
                } else {
                    alert('Please select a row first');
                }
            });

            $('#btn-delete').click(function() {
                var data = table.row('.selected').data();
                if (data) {
                    if (confirm('Delete cycle ID: ' + data[0] + ' ?')) {
                        table.row('.selected').remove().draw(false);
                    }
                } else {
                    alert('Please select a row first');
                }
            });
        });
    </script>
</body>

</html>