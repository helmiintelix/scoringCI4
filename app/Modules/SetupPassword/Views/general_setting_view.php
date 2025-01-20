<h4 class="blue smaller">
    PASSWORD
</h4>

<div class="container my-4" >
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Min Length</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="PASSWORD_MIN_LENGTH" value="<?=@$general_setting["PASSWORD_MIN_LENGTH"]?>">
                <button class="btn btn-primary" data-id="PASSWORD_MIN_LENGTH" type="button">Save</button>
            </div>
        </div>
        &nbsp; char(s)
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Max Length</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="PASSWORD_MAX_LENGTH" value="<?=@$general_setting["PASSWORD_MAX_LENGTH"]?>">
                <button class="btn btn-primary" data-id="PASSWORD_MAX_LENGTH" type="button">Save</button>
            </div>
        </div>
        &nbsp; char(s)
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Numerical</div>
        <div class="col-md-4">
            <div class="input-group">
                <select class='form-control editable' id="PASSWORD_INCLD_NUMERIC">
                    <option value="Y" <?=@$general_setting["PASSWORD_INCLD_NUMERIC"]=="Y"?"selected":""?>>Y</option>
                    <option value="N" <?=@$general_setting["PASSWORD_INCLD_NUMERIC"]=="N"?"selected":""?>>N</option>
                </select>
                <button class="btn btn-primary" data-id="PASSWORD_INCLD_NUMERIC" type="button">Save</button>
            </div>
        </div>
        &nbsp; 
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Alphabet</div>
        <div class="col-md-4">
            <div class="input-group">
                <select  class='form-control editable' id="PASSWORD_INCLD_ALPHABET">
                    <option value="Y" <?=@$general_setting["PASSWORD_INCLD_ALPHABET"]=="Y"?"selected":""?>>Y</option>
                    <option value="N" <?=@$general_setting["PASSWORD_INCLD_ALPHABET"]=="N"?"selected":""?>>N</option>
                </select>
                <button class="btn btn-primary" data-id="PASSWORD_INCLD_ALPHABET" type="button">Save</button>
            </div>
        </div>
        &nbsp; 
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Special Character</div>
        <div class="col-md-4">
            <div class="input-group">
                <select class='form-control editable' id="PASSWORD_INCLD_SPECIAL_CHAR">
                    <option value="Y" <?=@$general_setting["PASSWORD_INCLD_SPECIAL_CHAR"]=="Y"?"selected":""?>>Y</option>
                    <option value="N" <?=@$general_setting["PASSWORD_INCLD_SPECIAL_CHAR"]=="N"?"selected":""?>>N</option>
                </select>
                <button class="btn btn-primary" data-id="PASSWORD_INCLD_SPECIAL_CHAR" type="button">Save</button>
            </div>
        </div>
        &nbsp; 
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Case Sensitive</div>
        <div class="col-md-4">
            <div class="input-group">
                <select  class='form-control editable' id="PASSWORD_CASE_SENSITIVE">
                    <option value="Y" <?=@$general_setting["PASSWORD_CASE_SENSITIVE"]=="Y"?"selected":""?>>Y</option>
                    <option value="N" <?=@$general_setting["PASSWORD_CASE_SENSITIVE"]=="N"?"selected":""?>>N</option>
                </select>
                <button class="btn btn-primary" data-id="PASSWORD_CASE_SENSITIVE" type="button">Save</button>
            </div>
        </div>
        &nbsp; 
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Expire Duration</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="PASSWORD_EXPIRE_DURATION" value="<?=@$general_setting["PASSWORD_EXPIRE_DURATION"]?>">
                <button class="btn btn-primary" data-id="PASSWORD_EXPIRE_DURATION" type="button">Save</button>
            </div>
        </div>
        &nbsp; day(s)
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Max Failed Attempts</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="PASSWORD_MAX_FAILED_ATTEMPS" value="<?=@$general_setting["PASSWORD_MAX_FAILED_ATTEMPS"]?>">
                <button class="btn btn-primary" data-id="PASSWORD_MAX_FAILED_ATTEMPS" type="button">Save</button>
            </div>
        </div>
        &nbsp; attempt(s)
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Change at First Login</div>
        <div class="col-md-4">
            <div class="input-group">
                <select  class='form-control editable' id="PASSWORD_CHANGE_AT_FIRST_LOGIN">
                    <option value="Y" <?=@$general_setting["PASSWORD_CHANGE_AT_FIRST_LOGIN"]=="Y"?"selected":""?>>Y</option>
                    <option value="N" <?=@$general_setting["PASSWORD_CHANGE_AT_FIRST_LOGIN"]=="N"?"selected":""?>>N</option>
                </select>
                <button class="btn btn-primary" data-id="PASSWORD_CHANGE_AT_FIRST_LOGIN" type="button">Save</button>
            </div>
        </div>
        &nbsp; 
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Default Password</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="PASSWORD_DEFAULT" value="<?=@$general_setting["PASSWORD_DEFAULT"]?>">
                <button class="btn btn-primary" data-id="PASSWORD_DEFAULT" type="button">Save</button>
            </div>
        </div>
        &nbsp; attempt(s)
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Force Exit When Idle</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="FORCED_EXIT" value="<?=@$general_setting["FORCED_EXIT"]?>">
                <button class="btn btn-primary" data-id="FORCED_EXIT" type="button">Save</button>
            </div>
        </div>
        &nbsp; minute(s)
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Password History</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="pass-min-history" value="<?=@$general_setting["PASSWORD_HISTORY"]?>">
                <button class="btn btn-primary" data-id="pass-min-history" type="button">Save</button>
            </div>
        </div>
        &nbsp; time(s)
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Min. Not Login</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="min-days-not-login" value="<?=@$general_setting["REPORT_LAST_LOGIN"]?>">
                <button class="btn btn-primary" data-id="min-days-not-login" type="button">Save</button>
            </div>
        </div>
        &nbsp; day(s)
    </div>

