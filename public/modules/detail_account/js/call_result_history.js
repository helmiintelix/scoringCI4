var selr;
var selected_data;
var TOKEN_VALID = false;

function deselect() {
  gridOptions.api.deselectAll();
  gridSmsHistoryOptions.api.deselectAll();
  gridEmailHistoryOptions.api.deselectAll();
  gridBrHistoryOptions.api.deselectAll();
}

function getData() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "detail_account/detail_account/call_result_history_data?customer_id=" +
      currCustId +
      "&card_no=" +
      currCardNo +
      "&query_filter=" +
      $("#opt-source").val() +
      classification,
    type: "get",
    success: function (msg) {
      gridOptions.api.setGridOption("columnDefs", msg.data.header);
      gridOptions.api.setGridOption("rowData", msg.data.data);
    },
    dataType: "json",
  });
}
function getDataSmsHistory() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "detail_account/detail_account/sms_history_data?card_no=" +
      currCardNo +
      classification,
    type: "get",
    success: function (msg) {
      gridSmsHistoryOptions.api.setGridOption("columnDefs", msg.data.header);
      gridSmsHistoryOptions.api.setGridOption("rowData", msg.data.data);
    },
    dataType: "json",
  });
}
getDataEmailHistory();
function getDataEmailHistory() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "detail_account/detail_account/email_history_data?card_no=" +
      currCardNo +
      classification,
    type: "get",
    success: function (msg) {
      gridEmailHistoryOptions.api.setGridOption("columnDefs", msg.data.header);
      gridEmailHistoryOptions.api.setGridOption("rowData", msg.data.data);
    },
    dataType: "json",
  });
}
getDataEmailHistory();
function getDataBrHistory() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "detail_account/detail_account/br_history_data?card_no=" +
      currCardNo +
      classification,
    type: "get",
    success: function (msg) {
      gridBrHistoryOptions.api.setGridOption("columnDefs", msg.data.header);
      gridBrHistoryOptions.api.setGridOption("rowData", msg.data.data);
    },
    dataType: "json",
  });
}
getDataBrHistory();
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
var gridSmsHistoryOptions = {
  columnDefs: [
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
  paginationPageSize: 10,
  pagination: true,

  // example event handler
};
var gridEmailHistoryOptions = {
  columnDefs: [
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
  paginationPageSize: 10,
  pagination: true,

  // example event handler
};
var gridBrHistoryOptions = {
  columnDefs: [
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
  paginationPageSize: 10,
  pagination: true,

  // example event handler
};
var eGridDiv = document.getElementById("myGridCh");
var eGridDivSmsHistory = document.getElementById("myGridSh");
var eGridDivEmailHistory = document.getElementById("myGridEh");
var eGridDivBrHistory = document.getElementById("myGridBh");

new agGrid.Grid(eGridDiv, gridOptions);
new agGrid.Grid(eGridDivSmsHistory, gridSmsHistoryOptions);
new agGrid.Grid(eGridDivEmailHistory, gridEmailHistoryOptions);
new agGrid.Grid(eGridDivBrHistory, gridBrHistoryOptions);

//Button Actions
var showFormResponse = function (responseText, statusText) {
  if (responseText.success) {
    showInfo(responseText.message);
    // getData();
    getDataSmsHistory();
    getDataEmailHistory();
    getDataBrHistory();

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

$("#btn-search").click(function () {
  var source = $("#opt-source").val();
  // alert(source);

  var url =
    GLOBAL_MAIN_VARS["SITE_URL"] +
    "detail_account/detail_account/call_result_history_data";
  var data = {
    customer_id: currCustId,
    card_no: currCardNo,
    query_filter: source,
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
  $("#opt-source").change(() => {
    let opt = $("#opt-source").val();
    if (opt == "PHONE" || opt == "MOBCOLL" || opt == "VISIT") {
      $("#call_history").show();
      $("#sms_history").hide();
      $("#email_history").hide();
      $("#br_history").hide();
    } else if (opt == "SMS") {
      $("#call_history").hide();
      $("#sms_history").show();
      $("#email_history").hide();
      $("#br_history").hide();
    } else if (opt == "EMAIL") {
      $("#call_history").hide();
      $("#sms_history").hide();
      $("#email_history").show();
      $("#br_history").hide();
    } else if (opt == "BR") {
      $("#call_history").hide();
      $("#sms_history").hide();
      $("#email_history").hide();
      $("#br_history").show();
    }
  });
  return false;
});
