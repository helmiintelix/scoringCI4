var arr_id = "";

jQuery(function($) {
	var grid_selector = "#it-list-grid-table";
	var pager_selector = "#it-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/input_telepon_list_temp";
	//var ci_update_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/input_telepon_list_temp";
	
	//var ci_controller = "customer/list_hasil_telpon?no_rekening="+GLOBAL_SESSION_VARS["NO_REKENING"];
	//var ci_update_controller = "customer/list_hasil_telpon?no_rekening="+GLOBAL_SESSION_VARS["NO_REKENING"];

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		//editurl: ci_update_controller,
		datatype: "json",
		height: 400,
		width: null,
		colNames:[
			'TGL TELEPON',
			'PARAMETER TELEPON',
			'NAMA PETUGAS',
			'NO YANG DIHUBUNGI',
			'NAMA YANG DIHUBUNGI',	
			'HASIL TELEPON',
			'NOMINAL BAYAR',
			'JANJI BAYAR',
			'REKOMENDASI',
			'CATATAN',
			'BIDANG USAHA SAAT INI',
			'ID',
			'STATUS APPROVAL',

							],
		colModel:[
		//26
			{name:'tgl_kunjungan', index:'tgl_kunjungan', width:150},
			{name:'tujuan', index:'tujuan', width:200},
			{name:'nama_petugas', index:'nama_petugas', width:150},
			{name:'no_yang_dihubungi', index:'no_yang_dihubungi', width:150},
			{name:'nama_yang_dihubungi', index:'nama_yang_dihubungi', width:200},
			{name:'hasil_telpon', index:'hasil_telpon', width:200},
			{name:'nominal_bayar', index:'nominal_bayar', width:150},
			{name:'janji_bayar', index:'janji_bayar', width:200},
			{name:'rekomendasi', index:'rekomendasi', width:200},
			{name:'catatan', index:'catatan', width:150},
			{name:'bidang_usaha_saat_ini', index:'bidang_usaha_saat_ini', width:200}, 
			{name:'id', index:'id', width:200, hidden:true}, 
			{name:'flag_tmp', index:'flag_tmp', width:200}, 
	
		], 

		sortname: 'no_rekening',
		viewrecords : true,
		rowNum:9,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		multiselect: false,
		onSelectRow: function(data){
			var arr = $(this).getRowData(data);
			arr_id = arr.id;
		},
		autowidth: false,
		toolbar: [true, "top"], 
		loadComplete : function() {
			var table = this;
			setTimeout(function(){
				updateActionIcons(table);
				updatePagerIcons(table);
				enableTooltips(table);
			}, 0);
		},

		//enable search/filter toolbar
		//jQuery(grid_selector).jqGrid('filterToolbar',{defaultSearch:true,stringResult:true})

		//autowidth: true,
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
	$("#t_it-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_it-list-grid-table").append(
		//"<button class='btn btn-sm btn-success' id='btn-area-add'>ADD</button>&nbsp;" +
		"<button class='btn btn-sm btn-primary' id='btn-it-edit'>APPROVE</button>&nbsp;" +
		"<button class='btn btn-sm btn-danger' id='btn-it-delete'>REJECT</button>"
	);
	
	//Button Actions
	$("#btn-it-edit").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Are you sure you want to Approve ?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_telepon_temp/", { id: arr_id }, function(responseText) {
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
	
	$("#btn-it-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Are you sure you want to reject this area?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_it/", { id: arr_id }, function(responseText) {
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
	
})