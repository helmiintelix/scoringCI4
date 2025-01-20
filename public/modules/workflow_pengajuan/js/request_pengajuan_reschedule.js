$("#flag_kondisi_khusus").on("change", function () {
  $("th").css("color", "black");
  $("#txt_catatan_kondisi_khusus").html("");
  if ($(this).val() == "1") {
    $("#alert_pilih_salah_satu").show();
    $("#desc_kondisi_khusus, #txt_catatan_kondisi_khusus").attr(
      "disabled",
      false
    );

    $(".tbl_block_code").hide();
    $(".tbl_kondisi_khusus").show();

    $(".tbl_block_code").css("color", disabled_color);
    $(".tbl_kondisi_khusus").attr("style", "color:black");
    $(".tbl_spcl_kondisi").attr("style", "color:black");

    $(".tbl_cc").css("color", disabled_color);
    $(".tbl_pl").css("color", disabled_color);
    // $('#desc_kondisi_khusus').change();
  } else {
    $("#txt_catatan_kondisi_khusus").val("");
    $("#desc_kondisi_khusus,#txt_catatan_kondisi_khusus").attr(
      "disabled",
      true
    );
    $("#desc_kondisi_khusus").val("");
    // $('#desc_kondisi_khusus').val('').change();

    $(".tbl_block_code").show();
    $(".tbl_kondisi_khusus").hide();

    $(".tbl_block_code").attr("style", "color:black");
    $(".tbl_kondisi_khusus").css("color", disabled_color);
    $(".tbl_spcl_kondisi").css("color", disabled_color);

    $(".tbl_cc").css("color", "black");
    $(".tbl_pl").css("color", "black");

    $("#alert_pilih_salah_satu").hide();
  }
  search_param("NEW");
});

$("#desc_kondisi_khusus").on("change", function () {
  if ($(this).val() != "") {
    $("#alert_pilih_salah_satu").hide();
  } else {
    $("#alert_pilih_salah_satu").show();
  }
  $("#param_bucket").html(bucket);
  search_param("NEW");
});

function search_param(param) {
  $("#restructure_rate").val("");
  $("#param_max_normal_disc_rate").html("");
  $("#param_max_interest_disc_rate").html("");
  $("#param_max_tenor").html("");
  $("#param_cicilan").html("");
  $("#param_min_outstanding").html("");
  $("#lbl_nama_parameter").html("");
  $("#param_block_status").html("");
  $("#alert_parameter").html("<i>Loading...</i>");

  max_normal_disc_rate = null;
  max_interest_disc_rate = null;
  limit_max_normal_disc_rate = null;
  limit_max_interest_disc_rate = null;
  data1 = {
    id_pengajuan: $("#txt_id_pengajuan").val(),
    request_type: "RSCH",
    flag_kondisi_khusus: $("#flag_kondisi_khusus").val(),
    desc_kondisi_khusus: $("#desc_kondisi_khusus").val(),
    agreement_no: $("#txt_card_number").val(),
    mob: $("#txt_mob").val(),
    bucket: $("#txt_bucket").val(),
    product_id: $("#txt_product_id").val(),
    block_code: $("#txt_block_code").val(),
  };
  console.log(data1);
  $.ajax({
    type: "GET",
    url:
      URL_DATA +
      "workflow_pengajuan/workflow_pengajuan_reschedule/cek_config_rest",
    data: data1,
    dataType: "json",
    //timeout: 3000,
    success: function (msg) {
      // $("#txt-late-charge-discount-2").val('0');
      // $('#txt-interest-discount-2').val('0');
      // $('#txt-late-charge-discount').val('0');
      // $('#txt-interest-discount').val('0');
      if (param == "NEW") {
        $("#txt-principle-balance-discount").val("");
        $("#txt-principle-balance-discount-2").val("");
        $("#txt-late-charge-discount-2").val("");
        $("#txt-interest-discount-2").val("");
        $("#txt-late-charge-discount").val("");
        $("#txt-interest-discount").val("");
        $("#txt-payment-pokok-val").val("");
        $("#txt_catatan_kondisi_khusus").html("");
        // $('.btn-form-upload').hide();
      }

      if (msg.success && msg.data != "NOT_FOUND") {
        let json_param = JSON.parse(msg.data.parameter_json);
        // console.log('json_param',json_param);
        $.each(json_param.rules, function (i, val) {
          console.log("val", val.value);
          var os_balance = "";
          if (Array.isArray(val.value)) {
            if (val.value.length > 1) {
              try {
                os_balance = val.value.join(" - ");
              } catch (error) {
                console.log("err", error);
              }
            } else {
              os_balance = val.value;
            }
          } else {
            os_balance = val.value;
          }

          if (val.id == "outstanding_balance") {
            $("#param_min_outstanding").html(os_balance);
          }
          if (val.id == "MOB") {
            // $('#param_mob').html(val.value);
          }
          if (val.id == "bucket") {
            // $('#param_bucket').html(val.value);
          }
          if (val.id == "") {
            block_code;
            $("#param_block_status").html(val.value);
          }
        });
        try {
          $("#param_max_late_charge_disc_rate").html(
            msg.data.max_late_charge_rate.toString()
          );
        } catch (error) {}

        try {
          $("#param_max_normal_disc_rate").html(
            msg.data.max_discount_rate.toString()
          );
        } catch (error) {}

        $("#reschedule_rate").val(msg.reschedule_parameter);

        try {
          $("#param_max_interest_disc_rate").html(
            msg.data.max_interest_rate.toString()
          );
          $("#param_cicilan").html(msg.data.ratio_cicilan.toString());
        } catch (error) {}

        $("#param_max_tenor").html(msg.data.max_tenor.toString());

        try {
          max_normal_disc_rate = parseFloat(msg.data.max_discount_rate);
          max_interest_disc_rate = parseFloat(msg.data.max_interest_rate);
          max_late_charge_disc_rate = parseFloat(msg.data.max_late_charge_rate);
        } catch (error) {}

        limit_max_normal_disc_rate = parseFloat(msg.data.max_discount_rate);
        limit_max_interest_disc_rate = parseFloat(msg.data.max_interest_rate);
        limit_max_late_charge_disc_rate = parseFloat(
          msg.data.max_late_charge_rate
        );

        $("#lbl_nama_parameter").html(
          "Parameter Name : <b>" + msg.data.parameter_name + "</b>"
        );
        $("#alert_parameter").html(msg.alert);
      } else {
        $("#alert_parameter").html(msg.alert);
        // showInfo("Parameter Not Found.");
      }
    },
    error: function (e) {
      showInfo(JSON.stringify(e));
      // $('#desc_kondisi_khusus').val('').change();
      $("#alert_parameter").html('<b style="color:red">ERROR</b>');
    },
  });
}

// $(document).ready(function(){
// 	let screen_level = $('#txt_screen_level').val();

