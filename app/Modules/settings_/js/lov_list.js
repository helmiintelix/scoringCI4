var arr_id = "";

jQuery(function($) {
	
	
	var grid_selector_registration = "#lov-registration-grid-table";
	var pager_selector_registration = "#lov-registration-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/lov_registration";

	jQuery(grid_selector_registration).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 140,
		width: null,
		colNames:['ID LoV Category','LoV Description','Active'],
		colModel:[ 
			{name:'id',index:'a.id', width:160},
			{name:'category_name', index:'a.category_name', width:180},
			{name:'is_active',index:'a.is_active', width:160, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
				    if(rowId=='1'){
						return 'Yes';
					}else{
						return 'No';
					}
				}
			},
		], 
		sortname: 'id',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector_registration,
		altRows: true,
		caption:"LOV CATEGORY REGISTRATION",
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
	
jQuery(grid_selector_registration).jqGrid('filterToolbar',{defaultSearch:true,stringResult:true});		
	//navButtons
	jQuery(grid_selector_registration).jqGrid('navGrid', pager_selector_registration,
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
	$("#t_lov-registration-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_lov-registration-grid-table").append(
		"<button class='btn btn-sm btn-success' id='btn-lov-add-registration'>ADD</button>&nbsp;"
		+"<button class='btn btn-sm btn-primary' id='btn-lov-edit-registration'>EDIT</button>&nbsp;"
	);

	
	var jqformValidateRegistrationAdd = function(jqForm)
{	
	var passed = true;
	if(($("#txt-lov-id").val()=="") || ($("#txt-lov-name").val()=="")){
		passed = false;
	}
	if(!passed) {
		showWarning('Silakan mengisi field mandatory');
		jqformValidateRegistrationAdd.open();
	}
	return passed;
}
	
	
	
	$("#btn-lov-add-registration").click(function() {
		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {					
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_lov_add/",
						type: "post",
						beforeSubmit: jqformValidateRegistrationAdd,
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
	
		showCommonDialog(900, 500, 'ADD LOV', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/lov_add_form/', buttons);	
	});
	
		$("#btn-lov-edit-registration").click(function() {
		var selr = $(grid_selector_registration).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			var buttons = {
				"success" :
				 {
					"label" : "<i class='icon-ok'></i> Save",
					"className" : "btn-sm btn-success",
					"callback": function() {
					
						var options = {
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_lov_edit/",
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
			showCommonDialog(900, 500, 'EDIT LOV', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/lov_edit_category/' + selr, buttons);	
		} else {
			showWarning("Please select the data");
		}
	});
	
	//lov 2 dan 3 relation tree 


	
	//LoV CATEGORY RELATION
	
	
	var grid_selector = "#lov-list-grid-table";
	var pager_selector = "#lov-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/lov_list_old";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 140,
		width: null,
		colNames:['ID LoV','Active','Collection Type','LoV 1 Label Name','CATEGORY 1', 'LoV 2 Label Name','CATEGORY 2', 'LoV 3 Label Name','CATEGORY 3', 'LoV 4 Label Name','CATEGORY 4', 'LoV 5 Label Name','CATEGORY 5','Created By','Created Time'],
		colModel:[ 
			{name:'lov_id',index:'a.lov_id', width:60, hidden:true},
			{name:'is_active', index:'a.is_active', width:180, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
					if(rowId=='0'){
						return '<span class=\"label label-sm label-danger\">NOT ACTIVE</span>';
					}else if(rowId=='1'){
						return '<span class=\"label label-sm label-info\">ACTIVE</span>';
					}else{
						return rowId;
					}
				}
			},
			{name:'type_collection', index:'a.type_collection', width:180},
			{name:'lov1_label_name', index:'a.lov1_label_name', width:180},
			{name:'lov1_category',index:'lov1_category', width:150, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
				    // var data = $.parseJSON($.ajax({
						// url:  'settings/getDataCategoryById',
						// dataType: "json", 
						// data: "value=" +rowId,
						// async: false
					// }).responseText); 
					// return data.item;
					return rowId;
				}
			},
			{name:'lov2_label_name', index:'a.lov2_label_name', width:180},
			{name:'lov2_category',index:'lov2_category', width:150, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
				    // var data = $.parseJSON($.ajax({
						// url:  'settings/getDataCategoryById',
						// dataType: "json", 
						// data: "value=" +rowId,
						// async: false
					// }).responseText); 
					// return data.item;
					return rowId;
				}
			},
			{name:'lov3_label_name', index:'a.lov3_label_name', width:180},
			{name:'lov3_category',index:'lov3_category', width:150, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
				    // var data = $.parseJSON($.ajax({
						// url:  'settings/getDataCategoryById',
						// dataType: "json", 
						// data: "value=" +rowId,
						// async: false
					// }).responseText); 
					// return data.item;
					return rowId;
				}
			},
			{name:'lov4_label_name', index:'a.lov4_label_name', width:180},
			{name:'lov4_category',index:'lov4_category', width:150, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
				    // var data = $.parseJSON($.ajax({
						// url:  'settings/getDataCategoryById',
						// dataType: "json", 
						// data: "value=" +rowId,
						// async: false
					// }).responseText); 
					// return data.item;
					return rowId;
				}
			},
			{name:'lov5_label_name', index:'a.lov5_label_name', width:180},
			{name:'lov5_category',index:'lov5_category', width:150, formatter: function (rowId, tv, rawObject, cm, rdata)
				{
				    // var data = $.parseJSON($.ajax({
						// url:  'settings/getDataCategoryById',
						// dataType: "json", 
						// data: "value=" +rowId,
						// async: false
					// }).responseText); 
					// return data.item;
					return rowId;
				}
			},
			{name:'created_by', index:'a.created_by', width:180},
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
		], 
		sortname: 'lov_id',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		caption:"LOV CATEGORY RELATION",
		multiselect: false,
		onSelectRow: function(data){
			var arr = $(this).getRowData(data);
			arr_id = arr.lov_id_cms;
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
	$("#t_lov-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_lov-list-grid-table").append(
		// "<button class='btn btn-sm btn-success' id='btn-lov-add-relation'>ADD</button>&nbsp;"
		"<button class='btn btn-sm btn-primary' id='btn-lov-edit-relation'>EDIT</button>&nbsp;"
	);

	
	//txt-kcu_office_id, txt-kcu_office_name, opt_kcu
	var jqformValidate = function(jqForm)
{	
	var passed = true;
	if(($("#opt-type_collection").val()=="") ){
		passed = false;
	}
	if(!passed) {
		showWarning('Silakan mengisi field mandatory');
		jqformValidate.open();
	}
	return passed;
}

	var jqformValidateRelationAdd = function(jqForm)
{	
	var passed = true;
	if(($("#opt-type_collection").val()=="") ){
		passed = false;
	}
	if(!passed) {
		showWarning('Please fill the mandatory field');
		jqformValidateRelationAdd.open();
	}
	return passed;
}
	var showFormResponse = function(responseText, statusText)
	{
		if(responseText.success) {
			showInfo("Data already saved");
			$(grid_selector).trigger('reloadGrid');
			$(grid_selector_registration).trigger('reloadGrid');
		} else{
			showInfo(responseText.message);
		}
	}
	
	
	$("#btn-lov-add-relation").click(function() {
		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {
					 // if(($("#opt-type_collection").val()=="")){
						// showWarning("Silakan mengisi field mandatory");
						// return false;
					// } 					
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_lov_relation_add/",
						type: "post",
						beforeSubmit: jqformValidateRelationAdd,
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
	
		showCommonDialog(1000, 500, 'ADD LOV RELATION', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/lov_relation_add_form/', buttons);	
	});
	
		$("#btn-lov-edit-relation").click(function() {
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
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_lov_relation_edit/",
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
			showCommonDialog(1000, 500, 'EDIT LOV RELATION', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/lov_edit_form/' + selr, buttons);	
		} else {
			showWarning("Please select the data");
		}
	});
	
	
})