</div>

<h4 class="blue smaller">
	MOBILE APP
</h4>
<div class="container my-4" >
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Idle Time</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="IDLE_TIME" value="<?=@$general_setting["IDLE_TIME"]?>">
                <button class="btn btn-primary" data-id="IDLE_TIME" type="button">Save</button>
            </div>
        </div>
        &nbsp; second(s)
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Interval Location</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="INTERVAL_LOCATION" value="<?=@$general_setting["INTERVAL_LOCATION"]?>">
                <button class="btn btn-primary" data-id="INTERVAL_LOCATION" type="button">Save</button>
            </div>
        </div>
        &nbsp; second(s)
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Min Login Time</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="time" class="form-control editable" id="LOGIN_TIME" value="<?=@$general_setting["LOGIN_TIME"]?>">
                <button class="btn btn-primary" data-id="LOGIN_TIME" type="button">Save</button>
            </div>
        </div>
        &nbsp; second(s)
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Min Logout Time</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="time" class="form-control editable" id="LOGOUT_TIME" value="<?=@$general_setting["LOGOUT_TIME"]?>">
                <button class="btn btn-primary" data-id="LOGOUT_TIME" type="button">Save</button>
            </div>
        </div>
        &nbsp; second(s)
    </div>
    
</div>

<script type="text/javascript">
	jQuery(function($) {
	

		var update_system_setting = function(id, value)
		{
			$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/update_system_setting/", { id: id, value: value }, function(responseText) {
				if(responseText.success) {
					showInfo("Data telah disimpan dan menunggu approval.");
				} else{
					showInfo("Gagal.");
				}
			}, "json");
		}	
		
		var update_mobcoll_setting = function(id, value)
		{
			$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/update_mobcoll_setting/", { id: id, value: value }, function(responseText) {
				if(responseText.success) {
					showInfo("Data telah disimpan dan menunggu approval.");
				} else{
					showInfo("Gagal.");
				}
			}, "json");
		}

        $(".btn").each(function () {
            $(this).click(function () {
                var id = this.dataset.id
                var value = $(`#${id}`).val();
                console.log(id, value, this.dataset.id, "eheheheheh")
                update_mobcoll_setting(id, value);
            });
        });
		

	});
</script>