// 	if(screen_level=='NEW'){
// 		set_new_form();
// 	}
// 	else if(screen_level=='EDIT'){

// 	}
// });

jQuery(function ($) {
  // var date = '<?=date('Y - m - d ');?>';
  $("#txt_ptp_date_for_rsc").daterangepicker(
    {
      singleDatePicker: true,
      autoApply: true,
      minDate: date,
      maxDate: ptp_grace_period,
      locale: {
        format: "YYYY-MM-DD",
      },
    },
    function (start, end, label) {
      // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');

      get_data_perhitungan_kotak_merah();
      $("#txt-late-charge-discount-2").val("");
      $("#txt-interest-discount-2").val("");
      $("#txt-late-charge-discount").val("");
      $("#txt-interest-discount").val("");

      $("#txt-total-bayar-diskon").html("");
      $("#txt-payment-pokok-val").val("");

      $("#txt-sisa-pokok-pinjaman-baru-val").val("");
      $("#txt-sisa-pokok-pinjaman-baru").val("");

      $("#txt-payment-pokok").val("");
      $("#txt-payment-pokok-val").val("");

      $("#txt_tenor_val").val("");
      $("#txt_interest_val").val("");

      $("#txt_new_installmetn_amount_val").val("");
      $("#txt_new_installmetn_amount").val("");
    }
  );

  let screen_level = $("#txt_screen_level").val();

  if (screen_level == "NEW") {
    set_new_form();
    get_data_perhitungan_kotak_merah();
    search_param("NEW");
    $(".btn-form-upload").hide();
  } else if (screen_level == "EDIT") {
    search_param("EDIT");
    $(".btn-form-upload").show();
  } else if (screen_level == "ASSIGNED") {
    search_param("EDIT");
    // $("input, textarea").attr('readonly',true);
    // $("#call-verif-process input").attr('readonly',false);
    // $("#call-verif-process textarea").attr('readonly',false);
  }

  if (product_type == "CIMB-PL") {
    $("#div_moratorium").show();
  }
});

function set_new_form() {
  let source = $("#source").val();
  var today = new Date();
  // var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
  // var date = '<?=date('Y - m - d ');?>';

  $("#flag_kondisi_khusus").change();

  if (source == "CMS") {
  } else if (source == "TELE") {
    $("#txt_ptp_date_for_rsc")
      .datepicker({
        dateFormat: "yy-mm-dd",
        minDate: date,
        maxDate: ptp_grace_period,
      })
      .on("change", function () {
        get_data_perhitungan_kotak_merah();
        $("#txt-late-charge-discount-2").val("");
        $("#txt-interest-discount-2").val("");
        $("#txt-late-charge-discount").val("");
        $("#txt-interest-discount").val("");

        $("#txt-total-bayar-diskon").html("");
        $("#txt-payment-pokok-val").val("");

        $("#txt-sisa-pokok-pinjaman-baru-val").val("");
        $("#txt-sisa-pokok-pinjaman-baru").val("");

        $("#txt-payment-pokok").val("");
        $("#txt-payment-pokok-val").val("");

        $("#txt_tenor_val").val("");
        $("#txt_interest_val").val("");

        $("#txt_new_installmetn_amount_val").val("");
        $("#txt_new_installmetn_amount").val("");
      });
  }
}

function set_edit_form() {}

function validasi2(elm) {
  // countSisaPokokPinjaman();
  let val = parseFloat(elm.value);
  if (val <= 0 || elm.value == "") {
    // elm.value = '0';
  }
}

function validationparam(elm, name, elmTo) {
  elm.value = elm.value.replace(/[^0-9.]/g, "");

  let disc_late_charge = parseFloat(
    $("#txt-late-charge-discount-2").val().replace(",", ".")
  );
  let disc_principal = parseFloat(
    $("#txt-principle-balance-discount-2").val().replace(",", ".")
  );
  let disc_interest = parseFloat(
    $("#txt-interest-discount-2").val().replace(",", ".")
  );
  // let disc_penalty  = parseFloat($('#txt-penalty-discount-2').val().replace(',','.'));
  if (isNaN(disc_principal)) disc_principal = 0;
  if (isNaN(disc_interest)) disc_interest = 0;
  // if(isNaN(disc_penalty))disc_penalty=0;

  if (name == "principle-balance") {
    let current = 0;
    current = max_normal_disc_rate - disc_principal;

    let max = max_normal_disc_rate;

    let sisa = 0;
    sisa = limit_max_normal_disc_rate - disc_principal;
    sisa = sisa.toString();
    if (sisa < 0) {
      $(elm).val(limit_max_normal_disc_rate);
    }
  } else if (name == "interest") {
    let curInterrest = parseFloat($("#txt-due-interest").val());
    if (curInterrest == 0) {
      $(elm).val("0");
    } else {
      let current = 0;
      current = max_interest_disc_rate_2 - disc_interest;

      let max = max_interest_disc_rate_2;

      let sisa = 0;
      sisa = limit_max_interest_disc_rate_2 - disc_interest;
      sisa = sisa.toString();
      if (sisa < 0) {
        $(elm).val(limit_max_interest_disc_rate_2);
      }
    }
  } else if (name == "late-charge") {
    //late-charge
    let curlatecharge = parseFloat(
      $("#txt-late-charge").val().replaceAll(",", "")
    );
    if (curlatecharge == 0) {
      $(elm).val("0");
    } else {
      let current = 0;
      current = max_late_charge_disc_rate - disc_late_charge;

      let max = max_late_charge_disc_rate;

      let sisa = 0;
      sisa = limit_max_late_charge_disc_rate - disc_late_charge;
      sisa = sisa.toString();
      if (sisa < 0) {
        $(elm).val(limit_max_late_charge_disc_rate);
      }
    }
  } else if (name == "penalty") {
    //penalty
    let curPenalty = parseFloat($("#txt-penalty").val());
    if (curPenalty == 0) {
      $(elm).val("0");
    } else {
      let current = 0;
      current = max_penalty_disc_rate - disc_penalty;

      let max = max_penalty_disc_rate;

      let sisa = 0;
      sisa = max_penalty_disc_rate - disc_penalty;
      sisa = sisa.toString();
      if (sisa < 0) {
        $(elm).val(max_penalty_disc_rate);
      }
    }
  }

  if ($("#flag_deviation").val() == "0") {
    $("#txt_deviation_reason").val("").attr("disabled", true);
  } else {
    $("#txt_deviation_reason").val("").attr("disabled", false);
  }

  setTimeout(() => {
    diskon_to_amount(elm, elmTo);
  }, 600);
}

