var arr_id = "";


jQuery(function($) {
	var grid_selector = "#ik-list-grid-table";
	var pager_selector = "#ik-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/input_kunjungan_list_temp";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:[

			'TGL KUNJUNGAN',
			'NAMA PETUGAS',
			'PARAMETER KUNJUNGAN',
			'TEMPAT DIKUNJUNGI',
			'NAMA YANG DITEMUI',
			'HUBUNGAN',
			'HASIL KUNJUNGAN',
			'NOMINAL BAYAR',
			'JANJI BAYAR',
			'RENCANA PENAGIHAN',
			'CATATAN',
			'BIDANG SAAT INI',
			'CREATED TIME',
			'ID',
			'STATUS APPROVAL',

							],
		colModel:[
		//26
			{name:'tgl_kunjungan', index:'tgl_kunjungan', width:150},
			{name:'nama_petugas', index:'nama_petugas', width:150},
			{name:'tugas_kunjungan', index:'tugas_kunjungan', width:200},
			{name:'tempat_dikunjungi', index:'tempat_dikunjungi', width:200}, 
			{name:'nama_yang_ditemui', index:'nama_yang_ditemui', width:150},
			{name:'hubungan', index:'hubungan', width:200},
			{name:'hasil_kunjungan', index:'hasil_kunjungan', width:200},
			{name:'nominal_bayar', index:'nominal_bayar', width:150},
			{name:'janji_bayar', index:'janji_bayar', width:200},
			{name:'rencana_penagihan', index:'rencana_penagihan', width:150},
			{name:'catatan', index:'catatan', width:150},
			{name:'bidang_usaha_saat_ini', index:'bidang_usaha_saat_ini', width:200}, 
			{name:'created_time', index:'created_time', width:200, hidden:true}, 
			{name:'id', index:'id', width:200, hidden:true}, 
			{name:'flag_tmp', index:'flag_tmp', width:200}, 
						
		], 

		sortname: 'created_time',
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
	$("#t_ik-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_ik-list-grid-table").append(
		//"<button class='btn btn-sm btn-success' id='btn-area-add'>ADD</button>&nbsp;" +
		"<button class='btn btn-sm btn-primary' id='btn-ik-edit'>APPROVE</button>&nbsp;" +
		"<button class='btn btn-sm btn-danger' id='btn-ik-delete'>REJECT</button>"
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
	
	$("#btn-ik-edit").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Are you sure you want to Approve ?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_kunjungan_temp/", {id: arr_id}, function(responseText) {
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
	
	$("#btn-ik-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Are you sure you want to reject this area?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_ik/", { id: selr }, function(responseText) {
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
		showWarning('Please enter a value for mandatory fields');
	}
	return passed;
}

})