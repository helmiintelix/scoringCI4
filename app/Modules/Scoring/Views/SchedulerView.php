<h4 class="blue smaller">
    PASSWORD
</h4>

<div class="container my-4 p-0">
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Schedule</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="SCHEDULER" value="<?=@$scheduler_setting["SCHEDULER"]?>" readonly>
                <!-- <button class="btn btn-primary btn_system" data-id="SCHEDULER" type="button">Save</button> -->
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Time Run</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="time" class="form-control editable" id="TIME_RUN_START" value="<?=@$scheduler_setting["TIME_RUN_START"]?>" placeholder="HH:MM (24-hour)">
                <button class="btn btn-primary btn_system" data-id="TIME_RUN_START" type="button">Save</button>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Notification Active</div>
        <div class="col-md-4">
            <div class="input-group">
                <select class='form-control editable' id="NOTIFICATION" disabled>
                    <option value="Y" <?=@$scheduler_setting["NOTIFICATION"]=="ACTIVE"?"selected":""?>>ON</option>
                    <option value="N" <?=@$scheduler_setting["NOTIFICATION"]=="NOTACTIVE"?"selected":""?>>OFF</option>
                </select>
                <!-- <button class="btn btn-primary btn_system" data-id="NOTIFICATION" type="button">Save</button> -->
            </div>
        </div>
        &nbsp;
    </div>

    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Notification Type </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="NOTIFICATION_TYPE" value="<?=@$scheduler_setting["NOTIFICATION_TYPE"]?>" readonly >
                <!-- <button class="btn btn-primary btn_system" data-id="NOTIFICATION_TYPE" type="button">Save</button> -->
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Email Address </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="email" class="form-control editable" id="EMAIL_ADDRESS" value="<?=@$scheduler_setting["EMAIL_ADDRESS"]?>" >
                <button class="btn btn-primary btn_system" data-id="EMAIL_ADDRESS" type="button">Save</button>
            </div>
        </div>
    </div>


</div>

<script type="text/javascript">
    jQuery(function($) {


        var updateSystemSetting = function(id, value, parameter) {
            $.post(GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/scheduler/updateSystemSetting/", {
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

                // Validate TIME_RUN_START format before sending (only HH:MM allowed)
                var isValidTimeFormat = function(v) {
                    if(!v) return false;
                    v = v.trim();
                    // Accept only HH:MM in 24-hour format
                    var re = /^(?:[01]\d|2[0-3]):[0-5]\d$/;
                    return re.test(v);
                }

                // Simple email validation (uses a reasonable, permissive regex)
                var isValidEmail = function(v) {
                    if(!v) return false;
                    v = v.trim();
                    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return re.test(v);
                }

                if(id === 'TIME_RUN_START') {
                    if(!isValidTimeFormat(value)) {
                        // showWarning is used in this project to show warnings
                        if(typeof showWarning === 'function') {
                            showWarning('Format waktu tidak valid. Gunakan HH:MM (24 jam).');
                        } else {
                            alert('Format waktu tidak valid. Gunakan HH:MM (24 jam).');
                        }
                        return;
                    }
                }

                if(id === 'EMAIL_ADDRESS') {
                    if(!isValidEmail(value)) {
                        if(typeof showWarning === 'function') {
                            showWarning('Alamat email tidak valid.');
                        } else {
                            alert('Alamat email tidak valid.');
                        }
                        return;
                    }
                }

                console.log(id, value, this.dataset.id);
                updateSystemSetting(id, value, "SYSTEM");
            });
        });

    });
</script>