function countAmountPTP(elm) {
  if (elm.value == "") return false;
  let valAmnt = parseFloat(elm.value.replaceAll(",", ""));
  let payBunga = parseFloat(
    $("#txt-payment-bunga").val().replaceAll(",", "").replace(/\s/g, "")
  );
  let payDenda = parseFloat(
    $("#txt-payment-denda").val().replaceAll(",", "").replace(/\s/g, "")
  );
  let payPokok = valAmnt - payBunga - payDenda;

  payPokok = isNaN(payPokok) ? 0 : payPokok;

  let strPayPokok = addPeriodx(payPokok);

  $("#txt-payment-pokok").val(strPayPokok);
  $("#txt-payment-pokok-val").val(payPokok);

  elm.value = elm.value.replace(/[^0-9.,]/g, "");
  elm.value = addPeriod(elm.value.toString(), elm);

  countSisaPokokPinjaman();
  countNewInstallmanetAmount();
}

function currencyformat(elm) {
  if (elm.value == "") return false;
  elm.value = elm.value.replace(/[^0-9.,]/g, "");
  elm.value = addPeriod(elm.value.toString(), elm);
  countSisaPokokPinjaman();
  countNewInstallmanetAmount();
}

function numberOnly(elm) {
  let deviasi = $("#flag_deviation").val();
  if (elm.id == "txt_interest_val") {
    if (deviasi == "0") {
      if (elm.value == "") return false;
      elm.value = elm.value.replace(/[^0-9.,]/g, "");
      // if(parseFloat(elm.value)>max_interest_disc_rate){
      if (parseFloat(elm.value) > 100) {
        elm.value = 100;
      } else if (parseFloat(elm.value) < 0) {
        elm.value = "0";
      }
    } else {
      //deviasi ,maksimal 100
      if (elm.value == "") return false;
      elm.value = elm.value.replace(/[^0-9.,]/g, "");
      if (parseFloat(elm.value) > 100) {
        elm.value = 100;
      } else if (parseFloat(elm.value) < 0) {
        elm.value = "0";
      }
    }
  } else if (elm.id == "txt_tenor_val") {
    $("#alert_input_tenor").hide();
    if ($("#flag_deviation").val() == "1") {
      elm.value = elm.value.replace(/[^0-9.,]/g, "");
    } else {
      let max_tenor = parseFloat($("#param_max_tenor").html());
      if (isNaN(max_tenor)) {
        elm.value = "0";
        $("#alert_input_tenor").show();
      } else {
        elm.value = elm.value.replace(/[^0-9.,]/g, "");
        if (elm.value > max_tenor) {
          elm.value = max_tenor.toString();
        } else if (elm.value < 0) {
          elm.value = "0";
        }
      }
    }

    if (product_id == "CC") {
      setTimeout(() => {
        //get_interest_api(elm.value);
      }, 200);
    }
  } else {
    if (elm.value == "") return false;
    elm.value = elm.value.replace(/[^0-9.,]/g, "");
  }
  setTimeout(() => {
    countSisaPokokPinjaman();
    countNewInstallmanetAmount();
  }, 600);
}

function addPeriodx(nStr) {
  let currency = nStr;

  currency = parseFloat(currency)
    .toLocaleString("en-US", {
      style: "currency",
      currency: "IDR",
      minimumFractionDigits: 2,
    })
    .replace("IDR", "")
    .trim();

  return currency;
}

function addPeriod(nStr, elm) {
  let currency = nStr;
  currency = currency.replaceAll(",", "");

  // currency = (parseFloat(currency)).toLocaleString('en-US', {
  // 	style: 'currency',
  // 	currency: 'idr',
  // 	minimumFractionDigits: 0
  // }).replace('IDR','').trim();
  // console.log('currency',currency);
  currency = addCommas(currency);
  amount_to_diskon(elm);
  return currency;
}

$("#flag_deviation").change(function () {
  // $("#txt-late-charge-discount-2").val('0');
  // $('#txt-interest-discount-2').val('0');
  // $('#txt-late-charge-discount').val('0');
  // $('#txt-interest-discount').val('0');
  $("#txt-late-charge-discount-2").val("");
  $("#txt-interest-discount-2").val("");
  $("#txt-late-charge-discount").val("");
  $("#txt-interest-discount").val("");

  $("#reschedule_rate").val("");
  $("#txt_hirarki").val("");

  $("#txt-payment-pokok").val("");
  $("#txt-payment-pokok-val").val("");
  let val = $(this).val();
  if (val == "1") {
    $("#flag_kondisi_khusus").attr("disabled", true);
    $("#desc_kondisi_khusus").attr("disabled", true);
    $("#txt_deviation_reason").attr("disabled", false).val("").change();

    $("#lbl_nama_parameter").html("Parameter Name : -");
    $("#tbl_filter_parameter th").css("color", "gray");
    $("#tbl_filter_parameter td").css("color", "gray");
    $("#tbl_filter_parameter td").html("-");

    max_special_condition_disc_rate = 100;
    max_block_status_disc_rate = 100;
    max_principal_disc_rate = 100;
    max_normal_disc_rate = 100;
    max_interest_disc_rate = 100;

    limit_max_special_condition_disc_rate = 100;
    limit_max_block_status_disc_rate = 100;
    limit_max_principal_disc_rate = 100;
    limit_max_normal_disc_rate = 100;
    limit_max_interest_disc_rate = 100;
  } else {
    $("#flag_kondisi_khusus").attr("disabled", false);
    $("#flag_kondisi_khusus").change();
    $("#txt_deviation_reason").attr("disabled", true).val("").change();
    $("#tbl_filter_parameter th").css("color", "black");
    $("#tbl_filter_parameter td").css("color", "black");
    search_param("NEW");
  }
});

