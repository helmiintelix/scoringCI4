jQuery(function($) {
	var grid_appr_selector = "#email-appr-list-grid-table";
	var pager_appr_selector = "#email-appr-list-grid-pager";
	var template_relation='';
	var ci_appr_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/email_sms_list_temp?template_relation="+template_relation;

	jQuery(grid_appr_selector).jqGrid({
		url: ci_appr_controller,
		datatype: "json",
		height: "500",
		width: null,
		colNames:['ID','TEMPLATE ID', 'TEMPLATE NAME','SENT BY','RECIPIENT','TEMPLATE DESIGN','MECHANISM','TIME','DIAJUKAN OLEH' ,'TGL PENGAJUAN','STATUS'],
		colModel:[
			{name:'id',index:'id', width:60, hidden:true},
			{name:'template_id',index:'template_id', width:200},
			// {name:'template_name', index:'list_number', width:60, align:'right', sorttype:"int",hidden:true},
			{name:'template_name',index:'template_name', width:200},
			{name:'sent_by', index:'sent_by', width:200},
			{name:'recipient', index:'recipient', width:200},
			{name:'template_design', index:'template_design', width:600, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'select_mechanism', index:'select_date', width:200},
			{name:'select_time', index:'select_time', width:200},
			{name:'created_by', index:'created_by', width:200},
			{name:'created_time', index:'created_time', width:200},
			{name:'flag', index:'flag', width:200},
			
		], 
		sortname: 'template_name',
		viewrecords : true,
		rowNum:10,
		caption:"WAITING APPROVAL",
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
	
	

	var grid_selector = "#email-list-grid-table";
	var pager_selector = "#email-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/email_sms_list";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 600,
		width: null,
		colNames:['ID','TEMPLATE ID', 'TEMPLATE NAME','SENT BY','RECIPIENT','TEMPLATE DESIGN','MECHANISM','TIME','CREATED BY' ,'CREATED TIME','ACTIVE'],
		colModel:[
			{name:'id',index:'id', width:60, hidden:true},
			{name:'template_id',index:'template_id', width:200},
			// {name:'template_name', index:'list_number', width:60, align:'right', sorttype:"int",hidden:true},
			{name:'template_name',index:'template_name', width:200},
			{name:'sent_by', index:'sent_by', width:200},
			{name:'recipient', index:'recipient', width:200},
			{name:'template_design', index:'template_design', width:600, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'select_mechanism', index:'select_date', width:200},
			{name:'select_time', index:'select_time', width:200},
			{name:'created_by', index:'created_by', width:200},
			{name:'created_time', index:'created_time', width:200},
			{name:'is_active', index:'is_active', width:200},
			
		], 
		sortname: 'template_name',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		multiselect: false,
		onSelectRow: function(data){
			var arr = $(this).getRowData(data);
			arr_id = arr.template_id;
		},
		caption:"SMS,EMAIL & WA TEMPLATE",
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
	$("#t_email-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_email-list-grid-table").append(
		"<button class='btn btn-sm btn-success' id='btn-email-add'>ADD</button>&nbsp;" +
		"<button class='btn btn-sm btn-primary' id='btn-email-edit'>EDIT</button>&nbsp;" +
		"<button class='btn btn-sm btn-danger' id='btn-email-delete'>DELETE</button>"
	);
	
	//Button Actions
	var showFormResponse = function(responseText, statusText)
	{
		if(responseText.success) {
			showInfo("Data telah disimpan dan menunggu approval");
			$(grid_selector).trigger('reloadGrid');
			$(grid_appr_selector).trigger('reloadGrid');
		} else{
			if(responseText.message){
				showInfo(responseText.message);
			}else{
				showInfo("Gagal.");
			}
		}
	}

	$("#btn-email-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Do you want to delete this data?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_email_sms/", { id: selr }, function(responseText) {
						if(responseText.success) {
							showInfo("Success.");
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
	
	$("#btn-email-add").click(function(){
		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {
					
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_email_sms_add/",
						type: "post",
						// beforeSubmit: jqformValidate,
						success: showFormResponse,
						dataType: 'json',
						data:{ content:tinyMCE.activeEditor.getContent()},
						// data:{sql:encodeURIComponent($('#query_builder').queryBuilder('getSQL', false, true).sql),sql_json:JSON.stringify($('#query_builder').queryBuilder('getRules'), null, 2)},
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
	
		showCommonDialog(900, 500, 'ADD SMS,EMAIL & WA TEMPLATE', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/email_sms_add_form/', buttons);	
	});
	
	$("#btn-email-edit").click(function() {
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
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_email_sms_edit/",
							type: "post",
							// beforeSubmit: jqformValidate,
							success: showFormResponse,
							dataType: 'json',
							data:{ content:tinyMCE.activeEditor.getContent()},
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
			showCommonDialog(1000, 600, 'EDIT SMS,EMAIL & WA TEMPLATE', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/emailSmsEditForm/' + selr, buttons);	
		} else {
			showWarning("Silakan pilih data");
		}
	});
	
	var jqformValidate = function(jqForm)
	{
	var passed = true;
	if(($("#txt-template-id").val()=="") || ($("#txt-template-name").val()=="") || ($("#opt-sentby").val()=="") || ($("#opt-template-relation").val()=="") || ($("#opt-recipient").val()=="") || ($("#opt-schedule").val()=="")  || ($("#txt-template-input-times").val()=="")){
		passed = false;
	}
	// alert($("#tmp_relation").val());
	
	if($("#tmp_relation").val()=='true'){
		if(($("#opt-product").val()=="") || ($("#opt-product-code").val()=="") || ($("#opt-flag-vip").val()=="") || ($("#opt-bucket").val()=="") || ($("#opt-rules").val()=="") || ($("#txt-template-input-value").val()=="")){
			passed = false;
		}
	}
	if(!passed) {
		showWarning('Silakan mengisi field mandatory');
		jqformValidate.open();
	}
	return passed;
}
	
})