var arr_id = "";

jQuery(function($) {
	var grid_selector = "#apk-list-grid-table";
	var pager_selector = "#apk-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/get_versi_apk_list_tmp";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['VERSI APK','NAMA FILE','UKURAN(KB)', 'PRIORITY','TGL INPUT','USER ID'],
		colModel:[
			{name:'versi_apk', index:'versi_apk', width:150},
			{name:'nama_file', index:'nama_file', width:300},
			{name:'file_size', index:'file_size', width:100,formatter:"integer",searchoptions: {sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']}},
			{name:'priority', index:'priority', width:100},
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
			{name:'created_by', index:'concat(usr.id,usr.name)', width:200}			
		], 
		sortname: 'a.created_time',
		sortorder: 'desc',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
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
	
	//navButtons
	jQuery(grid_selector).jqGrid('filterToolbar',{defaultSearch:true,stringResult:true});
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
	$("#t_apk-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_apk-list-grid-table").append(
		"<button class='btn btn-sm btn-success' id='btn-apk-add'>APPROVE</button>&nbsp;"+
		"<button class='btn btn-sm btn-danger' id='btn-apk-delete'>REJECT</button>"
	);
	
	//Button Actions
	var showFormResponse = function(responseText, statusText)
	{
		if(responseText.success) {
			showInfo("Data telah disimpan dan menunggu approval");
			$(grid_selector).trigger('reloadGrid');
		} else{
				
			showInfo(responseText.message);
		}
	}
	
		$("#btn-apk-add").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan melakukan approval?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_apk_temp/", { id: selr}, function(responseText) {
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
	
	$("#btn-apk-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan melakukan reject?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_apk/", { id: selr }, function(responseText) {
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
	
	//txt-apk_office_id, txt-apk_office_name, opt_apk
	var jqformValidate = function(jqForm)
{	
	var passed = true;
	if(($("#txt-versi-apk").val()=="")||($("#userfile").val()=="")){
		passed = false;
	}
	if(!passed) {
		showWarning('Silakan mengisi field mandatory');
		jqformValidate.open();
	}
	return passed;
}
})