function get_data_perhitungan_kotak_merah() {
  $("#loading_kotak_merah").show();
  $("#kotak_merah [type=text]").val("0");
  $.ajax({
    type: "GET",
    url:
      URL_DATA +
      "workflow_pengajuan/workflow_pengajuan_reschedule/get_data_kotak_merah",
    data: {
      due_date: due_date,
      ptp_date: $("#txt_ptp_date_for_rsc").val(),
      product_id: product_id,
      agreement_no: $("#txt_card_number").val(),
      tipe_pengajuan: "RSCH",
    },
    dataType: "json",
    //timeout: 3000,
    success: function (msg) {
      // console.log(msg);
      if (msg.success) {
        $("#txt-principle-balance").val(
          addPeriodx(msg.data.principal_amount_balance.toString())
        );
        $("#txt-principle-balance-val").val(msg.data.principal_amount_balance);

        $("#txt-total-due-balance-installment").val(
          addPeriodx(msg.data.total_due_balance_installemnt.toString())
        );
        $("#txt-total-due-balance-installment-val").val(
          msg.data.total_due_balance_installemnt
        );

        $("#txt-due-interest").val(
          addPeriodx(msg.data.interest_due.toString())
        );
        $("#txt-due-interest-val").val(msg.data.interest_due);

        $("#txt-late-charge").val(addPeriodx(msg.data.late_charge.toString()));
        $("#txt-late-charge-val").val(msg.data.late_charge);

        $("#txt-penalty").val(addPeriodx(msg.data.penalty.toString()));
        $("#txt-penalty-val").val(msg.data.penalty);

        $("#txt-stamp-duty").val(addPeriodx(msg.data.stamp_duty.toString()));
        $("#txt-stamp-duty-val").val(msg.data.stamp_duty);

        var total = parseFloat(msg.data.total);

        $("#txt-total").val(addPeriodx(total.toString()));
        $("#txt-total-val").val(total);

        $("#txt-payment-bunga").val(
          addPeriodx(msg.data.interest_due.toString())
        );
        $("#txt-payment-bunga-val").val(msg.data.interest_due);

        $("#txt-payment-denda").val(
          addPeriodx(msg.data.late_charge.toString())
        );
        $("#txt-payment-denda-val").val(msg.data.late_charge);

        $("#span-principle-balance").html(
          addPeriodx(msg.data.principal_amount_balance.toString())
        );
        $("#span-total-due-balance-installment").html(
          addPeriodx(msg.data.total_due_balance_installemnt.toString())
        );
        $("#span-due-interest").html(
          addPeriodx(msg.data.interest_due.toString())
        );
        $("#span-late-charge").html(
          addPeriodx(msg.data.late_charge.toString())
        );
        $("#span-penalty").html(addPeriodx(msg.data.penalty.toString()));
        $("#span-stamp-duty").html(addPeriodx(msg.data.stamp_duty.toString()));

        $("#span-total").html(addPeriodx(total.toString()));

        $("#span-payment-bunga").html(
          addPeriodx(msg.data.interest_due.toString())
        );
        $("#span-payment-denda").html(
          addPeriodx(msg.data.late_charge.toString())
        );
      } else {
        showWarning(msg.message);
      }
      $("#loading_kotak_merah").hide();
    },
    error: function (err) {
      // $('#kotak_merah [type=text]').val('0');
    },
  });
}

function diskon_to_amount(elm, toElm) {
  console.log("toElm", toElm);
  let val_diskon = elm.value;
  val_diskon = val_diskon.toString();
  if (toElm == "txt-principle-balance-discount") {
    let principal_balance = $("#txt-principle-balance").val();
    principal_balance = principal_balance.replaceAll(",", "");

    if (isNaN(principal_balance)) principal_balance = 0;
    let counting_diskon = 0;

    principal_balance = parseFloat(principal_balance);
    counting_diskon = (val_diskon * principal_balance) / 100;
    counting_diskon = counting_diskon.toString();
    // console.log('counting_diskon',counting_diskon);
    // console.log('counting_diskon addPeriodx',addPeriodx(counting_diskon.toString()));

    setTimeout(() => {
      $("#" + toElm.trim()).val(addPeriod(counting_diskon.toString(), null));
    }, 100);
  } else if (toElm == "txt-interest-discount") {
    let due_interest = $("#txt-due-interest").val();
    due_interest = due_interest.replaceAll(",", "");

    if (isNaN(due_interest)) due_interest = 0;
    let counting_diskon = 0;

    due_interest = parseFloat(due_interest);
    counting_diskon = (val_diskon * due_interest) / 100;
    counting_diskon = counting_diskon.toString();

    setTimeout(() => {
      $("#" + toElm.trim()).val(addPeriod(counting_diskon.toString(), null));
    }, 100);
  } else if (toElm == "txt-late-charge-discount") {
    let late_charge = $("#txt-late-charge").val();
    late_charge = late_charge.replaceAll(",", "");

    if (isNaN(late_charge)) late_charge = 0;
    let counting_diskon = 0;

    late_charge = parseFloat(late_charge);
    counting_diskon = (val_diskon * late_charge) / 100;
    counting_diskon = counting_diskon.toString();
    console.log("counting_diskon", counting_diskon);
    console.log(
      "counting_diskon addPeriodx",
      addPeriodx(counting_diskon.toString())
    );

    setTimeout(() => {
      $("#" + toElm.trim()).val(addPeriod(counting_diskon.toString(), null));
    }, 100);
  } else if (toElm == "txt-penalty-discount") {
    let penalty = $("#txt-penalty").val();
    penalty = penalty.replaceAll(",", "");

    if (isNaN(penalty)) penalty = 0;
    let counting_diskon = 0;

    penalty = parseFloat(penalty);
    counting_diskon = (val_diskon * penalty) / 100;
    counting_diskon = counting_diskon.toString();
    // console.log('counting_diskon',counting_diskon);
    // console.log('counting_diskon addPeriodx',addPeriodx(counting_diskon.toString()));

    setTimeout(() => {
      $("#" + toElm.trim()).val(addPeriod(counting_diskon.toString(), null));
    }, 100);
  }

  setTimeout(() => {
    countSisaPokokPinjaman();
    countNewInstallmanetAmount();
  }, 200);
}

function geTotalBayar() {
  let totalkotakmerah = $("#txt-total").val();
  totalkotakmerah = parseFloat(totalkotakmerah.replaceAll(",", ""));

  let principalBalance = $("#txt-late-charge-discount").val();
  if (principalBalance == "") principalBalance = "0";
  principalBalance = parseFloat(principalBalance.replaceAll(",", ""));

  let interest = $("#txt-interest-discount").val();
  if (interest == "") interest = "0";
  interest = parseFloat(interest.replaceAll(",", ""));

  let penalty = $("#txt-penalty-discount").val();
  if (penalty == "") penalty = "0";
  penalty = parseFloat(penalty.replaceAll(",", ""));

  // console.log('interest',interest);
  // console.log('principalBalance',principalBalance);
  // console.log('totalkotakmerah',totalkotakmerah);
  let alldiskon = interest + principalBalance + penalty;
  let totaldiskon = totalkotakmerah - alldiskon;
  $("#txt-payment-pokok-val").val(totaldiskon.toString());
  // console.log('totalkotakmerah',totalkotakmerah);
  // console.log('totaldiskon',totaldiskon);
  totalkotakmerah = addPeriodx(totaldiskon.toString());

  $("#txt-total-bayar-diskon").html("<b>" + totalkotakmerah + "</b>");
}

