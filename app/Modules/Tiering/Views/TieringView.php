<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tiering Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/free-jqgrid@4.15.5/css/ui.jqgrid.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/free-jqgrid@4.15.5/js/jquery.jqgrid.min.js"></script>
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

    <table id="score_tiering_table" class="table table-bordered mt-4"></table>
    <div id="score_tiering_pager"></div>

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

        $(document).ready(function() {
            if (typeof $.fn.jqGrid === 'undefined') {
                console.error('jqGrid is not loaded!');
                showWarning('jqGrid library failed to load. Please check your internet connection.');
                return;
            }

            $("#score_tiering_table").jqGrid({
                datatype: 'local',
                data: [],
                width: 1120,
                height: 350,
                colNames: ['Agreement No', 'Nama Debitur', 'Product', 'DPD', 'AR Balance', 'Tnggk. Cicilan', 'Denda', 'Penalty', 'Total Billing', 'Score 1', 'Score 2'],
                colModel: [{
                        name: 'no_pinjaman',
                        index: 'no_pinjaman',
                        width: 120
                    },
                    {
                        name: 'nama_debitur',
                        index: 'nama_debitur',
                        width: 200
                    },
                    {
                        name: 'product',
                        index: 'product',
                        width: 120
                    },
                    {
                        name: 'dpd',
                        index: 'dpd',
                        width: 100
                    },
                    {
                        name: 'ar_balance',
                        index: 'ar_balance',
                        width: 120,
                        align: 'right'
                    },
                    {
                        name: 'tunggakan_cicilan',
                        index: 'tunggakan_cicilan',
                        width: 150,
                        align: 'right'
                    },
                    {
                        name: 'denda',
                        index: 'denda',
                        width: 100,
                        align: 'right'
                    },
                    {
                        name: 'penalty',
                        index: 'penalty',
                        width: 100,
                        align: 'right'
                    },
                    {
                        name: 'total_billing',
                        index: 'total_billing',
                        width: 140,
                        align: 'right'
                    },
                    {
                        name: 'score',
                        index: 'score',
                        width: 100,
                        align: 'right'
                    },
                    {
                        name: 'score2',
                        index: 'score2',
                        width: 100,
                        align: 'right'
                    }
                ],
                rowNum: 10,
                pager: '#score_tiering_pager',
                sortname: 'no_pinjaman',
                viewrecords: true,
                gridview: true,
                shrinkToFit: true,
                forceFit: true,
                loadComplete: function() {
                    let totalRecords = $('#score_tiering_table').getGridParam('records');
                    $("#total-data").val(totalRecords);
                    $("#total-data-hidden").val(totalRecords); // Update hidden input juga
                }
            }).navGrid('#score_tiering_pager', {
                edit: false,
                add: false,
                del: false,
                search: false,
                refresh: false
            });

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

                $("#score_tiering_table").setGridParam({
                    datatype: 'json',
                    url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/tiering/scoring_result",
                    mtype: 'POST',
                    postData: {
                        score_start: start,
                        score_end: end,
                        score_type: $("#opt_type").val(),
                        lob: $("#opt_lob").val(),
                        cycle: $("#opt_cycle").val(),
                        bucket: selected.bucket,
                        operScoring: selected.oper
                    }
                }).trigger("reloadGrid");
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