var selr;
var selected_data;
var TOKEN_VALID = false;

function mapsRenderer(params) {
  const link = params.data.location;
  return link;
}
function totalAssignmentRenderer(params) {
  const link = params.data.totalAssignment;
  return link;
}
function totalVisitRenderer(params) {
  const link = params.data.totalVisit;
  return link;
}
function totalPtpRenderer(params) {
  const link = params.data.totalVisit;
  return link;
}
function totalPaymentRenderer(params) {
  const link = params.data.totalVisit;
  return link;
}

function deselect() {
  gridOptions.api.deselectAll();
  gridApprovalOptions.api.deselectAll();
}

function getData() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] + "monitoring_old/monitoring/petugas_list",
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;

      columnDefs.forEach(function (column) {
        if (column.field === "location") {
          column.cellRenderer = mapsRenderer;
        }
        if (column.field === "totalAssignment") {
          column.cellRenderer = totalAssignmentRenderer;
        }
        if (column.field === "totalVisit") {
          column.cellRenderer = totalVisitRenderer;
        }
        if (column.field === "totalPtp") {
          column.cellRenderer = totalPtpRenderer;
        }
        if (column.field === "totalPayment") {
          column.cellRenderer = totalPaymentRenderer;
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

var popupdatadetail = function (kategori, userid) {
  var header = "UNDEFINED";
  switch (kategori) {
    case "jumlahAssignment":
      header = "List Assignment";
      break;
    case "totalVisit":
      header = "List Total Visit";
      break;
    case "totalPtp":
      header = "List Total PTP";
      break;
    case "totalPayment":
      header = "List Total Payment";
      break;
  }

  var buttons = {
    button: {
      label: "Close",
      className: "btn-sm",
    },
  };
  console.log(userid);
  showCommonDialog(
    1200,
    300,
    header,
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "monitoring_old/monitoring/popup?kategori=" +
      kategori +
      "&userid=" +
      userid,
    buttons
  );
};
