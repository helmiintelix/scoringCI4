
var selr='';
var selected_data='';
var TOKEN_VALID = false;


function deselect() {
  gridOptions.api.deselectAll()
  gridApprovalOptions.api.deselectAll()
}

function getData() {
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "setup_area_branch/setup_area_branch/area_branch_list_temp" +
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
    selr = params.data.id;
    selected_data = params.data.area_id;
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

$("#btn-approve").click(function () {
    if (selr) {
      bootbox.confirm("Are you sure to approve this request ?",function (result) {
        if (result) {
            $.get(
              GLOBAL_MAIN_VARS["SITE_URL"] +
                "setup_area_branch_temp/setup_area_branch_temp/save_area_branch_edit_temp",
              {
                id: selr,
                arr_kk: selected_data,
              },
              function (data) {
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
    } else {
        showWarning("Silakan pilih data");
    }
  });
  
  $("#btn-reject").click(function () {
    if (selr) {
      bootbox.confirm("Are you sure to reject this request ?", function (result) {
        if (result) {
          $.get(
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "setup_area_branch_temp/setup_area_branch_temp/save_note_reject_area_branch",
            {
              id: selr,
              arr_kk: selected_data,
            },
            function (data) {
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
    }  else {
        showWarning("Silakan pilih data");
    }
  });

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya	
})