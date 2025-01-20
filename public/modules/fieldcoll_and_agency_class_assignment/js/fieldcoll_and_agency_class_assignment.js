var selr;
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
      "assignment/class_assignment/get_classification_list_assignment" +
      classification,
    type: "get",
    success: function (msg) {
      gridOptions.api.setGridOption("columnDefs", msg.data.header);
      gridOptions.api.setGridOption("rowData", msg.data.data);
      // gridOptions.api.sizeColumnsToFit();
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
    selr = params.data.classification_id;
    selected_data = params.data;
  },
};
var eGridDiv = document.getElementById("myGridFaca");
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
};
$("#btn-assign").click(function () {
  if (selr) {
    var buttons = {
      success: {
        label: "<i class='icon-ok'></i> Save",
        className: "btn-sm btn-success",
        callback: function () {
          var weight_list = "";
          $(".ass_weight").each(function () {
            if (weight_list == "") {
              weight_list = $(this).val();
            } else {
              weight_list = weight_list + "|" + $(this).val();
            }
          });

          let listAgentForAssigned = "";
          $.each($(".agent_id_assigned"), function (i, val) {
            if (i > 0) {
              listAgentForAssigned += "|" + val.innerHTML;
            } else {
              listAgentForAssigned += val.innerHTML;
            }
          });
          var options = {
            url:
              GLOBAL_MAIN_VARS["SITE_URL"] +
              "assignment/class_assignment/class_agent_assignment_form_save_waiting_approval",
            type: "post",
            success: showFormResponse,
            data: {
              classification_id: $("#class_id").val(),
              param_list: listAgentForAssigned,
              weight_list: weight_list,
            },
            dataType: "json",
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
      "ASSIGN COLLECTOR TO CLASS",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "assignment/class_assignment/class_agent_assignment_form?id=" +
        selr,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});
$("#btn-view").click(function () {
  if (selr) {
    var buttons = {
      button: {
        label: "Close",
        className: "btn-sm",
      },
    };

    showCommonDialog(
      800,
      800,
      "TEST CLASSIFICATION",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "assignment/class_assignment/classification_test_form?id=" +
        selr,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
