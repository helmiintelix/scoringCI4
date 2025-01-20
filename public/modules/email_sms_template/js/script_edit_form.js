function first_load() {
  $(".chosen-select").chosen();
}
setTimeout(first_load, 300);
$("#div-mechanism").hide();

$("#opt-sentby").change(function () {
  switch ($(this).val()) {
    case "WA":
      $("#div-template-relation").hide();
      $("#div-recepient").hide();
      $("#div-mechanism").hide();
      $("#div-select-times").hide();

      break;
    case "SMS":
    case "EMAIL":
    default:
      $("#div-template-relation").show();
      $("#div-recepient").show();
      $("#div-mechanism").show();
      $("#div-select-times").show();
      break;
  }
});

$(document).ready(function () {
  var maxParam = 5000;
  var zz = 1;
  var cc = 1;

  $(".btn_add_times").click(function () {
    if (zz == 5) {
      alert("Maksimal waktu pengiriman adalah 5x.");
    } else {
      zz++;
      $("#dynamic_field").append(`
              <div class="mb-3" id="form_btn_rmv_times${zz}">
                  <div class="row" style="display: flex; align-items: center;">
                      <div class="col-3">
                          <input type="time" id="txt-template-input-times${zz}" name="txt-template-input-times[]" class="form-control form-control-sm mandatory" />
                      </div>
                      <div class="col-9" style="display: flex; align-items: center;">
                          <i id="btn_rmv_times${zz}" class="bi bi-x-square btn_rmv_times" style="color:red;cursor: pointer;font-size:30px; margin-top:4px; margin-left:8px;"></i>
                      </div>
                  </div>
              </div>
          `);
    }
  });
  $("body").on("click", ".btn_rmv_times", function () {
    zz = zz - 1;
    $("#form_" + this.id).remove();
  });
  var send_times = select_time;
  var arrTimes = send_times.split(",");

  console.log(arrTimes);

  if (arrTimes) {
    // Mengatur nilai untuk elemen waktu pertama (times1)
    var times1 = document.getElementById("txt-template-input-times1");
    times1.value = arrTimes[0];

    // Mengatur nilai zz berdasarkan panjang arrTimes
    zz = arrTimes.length;
  }

  // Menambahkan elemen waktu tambahan ke dalam dynamic_field
  for (var ix = 1; ix < arrTimes.length; ix++) {
    var inputValue = arrTimes[ix].trim(); // Menghapus spasi ekstra jika ada

    $("#dynamic_field").append(`
          <div class="mb-3" id="form_btn_rmv_times${ix}">
              <div class="row" style="display: flex; align-items: center;">
                  <div class="col-3">
                      <input type="time" id="txt-template-input-times${ix}" name="txt-template-input-times[]" class="form-control form-control-sm mandatory" value="${inputValue}" />
                  </div>
                  <div class="col-9" style="display: flex; align-items: center;">
                      <i id="btn_rmv_times${ix}" class="bi bi-x-square btn_rmv_times" style="color:red; cursor: pointer; font-size:30px; margin-top:4px; margin-left:8px;"></i>
                  </div>
              </div>
          </div>
      `);
  }

  $("#rules_billing_info").hide();
  $("#template_program").hide();

  $("#opt-template-relation").change(function () {
    // alert(this.value);
    if (this.value == "Reminding PTP") {
      $("#opt-product").val("");
      $("#field-product").hide();
    } else {
    }

    switch (this.value) {
      // case '':
      //     $("#rules_ptp").show();
      //     break
      case "Reminding PTP":
        $("#template_program").show();
        break;
      case "Request Diskon Pelunasan":
      case "Request Diskon Pelunasan":
      case "Request Restructure":
      case "Request Reschedule":
      case "Verifikasi dan Upload Kelengkapan Checker":
      case "Approval Diskon Pelunasan":
      case "Approval Restructure":
      case "Approval Reschedule":
      case "Escalate Account Deskcoll":
      case "Request Billing dari Layar Agent Deskcoll":
      case "Flag Class Parameter":
      case "Surat Keterangan Lunas":
        $("#template_program").hide();
        $("#rules_ptp").hide();
        $("#rules_billing_info").hide();
        document.getElementById("tmp_relation").value = "false";
        break;
      case "Billing Info":
        $("#template_program").show();
        $("#rules_billing_info").show();
        $("#input_value_template").hide();
      case "After PTP":
        $("#template_program").show();
        $("#rules_billing_info").hide();
        $("#rules_ptp").hide();
      default:
        $("#template_program").show();
        $("#rules_ptp").show();
        $("#rules_billing_info").hide();
        setTimeout(first_load, 300);
        document.getElementById("tmp_relation").value = "true";
        break;
    }
  });
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

function addField() {
  // Pastikan editor TinyMCE sudah aktif
  if (typeof tinyMCE.activeEditor !== "undefined" && tinyMCE.activeEditor) {
    var selectedValue = $("#opt_field_list").val();
    if (selectedValue) {
      // Sisipkan konten ke dalam editor TinyMCE
      tinyMCE.activeEditor.execCommand(
        "mceInsertContent",
        false,
        selectedValue
      );
    } else {
      console.error("Selected value is empty or undefined.");
    }
  } else {
    console.error("TinyMCE editor is not active or initialized.");
  }
  return false; // Mencegah perilaku default dari tombol (misalnya, mengirimkan formulir)
}

try {
  tinymce.remove();
} catch (error) {}

tinymce.init({
  selector: "#txt-template-content",
  license_key: "gpl",
  toolbar: "undo redo | blocks fontfamily fontsize",
  tinycomments_mode: "embedded",
  tinycomments_author: "Author name",
  promotion: false,
  branding: false,
});
