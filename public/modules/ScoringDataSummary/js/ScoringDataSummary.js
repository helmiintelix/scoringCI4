var selr;
var selected_data;

function getData() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "scoring/scoringDataSummary/getScoringDataSummary",
    type: "get",
    success: function (msg) {
      //   gridOptions.api.setGridOption("columnDefs", msg.data.header);
      gridOptions.api.setGridOption("rowData", msg.data.data);
    },
    dataType: "json",
  });
}

// Grid Options are properties passed to the grid
var gridOptions = {
  columnDefs: [
    { headerName: "Tiering ID", field: "tiering_id", width: 120 },
    { headerName: "Tiering Name", field: "tiering_name", width: 200 },
    {
      headerName: "Score From",
      field: "score_from",
      width: 120,
      cellClass: "ag-right-aligned",
    },
    {
      headerName: "Score To",
      field: "score_to",
      width: 120,
      cellClass: "ag-right-aligned",
    },
    {
      headerName: "Score Type",
      field: "score_type",
      width: 120,
      cellClass: "ag-right-aligned",
    },
    {
      headerName: "Score From 2",
      field: "score_from2",
      width: 120,
      cellClass: "ag-right-aligned",
      hide: true,
    },
    {
      headerName: "Score To 2",
      field: "score_to2",
      width: 120,
      cellClass: "ag-right-aligned",
      hide: true,
    },
    {
      headerName: "Cycle",
      field: "cycle_name",
      width: 120,
      cellClass: "ag-right-aligned",
    },
    {
      headerName: "Bucket",
      field: "bucket",
      width: 120,
      cellClass: "ag-right-aligned",
    },
    {
      headerName: "LOB",
      field: "lob",
      width: 120,
      cellClass: "ag-right-aligned",
    },
    { headerName: "Owner", field: "owner", width: 180, hide: true },
    {
      headerName: "Total Data",
      field: "total_data",
      width: 120,
      cellClass: "ag-right-aligned",
    },
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

// get div to host the grid
var eGridDiv = document.getElementById("myGrid");

// new grid instance, passing in the hosting DIV and Grid Options
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

$("#btn-export-excel").click(function () {
  gridOptions.api.exportDataAsExcel();
});
jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
