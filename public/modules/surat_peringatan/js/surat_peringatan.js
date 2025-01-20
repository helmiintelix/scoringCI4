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
      "account_handling/sp_due_list/get_sp_due_list" +
      classification,
    type: "get",
    success: function (msg) {
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
    selr = params.data.no_sp;
    selected_data = params.data;
  },
};
var eGridDiv = document.getElementById("myGridSp");
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

$("#btn-print").click(function () {
  var selectedRows = gridOptions.api.getSelectedRows();
  var temp_selrs = selectedRows
    .map(function (row) {
      return row.id;
    })
    .join("xsplitx");

  var no_sp = selectedRows
    .map(function (row, index) {
      return row.no_sp;
    })
    .join("xsplitx");

  if (selectedRows.length > 0) {
    var printUrl =
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "account_handling/print_sp/?type=print&no_sp=" +
      no_sp;

    window.open(printUrl);

    setTimeout(function () {
      gridOptions.api.refreshCells();
    }, 1000);
  } else {
    showWarning("Silakan pilih data");
  }
});
$("#btn-preview").click(function () {
  var selrs = gridOptions.api.getSelectedRows();
  let no_sp = "";
  const jml = selrs.length - 1;
  selrs.forEach((row, index) => {
    if (index < jml) {
      no_sp += row.no_sp + "xsplitx";
    } else {
      no_sp += row.no_sp;
    }
  });
  if (selrs != "") {
    var buttons = {
      button: {
        label: "CLOSE",
        className: "btn-sm",
      },
    };
    showCommonDialog(
      1000,
      600,
      "Print Preview",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "account_handling/print_sp/?type=preview&no_sp=" +
        no_sp,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
