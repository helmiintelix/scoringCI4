$("#lov3").change(function () {
  let val = $(this).val();
  console.log(val);
  if (val == "PTP") {
    $("#connected_result").show("slow");
    $("#txt_ptp_amount, #txt_ptp_date").attr("readonly", false);
    $("#select_cara_bayar").attr("disabled", false);
  } else if (val == "APP") {
    $("#appointment_result").show("slow");
  } else {
    $("#txt_ptp_amount, #txt_ptp_date").val("").attr("readonly", true);
    $("#select_cara_bayar").val("").change().attr("disabled", true);

    $("#connected_result, #appointment_result").hide("slow");
  }
});

function first_load() {
  $.each(predictive_phone, function (i, val) {
    let des = $(
      "#phone-owner option[value='" + val["phone_number"] + "']"
    ).html();

    des = des + " (" + val["priority"] + ") - " + val["created_time"];
    try {
      $("#phone-owner option[value='" + val["phone_number"] + "']").html(des);
    } catch (error) { }
  });

  // $(".chosen-select").chosen();
  console.log("User ini adalah");
  console.log(user_level_group);
  if (user_level_group == "TELECOLL" || user_level_group == "FIELD_COLL") {
    $("#btn-dial-Handphone").show();
    $("#btnTalktoTL").attr("disabled", false);
    $("#btnSaveAndbreakFollowup").show();
  } else {
    // $("#btn-dial-Handphone").hide();
    $("#btnTalktoTL").attr("disabled", true);
    $("#btnSaveAndbreakFollowup").hide();
  }
}
setTimeout(first_load, 300);

function numbOnly(event) {
  var key = event.keyCode;
  return (key >= 48 && key <= 57) || key == 8;
}

var showCustomerDetail = function () {
  $(".detail-toggle").toggle();
  $(".customer-detail-panel").toggle("slow");
};

// $("#select_call_result_not_connected").chosen({
//     width: "95%"
// });
// $("#select_place").chosen({
//     width: "95%"
// });
// $("#action_code_ct").chosen({
//     width: "95%"
// });
// $("#select_call_result{CM_CARD_NMBR}").chosen({
//     width: "95%"
// });
// $("#select_reason").chosen({
//     width: "95%"
// });
var card_number = $("#no_kontrak").val();
console.log(card_number);
console.log(
  "--------------------------------------------------------------------------------------------------------------"
);

var submitHandler = function (val) {
  var other_phone = $("#other_phone").val();
  $("#span_other").html(other_phone);
};