function amount_to_diskon(elm) {
  if (elm == null) return false;

  let elmTo;
  let amount = parseFloat(elm.value.replaceAll(",", ""));
  let countingDiskon;
  if (elm.id == "txt-late-charge-discount") {
    let late_charge = parseFloat(
      $("#txt-late-charge").val().replaceAll(",", "")
    );
    elmTo = "txt-late-charge-discount-2";
    if (amount == "") {
      $("#" + elmTo).val("");
      return false;
    }

    if (late_charge == 0) {
      $("#" + elmTo).val("0");
    } else {
      countingDiskon = (amount / late_charge) * 100;

      if (isNaN(countingDiskon)) countingDiskon = "";
      countingDiskon = countingDiskon.toString();

      $("#" + elmTo).val(countingDiskon);
    }

    validationparam($("#" + elmTo)[0], "late-charge", elm.id);
  } else if (elm.id == "txt-interest-discount") {
    let interest = parseFloat($("#txt-due-interest").val().replaceAll(",", ""));
    elmTo = "txt-interest-discount-2";

    if (amount == "") {
      $("#" + elmTo).val("");
      return false;
    }

    if (interest == 0) {
      $("#" + elmTo).val("0");
    } else {
      countingDiskon = (amount / interest) * 100;

      if (isNaN(countingDiskon)) countingDiskon = "";
      countingDiskon = countingDiskon.toString();

      $("#" + elmTo).val(countingDiskon);
    }

    validationparam($("#" + elmTo)[0], "interest", elm.id);
  } else if (elm.id == "txt-principle-balance-discount") {
    let balance = parseFloat(
      $("#txt-principle-balance").val().replaceAll(",", "")
    );

    elmTo = "txt-principle-balance-discount-2";

    if (amount == "") {
      $("#" + elmTo).val("");
      return false;
    }

    if (balance == 0) {
      $("#" + elmTo).val("0");
    } else {
      countingDiskon = (amount / balance) * 100;

      if (isNaN(countingDiskon)) countingDiskon = "";
      countingDiskon = countingDiskon.toString();

      $("#" + elmTo).val(countingDiskon);
    }

    validationparam($("#" + elmTo)[0], "principle-balance", elm.id);
  } else if (elm.id == "txt-penalty-discount") {
    let penalty = parseFloat($("#txt-penalty").val().replaceAll(",", ""));

    elmTo = "txt-penalty-discount-2";

    if (amount == "") {
      $("#" + elmTo).val("");
      return false;
    }

    if (penalty == 0) {
      $("#" + elmTo).val("0");
    } else {
      countingDiskon = (amount / penalty) * 100;

      if (isNaN(countingDiskon)) countingDiskon = "";
      countingDiskon = countingDiskon.toString();

      $("#" + elmTo).val(countingDiskon);
    }

    validationparam($("#" + elmTo)[0], "penalty", elm.id);
  }
}

function countSisaPokokPinjaman() {
  let total = parseFloat($("#txt-total").val().replaceAll(",", ""));
  let payBunga = parseFloat($("#txt-payment-bunga").val().replaceAll(",", ""));
  let payDenda = parseFloat($("#txt-payment-denda").val().replaceAll(",", ""));
  let distPrincipleBalance = parseFloat(
    $("#txt-principle-balance-discount").val().replaceAll(",", "")
  );
  let distLateCharge = parseFloat(
    $("#txt-late-charge-discount").val().replaceAll(",", "")
  );
  let distInterest = parseFloat(
    $("#txt-interest-discount").val().replaceAll(",", "")
  );
  let payPokok = parseFloat(
    $("#txt-payment-pokok-val").val().replaceAll(",", "")
  );

  if (isNaN(total)) total = 0;
  if (isNaN(payBunga)) payBunga = 0;
  if (isNaN(payDenda)) payDenda = 0;
  if (isNaN(distPrincipleBalance)) distPrincipleBalance = 0;
  if (isNaN(distLateCharge)) distLateCharge = 0;
  if (isNaN(distInterest)) distInterest = 0;
  if (isNaN(payPokok)) payPokok = 0;

  let sisa =
    total -
    payBunga -
    payDenda -
    distLateCharge -
    distInterest -
    distPrincipleBalance -
    payPokok;
  $("#txt-sisa-pokok-pinjaman-baru").val(addPeriodx(sisa.toString()));
  $("#txt-sisa-pokok-pinjaman-baru-val").val(sisa);
}

function countNewInstallmanetAmount() {
  let sisapokokpinjamanbaru = parseFloat(
    $("#txt-sisa-pokok-pinjaman-baru-val").val()
  );
  let txt_interest_val = parseFloat($("#txt_interest_val").val());
  let txt_tenor_val = parseFloat($("#txt_tenor_val").val());

  if (isNaN(sisapokokpinjamanbaru)) sisapokokpinjamanbaru = 0;
  if (isNaN(txt_interest_val)) txt_interest_val = 0;
  if (isNaN(txt_tenor_val)) txt_tenor_val = 0;

  let cicilan = get_new_installment_amount();

  // cicilan = cicilan.toFixed(2);
  // $("#txt_new_installment_amount").html("<b>"+addPeriodx(cicilan.toString())+"</b>");
  // $("#txt_new_installment_amount_val").val(cicilan);
}

function save($flag) {
  // $flag = 1 = save and finish
  // $flag = 0 = save and draft

  let passes = true;

  if ($("#flag_kondisi_khusus").val() == "1") {
    if ($("#desc_kondisi_khusus").val() == "") {
      showWarning("Deskripsi Kondisi Khusus kosong!");
      return false;
    }
  }

  if ($("#flag_deviation").val() == "1") {
    if ($("#txt_deviation_reason").val() == "") {
      showWarning("Deviation Reason kosong!");
      return false;
    }
  }

  if ($("#txt_ptp_date_for_rsc").val() == "") {
    showWarning("PTP Date kosong!");
    return false;
  }

  if (
    $("#txt-amount-ptp-dp").val() == "" ||
    parseInt($("#txt-amount-ptp-dp").val()) <= 0
  ) {
    showWarning("amount PTP kosong!");
    return false;
  }

  if ($("#txt_tenor_val").val() == "") {
    showWarning("Tenor kosong!");
    return false;
  }

  if ($("#txt_interest_val").val() == "") {
    showWarning("Interest kosong!");
    return false;
  }

  if (
    $('input[name="btnradiotipesukubunga"]:checked').val() == "" ||
    typeof $('input[name="btnradiotipesukubunga"]:checked').val() == "undefined"
  ) {
    showWarning("pilih suku bunga");
    return false;
  }

  if (product_type == "CIMB-PL") {
    if ($("#txt-moratorium-val").val() == "") {
      showWarning("moratorium kosong!");
      return false;
    }
  }

  if (!checkValidate()) {
    return false;
  }
  let narasi = "are you sure?";
  swal({
    title: "Request Program",
    text: narasi,
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      if ($("#txt-principle-balance-discount").val() == "") {
        $("#txt-principle-balance-discount").val("0");
      }
      if ($("#txt-principle-balance-discount-2").val() == "") {
        $("#txt-principle-balance-discount-2").val("0");
      }

      if ($("#txt-late-charge-discount").val() == "") {
        $("#txt-late-charge-discount").val("0");
      }
      if ($("#txt-late-charge-discount-2").val() == "") {
        $("#txt-late-charge-discount-2").val("0");
      }

      if ($("#txt-interest-discount").val() == "") {
        $("#txt-interest-discount").val("0");
      }
      if ($("#txt-interest-discount-2").val() == "") {
        $("#txt-interest-discount-2").val("0");
      }

      $("#flag_kondisi_khusus").attr("disabled", false);
      $("#desc_kondisi_khusus").attr("disabled", false);

      $("#btn-save-finish ,#btn-save-draft").attr("disabled", true);
      $("#loading_save").show();
      $.ajax({
        url:
          URL_DATA +
          "workflow_pengajuan/workflow_pengajuan_reschedule/save_pengajuan_reschedule?status=" +
          $flag,
        type: "post",
        data: $("#formDiscountRequest").serialize(),
        success: function (msg) {
          if (msg.success) {
            if (msg.newCsrfToken) {
              $("#token_csrf").val(msg.newCsrfToken);
            }
            showInfo(msg.message);
            getData();
            $("#id").val(msg.id);
            $("#txt_ptp_date").val($("#txt_ptp_date_for_rsc").val());
            $("#txt_ptp_amount").val($("#txt-amount-ptp-dp").val());
            $("#txt_ptp_amount").attr("disabled", true);
            $("#txt_id_pengajuan").val(msg.id);
            upload_doc("FINISH");
            setTimeout(() => {
              $("#loading_save").hide();
              // $('#btn-save-finish ,#btn-save-draft').attr('disabled',false);
            }, 300);

            try {
              // update_join_program('RESTRUCTURE');//fungsi ada di panin_deskcoll agent_main.js
            } catch (error) {
              console.log(error);
            }
          } else {
            showWarning(msg.message);
            $("#btn-save-finish ,#btn-save-draft").attr("disabled", false);
            setTimeout(() => {
              $("#loading_save").hide();
              // $('#btn-save-finish ,#btn-save-draft').attr('disabled',false);
            }, 300);
          }
        },
        error: function (err) {
          showWarning("Gagal tersimpan, mohon ulangi kembali");

          console.log(err);

          setTimeout(() => {
            $("#loading_save").hide();
            $("#btn-save-finish ,#btn-save-draft").attr("disabled", false);
          }, 300);
        },
        dataType: "json",
      });
    } else {
    }
  });
}

