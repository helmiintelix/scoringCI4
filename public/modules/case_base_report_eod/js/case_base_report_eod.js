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
      "new_report/case_base/case_base_data" +
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
var eGridDiv = document.getElementById("myGridCbr");
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

function initializeSingleDatePicker(elementId) {
  var isEndDate = elementId === "#end_date";

  $(elementId).daterangepicker({
    singleDatePicker: true,
    autoUpdateInput: !isEndDate,
    locale: {
      format: "YYYY-MM-DD",
      cancelLabel: "Close",
    },
    autoApply: true,
  });

  $(elementId).on("apply.daterangepicker", function (ev, picker) {
    if (isEndDate) {
      $(this).val(picker.startDate.format("YYYY-MM-DD"));
    }
  });
}

$(function () {
  initializeSingleDatePicker("#start_date");
  initializeSingleDatePicker("#end_date");
});

$("#btn-search").click(function () {
  var start_date = $("#start_date").val();
  var end_date = $("#end_date").val();
  var sub_product = $("#opt-sub-product").val();
  var search_by = $("#opt-search-by").val();
  var keyword = $("#txt-keyword").val();

  var url =
    GLOBAL_MAIN_VARS["SITE_URL"] + "new_report/case_base/case_base_data";
  var data = {
    start_date: start_date,
    end_date: end_date,
    sub_product: sub_product,
    search_by: search_by,
    keyword: keyword,
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
