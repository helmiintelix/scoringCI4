<style>
	.no-search .select2-search {
		display:none
	}	
</style>
<h4 class="blue">Setup Alert</h4>
<!-- <i>*setup alert untuk warning pada menu monitoring agent jika agent melakukan aktifitas melebihi durasi yang di setup</i> -->
<div class="container my-4">
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Break</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="aux-break" value="<?=@$aux_max_time["BREAK"]?>">
                <button class="btn btn-primary" id="save-button-break" type="button">Save</button>
            </div>
        </div>
        &nbsp; minute(s)
    </div>

    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Praying</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="aux-praying" value="<?=@$aux_max_time["PRAYING"]?>">
                <button class="btn btn-primary" id="save-button-praying" type="button">Save</button>
            </div>
        </div>
        &nbsp; minute(s)
    </div>

    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Toilet</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="aux-toilet" value="<?=@$aux_max_time["TOILET"]?>">
                <button class="btn btn-primary" id="save-button-toilet" type="button">Save</button>
            </div>
        </div>
        &nbsp; minute(s)
    </div>

    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Coaching</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="aux-coaching" value="<?=@$aux_max_time["COACHING"]?>">
                <button class="btn btn-primary" id="save-button-coaching" type="button">Save</button>
            </div>
        </div>
        &nbsp; minute(s)
    </div>

    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Training</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="aux-training" value="<?=@$aux_max_time["TRAINING"]?>">
                <button class="btn btn-primary" id="save-button-trining" type="button">Save</button>
            </div>
        </div>
        &nbsp; minute(s)
    </div>

    <div class="row mb-3">
        <div class="col-md-3 fw-bold">ACW (After Call Work)</div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control editable" id="acw" value="<?=@$aux_max_time["ACW"]?>">
                <button class="btn btn-primary" id="save-button-acw" type="button">Save</button>
            </div>
        </div>
        &nbsp; minute(s)
    </div>



<script type="text/javascript">
	$(document).ready(()=>{

    })

    $("#save-button-break").click(()=>{
        var options = {
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/setupAux/updateData",
            type: "post",
            // beforeSubmit: jqformValidate,
            success: showFormResponse,
            dataType: 'json',
            data:{param:'BREAK',value:$('#aux-break').val()}
        };

        $.ajax(options);
    })
    $("#save-button-praying").click(()=>{
        var options = {
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/setupAux/updateData",
            type: "post",
            // beforeSubmit: jqformValidate,
            success: showFormResponse,
            dataType: 'json',
            data:{param:'PRAYING',value:$('#aux-praying').val()}
        };

        $.ajax(options);
    })
    
    $("#save-button-toilet").click(()=>{
        var options = {
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/setupAux/updateData",
            type: "post",
            // beforeSubmit: jqformValidate,
            success: showFormResponse,
            dataType: 'json',
            data:{param:'TOILET',value:$('#aux-toilet').val()}
        };

        $.ajax(options);
    })

    $("#save-button-training").click(()=>{
        var options = {
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/setupAux/updateData",
            type: "post",
            // beforeSubmit: jqformValidate,
            success: showFormResponse,
            dataType: 'json',
            data:{param:'TRAINING',value:$('#aux-training').val()}
        };

        $.ajax(options);
    })

    $("#save-button-acw").click(()=>{
        var options = {
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/setupAux/updateData",
            type: "post",
            // beforeSubmit: jqformValidate,
            success: showFormResponse,
            dataType: 'json',
            data:{param:'ACW',value:$('#acw').val()}
        };

        $.ajax(options);
    })

    var showFormResponse = (msg)=>{
        showInfo(msg.message)
    }
</script>