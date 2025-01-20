
var selr='';
var selected_data;
var TOKEN_VALID = false;


function deselect() {
  gridOptions.api.deselectAll()
}

function getData() {
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "setup_wa_template/setup_wa_template/get_wa_template_list" +
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
    console.log(selr);
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
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "setup_wa_template/setup_wa_template/save_wa_template_edit",
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

    showCommonDialog(800, 800, 'EDIT WA TEMPLATE', GLOBAL_MAIN_VARS["SITE_URL"] + 'setup_wa_template/setup_wa_template/wa_template_edit_form?id=' + selr, buttons);
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
          url: GLOBAL_MAIN_VARS["SITE_URL"] + "setup_wa_template/setup_wa_template/save_wa_template_add",
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

  showCommonDialog(800, 800, 'ADD WA TEMPLATE', GLOBAL_MAIN_VARS["SITE_URL"] + '/setup_wa_template/setup_wa_template/wa_template_add_form', buttons);
});

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya	
})