jQuery(function ($) {
  $(".btn-close").hide();
  $("#divWa").hide();

  if (group_id == "ROOT" || group_id == "ADMIN_INTELIX") {
    $("#account_status").removeAttr("disabled");
  }
  GLOBAL_MAIN_VARS["coordinator_id"] = "coordinator";
  GLOBAL_MAIN_VARS["curr_customer_id"] = $("#curr_customer_id").val();

  $("#multiple_contract").load(
    GLOBAL_MAIN_VARS["SITE_URL"] +
    "detail_account/detail_account/contract_detail_new2/?customer_id=" +
    $("#curr_customer_id").val().replace(" ", "%20") +
    "&account_no=" +
    card_number +
    "&history_id=" +
    history_id +
    "&approval=" +
    approval
  );
  $("#multiple_contract2").load(
    GLOBAL_MAIN_VARS["SITE_URL"] +
    "detail_account/detail_account/contract_detail_new/?customer_id=" +
    $("#curr_customer_id").val().replace(" ", "%20") +
    "&account_no=" +
    card_number +
    "&history_id=" +
    history_id +
    "&approval=" +
    approval
  );
  $("#connected_result").load(
    GLOBAL_MAIN_VARS["SITE_URL"] +
    "detail_account/detail_account/connected_result/?customer_id=" +
    $("#curr_customer_id").val().replace(" ", "%20") +
    "&account_no=" +
    card_number
  );
  $("#appointment_result").load(
    GLOBAL_MAIN_VARS["SITE_URL"] +
    "detail_account/detail_account/appointment_result/?customer_id=" +
    $("#curr_customer_id").val().replace(" ", "%20") +
    "&account_no=" +
    card_number
  );

  $("#dial-button").click(function (e) {
    //document.execCommand('copy');
    let phone = $("#phone-owner").val().replace(/^\D+/g, "");
    navigator.clipboard.writeText(phone);
  });

  $('input[type="radio"]').click(function () {
    if ($(this).is(":checked") && $(this).val() == "Other") {
      $("#other_phone").removeAttr("readonly");
      // $('#other_phone').val('');
    } else {
      $("#other_phone").attr("readonly", "readonly");
    }
  });

  $("#phone-owner").change(function () {
    if ($("#phone-owner").val() != "") {
      // let phone_type = $("#phone-owner").val().split('|');
      let noPhone = $("#phone-owner").val();
      let phone_type = $("#phone-owner option:selected").attr("phonetype");
      if (noPhone == "other") {
        $("#other_phone").val("");
        $("#other_phone").removeAttr("readonly");

        $("#DialedPhoneType").val("other");
      } else {
        $("#other_phone").val(noPhone);
        $("#other_phone").attr("readonly", "readonly");

        $("#DialedPhoneType").val(phone_type);
      }
    }
  });

  $("#select_join_program").change(function () {
    let next_action = $("#select_join_program").val();
    console.log();
    let jenis_pengajuan = "";
    switch (next_action) {
      case "DP": //lunas diskon
        jenis_pengajuan = "LUNAS DISKON";
        break;
      case "RSTR": //restruktur
        jenis_pengajuan = "RESTRUKTUR";
        break;
      case "RSCH": //restruktur
        jenis_pengajuan = "RESCHEDULE";
        break;
    }

    $.ajax({
      url: GLOBAL_MAIN_VARS["SITE_URL"] + "workflow_pengajuan/program_checking",
      data: {
        card_number: card_number,
      },
      type: "POST",
      dataType: "json",
      success: function (msg) {
        if (!msg.success) {
          showWarning(msg.message);
          $("#select_join_program").val("");
          return false;
        } else {
          if (jenis_pengajuan != "") {
            if (jenis_pengajuan == "LUNAS DISKON") {
              var buttons = {
                button: {
                  label: "Close",
                  className: "btn-sm btn-danger",
                },
              };
              showCommonDialog3(
                1200,
                700,
                "DISCOUNT PROGRAM REQUEST",
                GLOBAL_MAIN_VARS["SITE_URL"] +
                "workflow_pengajuan/pengajuan_diskon_lunas_form/?account_id=" +
                card_number +
                "&jenis_pengajuan=" +
                jenis_pengajuan +
                "&source=CMS&agent_id=" +
                GLOBAL_VARS,
                buttons
              );
            } else if (jenis_pengajuan == "RESTRUKTUR") {
              var buttons = {
                button: {
                  label: "Close",
                  className: "btn-sm btn-danger",
                },
              };
              showCommonDialog3(
                1200,
                700,
                "RESTRUCTURE PROGRAM REQUEST",
                GLOBAL_MAIN_VARS["SITE_URL"] +
                "workflow_pengajuan/pengajuan_restructure_form/?account_id=" +
                card_number +
                "&jenis_pengajuan=" +
                jenis_pengajuan +
                "&source=CMS&agent_id=" +
                GLOBAL_VARS,
                buttons
              );
            } else if (jenis_pengajuan == "RESCHEDULE") {
              var buttons = {
                button: {
                  label: "Close",
                  className: "btn-sm btn-danger",
                },
              };
              showCommonDialog3(
                1200,
                700,
                "RESCHEDULE PROGRAM REQUEST",
                GLOBAL_MAIN_VARS["SITE_URL"] +
                "workflow_pengajuan/pengajuan_reschedule_form/?account_id=" +
                card_number +
                "&jenis_pengajuan=" +
                jenis_pengajuan +
                "&source=CMS&agent_id=" +
                GLOBAL_VARS,
                buttons
              );
            } else {
              var buttons = {
                save: {
                  label: "Save",
                  className: "btn-sm btn-primary",
                  callback: function () {
                    if (
                      $("#new_xpac").val() == "" ||
                      $("#nominal_bayar").val() == "" ||
                      $("#opt_reason").val() == "" ||
                      $("#description").val() == "" ||
                      $("#tenor").val() == "" ||
                      $("#cicilan").val() == "" ||
                      $("#tenor2").val() == "" ||
                      $("#cicilan2").val() == "" ||
                      $("#deferred").val() == ""
                    ) {
                      showInfo("Mohon isi field yang tersedia terlebih dahulu");
                      return false;
                    }

                    $.post(
                      GLOBAL_MAIN_VARS["SITE_URL"] +
                      "workflow_pengajuan/pengajuan_request_save/?account_id=" +
                      card_number +
                      "&jenis_pengajuan=" +
                      jenis_pengajuan,
                      $("#formDiscountRequest").serialize(),
                      function (data) {
                        if (data.success == true) {
                          showInfo(
                            "Pengajuan " + jenis_pengajuan + "  berhasil."
                          );
                        } else {
                          showInfo("Pengajuan " + jenis_pengajuan + " gagal.");
                        }
                      },
                      "json"
                    );
                  },
                },
                button: {
                  label: "Close",
                  className: "btn-sm btn-danger",
                },
              };

              showCommonDialog3(
                900,
                500,
                "PENGAJUAN",
                GLOBAL_MAIN_VARS["SITE_URL"] +
                "workflow_pengajuan/pengajuan_request_form/?account_id=" +
                card_number +
                "&jenis_pengajuan=" +
                jenis_pengajuan,
                buttons
              );
            }
          }
        }
      },
    });
  });

  //Update Customer
  $("#btn-update-data").click(function (e) {
    var buttons = {
      new: {
        label: "Update",
        className: "btn-sm btn-success btn-save-new-data",
        callback: function () {
          $(".btn-save-new-data").prop("disabled", true);
          $(".btn-save-update-data").prop("disabled", false);

          $("#select_update_date").val("").prop("disabled", true);

          $("#txt-home-phone").removeAttr("readonly");
          $("#txt-office-phone").removeAttr("readonly");
          $("#txt-cell-phone").removeAttr("readonly");
          $("#txt-bill-address").removeAttr("readonly");
          $("#txt-mail-address").removeAttr("readonly");
          $("#txt-home-address").removeAttr("readonly");
          $("#txt-office-address").removeAttr("readonly");

          $("#txt-home-phone").focus();

          return false;
        },
      },
      save: {
        label: "Save",
        className: "btn-sm btn-primary btn-save-update-data",
        style: "disabled",
        callback: function () {
          var options = {
            url:
              GLOBAL_MAIN_VARS["SITE_URL"] +
              "detail_account/detail_account/save_customer_update_data/",
            type: "post",
            success: function () {
              showInfo("Berhasil");
              return true;
            },
            dataType: "json",
          };
          $("#form-update-data").ajaxSubmit(options);

          //return false;
        },
      },
      button: {
        label: "Close",
        className: "btn-sm btn-danger",
      },
    };

    showCommonDialog3(
      500,
      500,
      "Update Data",
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "detail_account/detail_account/update_customer_data/" +
      $("#curr_customer_id").val(),
      buttons
    );
  });

  $("#btn-reassign").click(function (e) {
    var buttons = {
      save: {
        label: "Save",
        className: "btn-sm btn-primary btn-save-update-data",
        style: "disabled",
        callback: function () {
          var options = {
            url:
              GLOBAL_MAIN_VARS["SITE_URL"] +
              "detail_account/detail_account/save_reassign_account/?account_id=" +
              card_number,
            type: "post",
            success: function () {
              showInfo("Berhasil");
              $("#ag_id").html($("#opt-list_user option:selected").text());
              return true;
            },
            dataType: "json",
          };
          $("#form_set_assign").ajaxSubmit(options);

          //return false;
        },
      },
      button: {
        label: "Close",
        className: "btn-sm btn-danger",
      },
    };

    showCommonDialog3(
      500,
      500,
      "Reassign",
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "detail_account/detail_account/reassign_account/?account_id=" +
      card_number,
      buttons
    );
  });

  // characeter limiter
  $("textarea.limited").inputlimiter({
    remText: "%n character%s remaining...",
    limitText: "max allowed : %n.",
  });

  // ajax tabs
  $("#tabCustomer a, #tabCallMenu a").click(function (e) {
    e.preventDefault();

    var url = $(this).attr("data-url");
    var href = this.hash;
    var pane = $(this);

    // ajax load from data-url
    $(href).load(url, function (result) {
      pane.tab("show");
    });
  });

  //Appointment
  var currentDate = new Date();

  $("#select_call_status").change(function () {
    var active_tab = $("ul#contractDetailTab li.active")
      .attr("id")
      .substring(9);
    if ($(this).val() == "CONNECTED") {
      $("#select_place, #select_contact_person").removeAttr("disabled");
      $("#not_connected_result").slideUp("fast", function () {
        $("#not_connected_result_note").slideUp("fast");
        $("#connected_result_" + active_tab).slideDown("slow");
      });
    } else {
      $("#select_place, #select_contact_person").attr("disabled", "disabled");
      $(".connected_result").slideUp("fast", function () {
        $("#not_connected_result").slideDown("slow");
        $("#not_connected_result_note").slideDown("slow");
      });
    }
  });

  $("#btnSaveFollowup").click(function () {
    save_history_call("save");
  });

  $("#btnSaveAndbreakFollowup").click(function () {
    //cek validasi submit
    if (!validasiSubmit()) {
      return false;
    }

    var buttons = {
      save: {
        label: "Break",
        className: "btn btn-xs btn-primary",
        callback: function () {
          if ($("#txt_break_reason").val() == "") {
            showWarning("Please select break reason.", 2000);
            return false;
          } else {
            setTimeout(function () {
              console.log("delaying dial just 1 second.");
            }, 10000);

            saveAndBreak($("#txt_break_reason").val());
          }
        },
      },
      button: {
        label: "Close",
        className: "btn-sm btn-danger",
      },
    };

    showCommonDialog3(
      300,
      160,
      "Agent Break",
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "detail_account/detail_account/save_and_break/save_and_break_form",
      buttons
    );
  });

  $("#btn_eskalasi_sms").click(function () {
    // console.log("phone_no= "+$("#phone-owner").val().split('|')[1]);
    let template = $("#sms_template").val();
    let phone = $("#phone-owner").val();
    if (template != "" && phone != undefined) {
      if (phone == "Other") {
        phone = $("#other_phone").val();
      } else {
        phone = phone.split("|")[1];
      }
      var buttons = {
        save: {
          label: "Send",
          className: "btn-sm btn-primary btn-save-update-data",
          style: "disabled",
          callback: function () {
            var options = {
              url:
                GLOBAL_MAIN_VARS["SITE_URL"] +
                "detail_account/detail_account/send_message",
              data: {
                account_no: card_number,
                type: "sms",
                template_id: template,
              },
              type: "post",
              success: function () {
                showInfo("Berhasil");
                return true;
              },
              dataType: "json",
            };
            $("#messaging_preview").ajaxSubmit(options);

            //return false;
          },
        },
        button: {
          label: "Close",
          className: "btn-sm btn-danger",
        },
      };

      showCommonDialog3(
        500,
        500,
        "SMS PREVIEW",
        GLOBAL_MAIN_VARS["SITE_URL"] +
        "detail_account/detail_account/messaging_preview/?type=sms&account_id=" +
        card_number +
        "&template=" +
        template +
        "&phone=" +
        phone,
        buttons
      );
    } else {
      if (phone == undefined) {
        showInfo("Silahkan Pilih Phone tujuan!");
      } else {
        showInfo("Silahkan Pilih Template SMS!");
      }
    }
  });
  $("#btn_eskalasi_wa").click(function () {
    // console.log("phone_no= "+$("#phone-owner").val().split('|')[1]);
    let template = $("#wa_template").val();
    let phone = $("#phone-owner").val();
    if (template != "" && phone != undefined) {

      //validasi harus ke handphone 1 (soalnya di mapping ke situ)
      if ($('option[phonetype="hp1"]').val() != phone) {
        showInfo("Silahkan Pilih Phone tujuan Handphone 1");
        return false;
      }

      if (phone == "Other") {
        phone = $("#other_phone").val();
      } else {
        phone = $("#phone-owner").val();
      }
      var buttons = {
        save: {
          label: "Send",
          className: "btn-sm btn-primary btn-save-update-data",
          style: "disabled",
          callback: function () {
            var options = {
              url:
                GLOBAL_MAIN_VARS["SITE_URL"] +
                "WhatsappConversation/WhatsappConversation/blast_template_by_agent",
              data: {
                account_no: card_number,
                type: "wa",
                template_id: template,
              },
              type: "post",
              success: function (data) {


                showInfo("Berhasil");
                if (data.success === true) {

                  $(".maskName").html(data.data['to_number']);
                  $(".status").html(data.data['template_name']);
                  $(".conversation-container").html('');
                  $(".conversation-container").append('<div class="message sent"><span id="template">' + data.data['message'] + '</span><br><span class="metadata"><span class="time">' + data.data['created_time'] + '</span></span><span class="metadata" style="float: left;"><span class="time"><i  class="bi bi-clock-history tooltip_all" id="tooltip_all0"></i></span></span></div>');
                  $("#txt_wa").val('')
                } 2

                return true;
              },
              dataType: "json",
            };
            $("#messaging_preview").ajaxSubmit(options);

            //return false;
          },
        },
        button: {
          label: "Close",
          className: "btn-sm btn-danger",
        },
      };

      showCommonDialog3(
        500,
        500,
        "WA PREVIEW",
        GLOBAL_MAIN_VARS["SITE_URL"] +
        "detail_account/detail_account/messaging_preview/?type=wa&account_id=" +
        card_number +
        "&template=" +
        template +
        "&phone=" +
        phone,
        buttons
      );
    } else {
      if (phone == undefined) {
        showInfo("Silahkan Pilih Phone tujuan!");
      } else {
        showInfo("Silahkan Pilih Template WA!");
      }
    }
  });

  $("#btn_eskalasi_email").click(function () {
    let template = $("#email_template").val();
    if (template != "") {
      var buttons = {
        save: {
          label: "Send",
          className: "btn-sm btn-primary btn-save-update-data",
          style: "disabled",
          callback: function () {
            var options = {
              url:
                GLOBAL_MAIN_VARS["SITE_URL"] +
                "detail_account/detail_account/send_message",
              data: {
                account_no: card_number,
                type: "email",
                template_id: template,
              },
              type: "post",
              success: function () {
                showInfo("Berhasil");
                return true;
              },
              dataType: "json",
            };
            $("#messaging_preview").ajaxSubmit(options);

            //return false;
          },
        },
        button: {
          label: "Close",
          className: "btn-sm btn-danger",
        },
      };

      showCommonDialog3(
        600,
        600,
        "EMAIL PREVIEW",
        GLOBAL_MAIN_VARS["SITE_URL"] +
        "detail_account/detail_account/messaging_preview/?type=email&account_id=" +
        card_number +
        "&template=" +
        template,
        buttons
      );
    } else {
      showInfo("Silahkan Pilih Template SMS!");
    }
  });

  $("#btn_save_status").click(function () {
    var active_card = $("ul#contractDetailTab li.active")
      .attr("id")
      .substring(9);
    $.post(
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "detail_account/detail_account/save_status",
      {
        account_no: active_card,
        status: $("#account_status").val(),
      },
      function (responseText) {
        if (responseText.success) {
          showInfo("Data Terkirim.");
        } else {
          showInfo("Data Gagal Terkirim.");
        }
      },
      "json"
    );
  });
});

