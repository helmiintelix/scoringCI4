var GLOBAL_MAIN_VARS = new Array();
GLOBAL_MAIN_VARS["SPINNER"] = "<img src='assets/img/select2-spinner.gif'/>";
var loadMenu = function(name, link){
	$("#screen-title").text("| "+name);
	$("#main-wrapper").html(GLOBAL_MAIN_VARS["SPINNER"]).fadeOut('500').load(link).fadeIn('800');		
}
var jqform_validate = function (formData, jqForm, options) {
	var pass = true;
	
	$('.mandatory', $(jqForm)).each(function() {
		if (!$(this).val()) {
			$(this).parent().addClass("error-state");
			$(this).addClass("mandatory_invalid");
			pass = false;
		} else {
			$(this).parent().removeClass("error-state");
			$(this).removeClass("mandatory_invalid");
			$('button:contains(Save)', $(jqForm)).each(function(){
				
			});
		}
	});

	if(!pass) {
		alert('Please enter a value for mandatory fields');
		setTimeout(function(){
			$('button:contains(loading...)').removeClass('disabled','').removeAttr('disabled','').html('Save  <i class="icon-floppy bg-lightBlue"></i>');
			$('button:contains(Update Loading...)').removeClass('disabled','').removeAttr('disabled','').html('Update  <i class="icon-pencil bg-lightBlue"></i>');
		},2000);
	}else{
		$('button:contains(Save)', $(jqForm)).each(function(){
			$(this).button('loading');
		});
		setTimeout(function(){
			$('button:contains(loading...)').removeClass('disabled','').removeAttr('disabled','').html('Save  <i class="icon-floppy bg-lightBlue"></i>');
			$('button:contains(Update Loading...)').removeClass('disabled','').removeAttr('disabled','').html('Update  <i class="icon-pencil bg-lightBlue"></i>');
		},3000);
	}
	return pass;
}
$(document).ready(function() {
	$("#menu").menu();
	$("#screen-title").text("| Dashboard");
	$("#main-wrapper").html(GLOBAL_MAIN_VARS["SPINNER"]).load("dashboard/overview_monitoring").fadeIn('1000');
	$('.date-picker').datepicker({autoclose:true}).next().on(ace.click_event, function(){
		$(this).prev().focus();
	});
});

function RefreshTable(tableId, urlData)
{
	$.getJSON(urlData, null, function( json )
	{
		table = $(tableId).dataTable();
		oSettings = table.fnSettings();
		table.fnClearTable(this);
		for (var i=0; i<json.aaData.length; i++)
		{
			table.oApi._fnAddData(oSettings, json.aaData[i]);
		}
		oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
		table.fnDraw();
	});
}
