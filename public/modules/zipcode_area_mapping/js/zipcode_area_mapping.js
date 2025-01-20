var selr;
var selected_data;
var TOKEN_VALID = false;

function labelRenderer(params) {
  const link = params.data.status_pengajuan;
  return link;
}
function flagRenderer(params) {
  const link = params.data.jenis_pengajuan;
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
      "zipcodes/zipcode_area_mapping/zipcode_area_mapping_list" +
      classification,
    type: "get",
    success: function (msg) {
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
      "zipcodes/zipcode_area_mapping_temp/zipcode_area_mapping_list_temp" +
      classification,
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;
      columnDefs.forEach(function (column) {
        if (column.field === "status_pengajuan") {
          column.cellRenderer = labelRenderer;
        }
        if (column.field === "jenis_pengajuan") {
          column.cellRenderer = flagRenderer;
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
var eGridDiv = document.getElementById("myGridFcam");
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
            "zipcodes/zipcode_area_mapping/save_zipcode_area_mapping_add",
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
    "ADD ZIPCODE AREA MAPPING",
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "zipcodes/zipcode_area_mapping/zipcode_area_mapping_add_form",
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
              "zipcodes/zipcode_area_mapping/save_zipcode_area_mapping_edit",
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
      "EDIT ZIPCODE AREA MAPPING",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "zipcodes/zipcode_area_mapping/zipcode_area_mappingEditForm?id=" +
        selr,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
