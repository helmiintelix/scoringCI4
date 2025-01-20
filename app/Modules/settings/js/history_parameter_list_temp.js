var arr_id = "";
var arr_kk = "";

jQuery(function($) {
	var grid_app_selector = "#history_parameter-app-list-grid-table";
	var pager_app_selector = "#history_parameter-app-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/history_parameter_list_temp";

	jQuery(grid_app_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','Product','Bucket List', 'x times','x days','Call Result','x times','x days','Visit Result','x times','x days','TGL PENGAJUAN','STATUS PENGAJUAN','JENIS PENGAJUAN'],
		colModel:[
			{name:'id',index:'a.id', width:60, hidden:true},
			{name:'product', index:'a.product', width:180},
			{name:'bucket', index:'a.bucket', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'n_times_broken_ptp', index:'a.n_times_broken_ptp', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'n_days_broken_ptp', index:'a.n_days_broken_ptp', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'call_result_lov', index:'a.call_result_lov', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'n_times_call_result', index:'a.n_times_call_result', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'n_days_call_result', index:'a.n_days_call_result', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'visit_result_lov', index:'a.call_result_lov', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'n_times_visit_result', index:'a.n_times_call_result', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'n_days_visit_result', index:'a.n_days_call_result', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'created_time', index:'date(a.created_time)', width:200,searchoptions:
				{
					dataInit: function(el) {
						$(el).datepicker({
							changeYear: true,
							changeMonth: true,
							showButtonPanel: true,
							//dateFormat: 'yy-mm-dd',
							format: 'yyyy-mm-dd',
						}).on("changeDate",function(e){
                            setTimeout(function () {
                                $(grid_app_selector)[0].triggerToolbar();
                            }, 100);
							
						});
					}
				}
			},
			{name:'status_approval', index:'a.status_approval', width:180},
			{name:'jenis_req', index:'a.jenis_req', width:180},
		], 
		sortname: 'created_time',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_app_selector,
		altRows: true,
		multiselect: false,
		onSelectRow: function(data){
			var arr = $(this).getRowData(data);
			arr_id = arr.id;
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
	jQuery(grid_app_selector).jqGrid('filterToolbar',{defaultSearch:true,stringResult:true});			
	//navButtons
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
	$(".ui-jqgrid-bdiv").css("overflow","auto");
	//Add header
	$("#t_history_parameter-app-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_history_parameter-app-list-grid-table").append(
		//"<button class='btn btn-sm btn-success' id='btn-history_parameter-add'>ADD</button>&nbsp;" +
		"<button class='btn btn-sm btn-primary' id='btn-history_parameter-edit'>APPROVE</button>&nbsp;" +
		"<button class='btn btn-sm btn-danger' id='btn-history_parameter-delete'>REJECT</button>"
	);
	
	//Button Actions
	var showFormResponse = function(responseText, statusText)
	{
		if(responseText.success) {
			showInfo("Input berhasil.");
			$(grid_app_selector).trigger('reloadGrid');
		} else{
			showInfo("Input gagal.");
		}
	}
	
	$("#btn-history_parameter-edit").click(function() {
		var selr = $(grid_app_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan melakukan approval?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_history_parameter_edit_temp/", { id: arr_id }, function(responseText) {
						if(responseText.success) {
							showInfo("Berhasil.");
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

	
		$("#btn-history_parameter-delete").click(function() {
		var selr = $(grid_app_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan melakukan reject?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_note_reject_history_parameter/", {id: arr_id}, function(responseText) {
						if(responseText.success) {
							showInfo("Berhasil.");
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
	
	
	//txt-history_parameter_office_id, txt-history_parameter_office_name, opt_history_parameter
	var jqformValidate = function(jqForm)
{	
	var passed = true;
	if(($("#txt-history_parameter-id").val()=="") || ($("#txt-history_parameter-name").val()=="")){
		passed = false;
	}
	if(!passed) {
		showWarning('Silakan mengisi field mandatory');
		jqformValidate.open();
	}
	return passed;
}
})