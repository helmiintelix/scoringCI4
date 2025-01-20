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
      "detail_account/detail_account/action_code_history_data?customer_id=" +
      currCustId +
      "&card_no=" +
      currCardNo +
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
    selr = params.data.restructure_parameter_id;
    selected_data = params.data;
  },
};
var eGridDiv = document.getElementById("myGrid");
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

$("#btn-search").click(function () {
  var source = $("#opt-source").val();
  // alert(source);

  var url =
    GLOBAL_MAIN_VARS["SITE_URL"] +
    "detail_account/detail_account/action_code_history_data";
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

  return false;
});
