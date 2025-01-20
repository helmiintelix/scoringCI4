jQuery(function($) {
	var grid_selector = "#letter-list-grid-table";
	var pager_selector = "#letter-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/settings_ext/ral_list_temp";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: "100%",
		colNames:['RAL ID', 'NO.','RAL Name','DPD','DPD TO', 'Asset Type', 'Content', 'Updated Date', 'Approval Status','ID'],
		colModel:[
			{name:'ral_id',index:'letter_id', width:60, hidden:true},
			{name:'list_number', index:'list_number', width:60, align:'right', sorttype:"int",hidden:true},
			{name:'info', index:'info', width:200},
			{name:'dpd_from', index:'dpd_from', width:200},
			{name:'dpd_to', index:'dpd_to', width:200,hidden:true},
			{name:'asset', index:'asset', width:100},
			{name:'content', index:'content', width:600, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'updated_time', index:'updated_time', width:200},
			{name:'flag_tmp', index:'flag_tmp', width:200},
			{name:'id',index:'id', width:60, hidden:true},
			
		], 
		sortname: 'info',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		multiselect: false,
		toolbar: [true, "top"], 
		loadComplete : function() {
			var table = this;
			setTimeout(function(){
				updateActionIcons(table);
				updatePagerIcons(table);
				enableTooltips(table);
			}, 0);
		},
		autowidth: true,
		shrinkToFit: false
	});
	
	//navButtons
	jQuery(grid_selector).jqGrid('navGrid', pager_selector,
		{ 	//navbar options
			edit: false,
			editicon : 'icon-pencil blue',
			add: false,
			addicon : 'icon-plus-sign purple',
			del: false,
			delicon : 'icon-trash red',
			search: true,
			searchicon : 'icon-search orange',
			refresh: true,
			refreshicon : 'icon-refresh green',
			view: false,
			viewicon : 'icon-zoom-in grey',
		},
		{
			//search form
			recreateForm: true,
			afterShowSearch: function(e){
				var form = $(e[0]);
				form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
				style_search_form(form);
			},
			afterRedraw: function(){
				style_search_filters($(this));
			}
			,
			multipleSearch: true,
			/**
			multipleGroup:true,
			showQuery: true
			*/
		}
	)
	
	//Add header
	$("#t_letter-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_letter-list-grid-table").append(
//		"<button class='btn btn-sm btn-success' id='btn-letter-add'>ADD</button>&nbsp;" +
		"<button class='btn btn-sm btn-primary' id='btn-letter-edit'>APPROVE</button>&nbsp;" +
		"<button class='btn btn-sm btn-danger' id='btn-letter-delete'>REJECT</button>"
	);
	
	//Button Actions
	var showFormResponse = function(responseText, statusText)
	{
		if(responseText.success) {
			showInfo("Berhasil.");
			$(grid_selector).trigger('reloadGrid');
		} else{
			showInfo("Gagal.");
		}
	}
	
	
	$("#btn-letter-edit").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{

			bootbox.confirm("Do you want to approve this request?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/settings_ext/save_ral_edit_temp/", { id: selr }, function(responseText) {
						if(responseText.success) {
							showInfo("Success.");
							$(grid_selector).trigger('reloadGrid');
						} else{
							showInfo("Failed.");
						}
					}, "json");
				}
			});

		} else {
			showWarning("Silakan pilih data");
		}


	});

	$("#btn-letter-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Do you want to reject this request?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/settings_ext/delete_ral_edit_temp/", { id: selr }, function(responseText) {
						if(responseText.success) {
							showInfo("Success.");
							$(grid_selector).trigger('reloadGrid');
						} else{
							showInfo("Failed.");
						}
					}, "json");
				}
			});

		} else {
			showWarning("Silakan pilih data");
		}
	});
	
	var jqformValidate = function(jqForm)
{
	var passed = true;
	if(($("#txt-letter-info").val()=="") || ($("#txt-letter-dpd-from").val()=="") || ($("#txt-letter-dpd-to").val()=="") || ($("#opt_field_list").val()=="")){
		passed = false;
	}
	if(!passed) {
		showWarning('Please fill the mandatory field');
		jqformValidate.open();
	}
	return passed;
}
	
})