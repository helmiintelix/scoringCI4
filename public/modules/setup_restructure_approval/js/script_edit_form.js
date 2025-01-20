$("#loading_checker").hide();
$("#loading-approval2-1").hide();
$("#loading-approval-1").hide();
$(".approval-loading").hide();

function addChecker(elm) {
  console.log("numberOfChecker", numberOfChecker);
  console.log("LIMIT_USER_CHECKER", LIMIT_USER_CHECKER);
  if (numberOfChecker + 1 > LIMIT_USER_CHECKER) {
    showWarning("Maksimal " + LIMIT_USER_CHECKER + " user");
    return false;
  }

  let btn_add = elm.id;
  $("#" + btn_add).hide();
  $("#loading_checker").show();

  $.ajax({
    type: "get",
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "/workflow_pengajuan/setup_restructure_approval_list/get_user_list",
    data: { id: $("#id_pengajuan").val(), param: "checker" },
    dataType: "json",
    timeout: 3000,
    success: function (msg) {
      let html = "";
      html += '<div class="input-group">';
      html +=
        '<select class="form-control form-control-sm" id="checked_by" name="checked_by[]" style="margin-top: 10px;">';
      html += '<option value="">[Select User]</option>';
      $.each(msg.data, function (i, val) {
        html +=
          '<option value="' +
          val["value"] +
          '">' +
          val["value"] +
          " - " +
          val["item"] +
          "</option>";
      });
      html += "</select>";
      html += '<span class="input-group-append" style="margin-top:5px">';
      html +=
        '<span class="bi bi-x-square" style="color:red;cursor: pointer;margin-top: 5px;margin-left: 10px;color:red;" onClick="delListChecker(this)" data-toggle="tooltip" data-placement="top" title="Delete user"></span>';
      html += "</span>";
      html += "</div>";
      $("#form-checker").append(html);
      numberOfChecker++;
      setTimeout(() => {
        $("#" + btn_add).show();
        $("#loading_checker").hide();
      }, 500);
    },
  });
}

function isActive(elm) {
  if ($(elm)[0].checked) {
    $("#opt-active-flag").val("1").change();
    //$("label[for='flexSwitchCheckChecked']").text('Active');
  } else {
    $("#opt-active-flag").val("0").change();
    //$("label[for='flexSwitchCheckChecked']").text('Not Active');
  }
}
function addLevelApproval(elm) {
  let level = parseInt(numberOfLevel) + 1;

  if (level > LIMIT_LEVEL_APPROVAL) {
    showWarning("Maksimal " + LIMIT_LEVEL_APPROVAL + " level");
    return false;
  }

  let num = parseInt(numberOfLevel);
  lastNumberOfLevel = parseInt(lastNumberOfLevel) + 1;
  let btn_add = elm.id;
  $("#" + btn_add).hide();
  $("#loading-approval2-1").show();

  let arrLevel =
    $("#arrlevel").val() + "|approval-by-" + lastNumberOfLevel + "[]";
  $("#arrlevel").val(arrLevel);
  $.ajax({
    type: "get",
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "/workflow_pengajuan/setup_restructure_approval_list/get_user_list",
    data: { id: $("#id_pengajuan").val(), param: "approval" },
    dataType: "json",
    timeout: 3000,
    success: function (msg) {
      let html = "";
      html +=
        '<div class="form-group" id="form-approval-' + lastNumberOfLevel + '">';
      html += '<div class="col-sm-12">';
      html +=
        '<span onClick="delLevel(' +
        lastNumberOfLevel +
        ')" data-toggle="tooltip" data-placement="top" title="Hapus level approval" class="bi bi-dash-circle" style="margin-right: 6px; color: red; cursor: pointer;"></span>';
      html +=
        '<label for="approval-by-' +
        lastNumberOfLevel +
        '" style="color: cornflowerblue" class="lbl-approval">Approval ' +
        lastNumberOfLevel +
        "</label>";
      html += "<br>";
      html += '<div class="input-group">';
      html +=
        '<select class="form-control form-control-sm" id="approval-by-' +
        lastNumberOfLevel +
        '" name="approval-by-' +
        lastNumberOfLevel +
        '[]" style="margin-top: 0px;">';
      html += '<option value="">[Select User]</option>';
      $.each(msg.data, function (i, val) {
        html +=
          '<option value="' +
          val["value"] +
          '">' +
          val["value"] +
          " - " +
          val["item"] +
          "</option>";
      });
      html += "</select>";
      html += '<div class="input-group-append">';
      html +=
        '<span class="bi bi-plus-square" style="cursor: pointer; margin-left: 5px;" id="btn-add-approval-' +
        lastNumberOfLevel +
        '" onClick="addApproval(this, ' +
        lastNumberOfLevel +
        ')" data-toggle="tooltip" data-placement="top" title="Menambahkan approval"></span>';

      html +=
        '<div class="spinner-border spinner-border-sm" id="loading-approval-' +
        lastNumberOfLevel +
        '" role="status">';
      html +=
        '<span class="visually-hidden" id="loading-approval-' +
        lastNumberOfLevel +
        '">Loading...';
      html += "</span>";
      html += "</div>";
      html += "</div>";
      html += "</div>";
      html += "</div>";
      html += "</div>";

      $("#form-approval").append(html);
      $("#loading-approval-" + lastNumberOfLevel).hide();
      reNumbering();
      setTimeout(() => {
        $("#" + btn_add).show();
        $("#loading-approval2-1").hide();
      }, 500);
    },
  });
  numberOfLevel = parseInt(numberOfLevel) + 1;
  $("#numLevel").val(numberOfLevel);
}

