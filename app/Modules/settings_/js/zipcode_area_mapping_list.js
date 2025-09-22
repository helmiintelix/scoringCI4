var arr_id = "";

jQuery(function($) {
	var grid_selector = "#zipcode_area_mapping-list-grid-table";
	var pager_selector = "#zipcode_area_mapping-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/zipcode_area_mapping_list";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','SUB AREA ID','SUB AREA NAME','AREA TAGIH', 'PRODUCT','ZIPCODE','CREATED_TIME','CREATED_BY'],
		colModel:[
			{name:'id',index:'a.id', width:60, hidden:true},
			{name:'sub_area_id', index:'a.sub_area_id', width:180},
			{name:'sub_area_name', index:'a.sub_area_name', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'area_tagih', index:'a.area_tagih', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'product', index:'a.product', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'zip_code', index:'a.zip_code', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
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
		sortname: 'sub_area_name',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		caption:"ZIPCODE AREA MAPPING",
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
	$("#t_zipcode_area_mapping-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_zipcode_area_mapping-list-grid-table").append(
		"<button class='btn btn-sm btn-success' id='btn-zipcode_area_mapping-add'>ADD</button>&nbsp;"
		+"<button class='btn btn-sm btn-primary' id='btn-zipcode_area_mapping-edit'>EDIT</button>&nbsp;"
		+(GLOBAL_SESSION_VARS["LEVEL_GROUP"]=='ROOT'? "":"<button class='btn btn-sm btn-danger' id='btn-zipcode_area_mapping-delete'>DELETE</button>")
	);

	var grid_app_selector = "#zipcode_area_mapping-appr-list-grid-table";
	var pager_app_selector = "#zipcode_area_mapping-appr-list-grid-pager";
	var ci_app_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/zipcode_area_mapping_list_temp";

	jQuery(grid_app_selector).jqGrid({
		url: ci_app_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','SUB AREA ID','SUB AREA NAME','AREA TAGIH', 'PRODUCT','ZIPCODE','CREATED_TIME','CREATED_BY'],
		colModel:[
			{name:'id',index:'a.id', width:60, hidden:true},
			{name:'sub_area_id', index:'a.sub_area_id', width:180},
			{name:'sub_area_name', index:'a.sub_area_name', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'area_tagih', index:'a.area_tagih', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'product', index:'a.product', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'zip_code', index:'a.zip_code', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
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
			arr_kk = arr.sub_area_id;
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
	
	$("#btn-zipcode_area_mapping-add").click(function() {
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
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_zipcode_area_mapping_add/",
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
	
		showCommonDialog(900, 500, 'ADD ZIPCODE AREA MAPPING', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/zipcode_area_mapping_add_form/', buttons);	
	});
	
		$("#btn-zipcode_area_mapping-edit").click(function() {
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
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_zipcode_area_mapping_edit/",
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
			showCommonDialog(900, 500, 'EDIT ZIPCODE AREA MAPPING', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/zipcode_area_mappingEditForm/' + selr, buttons);	
		} else {
			showWarning("Silakan pilih data");
		}
	});
	
	$("#btn-zipcode_area_mapping-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan menghapus?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_zipcode_area_mapping/", { id: arr_id }, function(responseText) {
						if(responseText.success) {
							showInfo("Berhasil."+arr_id);
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
	if(($("#txt-zipcode_area_mapping-sub_area_id").val()=="") || ($("#txt-zipcode_area_mapping-sub_area_name").val()=="") || ($("#opt-zipcode_area_mapping-area_tagih").val()=="") || ($("#opt-zipcode_area_mapping-product").val()=="") || ($("#txt-zipcode_area_mapping-zip_code").val()=="")){
		passed = false;
	}
	if(!passed) {
		showWarning('Silakan mengisi field mandatory');
		jqformValidate.open();
	}
	return passed;
}
})