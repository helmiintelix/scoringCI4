jQuery(function ($) {
	var selr;
	var selected_data;
	function deselect() {
		gridOptions.api.deselectAll()
	}

	// Grid Options are properties passed to the grid
	const gridOptions = {

		// each entry here represents one column
		columnDefs: [
			{ field: 'team_idxxx' },
			{ field: 'team_name' },
			{ field: 'team_leader' },
			{ field: 'supervisor' },
			{ field: 'agent_assignment_list' },
			{ field: 'description' },
			{ field: 'is_active' },
			{ field: 'created_by' },
			{ field: 'created_time' }
		],

		// default col def properties get applied to all columns
		// defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },
		defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },

		rowSelection: 'single', // allow rows to be selected
		enableRangeSelection: true,
		animateRows: true, // have rows animate to new positions when sorted


		// example event handler
		onCellClicked: params => {
			console.log('cell was clicked', params)
			selr = params.data.team_id;
			selected_data = params.data;
		}
	};

	// get div to host the grid
	const eGridDiv = document.getElementById("myGrid");
	// new grid instance, passing in the hosting DIV and Grid Options
	new agGrid.Grid(eGridDiv, gridOptions);








	//Button Actions
	var showFormResponse = function (responseText, statusText) {
		if (responseText.success) {
			showInfo("Data already save");
			getData();

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
						/*if($('#team_name').val() == ''|| $('#coord_list').val() == ''|| $('#spv_list').val() == ''||$('#tom_agent').val() == null){
							showWarning('Silakan mengisi field mandatory');
							return false;
						}*/

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
					/*if($('#team_name').val() == ''|| $('#coord_list').val() == ''|| $('#spv_list').val() == ''||$('#tom_agent').val() == null){
						showWarning('Silakan mengisi field mandatory');
						return false;
					}*/

					var options = {
						url: GLOBAL_MAIN_VARS["SITE_URL"] + "team_management/team_work/save_team",
						type: "post",
						// beforeSubmit: jqformValidate,
						success: showFormResponse,
						dataType: 'json',
					};
					if (checkValidate() == false) { return false; }
					$('form').ajaxSubmit(options);
				}
			},
			"button":
			{
				"label": "Close",
				"className": "btn-sm"
			}
		}

		showCommonDialog(500, 500, 'Add Team', GLOBAL_MAIN_VARS["SITE_URL"] + 'team_management/team_work/add_team_form', buttons);
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

	$("#btn-export-excel").click(function () {
		gridOptions.api.exportDataAsExcel();
	})

	function getData() {
		$.ajax({
			url: GLOBAL_MAIN_VARS["SITE_URL"] + "team_management/team_work/get_list_team",
			type: "get",
			success: function (msg) {
				gridOptions.api.setColumnDefs(msg.data.header);
				gridOptions.api.setRowData(msg.data.data);
			},
			dataType: 'json',
		});
	}
	getData()
})