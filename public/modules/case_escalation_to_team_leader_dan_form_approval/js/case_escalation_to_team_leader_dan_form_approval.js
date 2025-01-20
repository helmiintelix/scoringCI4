var selr, cont, vCallResult, vCollId, vtaskId;
var selected_data;
var TOKEN_VALID = false;
var dataResults = {
  CMP: null,
  NPH: null,
  NAD: null,
  SMS: null,
  DPT: null,
  BPH: null,
  NRV: null,
  SENT_TO_COLL: null,
  SPV_REVIEW: null,
  PTC: null,
  PTP: null,
  STF: null,
  ESKALASI: null,
};

getDataCoordinator();

$.each(dataId, function (index, item) {
  var collectionResult = item.collection_result;
  console.log("Collection Result: " + collectionResult);

  // Periksa apakah collectionResult ada dalam objek dataResults
  if (dataResults.hasOwnProperty(collectionResult)) {
    // dataResults[collectionResult] = collectionResult;
    getData(collectionResult);
  }
});

function actionRenderer(params) {
  const link = params.data.action;
  return link;
}

function deselect() {
  gridOptions.api.deselectAll();
}

function getDataCoordinator() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "coordinator_main/approval/get_data_coordinator" +
      classification,
    type: "get",
    success: function (response) {
      renderCoordinatorList(response.data);
    },
    dataType: "json",
  });
}

var renderCoordinatorList = (data) => {
  console.log("data", data);
  if (typeof data === "string") {
    try {
      data = JSON.parse(data);
    } catch (e) {
      console.error("Error parsing JSON:", e);
      return;
    }
  }

  var html = "";
  var i = 1;

  $.each(data, function (index, val) {
    var active_class = i == 1 ? "active" : "";
    html += `<button class="nav-link ${active_class}" id="${val.collection_result}-tab"
              data-bs-toggle="tab" data-bs-target="#${val.collection_result}" type="button" role="tab"
              aria-controls="${val.collection_result}"
              aria-selected="true">${val.description}(${val.open})</button>`;
    i++;
  });

  // Assuming you want to append this to a specific container
  $("#nav-tab").html(html);
};

function getData(collectionResult) {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "coordinator_main/approval/view_subgrid_coordinator_performance" +
      classification,
    type: "get",
    data: { id_call_result: collectionResult },
    success: function (msg) {
      var columnDefs = msg.data.header;

      columnDefs.forEach(function (column) {
        if (column.field === "action") {
          column.cellRenderer = actionRenderer;
        }
      });
      console.log("test branch");
      console.log(msg);
      gridOptions.api.setGridOption("columnDefs", msg.data.header);
      gridOptions.api.setGridOption("rowData", msg.data.data);
    },
    dataType: "json",
  });
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

    defaultColDef: {
      sortable: true,
      filter: "agSetColumnFilter",
      floatingFilter: true,
      resizable: true,
    },

    rowSelection: "multiple",
    animateRows: true,
    paginationAutoPageSize: true,
    pagination: true,

    onCellClicked: (params) => {
      console.log("cell was clicked", params);
      selr = params.data;
      cont = params.data.contract_number;
      vCallResult = params.data.collection_result;
      vCollId = params.data.collection_history_id;
      cont = params.data.contract_number;
      vtaskId = params.data.task_id;
      selected_data = params.data;
    },
  };
  var eGridDiv = document.getElementById(collectionResult + "Cet");
  new agGrid.Grid(eGridDiv, gridOptions);
}

