var arr_id = "";

jQuery(function($) {
	var grid_selector = "#sub-area-list-grid-table";
	var pager_selector = "#sub-area-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/sub_area_list_temp";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','ACTION','KODE SUB AREA', 'NAMA SUB AREA','AREA','ZIP CODE HANDLED','TGL INPUT','TGL UPDATE','STATUS AKTIF','STATUS APPROVAL'],
		colModel:[
			{name:'id',index:'a.id', width:60, hidden:true},
			{name:'approval_notes',index:'a.approval_notes', width:100},
			{name:'sub_area_id',index:'a.sub_area_id', width:120},
			{name:'sub_area_name', index:'a.sub_area_name', width:160, sorttype:"int"},
			{name:'area_name', index:'concat(a.area_id,b.area_name)', width:200},
			{name:'zip_code', index:'zip_code', width:200},
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

			{name:'flag', index:'a.flag', width:200,stype:'select',
				searchoptions:
				{
					value: ':ALL;1:ENABLE;0:DISABLE',
					separator: ':',
					delimiter: ';'
				}},
			{name:'flag_tmp', index:'a.flag_tmp', width:200,stype:'select',
				searchoptions:
				{
					value: ':ALL;0:WAITING APPROVAL;1:APPROVED;2:REJECTED',
					separator: ':',
					delimiter: ';'
				}},

		], 

		sortname: 'sub_area_name',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		multiselect: false,
		onSelectRow: function(data){
			var arr = $(this).getRowData(data);
			arr_id = arr.sub_area_id;
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
	$("#t_sub-area-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_sub-area-list-grid-table").append(
		//"<button class='btn btn-sm btn-success' id='btn-sub-area-add'>ADD</button>&nbsp;" +
		"<button class='btn btn-sm btn-primary' id='btn-sub-area-edit'>APPROVE</button>&nbsp;" +
		"<button class='btn btn-sm btn-danger' id='btn-sub-area-delete'>REJECT</button>"
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
	
	$("#btn-sub-area-add").click(function() {
		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {
					
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/sub_area_add/",
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
	
		showCommonDialog(900, 500, 'ADD SUB AREA TEMPLATE', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/sub_area_add_form/', buttons);	
	});
	
	$('#btn-sub-area-editz').click(function(){
				var options = {
					url: "settings/edit_flag_chekermaker",
					type: 'post',
					beforeSubmit: jqformValidate,
					success: showResponse
				};
				
				//$("#input_kunjungan").ajaxSubmit(options);
				//ResetFunction();
				//return false; 
			});				

	
	$("#btn-sub-area-edit").click(function() {
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
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/edit_flag_chekermaker/",
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
			showCommonDialog(900, 500, 'EDIT AREA TEMPLATE', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/area_edit_form/' + selr, buttons);	
*/
			bootbox.confirm("Apakah anda yakin akan melakukan approval?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/edit_flag_chekermaker_sub_area/", { id: arr_id }, function(responseText) {
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
	
	$("#btn-sub-area-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan melakukan reject?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_sub_area/", { id: arr_id }, function(responseText) {
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
	if(($("#txt-sub_area_id").val()=="") || ($("#txt-sub_area_name").val()=="") || ($("#area_list").val()=="")){
		passed = false;
	}
	if(!passed) {
		showWarning('Silakan mengisi field mandatory');
	}
	return passed;
}

})