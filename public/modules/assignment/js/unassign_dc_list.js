var selr;
var selected_data;
var TOKEN_VALID = false;
var customer_id = "";

function deselect() {
  gridOptions.api.deselectAll();
  gridApprovalOptions.api.deselectAll();
}

function getData() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "assignment/assignment/account_unassignment_to_dc_list" +
      classification,
    type: "get",
    data: {
      user_id: $("#user_id").val(),
    },
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
    headerCheckboxSelection: isFirstColumn,
    checkboxSelection: isFirstColumn,
  },

  suppressRowClickSelection: true,
  rowSelection: "multiple", // allow rows to be selected
  animateRows: true, // have rows animate to new positions when sorted
  paginationAutoPageSize: true,
  pagination: true,
  onSelectionChanged: onSelectionChanged,
  // example event handler
  onCellClicked: (params) => {
    console.log("cell was clicked", params);
    selr = params.data.CM_CARD_NMBR;
    selected_data = params.data;
  },
};
function isFirstColumn(params) {
  var displayedColumns = params.api.getAllDisplayedColumns();
  var thisIsFirstColumn = displayedColumns[0] === params.column;
  return thisIsFirstColumn;
}
function onSelectionChanged() {
  var selectedRows = gridOptions.api.getSelectedRows();
  customer_id = selectedRows.map((row) => row.CM_CARD_NMBR);
  console.log("Selected IDs:", customer_id.toString().replace(/,/gi, "|"));
}
var eGridDiv = document.getElementById("myGrid");
new agGrid.Grid(eGridDiv, gridOptions);

//Button Actions
var showFormResponse = function (responseText, statusText) {
  if (responseText.success) {
    showInfo(responseText.message);
    // getData();
    if (responseText.notification_id) {
      sendNotification(responseText.notification_id);
    }
  } else {
    showInfo(responseText.message);
    return false;
  }
};

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
