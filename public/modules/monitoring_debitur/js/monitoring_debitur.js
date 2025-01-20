var selr;
var selected_data;
var TOKEN_VALID = false;

function mapsRenderer(params) {
  const link = params.data.track;
  return link;
}
function picture1Renderer(params) {
  const link = params.data.track;
  return link;
}
function picture2Renderer(params) {
  const link = params.data.track;
  return link;
}
function picture3Renderer(params) {
  const link = params.data.track;
  return link;
}
function picture4Renderer(params) {
  const link = params.data.track;
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
      "monitoring_old/monitoring/customer_list" +
      classification,
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;

      columnDefs.forEach(function (column) {
        if (column.field === "location") {
          column.cellRenderer = mapsRenderer;
        }
        if (column.field === "picture1") {
          column.cellRenderer = picture1Renderer;
        }
        if (column.field === "picture2") {
          column.cellRenderer = picture2Renderer;
        }
        if (column.field === "picture3") {
          column.cellRenderer = picture3Renderer;
        }
        if (column.field === "picture4") {
          column.cellRenderer = picture4Renderer;
        }
      });
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
var eGridDiv = document.getElementById("myGridMd");
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

var TrackingAgent = function (lat, long, user_id) {
  // alert(
  //   `Latitude: ${lat}, Longitude: ${long}, User ID: ${user_id}, ID: ${id}, Account No: ${card}, Address Type: ${addr_type}`
  // );
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
      "monitoring_old/monitoring/tracking?lat=" +
      lat +
      "&long=" +
      long +
      "&user_id=" +
      user_id,
    buttons
  );
};
