var arr_id = "";
var status_ACTIVE = ":-PILIH-;1:ENABLE;0:DISABLE"

	var grid_selector = "#balai-lelang-list-grid-table";
	var pager_selector = "#balai-lelang-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/settings_ext/balai_lelang_list";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','Auction House Name', 'Address','Status','Created By','Created Date'],
		colModel:[
			{name:'id',index:'id', width:250, hidden:false},
			{name:'balai_name', index:'balai_name', width:250},
			{name:'address', index:'address', width:300},
			{name:'is_active', index:'is_active', width:100,stype:'select',searchoptions:
				{
					value: status_ACTIVE,
					separator: ':',
					delimiter: ';'
				}},
			{name:'created_by', index:'created_by', width:180},
			{name:'created_time', index:'date(created_time)', width:200,searchoptions:
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
		sortname: 'a.name',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		caption:"Action House List",
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
	$("#t_balai-lelang-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_balai-lelang-list-grid-table").append(
		"<button class='btn btn-sm btn-success' id='btn-balai-add' onClick='form_balai_lelang_add()'>ADD</button>&nbsp;"
		+"<button class='btn btn-sm btn-primary' id='btn-balai-edit' onClick='form_balai_lelang_edit()'>EDIT</button>&nbsp;"
		+"<button class='btn btn-sm btn-warning' id='btn-balai-activate' >ACTIVATE</button>&nbsp;"
		+"<button class='btn btn-sm btn-success hide' id='btn-user-add-excel'>ADD BY EXCEL</button>&nbsp;" 
		+(GLOBAL_SESSION_VARS["LEVEL_GROUP"]!='ROOT'? "":"<button class='btn btn-sm btn-danger hide' id='btn-kcu-delete'>DELETE</button>")
	);

	var grid_app_selector = "#kcu-appr-list-grid-table";
	var pager_app_selector = "#kcu-appr-list-grid-pager";
	var ci_app_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/settings_ext/kcu_list_temp";

	jQuery(grid_app_selector).jqGrid({
		url: ci_app_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID KCU', 'ACTION', 'KODE AREA TAGIH', 'NAMA AREA TAGIH','AREA','CABANG','AREA ZIP CODE','ALAMAT','TGL INPUT','TGL UPDATE','STATUS AKTIF','STATUS APPROVAL'],
		colModel:[
			{name:'id',index:'a.id', width:60, hidden:true},
			{name:'action', index:'action', width:200},
			{name:'kcu_id', index:'a.kcu_id', width:180},
			{name:'kcu_name', index:'a.kcu_name', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'area', index:'a.area', width:180, hidden:true},
			{name:'cabang', index:'a.cabang', width:180, hidden:true},
			{name:'zip_code_list', index:'a.zip_code_list', width:300,cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			{name:'alamat', index:'a.alamat', width:180, hidden:true},
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
		sortname: 'kcu_name',
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
			arr_kk = arr.kcu_id;
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
			showInfo(responseText.message);
			$(grid_selector).trigger('reloadGrid');
			$(grid_app_selector).trigger('reloadGrid');
		} else{
			showInfo(responseText.message);
		}
	}
	
	function form_balai_lelang_add() {
		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {
					if(($("#txt-nama-balai").val()=="")||($("#txt-alamat").val()=="")){
						showWarning("Fill the mandatory field");
						return false;
					}
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/settings_ext/save_balai_lelang/",
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
	
		showCommonDialog(920, 500, 'Add Action House', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/settings_ext/balai_lelang_add_form?for=add', buttons);	
	};
	
	function form_balai_lelang_edit() {
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
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/settings_ext/save_balai_lelang/",
							type: "post",
							beforeSubmit: jqformValidate,
							success: showFormResponse,
							dataType: 'json',
						}; 
						$('#tom_agent option').prop('selected', true);
						$('form').ajaxSubmit(options);
					}
				},
				"button" :
				{
					"label" : "Close",
					"className" : "btn-sm"
				}
			}
			showCommonDialog(920, 500, 'EDIT COLLECTION CENTER', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/settings_ext/balai_lelang_add_form?id=' + selr+"&for=edit", buttons);	
		} else {
			showWarning("Please select the data");
		}
	};

	$("#btn-balai-activate").click(function(){
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		var is_active = $(grid_selector).jqGrid('getCell', selr, 'is_active');
		let strippedString = is_active.replace(/(<([^>]+)>)/gi, "");
		console.log(strippedString)
		let data=''
		if(strippedString=='ENABLE'){
			data = 'deactive'
		}else{
			data= 'activated'
		}
		
		if(selr)
		{
			bootbox.confirm("Are you sure you want to "+data+" this data?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/settings_ext/del_mBalai/", { data_id: selr }, function(data) {
						if(data.success==true) {
							showInfo(data.message);
							$(grid_selector).trigger('reloadGrid');	
						} else{
							showInfo(data.message);
							return false;
						}
					}, "json");
				}
			});
		} else {
			alert("Please select a row.");
		}
	});
	
	$("#btn-kcu-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan menghapus?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/settings_ext/delete_kcu/", { id: arr_id }, function(responseText) {
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
			showWarning("Please select the data");
		}
	});
	
	//txt-kcu_office_id, txt-kcu_office_name, opt_kcu
var jqformValidate = function(jqForm)
{	
	 
	var passed = true;
	if(($("#txt-kcu-id").val()=="") || ($("#txt-kcu-name").val()=="") || ($("#txt-kd-kcu").val()=="") || ($("#txt-kcu").val()=="") ){
		passed = false;
	}
	if(!passed) {
		showWarning('Silakan mengisi field mandatory');
		jqformValidate.open();
	}
	return passed;
}

	$("#btn-user-add-excel").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		// if(selr)
		// {
			var buttons = {
				"success" :
				 {
					"label" : "<i class='icon-ok'></i> Save",
					"className" : "btn-sm btn-success",
					"callback": function() {
					
					
						var options = {
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/settings_ext/save_area_tagih_excel/",
							type: "post",
							// beforeSend: showProgressBar,
							success:  function(responseText, statusText){
									if(responseText.success) {
										showInfo("Data telah disimpan.");
										//alert("Data telah disimpan dan menunggu approval");
										$(grid_selector).trigger('reloadGrid');
									} else{
										//alert(responseText.message);	
										showWarning(responseText.message);
									}
							},
							dataType: 'json',
						}; 
						//if(($("#txt-user-id").val()=="") || ($("#txt-user-name").val()=="") || ($("#opt-user-level").val()=="") || ($("#opt-active-flag").val()=="")){
						//	showWarning("Silakan mengisi field mandatory");
						//	return false;
						//}else{
								
									$('form').ajaxSubmit(options);
								
							
						//}
						
					}
				},
				"button" :
				{
					"label" : "Close",
					"className" : "btn-sm"
				}
			}
			showCommonDialog(900, 500, 'Add Area Tagih Excel', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/settings_ext/add_area_tagih_excel/' + selr, buttons);	
		// } else {
			// showWarning("Silakan pilih data pada tabel.");
		// }
	});