function jadwal_pebayaran() {
  let tipe = $('input[name="btnradiotipesukubunga"]:checked').val();
  tipe_suku_bunga = tipe;
  let tenor = $("#txt_tenor_val").val();
  let hutang_pokok = $("#txt-sisa-pokok-pinjaman-baru-val").val();
  let interest = $("#txt_interest_val").val();
  let ptp = $("#txt_ptp_date_for_rsc").val();
  let dp = $("#txt-amount-ptp-dp").val();
  let moratorium = $("#txt-moratorium-val").val();

  if (tenor == "") {
    showWarning("Tenor belum di input");
    return false;
  } else if (hutang_pokok == "") {
    showWarning("sisa pokok pinjaman kosong");
    return false;
  } else if (interest == "") {
    showWarning("interest kosong");
    return false;
  } else if (ptp == "") {
    showWarning("tanggal PTP kosong");
    return false;
  } else if (dp == "") {
    showWarning(" PTP amount kosong");
    return false;
  }

  if (product_type == "CIMB-PL") {
    if ($("#txt-moratorium-val").val() == "") {
      showWarning("moratorium kosong!");
      return false;
    }
  }

  var buttons = {
    button: {
      label: "Close",
      className: "btn-sm",
    },
  };
  showCommonDialog3(
    1000,
    700,
    "JADWAL PEMBAYARAN - " + tipe,
    URL_DATA +
      "workflow_pengajuan/workflow_pengajuan_reschedule/payment_plan?tipe=" +
      tipe +
      "&tenor=" +
      tenor +
      "&hutang=" +
      hutang_pokok +
      "&bunga=" +
      interest +
      "&moratorium=" +
      moratorium,
    buttons
  );
}

function get_new_installment_amount(
  sisapokokpinjamanbaru,
  txt_tenor_val,
  txt_interest_val
) {
  let tipe = $('input[name="btnradiotipesukubunga"]:checked').val();
  tipe_suku_bunga = tipe;
  let tenor = $("#txt_tenor_val").val();
  let hutang_pokok = $("#txt-sisa-pokok-pinjaman-baru-val").val();
  let interest = $("#txt_interest_val").val();
  let ptp = $("#txt_ptp_date_for_rsc").val();
  let dp = $("#txt-amount-ptp-dp").val();
  let moratorium = $("#txt-moratorium-val").val();

  if (tenor == "") {
    return false;
  } else if (hutang_pokok == "") {
    return false;
  } else if (interest == "") {
    return false;
  } else if (ptp == "") {
    return false;
  } else if (dp == "") {
    return false;
  }

  if (product_type == "CIMB-PL") {
    if ($("#txt-moratorium-val").val() == "") {
      return false;
    }
  }

  $.ajax({
    type: "GET",
    async: true,
    dataType: "json",
    url:
      URL_DATA +
      "workflow_pengajuan/workflow_pengajuan_reschedule/get_new_installment_amount?tipe=" +
      tipe +
      "&tenor=" +
      tenor +
      "&hutang=" +
      hutang_pokok +
      "&bunga=" +
      interest +
      "&moratorium=" +
      moratorium,
    success: function (msg) {
      console.log(msg);

      let cicilan = msg.new_installmet_amount;
      cicilan = cicilan.toFixed(2);
      $("#txt_new_installment_amount").val(addPeriodx(cicilan.toString()));
      $("#txt_new_installment_amount_val").val(cicilan);
    },
  });
}

function addCommas(nStr) {
  nStr += "";
  var x = nStr.split(".");
  var x1 = x[0];
  var x2 = x.length > 1 ? "." + x[1] : "";
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
    x1 = x1.replace(rgx, "$1" + "," + "$2");
  }
  return x1 + x2;
}

function get_interest_api(tenor) {
  $("#txt_interest_val").val("");
  $("#txt_interest_id").val("");
  $("#alert_interest_val").html("");
  $("#html_txt_interest_val").html("");
  var dpd = $("#dpd").val();

  $.ajax({
    type: "GET",
    async: true,
    dataType: "json",
    url: URL_DATA + "workflow_pengajuan/get_interest_api",
    data: { tenor: tenor, dpd: dpd },
    success: function (msg) {
      if (msg.success) {
        $("#alert_interest_val").html(msg.message);
        var html_interest = "";
        $("#html_txt_interest_val").html("");

        html_interest = '-<i style="font-size:9px">pilih salah satu</i>-<br>';
        $("#html_txt_interest_val").append(html_interest);

        $.each(msg.interest_rate, function (i, val) {
          html_interest =
            '<input type="radio" data-id = "' +
            val.id +
            '" id="interest_rate_radio' +
            i +
            '" name="interest_rate_radio" value="' +
            val.interest_rate +
            '" onChange="input_interest_rate(' +
            val.interest_rate +
            " , " +
            val.id +
            ' )">';
          html_interest +=
            '<label for="interest_rate_radio' +
            i +
            '">&nbsp;&nbsp;' +
            val.interest_rate +
            "%</label><br>";

          $("#html_txt_interest_val").append(html_interest);
        });

        // $("#html_txt_interest_val").html(html_interest);
      } else {
        $("#alert_interest_val").html(msg.message);
      }
    },
  });
}

