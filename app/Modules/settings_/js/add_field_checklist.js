// Remove parent of 'remove' link when link is clicked.
$('#formAddCampaignField').on('click', '#remove_field', function (e) {
	e.preventDefault();
	$(this).parent().remove();
});
function remove_field(field) {
	// e.preventDefault();
	// alert(field);
	$("#remove-" + field).parent().parent().remove();
}

var addAttribute = function (id) {
	if ($('#optTypeField_' + id).val() == 'combo') {
		arr = '<div class="col-lg-2" id="idValueCombo_' + id + '">' +
			'<input type="text" class="form-control" name="txtAddValueCombo[' + id + ']" id="txtAddValueCombo" placeholder="separated with ;" data-required="true">' +
			'</div>' +
			'<div class="col-lg-1" id="idIsMultiple_' + id + '">' +
			'<select class="form-control" id="optIsMultiple_' + id + '" name="optIsMultiple[' + id + ']" >' +
			'<option value="">Single</option> ' +
			'<option value="multiple">Multiple</option> ' +
			'</select>' +
			'</div>';
		$('#addAttr_' + id).append(arr);
	} else {
		$('#idValueCombo_' + id).remove();
		$('#idIsMultiple_' + id).remove();
	}
}

function addFieldAsset(assetType) {
	inc = parseInt(inc) + 1;
	// console.log($('#opt-asset').val());
	data = '<div class="form-group">' +
		'<label for="txtCode" class="col-lg-2 control-label">PERTANYAAN</label>' +
		'<div class="col-lg-5">' +
		'<input type="text" class="form-control" name="txtFieldName[' + parseInt(inc) + ']" id="txtFieldName" placeholder="Field name" data-required="true">' +
		'</div>' +
		'<div class="col-lg-1">' +
		'<input type="text" class="form-control" name="txtFieldOrder[' + parseInt(inc) + ']" id="txtFieldOrder" placeholder="Sort">' +
		'</div>' +
		'<div class="col-lg-1">' +
		'<div id="addAttr_' + parseInt(inc) + '"></div>' +
		'<a class="btn btn-danger btn-sm pull-left" href="#" id="remove-' + parseInt(inc) + '" href="#" onClick="remove_field(' + parseInt(inc) + ')"><i class="fa fa-minus-circle fa-lg"></i></a>' +
		'</div>' +
		'</div>';
	$('#addField-' + assetType).append(data);
}


$(document).ready(function () {
	$('#btnAddField-' + $('#opt-asset').val()).click(function () {
		// alert('test');
		inc = parseInt(inc) + 1;
		console.log($('#opt-asset').val());
		data = '<div class="form-group">' +
			'<label for="txtCode" class="col-lg-2 control-label">PERTANYAAN</label>' +
			'<div class="col-lg-5">' +
			'<input type="text" class="form-control" name="txtFieldName[' + parseInt(inc) + ']" id="txtFieldName" placeholder="Field name" data-required="true">' +
			'</div>' +
			'<div class="col-lg-1">' +
			'<input type="text" class="form-control" name="txtFieldOrder[' + parseInt(inc) + ']" id="txtFieldOrder" placeholder="Sort">' +
			'</div>' +
			'<div class="col-lg-1">' +
			'<div id="addAttr_' + parseInt(inc) + '"></div>' +
			'<a class="btn btn-danger btn-sm pull-left" href="#" id="remove_field"><i class="fa fa-minus-circle fa-lg"></i></a>' +
			'</div>' +
			'</div>';
		$('#addField-' + $('#opt-asset').val()).append(data);
	});
});


$("#submit_checklist").click(function (e) {
	e.preventDefault();
	// form_add_submit(asset_);
})
function form_add_submit(form_add) {
	// event.preventDefault();
	form_add = "formAdd-" + form_add;
	var options = {
		url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/settings_ext/save_field_checklist/",
		data: { data: JSON.stringify($('#' + form_add).serialize()) },
		type: "post",
		//beforeSubmit: jqformValidate,
		success: function (msg) {
			//$(grid_selector).trigger('reloadGrid');	
			if (msg.success == true) {
				showInfo(msg.message);
				//$(grid_selector).trigger('reloadGrid');	
			} else {
				showInfo(msg.message);
			}
		},
		complete: function (data) {

		},
		dataType: 'json',
	};
	$('#' + form_add).ajaxSubmit(options);
}

var asset_prev = '';
$('#opt-asset').change(function () {
	// alert($(this).val());
	$('#formAdd-' + asset_prev).addClass('hide');
	$('#formAdd-' + $(this).val()).removeClass('hide');
	asset_prev = $(this).val();
});


// $('#formAddCampaignField').parsley( { listeners: {
	// onFieldValidate: function ( elem ) {
		// return false;
	// },
	// onFormSubmit: function ( isFormValid, event ) {
		// if(isFormValid)	{
			// if (confirm("Are you sure ?")){
				// var options = {
					// url : "ticket/add_field_ticket/save_field",
					// dataType: 'json',
					// success: function(responseText, statusText){
						// notify(responseText.message, responseText.status);
					// }
				// };
				// $("#formAddCampaignField").ajaxSubmit(options);
				// return false;
			// }else{
				// return false;
			// }
		// }

	// }
// }});

