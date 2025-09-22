var arr_id = "";

jQuery(function ($) {
	var grid_selector = "#lov-list-grid-table";
	var pager_selector = "#lov-list-grid-pager";

	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/lov_list";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames: ['ID', 'LOV ID CMS', 'LOV NAME', 'CATEGORY', 'HIRARKI', 'CREATED TIME', 'CREATED BY', 'UPDATED TIME', 'UPDATED BY'],
		colModel: [
			{ name: 'id', index: 'id', width: 60, hidden: true },
			{ name: 'lov_id_cms', index: 'lov_id_cms', width: 60, hidden: true },
			{ name: 'category_name', index: 'category_name', search: true, searchoptions: { attr: { style: 'width: 100%;' } }, hidden: false, width: 180 },
			{
				name: 'category', index: 'category', width: 200, hidden: false, search: true, stype: 'select', searchoptions:
				{
					value: ':-PILIH-;CTC:CTC - Contacted;NCT:NCT - Uncontacted;APP:APP - Appointment',
					separator: ':',
					delimiter: ';'
				}
			},
			{ name: 'hirarki', index: 'hirarki', width: 200, hidden: false, searchoptions: { attr: { style: 'width: 100%;' } } },
			{
				name: 'created_time', index: 'date(created_time)', width: 200, hidden: false, searchoptions:
				{
					attr: { style: 'width: 100%;' },
					dataInit: function (el) {
						$(el).datepicker({
							changeYear: false,
							changeMonth: false,
							showButtonPanel: false,
							dateFormat: 'yy-mm-dd',
							// format: 'yyyy-mm-dd',
						}).on("changeDate", function (e) {
							setTimeout(function () {
								$(grid_selector)[0].triggerToolbar();
							}, 100);

						});
					}
				}
			},
			{ name: 'created_by', index: 'created_by', searchoptions: { attr: { style: 'width: 100%;' } }, width: 180, hidden: false },
			{
				name: 'updated_time', index: 'date(updated_time)', width: 200, hidden: false, searchoptions:
				{
					attr: { style: 'width: 100%;' },
					dataInit: function (el) {
						$(el).datepicker({
							changeYear: false,
							changeMonth: false,
							showButtonPanel: false,
							dateFormat: 'yy-mm-dd',
							// format: 'yyyy-mm-dd',
						}).on("changeDate", function (e) {
							setTimeout(function () {
								$(grid_selector)[0].triggerToolbar();
							}, 100);

						});
					}
				}
			},
			{ name: 'updated_by', index: 'updated_by', searchoptions: { attr: { style: 'width: 100%;' } }, width: 180, hidden: false }
		],
		//sortname: 'lov_label',
		viewrecords: true,
		rowNum: 10,
		rowList: [10, 20, 30],
		pager: pager_selector,
		altRows: true,
		caption: "MAPPING & HIRARKI LOV",
		multiselect: false,
		multiboxonly: false,
		onSelectRow: function (data) {
			var arr = $(this).getRowData(data);
			arr_id = arr.id;
		},
		toolbar: [true, "top"],
		loadComplete: function () {
			var table = this;
			setTimeout(function () {
				updateActionIcons(table);
				updatePagerIcons(table);
				enableTooltips(table);
			}, 0);
		},
		autowidth: false,
		shrinkToFit: false
	});

	jQuery(grid_selector).jqGrid('filterToolbar', { defaultSearch: true, stringResult: true });
	//navButtons
	jQuery(grid_selector).jqGrid('navGrid', pager_selector,
		{ 	//navbar options
			edit: false,
			editicon: 'icon-pencil blue',
			add: false,
			addicon: 'icon-plus-sign purple',
			del: false,
			delicon: 'icon-trash red',
			search: true,
			searchicon: 'icon-search orange',
			refresh: true,
			refreshicon: 'icon-refresh green',
			view: false,
			viewicon: 'icon-zoom-in grey',
		},
		{
			//search form
			recreateForm: true,
			afterShowSearch: function (e) {
				var form = $(e[0]);
				form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
				style_search_form(form);
			},
			afterRedraw: function () {
				style_search_filters($(this));
			}
			,
			multipleSearch: true,
			/**
			multipleGroup:true,
			showQuery: true
			*/
		}
	);

	//Add header
	$("#t_lov-list-grid-table").css("height", "40px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_lov-list-grid-table").append(
		"<button class='btn btn-sm btn-primary' id='btn-lov-add'>ADD</button>&nbsp;"
		+ "<button class='btn btn-sm btn-primary' id='btn-lov-edit'>EDIT</button>&nbsp;"
		// +"<button class='btn btn-sm btn-primary' id='btn-lov-export'>Save to Excel<i class='icon-table icon-on-right bigger-110'></i></button>&nbsp;"
	);

	/*+(GLOBAL_SESSION_VARS["LEVEL_GROUP"]=='ROOT'? "":"<button class='btn btn-sm btn-danger' id='btn-kpi-delete'>DELETE</button>")*/

	//Button Actions
	var showFormResponse = function (responseText, statusText) {
		// if(responseText.success) {
		// //showInfo("Data telah disimpan");
		// showInfo('Data Saved',1500);
		// //warningDialog(300, 100, responseText.success, 'Data telah disimpan');
		// $(grid_selector).trigger('reloadGrid');
		// $("#CommonDialog").dialog('close');
		// } else{
		// warningDialog(300, 100, responseText.success, responseText.message);
		// //showInfo(responseText.message);
		// }


		if (responseText.success) {
			showInfo('Data Saved', 1500);
			$(grid_selector).trigger('reloadGrid');
			$(grid_appr_selector).trigger('reloadGrid');
		} else {
			showInfo("Gagal." + responseText.message);
		}
	}

	$("#btn-lov-add").click(function () {

		var buttons = {
			"success":
			{
				"label": "Save",
				"class": "btn btn-xs btn-success",
				"callback": function () {
					console.log("Masuk Success");
					/* if(($("#opt-bisnis-unit").val()=="")||($("#txt-kcu-name").val()=="")||($("#txt-kcu-id").val()=="")){
						showWarning("Silakan mengisi field mandatory");
						return false;
					} */

					if ($("#opt-lov_list").val() == "") {
						showWarning("please input SELECT LOV");
						return false;
					}
					if ($("#opt-lov_category").val() == "") {
						showWarning("please input CATEGORY");
						return false;
					}
					if ($("#opt-lov_hirarki").val() == "") {
						showWarning("please input HIRARKI");
						return false;
					}
					var options = {
						url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_lov_add_hyrarki/",
						type: "post",
						beforeSubmit: jqformValidate,
						success: function (msg) {
							showFormResponse(msg, '');
						},
						dataType: 'json',
					};
					$('form').ajaxSubmit(options);
				}
			},
			"button":
			{
				"label": "Close",
				"className": "btn-sm"
			}
		}

		showCommonDialog(500, 400, 'ADD LOV', GLOBAL_MAIN_VARS["SITE_URL"] + '/settings/lov_add_form_hyrarki/', buttons);
	});

	$("#btn-lov-edit").click(function () {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');

		if (selr) {
			var buttons = {
				"success":
				{
					"label": "Save",
					"class": "btn btn-xs btn-success",
					"callback": function () {
						if ($("#opt-lov_list").val() == "") {
							showWarning("please input SELECT LOV");
							return false;
						}
						if ($("#opt-lov_category").val() == "") {
							showWarning("please input CATEGORY");
							return false;
						}
						if ($("#opt-lov_hirarki").val() == "") {
							showWarning("please input HIRARKI");
							return false;
						}

						var options = {
							url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_lov_edit_hyrarki/",
							type: "post",
							beforeSubmit: jqformValidate,
							success: function (msg) {
								showFormResponse(msg, '');
							},
							dataType: 'json',
						};
						$('form').ajaxSubmit(options);
					}
				},
				"button":
				{
					"label": "Close",
					"className": "btn-sm"
				}
			}
			showCommonDialog(900, 500, 'EDIT LOV', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/lov_edit_form_hyrarki?id=' + selr, buttons);
		} else {
			showWarning("please chosee data");
		}
	});

	$("#btn-lov-export").click(function () {

		var obj = {
			lov_id_cms: $("#gs_lov_id_cms").val(),
			category: $("#gs_category").val(),
			hirarki: $("#gs_hirarki").val(),
			created_time: $("#gs_created_time").val(),
			created_by: $("#gs_created_by").val(),
			updated_time: $("#gs_created_time").val(),
			updated_by: $("#gs_created_by").val()
		};
		var remark = JSON.stringify(obj);

		location.href = GLOBAL_MAIN_VARS["SITE_URL"] + "/settings/save_lov_excel?mode=xls&remark=" + remark;
	});

	/*$("#btn-kpi-delete").click(function() {
		var selr = $(grid_selector).jqGrid('getGridParam', 'selrow');
		
		if(selr)
		{
			bootbox.confirm("Apakah anda yakin akan menghapus?", function(result) {
				if(result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_kpi/", { id: arr_id }, function(responseText) {
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
			showWarning("Silakan pilih data");
		}
	});*/

	//txt-kcu_office_id, txt-kcu_office_name, opt_kcu
	var jqformValidate = function (jqForm) {
		var passed = true;
		if (($("#txt-kpi-id").val() == "")) {
			passed = false;
		}
		if (!passed) {
			showWarning('please input field mandatory');
			jqformValidate.open();
		}
		return passed;
	}

	$("form :input").attr("autocomplete", "off");
});