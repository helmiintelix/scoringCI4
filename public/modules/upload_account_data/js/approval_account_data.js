var selr = "";
var selected_data = "";
var TOKEN_VALID = false;

function deselect() {
  gridOptions.api.deselectAll();
  gridApprovalOptions.api.deselectAll();
}

function getDataApprove() {
  console.log("WAAW");
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "settings/upload_account_data/get_upload_data_approval",
    type: "get",
    success: function (msg) {
      console.log(msg);
      gridOptions.api.setGridOption("columnDefs", msg.data.header);
      gridOptions.api.setGridOption("rowData", msg.data.data);
    },
    dataType: "json",
  });
}
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
  },
};
var eGridDiv = document.getElementById("myGridLp");
new agGrid.Grid(eGridDiv, gridOptions);

//Button Actions
var showFormResponse = function (responseText, statusText) {
  selr = "";
  if (responseText.success) {
    showInfo(responseText.message);
    getDataApprove();
    if (responseText.notification_id) {
      sendNotification(responseText.notification_id);
    }
  } else {
    showInfo(responseText.message);
    return false;
  }
};

jQuery(function ($) {
  getDataApprove(); // untuk menampilkan data di table nya
});

$("#btn-view").click(function () {
  if (selr) {
    var buttons = {
      success: {
        label: "<i class='icon-ok'></i> Approve",
        className: "btn-sm btn-success",
        callback: function () {
          $.ajax({
            url:
              GLOBAL_MAIN_VARS["SITE_URL"] +
              "settings/upload_account_data/approve_file",
            type: "post",
            data: {
              id: selr,
            },
            success: function (response) {
              showFormResponse(response);
            },
            dataType: "json",
          });
        },
      },
      reject: {
        label: "<i class='icon-ok'></i> Reject",
        className: "btn-sm btn-danger",
        callback: function () {
          $.ajax({
            url:
              GLOBAL_MAIN_VARS["SITE_URL"] +
              "settings/upload_account_data/reject_file",
            type: "post",
            data: {
              id: selr,
            },
            success: function (response) {
              showFormResponse(response);
            },
            dataType: "json",
          });
        },
      },
      button: {
        label: "Close",
        className: "btn-sm",
      },
    };

    showCommonDialog(
      800,
      800,
      "View Data " + selected_data.fileName ?? "",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "settings/upload_account_data/show_uploaded_file_form?id=" +
        selr,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});

$("#btn-download").click(function (e) {
  window.location.href =
    GLOBAL_MAIN_VARS["SITE_URL"] +
    "/template/uploadAccountData/templateAccountData.xlsx";
});
