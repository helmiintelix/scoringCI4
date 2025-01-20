var selr;
var selected_data;
var TOKEN_VALID = false;

function bucketListRenderer(params) {
  const link = params.data.bucket_list;
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
      "detail_account/approval/get_restructure_parameter_list" +
      classification,
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;

      columnDefs.forEach(function (column) {
        if (column.field === "bucket_list") {
          column.cellRenderer = bucketListRenderer;
        }
        column.autoHeight = true;
      });
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
var eGridDiv = document.getElementById("myGridPpr");
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

$("#btn-add").click(function () {
  var buttons = {
    success: {
      label: "<i class='icon-ok'></i> Save",
      className: "btn-sm btn-success",
      callback: function () {
        var options = {
          url:
            GLOBAL_MAIN_VARS["SITE_URL"] +
            "detail_account/approval/save_restructure_parameter_add",
          type: "post",
          data: {
            sql: encodeURIComponent(
              $("#query_builder").queryBuilder("getSQL", false, true).sql
            ),
            sql_json: JSON.stringify(
              $("#query_builder").queryBuilder("getRules"),
              null,
              2
            ),
          },
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
    "ADD RESTRUCTURE PARAMETER",
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "detail_account/approval/restructure_parameter_add_form",
    buttons
  );
});
$("#btn-edit").click(function () {
  if (selr) {
    var buttons = {
      success: {
        label: "<i class='icon-ok'></i> Save",
        className: "btn-sm btn-success",
        callback: function () {
          var options = {
            url:
              GLOBAL_MAIN_VARS["SITE_URL"] +
              "detail_account/approval/save_restructure_parameter_edit",
            type: "post",
            data: {
              restructure_parameter_id: $(
                "#txt-restructure-parameter-id"
              ).val(),
              sql: encodeURIComponent(
                $("#query_builder_edit").queryBuilder("getSQL", false, true).sql
              ),
              sql_json: JSON.stringify(
                $("#query_builder_edit").queryBuilder("getRules"),
                null,
                2
              ),
            },
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
      "Edit Restructure Parameter",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "detail_account/approval/restructure_parameter_edit_form?id=" +
        selr,
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
