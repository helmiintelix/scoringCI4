<<<<<<< HEAD

var selr='';
var selected_data;
var TOKEN_VALID = false;


function deselect() {
  gridOptions.api.deselectAll()
  gridApprovalOptions.api.deselectAll()
=======
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
>>>>>>> 946f0afe020db0da9ae2071796f9cc371f857046
}

function getData() {
  $.ajax({
<<<<<<< HEAD
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "surat_peringatan_sp_template/surat_peringatan_sp_template/letter_list" +
=======
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "surat_peringatan_sp_template/letter_template/letter_list" +
>>>>>>> 946f0afe020db0da9ae2071796f9cc371f857046
      classification,
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;
<<<<<<< HEAD
      
      gridOptions.api.setGridOption('columnDefs', columnDefs);
      gridOptions.api.setGridOption('rowData', msg.data.data);
    },
    dataType: 'json',
  });
}


function getDataApproval() {
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "surat_peringatan_sp_template/surat_peringatan_sp_template/letter_list_temp",
=======

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
function getDataApproval() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "settings/letter_template_temp/letter_list_temp" +
      classification,
>>>>>>> 946f0afe020db0da9ae2071796f9cc371f857046
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;

<<<<<<< HEAD
      columnDefs.forEach(function(column) {
          if (column.field === 'id') {
              column.hide = true;
          }
      });
      
      gridApprovalOptions.api.setGridOption('columnDefs', columnDefs);
      gridApprovalOptions.api.setGridOption('rowData', msg.data.data);
    },
    dataType: 'json',
  });
}
getDataApproval(); // untuk menampilkan data di table nya	

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
=======
      columnDefs.forEach(function (column) {
        if (column.field === "content") {
          column.cellRenderer = contentRender;
        }
        if (column.field === "flag_tmp") {
          column.cellRenderer = flagTmplRender;
        }
        column.autoHeight = true;
      });
      gridApprovalOptions.api.setGridOption("columnDefs", msg.data.header);
      gridApprovalOptions.api.setGridOption("rowData", msg.data.data);
      gridApprovalOptions.api.autoSizeAllColumns();
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
>>>>>>> 946f0afe020db0da9ae2071796f9cc371f857046
  ],

  // default col def properties get applied to all columns
  // defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },
<<<<<<< HEAD
  defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },

  rowSelection: 'single', // allow rows to be selected
=======
  defaultColDef: {
    sortable: true,
    filter: "agSetColumnFilter",
    floatingFilter: true,
    resizable: true,
  },

  rowSelection: "multiple", // allow rows to be selected
>>>>>>> 946f0afe020db0da9ae2071796f9cc371f857046
  animateRows: true, // have rows animate to new positions when sorted
  paginationAutoPageSize: true,
  pagination: true,

  // example event handler
<<<<<<< HEAD
  onCellClicked: params => {
    console.log('cell was clicked', params)
    selr = params.data.id;
    selected_data = params.data;
  }
};


var gridApprovalOptions = {

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
    { field: '' },
    { field: '' },
    { field: '' }
=======
  onCellClicked: (params) => {
    console.log("cell was clicked", params);
    selr = params.data.letter_id;
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
>>>>>>> 946f0afe020db0da9ae2071796f9cc371f857046
  ],

  // default col def properties get applied to all columns
  // defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },
<<<<<<< HEAD
  defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },

  rowSelection: 'single', // allow rows to be selected
=======
  defaultColDef: {
    sortable: true,
    filter: "agSetColumnFilter",
    floatingFilter: true,
    resizable: true,
  },

  rowSelection: "multiple", // allow rows to be selected
>>>>>>> 946f0afe020db0da9ae2071796f9cc371f857046
  animateRows: true, // have rows animate to new positions when sorted
  paginationAutoPageSize: true,
  paginationPageSize: 10,
  pagination: true,

  // example event handler
};
<<<<<<< HEAD

// get div to host the grid
var eGridDiv = document.getElementById("myGrid");
var eGridDivApproval = document.getElementById("myGridApproval");
// new grid instance, passing in the hosting DIV and Grid Options
=======
var eGridDiv = document.getElementById("myGridSpt");
var eGridDivApproval = document.getElementById("myGridApproval");

>>>>>>> 946f0afe020db0da9ae2071796f9cc371f857046
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
<<<<<<< HEAD
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
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "setup_area_branch/setup_area_branch/save_area_branch_edit",
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

    showCommonDialog(800, 800, 'EDIT AREA BRANCH', GLOBAL_MAIN_VARS["SITE_URL"] + 'setup_area_branch/setup_area_branch/area_branchEditForm?id=' + selr, buttons);
=======
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
            "surat_peringatan_sp_template/letter_template/save_letter_add",
          type: "post",
          // beforeSubmit: jqformValidate,
          success: showFormResponse,
          dataType: "json",
          data: { content: tinyMCE.activeEditor.getContent() },
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
    "ADD SP TEMPLATE",
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "surat_peringatan_sp_template/letter_template/letter_add_form",
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
              "surat_peringatan_sp_template/letter_template/save_letter_edit",
            type: "post",
            success: showFormResponse,
            dataType: "json",
            data: { content: tinyMCE.activeEditor.getContent() },
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
      "EDIT SP TEMPLATE",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "surat_peringatan_sp_template/letter_template/letterEditForm?id=" +
        selr,
      buttons
    );
>>>>>>> 946f0afe020db0da9ae2071796f9cc371f857046
  } else {
    showWarning("Silakan pilih data");
  }
});

<<<<<<< HEAD
$("#btn-add").click(function () {
  var buttons = {
    "success":
    {

      "label": "<i class='icon-ok'></i> Save",
      "className": "btn-sm btn-success",
      "callback": function () {
        var options = {
          url: GLOBAL_MAIN_VARS["SITE_URL"] + "setup_area_branch/setup_area_branch/save_area_branch_add",
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

  showCommonDialog(800, 800, 'ADD AREA BRANCH', GLOBAL_MAIN_VARS["SITE_URL"] + '/setup_area_branch/setup_area_branch/area_branch_add_form', buttons);
});

$("#btn-export-excel").click(function () {
  gridOptions.api.exportDataAsExcel();
})
jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya	
})
=======
$("#btn-export-excel").click(function () {
  gridOptions.api.exportDataAsExcel();
});

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
>>>>>>> 946f0afe020db0da9ae2071796f9cc371f857046
