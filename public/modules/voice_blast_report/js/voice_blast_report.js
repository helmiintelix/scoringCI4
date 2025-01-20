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
      "reportCols/load_voiceblast/get_report_voice_blast_list" +
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
    selr = params.data.restructure_parameter_id;
    selected_data = params.data;
  },
};
var eGridDiv = document.getElementById("myGridVbr");
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
    "reportCols/load_voiceblast/get_report_voice_blast_list";
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
