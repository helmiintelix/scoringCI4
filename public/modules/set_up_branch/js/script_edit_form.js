function isActive(elm) {
  if ($(elm)[0].checked) {
    $("#opt-active-flag").val("1").change();
    //$("label[for='flexSwitchCheckChecked']").text('Active');
  } else {
    $("#opt-active-flag").val("0").change();
    //$("label[for='flexSwitchCheckChecked']").text('Not Active');
  }
}
$("#opt-branch-province").change(function () {
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/branch/getCity",
    data: {
      provinsi: $("#opt-branch-province").val()
    },
    dataType: "json",
    type: "get",
    success: function (data) {
      $("#opt-branch-city").html(data.data);
      $("#opt-branch-district , #opt-branch-sub-district").html("<option>[SELECT DATA]</option>");
    },
  });
});

$("#opt-branch-city").change(function () {
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/branch/getKecamatan",
    data: {
      provinsi: $("#opt-branch-province").val(),
      kota: $("#opt-branch-city").val()
    },
    dataType: "json",
    type: "get",
    success: function (data) {
      $("#opt-branch-district").html(data.data);
      $("#opt-branch-sub-district").html("<option>[SELECT DATA]</option>");
    },
  });
});

$("#opt-branch-district").change(function () {
  $.ajax({
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/branch/getKelurahan",
    data: {
      provinsi: $("#opt-branch-province").val(),
      kota: $("#opt-branch-city").val(),
      kecamatan: $("#opt-branch-district").val()
    },
    dataType: "json",
    type: "get",
    success: function (data) {
      $("#opt-branch-sub-district").html(data.data);
    },
  });
});
$("#txt-phone-number").keyup(function () {
  var curchr = this.value.replaceAll(" ", "").length;
  var curval = $(this)
    .val()
    .replace(/[^0-9]/g, "");

  $("#txt-phone-number").val(curval);
});
