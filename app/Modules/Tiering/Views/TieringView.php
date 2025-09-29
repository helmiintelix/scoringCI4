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

    .text-end {
        text-align: right;
    }

    .small-text {
        font-size: 0.8rem;
        color: red;
    }
</style>

<form id="frmTieringSettings" class="container">
    <input type="hidden" id="assign-to" name="assign-to" value="" />
    <!-- Tiering ID -->
    <div class="profile-info-row">
        <div class="profile-info-name">Tiering ID</div>
        <div class="profile-info-value">
            <input type="text" id="tiering-id" name="tiering-id"
                class="form-control mandatory"
                placeholder="Tiering ID"
                data-label="Tiering ID"
                onkeyup="validasi_space(this.value,this.id)"
                value="<?= @$tiering_data['id'] ?>">
        </div>
    </div>

    <!-- Tiering Label -->
    <div class="profile-info-row">
        <div class="profile-info-name">Tiering Label</div>
        <div class="profile-info-value">
            <input type="text" id="tiering-label" name="tiering-label"
                class="form-control mandatory"
                placeholder="Tiering Label"
                data-label="Tiering Label"
                value="<?= @$tiering_data['name'] ?>">
        </div>
    </div>

    <!-- Score Type -->
    <div class="profile-info-row">
        <div class="profile-info-name">Score Type</div>
        <div class="profile-info-value">
            <select id="opt_type" name="opt_type"
                class="form-select mandatory"
                data-label="Score Type">
                <option value="">-- Pilih --</option>
                <option value="score_value" <?= (@$tiering_data['score_type'] == 'score_value') ? 'selected' : '' ?>>Score 1</option>
                <option value="score_value2" <?= (@$tiering_data['score_type'] == 'score_value2') ? 'selected' : '' ?>>Score 2</option>
            </select>
        </div>
    </div>

    <!-- LOB -->
    <div class="profile-info-row">
        <div class="profile-info-name">LOB</div>
        <div class="profile-info-value">
            <!-- hidden supaya opt_lob selalu ada di POST -->
            <input type="hidden" name="opt_lob" value="">
            <?php
            $options = ['' => 'Select Data'];
            if (!empty($LOB_CODE)) {
                $options += $LOB_CODE;
                echo form_dropdown("opt_lob", $options, @$tiering_data['lob'], 'class="form-select mandatory" id="opt_lob" data-label="LOB"');
            } else {
                echo '<select class="form-select mandatory" id="opt_lob" data-label="LOB">
                    <option value="">Select Data</option>
                    <option value="" disabled>No Data Available</option>
                  </select>';
            }
            ?>
        </div>
    </div>

    <!-- Bucket -->
    <div class="profile-info-row">
        <div class="profile-info-name">Bucket</div>
        <div class="profile-info-value">
            <!-- hidden supaya opt_bucket selalu ada di POST -->
            <input type="hidden" name="opt_bucket" value="">
            <?php
            $options = ['' => 'Select Data'];
            if (!empty($BUCKET_SC)) {
                $options += $BUCKET_SC;
                echo form_dropdown("opt_bucket", $options, @$tiering_data['bucket'], 'class="form-select mandatory" id="opt_bucket" data-label="Bucket"');
            } else {
                echo '<select class="form-select mandatory" id="opt_bucket" data-label="Bucket">
                    <option value="">Select Data</option>
                    <option value="" disabled>No Data Available</option>
                  </select>';
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
                class="form-control text-end me-2"
                onkeypress="return numbersOnly(event, true)"
                placeholder="0" style="width: 80px;"
                value="<?= @$tiering_data['score_tiering_start'] ?>">
            <span class="mx-2">to</span>
            <input type="text" id="score-tiering-end" name="score-tiering-end"
                class="form-control text-end me-2"
                onkeypress="return numbersOnly(event, true)"
                placeholder="0" style="width: 80px;"
                value="<?= @$tiering_data['score_tiering_end'] ?>">
            <span class="small-text">*desimal dengan titik (.)</span>
        </div>
    </div>

    <!-- Total Data -->
    <div class="profile-info-row">
        <div class="profile-info-name">Total Data</div>
        <div class="profile-info-value d-flex align-items-center">
            <input type="text" id="total-data" readonly
                class="form-control text-end me-2" style="width: 80px;">
            <button type="button" id="btn-calculate" class="btn btn-info">Calculate</button>
        </div>
    </div>

    <!-- Buttons -->
    <div class="profile-info-row mt-3">
        <div class="profile-info-name"></div>
        <div class="profile-info-value">
            <button type="button" id="btn-save-form" class="btn btn-primary me-2">Save</button>
            <button type="reset" id="btn-reset-form" class="btn btn-secondary">Reset</button>
        </div>
    </div>
</form>

<!-- Table -->
<table id="score_tiering_table" class="table table-bordered mt-4"></table>
<div id="score_tiering_pager"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
<script>
    function validasi_space(val, id) {
        if (val.indexOf(' ') >= 0) {
            showWarning("Dilarang menggunakan spasi!");
            document.getElementById(id).value = val.replace(/\s/g, '');
        }
    }

    function numbersOnly(evt, allowDecimal = false) {
        let charCode = evt.which ? evt.which : evt.keyCode;
        if (allowDecimal && charCode === 46) return true; // allow dot
        if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
        return true;
    }

    $(document).ready(function() {
        // daftar field yang akan di-bypass validasinya
        const bypassFields = ['opt_lob', 'opt_bucket'];

        // tombol calculate
        $("#btn-calculate").click(function() {
            let start = parseFloat($("#score-tiering-start").val()) || 0;
            let end = parseFloat($("#score-tiering-end").val()) || 0;

            if (start > end) {
                showWarning("Score start tidak boleh lebih besar dari end!");
                return false;
            }

            let total = end - start + 1;
            $("#total-data").val(total > 0 ? total : 0);
        });

        // tombol save
        $("#btn-save-form").off('click').on('click', function(e) {
            e.preventDefault();

            let passed = true;
            $(".mandatory").each(function() {
                let nm = $(this).attr('id') || $(this).attr('name') || '';

                // kalau field termasuk bypass, lewati validasi
                if (bypassFields.includes(nm)) {
                    console.info("Bypass field:", nm);
                    return true; // lanjut ke field berikutnya
                }

                // validasi normal
                if (!$(this).val()) {
                    passed = false;
                    let label = $(this).data("label") || $(this).attr("placeholder") || nm;
                    showWarning("Field " + label + " wajib diisi!");
                    $(this).focus();
                    return false; // stop loop
                }
            });
            if (!passed) return false;

            // tambahkan form_mode jika belum ada
            let formMode = "<?= @$form_mode ?>";
            if ($("#frmTieringSettings input[name='form_mode']").length === 0) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'form_mode',
                    value: formMode
                }).appendTo('#frmTieringSettings');
            }

            // serialize form
            let formData = $("#frmTieringSettings").serialize();
            console.log("Data yang dikirim (bypassFields=" + bypassFields.join(',') + "):", formData);

            // kirim AJAX
            $.ajax({
                url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/tiering/save_tiering/",
                type: "post",
                data: formData,
                dataType: "json",
                success: function(response) {
                    console.log("Response server:", response);
                    if (response.success) {
                        showInfo(response.message || "Data berhasil disimpan.");
                    } else {
                        showWarning(response.message || "Gagal menyimpan data.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error AJAX:", xhr.responseText);
                    showWarning("Terjadi kesalahan: " + error);
                }
            });

            return false;
        });
    });
</script>