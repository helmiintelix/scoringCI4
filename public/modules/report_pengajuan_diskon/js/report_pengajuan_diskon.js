var selr, cm_card_nmbr;
var selected_data;
var TOKEN_VALID = false;

function deselect() {
  gridOptions.api.deselectAll();
}

function getData() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "reports/report_pengajuan_diskon/get_report_pengajuan_diskon" +
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
    selr = params.data.id;
    cm_card_nmbr = params.data.cm_card_nmbr;
    selected_data = params.data;
  },
};
var eGridDiv = document.getElementById("myGridRpd");
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
$("#btn-view").click(function () {
  if (selr) {
    var buttons = {
      button: {
        label: "Close",
        className: "btn-sm btn-close-modal",
      },
    };
    showCommonDialog(
      1200,
      700,
      "VIEW DETAIL",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "reports/report_pengajuan_diskon/view_detail_diskon_lunas?account_id=" +
        cm_card_nmbr +
        "&id=" +
        selr +
        "&for=view",
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
