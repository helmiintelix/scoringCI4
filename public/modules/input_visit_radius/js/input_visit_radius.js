
var selr='';
var selected_data;
var TOKEN_VALID = false;


function deselect() {
  gridOptions.api.deselectAll()
  gridApprovalOptions.api.deselectAll()
}

function getData() {
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "input_visit_radius/input_visit_radius/visit_radius_all_list" +
      classification,
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



// get div to host the grid
var eGridDiv = document.getElementById("myGrid");
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
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "input_visit_radius/input_visit_radius/save_visit_radius_all_edit",
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

    showCommonDialog(800, 800, 'INPUT VISIT RADIUS', GLOBAL_MAIN_VARS["SITE_URL"] + 'input_visit_radius/input_visit_radius/visit_radius_all_edit_form?id=' + selr, buttons);
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
          url: GLOBAL_MAIN_VARS["SITE_URL"] + "input_visit_radius/input_visit_radius/save_visit_radius_all_add",
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

  showCommonDialog(800, 800, 'INPUT VISIT RADIUS', GLOBAL_MAIN_VARS["SITE_URL"] + '/input_visit_radius/input_visit_radius/visit_radius_all_add_form', buttons);
});

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya	
})