$("#btn-dial-Handphone").click(function () {
  $("#caller_id1").val("");
  $("#caller_id2").val("");
  if ($("#btn-dial-Handphone").val() == "dial") {
    var now = new Date();
    $("#dial_time").val(
      now.getFullYear() +
      "-" +
      (now.getMonth() + 1) +
      "-" +
      now.getDate() +
      " " +
      now.getHours() +
      ":" +
      now.getMinutes() +
      ":" +
      now.getSeconds()
    );

    //no_hp customer
    var phone_element = $("#other_phone")
      .val()
      .replace(/[^0-9]/g, "");
    showInfo("Dialing " + phone_element);
    // setTimeout(outbound(), 1000);
    // outbound();
    // originateCalloriginateCall(phone_element, $("#curr_customer_id").val());
    GLOBAL_MAIN_VARS["BREAK"] = false;
    TELEPHONY_CALLER_ID = "";
    originateCall(phone_element, GLOBAL_MAIN_VARS["CONTRACT_NUMBER"]);
    GLOBAL_MAIN_VARS["ACTIVE_CALL_NO"] = phone_element;
    //$("#txt_dialed_phone_no").val("");
    $("#btn-dial-Handphone")
      .val("hangup")
      .removeClass("btn-success")
      .addClass("btn-danger");
  } else {
    disconnectCall();
    $("#btn-dial-Handphone")
      .val("dial")
      .removeClass("btn-danger")
      .addClass("btn-success");
  }
});

