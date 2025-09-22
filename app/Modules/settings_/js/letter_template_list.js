jQuery(function($) {
	var grid_appr_selector = "#letter-appr-list-grid-table";
	var pager_appr_selector = "#letter-appr-list-grid-pager";
	
	var ci_appr_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/letter_list_temp";

	var LANG = GLOBAL_MAIN_VARS["LANGUAGE"]['cms_bucket']; 
	var LANGS = GLOBAL_MAIN_VARS["LANGUAGE"]['cms_letter_template']; 
	var ACTION = GLOBAL_MAIN_VARS["LANGUAGE"]['button'];
	var LABEL = GLOBAL_MAIN_VARS["LANGUAGE"]['label'];

	jQuery(grid_appr_selector).jqGrid({
		url: ci_appr_controller,
		datatype: "json",
		height: null,
		width: null,
		colNames:['ID', 'NO.',LANGS['info'], LABEL['content'],'DETAIL','third party', LABEL['created_by'],LABEL['created_time'], LABEL['updated_by'], LABEL['updated_time'],'type letter', 'IS AKTIF','APPROVAL STATUS'],
		colModel:[
			{name:'letter_id',index:'letter_id', width:60, hidden:true},
			{name:'list_number', index:'list_number', width:60, align:'right', sorttype:"int",hidden:true},
			{name:'info', index:'info', width:200},
			{name:'content', index:'content', width:600, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'filter_detail', index:'filter_detail', width:300},
			{name:'id_lawyer', index:'id_lawyer', width:300},
			{name:'created_by', index:'created_by', width:200},
			{name:'created_time', index:'created_time', width:200},
			{name:'updated_by', index:'updated_by', width:200},
			{name:'updated_time', index:'updated_time', width:200},
			{name:'letter_type', index:'letter_type', width:300},
			{name:'flag', index:'flag', width:200},
			{name:'flag_tmp', index:'flag_tmp', width:200},
			
		], 
		sortname: 'info',
		viewrecords : true,
		rowNum:10,
		caption:LABEL['waiting'],
		rowList:[10,20,30],
		pager : pager_appr_selector,
		altRows: true,
		multiselect: false,
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
	
	$(".ui-jqgrid-bdiv").css("overflow","auto");
	//navButtons
	jQuery(grid_appr_selector).jqGrid('navGrid', pager_appr_selector,
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
	
	

	var grid_selector = "#letter-list-grid-table";
	var pager_selector = "#letter-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/letter_list";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID', 'NO.',LANGS['info'], LABEL['content'],'DETAIL','third party', LABEL['created_by'],LABEL['created_time'], LABEL['updated_by'], LABEL['updated_time'],'type letter', 'IS AKTIF','APPROVAL STATUS'],
		colModel:[
			{name:'letter_id',index:'letter_id', width:60, hidden:true},
			{name:'list_number', index:'list_number', width:60, align:'right', sorttype:"int",hidden:true},
			{name:'info', index:'info', width:200},
			{name:'content', index:'content', width:600, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'filter_detail', index:'filter_detail', width:300},
			{name:'id_lawyer', index:'id_lawyer', width:300},
			{name:'created_by', index:'created_by', width:200},
			{name:'created_time', index:'created_time', width:200},
			{name:'updated_by', index:'updated_by', width:200},
			{name:'updated_time', index:'updated_time', width:200},
			{name:'letter_type', index:'letter_type', width:300},
			{name:'flag', index:'flag', width:200},
			{name:'flag_tmp', index:'flag_tmp', width:200},
			
		], 
		sortname: 'info',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		multiselect: false,
		caption:LABEL['official_template'],
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

	
	$(".ui-jqgrid-bdiv").css("overflow","auto");
	
	//Add header
	$("#t_letter-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_letter-list-grid-table").append(
		"<button class='btn btn-sm btn-success' id='btn-letter-add'>"+ACTION['add']+"</button>&nbsp;" +
		"<button class='btn btn-sm btn-primary' id='btn-letter-edit'>"+ACTION['edit']+"</button>&nbsp;"
//		"<button class='btn btn-sm btn-danger' id='btn-letter-delete'>DELETE</button>"
	);
	
	//Button Actions
	var showFormResponse = function(responseText, statusText)
	{
		if(responseText.success) {
			showInfo(LABEL['success']);
			$(grid_selector).trigger('reloadGrid');
			$(grid_appr_selector).trigger('reloadGrid');
		} else{
			showInfo(LABEL['isi_field']+responseText.message);
		}
	}

	$("#btn-letter-add").click(function() {
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
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_letter_add/",
						type: "post",
						beforeSubmit: jqformValidate,
						success: showFormResponse,
						data: { sql: encodeURIComponent($('#query_builder').queryBuilder('getSQL', false, true).sql), sql_json: JSON.stringify($('#query_builder').queryBuilder('getRules'), null, 2), content:tinyMCE.activeEditor.getContent() },
						dataType: 'json',
						// data:{ content:tinyMCE.activeEditor.getContent()},
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
	
		showCommonDialog(900, 500, ACTION['add']+" "+LABEL['official_template'], GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/letter_add_form/', buttons);	
	});
	
	$("#btn-letter-edit").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			var buttons = {
				"success" :
				 {
					"label" : "<i class='icon-ok'></i> Apply",
					"className" : "btn-sm btn-success",
					"callback": function() {
					
						var options = {
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_letter_edit/",
							type: "post",
							beforeSubmit: jqformValidate,
							success: showFormResponse,
							dataType: 'json',
							data:{ sql: encodeURIComponent($('#query_builder').queryBuilder('getSQL', false, true).sql), sql_json: JSON.stringify($('#query_builder').queryBuilder('getRules'), null, 2), content:tinyMCE.activeEditor.getContent()},
						}; 
						$('form').ajaxSubmit(options);
					}
				},
				"button" :
				{
					"label" : ACTION['close'],
					"className" : "btn-sm"
				}
			}
			showCommonDialog(1000, 600, 'EDIT OFFICIAL LETTER TEMPLATE', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/letterEditForm/' + selr, buttons);	
		} else {
			showWarning(LABEL['pilih_data']);
		}
	});
	
	var jqformValidate = function(jqForm)
{
	var passed = true;
	if(($("#txt-letter-info").val()=="") && ($("#txt-letter-dpd-from").val()=="") && ($("#txt-letter-dpd-to").val()=="") && ($("#opt_field_list").val()=="")){
		passed = false;
	}
	if(($("#txt-letter-info").val()!="SP3") && ($("#txt-letter-dpd-from").val()=="") && ($("#txt-letter-dpd-to").val()=="") && ($("#opt_field_list").val()=="")){
		passed = false;
	}

	if(($("#txt-letter-dpd-from").val()*1)> ($("#txt-letter-dpd-to").val()*1)){
		showWarning('DPD From cannot greather DPD To');
		jqformValidate.open();
		return passed;
		
	}
	if(!passed) {
		showWarning(LABEL['isi_field']);
		jqformValidate.open();
	}
	return passed;
}
	
})