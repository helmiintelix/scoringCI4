<h4 class="blue smaller">
    Password Configuration
</h4>

<div class="container my-4 p-0">
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Minimum Length (char)</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="number" inputmode="numeric" pattern="\d*" oninput="this.value=this.value.replace(/\D/g,'')" class="form-control editable" id="PASSWORD_MIN_LENGTH" value="<?= @$general_setting["PASSWORD_MIN_LENGTH"] ?>" min="0" step="1" required>
                <button class="btn btn-primary btn_system" data-id="PASSWORD_MIN_LENGTH" type="button">Save</button>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Maximum Length (char)</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="number" inputmode="numeric" pattern="\d*" oninput="this.value=this.value.replace(/\D/g,'')" class="form-control editable" id="PASSWORD_MAX_LENGTH" value="<?= @$general_setting["PASSWORD_MAX_LENGTH"] ?>" min="0" step="1" required>
                <button class="btn btn-primary btn_system" data-id="PASSWORD_MAX_LENGTH" type="button">Save</button>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold"> Contain Numerical </div>
        <div class="col-md-4">
            <div class="input-group">
                <select class='form-control editable' id="PASSWORD_INCLD_NUMERIC" required>
                    <option value="Y" <?= @$general_setting["PASSWORD_INCLD_NUMERIC"] == "Y" ? "selected" : "" ?>>Yes</option>
                    <option value="N" <?= @$general_setting["PASSWORD_INCLD_NUMERIC"] == "N" ? "selected" : "" ?>>No</option>
                </select>
                <button class="btn btn-primary btn_system" data-id="PASSWORD_INCLD_NUMERIC" type="button">Save</button>
            </div>
        </div>
        &nbsp;
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold"> Contain Alphabet </div>
        <div class="col-md-4">
            <div class="input-group">
                <select class='form-control editable' id="PASSWORD_INCLD_ALPHABET" required>
                    <option value="Y" <?= @$general_setting["PASSWORD_INCLD_ALPHABET"] == "Y" ? "selected" : "" ?>>Yes</option>
                    <option value="N" <?= @$general_setting["PASSWORD_INCLD_ALPHABET"] == "N" ? "selected" : "" ?>>No</option>
                </select>
                <button class="btn btn-primary btn_system" data-id="PASSWORD_INCLD_ALPHABET" type="button">Save</button>
            </div>
        </div>
        &nbsp;
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold"> Contain Special Char </div>
        <div class="col-md-4">
            <div class="input-group">
                <select class='form-control editable' id="PASSWORD_INCLD_SPECIAL_CHAR" required>
                    <option value="Y" <?= @$general_setting["PASSWORD_INCLD_SPECIAL_CHAR"] == "Y" ? "selected" : "" ?>>Yes</option>
                    <option value="N" <?= @$general_setting["PASSWORD_INCLD_SPECIAL_CHAR"] == "N" ? "selected" : "" ?>>No</option>
                </select>
                <button class="btn btn-primary btn_system" data-id="PASSWORD_INCLD_SPECIAL_CHAR" type="button">Save</button>
            </div>
        </div>
        &nbsp;
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold"> Use upper- and lower-case </div>
        <div class="col-md-4">
            <div class="input-group">
                <select class='form-control editable' id="PASSWORD_CASE_SENSITIVE" required>
                    <option value="Y" <?= @$general_setting["PASSWORD_CASE_SENSITIVE"] == "Y" ? "selected" : "" ?>>Yes</option>
                    <option value="N" <?= @$general_setting["PASSWORD_CASE_SENSITIVE"] == "N" ? "selected" : "" ?>>No</option>
                </select>
                <button class="btn btn-primary btn_system" data-id="PASSWORD_CASE_SENSITIVE" type="button">Save</button>
            </div>
        </div>
        &nbsp;
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Validity Period (day)</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="number" inputmode="numeric" pattern="\d*" oninput="this.value=this.value.replace(/\D/g,'')" class="form-control editable" id="PASSWORD_EXPIRE_DURATION" value="<?= @$general_setting["PASSWORD_EXPIRE_DURATION"] ?>" min="0" step="1" required>
                <button class="btn btn-primary btn_system" data-id="PASSWORD_EXPIRE_DURATION" type="button">Save</button>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold"> Maximum Failed (attempt) </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="number" inputmode="numeric" pattern="\d*" oninput="this.value=this.value.replace(/\D/g,'')" class="form-control editable" id="PASSWORD_MAX_FAILED_ATTEMPTS" value="<?= @$general_setting["PASSWORD_MAX_FAILED_ATTEMPTS"] ?>" min="0" step="1" required>
                <button class="btn btn-primary btn_system" data-id="PASSWORD_MAX_FAILED_ATTEMPTS" type="button">Save</button>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold"> Change at First Login </div>
        <div class="col-md-4">
            <div class="input-group">
                <select class='form-control editable' id="PASSWORD_CHANGE_AT_FIRST_LOGIN" required>
                    <option value="Y" <?= @$general_setting["PASSWORD_CHANGE_AT_FIRST_LOGIN"] == "Y" ? "selected" : "" ?>>Yes</option>
                    <option value="N" <?= @$general_setting["PASSWORD_CHANGE_AT_FIRST_LOGIN"] == "N" ? "selected" : "" ?>>No</option>
                </select>
                <button class="btn btn-primary btn_system" data-id="PASSWORD_CHANGE_AT_FIRST_LOGIN" type="button">Save</button>
            </div>
        </div>
        &nbsp;
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Default Password</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="PASSWORD_DEFAULT" value="<?= @$general_setting["PASSWORD_DEFAULT"] ?>" required>
                <button class="btn btn-primary btn_system" data-id="PASSWORD_DEFAULT" type="button">Save</button>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Forced exit when idle for (minute)</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="number" inputmode="numeric" pattern="\d*" oninput="this.value=this.value.replace(/\D/g,'')" class="form-control editable" id="FORCED_EXIT" value="<?= @$general_setting["FORCED_EXIT"] ?>" min="0" step="1" required>
                <button class="btn btn-primary btn_system" data-id="FORCED_EXIT" type="button">Save</button>
            </div>
        </div>
    </div>