function setDisplayLov() {
  if (lov1_status == "0") {
    $("#lov1").parent().hide().attr("disabled", true);
    $("#lbllov1").hide();
  }
  if (lov2_status == "0") {
    $("#lov2").parent().hide().attr("disabled", true);
    $("#lbllov2").hide();
  }
  if (lov3_status == "0") {
    $("#lov3").parent().hide().attr("disabled", true);
    $("#lbllov3").hide();
  }
  if (lov4_status == "0") {
    $("#lov4").parent().hide().attr("disabled", true);
    $("#lbllov4").hide();
  }
  if (lov5_status == "0") {
    $("#lov5").parent().hide().attr("disabled", true);
    $("#lbllov5").hide();
  }
}

$(document).ready(function () {
  $("#CallID").val(uuid());
  setDisplayLov();

  $("#lov3").change();

  if (GLOBAL_MAIN_VARS["ACTIVE_CALL_NO"] != "") {
    $("#phone-owner").val(GLOBAL_MAIN_VARS["ACTIVE_CALL_NO"]).change();
    if ($("#phone-owner").val() == null) {
      $("#phone-owner").val("other").change();
      $("#other_phone").val(GLOBAL_MAIN_VARS["ACTIVE_CALL_NO"]);
    }
  }
});


function saveAndBreak(reason) {
  try {
    disconnectCall();
  } catch (error) {
    console.log("error", error);
  }
  GLOBAL_MAIN_VARS["BREAK"] = true;
  $("#btnSaveFollowup").click();
  // aux("Break"); // telepony break
  setTimeout(() => {
    aux(reason);
    $("#btnGetAccount").attr("disabled", false).show();
    setDialingModeDesc(dialing_mode);
  }, 1000);
}

