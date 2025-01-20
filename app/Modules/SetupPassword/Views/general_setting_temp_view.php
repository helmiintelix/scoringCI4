<div class="row">

    <div class="col-xs-12">
        <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-warning" id="btn-approve">APPROVE</button>
            <button type="button" class="btn btn-outline-danger" id="btn-reject">REJECT</button>
            <!-- <button type="button" class="btn btn-outline-dark" id="btn-force-logout">Force Logout</button> -->
        </div>
        <!-- <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-success" id="btn-export-excel">Download Excel</button>
        </div> -->
    </div>
</div>

<div class="card">

    <div class="card-header">
        Setup Password Approval
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridTemp" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>

<!-- <script src="<?= base_url(); ?>modules/SetupPassword/js/setupPassword_temp.js?v=<?= rand() ?>"></script> -->
<script>
var selr, val;
var selected_data;
var TOKEN_VALID = false;

function deselect() {
  gridOptions.api.deselectAll();
  gridApprovalOptions.api.deselectAll();
}

function getData() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "settings/get_password_setting_temp/",
    type: "get",
    success: function (msg) {
      console.log("test branch");
      console.log(msg);
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
  ],

  // default col def properties get applied to all columns
  // defaultColDef: { sortable: true, filter: 'agSetColumnFilter', floatingFilter: true, resizable: true },
  defaultColDef: {
    sortable: true,
    filter: "agSetColumnFilter",
    floatingFilter: true,
    resizable: true,
  },

  rowSelection: "multiple", // allow rows to be selected
  animateRows: true, // have rows animate to new positions when sorted
  paginationAutoPageSize: true,
  pagination: true,

  // example event handler
  onCellClicked: (params) => {
    console.log("cell was clicked", params);
    selected_data = params.data;
    selr = params.data.id;
    val = params.data.value;
  },
};
var eGridDiv = document.getElementById("myGridTemp");
new agGrid.Grid(eGridDiv, gridOptions);

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
};

$("#btn-approve").click(function () {
	console.log("APPROVE", selr)
  if (selr) {
    bootbox.confirm(
      "Are you sure to approve this request ?",
      function (result) {
        if (result) {
          $.post(
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "settings/approve_system_setting",
            {
              id: selr,
              value: val,
            },
            function (data) {
              console.log(data);
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
  }
});

$("#btn-reject").click(function () {
  if (selr) {
    bootbox.confirm("Are you sure to reject this request ?", function (result) {
      if (result) {
        $.post(
          GLOBAL_MAIN_VARS["SITE_URL"] +
            "settings/reject_system_setting",
          {
            id: selr,
            value: val,
          },
          function (data) {
            console.log(data);
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
  }
});
jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});


</script>