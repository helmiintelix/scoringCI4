var selr;
	var selected_data;

	//Button Actions
	var showFormResponse = function (responseText, statusText) {
		if (responseText.success) {
			showInfo("Data already save");
			// getData();

		} else {
			showInfo(responseText.message);
			return false;
		}
	}

	$("#btn-edit-team").click(function () {

		if (selr) {

			var buttons = {
				"success":
				{

					"label": "<i class='icon-ok'></i> Save",
					"className": "btn-sm btn-success",
					"callback": function () {
						var options = {
							url: GLOBAL_MAIN_VARS["SITE_URL"] + "team_management/team_work/edit_team",
							type: "post",
							beforeSubmit: jqformValidate,
							success: showFormResponse,
							dataType: 'json',
						};
						$('#tom_agent option').prop('selected', true);
						if (jqformValidate() == false) { return false; }
						$('form').ajaxSubmit(options).delay(3000);
					}
				},
				"button":
				{
					"label": "Close",
					"className": "btn-sm"
				}
			}

			showCommonDialog(500, 500, 'Edit Team', GLOBAL_MAIN_VARS["SITE_URL"] + 'team_management/team_work/edit_team_form?team_id=' + selr, buttons);
		} else {
			showWarning("Silakan pilih data");
		}
	});

	$("#btn-add-team").click(function () {
		var buttons = {
			"success":
			{
				"label": "<i class='icon-ok'></i> Save",
				"className": "btn-sm btn-success",
				"callback": function () {
					var options = {
						url: GLOBAL_MAIN_VARS["SITE_URL"] + "team_management/team_work/save_team",
						type: "post",
						// beforeSubmit: jqformValidate,
						success: showFormResponse,
						dataType: 'json',
					};
					
					$('#form_add_team').ajaxSubmit(options);
				}
			},
			"button":
			{
				"label": "Close",
				"className": "btn-sm"
			}
		}

		showCommonDialog(800, 500, 'Add Team', GLOBAL_MAIN_VARS["SITE_URL"] + 'team_management/team_work/add_team_form', buttons);
	});

	$("#btn-del-team").click(function () {


		if (selr) {
			bootbox.confirm("Are you sure you want to deactive " + selected_data.team_name + " this team?", function (result) {
				if (result) {
					$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "team_management/team_work/del_team", { team_id: selr }, function (data) {
						if (data.success == true) {
							showInfo(data.message);
							$(grid_selector).trigger('reloadGrid');
						} else {
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