function validasiSubmit() {
  if ($("#lov1").val() == "" && lov1_status == "1") {
    showWarning("Please select " + lbl_lov1);
    return false;
  }
  if ($("#lov2").val() == "" && lov2_status == "1") {
    showWarning("Please select " + lbl_lov2);
    return false;
  }
  if ($("#lov3").val() == "" && lov3_status == "1") {
    showWarning("Please select " + lbl_lov3);
    return false;
  }
  if ($("#lov4").val() == "" && lov4_status == "1") {
    showWarning("Please select " + lbl_lov4);
    return false;
  }
  if ($("#lov5").val() == "" && lov5_status == "1") {
    showWarning("Please select " + lbl_lov5);
    return false;
  }
  if ($("#select_escalate_to_tl").val() == "") {
    showWarning("Please select Escalate to TL");
    return false;
  }

  if (approval == "1") {
  }

  if ($("#select_join_program").val() == "") {
    showWarning("Please select Join Program");
    return false;
  }

  // if($("#select_cara_bayar["+card_number+"]").val() == "" ){
  // showWarning("Please select cara bayar");
  // return false;
  // }
  if ($("#txt_agent_notepad").val() == "") {
    showWarning("Please input note");
    return false;
  }

  return true;
}

setTimeout(() => {
  if (GLOBAL_MAIN_VARS["ACTIVE_CALL_NO"] != "") {
    $("#btn-dial-Handphone")
      .val("hangup")
      .removeClass("btn-success")
      .addClass("btn-danger");
  }
}, 1000);

