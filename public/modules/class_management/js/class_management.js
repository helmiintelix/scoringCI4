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
      "classification/classification_list/get_classification_list" +
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
    selr = params.data.classification_id;
    selected_data = params.data;
  },
};

var eGridDiv = document.getElementById("myGridCm");

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
            "classification/classification_list/save_classification_add",
          type: "post",
          data: {
            sql: $("#query_builder").queryBuilder("getSQL", false, true).sql,
            sql_json: JSON.stringify(
              $("#query_builder").queryBuilder("getRules"),
              null,
              2
            ),
          },
          // beforeSubmit: jqformValidate,
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
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "classification/classification_list/classification_add_form",
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
              "classification/classification_list/save_classification_edit",
            type: "post",
            success: showFormResponse,
            dataType: "json",
            data: {
              sql: $("#query_builder").queryBuilder("getSQL", false, true).sql,
              sql_json: JSON.stringify(
                $("#query_builder").queryBuilder("getRules"),
                null,
                2
              ),
            },
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
        "classification/classification_list/classification_edit_form?id=" +
        selr,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});

$("#btn-disable").click(function () {
  var csrf_token = $("#token").val();
  if (selr) {
    bootbox.confirm("Do you want to disable this data?", function (result) {
      if (result) {
        $.post(
          GLOBAL_MAIN_VARS["SITE_URL"] +
            "classification/classification_list/delete_classification",
          {
            id: selr,
            csrf_security: csrf_token,
          },
          function (data) {
            console.log(data);
            if (data.success == true) {
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
    });
  }
});

$("#btn-test-data").click(function () {
  if (selr) {
    var buttons = {
      button: {
        label: "Close",
        className: "btn-sm",
      },
    };

    showCommonDialog(
      800,
      800,
      "TEST CAMPAIGN",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "classification/classification_list/classification_test_form?id=" +
        selr,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});

$("#btn-apply-class").click(function () {
  var csrf_token = $("#token").val();
  if (selr) {
    bootbox.confirm(
      "Apakah anda yakin akan menerapkan classification?",
      function (result) {
        if (result) {
          $.post(
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "classification/classification_list/apply_classification",
            {
              id: selr,
              csrf_security: csrf_token,
            },
            function (data) {
              console.log(data);
              if (data.success == true) {
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
  }
});
$("#btn-export-campaign").click(function () {
  if (selr) {
    location.href =
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "classification/classification_list/get_export_class?id=" +
      selr;
  } else {
    showWarning("Please select the data");
  }
});
$("#btn-export-excel").click(function () {
  gridOptions.api.exportDataAsExcel();
});

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
