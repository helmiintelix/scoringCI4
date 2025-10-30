<h4 class="blue smaller">
    Password Configuration
</h4>

<div class="container my-4 p-0">
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Minimum Length (char)</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="number" class="form-control editable" id="PASSWORD_MIN_LENGTH" value="<?= @$general_setting["PASSWORD_MIN_LENGTH"] ?>">
                <button class="btn btn-primary btn_system" data-id="PASSWORD_MIN_LENGTH" type="button">Save</button>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Maximum Length (char)</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="number" class="form-control editable" id="PASSWORD_MAX_LENGTH" value="<?= @$general_setting["PASSWORD_MAX_LENGTH"] ?>">
                <button class="btn btn-primary btn_system" data-id="PASSWORD_MAX_LENGTH" type="button">Save</button>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold"> Contain Numerical </div>
        <div class="col-md-4">
            <div class="input-group">
                <select class='form-control editable' id="PASSWORD_INCLD_NUMERIC">
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
                <select class='form-control editable' id="PASSWORD_INCLD_ALPHABET">
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
                <select class='form-control editable' id="PASSWORD_INCLD_SPECIAL_CHAR">
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
                <select class='form-control editable' id="PASSWORD_CASE_SENSITIVE">
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
                <input type="number" class="form-control editable" id="PASSWORD_EXPIRE_DURATION" value="<?= @$general_setting["PASSWORD_EXPIRE_DURATION"] ?>">
                <button class="btn btn-primary btn_system" data-id="PASSWORD_EXPIRE_DURATION" type="button">Save</button>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold"> Maximum Failed (attempt) </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="number" class="form-control editable" id="PASSWORD_MAX_FAILED_ATTEMPTS" value="<?= @$general_setting["PASSWORD_MAX_FAILED_ATTEMPTS"] ?>">
                <button class="btn btn-primary btn_system" data-id="PASSWORD_MAX_FAILED_ATTEMPTS" type="button">Save</button>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold"> Change at First Login </div>
        <div class="col-md-4">
            <div class="input-group">
                <select class='form-control editable' id="PASSWORD_CHANGE_AT_FIRST_LOGIN">
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
                <input type="text" class="form-control editable" id="PASSWORD_DEFAULT" value="<?= @$general_setting["PASSWORD_DEFAULT"] ?>">
                <button class="btn btn-primary btn_system" data-id="PASSWORD_DEFAULT" type="button">Save</button>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Forced exit when idle for (minute)</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="number" class="form-control editable" id="FORCED_EXIT" value="<?= @$general_setting["FORCED_EXIT"] ?>">
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
                var id = this.dataset.id
                var value = $(`#${id}`).val();
                console.log(id, value, this.dataset.id, "eheheheheh")
                updateSystemSetting(id, value, "SYSTEM");
            });
        });

    });
</script>