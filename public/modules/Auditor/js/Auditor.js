
var selr;
var selected_data;
var TOKEN_VALID = false;


function deselect() {
  gridOptions.api.deselectAll()
  gridApprovalOptions.api.deselectAll()
}

function getData() {
     
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "auditor/auditor/auditor_list",
    type: "get",
    success: function (msg) {
        let col = [
            ...msg.data.header,
            {
                headerName: "scheme_detail_before",
                field: "scheme_detail_before",
                wrapText: true,
                autoHeight: true, 
                cellRenderer: params => {
                    return params.value ? params.value : '';
                }
            },
            {
                headerName: "scheme_detail_after",
                field: "scheme_detail_after",
                wrapText: true,   // aktifkan text wrap
                autoHeight: true, 
                cellRenderer: params => {
                    return params.value ? params.value : '';
                }
            }
        ];
        
      gridOptions.api.setGridOption('columnDefs', col);
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

$("#btn-export-excel").click(function () {
  gridOptions.api.exportDataAsExcel();
})
jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya	
})