$("input[type=radio][name=interest_rate_radio]").change(function () {
  // let interest = $("[name=interest_rate_radio]:checked").val();
  console.log("adsdadsa", interest);
});
$("input[type=radio][name=phone-owner]").change(function () {
  if ($(this).val() == "other_phone") {
    $("#txt_other_phone").attr("disabled", false);
  } else {
    $("#txt_other_phone").attr("disabled", true).val("");
  }
});

function input_interest_rate(interest, id) {
  console.log(interest);

  $("#txt_interest_val").val(interest);
  numberOnly($("#txt_interest_val")[0]);

  $("#txt_interest_id").val(id);
}

$('input[name="btnradiotipesukubunga"]').change(function () {
  let tipe = $('input[name="btnradiotipesukubunga"]:checked').val();
  tipe_suku_bunga = tipe;
  get_new_installment_amount();
});

$("#btn-to-call").click(function () {
  if ($(this).hasClass("btn-success")) {
    let number = $("input[name=phone-owner]:checked").val();

    if (number != "other_phone") {
      if (number == "") {
        showWarning("Nomor Telpn kosong");
      } else {
        outbound();
        setTimeout(() => {
          $("#btn-to-call")
            .removeClass("btn-success")
            .addClass("btn-danger")
            .html("disconnect")
            .attr("disabled", true);
          $("#btn-to-call")
            .children()
            .removeClass("icon-phone")
            .addClass("icon-ban-circle");

          originateCall(number, $("#txt_id_pengajuan").val());
        }, 500);
      }
    } else {
      outbound();
      setTimeout(() => {
        $("#btn-to-call")
          .removeClass("btn-success")
          .addClass("btn-danger")
          .html("disconnect")
          .attr("disabled", true);
        $("#btn-to-call")
          .children()
          .removeClass("icon-phone")
          .addClass("icon-ban-circle");

        originateCall(
          $("#txt_other_phone").val(),
          $("#txt_id_pengajuan").val()
        );
      }, 500);
    }
  } else {
    swal({
      title: "WARNING!",
      text: "Apakah ingin mengakhiri panggilan ?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        disconnectCall();
        setTimeout(() => {
          $("#btn-to-call")
            .removeClass("btn-danger")
            .addClass("btn-success")
            .html("call");
          $("#btn-to-call")
            .children()
            .removeClass("icon-ban-circle")
            .addClass("icon-phone");
        }, 300);
      } else {
      }
    });
  }
});

function save_call_history(status) {
  if (typeof $("input[name=phone-owner]:checked").val() == "undefined") {
    showWarning("Nomor Telfon Belum dipilih!");
    return false;
  }
  let phone = "";
  let tipe_contact = "";
  if ($("input[name=phone-owner]:checked").val() == "other_phone") {
    phone = $("#txt_other_phone").val();
    tipe_contact = "other";
  } else {
    phone = $("input[name=phone-owner]:checked").val();
    tipe_contact = $("input[name=phone-owner]:checked").attr("phone_type");
  }

  let agreement_no = $("#txt_card_number").val();
  let call_status = $("#contact_result").val();
  let call_result = $("#result_call").val();
  let remark = $("#call-remark").val();
  let id_pengajuan = $("#txt_id_pengajuan").val();
  let submit_id = $("#submit_id").val();
  if (call_status == "") {
    showWarning("Contact Result Belum dipilih!");
    return false;
  }
  if (call_result == "") {
    showWarning("Result Call Belum dipilih!");
    return false;
  }

  $("#loading_call").show();
  $("#contact_result").attr("disabled", true);
  $("#result_call").attr("disabled", true);
  $("#call-remark").attr("disabled", true);
  $(".btn-form-call").attr("disabled", true);
  var caller_id = null;
  try {
    caller_id = TELEPHONY_CALLER_ID;
  } catch (error) {
    caller_id = null;
  }
  $.ajax({
    type: "POST",
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "workflow_pengajuan/workflow_pengajuan_reschedule/save_call_history",
    data: {
      status: status,
      tipe_pengajuan: "RSCH",
      phone: phone,
      tipe_contact: tipe_contact,
      agreement_no: agreement_no,
      call_status: call_status,
      call_result: call_result,
      remark: remark,
      id_pengajuan: id_pengajuan,
      submit_id: submit_id,
      caller_id: caller_id,
      csrf_security: $("#token_csrf").val(),
    },
    dataType: "json",
    timeout: 10000, //10 detik
    success: function (msg) {
      if (msg.success) {
        setTimeout(() => {
          $("#contact_result").val("");
          $("#result_call").val("");
          $("#call-remark").val("");

          // if(status!='FINISH'){
          $("#contact_result").attr("disabled", false);
          $("#result_call").attr("disabled", false);
          $("#call-remark").attr("disabled", false);
          $(".btn-form-call").attr("disabled", false);
          // }
          $("#loading_call").hide();
          $("#token_csrf").val(msg.newCsrfToken);
          getData();
        }, 1000);
        showInfo(msg.message);
      } else {
        setTimeout(() => {
          $("#contact_result").attr("disabled", false);
          $("#result_call").attr("disabled", false);
          $("#call-remark").attr("disabled", false);
          $(".btn-form-call").attr("disabled", false);
          $("#loading_call").hide();
        }, 1000);
        showWarning(msg.message);
        getData();
      }
    },
    error: function (e) {
      setTimeout(() => {
        showWarning("Penyimpanan Gagal!");
        $("#contact_result").attr("disabled", false);
        $("#result_call").attr("disabled", false);
        $("#call-remark").attr("disabled", false);
        $(".btn-form-call").attr("disabled", false);
        $("#loading_call").hide();
        getData();
      }, 1000);
    },
  });
}

