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
<!-- 
<div class="page-header">
    <h1>
        Scoring Settings
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            <?= ($form_mode == 'EDIT') ? 'Edit' : 'Add New' ?> Scheme
        </small>
    </h1>
</div> -->

<div class="row">
    <div class="col-xs-12">
        <form class="form-horizontal" role="form" id="frmScoringSettings" name="frmScoringSettings">
            <input type="hidden" id="opt_method" name="opt_method" value="METHOD2" />

            <div class="form-section-title" style="padding-bottom: 10px;">
                <strong>Data grouped by</strong> <span style="font-weight: normal;"> (Primary Key)</span>
            </div>

            <div class="form-group">
                <div class="col-sm-3">
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
            </div>

            <h4 class="form-section-title" style="padding-top: 20px; font-weight: bold;">Scoring Purple</h4>
            <div class="table-responsive">
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

                                    switch ($row['value_content']) {
                                        case 'TEXT':
                                            echo '<input type="text" name="' . $input_name . '" value="' . esc($input_value) . '" class="form-control" />';
                                            break;
                                        case 'NUMBER':
                                            echo '<input type="number" name="' . $input_name . '" value="' . esc($input_value) . '" class="form-control" />';
                                            break;
                                        case 'DATE':
                                            echo '<input type="date" name="' . $input_name . '" value="' . esc($input_value) . '" class="form-control date-picker" />';
                                            break;
                                        case 'MULTIPLE_VALUE':
                                            if (isset($ref_list[$row['parameter_reference']])) {
                                                echo form_dropdown(
                                                    $input_name . '[]',
                                                    $ref_list[$row['parameter_reference']],
                                                    json_decode($input_value, true),
                                                    'class="form-control" multiple'
                                                );
                                            } else {
                                                echo '<input type="text" name="' . $input_name . '" value="' . esc($input_value) . '" class="form-control" />';
                                            }
                                            break;
                                        default:
                                            if (isset($ref_list[$row['parameter_reference']])) {
                                                echo form_dropdown(
                                                    $input_name,
                                                    $ref_list[$row['parameter_reference']],
                                                    $input_value,
                                                    'class="form-control"'
                                                );
                                            } else {
                                                echo '<input type="text" name="' . $input_name . '" value="' . esc($input_value) . '" class="form-control" />';
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

            <div class="form-group" style="margin-bottom:15px;">
                <label class="col-sm-3 control-label" for="score_value_all" style="margin-bottom: 4px;">Score Value 1</label>
                <div class="col-sm-6">
                    <input type="text" id="score_value_all" name="score_value_all" class="form-control" onKeypress="return numbersOnly(this, event)" value="<?= $total_score ?>" />
                </div>
            </div>

            <div class="form-group" style="margin-bottom:15px;">
                <label class="col-sm-3 control-label" for="score_value_all2" style="margin-bottom: 4px;">Score Value 2</label>
                <div class="col-sm-6">
                    <input type="text" id="score_value_all2" name="score_value_all2" class="form-control" onKeypress="return numbersOnly(this, event)" value="<?= $total_score2 ?>" />
                </div>
            </div>

            <div class="form-group" style="margin-bottom:15px;">
                <label class="col-sm-3 control-label" for="score_label" style="margin-bottom: 4px;">Label/Title</label>
                <div class="col-sm-6">
                    <input type="text" id="score_label" name="score_label" placeholder="Label/Title" class="form-control" value="<?= @$scheme_data[0]['name'] ?>" />
                </div>
            </div>

            <div class="form-group" style="margin-bottom:15px;">
                <label class="col-sm-3 control-label" for="is_active" style="margin-bottom: 4px;">Is Active</label>
                <div class="col-sm-3">
                    <?= form_dropdown('is_active', $yes_no, 'Y', 'class="form-control" id="is_active"'); ?>
                </div>
            </div>

            <div class="form-actions text-center">
                <button class="btn btn-primary" type="button" id="saveForm">
                    Save
                </button>
                <button class="btn btn-secondary" type="reset">
                    Reset
                </button>
                <button class="btn btn-success" type="button" id="uploadForm">
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>