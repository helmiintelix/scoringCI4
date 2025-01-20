var selr;
var selected_data;
var TOKEN_VALID = false;
var customer_id;

function deselect() {
  gridOptions.api.deselectAll();
  gridApprovalOptions.api.deselectAll();
}

function getData() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "assignment/reassignment/get_reassignment_list" +
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
  customer_id = selectedRows.map((row) => row.CM_CARD_NMBR);
  console.log("Selected IDs:", customer_id);
}

var eGridDiv = document.getElementById("myGridFar");
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
$("#btn-assign-to").click(function () {
  if (customer_id) {
    var buttons = {
      success: {
        label: "<i class='icon-ok'></i> Save",
        className: "btn-sm btn-success",
        callback: function () {
          var optGroupAssignment = $("#optGroupAssignment").val();
          if (optGroupAssignment == "") {
            alert("Please Select Group");
            return false;
          } else {
            if (optGroupAssignment == "TEAM") {
              $("#opt_id_field_coll").val("");
              $("#opt_id_agency").val("");
              if ($("#opt-id-coll").val() == "") {
                alert("Please select Collector");
                return false;
              }
            }
            if (optGroupAssignment == "FC") {
              $("#opt-id-coll").val("");
              $("#opt_id_agency").val("");
              if ($("#opt_id_field_coll").val() == "") {
                alert("Please select Field Collector");
                return false;
              }
            }
            if (optGroupAssignment == "AGENCY") {
              $("#opt-id-coll").val("");
              $("#opt_id_field_coll").val("");
              if ($("#opt_id_agency").val() == "") {
                alert("Please select Agency");
                return false;
              }
            }
          }
          var options = {
            url:
              GLOBAL_MAIN_VARS["SITE_URL"] +
              "assignment/reassignment/reassignment_request",
            type: "post",
            success: showFormResponse,
            dataType: "json",
          };
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
      "ASSIGN TO ...",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "assignment/reassignment/account_reassignment_form?customer_id=" +
        customer_id,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
