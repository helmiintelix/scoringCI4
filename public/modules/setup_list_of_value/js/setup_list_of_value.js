
var selr='';
var selected_data;
var TOKEN_VALID = false;


function deselect() {
  gridOptions.api.deselectAll()
  gridApprovalOptions.api.deselectAll()
}

function getData() {
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "setup_list_of_value/setup_list_of_value/lov_registration" +
      classification,
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;
      columnDefs.forEach(function(column) {
          if (column.field === 'id') {
              column.hide = true;
          }
      });
      
      gridOptions.api.setGridOption('columnDefs', columnDefs);
      gridOptions.api.setGridOption('rowData', msg.data.data);
    },
    dataType: 'json',
  });
}


function getDataApproval() {
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "setup_list_of_value/setup_list_of_value/lov_list_old",
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;

      columnDefs.forEach(function(column) {
          if (column.field === 'lov_id') {
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
    selr = params.data.id_lov_category;
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

  onCellClicked: params => {
    console.log('cell was clicked', params)
    selr = params.data.lov_id;
    selected_data = params.data;
  }
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
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "setup_list_of_value/setup_list_of_value/save_lov_edit",
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

    showCommonDialog(800, 800, 'EDIT LOV', GLOBAL_MAIN_VARS["SITE_URL"] + '/setup_list_of_value/setup_list_of_value/lov_edit_category?id=' + selr, buttons);
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
          url: GLOBAL_MAIN_VARS["SITE_URL"] + "setup_list_of_value/setup_list_of_value/save_lov_add",
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

  showCommonDialog(800, 800, 'ADD LOV', GLOBAL_MAIN_VARS["SITE_URL"] + '/setup_list_of_value/setup_list_of_value/lov_add_form', buttons);
});

$("#btn-edit-relation").click(function () {
    var buttons = {
      "success":
      {
  
        "label": "<i class='icon-ok'></i> Save",
        "className": "btn-sm btn-success",
        "callback": function () {
          var options = {
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "setup_list_of_value/setup_list_of_value/save_lov_relation_edit",
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
  
    showCommonDialog(800, 800, 'EDIT LOV RELATION', GLOBAL_MAIN_VARS["SITE_URL"] + '/setup_list_of_value/setup_list_of_value/lov_edit_form?id=' + selr, buttons);
  });
jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya	
})