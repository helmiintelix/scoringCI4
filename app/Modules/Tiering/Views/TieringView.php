<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tiering Settings</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        .profile-info-row {
            display: flex;
            margin-bottom: 20px;
            align-items: center;
        }

        .profile-info-name {
            width: 150px;
            font-weight: bold;
        }

        .profile-info-value {
            flex: 1;
        }

        .mandatory {
            border: 1px solid #ccc;
        }

        .small-text {
            font-size: 0.8rem;
            color: red;
        }

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

<body class="container py-4">
    <form id="frmTieringSettings" class="container">
        <input type="hidden" id="form_mode" name="form_mode"
            value="<?= (!empty($tiering_data['id'])) ? 'EDIT' : 'ADD' ?>">

        <input type="hidden" id="assign-to" name="assign-to" value="" />

        <!-- Tiering ID -->
        <div class="profile-info-row">
            <div class="profile-info-name">Tiering ID</div>
            <div class="profile-info-value">
                <input type="text" id="tiering-id" name="tiering-id" class="form-control mandatory"
                    placeholder="Tiering ID" data-label="Tiering ID"
                    onkeyup="validasi_space(this.value,this.id)" value="<?= @$tiering_data['id'] ?>">
            </div>
        </div>

        <!-- Tiering Label -->
        <div class="profile-info-row">
            <div class="profile-info-name">Tiering Label</div>
            <div class="profile-info-value">
                <input type="text" id="tiering-label" name="tiering-label" class="form-control mandatory"
                    placeholder="Tiering Label" data-label="Tiering Label" value="<?= @$tiering_data['name'] ?>">
            </div>
        </div>

        <!-- Score Type -->
        <div class="profile-info-row">
            <div class="profile-info-name">Score Type</div>
            <div class="profile-info-value">
                <select id="opt_type" name="opt_type" class="form-select mandatory" data-label="Score Type">
                    <option value="">Select Data</option>
                    <option value="score_value" <?= (@$tiering_data['score_type'] == 'score_value') ? 'selected' : '' ?>>Score 1</option>
                    <option value="score_value2" <?= (@$tiering_data['score_type'] == 'score_value2') ? 'selected' : '' ?>>Score 2</option>
                </select>
            </div>
        </div>

        <!-- LOB -->
        <div class="profile-info-row">
            <div class="profile-info-name">LOB</div>
            <div class="profile-info-value">
                <?php
                $options = ['' => 'Select Data'];
                if (!empty($LOB_CODE)) {
                    $options += $LOB_CODE;
                    echo form_dropdown("opt_lob", $options, @$tiering_data['lob'], 'class="form-select mandatory" id="opt_lob" data-label="LOB"');
                } else {
                    echo '<select class="form-select mandatory" id="opt_lob" data-label="LOB"><option value="">Select Data</option><option value="" disabled>No Data Available</option></select>';
                }
                ?>
            </div>
        </div>

        <!-- Bucket -->
        <div class="profile-info-row">
            <div class="profile-info-name">Bucket</div>
            <div class="profile-info-value">
                <?php
                $options = ['' => 'Select Data'];
                if (!empty($BUCKET_SC)) {
                    $options += $BUCKET_SC;
                    echo form_dropdown("opt_bucket", $options, @$tiering_data['bucket'], 'class="form-select mandatory" id="opt_bucket" data-label="Bucket"');
                } else {
                    echo '<select class="form-select mandatory" id="opt_bucket" data-label="Bucket"><option value="">Select Data</option><option value="" disabled>No Data Available</option></select>';
                }
                ?>
            </div>
        </div>

        <!-- Cycle -->
        <div class="profile-info-row">
            <div class="profile-info-name">Cycle</div>
            <div class="profile-info-value">
                <?php
                $options = ['' => 'Select Data'];
                if (!empty($CYCLE_CODE)) {
                    $options += $CYCLE_CODE;
                    echo form_dropdown("opt_cycle", $options, @$tiering_data['cycle'], 'class="form-select mandatory" id="opt_cycle" data-label="Cycle"');
                } else {
                    echo '<select class="form-select mandatory" id="opt_cycle" data-label="Cycle"><option value="">Select Data</option><option value="" disabled>No Data Available</option></select>';
                }
                ?>
            </div>
        </div>

        <!-- Score Tiering -->
        <div class="profile-info-row">
            <div class="profile-info-name">Score Tiering</div>
            <div class="profile-info-value d-flex align-items-center">
                <input type="text" id="score-tiering-start" name="score-tiering-start"
                    class="form-control text-end me-2" onkeypress="return numbersOnly(event, true)"
                    placeholder="0" style="width: 80px;" value="<?= @$tiering_data['score_tiering_start'] ?>">
                <span class="mx-2">to</span>
                <input type="text" id="score-tiering-end" name="score-tiering-end"
                    class="form-control text-end me-2" onkeypress="return numbersOnly(event, true)"
                    placeholder="0" style="width: 80px;" value="<?= @$tiering_data['score_tiering_end'] ?>">
                <span class="small-text">*desimal dengan titik (.)</span>
            </div>
        </div>

        <!-- Total Data -->
        <div class="profile-info-row">
            <div class="profile-info-name">Total Data</div>
            <div class="profile-info-value d-flex align-items-center">
                <input type="hidden" id="total-data-hidden" name="total-data" value="">
                <input type="text" id="total-data" readonly class="form-control text-end me-2" style="width: 80px;">
                <button type="button" id="btn-calculate" class="btn btn-info">Calculate</button>
            </div>
        </div>

        <!-- Buttons -->
        <div class="profile-info-row mt-3">
            <div class="profile-info-name"></div>
            <div class="profile-info-value">
                <button type="button" id="btn-save-form" class="btn btn-primary me-2">Save</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </div>
        </div>
    </form>

    <table id="score_tiering_table" class="table table-striped table-bordered mt-4" style="width:100%">
        <thead>
            <tr>
                <th>Agreement No</th>
                <th>Nama Debitur</th>
                <th>Product</th>
                <th>DPD</th>
                <th>AR Balance</th>
                <th>Tnggk. Cicilan</th>
                <th>Denda</th>
                <th>Penalty</th>
                <th>Total Billing</th>
                <th>Score 1</th>
                <th>Score 2</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        function validasi_space(val, id) {
            if (val.indexOf(' ') >= 0) {
                showWarning("Dilarang menggunakan spasi!");
                document.getElementById(id).value = val.replace(/\s/g, '');
            }
        }

        function numbersOnly(evt, allowDecimal = false) {
            let charCode = evt.which ? evt.which : evt.keyCode;
            if (allowDecimal && charCode === 46) return true;
            return charCode <= 31 || (charCode >= 48 && charCode <= 57);
        }

        function initializeDataTable() {
            console.log('jQuery version:', $.fn.jquery);
            console.log('DataTable loaded:', $.fn.DataTable ? 'yes' : 'no');

            if (!$.fn.DataTable) {
                console.error("DataTables belum loaded, mencoba lagi...");
                return false;
            }

            var table = $('#score_tiering_table').DataTable({
                processing: true,
                serverSide: false,
                data: [],
                columns: [{
                        data: 'no_pinjaman'
                    },
                    {
                        data: 'nama_debitur'
                    },
                    {
                        data: 'product'
                    },
                    {
                        data: 'dpd'
                    },
                    {
                        data: 'ar_balance',
                        className: 'text-end'
                    },
                    {
                        data: 'tunggakan_cicilan',
                        className: 'text-end'
                    },
                    {
                        data: 'denda',
                        className: 'text-end'
                    },
                    {
                        data: 'penalty',
                        className: 'text-end'
                    },
                    {
                        data: 'total_billing',
                        className: 'text-end'
                    },
                    {
                        data: 'score',
                        className: 'text-end'
                    },
                    {
                        data: 'score2',
                        className: 'text-end'
                    }
                ],
                responsive: true,
                paging: true,
                ordering: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [
                    [0, 'asc']
                ],
                drawCallback: function() {
                    let api = this.api(); // Get the DataTables API instance
                    let totalRecords = api.rows().count();
                    $("#total-data").val(totalRecords);
                    $("#total-data-hidden").val(totalRecords);
                }
            });

            return table;
        }

        $(document).ready(function() {
            var maxRetries = 10;
            var retryCount = 0;
            var retryInterval = 100;
            var table = null;

            function tryInitialize() {
                table = initializeDataTable();
                if (table) {
                    console.log('DataTable berhasil diinisialisasi!');
                } else {
                    retryCount++;
                    if (retryCount < maxRetries) {
                        console.log('Retry ' + retryCount + ' dari ' + maxRetries);
                        setTimeout(tryInitialize, retryInterval);
                    } else {
                        console.error('DataTables gagal diload setelah ' + maxRetries + ' percobaan!');
                        alert('Error: DataTables library gagal dimuat. Silakan refresh halaman.');
                    }
                }
            }

            tryInitialize();

            $("#btn-calculate").on('click', function() {
                let start = $("#score-tiering-start").val();
                let end = $("#score-tiering-end").val();

                if (!start || !end) {
                    showWarning("Score tiering tidak boleh kosong!");
                    return false;
                }
                if (parseFloat(start) > parseFloat(end)) {
                    showWarning("Score start tidak boleh lebih besar dari end!");
                    return false;
                }

                const bucketMap = {
                    'CA1 & CA2': {
                        bucket: 'BUCKET 1',
                        oper: 'equivalent'
                    },
                    'CA3': {
                        bucket: 'BUCKET 2',
                        oper: 'equivalent'
                    },
                    'EARLY': {
                        bucket: 'BUCKET 3',
                        oper: 'equivalent'
                    },
                    'MID': {
                        bucket: 'BUCKET 4',
                        oper: 'equivalent'
                    },
                    'NPL': {
                        bucket: 'BUCKET 5|BUCKET 6|BUCKET 7',
                        oper: 'in'
                    },
                    'WO': {
                        bucket: 'REMEDIAL',
                        oper: 'equivalent'
                    }
                };

                const selected = bucketMap[$("#opt_bucket").val()] || {
                    bucket: '',
                    oper: 'equivalent'
                };

                $.ajax({
                    url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/tiering/scoring_result",
                    type: 'POST',
                    data: {
                        score_start: start,
                        score_end: end,
                        score_type: $("#opt_type").val(),
                        lob: $("#opt_lob").val(),
                        cycle: $("#opt_cycle").val(),
                        bucket: selected.bucket,
                        operScoring: selected.oper
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Cek apakah response adalah error
                        if (response && response.type === 'DivisionByZeroError') {
                            showWarning("Error: " + response.message + " - Tidak ada data yang sesuai dengan kriteria.");
                            return;
                        }

                        if (table) {
                            table.clear();
                            if (response && response.length > 0) {
                                table.rows.add(response);
                            } else {
                                showWarning("Tidak ada data yang ditemukan dengan kriteria tersebut.");
                            }
                            table.draw();
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = "Error loading data";

                        // Parse error response
                        try {
                            let errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse.message) {
                                errorMessage = errorResponse.message;
                            }
                        } catch (e) {
                            errorMessage += ": " + error;
                        }

                        showWarning(errorMessage);
                    }
                });
            });

            $("#btn-save-form").on('click', function(e) {
                e.preventDefault();

                let passed = true;
                $(".mandatory").each(function() {
                    let nm = $(this).attr('id') || $(this).attr('name') || '';

                    if (!$(this).val()) {
                        passed = false;
                        let label = $(this).data("label") || $(this).attr("placeholder") || nm;
                        showWarning("Field " + label + " wajib diisi!");
                        $(this).focus();
                        return false;
                    }
                });

                if (!passed) return false;

                $.ajax({
                    url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/tiering/save_tiering/",
                    type: "post",
                    data: $("#frmTieringSettings").serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            showInfo(response.message || "Data berhasil disimpan.");
                            loadMenu("Preview Tiering", "scoring/tieringPreview", "tieringPreview");
                        } else {
                            showWarning(response.message || "Gagal menyimpan data.");
                        }
                    },
                    error: function(xhr, status, error) {
                        showWarning("Terjadi kesalahan: " + error);
                    }
                });
            });
        });
    </script>
</body>

</html>