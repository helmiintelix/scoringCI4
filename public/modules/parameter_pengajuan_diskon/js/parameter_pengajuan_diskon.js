<<<<<<< HEAD
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
=======

var selr='';
var selected_data;
var TOKEN_VALID = false;


function deselect() {
  gridOptions.api.deselectAll()
>>>>>>> 7325b351ce173c32b50ebaff1546e2e66095a663
}

function getData() {
  $.ajax({
<<<<<<< HEAD
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "parameter_pengajuan_diskon/discount_parameter/get_discount_parameter_list" +
=======
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "parameter_pengajuan_diskon/parameter_pengajuan_diskon/get_discount_parameter_list" +
>>>>>>> 7325b351ce173c32b50ebaff1546e2e66095a663
      classification,
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;
<<<<<<< HEAD

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
=======
      
      gridOptions.api.setGridOption('columnDefs', columnDefs);
      gridOptions.api.setGridOption('rowData', msg.data.data);
    },
    dataType: 'json',
  });
}	

// Grid Options are properties passed to the grid
var gridOptions = {
    columnDefs: [
    { field: '' },
    { field: '' },
    { field: '' },
    { field: '' },
    { field: '' },
    { field: '' },
    { field: '' },
    { field: '' },
    { field: '' },
    { field: '' },
    { field: '' },
    { field: '' }
>>>>>>> 7325b351ce173c32b50ebaff1546e2e66095a663
  ],

  // default col def properties get applied to all columns
  // defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },
<<<<<<< HEAD
  defaultColDef: {
    sortable: true,
    filter: "agSetColumnFilter",
    floatingFilter: true,
    resizable: true,
  },

  rowSelection: "multiple", // allow rows to be selected
=======
  defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },

  rowSelection: 'single', // allow rows to be selected
>>>>>>> 7325b351ce173c32b50ebaff1546e2e66095a663
  animateRows: true, // have rows animate to new positions when sorted
  paginationAutoPageSize: true,
  pagination: true,

  // example event handler
<<<<<<< HEAD
  onCellClicked: (params) => {
    console.log("cell was clicked", params);
    selr = params.data.discount_parameter_id;
    selected_data = params.data;
  },
};
var eGridDiv = document.getElementById("myGridPpd");
=======
  onCellClicked: params => {
    console.log('cell was clicked', params)
    selr = params.data.id;
    console.log(selr);
    selected_data = params.data;
  }
};

// get div to host the grid
var eGridDiv = document.getElementById("myGrid");
// new grid instance, passing in the hosting DIV and Grid Options
>>>>>>> 7325b351ce173c32b50ebaff1546e2e66095a663
new agGrid.Grid(eGridDiv, gridOptions);

//Button Actions
var showFormResponse = function (responseText, statusText) {
  if (responseText.success) {
    showInfo(responseText.message);
    getData();
<<<<<<< HEAD
=======

>>>>>>> 7325b351ce173c32b50ebaff1546e2e66095a663
    if (responseText.notification_id) {
      sendNotification(responseText.notification_id);
    }
  } else {
    showInfo(responseText.message);
    return false;
  }
<<<<<<< HEAD
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
            "parameter_pengajuan_diskon/discount_parameter/save_discount_parameter_add",
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
    "ADD Discount PARAMETER",
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "parameter_pengajuan_diskon/discount_parameter/discount_parameter_add_form",
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
              "parameter_pengajuan_diskon/discount_parameter/save_discount_parameter_edit",
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
      "Edit Discount Parameter",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "parameter_pengajuan_diskon/discount_parameter/discount_parameter_edit_form?id=" +
        selr,
      buttons
    );
=======
}

$("#btn-edit").click(function () {

  if (selr) {

    var buttons = {
      "success":
      {

        "label": "<i class='icon-ok'></i> Save",
        "className": "btn-sm btn-success",
        "callback": function () {

          var options = {
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "setup_wa_number/setup_wa_number/save_device_edit",
            type: "post",
            success: showFormResponse,
            dataType: 'json',
          };
          // if (TOKEN_VALID == false) { return false; }

          $('form').ajaxSubmit(options);
        }
      },
      "button":
      {
        "label": "Close",
        "className": "btn-sm"
      }
    }

    showCommonDialog(800, 800, 'EDIT SETUP DEVICE', GLOBAL_MAIN_VARS["SITE_URL"] + 'setup_wa_number/setup_wa_number/device_edit_form?id=' + selr, buttons);
>>>>>>> 7325b351ce173c32b50ebaff1546e2e66095a663
  } else {
    showWarning("Silakan pilih data");
  }
});

<<<<<<< HEAD
$("#btn-export-excel").click(function () {
  gridOptions.api.exportDataAsExcel();
});

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
=======
$("#btn-add").click(function () {
  var buttons = {
    "success":
    {

      "label": "<i class='icon-ok'></i> Save",
      "className": "btn-sm btn-success",
      "callback": function () {
        var options = {
          url: GLOBAL_MAIN_VARS["SITE_URL"] + "parameter_pengajuan_diskon/parameter_pengajuan_diskon/discount_parameter_add_form",
          type: "post",
          // beforeSubmit: jqformValidate,
          success: showFormResponse,
          dataType: 'json',
        };

        $('form').ajaxSubmit(options);
      }
    },
    "button":
    {
      "label": "Close",
      "className": "btn-sm"
    }
  }

  showCommonDialog(800, 800, 'ADD DISCOUNT PARAMETER', GLOBAL_MAIN_VARS["SITE_URL"] + '/parameter_pengajuan_diskon/parameter_pengajuan_diskon/discount_parameter_add_form', buttons);
});

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya	
})
>>>>>>> 7325b351ce173c32b50ebaff1546e2e66095a663