$("#btnSaveAndNext").click(function () {
  save_history_call("saveandnext");
});

$("#btnAddNewPhone").click(() => {
  var buttons = {
    save: {
      label: "Save",
      className: "btn-sm btn-primary btn-save-AddNewPhone",
      callback: function () {
        let param = {
          csrf_security: $("#csrf_token").val(),
          callId: $("#call_id_new_phone").val(),
          contract_number: $("#contract_number_new_phone").val(),
          newhp1: $("#newhp1").val(),
          newhp2: $("#newhp2").val(),
          newhp3: $("#newhp3").val(),
        };
        console.log("param", param);
        if (param.newhp1 == "" && param.newhp2 == "" && param.newhp3 == "") {
          showWarning("phone is empty");
          return false;
        }
        if (param.contract_number == "") {
          showWarning("loan is empty");
          return false;
        }

        $.ajax({
          url:
            GLOBAL_MAIN_VARS["SITE_URL"] +
            "detail_account/detail_account/save_new_phone",
          data: param,
          type: "post",
          success: function () {
            showInfo("Berhasil");
            return true;
          },
          dataType: "json",
        });

        //return false;
      },
    },
    button: {
      label: "Close",
      className: "btn-sm btn-danger",
    },
  };

  let callId = $("#CallID").val();

  showCommonDialog3(
    400,
    500,
    "Add New Phone",
    GLOBAL_MAIN_VARS["SITE_URL"] +
    "detail_account/detail_account/add_new_phone_form?collection_history_id=" +
    callId +
    "&contract_number=" +
    card_number,
    buttons
  );
});

