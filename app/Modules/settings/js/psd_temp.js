jQuery(function($) {
	var grid_selector = "#area-list-grid-table";
	var pager_selector = "#area-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/psd_list_temp";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['COLLECTION CENTER','KCU','AREA','NAMA PETUGAS','NO AKSEP','NAMA DEBITUR','STATUS WO','STATUS AYDA','USER','TIME STATUS', 'STATUS APPROVAL'],
		colModel:[
			{name:'kcu_group',index:'kcu_group', width:120},
			{name:'kcu_cabang',index:'kcu_cabang', width:120},
			{name:'area',index:'area', width:120,hidden:GLOBAL_MAIN_VARS["ASSIGNMENT_TYPE"]=="NON_OWNERSHIP"?false:true},
			{name:'name',index:'name', width:200},
			{name:'no_rekening',index:'no_rekening', width:120},
			{name:'first_name',index:'first_name', width:200},
			{name:'wo_status', index:'wo_status', width:160},
			{name:'ayda_status', index:'ayda_status', width:160},
			{name:'wo_user', index:'wo_user', width:200},
			//{name:'ayda_status_time', index:'ayda_status_time', width:200},
			{name:'wo_status_time', index:'wo_status_time', width:200},
			{name:'flag_wo', index:'flag_wo', width:200},

		], 
		sortname: 'no_rekening',
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
	ondblClickRow: function(id){
					var row = $(this).getRowData(id);
   				var norekening = row["no_rekening"];
					var buttons = {
						"button" :
						{
							"label" : "Close",
							"className" : "btn-sm"
						}
					}

   					showCommonDialog(1300, 600, 'DETAIL DEBITUR', GLOBAL_MAIN_VARS["SITE_URL"] + 'cockpit/customer_verif?norekening='+ norekening , buttons);	

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
	
	$('#btn-area-editz').click(function(){
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

	
	$("#btn-area-edit").click(function() {
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
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/edit_flag_psd/", { id: selr }, function(responseText) {
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
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_psd/", { id: selr }, function(responseText) {
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