/**
* jquery main program	agent_main.js
*/
$(document).ready(function () {
	var edituserfrm = jQuery("#agent_script_table").jqGrid('getGridParam', 'selrow');
	if (edituserfrm != null) jQuery("#agent_script_table").jqGrid('editGridRow', gr, { height: 100, reloadAfterSubmit: false, closeAfterAdd: true });

	$("#agent_script_table").jqGrid({
		url: GLOBAL_MAIN_VARS["SITE_URL"] + "agent_script/get_agent_script_list/",
		datatype: 'json',
		height: 425,
		width: 1000,
		colNames: ['ID', 'Subject', 'Script', 'Created By', 'Created Time'],
		colModel: [
			{ name: 'id', index: 'id', width: 25, sorttype: "int", hidden: true },
			{ name: 'subject', index: 'subject', align: 'left', width: 200, hidden: false },
			{
				name: 'script', index: 'script', align: 'left', width: 800, hidden: false, cellattr: function (rowId, tv, rawObject, cm, rdata) {
					return 'style="white-space: normal;margin:3px;padding:3px'
				}
			},
			{ name: 'created_by', index: 'created_by', align: 'left', width: 160, hidden: false },
			{ name: 'created_time', index: 'created_time', align: 'left', width: 160, search: false, hidden: false }
		],
		rowNum: 20,
		pager: '#agent_script_pager',
		sortname: 'subject',
		viewrecords: true,
		toolbar: [true, "top"],
		multiselect: false,
		caption: 'Agent Scripts'
	});
	$("#agent_script_table").jqGrid('navGrid', '#agent_script_pager', {
		edit: false,
		add: false,
		del: false,
		search: true,
		searchicon: 'icon-search orange',
		refresh: false
	});

	$("#t_agent_script_table").css("height", "34px").css("background", "#fee188");
	$("#t_agent_script_table").append(
		"<button class='btn btn-sm btn-yellow' id='btn_add'>Add</button>" +
		"<button class='btn btn-sm btn-yellow' id='btn_update'>Update</button>"
		// "<button class='btn btn-sm btn-yellow' id='btn_delete'>Delete</button>"
		// "<button class='btn btn-sm btn-yellow' id='btn_find'>Find Scrips</button>
	);


	var showFormResponse = function (responseText, statusText) {
		if (responseText.success) {
			showInfo("Data telah disimpan");
			$("#agent_script_table").trigger('reloadGrid');
		} else {
			showInfo(responseText.message);
			return false;
		}
	}

	$("#btn_add").click(function () {

		var buttons = {
			"success":
			{
				"label": "<i class='icon-ok'></i> Save",
				"className": "btn-sm btn-success",
				"callback": function () {
					var options = {
						type: "POST",
						url: GLOBAL_MAIN_VARS["SITE_URL"] + "/agent_script/new_script/",
						async: false,
						dataType: "json",
						data: $('form').serialize(),
						// beforeSubmit:	jqform_validate,
						success: function (msg) {
							if (msg.success == true) {
								showFormResponse(msg);
								// $("#agent_script_table").trigger('reloadGrid');
								$("#CommonDialog").remove();
							}
							else if (msg.success == false) {
								warningDialog(300, 100, "Warning!", msg.message);
							}
						},
						error: function () {
							alert("Failed: new call result");
						}
					};

					$("#add_script_form").ajaxSubmit(options);

				}
			},
			"button":
			{
				"label": "Close",
				"className": "btn-sm"
			}
		}
		showCommonDialog(1200, 600, 'Assign Collector to Class', GLOBAL_MAIN_VARS["SITE_URL"] + 'agent_script/add_script', buttons);



	});

	$("#btn_update").click(function () {
		var selr = $('#agent_script_table').jqGrid('getGridParam', 'selrow');
		if (selr) {
			GLOBAL_MAIN_VARS['script_id'] = selr;


			var buttons = {
				"success":
				{
					"label": "Submit",
					"className": "btn-sm btn-success",
					"callback": function () {
						var options = {
							type: "POST",
							url: GLOBAL_MAIN_VARS["SITE_URL"] + "/agent_script/update_agent_script/",
							async: false,
							dataType: "json",
							data: $('form').serialize(),
							// beforeSubmit:	jqform_validate,
							success: function (msg) {
								if (msg.success == true) {
									showFormResponse(msg);
									$("#agent_script_table").trigger('reloadGrid');
									//$("#CommonDialog").dialog('close');	 
									//$("#CommonDialog").dialog('destroy');
									$("#CommonDialog").remove();
								}
								else if (msg.success == false) {
									warningDialog(300, 100, "Warning!", msg.message);
								}
							},
							error: function () {
								alert("Failed: new call result");
							}
						};

						$("#update_script_form").ajaxSubmit(options);

					}
				},
				"button":
				{
					"label": "Close",
					"className": "btn-sm"
				}
			}
			showCommonDialog(1200, 600, 'Update Script', GLOBAL_MAIN_VARS["SITE_URL"] + 'agent_script/update_script', buttons);
		} else {
			showWarning("Silakan pilih data pada tabel.");
		}

	});

	$("#btn_delete").click(function () {
		var selr = $('#agent_script_table').jqGrid('getGridParam', 'selrow');
		if (selr) {
			r = confirm('Are you sure for delete?');
			if (r == true) {
				$.post('agent_script/remove_agent_script', { id: selr }, function (msg) {
					if (msg.success == true) {
						showInfo('Script deleted!', 1500);
						$("#agent_script_table").trigger('reloadGrid');
					} else {
						warningDialog(300, 100, "Warning!", msg.message);
					}
				}, "json");
			}
		} else {
			alert("No selected row");
		}
	});

	$("#btn_find").click(function () {
		$("#agent_script_table").jqGrid('searchGrid',
			{ sopt: ['cn'] }
		);
	});
});