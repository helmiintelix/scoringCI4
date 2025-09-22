var arr_id = "";
var arr_kk = "";

jQuery(function($) {
	var grid_selector = "#area_tagih-list-grid-table";
	var pager_selector = "#area_tagih-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/area_tagih_list_temp";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','AREA TAGIH ID','AREA TAGIH NAME','TGL PENGAJUAN','STATUS PENGAJUAN','JENIS PENGAJUAN'],
		colModel:[
			{name:'id',index:'a.id', width:60, hidden:true},
			{name:'area_tagih_id', index:'a.area_tagih_id', width:180},
			{name:'area_tagih_name', index:'a.area_tagih_name', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
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
			{name:'status_approval', index:'a.status_approval', width:180},
			{name:'jenis_req', index:'a.jenis_req', width:180},
		], 
		sortname: 'area_tagih_name',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		multiselect: false,
		onSelectRow: function(data){
			var arr = $(this).getRowData(data);
			arr_id = arr.id;
			arr_kk = arr.area_tagih_id;
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
	$("#t_area_tagih-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_area_tagih-list-grid-table").append(
		//"<button class='btn btn-sm btn-success' id='btn-area_tagih-add'>ADD</button>&nbsp;" +
		"<button class='btn btn-sm btn-primary' id='btn-area_tagih-edit'>APPROVE</button>&nbsp;" +
		"<button class='btn btn-sm btn-danger' id='btn-area_tagih-delete'>REJECT</button>"
	);
	
	//Button Actions
	var showFormResponse = function(responseText, statusText)
	{
		if(responseText.success) {
			showInfo("Input berhasil.");
			$(grid_selector).trigger('reloadGrid');
		} else{
			showInfo("Input gagal.");
		}
	}

	
	$("#btn-area_tagih-edit").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{

			bootbox.confirm("Apakah anda yakin akan melakukan approval?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_area_tagih_edit_temp/", { id: arr_id, arr_kk : arr_kk }, function(responseText) {
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


	
		$("#btn-area_tagih-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{


			bootbox.confirm("Apakah anda yakin akan melakukan reject?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_note_reject_area_tagih/", {id: arr_id}, function(responseText) {
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

	
	//txt-area_tagih_office_id, txt-area_tagih_office_name, opt_area_tagih
	var jqformValidate = function(jqForm)
{	
	var passed = true;
	if(($("#txt-area_tagih-id").val()=="") || ($("#txt-area_tagih-name").val()=="")){
		passed = false;
	}
	if(!passed) {
		showWarning('Silakan mengisi field mandatory');
		jqformValidate.open();
	}
	return passed;
}
})