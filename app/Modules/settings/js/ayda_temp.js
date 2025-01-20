var arr_no_rekening = "";
var arr_id = "";
var arr_pokok = "";
var arr_bunga = "";
var arr_denda = "";

jQuery(function($) {
	var grid_selector = "#area-list-grid-table";
	var pager_selector = "#area-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/ayda_list_temp";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['COLLECTION CENTER','KCU','AREA','PETUGAS','NO CIF','CREDIT LINE', 'NO AKSEP','NAMA DEBITUR','NO AKSEP','POKOK','BUNGA','DENDA', 'NAMA PETUGAS', 'TIME STATUS','TGL BAYAR','STATUS AKTIF'],
		colModel:[
			{name:'group_kcu', index:'kcu_group.kcu_id', width:170},
			{name:'kcu', index:'kcu.kcu_id', width:170},
			{name:'assigned_area', index:'assigned_area', width:200,hidden:GLOBAL_MAIN_VARS["ASSIGNMENT_TYPE"]=="NON_OWNERSHIP"?false:true},
			{name:'user_id', index:'b.user_id', width:150},
			{name:'no_cif', index:'cust.no_cif', width:110},			
			{name:'credit_line_number', index:'cust.credit_line_number', width:100},
			{name:'no_rekening', index:'cust.no_rekening', width:110},
			{name:'first_name', index:'first_name', width:260},
			{name:'no_rekening',index:'cust.no_rekening', width:120},
			{name:'pokok', index:'b.pokok', width:160,formatter:"integer",align:"right"},
			{name:'bunga', index:'b.bunga', width:160,formatter:"integer",align:"right"},
			{name:'denda', index:'b.denda', width:160,formatter:"integer",align:"right"},
			{name:'nama_petugas', index:'a.name', width:200},
			{name:'created_time',index:'b.created_time', width:120},
			{name:'tgl_bayar',index:'tgl_bayar', width:120},
			{name:'flag', index:'b.flag', width:200}

		], 
		sortname: 'b.created_time',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		multiselect: false,
		onSelectRow: function(data){
			var arr = $(this).getRowData(data);
			arr_no_rekening = arr.no_rekening;
			arr_id = arr.id;
			arr_pokok = arr.pokok;
			arr_bunga = arr.bunga;
			arr_denda = arr.denda;
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
	$("#t_area-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_area-list-grid-table").append(
		//"<button class='btn btn-sm btn-success' id='btn-area-add'>ADD</button>&nbsp;" +
		"<button class='btn btn-sm btn-primary' id='btn-area-edit'>APPROVE</button>&nbsp;" +
		"<button class='btn btn-sm btn-danger' id='btn-area-delete'>REJECT</button>"
	);
	
	//Button Actions
	var showFormResponse = function(responseText, statusText)
	{
		if(responseText.success) {
			showInfo("Pemberian ijin berhasil");
			$(grid_selector).trigger('reloadGrid');
		} else{
			showInfo("Gagal.");
		}
	}
	
	$("#btn-area-add").click(function() {
		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {
					
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/area_add/",
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
	
		showCommonDialog(900, 500, 'ADD AREA TEMPLATE', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/area_add_form/', buttons);	
	});			

	$("#btn-area-edit").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan melakukan approval?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_edit_flag_ayda/", { id: selr, norekening: arr_no_rekening, pokok: arr_pokok, bunga: arr_bunga, denda: arr_denda }, function(responseText) {
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
	
	$("#btn-area-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan melakukan reject?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_ayda/", { id: selr }, function(responseText) {
						if(responseText.success) {
							showInfo("Data telah ditolak");
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
	
	//txt-kcu_office_id, txt-kcu_office_name, opt_kcu
	var jqformValidate = function(jqForm)
{	
	var passed = true;
	if(($("#txt-area_id").val()=="") || ($("#txt-area_name").val()=="") || ($("#kcu_list").val()=="")){
		passed = false;
	}
	if(!passed) {
		showWarning('Silakan mengisi field mandatory');
	}
	return passed;
}

})