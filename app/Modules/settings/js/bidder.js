var arr_id = "";
var status_ACTIVE = ":-PILIH-;1:ENABLE;0:DISABLE"
jQuery(function($) {
	var grid_selector = "#bidder-list-grid-table";
	var pager_selector = "#bidder-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/settings_ext/bidder_list";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID',
				  'ID Card No.',
				  'Name',
				  'Phone No',
				  'Address',
				  'Area',
				  'CG ID',
				  'Branch',
				  'Status',
				  'Created By',
				  'Created Date'],
		colModel:[
			{name:'id',index:'a.bidder_id', width:250, hidden:false},
			{name:'id_card',index:'a.id_card', width:150, hidden:false},
			{name:'name', index:'a.name', width:250},
			{name:'phone_1', index:'a.phone_1', width:150},
			{name:'address', index:'address', width:300},
			{name:'area_name', index:'d.area_name', width:200},
			{name:'cgid_name', index:'cgid_name', width:200,hidden:true},
			{name:'branch_name', index:'c.branch_name', width:200},
			{name:'is_active', index:'a.is_active', width:100,stype:'select',searchoptions:
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
		caption:"BIDDER LIST",
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
	$("#t_bidder-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_bidder-list-grid-table").append(
		"<button class='btn btn-sm btn-success' id='btn-balai-add'>ADD</button>&nbsp;"
		+"<button class='btn btn-sm btn-primary' id='btn-balai-edit'>EDIT</button>&nbsp;"
		+"<button class='btn btn-sm btn-primary' id='btn-balai-activate'>ACTIVATE</button>&nbsp;"
		+"<button class='btn btn-sm btn-success hide' id='btn-user-add-excel'>ADD BY EXCEL</button>&nbsp;" 
		+(GLOBAL_SESSION_VARS["LEVEL_GROUP"]!='ROOT'? "":"<button class='btn btn-sm btn-danger hide' id='btn-kcu-delete'>DELETE</button>")
	);

	
	
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
	
	$("#btn-balai-add").click(function() {
		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {
					if(($("#txt-nama-balai").val()=="")||($("#txt-alamat").val()=="")){
						showWarning("Silakan mengisi field mandatory");
						return false;
					}
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/settings_ext/save_bidder/",
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
	
		showCommonDialog(920, 500, 'ADD BIDDER', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/settings_ext/bidder_form?for=add', buttons);	
	});
	
		$("#btn-balai-edit").click(function() {
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
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/settings_ext/save_bidder/",
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
			showCommonDialog(920, 500, 'EDIT BIDDER', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/settings_ext/bidder_form?id=' + selr+"&for=edit", buttons);	
		} else {
			showWarning("Please select the data");
		}
	});

	$("#btn-balai-activate").click(function(){
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		
		if(selr)
		{
			var is_active = $(grid_selector).jqGrid('getCell', selr, 'is_active');
			let strippedString = is_active!="undefined" ? is_active.replace(/(<([^>]+)>)/gi, "") : "";
			console.log(strippedString)
			let data=''
			if(strippedString=='ENABLE'){
				data = 'deactive'
			}else{
				data= 'activated'
			}
			bootbox.confirm("Are you sure you want to "+data+" this data?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/settings_ext/del_bidder/", { data_id: selr }, function(data) {
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
})