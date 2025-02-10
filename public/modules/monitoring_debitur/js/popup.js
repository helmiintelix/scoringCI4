var selr;
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
      "monitoring_old/monitoring/popup_list?kategori=" +
      kategori +
      "&userid=" +
      userId,
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;

      console.log("test branch");
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

  defaultColDef: {
    sortable: true,
    filter: "agSetColumnFilter",
    floatingFilter: true,
    resizable: true,
  },

  rowSelection: "multiple",
  animateRows: true,
  paginationAutoPageSize: true,
  pagination: true,

  onCellClicked: (params) => {
    console.log("cell was clicked", params);
    selr = params.data.user_id;
    selected_data = params.data;
  },
};
var eGridDiv = document.getElementById("myGridMdPopup");
new agGrid.Grid(eGridDiv, gridOptions);

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
$("#btn-export-excel").click(function () {
  gridOptions.api.exportDataAsExcel();
});

jQuery(function ($) {
  getData();
});
