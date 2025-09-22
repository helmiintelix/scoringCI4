jQuery(function($) {
	var grid_selector = "#kcu-list-grid-table";
	var pager_selector = "#kcu-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/kcu_aja_list";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','KCU CODE', 'DESCRIPTION'],
		colModel:[
			{name:'id',index:'id', width:60, hidden:true},
			{name:'kcu_id', index:'kcu_id', width:200},
			{name:'kcu_name', index:'kcu_name', width:600, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}}
		], 
		sortname: 'kcu_name',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
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
	$("#t_kcu-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_kcu-list-grid-table").append(
		"<button class='btn btn-sm btn-success' id='btn-kcu-add'>ADD</button>&nbsp;" +
		"<button class='btn btn-sm btn-primary' id='btn-kcu-edit'>EDIT</button>&nbsp;" +
		"<button class='btn btn-sm btn-danger' id='btn-kcu-delete'>DELETE</button>"
	);
	
	//Button Actions
	var showFormResponse = function(responseText, statusText)
	{
		if(responseText.success) {
			showInfo("Data telah disimpan dan menunggu approval");
			$(grid_selector).trigger('reloadGrid');
		} else{
			showInfo("Gagal.");
		}
	}
	
	$("#btn-kcu-add").click(function() {
		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {
					
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_kcu_aja_add/",
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
	
		showCommonDialog(900, 500, 'ADD KCU', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/kcu_aja_add_form/', buttons);	
	});
	
	$("#btn-kcu-edit").click(function() {
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
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_kcu_aja_edit/",
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
			showCommonDialog(900, 500, 'EDIT KCU Template', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/kcuAjaEditForm/' + selr, buttons);	
		} else {
			showWarning("Silakan pilih data");
		}
	});
	
	$("#btn-kcu-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Are you sure you want to delete this kcu content?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_kcu_aja/", { id: selr }, function(responseText) {
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
})