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
      "voiceblast/campaign/get_campaign_list" +
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
function getDataApproval() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "voiceblast/campaign_tmp/get_campaign_list_tmp" +
      classification,
    type: "get",
    success: function (msg) {
      console.log("test approval");
      console.log(msg);
      gridApprovalOptions.api.setGridOption("columnDefs", msg.data.header);
      gridApprovalOptions.api.setGridOption("rowData", msg.data.data);
    },
    dataType: "json",
  });
}
getDataApproval();
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
    selr = params.data.id;
    selected_data = params.data;
  },
};
var gridApprovalOptions = {
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
var eGridDiv = document.getElementById("myGridSvbm");
var eGridDivApproval = document.getElementById("myGridApproval");

new agGrid.Grid(eGridDiv, gridOptions);
new agGrid.Grid(eGridDivApproval, gridApprovalOptions);

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

$("#btn-add").click(function () {
  var buttons = {
    success: {
      label: "<i class='icon-ok'></i> Save",
      className: "btn-sm btn-success",
      callback: function () {
        var options = {
          url:
            GLOBAL_MAIN_VARS["SITE_URL"] + "voiceblast/campaign/save_campaign",
          type: "post",
          data: {
            sql: $("#query_builder").queryBuilder("getSQL", false, true).sql,
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
    "ADD CAMPAIGN",
    GLOBAL_MAIN_VARS["SITE_URL"] + "voiceblast/campaign/campaign_add_form",
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
              "voiceblast/campaign/update_campaign",
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
      "EDIT CAMPAIGN",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "voiceblast/campaign/campaign_edit_form?id=" +
        selr,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});

$("#btn-del").click(function () {
  var csrf_token = $("#token").val();

  if (selr) {
    bootbox.confirm(
      "Are you sure you want to delete this data?",
      function (result) {
        if (result) {
          $.post(
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "voiceblast/campaign/delete_campaign",
            {
              id: selr,
              csrf_security: csrf_token,
            },
            function (data) {
              console.log(data);
              if (data.success == true) {
                console.log("test data");
                console.log(data);
                showInfo(data.message);
                if (data.newCsrfToken) {
                  $("#token").val(data.newCsrfToken);
                }
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
    );
  } else {
    alert("Please select a row.");
  }
});
$("#btn-test").click(function () {
  if (selr) {
    var buttons = {
      success: {
        label: "<i class='icon-ok'></i> Save",
        className: "btn-sm btn-success",
      },
      button: {
        label: "Close",
        className: "btn-sm",
      },
    };

    showCommonDialog(
      1300,
      500,
      "EDIT CAMPAIGN",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "voiceblast/campaign/test_data_view?id=" +
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
