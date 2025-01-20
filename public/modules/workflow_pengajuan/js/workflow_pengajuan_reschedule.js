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
      "workflow_pengajuan/workflow_pengajuan_reschedule/workflow_pengajuan_reschedule_list" +
      classification,
    type: "get",
    data: { status: status },
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
    selr = params.data.id;
    selected_data = params.data;
  },
  onCellDoubleClicked: (params) => {
    console.log("cell was clicked", params);
    if (params.data.status == "NEW") {
      var button2 = {
        button: {
          label: "Close",
          className: "btn-sm btn-close-modal",
        },
      };
      showCommonDialog(
        1200,
        700,
        "RESCHEDULE PROGRAM REQUEST",
        GLOBAL_MAIN_VARS["SITE_URL"] +
          "workflow_pengajuan/workflow_pengajuan_reschedule/pengajuan_program_form?id=" +
          params.data.id +
          "&account_id=" +
          params.data.cm_card_nmbr +
          "&source=CMS&agent_id=" +
          GLOBAL_VARS +
          "&screen_level=EDIT",
        button2
      );
    } else if (params.data.status == "ASSIGNED") {
      selected_data = params.data;
      $("#btn-request-frmagt").click();
    }
  },
};
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
};
$("#btn-request-only-flag").click(function () {
  var buttons = {
    success: {
      label: "<i class='icon-ok'></i> Request",
      className: "btn-sm btn-warning btn-request",
      callback: function () {
        let cm_card_nmbr = $("#txt-card-number").val();
        $(".modal").modal("hide");
        var button2 = {
          button: {
            label: "Close",
            className: "btn-sm btn-close-modal",
          },
        };

        setTimeout(() => {
          showCommonDialog(
            1200,
            700,
            "RESCHEDULE PROGRAM REQUEST",
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "workflow_pengajuan/workflow_pengajuan_reschedule/pengajuan_program_form?id=null&account_id=" +
              cm_card_nmbr +
              "&source=CMS&agent_id=" +
              GLOBAL_VARS +
              "&screen_level=NEW",
            button2
          );
          // $('.modal').modal('hide');
        }, 500);
        return false;

        // $.ajax({
        //   type: "POST",
        //   url:
        //     GLOBAL_MAIN_VARS["SITE_URL"] +
        //     "/workflow_pengajuan/workflow_pengajuan_reschedule/saveNewRequest",
        //   data: { cm_card_nmbr: cm_card_nmbr },
        //   async: false,
        //   dataType: "json",
        //   success: function (msg) {},
        // });

        // return false;
      },
    },
    button: {
      label: "Close",
      className: "btn-sm btn-close-modal",
    },
  };
  showCommonDialog(
    500,
    700,
    "SEARCH",
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "workflow_pengajuan/workflow_pengajuan_reschedule/requestData",
    buttons
  );
});

$("#btn-assign-to-tl").click(function () {
  if (selected_data.status) {
    if (selected_data.status == "NEW") {
      var buttons = {
        success: {
          label: "<i class='icon-ok'></i> assign",
          className: "btn-sm btn-warning btn-request",
          callback: function () {
            $.ajax({
              type: "POST",
              url:
                GLOBAL_MAIN_VARS["SITE_URL"] +
                "workflow_pengajuan/workflow_pengajuan_reschedule/saveAssignData",
              data: {
                id: $("#id").val(),
                tl: $("#team_leader").val(),
                csrf_security: $("#token_csrf").val(),
              },
              async: false,
              dataType: "json",
              success: function (msg) {
                if (msg.success) {
                  showInfo(msg.message);
                } else {
                  showWarning(msg.message);
                }
                getData();
              },
            });
          },
        },
        button: {
          label: "Close",
          className: "btn-sm btn-close-modal",
        },
      };
      showCommonDialog(
        500,
        700,
        "ASSIGNED",
        GLOBAL_MAIN_VARS["SITE_URL"] +
          "workflow_pengajuan/workflow_pengajuan_reschedule/assignData?id=" +
          selected_data.id,
        buttons
      );
    } else {
      showWarning("please select NEW data");
    }
  } else {
    showWarning("please select data");
  }
});
$("#btn-request-frmagt").click(function () {
  var card_no = selected_data.cm_card_nmbr;
  var id = selected_data.id;
  var button2 = {
    button: {
      label: "Close",
      className: "btn-sm btn-close-modal",
    },
  };

  showCommonDialog(
    1200,
    700,
    "RESCHEDULE PROGRAM REQUEST",
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "workflow_pengajuan/workflow_pengajuan_reschedule/pengajuan_program_form?id=" +
      id +
      "&account_id=" +
      card_no +
      "&source=CMS&agent_id=" +
      GLOBAL_VARS +
      "&screen_level=ASSIGNED",
    button2
  );
});
$("#btn-approval").click(function () {
  if (selected_data.status) {
    if (
      selected_data.status == "APPROVAL" ||
      selected_data.status == "APPROVED" ||
      selected_data.status == "REJECT"
    ) {
      var buttons = {
        button: {
          label: "Close",
          className: "btn-sm btn-close-modal",
        },
      };
      showCommonDialog(
        1200,
        700,
        "Approval",
        GLOBAL_MAIN_VARS["SITE_URL"] +
          "workflow_pengajuan/workflow_pengajuan_reschedule/approvalrequest?id=" +
          selected_data.id,
        buttons
      );
    }
  }
});
jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
