
var selr='';
var selected_data;
var TOKEN_VALID = false;


function deselect() {
  gridOptions.api.deselectAll()
  gridApprovalOptions.api.deselectAll()
}

function getData() {
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "setup_area_branch/setup_area_branch/area_branch_list" +
      classification,
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;
      
      gridOptions.api.setGridOption('columnDefs', columnDefs);
      gridOptions.api.setGridOption('rowData', msg.data.data);
    },
    dataType: 'json',
  });
}


function getDataApproval() {
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "setup_area_branch/setup_area_branch/area_branch_list_temp",
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;

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
  ],

  // default col def properties get applied to all columns
  // defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },
  defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },

  rowSelection: 'single', // allow rows to be selected
  animateRows: true, // have rows animate to new positions when sorted
  paginationAutoPageSize: true,
  pagination: true,

  // example event handler
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
  ],

  // default col def properties get applied to all columns
  // defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },
  defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },

  rowSelection: 'single', // allow rows to be selected
  animateRows: true, // have rows animate to new positions when sorted
  paginationAutoPageSize: true,
  paginationPageSize: 10,
  pagination: true,

  // example event handler
};

// get div to host the grid
var eGridDiv = document.getElementById("myGrid");
var eGridDivApproval = document.getElementById("myGridApproval");
// new grid instance, passing in the hosting DIV and Grid Options
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
  } else {
    showWarning("Silakan pilih data");
  }
});

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