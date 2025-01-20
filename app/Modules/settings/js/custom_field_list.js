var arr_id = "";

jQuery(function($) {
	var grid_selector = "#custom-field-list-grid-table";
	var pager_selector = "#custom-field-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/custom_field_list";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['FIELD NAME','FIELD LABEL', 'FIELD TYPE','FIELD LENGTH','TABLE','DESCRIPTION','STATUS APPROVAL'],
		colModel:[
			{name:'field_name',index:'a.field_name', width:200, hidden:false},
			{name:'field_label', index:'a.field_label', width:180},
			{name:'field_type', index:'a.field_type', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'field_length', index:'a.field_length', width:300,cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'table_destination', index:'a.table_destination', width:180},
			{name:'description', index:'a.description', width:200},
			{name:'flag_tmp', index:'a.flag_tmp', width:130,stype:'select',
				searchoptions:
				{
					value: ':ALL;0:WAITING APPROVAL;1:APPROVED;2:REJECTED',
					separator: ':',
					delimiter: ';'
				}},
		], 
		sortname: 'field_name',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		caption:"CUSTOM FIELD",
		multiselect: false,
		onSelectRow: function(data){
			var arr = $(this).getRowData(data);
			console.log(arr);
			arr_id = arr.field_name;
			table_destination = arr.table_destination;
			console.log('arr_id bro '+arr_id);
		},
		toolbar: [true, "top"], 
		loadComplete : function() {
			var table = this;
			setTimeout(function(){
				updateActionIcons(table);
				updatePagerIcons(table);
				enableTooltips(table);
			}, 0);
		},
		autowidth: false,
		shrinkToFit: false
	});
	
jQuery(grid_selector).jqGrid('filterToolbar',{defaultSearch:true,stringResult:true});		
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
	$("#t_custom-field-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_custom-field-list-grid-table").append(
		"<button class='btn btn-sm btn-success' id='btn-custom-field-add'>ADD</button>&nbsp;"
		+"<button class='btn btn-sm btn-primary' id='btn-custom-field-edit'>EDIT</button>&nbsp;"
		+(GLOBAL_SESSION_VARS["LEVEL_GROUP"]!='ROOT'? "":"<button class='btn btn-sm btn-danger' id='btn-custom-field-delete'>DELETE</button>")
	);

	var grid_app_selector = "#custom-field-appr-list-grid-table";
	var pager_app_selector = "#custom-field-appr-list-grid-pager";
	var ci_app_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/custom_field_list_temp";

	jQuery(grid_app_selector).jqGrid({
		url: ci_app_controller,
		datatype: "json",
		height: 340,
		width: null,
	colNames:['ACTION','FIELD NAME','FIELD LABEL', 'FIELD TYPE','FIELD LENGTH','TABLE','DESCRIPTION','STATUS APPROVAL'],
		colModel:[
			{name:'action', index:'action', width:200},
			{name:'field_name',index:'a.field_name', width:200, hidden:false},
			{name:'field_label', index:'a.field_label', width:180},
			{name:'field_type', index:'a.field_type', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'field_length', index:'a.field_length', width:300,cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'table_destination', index:'a.table_destination', width:180},
			{name:'description', index:'a.description', width:200},
			{name:'flag_tmp', index:'a.flag_tmp', width:130,stype:'select',
				searchoptions:
				{
					value: ':ALL;0:WAITING APPROVAL;1:APPROVED;2:REJECTED',
					separator: ':',
					delimiter: ';'
				}},
		], 
		sortname: 'field_name',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_app_selector,
		altRows: true,
		caption:"WAITING APPROVAL",
		multiselect: false,
		onSelectRow: function(data){
			var arr = $(this).getRowData(data);
			// arr_id = arr.id;
			// arr_kk = arr.custom-field_id;
			// table_destination = arr.table_destination;
		},
		toolbar: [true, "top"], 
		loadComplete : function() {
			var table = this;
			setTimeout(function(){
				updateActionIcons(table);
				updatePagerIcons(table);
				enableTooltips(table);
			}, 0);
		},
		autowidth: false,
		shrinkToFit: false
	});
	
	//navButtons
	jQuery(grid_app_selector).jqGrid('filterToolbar',{defaultSearch:true,stringResult:true});		
	jQuery(grid_app_selector).jqGrid('navGrid', pager_app_selector,
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
	
	//Button Actions
	var showFormResponse = function(responseText, statusText)
	{
		if(responseText.success) {
			showInfo("Data telah disimpan dan menunggu approval");
			$(grid_selector).trigger('reloadGrid');
			$(grid_app_selector).trigger('reloadGrid');
		} else{
			showInfo(responseText.message);
		}
	}
	
	$("#btn-custom-field-add").click(function() {
		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {
					if(($("#opt-field-type").val()=="")||($("#opt-table-destination").val()=="")||($("#txt-field-name").val()=="")||($("#txt-field-length").val()=="")||($("#txt-field-label").val()=="")){
						showWarning("Silakan mengisi field mandatory");
						return false;
					}					
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_custom_field_add/",
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
	
		showCommonDialog(900, 500, 'ADD CUSTOM FIELD', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/custom_field_add_form/', buttons);	
	});
	
		$("#btn-custom-field-edit").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			var buttons = {
				"success" :
				 {
					"label" : "<i class='icon-ok'></i> Save",
					"className" : "btn-sm btn-success",
					"callback": function() {
					
						var options = {
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_custom_field_edit/",
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
			showCommonDialog(900, 500, 'EDIT CUUSTOM FIELD', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/customfieldEditForm/' + selr, buttons);	
		} else {
			showWarning("Silakan pilih data");
		}
	});
	
	$("#btn-custom-field-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		console.log('ini arr_id : '+ arr_id);
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan menghapus?", function(result) {
				if(result) {
					console.log('OK Deleted');
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_custom_field/", { id: arr_id,table_destination:table_destination }, function(responseText) {
						if(responseText.success) {
							showInfo("Berhasil.");
							$(grid_selector).trigger('reloadGrid');
							$(grid_app_selector).trigger('reloadGrid');
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
	
	//txt-custom-field_office_id, txt-custom-field_office_name, opt_custom-field
	var jqformValidate = function(jqForm)
{	
	var passed = true;
	if(($("#txt-custom-field-id").val()=="") || ($("#txt-custom-field-name").val()=="")){
		passed = false;
	}
	if(!passed) {
		showWarning('Silakan mengisi field mandatory');
		jqformValidate.open();
	}
	return passed;
}
})