</div>

<script type="text/javascript">
    jQuery(function($) {


        var updateSystemSetting = function(id, value, parameter) {
            $.post(GLOBAL_MAIN_VARS["SITE_URL"] + "setting/general/updateSystemSetting/", {
                id: id,
                value: value,
                parameter: parameter
            }, function(responseText) {
                if (responseText.success) {
                    showInfo("Data telah disimpan");
                } else {
                    showWarning("Gagal");
                }
            }, "json");
        }

        $(".btn_system").each(function() {
            $(this).click(function() {
                var id = this.dataset.id;
                var $el = $(`#${id}`);
                var value = $el.val();
                if (typeof value === 'string') value = value.trim();

                var numericFields = ['PASSWORD_MIN_LENGTH','PASSWORD_MAX_LENGTH','PASSWORD_EXPIRE_DURATION','PASSWORD_MAX_FAILED_ATTEMPTS','FORCED_EXIT'];

                var warn = function(msg) {
                    if (typeof showWarning === 'function') showWarning(msg);
                    else alert(msg);
                };

                // Required check
                if ($el.prop('required')) {
                    if (value === null || value === undefined || value === '') {
                        warn('Field ini wajib diisi');
                        return;
                    }
                }

                // Numeric, non-negative checks
                if (numericFields.indexOf(id) !== -1) {
                    // allow numeric input only (non-negative integers)
                    if (!/^\d+$/.test(String(value))) {
                        warn('Nilai harus bilangan bulat >= 0');
                        return;
                    }
                    var intVal = parseInt(value, 10);
                    if (intVal < 0) {
                        warn('Nilai tidak boleh negatif');
                        return;
                    }

                    // Cross-field checks: min <= max
                    if (id === 'PASSWORD_MIN_LENGTH') {
                        var maxVal = $(`#PASSWORD_MAX_LENGTH`).val();
                        if (maxVal !== null && maxVal !== undefined && maxVal !== '') {
                            if (/^\d+$/.test(String(maxVal))) {
                                if (intVal > parseInt(maxVal, 10)) {
                                    warn('Minimum length tidak boleh lebih besar dari Maximum length');
                                    return;
                                }
                            }
                        }
                    }
                    if (id === 'PASSWORD_MAX_LENGTH') {
                        var minVal = $(`#PASSWORD_MIN_LENGTH`).val();
                        if (minVal !== null && minVal !== undefined && minVal !== '') {
                            if (/^\d+$/.test(String(minVal))) {
                                if (parseInt(minVal, 10) > intVal) {
                                    warn('Maximum length harus lebih besar atau sama dengan Minimum length');
                                    return;
                                }
                            }
                        }
                    }
                }

                // All checks passed
                updateSystemSetting(id, value, "SYSTEM");
            });
        });

    });
</script>