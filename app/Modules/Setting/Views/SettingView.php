<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Scoring Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css">

    <!-- Bootstrap Datepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" />

    <style>
        .no-search .select2-search {
            display: none;
        }

        .page-header h1 {
            font-size: 22px;
            margin-bottom: 20px;
        }

        .table th {
            background-color: #004085 !important;
            color: #fff !important;
        }

        .form-actions {
            margin-top: 20px;
        }
    </style>
</head>

<div class="row">
    <div class="col-xs-12">
        <form class="form-horizontal" role="form" id="frmScoringSettings" name="frmScoringSettings">
            <input type="hidden" id="opt_method" name="opt_method" value="METHOD2" />
            <h4 style="margin-bottom: 20px; font-weight: bold;">
                Scoring Settings
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    <?= ($form_mode == 'EDIT') ? '→ Edit' : '→ Add New' ?> Scheme
                </small>
            </h4>

            <!-- Group By -->
            <div class="form-section-title mb-2">
                <strong>Data grouped by</strong> <span style="font-weight: normal;"> (Primary Key)</span>
            </div>
            <div class="mb-3 col-sm-3">
                <?php
                $group_by_list = array("CM_CARD_NMBR" => "Agreement Number");
                echo form_dropdown(
                    "opt_group_by",
                    $group_by_list,
                    @$scheme_data[0]['group_by'],
                    'class="form-control" id="opt_group_by"'
                );
                ?>
            </div>

            <!-- Scoring Purple -->
            <h4 class="form-section-title mt-4 mb-2 fw-bold">Scoring Purple</h4>
            <div class="table-responsive mb-3">
                <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 250px">Parameters</th>
                            <th style="width: 200px">Function</th>
                            <th style="width: 250px">Input Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_score = 0;
                        $total_score2 = 0;
                        foreach ($scoring_purple_parameter as $row) {
                            if ($row['score_value'])
                                $total_score += @$row['score_value'];
                            if ($row['score_value2'])
                                $total_score2 += @$row['score_value2'];
                        ?>
                            <tr>
                                <td><?= esc($row['name']) ?></td>
                                <td>
                                    <?php
                                    $function_options = $function_list[$row["value_type"]][$row["value_content"]] ?? [];
                                    echo form_dropdown(
                                        "opt_function1_SCORING_PURPLE_" . $row['param_id'],
                                        $function_options,
                                        $row['parameter_function'],
                                        'class="form-control"'
                                    );
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $input_name = "par_SCORING_PURPLE_" . $row['param_id'];
                                    $input_value = $row['parameter_value'] ?? '';

                                    $decoded_value = '';
                                    if (!empty($input_value) && $input_value !== '[]') {
                                        $decoded_array = json_decode($input_value, true);
                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_array)) {
                                            if (count($decoded_array) === 1) {
                                                $decoded_value = $decoded_array[0];
                                            } else {
                                                $decoded_value = $decoded_array;
                                            }
                                        } else {
                                            $decoded_value = $input_value;
                                        }
                                    }
                                    switch ($row['value_content']) {
                                        case 'TEXT':
                                            echo '<input type="text" name="' . $input_name . '" value="' . esc($decoded_value) . '" class="form-control" />';
                                            break;
                                        case 'NUMBER':
                                            echo '<input type="number" name="' . $input_name . '" value="' . esc($decoded_value) . '" class="form-control" />';
                                            break;
                                        case 'DATE':
                                            echo '<input type="text" name="' . $input_name . '" value="' . esc($decoded_value) . '" class="form-control date-picker" />';
                                            break;
                                        case 'MULTIPLE_VALUE':
                                            if (isset($ref_list[$row['parameter_reference']])) {
                                                $selected_values = is_array($decoded_value) ? $decoded_value : [];
                                                echo form_dropdown(
                                                    $input_name . '[]',
                                                    $ref_list[$row['parameter_reference']],
                                                    $selected_values,
                                                    'class="form-control" multiple'
                                                );
                                            } else {
                                                echo '<input type="text" name="' . $input_name . '" value="' . esc($input_value) . '" class="form-control" />';
                                            }
                                            break;
                                        case 'SINGLE_VALUE':
                                            if (isset($ref_list[$row['parameter_reference']])) {
                                                echo form_dropdown(
                                                    $input_name,
                                                    $ref_list[$row['parameter_reference']],
                                                    $decoded_value,
                                                    'class="form-control"'
                                                );
                                            } else {
                                                echo '<input type="text" name="' . $input_name . '" value="' . esc($decoded_value) . '" class="form-control" placeholder="Enter text value" />';
                                            }
                                            break;
                                        default:
                                            if (isset($ref_list[$row['parameter_reference']])) {
                                                echo form_dropdown(
                                                    $input_name,
                                                    $ref_list[$row['parameter_reference']],
                                                    $decoded_value,
                                                    'class="form-control"'
                                                );
                                            } else {
                                                echo '<input type="text" name="' . $input_name . '" value="' . esc($decoded_value) . '" class="form-control" />';
                                            }
                                            break;
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Form Inputs -->
            <?php
            if ($form_mode == "ADD")
                $scheme_id = uniqid();
            else
                $scheme_id = @$scheme_data[0]['id'];

            $total_score = (@$scheme_data[0]['method'] == "METHOD1") ? "x" : @$scheme_data[0]['score_value'];
            $total_score2 = @$scheme_data[0]['score_value2'];
            ?>

            <input type="hidden" id="form_mode" name="form_mode" value="<?= $form_mode ?>" />
            <input type="hidden" id="score_scheme_id" name="score_scheme_id" value="<?= $scheme_id ?>" />

            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label" for="score_value_all">Score Value 1</label>
                <div class="col-sm-6">
                    <input type="text" id="score_value_all" name="score_value_all" class="form-control" onKeypress="return numbersOnly(this, event)" value="<?= $total_score ?>" />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label" for="score_value_all2">Score Value 2</label>
                <div class="col-sm-6">
                    <input type="text" id="score_value_all2" name="score_value_all2" class="form-control" onKeypress="return numbersOnly(this, event)" value="<?= $total_score2 ?>" />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label" for="score_label">Label/Title</label>
                <div class="col-sm-6">
                    <input type="text" id="score_label" name="score_label" placeholder="Label/Title" class="form-control" value="<?= @$scheme_data[0]['name'] ?>" />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label" for="is_active">Is Active</label>
                <div class="col-sm-3">
                    <?= form_dropdown('is_active', $yes_no, 'Y', 'class="form-control" id="is_active"'); ?>
                </div>
            </div>

            <div class="form-actions text-center mb-4">
                <button class="btn btn-primary" type="button" id="saveForm">Save</button>
                <button class="btn btn-secondary" type="reset">Reset</button>
                <button class="btn btn-success" type="button" id="uploadForm">Upload</button>
            </div>
        </form>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".date-picker").forEach(function(el) {
                new Datepicker(el, {
                    format: "yyyy-mm-dd",
                    autohide: true
                });
            });
        });

        $("#saveForm").click(function() {
            if (!validateForm()) return;

            $.ajax({
                type: "POST",
                url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/setting/save_setting/",
                data: $("#frmScoringSettings").serialize(),
                dataType: "json",
                success: function(msg) {
                    if (msg.success) {
                        showInfo("Data berhasil disimpan.");
                        loadMenu('preview', 'scoring/preview/preview');
                    } else {
                        showWarning(msg.message || "Terjadi kesalahan saat menyimpan.");
                    }
                },
                error: function(xhr) {
                    console.error("Raw response:", xhr.responseText);
                    showWarning("Server error: " + xhr.status);
                }
            });
        });

        function validateForm() {
            if ($("#score_label").val().trim() === "") {
                showWarning("Label/Title harus diisi!");
                return false;
            }
            return true;
        }

        $("#uploadForm").click(function(e) {
            e.preventDefault();

            $.ajax({
                type: "GET",
                url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/setting/upload_file_form/",
                success: function(response) {
                    if ($('#uploadModal').length === 0) {
                        $('body').append(`
                            <div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Upload File Form</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="uploadModalBody"></div>
                                </div>
                            </div>
                            </div>
                            `);
                    }

                    $('#uploadModalBody').html(response);

                    $('#uploadModal').modal('show');
                },
                error: function(xhr, status, error) {
                    showWarning("Gagal memuat form upload: " + error);
                }
            });
        });

        window.isUploading = false;
        window.uploadFormBound = window.uploadFormBound || false;

        if (!window.uploadFormBound) {
            $(document).on('submit', '#frm_upload', function(e) {
                e.preventDefault();

                if (window.isUploading) {
                    showInfo("Upload sedang dalam proses, mohon tunggu...");
                    return;
                }

                let fileInput = $("#userfile")[0]?.files[0];
                if (!fileInput) {
                    showInfo("Silakan pilih file yang mau diupload!");
                    return;
                }

                window.isUploading = true;

                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.prop('disabled', true).html('<i class="bi bi-spinner bi-spin"></i> Uploading...');

                let formData = new FormData(this);

                $.ajax({
                    type: "POST",
                    url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/setting/save_file/",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(msg) {
                        if (msg.success === true) {
                            showInfo("File berhasil diupload.");
                            $('#uploadModal').modal('hide');
                            loadMenu("preview", "scoring/preview/preview");
                        } else {
                            showWarning(msg.message || "Terjadi kesalahan saat upload.");
                        }
                    },
                    error: function(xhr, status, error) {
                        showWarning("Request gagal: " + error);
                    },
                    complete: function() {
                        window.isUploading = false;
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            window.uploadFormBound = true;
        }

        $(document).off('hidden.bs.modal', '#uploadModal').on('hidden.bs.modal', '#uploadModal', function() {
            $('#frm_upload')[0]?.reset();
            window.isUploading = false;
            const submitBtn = $('#frm_upload').find('button[type="submit"]');
            submitBtn.prop('disabled', false).html('<i class="bi bi-upload"></i> Upload');
        });
    </script>


    </body>

</html>