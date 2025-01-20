function inputTexttoNumber(elementId) {
  $(elementId).keyup(function () {
    var curchr = this.value.replaceAll(" ", "").length;
    var curval = $(this)
      .val()
      .replace(/[^0-9]/g, "");

    $(this).val(curval);
  });
}

$(function () {
  inputTexttoNumber("#txt-classification-priority");
  inputTexttoNumber("#txt-times");
  inputTexttoNumber("#txt-days");
  inputTexttoNumber("#expired_time");
});

function first_load() {
  $(".chosen-select").chosen();
}
setTimeout(first_load, 300);

$("#opt-filter-list").change(function () {
  $.ajax({
    url: "classification/classification_list/getDetail",
    data: {
      value: $("#opt-filter-list").val(),
    },
    // dataType:"html",
    type: "get",
    success: function (data) {
      // alert(data);
      // $("#opt-product-code").html(data);
      console.log("testt");
      console.log(data);
      $("#opt-detail-list").empty();
      $("#opt-detail-list").append(data);
      $("#opt-detail-list").trigger("chosen:updated");
    },
  });
});

$("#opt-handling").change(function () {
  var selectedValue = $(this).val();
  console.log(selectedValue);
  if (selectedValue != null) {
    if (!selectedValue.includes("Telecoll")) {
      $("#check-number").val("");
      $('#check-number option[value="1"]').remove();
    } else {
      console.log($('#check-number option[value="1"]').length > 0);
      if (!$('#check-number option[value="1"]').length) {
        $("#check-number").append($("<option></option>").val("1").html("Yes"));
      }
    }
    $(".chosen-select").trigger("chosen:updated");

    if (selectedValue.includes("SMS")) {
      $("#form-template-sms").show();
      var param = "SMS";
      $.ajax({
        url: "classification/classification_list/getTemplate",
        data: {
          value: param,
        },
        // dataType:"html",
        type: "get",
        success: function (data) {
          var parsedHTML = $.parseHTML(data);
          // Masukkan ke dalam div output
          $("#opt-sms-template").empty().append(parsedHTML);

        },
      });
    } else {
      $("#form-template-sms").hide();
      $("#opt-sms-template").val("");
      $("#form-template-email").val("");
      // $('#form-template-wa').val("");
    }

    if (selectedValue.includes("Email")) {
      $("#form-template-email").show();
      var param = "Email";
      $.ajax({
        url: "classification/classification_list/getTemplate",
        data: {
          value: param,
        },
        // dataType:"html",
        type: "get",
        success: function (data) {
          var parsedHTML = $.parseHTML(data);
          // Masukkan ke dalam div output
          $("#opt-email-template").empty().append(parsedHTML);
        },
      });
    } else {
      $("#form-template-email").hide();
      $("#opt-sms-template").val("");
      $("#form-template-email").val("");
      // $('#form-template-wa').val("");
    }

    // if (selectedValue.includes('Whatsapp')) {
    //     $('#form-template-wa').show();
    //     var param = 'Email';
    //     $.ajax({
    //         url: 'classification/classification_list/getTemplateWa',
    //         data: {
    //             "value": param
    //         },
    //         // dataType:"html",
    //         type: "get",
    //         success: function (data) {
    //             $("#opt-wa-template").html(data);
    //         }
    //     });
    // } else {
    //     $('#form-template-wa').hide();
    //     $("#opt-sms-template").val("");
    //     $('#form-template-email').val("");
    //     $('#form-template-wa').val("");

    // }
    // else{
    // $('#form-template').hide();
    // $('#opt-template').val("");
    // var param='';
    // }
  } else {
    $("#form-template-sms").hide();
    $("#form-template-email").hide();
    // $('#form-template-wa').hide();
  }
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
    $(this).val(picker.startDate.format("YYYY/MM/DD"));
  });
}

$(function () {
  initializeSingleDatePicker("#specificDate");
  initializeSingleDatePicker("#DateRangeTo");
  initializeSingleDatePicker("#DateRangeFrom");
  initializeSingleDatePicker("#effectiveDate");
  initializeSingleDatePicker("#expiredDate");
});

var option_list = null;
$.ajax({
  type: "GET",
  dataType: "json",
  url: "classification/classification_list/getListCustomField",
  success: function (msg) {
    option_list = msg;
    console.log(option_list);
    arr = "";
    $.each(msg, function (index, obj) {
      $.each(obj, function (key, value) {
        arr += '<option value="' + key + '">' + value + "</option>";
      });
    });
    console.log(arr);
  },
});

var i = 0;

