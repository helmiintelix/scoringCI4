
var selr = '';
var selected_data = '';
function deselect() {
    gridOptions.api.deselectAll();
    gridApprovalOptions.api.deselectAll();
}

function getData() {
    $.ajax({
        url:
            GLOBAL_MAIN_VARS["SITE_URL"] +
            "user_and_group/user_and_group/user_group_management_list",
        type: "get",
        success: function (msg) {
            gridOptions.api.setColumnDefs(msg.data.header);
            gridOptions.api.setRowData(msg.data.data);
        },
        dataType: "json",
    });
}


function getDataApproval() {
    $.ajax({
        url:
            GLOBAL_MAIN_VARS["SITE_URL"] +
            "user_and_group/user_group_management_list_temp",
        type: "get",
        success: function (msg) {
            gridApprovalOptions.api.setColumnDefs(msg.data.header);
            gridApprovalOptions.api.setRowData(msg.data.data);
        },
        dataType: "json",
    });
}
// getDataApproval(); // untuk menampilkan data di table nya

// Grid Options are properties passed to the grid
var gridOptions = {
    columnDefs: [
        { field: "" },
        { field: "" },
        { field: "" },
        { field: "" },
        { field: "" },
        { field: "" },
        { field: "" },
        { field: "" },
        { field: "" },
    ],

    // default col def properties get applied to all columns
    // defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },
    defaultColDef: {
        sortable: true,
        filter: "agSetColumnFilter",
        floatingFilter: true,
        resizable: true,
    },

    rowSelection: "multiple", // allow rows to be selected
    animateRows: true, // have rows animate to new positions when sorted
    paginationAutoPageSize: true,
    pagination: true,

    // example event handler
    onCellClicked: (params) => {
        console.log("cell was clicked", params);
        selr = params.data.id;
        selected_data = params.data;
        console.log("click>>>>", selr);
        console.log("data>>", selected_data);
    },
};

// var gridApprovalOptions = {
// 	columnDefs: [
// 		{ field: "id" },
// 		{ field: "name" },
// 		{ field: "group_id" },
// 		{ field: "is_active" },
// 		{ field: "login_status" },
// 		{ field: "description" },
// 		{ field: "is_active" },
// 		{ field: "created_by" },
// 		{ field: "created_time" },
// 	],

// 	// default col def properties get applied to all columns
// 	// defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },
// 	defaultColDef: {
// 		sortable: true,
// 		filter: "agSetColumnFilter",
// 		floatingFilter: true,
// 		resizable: true,
// 	},

// 	rowSelection: "multiple", // allow rows to be selected
// 	animateRows: true, // have rows animate to new positions when sorted
// 	paginationAutoPageSize: true,
// 	paginationPageSize: 10,
// 	pagination: true,

// 	// example event handler
// };

// get div to host the grid
var eGridDiv = document.getElementById("myGrid");
// var eGridDivApproval = document.getElementById("myGridApproval");
// new grid instance, passing in the hosting DIV and Grid Options
new agGrid.Grid(eGridDiv, gridOptions);
// new agGrid.Grid(eGridDivApproval, gridApprovalOptions);

//Button Actions
var showFormResponse = function (responseText, statusText) {
    if (responseText.success) {
        showInfo("Data already save");
        getData();
        // getDataApproval();
    } else {
        showInfo(responseText.message);
        return false;
    }
};

$("#btn-edit").click(function () {
    if (selr) {
        console.log("sel>>", selr);
        // var buttons = {
        // 	success: {
        // 		label: "<i class='icon-ok'></i> Save",
        // 		className: "btn-sm btn-success",
        // 		callback: function () {
        // 			var options = {
        // 				url:
        // 					GLOBAL_MAIN_VARS["SITE_URL"] +
        // 					"user_and_group/save_user_group_edit",
        // 				type: "post",
        // 				// beforeSubmit: jqformValidate,
        // 				success: showFormResponse,
        // 				dataType: "json",
        // 			};
        // 			console.log("ini test iqra " + checkValidate());
        // 			if (checkValidate() == false) {
        // 				return false;
        // 			}
        // 			$("form").ajaxSubmit(options);
        // 		},
        // 	},
        // 	button: {
        // 		label: "Close",
        // 		className: "btn-sm",
        // 	},
        // };

        var buttons = {
            "success":
            {

                "label": "<i class='icon-ok'></i> Save",
                "className": "btn-sm btn-success",
                "callback": function () {

                    var options = {
                        url: GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/user_and_group/save_user_group_edit",
                        type: "post",
                        success: showFormResponse,
                        dataType: 'json',
                    };
                    // if (TOKEN_VALID == false) { return false; }

                    $('form').ajaxSubmit(options);
                }
            },
            "button":
            {
                "label": "Close",
                "className": "btn-sm"
            }
        }
        showCommonDialog(
            900,
            500,
            "Edit Team",
            GLOBAL_MAIN_VARS["SITE_URL"] +
            "user_and_group/user_and_group/user_group_edit_form?id=" +
            selr,
            buttons
        );
    } else {
        showWarning("Silakan pilih data");
    }
});

$("#btn-add").click(function () {
    var buttons = {
        success: {
            label: "<i class='icon-ok'></i> Save",
            className: "btn-sm btn-success",
            callback: function () {
                var options = {
                    url:
                        GLOBAL_MAIN_VARS["SITE_URL"] +
                        "user_and_group/user_and_group/save_user_group_add",
                    type: "post",
                    // beforeSubmit: jqformValidate,
                    success: showFormResponse,
                    dataType: "json",
                };
                if (checkValidate() == false) {
                    return false;
                }
                $("form").ajaxSubmit(options);
            },
        },
        button: {
            label: "Close",
            className: "btn-sm",
        },
    };

    showCommonDialog(
        900,
        500,
        "ADD USER GROUP",
        GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/user_and_group/user_group_add_form",
        buttons
    );
});

$("#btn-del").click(function () {
    if (selr) {
        console.log(selr);
        bootbox.confirm(
            "Are you sure you want to delete " + selected_data.id + "?",
            function (result) {
                if (result) {
                    $.post(
                        GLOBAL_MAIN_VARS["SITE_URL"] +
                        "user_and_group/user_and_group/remove_user_group/",
                        { id_user: selr },
                        function (data) {
                            if (data.success == true) {
                                showInfo(data.message);
                                getData();
                            } else {
                                showInfo(data.message);
                                return false;
                            }
                        },
                        "json"
                    );
                }
            }
        );
    } else {
        alert("Please select a row.");
    }
});

$("#btn-export-excel").click(function () {
    gridOptions.api.exportDataAsExcel();
});

$(document).ready(function () {
    getData(); // untuk menampilkan data di table nya
})