function upload_doc(status) {
  let isUpload = true;
  let submit_id = $("#submit_id").val();
  last_action = status;

  if (!checkValidate()) {
    return false;
  }

  let narasi = "UPLOAD";
  if (status == "FINISH") {
    // Handle FINISH status if needed
  } else if (status == "DRAFT") {
    // Handle DRAFT status if needed
  }

  if ($("#upload-data-remark").val() != "") {
    let id = $("#txt_id_pengajuan").val();
    let remark = $("#upload-data-remark").val();

    $.ajax({
      type: "POST",
      url:
        GLOBAL_MAIN_VARS["SITE_URL"] +
        "workflow_pengajuan/workflow_pengajuan_reschedule/save_remark_verif",
      data: {
        csrf_security: $("#token_csrf").val(),
        id_pengajuan: id,
        remark: remark,
        submit_id: submit_id,
      },
      dataType: "json",
      success: function (msg) {
        console.log("save_remark_verif", msg);
      },
      error: function (err) {
        console.log("save_remark_verif", err);
      },
    });
  }

  async function uploadFile(i) {
    let id_upload = i.id;
    let label = i.dataset.label;
    let isUpload = true;

    try {
      if (
        $("#" + id_upload).attr("data-upload") == "false" ||
        $("#" + id_upload).attr("data-upload") == false
      ) {
        isUpload = false;
      } else if (
        typeof document.querySelector("#" + id_upload).files[0] == "undefined"
      ) {
        showWarning(label + " is empty!");
        isUpload = false;
      }
    } catch (error) {
      console.log("error", error);
      isUpload = false;
      showWarning(label + " is empty!");
    }

    if (isUpload) {
      $("#" + id_upload).attr("disabled", true);
      $("#progress-bar-" + id_upload).show();

      var formdata = new FormData();
      formdata.append(
        id_upload,
        document.querySelector("#" + id_upload).files[0]
      );
      formdata.append("id_pengajuan", $("#txt_id_pengajuan").val());
      formdata.append("agreement_no", $("#txt_card_number").val());
      formdata.append("id_upload", id_upload);
      formdata.append("tipe_pengajuan", "RSCH");
      formdata.append("status", status);
      formdata.append("submit_id", submit_id);
      $("#progress-bar-" + id_upload).css("width", "0%");
      $("#div-progress-bar-" + id_upload).show();
      $("#btn-" + id_upload).hide();
      $("#alert-" + id_upload).html("");

      try {
        const msg = await getCsrfToken();
        if (msg.success && msg.newCsrfToken) {
          formdata.append("csrf_security", msg.newCsrfToken);

          await uploadDocument(formdata, id_upload);
        }
      } catch (error) {
        console.log("get_csrf_token error", error);
      }
    }
  }

  function getCsrfToken() {
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "GET",
        url:
          GLOBAL_MAIN_VARS["SITE_URL"] +
          "workflow_pengajuan/workflow_pengajuan_reschedule/get_csrf_token",
        dataType: "json",
        success: function (msg) {
          resolve(msg);
        },
        error: function (err) {
          reject(err);
        },
      });
    });
  }

  function uploadDocument(formdata, id_upload) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url:
          GLOBAL_MAIN_VARS["SITE_URL"] +
          "workflow_pengajuan/workflow_pengajuan_reschedule/upload_document",
        data: formdata,
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        enctype: "multipart/form-data",
        xhr: function () {
          var xhr = new window.XMLHttpRequest();
          xhr.upload.addEventListener(
            "progress",
            function (evt) {
              if (evt.lengthComputable) {
                var percentComplete = evt.loaded / evt.total;
                var progress_persen = Math.round(percentComplete * 100) + "%";
                $("#progress-bar-" + id_upload).css("width", progress_persen);
                $("#txt-progress-bar-" + id_upload).html(progress_persen);
              }
            },
            false
          );
          return xhr;
        },
        success: function (response) {
          let jumlah_uploaded = parseInt(
            $("#jumlah-uploaded-" + id_upload).html()
          );
          jumlah_uploaded += 1;
          $("#jumlah-uploaded-" + id_upload).html(jumlah_uploaded);

          setTimeout(() => {
            $("#" + id_upload).attr("disabled", false);
            $("#" + id_upload).val("");
            $("#div-progress-bar-" + id_upload).hide();
            $("#btn-" + id_upload).show();
            $("#alert-" + id_upload).html("Complete!");
            $("#alert-" + id_upload).css("color", "green");
            $("#btn-delete-" + id_upload).hide();
            resolve();
          }, 1000);
        },
        error: function (err) {
          setTimeout(() => {
            $("#" + id_upload).attr("disabled", false);
            $("#div-progress-bar-" + id_upload).hide();
            $("#btn-" + id_upload).show();
            showWarning("Upload gagal, coba ulangi kembali");
            $("#alert-" + id_upload).html("Failed!");
            $("#alert-" + id_upload).css("color", "red");
            $("#reupload-" + id_upload).show();
            reject(err);
          }, 1000);
        },
        dataType: "json",
      });
    });
  }

  async function processUploads() {
    let uploadPromises = [];
    for (const i of $(".doUpload")) {
      uploadPromises.push(uploadFile(i));
      await new Promise((resolve) => setTimeout(resolve, 3000)); // Wait for 3000ms before next iteration
    }
    await Promise.all(uploadPromises);
  }

  processUploads().then(() => {
    setTimeout(() => {
      save_upload_document(status);
    }, 2000); // Wait for 3000ms before running save_upload_document
  });
}
function save_upload_document(status) {
  $.ajax({
    type: "GET",
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "workflow_pengajuan/workflow_pengajuan_reschedule/get_csrf_token",
    dataType: "json",
    success: function (msg) {
      let submit_id = $("#submit_id").val();

      var formdata = new FormData();

      formdata.append("id_pengajuan", $("#txt_id_pengajuan").val());
      formdata.append("agreement_no", $("#txt_card_number").val());
      formdata.append("csrf_security", msg.newCsrfToken);
      formdata.append("tipe_pengajuan", "RSCH");
      formdata.append("status", status);
      formdata.append("submit_id", submit_id);
      $.ajax({
        url:
          GLOBAL_MAIN_VARS["SITE_URL"] +
          "workflow_pengajuan/workflow_pengajuan_reschedule/save_document_verification?tipe=restructure",
        data: formdata,
        async: true,
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        enctype: "multipart/form-data",
        success: function (response) {
          if (response.newCsrfToken) {
            $("#token_csrf").val(response.newCsrfToken);
          }
        },
        err: function (err) {},
      });
    },
    error: function (err) {},
  });
}

function numberOnly_all(elm) {
  elm.value = elm.value.replace(/[^0-9.,]/g, "");
}
setInterval(() => {
  if (TELEPHONY_CURRENT_STATUS == "RINGING") {
    $("#btn-to-call").attr("disabled", true);
    $("#id_status_telephony").html("ringing...").show();
  } else if (TELEPHONY_CURRENT_STATUS == "ACW") {
    $("#btn-to-call")
      .removeClass("btn-danger")
      .addClass("btn-success")
      .html("call")
      .attr("disabled", false);
    $("#btn-to-call")
      .children()
      .removeClass("icon-ban-circle")
      .addClass("icon-phone");
  } else {
    $("#btn-to-call").attr("disabled", false);
    $("#id_status_telephony").html(TELEPHONY_CURRENT_STATUS).hide();
  }
}, 1000);
