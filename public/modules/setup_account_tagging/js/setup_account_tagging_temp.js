var selr;
var selected_data;
var TOKEN_VALID = false;
var account_no;

function flagRender(params) {
  const link = params.data.flag_tmp;
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
      "account_handling/setup_account_tagging_list_temp/get_collection_list_temp" +
      classification,
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;

      columnDefs.forEach(function (column) {
        if (column.field === "flag_tmp") {
          column.cellRenderer = flagRender;
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
    selr = params.data.id;
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
  account_no = selectedRows.map((row) => row.CM_CARD_NMBR);
  console.log("Selected IDs:", account_no);
}
var eGridDiv = document.getElementById("myGridTemp");
new agGrid.Grid(eGridDiv, gridOptions);

//Button Actions
var showFormResponse = function (responseText, statusText) {
  if (responseText.success) {
    showInfo(responseText.message);
    getData();
    getDataApproval();

    if (responseText.notification_id) {
      sendNotification(responseText.notification_id);
    }
  } else {
    showInfo(responseText.message);
    return false;
  }
};

$("#btn-approve").click(function () {
  bootbox.confirm(
    "Apakah anda yakin akan melakukan approval ?",
    function (result) {
      if (result) {
        if (account_no == "") {
          showWarning("Silakan pilih data");
          return false;
        } else {
          $.get(
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "account_handling/setup_account_tagging_list_temp/set_account_tagging_approve",
            {
              account_no: account_no,
            },
            function (data) {
              console.log(data);
              if (data.success == true) {
                showInfo(data.message);
                getData();
              } else {
                showInfo(data.message);
                return false;
              }
            },
            "json"
          );
        }
      }
    }
  );
});

$("#btn-reject").click(function () {
  bootbox.confirm(
    "Apakah anda yakin akan melakukan reject?",
    function (result) {
      if (result) {
        if (account_no == "") {
          showWarning("Silakan pilih data");
          return false;
        } else {
          $.get(
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "account_handling/setup_account_tagging_list_temp/set_account_tagging_reject",
            {
              account_no: account_no,
            },
            function (data) {
              console.log(data);
              if (data.success == true) {
                showInfo(data.message);
                getData();
              } else {
                showInfo(data.message);
                return false;
              }
            },
            "json"
          );
        }
      }
    }
  );
});
jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
