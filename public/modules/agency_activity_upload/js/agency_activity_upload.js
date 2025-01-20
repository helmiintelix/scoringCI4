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
      "agency/upload_activity/get_activity_file_list" +
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
var eGridDiv = document.getElementById("myGridAcu");
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
    showWarning(responseText.message);
    return false;
  }
};

$("#btn-upload").click(function () {
  var buttons = {
    success: {
      label: "<i class='icon-ok'></i> Save",
      className: "btn-sm btn-success",
      callback: function () {
        var options = {
          url:
            GLOBAL_MAIN_VARS["SITE_URL"] + "agency/upload_activity/save_file",
          type: "post",
          dataType: "json",
          success: function (msg) {
            if (msg.success == true) {
              alert(msg.id);
              var buttons = {
                success: {
                  label: "<i class='icon-ok'></i> Upload",
                  className: "btn-sm btn-success",
                  callback: function () {
                    var options = {
                      url:
                        GLOBAL_MAIN_VARS["SITE_URL"] +
                        "agency/upload_activity/upload_file",
                      type: "post",
                      data: {
                        id: msg.id,
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
                "UPLOAD ACTIVITY FILE",
                GLOBAL_MAIN_VARS["SITE_URL"] +
                  "agency/upload_activity/show_uploaded_file_form?id=" +
                  msg.id,
                buttons
              );
            }
          },
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
    "UPLOAD ACTIVITY FILE",
    GLOBAL_MAIN_VARS["SITE_URL"] + "agency/upload_activity/upload_file_form",
    buttons
  );
});
$("#btn-view").click(function () {
  if (selr) {
    var buttons = {
      success: {
        label: "<i class='icon-ok'></i> Upload",
        className: "btn-sm btn-success",
        callback: function () {
          var options = {
            url:
              GLOBAL_MAIN_VARS["SITE_URL"] +
              "agency/upload_activity/upload_file",
            type: "post",
            data: {
              id: selr,
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
      "UPLOAD ACTIVITY FILE",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "agency/upload_activity/show_uploaded_file_form?id=" +
        selr,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});

$("#btn-download").click(function (e) {
  window.location.href = basePath + "/file_upload/format_upload_activity.xlsx";
});

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
