<style>
    .form-switch .form-check-input {
        width: 2.5em !important;
        height: 1.2em !important;
        font-size: 1.2em;
    }

    .form-switch .form-check-input:focus {
        border-color: rgba(13, 110, 253, 0.25);
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .table th {
        background-color: #004085 !important;
        color: #fff !important;
    }
</style>

<h4 class="mb-4" style="font-weight: bold;">Scoring Purple</h4>

<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="py-3 px-4">Parameters</th>
                        <th scope="col" class="py-3">Include</th>
                        <th scope="col" class="py-3 px-4">Summed</th>
                        <th scope="col" class="py-3 px-4">Value Content</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($scoring_purple_parameter) && is_array($scoring_purple_parameter)) {
                        foreach ($scoring_purple_parameter as $row) {
                            $is_include = ($row['is_include'] == 'YES') ? ' checked' : '';
                            $is_primary = ($row['is_primary'] == 'YES') ? ' checked' : '';
                            $is_sum = ($row['is_sum'] == 'YES') ? ' checked' : '';
                            $is_monthly = ($row['is_monthly'] == 'YES') ? ' checked' : '';
                    ?>
                            <tr>
                                <td class="px-4"><?= htmlspecialchars($row["name"]) ?></td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input btn_parameter"
                                            type="checkbox"
                                            id="scoring_purple-include-<?= $row["id"] ?>"
                                            name="scoring_purple-include"
                                            value="YES"
                                            <?= $is_include ?>
                                            data-param="SCORING_PURPLE"
                                            data-param-id="<?= $row["id"] ?>"
                                            data-column="is_include">
                                        <span class="lbl"></span>
                                    </div>

                                    <input type="hidden"
                                        id="scoring_purple-primary-<?= $row["id"] ?>"
                                        name="scoring_purple-primary"
                                        value="YES"
                                        <?= $is_primary ?>>
                                </td>

                                <?php if ($row["is_sum"] != "EXC"): ?>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input btn_parameter"
                                                type="checkbox"
                                                id="scoring_purple-summed-<?= $row["id"] ?>"
                                                name="scoring_purple-summed"
                                                value="YES"
                                                <?= $is_sum ?>
                                                data-param="SCORING_PURPLE"
                                                data-param-id="<?= $row["id"] ?>"
                                                data-column="is_sum">
                                            <span class="lbl"></span>
                                        </div>
                                    </td>
                                <?php else: ?>
                                    <td>&nbsp;</td>
                                <?php endif; ?>

                                <td class="px-4">
                                    <select class="form-select btn_parameter_dropdown"
                                        id="value_content_<?= $row["id"] ?>"
                                        data-param="SCORING_PURPLE"
                                        data-param-id="<?= $row["id"] ?>"
                                        data-column="value_content">
                                        <?php foreach ($aging_value_content_list as $key => $value): ?>
                                            <option value="<?= $key ?>" <?= ($row['value_content'] == $key) ? 'selected' : '' ?>>
                                                <?= $value ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <?php
                                    ?>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                <em>No scoring purple parameters found or data is empty.</em>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row">
    <div class="col-9">
        <form class="form-horizontal" role="form">
            <div class="d-flex">
                <button class="btn btn-primary me-2" type="button" id="saveForm">
                    Save
                </button>

                <button class="btn btn-secondary" type="reset" id="resetForm">
                    Reset
                </button>
            </div>
        </form>
    </div>
</div>


<div class="vspace-xs-4"></div>

<script src="<?= base_url(); ?>modules/Parameters/js/Parameters.js?v=<?= rand() ?>"></script>