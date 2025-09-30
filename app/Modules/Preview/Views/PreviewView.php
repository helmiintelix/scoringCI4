<style>
    .table thead th {
        position: sticky;
        top: 0;
        background-color: #004085 !important;
        color: #fff !important;
        z-index: 10;
    }

    .scheme-separator {
        height: 10px;
        padding: 0;
        background-color: #e6e6e6;
    }

    .btn-minier {
        font-size: 11px;
        padding: 2px 6px;
        line-height: 1.5;
    }

    .table-container {
        max-height: 500px;
        overflow-y: auto;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="table-responsive table-container">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="px-4 align-middle">Label</th>
                        <th scope="col" class="px-4 align-middle">Score 1</th>
                        <th scope="col" class="px-4 align-middle">Score 2</th>
                        <th scope="col" class="px-4 align-middle">Group By</th>
                        <th scope="col" class="px-4 align-middle">Parameter Group</th>
                        <th scope="col" class="px-4 align-middle">Parameter Selected</th>
                        <th scope="col" class="px-4 align-middle">Function</th>
                        <th scope="col" class="px-4 align-middle">Parameter Value</th>
                        <th scope="col" class="px-4 align-middle" colspan="2">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if (!empty($scheme_list) && is_array($scheme_list)) {
                        $scheme_name = "";
                        foreach ($scheme_list as $row) {
                            if ($scheme_name != $row['scheme_name']) {
                                $scheme_name = $row['scheme_name'];
                    ?>
                                <tr>
                                    <td class="px-4" nowrap><strong><?= htmlspecialchars($row['scheme_name']) ?></strong></td>
                                    <td nowrap>
                                        <span class="badge bg-success"><?= htmlspecialchars($row['score_value']) ?></span>
                                    </td>
                                    <td nowrap>
                                        <span class="badge bg-info"><?= htmlspecialchars($row['score_value2']) ?></span>
                                    </td>
                                    <td class="px-4" nowrap><?= htmlspecialchars($row['group_by']) ?></td>
                                    <td class="px-4" nowrap>
                                        <span class="badge bg-light text-dark"><?= htmlspecialchars($row['parameter_group']) ?></span>
                                    </td>
                                    <td class="px-4" nowrap><?= htmlspecialchars($row['parameter_selected']) ?></td>
                                    <td class="px-4" nowrap>
                                        <code class="small"><?= htmlspecialchars($row['parameter_function']) ?></code>
                                    </td>
                                    <td class="px-4" nowrap>
                                        <small><?= htmlspecialchars($row['parameter_value']) ?></small>
                                    </td>
                                    <td class="text-center" nowrap>
                                        <button class="btn btn-warning btn-minier" type="button"
                                            onClick="loadScoreSettingEditForm2('<?= htmlspecialchars($row['scheme_id']) ?>','<?= htmlspecialchars($row['parameter_group']) ?>','<?= htmlspecialchars($row['parameter_selected']) ?>')">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </td>
                                    <td class="text-center" nowrap>
                                        <button class="btn btn-danger btn-minier" type="button"
                                            onClick="loadScoreSettingDeleteForm('<?= htmlspecialchars($row['scheme_id']) ?>','<?= htmlspecialchars($row['scheme_name']) ?>')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php
                            } else {
                                if ($row['value_content'] == 'MAPPING') {
                                    $parameter_function = 'MAPPING';
                                    $parameter_value = 'Reference: ' . $row['map_reference'];
                                } else {
                                    $parameter_function = $row['parameter_function'];
                                    $parameter_value = $row['parameter_value'];
                                }
                            ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="px-4" nowrap>
                                        <span class="badge bg-light text-dark"><?= htmlspecialchars($row['parameter_group']) ?></span>
                                    </td>
                                    <td class="px-4" nowrap><?= htmlspecialchars($row['parameter_selected']) ?></td>
                                    <td class="px-4" nowrap>
                                        <code class="small"><?= htmlspecialchars($parameter_function) ?></code>
                                    </td>
                                    <td class="px-4" nowrap>
                                        <small><?= htmlspecialchars($parameter_value) ?></small>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                        <?php
                            }
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                <em>No scheme data found or data is empty.</em>
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
<div class="vspace-xs-4"></div>

<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Delete Confirmation</h5>
            </div>
            <div class="modal-body">
                <p id="deleteConfirmMessage">Are you sure you want to delete this scheme?</p>
                <input type="hidden" id="deleteSchemeId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url(); ?>modules/Preview/js/Preview.js?v=<?= rand() ?>"></script>