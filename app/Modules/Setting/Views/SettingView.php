<!-- CSS -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css">
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
<?php
    helper('generateDropdown'); 
    helper('generateInputObject'); 
?>

<div class="row">
    <div class="col-xs-12">
        <form class="form-horizontal" role="form" id="frmScoringSettings" name="frmScoringSettings">
            <input type="hidden" id="opt_method" name="opt_method" value="METHOD2" />

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
                        // echo "<pre>";
                        // print_r($scoring_purple_parameter);
                        // echo "</pre>";
                        // die;
                        foreach ($scoring_purple_parameter as $row) {
                          	$is_include = ($row['is_include'] == 'YES') ? ' checked' : '';
							$is_primary = ($row['is_primary'] == 'YES') ? ' checked' : '';

							if ($row['score_value'])
								$total_score += @$row['score_value'];
							if ($row['score_value2'])
								$total_score2 += @$row['score_value2'];
                        ?>
                            <tr>
                                <td><?= esc($row['name']) ?></td>
                                <td><?= generate_dropdown("opt_function1_SCORING_PURPLE_" . $row['param_id'], $function_list[$row["value_type"]][$row["value_content"]], $row['parameter_function'], FALSE); ?></td>
								<td><?= generate_input_object("SCORING_PURPLE", $row, $ref_list); ?></td>
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
                    <input type="text" id="score_value_all" name="score_value_all" placeholder="Score Value 1" class="form-control" onKeypress="return numbersOnly(this, event)" value="<?= $total_score ?>" />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label" for="score_value_all2">Score Value 2</label>
                <div class="col-sm-6">
                    <input type="text" id="score_value_all2" name="score_value_all2" placeholder="Score Value 2" class="form-control" onKeypress="return numbersOnly(this, event)" value="<?= $total_score2 ?>" />
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
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url(); ?>modules/Setting/js/Setting.js?v=<?= rand() ?>"></script>