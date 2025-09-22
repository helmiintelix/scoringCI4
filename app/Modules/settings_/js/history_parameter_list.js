var arr_id = "";

jQuery(function($) {
	var grid_selector = "#history_parameter-list-grid-table";
	var pager_selector = "#history_parameter-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/history_parameter_list";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','Product','Bucket List', 'x times','x days','Call Result','x times','x days','Visit Result','x times','x days', 'Created Time','Created By'],
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
                                $(grid_selector)[0].triggerToolbar();
                            }, 100);
							
						});
					}
				}
			},
			{name:'created_by', index:'a.created_by', width:180},
		], 
		sortname: 'created_time',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		caption:"HISTORY PARAMETER SETUP",
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
	
	jQuery(grid_selector).jqGrid('setGroupHeaders', {
	  useColSpanStyle: true,   
	  groupHeaders:[
		{startColumnName: 'n_times_broken_ptp', numberOfColumns: 2, titleText: '<center>BROKEN PROMISE</center>'},
		{startColumnName: 'call_result_lov', numberOfColumns: 3, titleText: '<center>CALL RESULT</center>'},
		{startColumnName: 'visit_result_lov', numberOfColumns: 3, titleText: '<center>VISIT RESULT</center>'},
	  ]
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
	
	$(".ui-jqgrid-bdiv").css("overflow","auto");
	//Add header
	$("#t_history_parameter-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_history_parameter-list-grid-table").append(
		"<button class='btn btn-sm btn-success' id='btn-history_parameter-add'>ADD</button>&nbsp;"
		+"<button class='btn btn-sm btn-primary' id='btn-history_parameter-edit'>EDIT</button>&nbsp;"
		+"<button class='btn btn-sm btn-danger' id='btn-history_parameter-delete'>DELETE</button>"
	);

	var grid_app_selector = "#history_parameter-appr-list-grid-table";
	var pager_app_selector = "#history_parameter-appr-list-grid-pager";
	var ci_app_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/history_parameter_list_temp";

	jQuery(grid_app_selector).jqGrid({
		url: ci_app_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','Product','Bucket List', 'x times','x days','Call Result','x times','x days','Visit Result','x times','x days','TGL PENGAJUAN','DIAJUKAN OLEH'],
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
                                $(grid_selector)[0].triggerToolbar();
                            }, 100);
							
						});
					}
				}
			},
			{name:'created_by', index:'a.created_by', width:180},
		], 
		sortname: 'created_time',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_app_selector,
		altRows: true,
		caption:"WAITING APPROVAL",
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
	
	jQuery(grid_app_selector).jqGrid('setGroupHeaders', {
	  useColSpanStyle: true,   
	  groupHeaders:[
		{startColumnName: 'n_times_broken_ptp', numberOfColumns: 2, titleText: '<center>BROKEN PROMISE</center>'},
		{startColumnName: 'call_result_lov', numberOfColumns: 3, titleText: '<center>CALL RESULT</center>'},
		{startColumnName: 'visit_result_lov', numberOfColumns: 3, titleText: '<center>VISIT RESULT</center>'},
	  ]
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
	$(".ui-jqgrid-bdiv").css("overflow","auto");
	
	$("#btn-history_parameter-add").click(function() {
		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {				
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_history_parameter_add/",
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
	
		showCommonDialog(900, 500, 'ADD HISTORY PARAMETER', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/history_parameter_add_form/', buttons);	
	});
	
	$("#btn-history_parameter-edit").click(function() {
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
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_history_parameter_edit/",
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
			showCommonDialog(900, 500, 'EDIT HISTORY PARAMETER', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/history_parameterEditForm/' + selr, buttons);	
		} else {
			showWarning("Silakan pilih data");
		}
	});
	
	$("#btn-history_parameter-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan menghapus?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_history_parameter/", { id: arr_id }, function(responseText) {
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
	
	//txt-kcu_office_id, txt-kcu_office_name, opt_kcu
	var jqformValidate = function(jqForm)
{	
	var passed = true;
	if(($("#opt-product").val()=="") || ($("#opt-bucket").val()=="") || ($("#txt-broken_promise-times").val()=="")|| ($("#txt-broken_promise-days").val()=="")|| ($("#opt-history_parameter-call_result").val()=="")|| ($("#txt-call_result-times").val()=="")|| ($("#txt-call_result-days").val()=="")|| ($("#opt-history_parameter-visit_result").val()=="")|| ($("#txt-visit_result-times").val()=="") || ($("#txt-visit_result-days").val()=="")){
		passed = false;
	}
	if(!passed) {
		showWarning('Silakan mengisi field mandatory');
		jqformValidate.open();
	}
	return passed;
}
})