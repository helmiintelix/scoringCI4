var selr;
var selected_data;
var TOKEN_VALID = false;
var customer_id = "";

function deselect() {
  gridOptions.api.deselectAll();
  gridApprovalOptions.api.deselectAll();
}

function getData() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "account_handling/download_account_handling/get_crprd" +
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
    selr = params.data.restructure_parameter_id;
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
  console.log("Selected IDs:", customer_id.toString().replace(/,/gi, "|"));
}
var eGridDiv = document.getElementById("myGridDah");
new agGrid.Grid(eGridDiv, gridOptions);

//Button Actions
var showFormResponse = function (responseText, statusText) {
  if (responseText.success) {
    showInfo(responseText.message);
    // getData();
    if (responseText.notification_id) {
      sendNotification(responseText.notification_id);
    }
  } else {
    showInfo(responseText.message);
    return false;
  }
};

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});

$("#date-range-picker").daterangepicker({
  autoUpdateInput: false,
  locale: {
    cancelLabel: "Clear",
  },
});

$("#date-range-picker").on("apply.daterangepicker", function (ev, picker) {
  $(this).val(
    picker.startDate.format("DD/MM/YYYY") +
      " - " +
      picker.endDate.format("DD/MM/YYYY")
  );
});

$("#date-range-picker").on("cancel.daterangepicker", function (ev, picker) {
  $(this).val("");
});

$("#btn-reset").click(function (e) {
  e.preventDefault();
  $("#date-range-picker").val("");
  $("#opt-petugas").val("");
  $("#btn-search").trigger("click");
});
$("#btn-search").click(function () {
  var date_filter = $("#date-range-picker").val();
  var petugas = $("#opt-petugas").val();
  console.log(date_filter);
  console.log(petugas);

  var url =
    GLOBAL_MAIN_VARS["SITE_URL"] +
    "account_handling/download_account_handling/get_crprd";
  var data = {
    petugas: petugas,
    tgl: date_filter,
  };

  $.ajax({
    url: url,
    type: "GET",
    data: data,
    dataType: "json",
    success: function (response) {
      showFormResponse(response);
      console.log(response);
      gridOptions.api.setGridOption("columnDefs", response.data.header);
      gridOptions.api.setGridOption("rowData", response.data.data);
    },
    error: function (xhr, status, error) {
      console.error("AJAX request error:", error);
    },
  });

  return false;
});

$("#btn-export-excel").click(function () {
  gridOptions.api.exportDataAsExcel();
});

$("#btn-send-email").click(function () {
  if (customer_id) {
    var temp_selrs = customer_id.toString().replace(/,/gi, "|");
    var buttons = {
      success: {
        label: "<i class='icon-ok'></i> Send Mail",
        className: "btn-sm btn-success",
        callback: function () {
          var options = {
            url:
              GLOBAL_MAIN_VARS["SITE_URL"] +
              "account_handling/download_account_handling/send_to_email?",
            type: "post",
            success: showFormResponse,
            dataType: "json",
          };
          // if (TOKEN_VALID == false) { return false; }

          $("#form_add").ajaxSubmit(options);
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
      "Send Contract Master By E-Mail",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "account_handling/download_account_handling/send_to_email_form?customer_id=" +
        temp_selrs,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});
