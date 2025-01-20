var selr;
var selected_data;
var TOKEN_VALID = false;
var account_no;

function deselect() {
  gridOptions.api.deselectAll();
  gridApprovalOptions.api.deselectAll();
}

function getData() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "account_handling/assigned_account/get_assigned_account" +
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

  rowSelection: "multiple", // allow rows to be selected
  animateRows: true, // have rows animate to new positions when sorted
  paginationAutoPageSize: true,
  pagination: true,
  onSelectionChanged: onSelectionChanged,

  // example event handler
  // onCellClicked: (params) => {
  //   console.log("cell was clicked", params);
  //   selr = params.data.id;
  //   selected_data = params.data;
  // },
  onRowDoubleClicked: (params) => {
    console.log("Double clicked", params.data.CM_CARD_NMBR);
    var id = params.data.CM_CARD_NMBR;
    var buttons = {
      button: {
        label: "CLOSE",
        className: "btn-sm",
      },
      // save: {
      //   label: "SAVE",
      //   className: "btn btn-sm btn-info",
      //   id: "btnSaveFollowup"
      // },
      // saveandnext: {
      //   label: "SAVE AND NEXT",
      //   className: "btn btn-sm btn-success",
      //   id: "btnSaveAndNext"
      // },
      // saveandbreak: {
      //   label: "SAVE AND BREAK",
      //   className: "btn btn-sm btn-danger",
      //   id: "btnSaveAndbreakFollowup"
      // },
    };

    showFullCommonDialog(
      window.innerWidth - 30,
      window.innerHeight + 50,
      "Followup",
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "detail_account/detail_account?account_id=" +
      id +
      "&cm_card_nmbr=" +
      id,
      buttons
    );
  },
};
function isFirstColumn(params) {
  var displayedColumns = params.api.getAllDisplayedColumns();
  var thisIsFirstColumn = displayedColumns[0] === params.column;
  return thisIsFirstColumn;
}
function onSelectionChanged() {
  var selectedRows = gridOptions.api.getSelectedRows();
  account_no = selectedRows.map((row) => row.CM_CUSTOMER_NMBR);
  console.log("Selected IDs:", account_no);
}

var eGridDiv = document.getElementById("myGridMa");
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
$("#btn-unassigned-add").click(function () {
  if (cr_zip_code) {
    var buttons = {
      success: {
        label: "<i class='icon-ok'></i> Save",
        className: "btn-sm btn-success",
        callback: function () {
          var options = {
            url:
              GLOBAL_MAIN_VARS["SITE_URL"] +
              "account_handling/assigned_account/save_zipcode_area_mapping_assign",
            type: "post",
            success: showFormResponse,
            dataType: "json",
          };
          // if (TOKEN_VALID == false) { return false; }

          $("form").ajaxSubmit(options);
        },
      },
      button: {
        label: "Close",
        className: "btn-sm",
      },
    };

    showCommonDialog(
      800,
      800,
      "EDIT ZIPCODE AREA MAPPING",
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "account_handling/assigned_account/zipcode_assign_mappingAddForm?id=" +
      cr_zip_code,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});

$("#btn-export").click(function () {
  gridOptions.api.exportDataAsExcel();
});

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
