jQuery(function($) {
	
	
	var grid_selector = "#parameters-list-grid-table";
	var pager_selector = "#parameters-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/parameters_list_temp";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		postData:{parameter_type: function() { return jQuery("#parameter_type option:selected").val();}},
		height: 340,
		width: null,
		colNames:['ID', 'ACTION', 'JENIS PARAMETER','REFIDD','KODE PARAMETER', 'DESKRIPSI','VIEW ON','STATUS AKTIF','TGL INPUT','TGL UPDATE','USER NAME','STATUS APPROVAL'],
		colModel:[
			{name:'id',index:'id', width:60, hidden:true},
			{name:'action', index:'b.action', width:150},
			{name:'reff_id', index:'reff_id', width:150},
			{name:'reff_id', index:'reff_id', width:150,hidden:true},
			{name:'value', index:'value', width:150, hidden:true},
			{name:'description', index:'b.description', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'add_field1', index:'add_field1', hidden:true, width:200,stype:'select',
				searchoptions:
				{
					value: ':ALL;DESKTOP:DESKTOP;MOBILE:MOBILE;BOTH:DESKTOP DAN MOBILE',
					separator: ':',
					delimiter: ';'
				}},
			{name:'flag', index:'b.flag', width:150,stype:'select',
				searchoptions:
				{
					value: ':ALL;1:ENABLE;0:DISABLE',
					separator: ':',
					delimiter: ';'
				}},
			{name:'created_time', index:'date(b.created_time)', width:200,searchoptions:
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

			{name:'updated_time', index:'date(b.updated_time)', width:200,searchoptions:
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
			{name:'created_by', index:'concat(a.id, a.name)', width:150},
			{name:'flag_tmp', index:'b.flag_tmp', width:150,stype:'select',
				searchoptions:
				{
					value: ':ALL;0:WAITING APPROVAL;1:APPROVED;2:REJECTED',
					separator: ':',
					delimiter: ';'
				}},
		], 
		sortname: 'value',
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
	$("#t_parameters-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_parameters-list-grid-table").append(
/*		"PARAMETER : <select id='parameter_type'> " +
		"<option value=''>-PILIH-</option>"+
		"<option value='CALL_RESULT'>HASIL TELEPON</option>"+
		"<option value='TUJUAN_KUNJUNGAN'>TUJUAN KUNJUNGAN</option>"+
		"<option value='HASIL_KUNJUNGAN'>HASIL KUNJUNGAN</option>"+
		"<option value='TUJUAN_TELEPON'>TUJUAN TELEPON</option>"+
		"<option value='PARAMETER_KUNJUNGAN'>PARAMETER KUNJUNGAN</option>"+
		"<option value='TEMPAT_KUNJUNGAN'>TEMPAT KUNJUNGAN</option>"+
		"<option value='HUBUNGAN'>HUBUNGAN</option>" +
		"<option value='RENCANA_PENANGANAN'>RENCANA PENANGANAN</option>" +
		"<option value='KONDISI_USAHA'>KONDISI USAHA</option>" +
		"<option value='KONDISI_DEBITUR'>KONDISI DEBITUR</option>" +
		"<option value='STATUS_JAMINAN'>STATUS_JAMINAN</option>" +
		"<option value='KONDISI_JAMINAN'>KONDISI JAMINAN</option>" +
		"</select>&nbsp;"+
*/
		//"<button class='btn btn-sm btn-success' id='btn-parameters-add'>ADD</button>&nbsp;" +
		"<button class='btn btn-sm btn-primary' id='btn-parameters-edit'>APPROVE</button>&nbsp;" +
		"<button class='btn btn-sm btn-danger' id='btn-parameters-delete'>REJECT</button>&nbsp;"
	);
	
	//Button Actions
	var showFormResponse = function(responseText, statusText)
	{
		if(responseText.success) {
			showInfo("Berhasil.");
			$(grid_selector).trigger('reloadGrid');
		} else{
			showInfo("Gagal.");
		}
	}
	
	$("#btn-parameters-add").click(function() {
		if($("#parameter_type").val() == ""){
			showWarning("Please select parameter type");
			return;
		}
		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {
					
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_parameters_add/",
						type: "post",
						data:{parameter_type:$("#parameter_type").val()},
						beforeSubmit: jqformValidate1,
						success: showFormResponse,
						dataType: 'json',
					}; 
					$('form').ajaxSubmit(options);
				}
			},
			"button" :
			{ //jqformValidate
				"label" : "Close",
				"className" : "btn-sm"
			}
		}
	
		showCommonDialog(900, 500, 'ADD PARAMETER ' + $("#parameter_type").val(), GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/parameters_add_form/', buttons);	
	});
	
	$("#btn-parameters-edit").click(function() {
/*		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			var buttons = {
				"success" :
				 {
					"label" : "<i class='icon-ok'></i> Apply",
					"className" : "btn-sm btn-success",
					"callback": function() {
					
						var options = {
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_parameters_edit_flag/",
							type: "post",
							data:{parameter_type:$("#parameter_type").val()},
							beforeSubmit: jqformValidate1,
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
			showCommonDialog(900, 500, 'EDIT PARAMETER ' + $("#parameter_type").val(), GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/parametersEditForm/?value=' + selr + '&parameter_type='+$("#parameter_type").val(), buttons);	
		} else {
			showWarning("Silakan pilih data");
		}
*/
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan melakukan approval?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_parameters_edit_flag/", { id: selr,parameter_type:$(grid_selector).jqGrid('getCell',selr,'reff_id') }, function(responseText) {
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
	
	$("#btn-parameters-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan melakukan reject?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/reject_parameters/", { id: selr,parameter_type:$(grid_selector).jqGrid('getCell',selr,'reff_id')  }, function(responseText) {
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
	
	$("#parameter_type").change(function(){
		//showWarning($("#parameter_type").val());
				$(grid_selector).trigger('reloadGrid');
		
	});
	
	var jqformValidate1 = function(jqForm)
{
	var passed = true;
	if(($("#txt-parameters-name").val()=="") || ($("#txt-parameters-id").val()=="")){
		passed = false;
	}
	if(!passed) {
		showWarning('Silakan mengisi field mandatory');
	}
	return passed;
}

})