var selr, cust_no;
var selected_data;
var TOKEN_VALID = false;

function deselect() {
  gridOptions.api.deselectAll();
  gridApprovalOptions.api.deselectAll();
}

function getData() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "reportCols/load_inventory_collection/get_collection_list" +
      classification,
    type: "get",
    success: function (msg) {
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
    selr = params.data.CM_CARD_NMBR;
    cust_no = params.data.CM_CUSTOMER_NMBR;
    selected_data = params.data;
  },
};
var eGridDiv = document.getElementById("myGridIc");
new agGrid.Grid(eGridDiv, gridOptions);

//Button Actions
var showFormResponse = function (responseText, statusText) {
  if (responseText.success) {
    showInfo(responseText.message);
    getData();
    if (responseText.notification_id) {
      sendNotification(responseText.notification_id);
    }
  } else {
    showInfo(responseText.message);
    return false;
  }
};
$("#btn-inventory-address").click(function () {
  if (selr) {
    var buttons = {
      success: {
        label: "<i class='icon-ok'></i> Ok",
        className: "btn-sm btn-success",
        callback: function () {},
      },
      button: {
        label: "Close",
        className: "btn-sm",
      },
    };
    showCommonDialog(
      1000,
      500,
      "CUSTOMER ADDRESS",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "reportCols/load_inventory_collection/customer_address?account_id=" +
        selr +
        "&customer_id=" +
        cust_no,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});
$("#btn-inventory-email").click(function () {
  if (selr) {
    var buttons = {
      success: {
        label: "<i class='icon-ok'></i> Ok",
        className: "btn-sm btn-success",
        callback: function () {},
      },
      button: {
        label: "Close",
        className: "btn-sm",
      },
    };
    showCommonDialog(
      1000,
      500,
      "CUSTOMER EMAIL",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "reportCols/load_inventory_collection/customer_mail?account_id=" +
        selr +
        "&customer_id=" +
        cust_no,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});
$("#btn-inventory-phone").click(function () {
  if (selr) {
    var buttons = {
      success: {
        label: "<i class='icon-ok'></i> Ok",
        className: "btn-sm btn-success",
        callback: function () {},
      },
      button: {
        label: "Close",
        className: "btn-sm",
      },
    };
    showCommonDialog(
      1000,
      500,
      "CUSTOMER PHONE",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "reportCols/load_inventory_collection/customer_phone?account_id=" +
        selr +
        "&customer_id=" +
        cust_no,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});
$("#btn-export-excel").click(function () {
  gridOptions.api.exportDataAsExcel();
});

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
