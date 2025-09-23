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

<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th scope="col" class="py-3 px-4">Parameters</th>
            <th scope="col" class="py-3 px-4">Include</th>
            <th scope="col" class="py-3 px-4">Summed</th>
            <th scope="col" class="py-3 px-4">Value Content</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($scoring_purple_parameter) && is_array($scoring_purple_parameter)) {
            foreach ($scoring_purple_parameter as $row) {
                $is_include = ($row['is_include'] == 'YES') ? ' checked' : '';
                $is_sum = ($row['is_sum'] == 'YES') ? ' checked' : '';
                $is_monthly = ($row['is_monthly'] == 'YES') ? ' checked' : '';
                $is_primary = ($row['is_primary'] == 'YES') ? ' checked' : '';
        ?>
                <tr>
                    <td class="px-4"><?= htmlspecialchars($row["name"]) ?></td>
                    <td class="px-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                type="checkbox"
                                id="scoring_purple-include-<?= $row["id"] ?>"
                                name="scoring_purple-include"
                                value="YES"
                                <?= $is_include ?>
                                onclick="update_parameter_setting_chcekbox('SCORING_PURPLE', '<?= $row["id"] ?>', 'is_include', $(this))">
                        </div>

                        <input type="hidden"
                            id="scoring_purple-primary-<?= $row["id"] ?>"
                            name="scoring_purple-primary"
                            value="YES"
                            <?= $is_primary ?>>
                    </td>

                    <?php if ($row["is_sum"] != "EXC"): ?>
                        <td class="px-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                    type="checkbox"
                                    id="scoring_purple-summed-<?= $row["id"] ?>"
                                    name="scoring_purple-summed"
                                    value="YES"
                                    <?= $is_sum ?>
                                    onclick="update_parameter_setting_chcekbox('SCORING_PURPLE', '<?= $row["id"] ?>', 'is_sum', $(this))">
                            </div>
                        </td>
                    <?php else: ?>
                        <td>&nbsp;</td>
                    <?php endif; ?>

                    <td class="px-4">
                        <?php
                        $attributes = 'class="form-select" id="value_content_' . $row["id"] . '" onchange="update_parameter_setting(\'SCORING_PURPLE\', \'' . $row["id"] . '\', \'value_content\', $(this).val())"';
                        echo form_dropdown('value_content', $aging_value_content_list, $row['value_content'], $attributes);
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