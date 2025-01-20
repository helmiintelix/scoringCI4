function isActive(elm) {
  if ($(elm)[0].checked) {
    $("#opt-active-flag").val("1").change();
    //$("label[for='flexSwitchCheckChecked']").text('Active');
  } else {
    $("#opt-active-flag").val("0").change();
    //$("label[for='flexSwitchCheckChecked']").text('Not Active');
  }
}
$("#opt-agency-province").change(function () {
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/branch/getCity",
    data: {
      provinsi: $("#opt-agency-province").val()
    },
    dataType: "json",
    type: "get",
    success: function (data) {
      $("#opt-agency-city").html(data.data);
      $("#opt-agency-district , #opt-agency-sub-district").html("<option>[SELECT DATA]</option>");
    },
  });
});

$("#opt-agency-city").change(function () {
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/branch/getKecamatan",
    data: {
      provinsi: $("#opt-agency-province").val(),
      kota: $("#opt-agency-city").val()
    },
    dataType: "json",
    type: "get",
    success: function (data) {
      $("#opt-agency-district").html(data.data);
      $("#opt-agency-sub-district").html("<option>[SELECT DATA]</option>");
    },
  });
});

$("#opt-agency-district").change(function () {
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/branch/getKelurahan",
    data: {
      provinsi: $("#opt-agency-province").val(),
      kota: $("#opt-agency-city").val(),
      kecamatan: $("#opt-agency-district").val()
    },
    dataType: "json",
    type: "get",
    success: function (data) {
      $("#opt-agency-sub-district").html(data.data);
    },
  });
});

$(".input-number").on("input", function (eve) {
  eve.target.value = eve.target.value.replace(/[^0-9.]/g, "");
});

function initializeSingleDatePicker(elementId) {
  $(elementId).daterangepicker({
    singleDatePicker: true,
    autoUpdateInput: false,
    locale: {
      cancelLabel: "Close",
    },
    autoApply: true,
  });

  $(elementId).on("apply.daterangepicker", function (ev, picker) {
    $(this).val(picker.startDate.format("YYYY-MM-DD"));
  });
}

$(function () {
  initializeSingleDatePicker("#notaris_datepicker");
  initializeSingleDatePicker("#datepicker");
  initializeSingleDatePicker("#datepicker2");
  initializeSingleDatePicker("#proposal_datepicker");
  initializeSingleDatePicker("#assessment_datepicker");
  initializeSingleDatePicker("#offering_datepicker");
});