$("#btn_add_set").click(function (e) {
  console.log("TEST BUTTTOBNNNNNI");
  e.preventDefault();
  i = i + 1;
  var values = [];
  $("select[name*='opt-search[]']").each(function () {
    values.push($(this).val());
  });
  // console.log(option_list);
  /* console.log(values);
  console.log(arr);
  
  console.log(option_list[0]);
  console.log(values.includes("CF_EXOSTATUS" ));*/
  arr_add = "";
  same = false;
  $.each(option_list, function (index, obj) {
    $.each(obj, function (key, value) {
      /*	// console.log(key)
          $.each(values, function( k, v) {
            // console.log(v);
            if(key == v){
              // console.log('found same');
              same = true;
              return false; 
            }
            else{
              // console.log('not found same');
              same = false;
              
            }
          });
          */
      if (same == false) {
        arr_add += '<option value="' + key + '">' + value + "</option>";
      }
      // console.log('--------------------------')
    });
  });
  //  console.log(arr_add);
  $("#set_detail").append(
    '	<div id="div_add_set' +
      i +
      '" name="div_add_set"class="row align-items-center" > ' +
      '<div class="col-auto">' +
      '<select name="opt-search[]" class="form-control form-control-sm mandatory" data-placeholder="-Please Select Data-" id="opt-search' +
      i +
      '">' +
      arr_add +
      "</select>" +
      "</div>" +
      '<div class="col-auto">' +
      '<label for="txt-class-category" class="fs-6 text-capitalize"> = </label>' +
      "</div>" +
      '<div class="col-sm-3" id="opt-input"> ' +
      '<select name="opt-self[]" class="form-control form-control-sm mandatory" data-placeholder="-Please Select Data-" id="opt-self' +
      i +
      '">' +
      '<option value="" selected="selected">[select data]</option>' +
      '<option value="this">this</option>' +
      "</select>" +
      "</div>" +
      '<div class="col-auto" id="text-input">' +
      '<input type="text" id="txt-keyword' +
      i +
      '" name="txt-keyword[]" class="form-control form-control-sm mandatory" />' +
      "</div>" +
      '<div class="col-auto">' +
      '<button type="button" class="btn btn-danger btn-sm" id="btn_delete_set"><i class="bi bi-x-circle"></i>' +
      "Delete</button>" +
      "</div>" +
      "</div></div>"
  );

  // $("select[name*='opt-search[]']").each(function() {
  $("#opt-search" + i).change(function () {
    // a+i = i;
    if ($(this).val() == "CF_ACCOUNT_GROUP") {
      console.log("testttt");

      var attrID = $(this).attr("id");
      lastChar = attrID.substr(attrID.length - 1);

      $("#txt-keyword" + lastChar).remove();

      $("#opt-self" + lastChar).remove();

      var addSelf =
        '<select name="opt-self[]" class="form-control form-control-sm mandatory" id="opt-self' +
        lastChar +
        '">' +
        '<option value="" selected="selected">[select data]</option>' +
        '<option value="this">this</option>' +
        '<option value="CF_USER_ACCOUNT_GROUP">User Account Group Request</option>' +
        "</select>";

      var addKeyword =
        '<select name="txt-keyword[]" class="form-control form-control-sm mandatory" id="txt-keyword' +
        lastChar +
        '">' +
        '<option value="" selected="selected">[select data]</option>';

      $.each(jsonAG, function (key, value) {
        addKeyword += '<option value="' + key + '">' + value + "</option>";
      });

      addKeyword += "</select>";

      $("#div_add_set" + lastChar + " #opt-input").append(addSelf);
      $("#div_add_set" + lastChar + " #text-input").append(addKeyword);
    } else if ($(this).val() == "AGENT_ID") {
      var attrID = $(this).attr("id");
      lastChar = attrID.substr(attrID.length - 1);

      $("#txt-keyword" + lastChar).remove();
      $("#opt-self" + lastChar).remove();

      var addSelf =
        '<select name="opt-self[]" class="form-control form-control-sm mandatory" id="opt-self' +
        lastChar +
        '">' +
        '<option value="" selected="selected">[select data]</option>' +
        '<option value="this">this</option>' +
        '<option value="last_agent1">Last Agent</option>' +
        "</select>";

      var addKeyword =
        '<select name="txt-keyword[]" class="form-control form-control-sm mandatory" id="txt-keyword' +
        lastChar +
        '">' +
        '<option value="" selected="selected">[select data]</option>';

      $.each(jsonAG, function (key, value) {
        addKeyword += '<option value="' + key + '">' + value + "</option>";
      });

      addKeyword += "</select>";

      $("#div_add_set" + lastChar + " #opt-input").append(addSelf);
      $("#div_add_set" + lastChar + " #text-input").append(addKeyword);
    } else {
      var attrID = $(this).attr("id");
      lastChar = attrID.substr(attrID.length - 1);

      $("#txt-keyword" + lastChar).remove();
      $("#opt-self" + lastChar).remove();

      var addSelf =
        '<select name="opt-self[]" class="form-control form-control-sm mandatory" id="opt-self' +
        lastChar +
        '">' +
        '<option value="" selected="selected">[select data]</option>' +
        '<option value="this">this</option>' +
        "</select>";

      $("#div_add_set" + lastChar + " #opt-input").append(addSelf);
      $("#div_add_set" + lastChar + " #text-input").append(
        '<input type="text" id="txt-keyword' +
          lastChar +
          '" name="txt-keyword[]" class="form-control form-control-sm mandatory" />'
      );
    }
  });
});

