var arr_id = "";
var arr_kk = "";

jQuery(function($) {
	var grid_selector = "#kpi-list-grid-table";
	var pager_selector = "#kpi-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/kpi_list_temp";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','KPI ID','KPI LABEL', 'PRODUCT','BUCKET LIST','NUMBER OF VISIT','NUMBER OF PTP','% PTP','NUMBER KEEP PTP','AMOUNT KEEP PTP','% KEEP PTP','AMOUNT COLLECT','% FLOW RATE','TGL PENGAJUAN','STATUS PENGAJUAN','JENIS PENGAJUAN'],
		colModel:[
				{name:'id',index:'a.id', width:60, hidden:true},
			{name:'kpi_id', index:'a.kpi_id', width:180},
			{name:'kpi_label', index:'a.kpi_label', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'product', index:'a.product', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'bucket_list', index:'a.bucket_list', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'number_of_visit', index:'a.number_of_visit', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'number_of_ptp', index:'a.number_of_ptp', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'%_ptp', index:'a.%_ptp', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'number_keep_ptp', index:'a.number_keep_ptp', width:180},
			{name:'amount_keep_ptp', index:'a.amount_keep_ptp', width:180},
			{name:'%_keep_ptp', index:'a.%_keep_ptp', width:180},
			{name:'amount_collect', index:'a.amount_collect', width:180},
			{name:'%_flow_rate', index:'a.%_flow_rate', width:180},
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
		sortname: 'kpi_label',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		multiselect: false,
		onSelectRow: function(data){
			var arr = $(this).getRowData(data);
			arr_id = arr.id;
			arr_kk = arr.kpi_id;
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
	$("#t_kpi-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_kpi-list-grid-table").append(
		//"<button class='btn btn-sm btn-success' id='btn-kpi-add'>ADD</button>&nbsp;" +
		"<button class='btn btn-sm btn-primary' id='btn-kpi-edit'>APPROVE</button>&nbsp;" +
		"<button class='btn btn-sm btn-danger' id='btn-kpi-delete'>REJECT</button>"
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
	

	
	$("#btn-kpi-edit").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan melakukan approval?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_kpi_edit_temp/", { id: arr_id, arr_kk : arr_kk }, function(responseText) {
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

	
		$("#btn-kpi-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan melakukan reject?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_note_reject_kpi/", {id: arr_id}, function(responseText) {
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
	
/*	
	$("#btn-kpi-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Are you sure you want to delete this kpi content?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_kpi/", { id: selr }, function(responseText) {
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
	
	//txt-kpi_office_id, txt-kpi_office_name, opt_kpi
	var jqformValidate = function(jqForm)
{	
	var passed = true;
	if(($("#txt-kpi-id").val()=="") || ($("#txt-kpi-name").val()=="")){
		passed = false;
	}
	if(!passed) {
		showWarning('Silakan mengisi field mandatory');
		jqformValidate.open();
	}
	return passed;
}
})