function addApproval(elm, num) {
  let countLimit = $("#form-approval-" + num + " select").length;

  if (countLimit == LIMIT_USER_APPROVAL) {
    showWarning("Maksimal " + LIMIT_USER_APPROVAL + " user");
    return false;
  }
  let btn_add = elm.id;
  $("#" + btn_add).hide();
  $("#loading-approval-" + num).show();

  $.ajax({
    type: "get",
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "/workflow_pengajuan/setup_restructure_approval_list/get_user_list",
    data: { id: $("#id_pengajuan").val(), param: "approval" },
    dataType: "json",
    timeout: 3000,
    success: function (msg) {
      let html = "";

      html += '<div class="input-group">';
      html +=
        '<select class="form-control form-control-sm" id="approval-by-' +
        num +
        '" name="approval-by-' +
        num +
        '[]" style="margin-top: 10px;">';
      html += '<option value="">[Select User]</option>';
      $.each(msg.data, function (i, val) {
        html +=
          '<option value="' +
          val["value"] +
          '">' +
          val["value"] +
          " - " +
          val["item"] +
          "</option>";
      });
      html += "</select>";
      html += '<span class="input-group-append" style="margin-top:5px">';
      html +=
        '<span class="bi bi-x-square" style="color:red;cursor: pointer;margin-top: 5px;margin-left: 10px;color:red;" onClick="delListChecker(this)" data-toggle="tooltip" data-placement="top" title="Delete user"></span>';
      html += "</span>";
      html += "</div>";

      //  html = '<div class="col-sm-12">';
      //  html += '<span class="bi bi-x-square pull-right" style="cursor:pointer;cursor:pointer;margin-top: 15px;color:red" onClick="delList(this)" data-toggle="tooltip" data-placement="top" title="delete user" ></span>';
      //  html += '<select class="" id="approval-by-'+num+'" name="approval-by-'+num+'[]" style="margin-top: 10px;">';
      //  html +='<option value="">[select User]</option>';
      //  $.each(msg.data,function(i,val){
      //      html +='<option value="'+val['value']+'">'+val['value']+' - '+val['item']+'</option>';
      //  });
      //  html += '</select>';
      //  html += '</div>';
      $("#form-approval-" + num).append(html);

      setTimeout(() => {
        $("#" + btn_add).show();
        $("#loading-approval-" + num).hide();
      }, 500);
    },
  });
}

function delList(elm) {
  elm.parentElement.remove();
}
function delListChecker(elm) {
  // elm.parentElement.remove();
  // numberOfChecker = numberOfChecker-1;
  // Cari elemen input-group terdekat dan hapus
  $(elm).closest(".input-group").remove();
  // Kurangi jumlah checker
  numberOfChecker--;
}
function delLevel(elm) {
  console.log(elm);
  let arrLevel = $("#arrlevel")
    .val()
    .replace("|approval-by-" + elm + "[]", "");

  console.log(arrLevel);
  console.log("halo");
  $("#arrlevel").val(arrLevel);
  $("#form-approval-" + elm).remove();
  numberOfLevel = numberOfLevel - 1;
  $("#numLevel").val(numberOfLevel);

  reNumbering();
}

function reNumbering() {
  $.each($(".lbl-approval"), function (i, val) {
    let num = i + 1;
    val.innerHTML = "Approval " + num;
  });
}

// function saveApproval(){
//     $('#numLevel').val(numberOfLevel);
//     let data = $("#form_approval_diskon").serialize();
//     $("input[type=text] , select").attr('disabled',true);

//     $('#btn_save').attr('disabled',true);
//     $.ajax({
//             type: "get",
//              url: GLOBAL_MAIN_VARS["SITE_URL"] + "/setup_diskon_approval/setup_diskon_approval/get_user_list",
//              data:data,
//              dataType: "json",
//              timeout: 3000,
//              success: function(msg){
//                 console.log(msg);

//              },
//              error:function (err) {
//                 $('#btn_save').attr('disabled',false);
//              }
//     });
//     $('#btn_save').attr('disabled',false);
//     $("input[type=text] , select").attr('disabled',false);
// }

function currencyformat(elm) {
  if (elm.value == "") return false;
  elm.value = elm.value.replace(/[^0-9.,]/g, "");

  elm.value = addPeriod(elm.value.toString(), elm);
}
function addPeriod(nStr, elm) {
  let currency = nStr;
  currency = currency.replaceAll(",", "");

  currency = parseFloat(currency)
    .toLocaleString("en-US", {
      style: "currency",
      currency: "idr",
      minimumFractionDigits: 0,
    })
    .replace("IDR", "")
    .trim();

  return currency;
}