$("#btnAddNewAddress").click(() => {
  var buttons = {
    save: {
      label: "Save",
      className: "btn-sm btn-primary btn-save-AddNewAddress",
      style: "disabled",
      callback: function () {
        let param = {
          csrf_security: $("#csrf_token").val(),
          callId: $("#call_id_new_phone").val(),
          contract_number: $("#contract_number_new_phone").val(),
          provinsi: $("#provinsi").val(),
          city: $("#city").val(),
          district: $("#district").val(),
          subdistrict: $("#sub-district").val(),
          zipcode: $("#zipcode").val(),
          address: $("#address").val(),
        };

        console.log("param", param);

        if (param.contract_number == "") {
          showWarning("loan is empty");
          return false;
        }
        $.ajax({
          url:
            GLOBAL_MAIN_VARS["SITE_URL"] +
            "detail_account/detail_account/save_new_address",
          data: param,
          type: "post",
          success: function () {
            showInfo("Berhasil");
            return true;
          },
          dataType: "json",
        });

        //return false;
      },
    },
    button: {
      label: "Close",
      className: "btn-sm btn-danger",
    },
  };

  let callId = $("#CallID").val();

  showCommonDialog3(
    500,
    500,
    "Add New Address",
    GLOBAL_MAIN_VARS["SITE_URL"] +
    "detail_account/detail_account/add_new_address_form?collection_history_id=" +
    callId +
    "&contract_number=" +
    card_number,
    buttons
  );
});

$("#btnAddNewEc").click(() => {
  var buttons = {
    save: {
      label: "Save",
      className: "btn-sm btn-primary btn-save-AddNewAddress",
      style: "disabled",
      callback: function () {
        let param = {
          csrf_security: $("#csrf_token").val(),
          callId: $("#call_id_new_ec").val(),
          contract_number: $("#contract_number_new_ec").val(),
          ecName: $("#ecName").val(),
          ecPhone: $("#ecPhone").val(),
          ecAddress: $("#ecAddress").val(),
        };

        console.log("param", param);

        if (
          param.ecName == "" &&
          param.ecPhone == "" &&
          param.ecAddress == ""
        ) {
          showWarning("mail is empty");
          return false;
        }
        if (param.contract_number == "") {
          showWarning("loan is empty");
          return false;
        }
        $.ajax({
          url:
            GLOBAL_MAIN_VARS["SITE_URL"] +
            "detail_account/detail_account/save_new_ec",
          data: param,
          type: "post",
          success: function () {
            showInfo("Berhasil");
            return true;
          },
          dataType: "json",
        });

        //return false;
      },
    },
    button: {
      label: "Close",
      className: "btn-sm btn-danger",
    },
  };

  let callId = $("#CallID").val();

  showCommonDialog3(
    500,
    500,
    "Add New Emergency Contact",
    GLOBAL_MAIN_VARS["SITE_URL"] +
    "detail_account/detail_account/add_new_ec_form?collection_history_id=" +
    callId +
    "&contract_number=" +
    card_number,
    buttons
  );
});

$("#btnAddNewEmail").click(() => {
  var buttons = {
    save: {
      label: "Save",
      className: "btn-sm btn-primary btn-save-AddNewAddress",
      style: "disabled",
      callback: function () {
        let param = {
          csrf_security: $("#csrf_token").val(),
          callId: $("#call_id_new_mail").val(),
          contract_number: $("#contract_number_new_mail").val(),
          newMail1: $("#newMail1").val(),
          newMail2: $("#newMail2").val(),
          newMail3: $("#newMail3").val(),
        };

        console.log("param", param);

        if (
          param.newMail1 == "" &&
          param.newMail2 == "" &&
          param.newMail3 == ""
        ) {
          showWarning("mail is empty");
          return false;
        }
        if (param.contract_number == "") {
          showWarning("loan is empty");
          return false;
        }
        $.ajax({
          url:
            GLOBAL_MAIN_VARS["SITE_URL"] +
            "detail_account/detail_account/save_new_mail",
          data: param,
          type: "post",
          success: function () {
            showInfo("Berhasil");
            return true;
          },
          dataType: "json",
        });

        //return false;
      },
    },
    button: {
      label: "Close",
      className: "btn-sm btn-danger",
    },
  };

  let callId = $("#CallID").val();

  showCommonDialog3(
    500,
    500,
    "Add New Email",
    GLOBAL_MAIN_VARS["SITE_URL"] +
    "detail_account/detail_account/add_new_email_form?collection_history_id=" +
    callId +
    "&contract_number=" +
    card_number,
    buttons
  );
});

$("#opt_request_phone_tag").change(function () {
  let value = $("#opt_request_phone_tag").val();

  if (value == "1") {
    $("#opt_reason_phone_tag").attr("disabled", false).val("");
  } else {
    $("#opt_reason_phone_tag").attr("disabled", true).val("");
  }
});

