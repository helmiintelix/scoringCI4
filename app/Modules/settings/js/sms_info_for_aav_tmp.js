jQuery(function($) {

	//list approval
	var grid_appr_selector = "#sms-appr-list-grid-table";
	var pager_appr_selector = "#sms-appr-list-grid-pager";
	
	var ci_appr_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/sms_info_list_temp";

	jQuery(grid_appr_selector).jqGrid({
		url: ci_appr_controller,
		datatype: "json",
		height: 340,
		width: "100%",
		colNames:['ID', 'NO.','SMS INFO', 'CONTENT','BUCKET'],
		colModel:[
			{name:'id',index:'id', width:60, hidden:true},
			{name:'list_number', index:'list_number', width:60, align:'right', sorttype:"int"},
			{name:'sms_info', index:'sms_info', width:200},
			{name:'sms_content', index:'sms_content', width:600, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'bucket_list', index:'bucket_list', width:200},
		], 
		sortname: 'info',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		caption:"WAITING APPROVAL",
		pager : pager_appr_selector,
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
	jQuery(grid_appr_selector).jqGrid('navGrid', pager_appr_selector,
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
	$("#t_sms-appr-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_sms-appr-list-grid-table").append(
		// "<button class='btn btn-sm btn-success' id='btn-sms-add'>Add</button>&nbsp;" +
		"<button class='btn btn-sm btn-primary' id='btn-sms-edit'>Approve</button>&nbsp;" +
		"<button class='btn btn-sm btn-danger' id='btn-sms-delete'>Reject</button>"
	);
	
	//Button Actions
	var showFormResponse = function(responseText, statusText)
	{
		if(responseText.success) {
			// showInfo("Berhasil.");
			showInfo("Data telah disimpan.");
			$(grid_appr_selector).trigger('reloadGrid');
		} else{
			showInfo("Gagal.");
		}
	}
	
	$("#btn-sms-add").click(function() {
		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {
					
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_sms_add/",
						type: "post",
						beforeSubmit: jqformValidate,
						success: showFormResponse,
						dataType: 'json',
					}; 
					$('form').ajaxSubmit(options);
				}
			},
			"button" :
			{
				"label" : "Close",
				"className" : "btn-sm"
			}
		}
	
		showCommonDialog(900, 500, 'Add SMS Template', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/sms_add_form/', buttons);	
	});
	
	$("#btn-sms-edit").click(function() {
		var selr = $(grid_appr_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan melakukan approval?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_sms_edit_temp/", { id: selr }, function(responseText) {
						if(responseText.success) {
							showInfo("Berhasil di approve.");
							$(grid_appr_selector).trigger('reloadGrid');
						} else{
							showInfo("Gagal.");
						}
					}, "json");
				}
			});	
		} else {
			showWarning("Silakan pilih data");
		}
	});
	
	$("#btn-sms-delete").click(function() {
		var selr = $(grid_appr_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan melakukan reject?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_sms_temp/", { id: selr }, function(responseText) {
						if(responseText.success) {
							showInfo("Berhasil di Reject.");
							$(grid_appr_selector).trigger('reloadGrid');
						} else{
							showInfo("Gagal.");
						}
					}, "json");
				}
			});
		} else {
			showWarning("Silakan pilih data");
		}
	});
})