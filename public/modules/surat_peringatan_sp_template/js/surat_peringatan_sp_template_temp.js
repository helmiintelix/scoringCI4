var selr;
var selected_data;
var TOKEN_VALID = false;

function contentRender(params) {
  const link = params.data.content;
  return link;
}
function flagTmplRender(params) {
  const link = params.data.flag_tmp;
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
      "settings/letter_template_temp/letter_list_temp" +
      classification,
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;

      columnDefs.forEach(function (column) {
        if (column.field === "content") {
          column.cellRenderer = contentRender;
        }
        if (column.field === "flag_tmp") {
          column.cellRenderer = flagTmplRender;
        }
        column.autoHeight = true;
      });
      gridOptions.api.setGridOption("columnDefs", msg.data.header);
      gridOptions.api.setGridOption("rowData", msg.data.data);
      gridOptions.api.autoSizeAllColumns();
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
    selr = params.data.letter_id;
    selected_data = params.data;
  },
};
var eGridDiv = document.getElementById("myGridTemp");
new agGrid.Grid(eGridDiv, gridOptions);

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

$("#btn-approve").click(function () {
  if (selr) {
    bootbox.confirm(
      "Are you sure to approve this request ?",
      function (result) {
        if (result) {
          $.get(
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "settings/letter_template_temp/save_letter_edit_temp",
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
      }
    );
  }
});

$("#btn-reject").click(function () {
  if (selr) {
    bootbox.confirm("Are you sure to reject this request ?", function (result) {
      if (result) {
        $.get(
          GLOBAL_MAIN_VARS["SITE_URL"] +
            "settings/letter_template_temp/delete_letter_edit_temp",
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
jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
