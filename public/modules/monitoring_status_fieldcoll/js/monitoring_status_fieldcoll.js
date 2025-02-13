var selr;
var selected_data;
var TOKEN_VALID = false;

function mapsRenderer(params) {
  console.log(params, "params");
  const link = params.data.maps;
  return link;
}

function deselect() {
  gridOptions.api.deselectAll();
  gridApprovalOptions.api.deselectAll();
}

function getData() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "visit_radius/monitor_field_coll_view/monitor_field_coll_list" +
      classification,
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;

      columnDefs.forEach(function (column) {
        if (column.field === "maps") {
          column.cellRenderer = mapsRenderer;
          column.cellStyle = { textAlign: "center" };
        }
      });
      // console.log("test branch");
      // console.log(msg);
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
    selr = params.data.restructure_parameter_id;
    selected_data = params.data;
  },
};
var eGridDiv = document.getElementById("myGridMsf");
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

var TrackingAgent = function (long, lat, user_id) {
  var buttons = {
    button: {
      label: "Close",
      className: "btn-sm",
    },
  };
  showCommonDialog(
    1000,
    1000,
    "TRACKING",
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "visit_radius/monitor_field_coll_view/tracking_history?user_id=" +
      user_id,
    buttons
  );
};
