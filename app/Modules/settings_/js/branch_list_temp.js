var arr_id = "";
var arr_kk = "";

jQuery(function($) {
	var grid_selector = "#branch-list-grid-table";
	var pager_selector = "#branch-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/branch_list_temp";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','ID Branch','Branch Name', 'Province','City','District','Sub District','Address','Phone Number','Manager Name','Created Date','Created Status','Action'],
		colModel:[
			{name:'id',index:'a.id', width:60, hidden:true},
			{name:'branch_id', index:'a.branch_id', width:180},
			{name:'branch_name', index:'a.branch_name', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'branch_prov',index:'branch_prov', width:150, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
				    var data = $.parseJSON($.ajax({
						url:  'settings/getDataRow',
						dataType: "json", 
						data: "value=" +rowId,
						async: false
					}).responseText); 
					return data.item;
				}
			},
			{name:'branch_city', index:'a.branch_city', width:200, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
				    var data = $.parseJSON($.ajax({
						url:  'settings/getDataRow',
						dataType: "json", 
						data: "value=" +rowId,
						async: false
					}).responseText); 
					return data.item;
				}
			},
			{name:'branch_kec', index:'a.branch_kec', width:200, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
				    var data = $.parseJSON($.ajax({
						url:  'settings/getDataRow',
						dataType: "json", 
						data: "value=" +rowId,
						async: false
					}).responseText); 
					return data.item;
				}
			},
			{name:'branch_kel', index:'a.branch_kel', width:200, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
				    var data = $.parseJSON($.ajax({
						url:  'settings/getDataRow',
						dataType: "json", 
						data: "value=" +rowId,
						async: false
					}).responseText); 
					return data.item;
				}
			},
			{name:'branch_address', index:'a.branch_address', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'branch_no_telp', index:'a.branch_no_telp', width:180},
			{name:'branch_manager', index:'a.branch_manager', width:180, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
				    var data = $.parseJSON($.ajax({
						url:  'settings/getDataRowUser',
						dataType: "json", 
						data: "value=" +rowId,
						async: false
					}).responseText); 
					return data.item;
				}
			},
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
			{name:'status_approval', index:'a.status_approval', width:180},
			{name:'jenis_req', index:'a.jenis_req', width:180},
		], 
		sortname: 'branch_name',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		multiselect: false,
		onSelectRow: function(data){
			var arr = $(this).getRowData(data);
			arr_id = arr.id;
			arr_kk = arr.branch_id;
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
	
	
	$(".ui-jqgrid-bdiv").css("overflow","auto");
	//Add header
	$("#t_branch-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_branch-list-grid-table").append(
		//"<button class='btn btn-sm btn-success' id='btn-branch-add'>ADD</button>&nbsp;" +
		"<button class='btn btn-sm btn-primary' id='btn-branch-edit'>APPROVE</button>&nbsp;" +
		"<button class='btn btn-sm btn-danger' id='btn-branch-delete'>REJECT</button>"
	);
	
	//Button Actions
	var showFormResponse = function(responseText, statusText)
	{
		if(responseText.success) {
			showInfo("Input berhasil.");
			$(grid_selector).trigger('reloadGrid');
		} else{
			showInfo("Input gagal.");
		}
	}
	
	$("#btn-branch-add").click(function() {
/*		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {
					
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_branch_add/",
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
	
		showCommonDialog(900, 500, 'ADD branch GROUP', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/branch_add_form/', buttons);	
*/

	});
	
	$("#btn-branch-edit").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
/*			var buttons = {
				"success" :
				 {
					"label" : "<i class='icon-ok'></i> Apply",
					"className" : "btn-sm btn-success",
					"callback": function() {
					
						var options = {
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_branch_edit_temp/",
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
			showCommonDialog(900, 500, 'EDIT branch TEMPLATE', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/branchEditForm_temp/' + selr, buttons);	
*/
			bootbox.confirm("Do you want to approve this request?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_branch_edit_temp/", { id: arr_id, arr_kk : arr_kk }, function(responseText) {
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
			showWarning("Choose the data");
		}
	});

		$("#btn-branch_office-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Do you want to reject this request?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_branch_office/", { id: arr_id, arr_kk: arr_kk }, function(responseText) {
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
			showWarning("Choose the data");
		}
	});
	
		$("#btn-branch-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			/*
			var buttons = {
				"success" :
				 {
					"label" : "<i class='icon-ok'></i> Apply",
					"className" : "btn-sm btn-success",
					"callback": function() {
					
						var options = {
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_note_reject_branch/",
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
			showCommonDialog(900, 500, 'EDIT branch TEMPLATE', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/note_reject_branch/' + selr, buttons);	
			*/

			bootbox.confirm("Do you want to reject this request?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_note_reject_branch/", {arr_id: arr_id}, function(responseText) {
						if(responseText.success) {
							showInfo("Berhasil.");
							$(grid_selector).trigger('reloadGrid');
						} else{
							showInfo("Gagal.");
						}
					}, "json");
				}
			});

		} else {
			showWarning("Choose the data");
		}
	});
	
/*	
	$("#btn-branch-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Are you sure you want to delete this branch content?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_branch/", { id: selr }, function(responseText) {
						if(responseText.success) {
							showInfo("Berhasil.");
							$(grid_selector).trigger('reloadGrid');
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
*/
	
	//txt-branch_office_id, txt-branch_office_name, opt_branch
	var jqformValidate = function(jqForm)
{	
	var passed = true;
	if(($("#txt-branch-id").val()=="") || ($("#txt-branch-name").val()=="")){
		passed = false;
	}
	if(!passed) {
		showWarning('Please fill mandatory field');
		jqformValidate.open();
	}
	return passed;
}
})