$("#opt-search").change(function () {
  if ($(this).val() == "CF_ACCOUNT_GROUP") {
    var attrID = $(this).attr("id");
    lastChar = attrID.substr(attrID.length - 1);

    $("#txt-keyword").remove();
    $("#opt-self").remove();

    var addSelf =
      '<select name="opt-self[]" class="form-control form-control-sm mandatory" id="opt-self">' +
      '<option value="" selected="selected">[select data]</option>' +
      '<option value="this">this</option>' +
      '<option value="CF_USER_ACCOUNT_GROUP">User Account Group Request</option>' +
      "</select>";

    var addKeyword =
      '<select name="txt-keyword[]" class="form-control form-control-sm mandatory" id="txt-keyword">' +
      '<option value="" selected="selected">[select data]</option>';

    $.each(jsonAG, function (key, value) {
      addKeyword += '<option value="' + key + '">' + value + "</option>";
    });

    addKeyword += "</select>";

    $("#div_add_set #opt-input").append(addSelf);
    $("#div_add_set #text-input").append(addKeyword);
  } else if ($(this).val() == "AGENT_ID") {
    var attrID = $(this).attr("id");
    lastChar = attrID.substr(attrID.length - 1);

    $("#txt-keyword").remove();
    $("#opt-self").remove();

    var addSelf =
      '<select name="opt-self[]" class="form-control form-control-sm mandatory" id="opt-self">' +
      '<option value="" selected="selected">[select data]</option>' +
      '<option value="this">this</option>' +
      '<option value="last_agent1">last_agent</option>' +
      "</select>";

    var addKeyword =
      '<select name="txt-keyword[]" class="form-control form-control-sm mandatory" id="txt-keyword">' +
      '<option value="" selected="selected">[select data]</option>';

    $.each(jsonAG, function (key, value) {
      addKeyword += '<option value="' + key + '">' + value + "</option>";
    });

    addKeyword += "</select>";

    $("#div_add_set #opt-input").append(addSelf);
    $("#div_add_set #text-input").append(addKeyword);
  } else {
    var attrID = $(this).attr("id");
    lastChar = attrID.substr(attrID.length - 1);

    $("#txt-keyword").remove();
    $("#opt-self").remove();

    var addSelf =
      '<select name="opt-self[]" class="form-control form-control-sm mandatory" id="opt-self">' +
      '<option value="" selected="selected">[select data]</option>' +
      '<option value="this">this</option>' +
      "</select>";

    $("#div_add_set #opt-input").append(addSelf);
    $("#div_add_set #text-input").append(
      '<input type="text" id="txt-keyword" name="txt-keyword[]" class="form-control form-control-sm mandatory" />'
    );
  }
});

// Remove parent of 'remove' link when link is clicked.
$("#set_detail").on("click", "#btn_delete_set", function (e) {
  e.preventDefault();
  $(this).parent().parent().remove();
  i = i - 1;
});

$("#opt-schedule").change(function () {
  switch ($(this).val()) {
    case "WEEKLY":
      $("#opt-week").show();
      $("#div_week").show();
      $("#opt-week").val("");
      $("#div_date").hide();
      $("#div_date_range").hide();
      $("#div_monthly").hide();
      break;
    case "DATE":
      $("#div_date").show();
      $("#div_week").hide();
      $("#div_date_range").hide();
      $("#div_monthly").hide();
      break;
    case "DATE_RANGE":
      $("#div_date").hide();
      $("#div_week").hide();
      $("#div_monthly").hide();
      $("#div_date_range").show();
      break;
    case "MONTHLY":
      $("#div_date").hide();
      $("#div_date_range").hide();
      $("#div_week").hide();
      $("#div_monthly").show();
      break;
    default:
      $("#div_date").hide();
      $("#div_date_range").hide();
      $("#div_week").hide();
      $("#div_monthly").hide();
      break;
  }
});
$("input[type=radio][name=month-option]").change(function () {
  console.log(this.value);
  switch (this.value) {
    case "DAYS":
      $("#table_week").hide();
      $("#table_week_days").hide();
      $("#table_days").show();
      break;
    case "WEEK_DAYS":
      $("#table_week").show();
      $("#table_week_days").show();
      $("#table_days").hide();
      break;
    default:
      $("#table_week").hide();
      $("#table_week_days").hide();
      $("#table_days").hide();

      break;
  }
});

var add_filter = daftar_filter;
console.log(add_filter);
$("#query_builder").queryBuilder({
  plugins: daftar_plugin,
  filters: add_filter, //define di admin_main_view.php

  // rules: rules_new
});

$("#btn-filter-reset").on("click", function () {
  $("#query_builder").queryBuilder("reset");
  $("div[name*='div_add_set']").remove();
  $("select[name*='opt-search']").val("0");
  $("input[name*='txt-keyword']").val("");
  $("#opt-schedule").val("");
  $("#opt-week").hide();
  $("#opt-week").val("");
  return false;
});
