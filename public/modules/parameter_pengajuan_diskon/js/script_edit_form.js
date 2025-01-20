bucket_pl = JSON.parse(bucket_pl);

$(".filterme").on("input", function (eve) {
  eve.target.value = eve.target.value.replace(/[^0-9.]/g, "");
});
$("#query_builder_edit").queryBuilder({
  // plugins: ['bt-tooltip-errors'],

  filters: add_filter, //define di admin_main_view.php
  rules: rules_basic2,
});

$("#query_builder_edit").on("change", function () {
  let check = $("#query_builder_edit").queryBuilder("getRules");
  if (check == null) {
    console.log("null");
  } else {
    let flag = $("#hirarki_flag").val();
    $.each(check.rules, function (i, val) {
      addOptionKondisiKhusus(val.value);
      addOptionBucket(val.value);

      if (flag == "3") {
        hide_all();

        $("#form-group-max-nor-disc-rate").show();
        $("#form-group-max-nor-disc-princt-rate").show();
        $("#form-group-max-nor-disc-int-rate").show();
      }
    });
  }
});

$("#tipe_pengajuan").change(function () {
  $("#query_builder_edit").change();
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
    // console.log('val',val);
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
  let check = $("#query_builder_edit").queryBuilder("getRules");
  console.log(check);

  if (check == null) {
    console.log("null");
  } else {
    let flag = $("#hirarki_flag").val();
    console.log("test flag", flag);
    $.each(check.rules, function (i, val) {
      addOptionKondisiKhusus(val.value);
      addOptionBucket(val.value);
    });
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
    // $("#txt-max-normal-discount-rate").val("");
    // $("#txt-max-normal-discount-principle-rate").val("");
    // $("#txt-max-normal-discount-interest-rate").val("");
  } else {
  }
});

$(document).ready(function () {
  $("#query_builder_edit").change();

  $("#hirarki_flag").val(hirarki).change();

  $("#txt-max-normal-discount-rate").val(max_normal_discount_rate);
  $("#txt-max-normal-discount-principle-rate").val(
    max_normal_discount_principal_rate
  );
  $("#txt-max-normal-discount-interest-rate").val(
    max_normal_discount_interest_rate
  );
  $("#txt-max-permanent-block-discount-rate").val(max_permanent_discount_rate);
  $("#txt-max-kondisi-khusus-discount-rate").val(
    max_kondisi_khusus_discount_rate
  );

  if (desc_kondisi_khusus != "") {
    desc_kondisi_khusus = JSON.parse(desc_kondisi_khusus);
    $("#desc_kondisi_khusus")
      .val(desc_kondisi_khusus)
      .change()
      .trigger("chosen:updated");
  }
  if (bucket_list != "") {
    bucket_list = JSON.parse(bucket_list);
    $("#bucket_id").val(bucket_list).change().trigger("chosen:updated");
  }
  $("#bucket_id , #desc_kondisi_khusus").chosen({
    width: "100%",
    no_results_text: "Tidak ditemukan",
  });
  $(".rules-group-header").children().children().next()[0].style =
    "display:none";
});
