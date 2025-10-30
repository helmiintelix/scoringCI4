
var selr;
var selected_data;
var TOKEN_VALID = false;


function deselect() {
  gridOptions.api.deselectAll()
  gridApprovalOptions.api.deselectAll()
}

function getData() {
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/user_and_group/user_management_list",
    type: "get",
    success: function (msg) {
      gridOptions.api.setGridOption('columnDefs', msg.data.header);
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
    { field: '' }
  ],

  // default col def properties get applied to all columns
  // defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },
  defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },

  rowSelection: 'multiple', // allow rows to be selected
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
    { field: 'id' },
    { field: 'name' },
    { field: 'group_id' },
    { field: 'is_active' },
    { field: 'login_status' },
    { field: 'description' },
    { field: 'is_active' },
    { field: 'created_by' },
    { field: 'created_time' }
  ],

  // default col def properties get applied to all columns
  // defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },
  defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },

  rowSelection: 'multiple', // allow rows to be selected
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
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/user_and_group/save_user_edit",
            type: "post",
            data:$("#form_edit_user").serialize(),
            // beforeSubmit: jqformValidate,
            success: showFormResponse,
            dataType: 'json',
          };

          $.ajax(options);
          
        }
      },
      "button":
      {
        "label": "Close",
        "className": "btn-sm"
      }
    }

    showCommonDialog(800, 800, 'EDIT USER', GLOBAL_MAIN_VARS["SITE_URL"] + 'user_and_group/user_and_group/user_edit_form?id=' + selr, buttons);
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
          url: GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/user_and_group/save_user_add",
          type: "post",
          data:$("#form_edit_user").serialize(),
          // beforeSubmit: jqformValidate,
          success: showFormResponse,
          dataType: 'json',
        };

        $.ajax(options);
      }
    },
    "button":
    {
      "label": "Close",
      "className": "btn-sm"
    }
  }

  showCommonDialog(800, 800, 'ADD USER', GLOBAL_MAIN_VARS["SITE_URL"] + '/user_and_group/user_and_group/user_add_form', buttons);
});

$("#btn-del").click(function () {
  if (selr) {
    console.log(selr);
    bootbox.confirm("Are you sure you want to delete `" + selected_data.name + "` ?", function (result) {
      if (result) {
        $.post(GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/user_and_group/delete_user/", { id_user: selr }, function (data) {
          if (data.success == true) {
            showInfo(data.message);
            getData();
          } else {
            showInfo(data.message);
            return false;
          }
        }, "json");
      }
    });
  } else {
    alert("Please select a row.");
  }
});

$("#btn-reset").click(()=>{
  if (selr) {
    console.log(selr);
    bootbox.confirm("Are you sure you want to reset Password `" + selected_data.name + "` ?", function (result) {
      if (result) {
        $.post(GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/user_and_group/reset_password/", { id_user: selr }, function (data) {
          if (data.success == true) {
            showInfo(data.message);
            getData();
          } else {
            showInfo(data.message);
            return false;
          }
        }, "json");
      }
    });
  } else {
    alert("Please select a row.");
  }
})
$("#btn-force-logout").click(()=>{
  if (selr) {
    console.log(selr);
    bootbox.confirm("Are you sure you want to force `" + selected_data.name + "` out ?", function (result) {
      if (result) {
        $.post(GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/user_and_group/force_logout_user/", { id_user: selr }, function (data) {
          if (data.success == true) {
            showInfo(data.message);
            getData();
          } else {
            showInfo(data.message);
            return false;
          }
        }, "json");
      }
    });
  } else {
    alert("Please select a row.");
  }
})

$("#btn-export-excel").click(function () {
  gridOptions.api.exportDataAsExcel();
})
jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya	
})