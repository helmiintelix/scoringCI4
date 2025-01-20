$("#provinsi").change(() => {
  let provinsi = $("#provinsi").val();

  $("#city").html("<option>[select data]</option>");
  $("#district").html("<option>[select data]</option>");
  $("#sub-district").html("<option>[select data]</option>");
  $("#zipcode").html("<option>[select data]</option>");

  if (provinsi != "") {
    $.ajax({
      url:
        GLOBAL_MAIN_VARS["SITE_URL"] + "detail_account/detail_account/get_city",
      data: {
        provinsi: provinsi,
      },
      type: "GET",
      dataType: "json",
      success: function (msg) {
        console.log("msg", msg);
        if (msg.success) {
          $.each(msg.data, function (i, val) {
            if (val.des === cityVal) {
              $("#city").append(
                '<option value="' +
                  val.des +
                  '" selected>' +
                  val.des +
                  "</option>"
              );
              $("#city").change();
            } else {
              $("#city").append(
                '<option value="' + val.des + '">' + val.des + "</option>"
              );
            }
          });
        } else {
        }
      },
    });
  }
});

$("#city").change(() => {
  let provinsi = $("#provinsi").val();
  let city = $("#city").val();

  $("#district").html("<option>[select data]</option>");
  $("#sub-district").html("<option>[select data]</option>");
  $("#zipcode").html("<option>[select data]</option>");

  if (provinsi != "") {
    $.ajax({
      url:
        GLOBAL_MAIN_VARS["SITE_URL"] +
        "detail_account/detail_account/get_district",
      data: {
        provinsi: provinsi,
        city: city,
      },
      type: "GET",
      dataType: "json",
      success: function (msg) {
        console.log("msg", msg);
        if (msg.success) {
          $.each(msg.data, function (i, val) {
            if (val.des === districtVal) {
              $("#district").append(
                '<option value="' +
                  val.des +
                  '" selected>' +
                  val.des +
                  "</option>"
              );
              $("#district").change();
            } else {
              $("#district").append(
                '<option value="' + val.des + '">' + val.des + "</option>"
              );
            }
          });
        } else {
        }
      },
    });
  }
});

$("#district").change(() => {
  let provinsi = $("#provinsi").val();
  let city = $("#city").val();
  let district = $("#district").val();

  $("#sub-district").html("<option>[select data]</option>");
  $("#zipcode").html("<option>[select data]</option>");

  if (provinsi != "") {
    $.ajax({
      url:
        GLOBAL_MAIN_VARS["SITE_URL"] +
        "detail_account/detail_account/get_sub_district",
      data: {
        provinsi: provinsi,
        city: city,
        district: district,
      },
      type: "GET",
      dataType: "json",
      success: function (msg) {
        console.log("msg", msg);
        if (msg.success) {
          $.each(msg.data, function (i, val) {
            if (val.des === subdistrictVal) {
              $("#sub-district").append(
                '<option value="' +
                  val.des +
                  '" selected>' +
                  val.des +
                  "</option>"
              );
              $("#sub-district").change();
            } else {
              $("#sub-district").append(
                '<option value="' + val.des + '">' + val.des + "</option>"
              );
            }
          });
        } else {
        }
      },
    });
  }
});

$("#sub-district").change(() => {
  let provinsi = $("#provinsi").val();
  let city = $("#city").val();
  let district = $("#district").val();
  let subDistrict = $("#sub-district").val();

  $("#zipcode").html("<option>[select data]</option>");

  if (provinsi != "") {
    $.ajax({
      url:
        GLOBAL_MAIN_VARS["SITE_URL"] +
        "detail_account/detail_account/get_zipcode",
      data: {
        provinsi: provinsi,
        city: city,
        district: district,
        subDistrict: subDistrict,
      },
      type: "GET",
      dataType: "json",
      success: function (msg) {
        console.log("msg", msg);
        if (msg.success) {
          $.each(msg.data, function (i, val) {
            if (val.des === zipcodeVal) {
              $("#zipcode").append(
                '<option value="' +
                  val.des +
                  '" selected>' +
                  val.des +
                  "</option>"
              );
              $("#zipcode").change();
            } else {
              $("#zipcode").append(
                '<option value="' + val.des + '">' + val.des + "</option>"
              );
            }
          });
        } else {
        }
      },
    });
  }
});

$(document).ready(() => {
  if (is_save == "1") {
    $(
      ".btn-save-AddNewAddress, #provinsi , #city , #district, #sub-district ,#zipcode , #address "
    ).attr("disabled", true);

    $("#provinsi").val(provinsiVal).change();
    $("#address").val(addressVal);
  } else {
    $(".btn-save-AddNewAddress").attr("disabled", false);
  }
});
