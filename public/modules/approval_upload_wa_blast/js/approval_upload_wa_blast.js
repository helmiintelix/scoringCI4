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
      "approval_upload_wa_blast/approval_upload_wa_blast/get_pengiriman_surat_file_list" +
      classification,
    type: "get",
    success: function (msg) {
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
};
var eGridDiv = document.getElementById("myGridAcu");
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
    showWarning(responseText.message);
    return false;
  }
};

$("#btn-upload").click(function () {
  var buttons = {
    success: {
      label: "<i class='icon-ok'></i> Save",
      className: "btn-sm btn-success",
      callback: function () {
        var options = {
          url:
            GLOBAL_MAIN_VARS["SITE_URL"] +
            "approval_upload_wa_blast/approval_upload_wa_blast/save_file",
          type: "post",
          dataType: "json",
          success: showFormResponse,
          
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
    "UPLOAD WA BLAST",
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "approval_upload_wa_blast/approval_upload_wa_blast/upload_file_form",
    buttons
  );
});

$("#btn-view").click(function () {
  if (selr) {
    var buttons = {
      success: {
        label: "<i class='icon-ok'></i> Approve",
        className: "btn-sm btn-success",
        callback: function () {
          var options = {
            url:
              GLOBAL_MAIN_VARS["SITE_URL"] +
              "approval_upload_wa_blast/approval_upload_wa_blast/approve_upload_file",
            type: "post",
            data: {
              id: selr,
            },
            success: showFormResponse,
            dataType: "json",
          };
          $("form").ajaxSubmit(options);
        },
      },
      reject: {
        label: "<i class='icon-remove'></i> Reject",
        className: "btn-sm btn-danger",
        callback: function () {
            // Logika untuk menolak, bisa disesuaikan sesuai kebutuhan
            var options = {
                url: GLOBAL_MAIN_VARS["SITE_URL"] + "approval_upload_wa_blast/approval_upload_wa_blast/reject_upload_file",
                type: "post",
                data: {
                    id: selr,
                },
                success: showFormResponse,
                dataType: "json",
            };
            // Anda bisa memanggil fungsi ajaxSubmit atau fungsi lain sesuai logika yang diinginkan
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
      "PREVIEW DETAIL UPLOAD",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "approval_upload_wa_blast/approval_upload_wa_blast/show_uploaded_file_form?id=" +
        selr,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});

/*$("#btn-view").click(function () {
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
      "PREVIEW DETAIL UPLOAD",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "approval_upload_wa_blast/approval_upload_wa_blast/show_uploaded_file_form?id=" +
        selr,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});*/

$("#btn-download").click(function (e) {
  window.location.href =
    basePath +
    "/file_upload/pengiriman_surat/format_upload_pengiriman_surat.xlsx";
});

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});

function allCheck(){
    let isCheckedAll = $('#check_all').is(':checked');
    if (isCheckedAll==true) {
      $(".check").prop("checked",true);
    }else{
      $(".check").prop("checked",false);
      $(".checkFalse").prop("checked",false);
    } 
  }
