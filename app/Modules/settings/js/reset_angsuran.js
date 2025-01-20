jQuery(function($) {

	var grid_selector = "#reset-angsuran-list-grid-table";
	var pager_selector = "#reset-angsuran-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "assignment/account_assignment_to_fc_list";
	var ci_update_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "assignment/account_assignment_to_fc_list";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		editurl: ci_update_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['NO','COLLECTION CENTER','KCU','AREA','PETUGAS','NO CIF','NAMA DEBITUR', 'NO AKSEP','KODE PRODUK','TANGGAL CAIR','TANGGAL JATUH TEMPO','PLAFOND','OUTSTANDING',
		'DPD','KOLEKTIBILITAS','ALAMAT', 'KOTA','KODE POS','NAMA AO'],
		colModel:[
			{name:'list_number', index:'list_number', width:60, align:'right', sorttype:"int",search:false,hidden:true},
			{name:'group_kcu', index:'concat(kcu_group.kcu_id,kcu_group.kcu_name)', width:200},
			{name:'kcu', index:'concat(cust.kode_kcu, kcu.kcu_name)', width:200},
			{name:'assigned_area', index:'concat(assigned_area, ar.area_name)', width:200}, //area
			{name:'assigned_fc', index:'concat(assigned_fc, usr.name)', width:170},
			{name:'no_cif', index:'no_cif', width:110},			
			{name:'first_name', index:'first_name', width:260},
			{name:'no_rekening', index:'cust.no_rekening', width:110},
			{name:'kode_produk', index:'concat(kode_produk, product_name)', width:170},
			{name:'tgl_pencairan', index:'tgl_pencairan', width:120,searchoptions:
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
			{name:'ori_maturity_date', index:'ori_maturity_date', width:180,searchoptions:
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
			{name:'plafond', index:'plafond', width:110,formatter:"integer",searchoptions: {sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']}},
			{name:'baki_debet', index:'baki_debet', width:110,formatter:"integer",searchoptions: {sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']}},
			{name:'dpd', index:'dpd', width:110,formatter:"integer",searchoptions: {sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']}},
			{name:'kolektibilitas', index:'kolektibilitas', width:110,formatter:"integer",searchoptions: {sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']}},
			{name:'cust_address_1', index:'cust_address_1', width:350},
			{name:'city', index:'city', width:150},
			{name:'zip_code', index:'zip_code', width:100},
			{name:'kode_AO', index:'concat(kode_AO, officer_name)', width:170},
		], 
		sortname: 'first_name',
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
		shrinkToFit: false,
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
	$("#t_reset-angsuran-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_reset-angsuran-list-grid-table").append(
		"<button class='btn btn-sm btn-primary' id='btn-reset'>RESET ALERT ANGSURAN</button>&nbsp;" 
	);
	
	//Button Actions
	$("#btn-reset").click(function() {
		
	var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
					bootbox.confirm("Apakah akan mereset no aksep "+selr +"?", function(result) {
						if(result){
							$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/reset_angsuran/", { account: selr}, 
							function(responseText) {
								if(responseText.success) {
									showInfo("Reset angsuran Berhasil .");
								} else{
									showInfo("Reset angsuran Gagal .");
								}
							}, "json");
						}
					});
		} else {
			showWarning("Silakan pilih data");
		}	});
	
	
	
})