
var selr = null;
var selected_data = '';
function deselect() {
    gridOptions.api.deselectAll();
    gridApprovalOptions.api.deselectAll();
}

function getData() {
    $.ajax({
        url:
            GLOBAL_MAIN_VARS["SITE_URL"] +
            "user_and_group/user_and_group/user_group_management_list_temp",
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
            "user_and_group/user_and_group/user_group_management_list_temp",
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

        selr = params.data.groupId;
        selected_data = params.data;
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
var eGridDiv = document.getElementById("myGridApproval1");
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


$("#btn-approve").click(function () {
    if (selr) {
        console.log(selr);
        bootbox.confirm(
            "Approve this activity ?",
            function (result) {
                if (result) {
                    $.post(
                        GLOBAL_MAIN_VARS["SITE_URL"] +
                        "user_and_group/user_and_group/approved_user_group",
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
        showWarning("Please select a row.");
    }
});

$("#btn-reject").click(function () {
    if (selr) {
        console.log(selr);
        bootbox.confirm(
            "Reject this activity ?",
            function (result) {
                if (result) {
                    $.post(
                        GLOBAL_MAIN_VARS["SITE_URL"] +
                        "user_and_group/user_and_group/reject_user_group",
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
        showWarning("Please select a row.");
    }
});
$("#btn-view").click(function () {
    if (selr) {
        var buttons = {
            success: {
                label: "<i class='icon-ok'></i> Approve",
                className: "btn-sm btn-success",
                callback: function () {
                    $.post(
                        GLOBAL_MAIN_VARS["SITE_URL"] +
                        "user_and_group/user_and_group/approved_user_group",
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
                },
            },
            reject: {
                label: "<i class='icon-ok'></i> Reject",
                className: "btn-sm btn-danger",
                callback: function () {
                    $.post(
                        GLOBAL_MAIN_VARS["SITE_URL"] +
                        "user_and_group/user_and_group/reject_user_group",
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
            "VIEW GROUP",
            GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/user_and_group/user_group_view_form?id=" + selr,
            buttons
        );
    } else {
        showWarning("Please select a row.");
    }
});

$("#btn-export-excel").click(function () {
    gridOptions.api.exportDataAsExcel();
});

$(document).ready(function () {
    getData(); // untuk menampilkan data di table nya
});