var showFormResponse = function (responseText, statusText) {
  if (responseText.success) {
    showInfo(responseText.message);
    if (responseText.notification_id) {
      sendNotification(responseText.notification_id);
    }
  } else {
    showInfo(responseText.message);
    return false;
  }
};
function showDataDebitur(historyId, contract_number, task_id, call_result) {
  if (
    call_result == "NPH" ||
    call_result == "PTC" ||
    call_result == "NAD" ||
    call_result == "STF" ||
    call_result == "NMAIL" ||
    call_result == "NEC"
  ) {
    showAction();
  } else {
    var buttons = {
      button: {
        label: "Close",
        className: "btn-sm btn-close",
        callback: function () {
          console.log("back");
          $.ajax({
            url:
              GLOBAL_MAIN_VARS["SITE_URL"] +
              "detail_account/detail_account/update_account_handling?contract_number=" +
              cm_card_nmbr,
            type: "get",
            success: function (msg) {},
            dataType: "json",
          });

          if (GLOBAL_MAIN_VARS["CONTRACT_NUMBER"] == "") {
            $("#lastAccountHandling").hide(1000);
          }
          try {
            if (TELEPHONY_CURRENT_STATUS != "AGENT_TALKING") {
              GLOBAL_MAIN_VARS["ACTIVE_CALL_NO"] = "";
            }
          } catch (error) {
            GLOBAL_MAIN_VARS["ACTIVE_CALL_NO"] = "";
          }
        },
      },
    };
    showCommonDialog(
      window.innerWidth,
      window.innerHeight,
      "Follow Up Team Leader",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "detail_account/detail_account?account_id=" +
        contract_number +
        "&cm_card_nmbr=" +
        contract_number +
        "&history_id=" +
        historyId +
        "&approval=1",
      buttons
    );
  }
}
function showAction() {
  GLOBAL_MAIN_VARS["approval_nomor_kontrak"] = cont;
  GLOBAL_MAIN_VARS["approval_id_penangganan"] = vCallResult;
  GLOBAL_MAIN_VARS["approval_collection_id"] = vCollId;
  GLOBAL_MAIN_VARS["approval_task_id"] = vtaskId;

  console.log("vCallResult", vCallResult);
  if (selr) {
    if (cont != "") {
      switch (vCallResult) {
        case "BPH":
          showCommonDialog(
            800,
            420,
            "All Phone Number are Invalid",
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "coordinator_main/approval/all_phone_invalid_approval",
            [
              {
                text: "Save",
                class: "btn btn-primary btn-xs",
                click: function () {
                  if ($("#coordinator_comments").val() == "") {
                    alert("field can't empty");
                    return false;
                  }
                  $.ajax({
                    type: "POST",
                    url:
                      GLOBAL_MAIN_VARS["SITE_URL"] +
                      "coordinator_main/approval/update_coordinator_task/",
                    async: false,
                    dataType: "json",
                    data: {
                      CollectionResult:
                        GLOBAL_MAIN_VARS["approval_id_penangganan"],
                      ContractNumber: $("#contract_no").html(),
                      CollectionHistory:
                        GLOBAL_MAIN_VARS["approval_collection_id"],
                      NextAction: $("#next_action").val(),
                    },
                    success: function (msg) {
                      if (msg.success == true) {
                        $.ajax({
                          type: "POST",
                          url:
                            GLOBAL_MAIN_VARS["SITE_URL"] +
                            "coordinator_main/update_comment_collection_history",
                          async: false,
                          dataType: "json",
                          data: {
                            CollectionHistory: $(
                              "#collection_history_id"
                            ).val(),
                            ContractNumber: $(
                              "#approval_account_table"
                            ).getCell(selr, "contract_number"),
                            comments: $("#coordinator_comments").val(),
                          },
                          success: function (result) {
                            if (result.success == true) {
                              //$("#CommonDialog").dialog('close');
                              //$("#CommonDialog").dialog('destroy');
                              $("#CommonDialog").remove();
                            }
                          },
                          error: function () {
                            alert("Failed: update_comment_collection_history");
                          },
                        });
                      } else {
                        alert(msg.message);
                      }
                    },
                    error: function () {
                      alert("Failed: update_coordinator_task");
                    },
                  });

                  $("#approval_account_table").trigger("reloadGrid");

                  //$(this).dialog('close');
                  ////$(this).dialog('destroy');
                  $("#CommonDialog").remove();
                },
              },
              {
                text: "Close",
                class: "btn btn-xs btn-danger",
                click: function () {
                  //$(this).dialog('close');
                  //$(this).dialog('destroy');
                  $("#CommonDialog").remove();
                },
              },
            ]
          );

          break;
        case "DPT":
          showCommonDialog(
            800,
            450,
            "Team Leader Approval",
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "coordinator_approval/unable_to_pay_approval",
            [
              {
                text: "Save",
                class: "btn btn-primary btn-xs",
                click: function () {
                  if ($("#coordinator_comments").val() == "") {
                    alert("field can't empty");
                    return false;
                  }
                  $.ajax({
                    type: "POST",
                    url:
                      GLOBAL_MAIN_VARS["SITE_URL"] +
                      "coordinator_main/update_coordinator_task/",
                    async: false,
                    dataType: "json",
                    data: {
                      CollectionResult:
                        GLOBAL_MAIN_VARS["approval_id_penangganan"],
                      ContractNumber: $("#contract_no").html(),
                      CollectionHistory:
                        GLOBAL_MAIN_VARS["approval_collection_id"],
                      NextAction: $("#next_action").val(),
                    },
                    success: function (msg) {
                      if (msg.success == true) {
                        $.ajax({
                          type: "POST",
                          url:
                            GLOBAL_MAIN_VARS["SITE_URL"] +
                            "coordinator_main/update_comment_collection_history",
                          async: false,
                          dataType: "json",
                          data: {
                            CollectionHistory: $(
                              "#collection_history_id"
                            ).val(),
                            ContractNumber: $(
                              "#approval_account_table"
                            ).getCell(selr, "contract_number"),
                            comments: $("#coordinator_comments").val(),
                          },
                          success: function (result) {
                            if (result.success == true) {
                              $("#CommonDialog").dialog("close");
                              $("#CommonDialog").dialog("destroy");
                              $("#CommonDialog").remove();
                            }
                          },
                          error: function () {
                            alert("Failed: update_comment_collection_history");
                          },
                        });
                      } else {
                        alert(msg.message);
                      }
                    },
                    error: function () {
                      alert("Failed: update_coordinator_task");
                    },
                  });
                  $("#approval_account_table").trigger("reloadGrid");

                  //$(this).dialog('close');
                  //$(this).dialog('destroy');
                  $("#CommonDialog").remove();
                },
              },
              {
                text: "Close",
                class: "btn btn-xs btn-danger",
                click: function () {
                  //$(this).dialog('close');
                  //$(this).dialog('destroy');
                  $("#CommonDialog").remove();
                },
              },
            ]
          );

          break;
        case "DC_COMPLAINT":
          showCommonDialog(
            800,
            420,
            "Komplain DC",
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "coordinator_approval/dc_complaint_approval",
            [
              {
                text: "Save",
                class: "btn btn-primary btn-xs",
                click: function () {
                  if ($("#coordinator_comments").val() == "") {
                    alert("field can't empty");
                    return false;
                  }
                  $.ajax({
                    type: "POST",
                    url:
                      GLOBAL_MAIN_VARS["SITE_URL"] +
                      "coordinator_main/approval/update_coordinator_task/",
                    async: false,
                    dataType: "json",
                    data: {
                      CollectionResult:
                        GLOBAL_MAIN_VARS["approval_id_penangganan"],
                      ContractNumber: $("#contract_no").html(),
                      CollectionHistory:
                        GLOBAL_MAIN_VARS["approval_collection_id"],
                      NextAction: $("#next_action").val(),
                    },
                    success: function (msg) {
                      if (msg.success == true) {
                        $.ajax({
                          type: "POST",
                          url:
                            GLOBAL_MAIN_VARS["SITE_URL"] +
                            "coordinator_main/update_comment_collection_history",
                          async: false,
                          dataType: "json",
                          data: {
                            CollectionHistory: $(
                              "#collection_history_id"
                            ).val(),
                            ContractNumber: $(
                              "#approval_account_table"
                            ).getCell(selr, "contract_number"),
                            comments: $("#coordinator_comments").val(),
                          },
                          success: function (result) {
                            if (result.success == true) {
                              $("#CommonDialog").dialog("close");
                              $("#CommonDialog").dialog("destroy");
                              $("#CommonDialog").remove();
                            }
                          },
                          error: function () {
                            alert("Failed: update_comment_collection_history");
                          },
                        });
                      } else {
                        alert(msg.message);
                      }
                    },
                    error: function () {
                      alert("Failed: update_coordinator_task");
                    },
                  });
                  //$("#CommondDialog").dialog('close');
                  //return false;
                  $("#approval_account_table").trigger("reloadGrid");

                  //$(this).dialog('close');
                  //$(this).dialog('destroy');
                  $("#CommonDialog").remove();
                },
              },
              {
                text: "Close",
                class: "btn btn-xs btn-danger",
                click: function () {
                  //$(this).dialog('close');
                  //$(this).dialog('destroy');
                  $("#CommonDialog").remove();
                },
              },
            ]
          );

          break;
        case "NRV":
          showCommonDialog(
            800,
            420,
            "Tidak Bertanggung Jawab",
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "coordinator_approval/not_related_to_vehicle_approval",
            [
              {
                text: "Save",
                class: "btn btn-primary btn-xs",
                click: function () {
                  if ($("#coordinator_comments").val() == "") {
                    alert("field can't empty");
                    return false;
                  }
                  $.ajax({
                    type: "POST",
                    url:
                      GLOBAL_MAIN_VARS["SITE_URL"] +
                      "coordinator_main/update_coordinator_task/",
                    async: false,
                    dataType: "json",
                    data: {
                      CollectionResult:
                        GLOBAL_MAIN_VARS["approval_id_penangganan"],
                      ContractNumber: $("#contract_no").html(),
                      CollectionHistory:
                        GLOBAL_MAIN_VARS["approval_collection_id"],
                      NextAction: $("#next_action").val(),
                    },
                    success: function (msg) {
                      if (msg.success == true) {
                        $.ajax({
                          type: "POST",
                          url:
                            GLOBAL_MAIN_VARS["SITE_URL"] +
                            "coordinator_main/update_comment_collection_history",
                          async: false,
                          dataType: "json",
                          data: {
                            CollectionHistory: $(
                              "#collection_history_id"
                            ).val(),
                            ContractNumber: $(
                              "#approval_account_table"
                            ).getCell(selr, "contract_number"),
                            comments: $("#coordinator_comments").val(),
                          },
                          success: function (result) {
                            if (result.success == true) {
                              $("#CommonDialog").dialog("close");
                              $("#CommonDialog").dialog("destroy");
                              $("#CommonDialog").remove();
                            }
                          },
                          error: function () {
                            alert(
                              "268 coordinator task fail connection: please contact your administrator"
                            );
                          },
                        });
                      } else {
                        alert(msg.message);
                      }
                    },
                    error: function () {
                      alert(
                        "276 coordinator task fail connection: please contact your administrator"
                      );
                    },
                  });
                  //$("#CommondDialog").dialog('close');
                  //return false;
                  $("#approval_account_table").trigger("reloadGrid");

                  //$(this).dialog('close');
                  //$(this).dialog('destroy');
                  $("#CommonDialog").remove();
                },
              },
              {
                text: "Close",
                class: "btn btn-xs btn-danger",
                click: function () {
                  //$(this).dialog('close');
                  //$(this).dialog('destroy');
                  $("#CommonDialog").remove();
                },
              },
            ]
          );

          break;

        case "SMS":
          showCommonDialog(
            800,
            500,
            "SMS Non Standard",
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "coordinator_main/approval/sms_non_standar_approval",
            [
              {
                text: "Approve",
                class: "btn btn-primary btn-xs",
                click: function () {
                  if (
                    $("#reason").val() == "" ||
                    $("#sms_message").val() == ""
                  ) {
                    alert("field can't empty");
                    return false;
                  }

                  var ci_url =
                    GLOBAL_MAIN_VARS["SITE_URL"] +
                    "coordinator_main/approval/update_coordinator_task/";
                  $.ajax({
                    type: "POST",
                    url: ci_url,
                    async: false,
                    dataType: "json",
                    data: {
                      CollectionResult:
                        GLOBAL_MAIN_VARS["approval_id_penangganan"],
                      ContractNumber: $("#contract_no").html(),
                      CollectionHistory:
                        GLOBAL_MAIN_VARS["approval_collection_id"],
                      NextAction: $("#next_action").val(),
                    },
                    success: function (msg) {
                      if (msg.success == true) {
                        $("#coord_task_table").trigger("reloadGrid");
                        $("#approval_account_table").trigger("reloadGrid");

                        var ci_url =
                          GLOBAL_MAIN_VARS["SITE_URL"] +
                          "coordinator_main/approval/update_comment_collection_history";
                        $.ajax({
                          type: "POST",
                          url: ci_url,
                          async: false,
                          dataType: "json",
                          data: {
                            CollectionHistory: $(
                              "#collection_history_id"
                            ).val(),
                            ContractNumber: $(
                              "#approval_account_table"
                            ).getCell(selr, "contract_number"),
                            comments: $("#coordinator_comments").val(),
                          },
                          success: function (result) {
                            if (result.success == true) {
                              $.ajax({
                                type: "POST",
                                url:
                                  GLOBAL_MAIN_VARS["SITE_URL"] +
                                  "/sms_non_standar/approve_sms",
                                async: false,
                                dataType: "json",
                                data: {
                                  contract_number: $("#contract_no").html(),
                                  sms_text: $("#sms_message").val(),
                                  sms_destination: $("#recipient_phone").html(),
                                },
                                success: function (result) {
                                  if (result.success == true) {
                                    $("#CommonDialog").dialog("close");
                                    $("#CommonDialog").dialog("destroy");
                                    $("#CommonDialog").remove();
                                  }
                                },
                                error: function () {
                                  alert("approve_sms: failed!");
                                },
                              });
                              $("#CommonDialog").dialog("close");
                              $("#CommonDialog").dialog("destroy");
                              $("#CommonDialog").remove();
                            }
                          },
                          error: function () {
                            alert("Failed: " + ci_url);
                          },
                        });
                      } else {
                        alert(msg.message);
                      }
                    },
                    error: function () {
                      alert("Failed: " + ci_url);
                    },
                  });

                  $("#CommonDialog").remove();
                },
              },
              {
                text: "Reject",
                class: "btn btn-warning btn-xs",
                click: function () {
                  var ci_url =
                    GLOBAL_MAIN_VARS["SITE_URL"] +
                    "coordinator_main/update_coordinator_task/";
                  $.ajax({
                    type: "POST",
                    url: ci_url,
                    async: false,
                    dataType: "json",
                    data: {
                      CollectionResult:
                        GLOBAL_MAIN_VARS["approval_id_penangganan"],
                      ContractNumber: $("#contract_no").html(),
                      CollectionHistory:
                        GLOBAL_MAIN_VARS["approval_collection_id"],
                      NextAction: $("#next_action").val(),
                    },
                    success: function (msg) {
                      if (msg.success == true) {
                        $("#coord_task_table").trigger("reloadGrid");
                        $("#approval_account_table").trigger("reloadGrid");

                        var ci_url =
                          GLOBAL_MAIN_VARS["SITE_URL"] +
                          "coordinator_main/update_comment_collection_history";
                        $.ajax({
                          type: "POST",
                          url: ci_url,
                          async: false,
                          dataType: "json",
                          data: {
                            CollectionHistory: $(
                              "#collection_history_id"
                            ).val(),
                            ContractNumber: $(
                              "#approval_account_table"
                            ).getCell(selr, "contract_number"),
                            comments: $("#coordinator_comments").val(),
                          },
                          success: function (result) {
                            if (result.success == true) {
                              var ci_url =
                                GLOBAL_MAIN_VARS["SITE_URL"] +
                                "sms_non_standar/reject_sms";
                              $.ajax({
                                type: "POST",
                                url: ci_url,
                                async: false,
                                dataType: "json",
                                data: {
                                  contract_number: $("#contract_no").html(),
                                  sms_text: $("#sms_message").val(),
                                  sms_destination: $("#recipient_phone").html(),
                                },
                                success: function (result) {
                                  if (result.success == true) {
                                    $("#CommonDialog").remove();
                                  }
                                },
                                error: function () {
                                  alert("Failed: " + ci_url);
                                },
                              });
                              $("#CommonDialog").remove();
                            }
                          },
                          error: function () {
                            alert("Failed: " + ci_url);
                          },
                        });
                      } else {
                        alert("Failed: " + ci_url);
                      }
                    },
                    error: function () {
                      alert("Failed: " + ci_url);
                    },
                  });

                  $("#CommonDialog").remove();
                },
              },
              {
                text: "Close",
                class: "btn btn-xs btn-danger",
                click: function () {
                  $("#CommonDialog").remove();
                },
              },
            ]
          );

          break;
        case "NPH":
          var buttons = {
            success: {
              label: "<i class='icon-ok'></i> APPROVE",
              className: "btn-sm btn-success",
              callback: function () {
                $.ajax({
                  url:
                    GLOBAL_MAIN_VARS["SITE_URL"] +
                    "coordinator_main/approval/save_approval_request?type=NPH&status=APPROVE&id=" +
                    vCollId,
                  type: "get",
                  success: function (msg) {
                    if (msg.success) {
                      showInfo("berhasil", 1000);
                      $("#nav-tab").empty();
                      $("#NPHCet").empty();
                      getDataCoordinator();
                      getData("NPH");
                    } else {
                      showWarning("upss.. terjadi kesalahan sistem", 1000);
                    }
                  },
                  dataType: "json",
                });
              },
            },
            reject: {
              label: "<i class='icon-fail'></i> REJECT",
              className: "btn-sm btn-danger",
              callback: function () {
                $.ajax({
                  url:
                    GLOBAL_MAIN_VARS["SITE_URL"] +
                    "coordinator_main/approval/save_approval_request?type=NPH&status=REJECT&id=" +
                    vCollId,
                  type: "get",
                  success: function (msg) {
                    if (msg.success) {
                      showInfo("berhasil", 1000);
                      $("#nav-tab").empty();
                      $("#NPHCet").empty();
                      getDataCoordinator();
                      getData("NPH");
                    } else {
                      showWarning("upss.. terjadi kesalahan sistem", 1000);
                    }
                  },
                  dataType: "json",
                });
              },
            },
            button: {
              label: "Close",
              className: "btn-sm",
              callback: function () {},
            },
          };

          showCommonDialog(
            950,
            500,
            "Add New Phone",
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "coordinator_main/approval/new_phone_address_approval?contract_no=" +
              GLOBAL_MAIN_VARS["approval_nomor_kontrak"] +
              "&coll_id=" +
              vCollId +
              "&type=NPH&taskId=" +
              vtaskId,
            buttons
          );

          break;
        case "NAD":
          var buttons = {
            success: {
              label: "<i class='icon-ok'></i> APPROVE",
              className: "btn-sm btn-success",
              callback: function () {
                $.ajax({
                  url:
                    GLOBAL_MAIN_VARS["SITE_URL"] +
                    "coordinator_main/approval/save_approval_request?type=NAD&status=APPROVE&id=" +
                    vCollId,
                  type: "get",
                  success: function (msg) {
                    if (msg.success) {
                      showInfo("berhasil", 1000);
                      $("#nav-tab").empty();
                      $("#NADCet").empty();
                      getDataCoordinator();
                      getData("NAD");
                    } else {
                      showWarning("upss.. terjadi kesalahan sistem", 1000);
                    }
                  },
                  dataType: "json",
                });
              },
            },
            reject: {
              label: "<i class='icon-fail'></i> REJECT",
              className: "btn-sm btn-danger",
              callback: function () {
                $.ajax({
                  url:
                    GLOBAL_MAIN_VARS["SITE_URL"] +
                    "coordinator_main/approval/save_approval_request?type=NAD&status=REJECT&id=" +
                    vCollId,
                  type: "get",
                  success: function (msg) {
                    if (msg.success) {
                      showInfo("berhasil", 1000);
                      $("#nav-tab").empty();
                      $("#NADCet").empty();
                      getDataCoordinator();
                      getData("NAD");
                    } else {
                      showWarning("upss.. terjadi kesalahan sistem", 1000);
                    }
                  },
                  dataType: "json",
                });
              },
            },
            button: {
              label: "Close",
              className: "btn-sm",
              callback: function () {},
            },
          };
          showCommonDialog(
            950,
            500,
            "Add New Address",
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "coordinator_main/approval/new_phone_address_approval?contract_no=" +
              GLOBAL_MAIN_VARS["approval_nomor_kontrak"] +
              "&coll_id=" +
              vCollId +
              "&type=NAD&taskId=" +
              vtaskId,
            buttons
          );

          break;
        case "PTC":
          showCommonDialog(
            800,
            420,
            "Request Visit",
            GLOBAL_MAIN_VARS["SITE_URL"] + "coordinator_approval/request_visit",
            [
              {
                text: "Approve",
                class: "btn btn-primary btn-xs",
                click: function () {
                  var ci_url =
                    GLOBAL_MAIN_VARS["SITE_URL"] +
                    "coordinator_approval/request_visit_approve";
                  $.ajax({
                    type: "POST",
                    url: ci_url,
                    async: false,
                    dataType: "json",
                    data: {
                      //contract_no:GLOBAL_MAIN_VARS['approval_nomor_kontrak'],
                      coordinator_task_id: vtaskId,
                    },
                    success: function (result) {
                      if (result.success == true) {
                        $("#coord_task_table").trigger("reloadGrid");
                        $("#approval_account_table").trigger("reloadGrid");

                        $("#CommonDialog").remove();
                      }
                    },
                    error: function () {
                      alert("Failed: " + ci_url);
                    },
                  });

                  $("#CommonDialog").remove();
                },
              },
              {
                text: "Reject",
                class: "btn btn-warning btn-xs",
                click: function () {
                  var ci_url =
                    GLOBAL_MAIN_VARS["SITE_URL"] +
                    "coordinator_approval/request_visit_reject";
                  $.ajax({
                    type: "POST",
                    url: ci_url,
                    async: false,
                    dataType: "json",
                    data: {
                      //contract_no:GLOBAL_MAIN_VARS['approval_nomor_kontrak'],
                      coordinator_task_id: vtaskId,
                    },
                    success: function (result) {
                      if (result.success == true) {
                        $("#coord_task_table").trigger("reloadGrid");
                        $("#approval_account_table").trigger("reloadGrid");

                        $("#CommonDialog").remove();
                      }
                    },
                    error: function () {
                      alert("Failed: " + ci_url);
                    },
                  });

                  $("#CommonDialog").remove();
                },
              },
              {
                text: "Close",
                class: "btn btn-xs btn-danger",
                click: function () {
                  $("#CommonDialog").remove();
                },
              },
            ]
          );

          break;
        case "STF":
          showCommonDialog(
            900,
            300,
            "Send to Field",
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "coordinator_main/approval/approval_send_to_field?contract_number=" +
              GLOBAL_MAIN_VARS["approval_nomor_kontrak"] +
              "&collection_history_id=" +
              vCollId +
              "&collection_result=STF",
            [
              {
                text: "Approve",
                class: "btn btn-primary btn-xs",
                click: function () {
                  var ci_url =
                    GLOBAL_MAIN_VARS["SITE_URL"] +
                    "coordinator_main/approval/save_send_to_field";
                  $.ajax({
                    type: "POST",
                    url: ci_url,
                    async: false,
                    dataType: "json",
                    data: {
                      //contract_no:GLOBAL_MAIN_VARS['approval_nomor_kontrak'],
                      coordinator_task_id: vtaskId,
                      collection_result: "STF",
                      status: "APPROVE",
                      notes: $("#coordinator_comments").val(),
                    },
                    success: function (result) {
                      if (result.success == true) {
                        $("#coord_task_table").trigger("reloadGrid");
                        $("#approval_account_table").trigger("reloadGrid");

                        $("#CommonDialog").remove();
                      }
                    },
                    error: function () {
                      alert("Failed: " + ci_url);
                    },
                  });

                  $("#CommonDialog").remove();
                },
              },
              {
                text: "Reject",
                class: "btn btn-warning btn-xs",
                click: function () {
                  var ci_url =
                    GLOBAL_MAIN_VARS["SITE_URL"] +
                    "coordinator_main/approval/save_send_to_field";
                  $.ajax({
                    type: "POST",
                    url: ci_url,
                    async: false,
                    dataType: "json",
                    data: {
                      //contract_no:GLOBAL_MAIN_VARS['approval_nomor_kontrak'],
                      coordinator_task_id: vtaskId,
                      collection_result: "STF",
                      status: "REJECT",
                      notes: $("#coordinator_comments").val(),
                    },
                    success: function (result) {
                      if (result.success == true) {
                        $("#coord_task_table").trigger("reloadGrid");
                        $("#approval_account_table").trigger("reloadGrid");

                        $("#CommonDialog").remove();
                      }
                    },
                    error: function () {
                      alert("Failed: " + ci_url);
                    },
                  });

                  $("#CommonDialog").remove();
                },
              },
              {
                text: "Close",
                class: "btn btn-xs btn-danger",
                click: function () {
                  $("#CommonDialog").remove();
                },
              },
            ]
          );

          break;

        default:
          break;
      }
    }
  } else {
    alert("No selected row");
  }

  return false;
}
jQuery(function ($) {
  // getData();
});
