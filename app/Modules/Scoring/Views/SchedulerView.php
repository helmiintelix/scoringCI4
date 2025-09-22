<h4 class="blue smaller">
    PASSWORD
</h4>

<div class="container my-4" >
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Schedule</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="SCHEDULER" value="<?=@$scheduler_setting["SCHEDULER"]?>">
                <button class="btn btn-primary btn_system" data-id="SCHEDULER" type="button">Save</button>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Time Run</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="TIME_RUN_START" value="<?=@$scheduler_setting["TIME_RUN_START"]?>">
                <button class="btn btn-primary btn_system" data-id="TIME_RUN_START" type="button">Save</button>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Notification Active</div>
        <div class="col-md-4">
            <div class="input-group">
                <select class='form-control editable' id="NOTIFICATION">
                    <option value="Y" <?=@$scheduler_setting["NOTIFICATION"]=="ACTIVE"?"selected":""?>>ON</option>
                    <option value="N" <?=@$scheduler_setting["NOTIFICATION"]=="NOTACTIVE"?"selected":""?>>OFF</option>
                </select>
                <button class="btn btn-primary btn_system" data-id="NOTIFICATION" type="button">Save</button>
            </div>
        </div>
        &nbsp; 
    </div>

    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Notification Type	</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="NOTIFICATION_TYPE" value="<?=@$scheduler_setting["NOTIFICATION_TYPE"]?>">
                <button class="btn btn-primary btn_system" data-id="NOTIFICATION_TYPE" type="button">Save</button>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Email Address	</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="EMAIL_ADDRESS" value="<?=@$scheduler_setting["EMAIL_ADDRESS"]?>">
                <button class="btn btn-primary btn_system" data-id="EMAIL_ADDRESS" type="button">Save</button>
            </div>
        </div>
    </div>
    
    
</div>

<script type="text/javascript">
	jQuery(function($) {
	

		var updateSystemSetting = function(id, value, parameter)
		{
			$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/scheduler/updateSystemSetting/", { id: id, value: value, parameter:parameter }, function(responseText) {
				if(responseText.success) {
					showInfo("Data telah disimpan");
				} else{
					showWarning("Gagal");
				}
			}, "json");
		}	
		
        $(".btn_system").each(function () {
            $(this).click(function () {
                var id = this.dataset.id
                var value = $(`#${id}`).val();
                console.log(id, value, this.dataset.id, "eheheheheh")
                updateSystemSetting(id, value, "SYSTEM");
            });
        });
       
	});
</script>