jQuery(function($) {
	
	var grid_aprv_selector = "#parameters-appr-list-grid-table";
	var pager_aprv_selector = "#parameters-appr-list-grid-pager";
	
	var ci_aprv_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/parameters_list_temp";

	jQuery(grid_aprv_selector).jqGrid({
		url: ci_aprv_controller,
		datatype: "json",
		postData:{parameter_type: function() { return jQuery("#parameter_type option:selected").val();}},
		height: "100%",
		width: null,
		colNames:['ID', 'ACTION', 'JENIS PARAMETER','KODE PARAMETER', 'DESKRIPSI','VIEW ON','TGL INPUT','TGL UPDATE','USER NAME','STATUS AKTIF','STATUS APPROVAL'],
		colModel:[
		{name:'id',index:'id', width:60, hidden:true},
		{name:'action', index:'action', width:150},
		{name:'reference', index:'c.description', width:150},
		{name:'value', index:'value', width:150, hidden:false},
		{name:'description', index:'b.description', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
			return 'style="white-space: normal;' 
		}},
		{name:'add_field1', index:'add_field1', width:200,hidden:true,stype:'select',
		searchoptions:
		{
			value: ':ALL;DESKTOP:DESKTOP;MOBILE:MOBILE;BOTH:DESKTOP DAN MOBILE',
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
								$(grid_aprv_selector)[0].triggerToolbar();
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
								$(grid_aprv_selector)[0].triggerToolbar();
							}, 100);
							
						});
					}
				}
			},

			{name:'created_by', index:'concat(a.id, a.name)', width:150},
			{name:'flag', index:'b.flag', width:150,stype:'select',
			searchoptions:
			{
				value: ':ALL;1:ENABLE;0:DISABLE',
				separator: ':',
				delimiter: ';'
			}},
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
			pager : pager_aprv_selector,
			altRows: true,
			multiselect: false,
			caption: 'WAITING APPROVAL',
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
	jQuery(grid_aprv_selector).jqGrid('filterToolbar',{defaultSearch:true,stringResult:true});
	jQuery(grid_aprv_selector).jqGrid('navGrid', pager_aprv_selector,
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

	var grid_selector = "#parameters-list-grid-table";
	var pager_selector = "#parameters-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/parameters_list";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		postData:{parameter_type: function() { return jQuery("#parameter_type option:selected").val();}},
		height: 340,
		width: null,
		colNames:['ID','KODE PARAMETER', 'DESKRIPSI','VIEW ON','TGL INPUT','TGL UPDATE','USER NAME','STATUS AKTIF','STATUS APPROVAL'],
		colModel:[
		{name:'id',index:'a.id', width:60, hidden:true},
		{name:'value', index:'a.value', width:200, hidden:false},
		{name:'description', index:'a.description', width:200, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
			return 'style="white-space: normal;' 
		}},
		{name:'add_field1', index:'add_field1', width:200,hidden:true,stype:'select',
		searchoptions:
		{
			value: ':ALL;DESKTOP:DESKTOP;MOBILE:MOBILE;BOTH:DESKTOP DAN MOBILE',
			separator: ':',
			delimiter: ';'
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
			{name:'created_by', index:'concat(c.id, c.name)', width:200},
			{name:'flag', index:'a.flag', width:100,stype:'select',
			searchoptions:
			{
				value: ':ALL;1:ENABLE;0:DISABLE',
				separator: ':',
				delimiter: ';'
			}},
			{name:'flag_tmp', index:'a.flag_tmp', width:150,stype:'select',
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
			caption:'SETTING PARAMETER',
			multiselect: false,
			toolbar: [true, "top"], 
			loadComplete : function() {
				var table = this;
				setTimeout(function(){
					updateActionIcons(table);
					updatePagerIcons(table);
					enableTooltips(table);
				//populate select 
				$.ajax({
					type: "POST",
					url:  GLOBAL_MAIN_VARS["SITE_URL"] +"settings/get_parameter_master_list/",
					async: false,
					dataType: "json",

					success: function(msg){
						try{

							if($("#parameter_type").val() == ''){
								$("#parameter_type")
								$.each(msg, function(val,text){
									//console.log(val);
									$("#parameter_type").append($('<option></option>').val(text.id).html(text.description));
								});
							}

						}catch(e){
							console.log(e);
						}

					},
					error: function(){
						console.log("conenction failed");
					}
				});
				
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
	$("#t_parameters-list-grid-table").css("height", "50px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_parameters-list-grid-table").append(
		"PARAMETER : <select id='parameter_type'> " +
		"<option value=''>-PILIH-</option>"+
/*		"<option value='ALASAN_TDK_BAYAR'>ALASAN TIDAK BAYAR</option>" +
		"<option value='ACCOUNT_TAGGING'>ACCOUNT TAGGING</option>" +
		"<option value='CARA_BAYAR'>CARA BAYAR</option>" +
		"<option value='ACTION_CODE'>HASIL TELEPON</option>"+
//		"<option value='HASIL_KUNJUNGAN'>HASIL KUNJUNGAN</option>"+
		"<option value='CONTACT_CODE'>HUBUNGAN</option>" +
		"<option value='DISCOUNT_REASON'>ALASAN DISCOUNT</option>" +
		"<option value='RESTRUCTURE_REASON'>ALASAN RESTRUKTURE</option>" +
		"<option value='INTEREST'>BUNGA RESTRUKTURE</option>" +
//		"<option value='KONDISI_DEBITUR'>KONDISI DEBITUR</option>" +
//		"<option value='KONDISI_JAMINAN'>KONDISI JAMINAN</option>" +
//		"<option value='KONDISI_USAHA'>KONDISI USAHA</option>" +
//		"<option value='RENCANA_PENANGANAN'>RENCANA PENANGANAN</option>" +
//		"<option value='STATUS_JAMINAN'>STATUS JAMINAN</option>" +
//		"<option value='PLACE_CODE'>TEMPAT KUNJUNGAN</option>"+
//		"<option value='TUJUAN_KUNJUNGAN'>TUJUAN KUNJUNGAN</option>"+
"<option value='PLACE_CODE'>TUJUAN TELEPON</option>"+*/
"</select>&nbsp;"+
"<button class='btn btn-sm btn-success' id='btn-parameters-add'>ADD</button>&nbsp;" 
+"<button class='btn btn-sm btn-primary' id='btn-parameters-edit'>EDIT</button>&nbsp;" 
+(GLOBAL_SESSION_VARS["LEVEL_GROUP"]!='ROOT'? "":"<button class='btn btn-sm btn-danger' id='btn-parameters-delete'>DELETE</button>&nbsp;")
);
	
	
	//Button Actions
	var showFormResponse = function(responseText, statusText)
	{
		if(responseText.success) {
			showInfo("Data telah disimpan dan menunggu approval.");
			$(grid_selector).trigger('reloadGrid');
			$(grid_aprv_selector).trigger('reloadGrid');
		} else{
			showInfo("Gagal.");
		}
	}
	
	$("#btn-parameters-add").click(function() {
		if($("#parameter_type").val() == ""){
			showWarning("Silakan pilih jenis Parameter");
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

		showCommonDialog(900, 500, 'ADD PARAMETER ' + $("#parameter_type").children("option:selected").text(), GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/parameters_add_form/?parameter_type='+$("#parameter_type").val(), buttons);	
	});
	
	$("#btn-parameters-edit").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			var buttons = {
				"success" :
				{
					"label" : "<i class='icon-ok'></i> Apply",
					"className" : "btn-sm btn-success",
					"callback": function() {

						var options = {
							url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_parameters_edit/",
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
			showCommonDialog(900, 500, 'EDIT PARAMETER ' + $("#parameter_type").children("option:selected").text(), GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/parametersEditForm/?value=' + selr + '&parameter_type='+$("#parameter_type").val(), buttons);	
		} else {
			showWarning("Silakan pilih data");
		}
	});
	
	$("#btn-parameters-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan melakukan delete?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_parameters/", { id: selr,parameter_type:$("#parameter_type").val() }, function(responseText) {
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
		if(($("#txt-parameters-name").val()=="") || ($("#txt-parameters-id").val()=="") || ($("#opt-active-flag").val()=="") || ($("#opt-bisnis-unit").val()=="")|| ($("#opt-destination").val()=="")){
			passed = false;
		}
		if(!passed) {
			showWarning('Silakan mengisi field mandatory');
			jqformValidate.open();
		}
		return passed;
	}

	//$("#parameter_type").chosen({width: "20%"});

})