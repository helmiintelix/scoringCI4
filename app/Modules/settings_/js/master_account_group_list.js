var arr_id = "";

jQuery(function($) {
	var grid_selector = "#mag-list-grid-table";
	var pager_selector = "#mag-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/master_account_group_list";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','DESCRIPTION', 'PAYMENT CRITERIA','TGL INPUT','TGL UPDATE','STATUS AKTIF','STATUS APPROVAL'],
		colModel:[
			{name:'id',index:'a.id', width:120},
			{name:'description', index:'a.description', width:180},
			{name:'payment_criteria', index:'a.payment_criteria', width:200},
		
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

			{name:'updated_time', index:'date(a.updated_time)', width:200,searchoptions:
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

			
			{name:'flag', index:'a.flag', width:150,stype:'select',
				searchoptions:
				{
					value: ':ALL;0:DISABLE;1:ENABLE',
					separator: ':',
					delimiter: ';'
				}},
			
			{name:'flag_tmp', index:'a.flag_tmp', width:130,stype:'select',
				searchoptions:
				{
					value: ':ALL;0:WAITING APPROVAL;1:APPROVED;2:REJECTED',
					separator: ':',
					delimiter: ';'
				}},
			//{name:'note_reject', index:'note_reject', width:200}
		], 
		sortname: 'id',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		caption:"MASTER ACCOUNT GROUP",
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
	$("#t_mag-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_mag-list-grid-table").append(
		"<button class='btn btn-sm btn-success' id='btn-mag-add'>ADD</button>&nbsp;"
		+"<button class='btn btn-sm btn-primary' id='btn-mag-edit'>EDIT</button>&nbsp;"
		+(GLOBAL_SESSION_VARS["LEVEL_GROUP"]!='ROOT'? "":"<button class='btn btn-sm btn-danger' id='btn-mag-delete'>DELETE</button>")
	);

	var grid_app_selector = "#mag-appr-list-grid-table";
	var pager_app_selector = "#mag-appr-list-grid-pager";
	var ci_app_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/master_account_group_list_temp";

	jQuery(grid_app_selector).jqGrid({
		url: ci_app_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ACTION','ID','DESCRIPTION', 'PAYMENT CRITERIA','TGL INPUT','TGL UPDATE','STATUS AKTIF','STATUS APPROVAL'],
		colModel:[
			{name:'action', index:'action', width:200},
			{name:'id', index:'a.id', width:180},
			{name:'description', index:'a.description', width:200},
			{name:'payment_criteria', index:'a.payment_criteria', width:300},
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
			{name:'updated_time', index:'date(a.updated_time)', width:200,searchoptions:
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
			
			
			{name:'flag', index:'a.flag', width:150,stype:'select',
				searchoptions:
				{
					value: ':ALL;0:DISABLE;1:ENABLE',
					separator: ':',
					delimiter: ';'
				}},
			{name:'flag_tmp', index:'a.flag_tmp', width:130,stype:'select',
				searchoptions:
				{
					value: ':ALL;0:WAITING APPROVAL;1:APPROVED;2:REJECTED',
					separator: ':',
					delimiter: ';'
				}},
			//{name:'note_reject', index:'note_reject', width:200},
		], 
		sortname: 'id',
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
			arr_kk = arr.mag_id;
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
	var showFormagesponse = function(responseText, statusText)
	{
		if(responseText.success) {
			showInfo("Data telah disimpan dan menunggu approval");
			$(grid_selector).trigger('reloadGrid');
			$(grid_app_selector).trigger('reloadGrid');
		} else{
			showInfo(responseText.message);
		}
	}
	
	$("#btn-mag-add").click(function() {
		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {
					if(($("#txt-id").val()=="")||($("#txt-description").val()=="")){
						showWarning("Silakan mengisi field mandatory");
						return false;
					}					
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_master_account_group_add/",
						type: "post",
						beforeSubmit: jqformValidate,
						success: showFormagesponse,
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
	
		showCommonDialog(900, 500, 'ADD MASTER ACCOUNT GROUP', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/master_account_group_add_form/', buttons);	
	});
	
		$("#btn-mag-edit").click(function() {
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
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_master_account_group_edit/",
							type: "post",
							beforeSubmit: jqformValidate,
							success: showFormagesponse,
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
			showCommonDialog(900, 500, 'EDIT MASTER ACCOUNT GROUP', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/masterAccountGroupEditForm/' + selr, buttons);	
		} else {
			showWarning("Silakan pilih data");
		}
	});
	
	$("#btn-mag-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan menghapus?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_master_account_group/", { id: arr_id }, function(responseText) {
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
	
	//txt-mag_office_id, txt-mag_office_name, opt_mag
	var jqformValidate = function(jqForm)
{	
	var passed = true;
	if(($("#txt-id").val()=="") || ($("#txt-description").val()=="")){
		passed = false;
	}
	if(!passed) {
		showWarning('Silakan mengisi field mandatory');
		jqformValidate.open();
	}
	return passed;
}
})