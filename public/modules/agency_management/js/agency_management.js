var selr;
var selected_data;
var TOKEN_VALID = false;

function labelRenderer(params) {
  const link = params.data.status_approval;
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
      "settings_am/settings_am_am/settings_am_list" +
      classification,
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;

      columnDefs.forEach(function (column) {
        if (column.field === "status_approval") {
          column.cellRenderer = labelRenderer;
          column.cellStyle = { textAlign: "center" };
        }
      });
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
      "settings_am/settings_am_temp/settings_am_list_temp" +
      classification,
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;

      columnDefs.forEach(function (column) {
        if (column.field === "status_approval") {
          column.cellRenderer = labelRenderer;
          column.cellStyle = { textAlign: "center" };
        }
      });
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
    selr = params.data.agency_id;
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
var eGridDiv = document.getElementById("myGridAm");
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
            "settings_am/settings_am_am/save_am_add",
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
    "ADD AGENCY",
    GLOBAL_MAIN_VARS["SITE_URL"] + "settings_am/settings_am_am/am_add_form",
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
              "settings_am/settings_am_am/save_am_edit",
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
      "EDIT AGENCY",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "settings_am/settings_am_am/am_edit_form?id=" +
        selr,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});
$("#btn-del").click(function () {
  if (selr) {
    bootbox.confirm("Are you sure to delete this data ?", function (result) {
      if (result) {
        $.get(
          GLOBAL_MAIN_VARS["SITE_URL"] +
            "settings_am/settings_am_am/delete_agency_to_waiting_approval",
          {
            id: selr,
          },
          function (data) {
            console.log(data);
            if (data.success == true) {
              showInfo(data.message);
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

$("#btn-export-excel").click(function () {
  gridOptions.api.exportDataAsExcel();
});

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