function save_history_call(source) {
  //save atau saveandnext
  // $("#CallID").val(uuid());
  try {
    $("#txt_TELEPHONY_CALLER_ID").val(TELEPHONY_CALLER_ID);
    $("#txt_TELEPHONY_RECORDING_ID").val(TELEPHONY_RECORDING_ID);
  } catch (error) {
    $("#txt_TELEPHONY_CALLER_ID").val("");
    $("#txt_TELEPHONY_RECORDING_ID").val("");
  }
  // var other_phone = $('#other_phone').val();
  var showFormResponse = function (responseText, statusText) {
    if (responseText.success) {
      showInfo("Berhasil");

      $("#formCallMenu")[0].reset();

      $("#txt_TELEPHONY_CALLER_ID").val("");
      $("#txt_TELEPHONY_RECORDING_ID").val("");
      $("#DialedPhoneType").val("");

      GLOBAL_MAIN_VARS["CONTRACT_NUMBER"] = "";
      GLOBAL_MAIN_VARS["ACTIVE_CALL_NO"] = "";
      $("#lastAccountHandling").hide(1000);
      $("#btnGetAccount").attr("disabled", false).html("GET ACCOUNT ");

      $("#coord_task_table").trigger("reloadGrid");
      $("#approval_account_table").trigger("reloadGrid");
      $("#assigned-account-list-grid-table").trigger("reloadGrid");

      let app = appointment_checking();
      if (!app) {
        if (source == "saveandnext") {
          setTimeout(() => {
            if (dialing_mode == "1") {
              autoIn();
              $(".btn-close").click();
              $("#btnGetAccount")
                .attr("disabled", true)
                .html("<i>waiting account...</i>");
              $("#btnStopGetAccount").show();
            } else {
              $(".btn-close").click();
            }
          }, 500);
        }
      }
    } else {
      showInfo("Gagal");
    }
  };

  //cek validasi submit
  if (!validasiSubmit()) {
    return false;
  }

  try {
    disconnectCall();
    $("#btn-dial-Handphone")
      .val("dial")
      .removeClass("btn-danger")
      .addClass("btn-success");
  } catch (error) {
    console.log("error", error);
  }

  var options = {
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "detail_account/detail_account/save_followup",
    type: "post",
    //beforeSubmit: jqformValidate,
    success: showFormResponse,
    dataType: "json",
  };

  //diberi delay agar memberi kesempatan telephony untuk save session log
  setTimeout(() => {
    $("#formCallMenu").ajaxSubmit(options);
  }, 1000);

  return false;
}

function appointment_checking() {
  var app = false;
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "detail_account/detail_account/appointment_chacking",
    type: "get",
    success: function (msg) {
      console.log("msg appointment_checking ", msg);
      if (msg.appointment) {
        app = true;
        $(".btn-close").click();
        setTimeout(() => {
          loadDataCustomer(msg.contract_number);
          showInfo(msg.message);
          $(".modal-title").html("APPOINTMENT - " + msg.appointment_time);
        }, 700);
      }
    },
    dataType: "json",
  });

  return app;
}
/*$("#btn_send_wa").click(function () {

  $.ajax({
      type: "GET",
      url: GLOBAL_MAIN_VARS["SITE_URL"] + "detail_account/detail_account/get_token",
      dataType: "json",
      success: function (msg) {
          var inputElement = document.getElementById("token");
              inputElement.name = msg.data['name']; // Change name
              inputElement.value = msg.data['value'];
          var options = {
            url:GLOBAL_MAIN_VARS["SITE_URL"] +"detail_account/detail_account/reply_wa",
            type: 'POST',
            dataType: 'json',     
            success: function (data) {
              if(data.success === true){
                

                $("#coba_tooltips"+$('#cm_card_nmbr').val()).append('<div class="message sent"><span id="template">'+data.data['message']+'</span><span class="metadata"><span class="time">'+data.data['created_time']+'</span></span><span class="metadata" style="float: left;"><span class="time"><i  class="bi bi-check2-all tooltip_all" id="tooltip_all'+$('#coba_tooltips'+$('#cm_card_nmbr').val()+' .message.sent').length+'"></i></span></span></div>');
                $("#txt_wa").val('')
              }else{
                
                alert(data.message)

                
              }
              return false;
            },
            error: function (a) {
              //backend.notify('error', a.responseText, 'bar', 'jelly');
            }
          };
          $('#form_add').ajaxSubmit(options);
      },
      error: function (error) {
          reject(error);  // Menangani error jika ada masalah dalam request
      }
  });
  
  
});*/



