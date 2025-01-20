var arr_id = "";

jQuery(function($) {
	var grid_selector = "#user_defined_status-list-grid-table";
	var pager_selector = "#user_defined_status-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/user_defined_status_list";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','SHORT DESCRIPTION','LONG DESCRIPTION','SYSTEM','PRIORITY','CREATED TIME'],
		colModel:[
			{name:'id',index:'a.id', width:200, hidden:false},
			{name:'short_description', index:'a.short_description', width:200},
			{name:'long_description', index:'a.long_description', width:300},
			{name:'system', index:'a.system', width:100},
			{name:'priority', index:'a.priority', width:100},
			{name:'created_time', index:'a.created_time', width:200},
		], 
		sortname: 'created_time',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		caption:"CUSTOM TABLE",
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
	$("#t_user_defined_status-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_user_defined_status-list-grid-table").append(
		"<button class='btn btn-sm btn-success' id='btn-user_defined_status-add'>ADD</button>&nbsp;"
		+"<button class='btn btn-sm btn-primary' id='btn-user_defined_status-edit'>EDIT</button>&nbsp;"
		+(GLOBAL_SESSION_VARS["LEVEL_GROUP"]!='ROOT'? "":"<button class='btn btn-sm btn-danger' id='btn-user_defined_status-delete'>DELETE</button>")
	);
	
	//Button Actions
	var showFormResponse = function(responseText, statusText)
	{
		if(responseText.success) {
			showInfo("Data telah disimpan");
			$(grid_selector).trigger('reloadGrid');
			$(grid_app_selector).trigger('reloadGrid');
		} else{
			showInfo(responseText.message);
		}
	}
	
	$("#btn-user_defined_status-add").click(function() {
		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {
					if(($("#txt-id").val()=="")||($("#txt-description").val()=="")){
						showWarning("Silakan mengisi field mandatory");
						return false;
					}					
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_user_defined_status_add/",
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
	
		showCommonDialog(900, 500, 'ADD user_defined_status TABLE', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/user_defined_status_edit_form/', buttons);	
	});
	
		$("#btn-user_defined_status-edit").click(function() {
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
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_user_defined_status_edit/",
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
			showCommonDialog(900, 500, 'EDIT user_defined_status TABLE', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/user_defined_status_edit_form/' + selr, buttons);	
		} else {
			showWarning("Silakan pilih data");
		}
	});
	
	$("#btn-user_defined_status-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		console.log('ini arr_id : '+ arr_id);
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan menghapus?", function(result) {
				if(result) {
					console.log('OK Deleted');
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_user_defined_status/", { id: arr_id,table_destination:table_destination }, function(responseText) {
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
	
	//txt-user_defined_status_office_id, txt-user_defined_status_office_name, opt_user_defined_status
	var jqformValidate = function(jqForm)
{	
	var passed = true;
	if(($("#txt-id").val()=="") || ($("#txt-short-description").val()=="") || ($("#txt-long-description").val()=="") || ($("#txt-system").val()=="") || ($("#txt-priority").val()=="")){
		passed = false;
	}
	if(!passed) {
		showWarning('Silakan mengisi field mandatory');
		jqformValidate.open();
	}
	return passed;
}
})