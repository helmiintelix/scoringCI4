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
          $("#opt-sms-template").html(data);
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
          $("#opt-email-template").html(data);
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

function initializeSingleDatePicker(elementId, value) {
  $(elementId).daterangepicker({
    singleDatePicker: true,
    autoUpdateInput: false,
    locale: {
      cancelLabel: "Close",
    },
    autoApply: true,
    startDate: value,
  });

  $(elementId).on("apply.daterangepicker", function (ev, picker) {
    $(this).val(picker.startDate.format("YYYY-MM-DD"));
  });
}

$(function () {
  var specificDateValue = new Date($("#specificDate").val());
  var DateRangeToValue = new Date($("#DateRangeTo").val());
  var DateRangeFromValue = new Date($("#DateRangeFrom").val());
  var effectiveDateValue = new Date($("#effectiveDate").val());
  var expiredDateValue = new Date($("#expiredDate").val());
  initializeSingleDatePicker("#specificDate", specificDateValue);
  initializeSingleDatePicker("#DateRangeTo", DateRangeToValue);
  initializeSingleDatePicker("#DateRangeFrom", DateRangeFromValue);
  initializeSingleDatePicker("#effectiveDate", effectiveDateValue);
  initializeSingleDatePicker("#expiredDate", expiredDateValue);
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
    $.each(update_json.opt_search, function (k, v) {
      if (k == 0) {
        $("#opt-search").val(v);

        console.log($("#opt-search").val());
        if ($("#opt-search").val() == "CF_ACCOUNT_GROUP") {
          $("#txt-keyword").remove();
          $("#opt-self").remove();

          var addSelf =
            '<select name="opt-self[]"class="form-control form-control-sm mandatory" id="opt-self">' +
            '<option value="" selected="selected">[select data]</option>' +
            '<option value="this">this</option>' +
            '<option value="CF_USER_ACCOUNT_GROUP">User Account Group Request</option>' +
            "</select>";

          var addKeyword =
            '<select name="txt-keyword[]" class="form-control form-control-sm mandatory" id="txt-keyword">' +
            '<option value="" selected="selected">[select data]</option>';

          $.each(jsonAG, function (key, value) {
            // console.log(key);
            // console.log(update_json.txt_keyword[k]);
            if (key == update_json.txt_keyword[k]) {
              addKeyword +=
                '<option value="' + key + '" selected>' + value + "</option>";
            } else {
              addKeyword +=
                '<option value="' + key + '">' + value + "</option>";
            }
          });

          addKeyword += "</select>";

          $("#div_edit_set #opt-input").append(addSelf);
          $("#div_edit_set #text-input").append(addKeyword);
        } else if ($("#opt-search").val() == "AGENT_ID") {
          $("#txt-keyword" + lastChar).remove();
          $("#opt-self" + lastChar).remove();

          var addSelf =
            '<select name="opt-self[]" class="form-control form-control-sm mandatory" id="opt-self' +
            lastChar +
            '">' +
            '<option value="" selected="selected">[select data]</option>' +
            '<option value="this">this</option>' +
            '<option value="last_agent1">last_agent</option>' +
            "</select>";

          var addKeyword =
            '<select name="txt-keyword[]" class="form-control form-control-sm mandatory" id="txt-keyword' +
            lastChar +
            '">' +
            '<option value="" selected="selected">[select data]</option>';

          $.each(jsonAG, function (key, value) {
            if (key == update_json.txt_keyword[k]) {
              addKeyword +=
                '<option value="' + key + '" selected>' + value + "</option>";
            } else {
              addKeyword +=
                '<option value="' + key + '">' + value + "</option>";
            }
          });

          addKeyword += "</select>";

          $("#div_edit_set" + lastChar + " #opt-input").append(addSelf);
          $("#div_edit_set" + lastChar + " #text-input").append(addKeyword);
        } else {
          $("#txt-keyword").remove();
          $("#opt-self").remove();

          var addSelf =
            '<select name="opt-self[]" class="form-control form-control-sm mandatory" id="opt-self">' +
            '<option value="" selected="selected">[select data]</option>' +
            '<option value="this">this</option>' +
            '<option value="last_agent1">last_agent</option>' +
            "</select>";

          $("#div_edit_set #opt-input").append(addSelf);
          $("#div_edit_set #text-input").append(
            '<input type="text" id="txt-keyword" name="txt-keyword[]" class="form-control form-control-sm mandatory" />'
          );
          $("#txt-keyword").val(update_json.txt_keyword[k]);
        }
        $("#opt-self").val(update_json.opt_self[k]);
      } else {
        i = i + 1;
        // console.log(msg);
        arr_edit = "";
        arr_self = "";
        if (update_json.opt_self[k]) {
          arr_self +=
            '<option value="">[select data]</option>' +
            '<option value="this" selected="selected">this</option>' +
            '<option value="last_agent1">last_agent</option>';
        } else {
          arr_self +=
            '<option value="" selected="selected">[select data]</option>' +
            '<option value="this">this</option>' +
            '<option value="last_agent1">last_agent</option>';
        }
        $.each(msg, function (index, obj) {
          $.each(obj, function (key, value) {
            if (key == v) {
              arr_edit +=
                '<option value="' + key + '" selected>' + value + "</option>";
            } else {
              arr_edit += '<option value="' + key + '">' + value + "</option>";
            }
          });
        });
        // console.log(arr_edit);
        $("#set_detail").append(
          '<div id="div_edit_set' +
          i +
          '" name="div_edit_set"class="row align-items-center" > ' +
          '<div class="col-auto">' +
          '<select name="opt-search[]" class="form-control form-control-sm mandatory" data-placeholder="-Please Select Data-" id="opt-search' +
          i +
          '">' +
          arr_edit +
          "</select>" +
          "</div>" +
          '<div class="col-auto">' +
          '<label for="txt-class-category" class="fs-6 text-capitalize"> = </label>' +
          "</div>" +
          '<div class="col-sm-3" id="opt-input"> ' +
          '<select name="opt-self[]" class="form-control form-control-sm mandatory" data-placeholder="-Please Select Data-" id="opt-self' +
          i +
          '">' +
          arr_self +
          "</select>" +
          "</div>" +
          '<div class="col-auto" id="text-input">' +
          '<input type="text" id="txt-keyword' +
          i +
          '" name="txt-keyword[]" class="form-control form-control-sm mandatory" value="' +
          update_json.txt_keyword[k] +
          '" />' +
          "</div>" +
          '<div class="col-auto">' +
          '<button type="button" class="btn btn-danger btn-sm" id="btn_delete_set"><i class="bi bi-x-circle"></i>' +
          "Delete</button>" +
          "</div>" +
          "</div></div>"
        );

        if ($("#opt-search" + i).val() == "CF_ACCOUNT_GROUP") {
          var attrID = $("#opt-search" + i).attr("id");
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
            if (key == update_json.txt_keyword[k]) {
              addKeyword +=
                '<option value="' + key + '" selected>' + value + "</option>";
            } else {
              addKeyword +=
                '<option value="' + key + '">' + value + "</option>";
            }
          });

          addKeyword += "</select>";

          $("#div_edit_set" + lastChar + " #opt-input").append(addSelf);
          $("#div_edit_set" + lastChar + " #text-input").append(addKeyword);
        } else if ($("#opt-search" + i).val() == "AGENT_ID") {
          var attrID = $("#opt-search" + i).attr("id");
          lastChar = attrID.substr(attrID.length - 1);

          $("#txt-keyword" + lastChar).remove();
          $("#opt-self" + lastChar).remove();

          var addSelf =
            '<select name="opt-self[]" class="form-control form-control-sm mandatory" id="opt-self' +
            lastChar +
            '">' +
            '<option value="" selected="selected">[select data]</option>' +
            '<option value="this">this</option>' +
            '<option value="last_agent1">last_agent</option>' +
            "</select>";

          var addKeyword =
            '<select name="txt-keyword[]" class="form-control form-control-sm mandatory" id="txt-keyword' +
            lastChar +
            '">' +
            '<option value="" selected="selected">[select data]</option>';

          $.each(jsonAG, function (key, value) {
            if (key == update_json.txt_keyword[k]) {
              addKeyword +=
                '<option value="' + key + '" selected>' + value + "</option>";
            } else {
              addKeyword +=
                '<option value="' + key + '">' + value + "</option>";
            }
          });

          addKeyword += "</select>";

          $("#div_edit_set" + lastChar + " #opt-input").append(addSelf);
          $("#div_edit_set" + lastChar + " #text-input").append(addKeyword);
        } else {
          /* var attrID = $(this).attr("id");
                lastChar = attrID.substr(attrID.length - 1);
              
              $("#txt-keyword"+lastChar).remove();
              
              $('#div_edit_set'+lastChar+' .edit').append('<input type="text" id="txt-keyword'+lastChar+'" name="txt-keyword[]" class="col-xs-10 col-sm-6 " />');
              $("#txt-keyword"+lastChar).val(update_json.txt_keyword[k]); */
        }
        $("#opt-self" + i).val(update_json.opt_self[k]);
        $("#opt-search" + i).change(function () {
          if ($(this).val() == "CF_ACCOUNT_GROUP") {
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
              addKeyword +=
                '<option value="' + key + '">' + value + "</option>";
            });

            addKeyword += "</select>";

            $("#div_edit_set" + lastChar + " #opt-input").append(addSelf);
            $("#div_edit_set" + lastChar + " #text-input").append(addKeyword);
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
              addKeyword +=
                '<option value="' + key + '">' + value + "</option>";
            });

            addKeyword += "</select>";

            $("#div_edit_set" + lastChar + " #opt-input").append(addSelf);
            $("#div_edit_set" + lastChar + " #text-input").append(addKeyword);
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
              '<option value="last_agent1">last_agent</option>' +
              "</select>";

            $("#div_edit_set" + lastChar + " #opt-input").append(addSelf);
            $("#div_edit_set" + lastChar + " #text-input").append(
              '<input type="text" id="txt-keyword' +
              lastChar +
              '" name="txt-keyword[]" class="form-control form-control-sm mandatory" />'
            );
          }
        });
      }
      // console.log(key);
      // console.log(value);
    });
  },
});

