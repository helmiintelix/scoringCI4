// kondisi_khusus_pl_rsch = JSON.parse(kondisi_khusus_pl_rsch);
// kondisi_khusus_pl_rstr = JSON.parse(kondisi_khusus_pl_rstr);
bucket_pl = JSON.parse(bucket_pl);

$(".filterme").on("input", function (eve) {
  eve.target.value = eve.target.value.replace(/[^0-9.]/g, "");
});
$("#query_builder").queryBuilder({
  // plugins: ['bt-tooltip-errors'],

  filters: add_filter, //define di admin_main_view.php
  rules: rules_new,
});
$("#query_builder").on("change", function () {
  getQueryBuilder();
});

$("#tipe_pengajuan").change(function () {
  $("#query_builder").change();
});

function addOptionKondisiKhusus(product) {
  // console.log('product'.product);
  $("#desc_kondisi_khusus").html("");

  $.each(kondisi_khusus_pl, function (i, val) {
    // console.log('val',val);
    if (i != "") {
      $("#desc_kondisi_khusus").append(
        '<option value="' + i + '" >' + i + " - " + val + "</option>"
      );
    } else {
      $("#desc_kondisi_khusus").append(
        '<option value="' + i + '" >' + val + "</option>"
      );
    }
  });

  setTimeout(() => {
    $("#desc_kondisi_khusus ").trigger("chosen:updated");
  }, 100);
}

function addOptionBucket(product) {
  $("#bucket_id").html("");

  $.each(bucket_pl, function (i, val) {
    if (i != "") {
      $("#bucket_id").append(
        '<option value="' + i + '" >' + i + " - " + val + "</option>"
      );
    } else {
      $("#bucket_id").append('<option value="' + i + '" >' + val + "</option>");
    }
  });

  setTimeout(() => {
    $("#bucket_id").trigger("chosen:updated");
  }, 100);
}
addOptionBucket("");

function getQueryBuilder() {
  let check = $("#query_builder").queryBuilder("getRules");
  if (check == null) {
    console.log("null");
  } else {
    let flag = $("#hirarki_flag").val();
    console.log("test flag", flag);
    $.each(check.rules, function (i, val) {
      addOptionKondisiKhusus(val.value);
      addOptionBucket(val.value);
    });
    if (flag == "3") {
      $("#form-group-max-nor-disc-rate").show();
      $("#form-group-max-nor-disc-princt-rate").show();
      $("#form-group-max-nor-disc-int-rate").show();
    }
  }
}

$("#hirarki_flag").on("change", function () {
  let flag = $(this).val();
  console.log(flag);
  $("#form-group-desc-kondisi-khusus").hide().val("");
  $("#form-group-max-kondisi-khusus").hide().val("");
  $("#form-group-max-kondisi-khusus-discount-rate").hide().val("");
  $("#form-group-max-nor-disc-rate").hide().val("");
  $("#form-group-max-nor-disc-princt-rate").hide().val("");
  $("#form-group-max-nor-disc-int-rate").hide().val("");

  if (flag == "1") {
    getQueryBuilder();
    $("#form-group-desc-kondisi-khusus").show();
    $("#form-group-max-kondisi-khusus").show();
  } else if (flag == "2") {
    $("#form-group-max-kondisi-khusus-discount-rate").show();
  } else if (flag == "3") {
    $("#query_builder").change();
    $("#txt-max-normal-discount-rate").val("");
    $("#txt-max-normal-discount-principle-rate").val("");
    $("#txt-max-normal-discount-interest-rate").val("");
  } else {
  }
});

$(document).ready(function () {
  $("#bucket_id , #desc_kondisi_khusus").chosen({
    width: "100%",
    no_results_text: "Tidak ditemukan",
  });
  $(".rules-group-header").children().children().next()[0].style =
    "display:none";
});
function isActive(elm) {
  if ($(elm)[0].checked) {
    $("#opt-active-flag").val("1").change();
    //$("label[for='flexSwitchCheckChecked']").text('Active');
  } else {
    $("#opt-active-flag").val("0").change();
    //$("label[for='flexSwitchCheckChecked']").text('Not Active');
  }
}
