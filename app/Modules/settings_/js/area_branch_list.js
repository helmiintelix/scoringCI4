var arr_id = "";

jQuery(function($) {
	var grid_selector = "#area_branch-list-grid-table";
	var pager_selector = "#area_branch-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/area_branch_list";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','AREA ID','NAMA AREA', 'PROVINSI AREA','KOTA AREA','KECAMATAN AREA','KELURAHAN AREA','BRANCH_LIST', 'ALAMAT AREA','NO.TELP AREA','MANAGER AREA','CREATED_TIME','CREATED_BY'],
		colModel:[
			{name:'id',index:'a.id', width:60, hidden:true},
			{name:'area_id', index:'a.area_id', width:180},
			{name:'area_name', index:'a.area_name', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'area_prov', index:'a.area_prov', width:200, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
					if(rowId){
						var data = $.parseJSON($.ajax({
							url:  'settings/getDataRow',
							dataType: "json", 
							data: "value=" +rowId,
							async: false
						}).responseText); 
						return data.item;
					}else{
						return '';
					}
				}
			},
			{name:'area_city', index:'a.area_city', width:200, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
					if(rowId){
						var data = $.parseJSON($.ajax({
							url:  'settings/getDataRow',
							dataType: "json", 
							data: "value=" +rowId,
							async: false
						}).responseText); 
						return data.item;
					}else{
						return '';
					}
				}
			},
			{name:'area_kec', index:'a.area_kec', width:200, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
					if(rowId){
						var data = $.parseJSON($.ajax({
							url:  'settings/getDataRow',
							dataType: "json", 
							data: "value=" +rowId,
							async: false
						}).responseText); 
						return data.item;
					}else{
						return '';
					}
				}
			},
			{name:'area_kel', index:'a.area_kel', width:200, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
					if(rowId){
						var data = $.parseJSON($.ajax({
							url:  'settings/getDataRow',
							dataType: "json", 
							data: "value=" +rowId,
							async: false
						}).responseText); 
						return data.item;
					}else{
						return '';
					}
				}
			},
			{name:'branch_list', index:'a.branch_list', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'area_address', index:'a.area_address', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'area_no_telp', index:'a.area_no_telp', width:180},
			{name:'area_manager', index:'a.area_manager', width:180, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
					/*if(rowId){
						var data = $.parseJSON($.ajax({
							url:  'settings/getDataRowUser',
							dataType: "json", 
							data: "value=" +rowId,
							async: false
						}).responseText); 
						return data.item;
					}else{*/
						return '';
					//}
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
			{name:'created_by', index:'a.created_by', width:180},
		], 
		sortname: 'area_name',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		caption:"AREA BRANCH",
		multiselect: false,
		onSelectRow: function(data){
			var arr = $(this).getRowData(data);
			arr_id = arr.area_id;
			id = arr.id;
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
	$("#t_area_branch-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_area_branch-list-grid-table").append(
		"<button class='btn btn-sm btn-success' id='btn-area_branch-add'>ADD</button>&nbsp;"
		+"<button class='btn btn-sm btn-primary' id='btn-area_branch-edit'>EDIT</button>&nbsp;"
		+"&nbsp;<button class='btn btn-sm btn-warning' id='btn-export-xls'>EXPORT TO XLS</button>"
		// +"<button class='btn btn-sm btn-danger' id='btn-area_branch-delete'>DELETE</button>"
	);

	var grid_app_selector = "#area_branch-appr-list-grid-table";
	var pager_app_selector = "#area_branch-appr-list-grid-pager";
	var ci_app_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/area_branch_list_temp";

	jQuery(grid_app_selector).jqGrid({
		url: ci_app_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','AREA ID','NAMA AREA', 'PROVINSI AREA','KOTA AREA','KECAMATAN AREA','KELURAHAN AREA','BRANCH_LIST', 'ALAMAT AREA','NO.TELP AREA','MANAGER AREA','TGL PENGAJUAN','DIAJUKAN OLEH'],
		colModel:[
			{name:'id',index:'a.id', width:60, hidden:true},
			{name:'area_id', index:'a.area_branch_id', width:180},
			{name:'area_name', index:'a.area_name', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'area_prov', index:'a.area_prov', width:200, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
					if(rowId){
						var data = $.parseJSON($.ajax({
							url:  'settings/getDataRow',
							dataType: "json", 
							data: "value=" +rowId,
							async: false
						}).responseText); 
						return data.item;
					}else{
						return '';
					}
				}
			},
			{name:'area_city', index:'a.area_city', width:200, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
					if(rowId){
						var data = $.parseJSON($.ajax({
							url:  'settings/getDataRow',
							dataType: "json", 
							data: "value=" +rowId,
							async: false
						}).responseText); 
						return data.item;
					}else{
						return '';
					}
				}
			},
			{name:'area_kec', index:'a.area_kec', width:200, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
					if(rowId){
						var data = $.parseJSON($.ajax({
							url:  'settings/getDataRow',
							dataType: "json", 
							data: "value=" +rowId,
							async: false
						}).responseText); 
						return data.item;
					}else{
						return '';
					}
				}
			},
			{name:'area_kel', index:'a.area_kel', width:200, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
					if(rowId){
						var data = $.parseJSON($.ajax({
							url:  'settings/getDataRow',
							dataType: "json", 
							data: "value=" +rowId,
							async: false
						}).responseText); 
						return data.item;
					}else{
						return '';
					}
				}
			},
			{name:'branch_list', index:'a.branch_list', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'area_address', index:'a.area_address', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'area_no_telp', index:'a.area_no_telp', width:180},
			{name:'area_manager', index:'a.area_manager', width:180, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
					if(rowId){
						var data = $.parseJSON($.ajax({
							url:  'settings/getDataRowUser',
							dataType: "json", 
							data: "value=" +rowId,
							async: false
						}).responseText); 
						return data.item;
					}else{
						return '';
					}
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
			arr_kk = arr.area_branch_id;
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
	
	$("#btn-area_branch-add").click(function() {
		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {
					/* if(($("#opt-bisnis-unit").val()=="")||($("#txt-kcu-name").val()=="")||($("#txt-kcu-id").val()=="")){
						showWarning("Silakan mengisi field mandatory");
						return false;
					} */					
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_area_branch_add/",
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
	
		showCommonDialog(900, 500, 'ADD AREA BRANCH', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/area_branch_add_form/', buttons);	
	});
	
		$("#btn-area_branch-edit").click(function() {
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
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_area_branch_edit/",
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
			showCommonDialog(900, 500, 'EDIT AREA BRANCH', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/area_branchEditForm/' + selr, buttons);	
		} else {
			showWarning("Silakan pilih data");
		}
	});
	
	$("#btn-area_branch-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan menghapus?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_area_branch/", { id:id }, function(responseText) {
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
		if(($("#txt-area_branch-id").val()=="") || ($("#txt-area_branch-name").val()=="") || ($("#opt-area_branch-province").val()=="") || ($("#opt-area_branch-city").val()=="") || ($("#opt-area_branch-kecamatan").val()=="") || ($("#opt-area_branch-kelurahan").val()=="") || ($("#txt-area_branch-address").val()=="") || ($("#txt-area_branch-telp").val()=="") || ($("#opt-area_branch-manager").val()=="")){
			passed = false;
		}
		if(!passed) {
			showWarning('Silakan mengisi field mandatory');
			jqformValidate.open();
		}
		return passed;
	}

	$("#btn-export-xls").click(function(){
		location.href = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/get_export_list_branch_area";
	});
})