var i = 0;

$("#btn_add_set").click(function (e) {
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
  arr_edit = "";
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
        arr_edit += '<option value="' + key + '">' + value + "</option>";
      }
      // console.log('--------------------------')
    });
  });
  //  console.log(arr_edit);
  $("#set_detail").append(
    '	<div id="div_add_set' +
    i +
    '" name="div_add_set"class="row align-items-center" > ' +
    '<div class="col-auto">' +
    '<select name="opt-search[]" class="form-control form-control-sm mandatory" data-placeholder="-Please Select Data-" id="opt-search' +
    i +
    '">' +
    arr_edit +
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

$(document).ready(function () {
  var opt_schedule_value = $("#opt-schedule").val();
  if (opt_schedule_value === "WEEKLY") {
    $("#div_week").show();
  } else if (opt_schedule_value === "DATE") {
    $("#div_date").show();
  } else if (opt_schedule_value === "DATE_RANGE") {
    $("#div_date_range").show();
  } else if (opt_schedule_value === "MONTHLY") {
    $("#div_monthly").show();
  }
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
      $("#div_date_range").show();
      $("#div_date").hide();
      $("#div_week").hide();
      $("#div_monthly").hide();
      break;
    case "MONTHLY":
      $("#div_monthly").show();
      $("#div_date").hide();
      $("#div_date_range").hide();
      $("#div_week").hide();
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

$("#query_builder").queryBuilder({
  plugins: daftar_plugin,
  filters: daftar_filter, //define di admin_main_view.php
  rules: rules_edit,
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
