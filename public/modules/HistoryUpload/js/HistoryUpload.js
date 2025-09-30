
var selr;
var selected_data;
var TOKEN_VALID = false;


function deselect() {
  gridOptions.api.deselectAll()
}

function getData() {
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "historyUpload/historyUpload/summary_upload_list",
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



// get div to host the grid
var eGridDiv = document.getElementById("myGrid");

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


$("#btn-del").click(function () {
  if (selr) {
    console.log(selr);
    bootbox.confirm("Are you sure you want to delete `" + selected_data.file_name + "` ?", function (result) {
      if (result) {
        $.post(GLOBAL_MAIN_VARS["SITE_URL"] + "historyUpload/historyUpload/delete_scheme_upload/", { id_user: selr }, function (data) {
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


$("#btn-export-excel").click(function () {
  gridOptions.api.exportDataAsExcel();
})
jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya	
})