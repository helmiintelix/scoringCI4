<!-- Bootstrap CSS -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->

<!-- DataTables CSS -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"> -->

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

<div class="container py-4">
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
</div>

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

<!-- DataTables core -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- DataTables Bootstrap 5 -->
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="<?= base_url(); ?>modules/Tiering/js/Tiering.js?v=<?= rand() ?>"></script>