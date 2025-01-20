var selr;
var selected_data;
var TOKEN_VALID = false;

function labelRenderer(params) {
  const link = params.data.is_active;
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
      "settings/deviation_approval/deviation_approval_list" +
      classification,
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;
      columnDefs.forEach(function (column) {
        // if (column.field === "flag" || column.field === "id") {
        //   column.hide = true;
        // }
        if (column.field === "is_active") {
          column.cellRenderer = labelRenderer;
        }
      });
      gridOptions.api.setGridOption("columnDefs", msg.data.header);
      gridOptions.api.setGridOption("rowData", msg.data.data);
      // gridOptions.api.sizeColumnsToFit();
    },
    dataType: "json",
  });
}
function getDataApproval() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "settings/deviation_approval_temp/deviation_approval_list_temp" +
      classification,
    type: "get",
    success: function (msg) {
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
    dev_app_id = params.data.dev_app_id;
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
var eGridDiv = document.getElementById("myGridDrau");
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
            GLOBAL_MAIN_VARS["SITE_URL"] +
            "settings/deviation_approval/save_deviation_approval_add",
          type: "post",
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
    "ADD DEVIATION APPROVAL",
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "settings/deviation_approval/deviation_approval_add_form",
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
              "settings/deviation_approval/save_deviation_approval_edit",
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
      "EDIT DEVIATION APPROVAL",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "settings/deviation_approval/deviation_approvalEditForm?id=" +
        selr,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});
$("#btn-del").click(function () {
  // alert(selr);
  if (selr) {
    bootbox.confirm("Are you sure to delete this data ?", function (result) {
      if (result) {
        $.post(
          GLOBAL_MAIN_VARS["SITE_URL"] +
            "settings/deviation_approval/delete_deviation_approval",
          {
            dev_app_id: dev_app_id,
            csrf_security: $("#csrf_token").val(),
          },
          function (data) {
            console.log(data);
            if (data.success == true) {
              showInfo(data.message);
              if (data.newCsrfToken) {
                $("#csrf_token").val(data.newCsrfToken);
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

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
