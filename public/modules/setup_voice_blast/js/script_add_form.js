// Inisialisasi plugin Chosen
function first_load() {
  $(".chosen-select").chosen();
}
setTimeout(first_load, 300);

var rules_new;

$("#query_builder").queryBuilder({
  // plugins: ["bt-tooltip-errors"],

  filters: daftar_filter, //define di admin_main_view.php

  rules: rules_new,
});

function addField() {
  var cursorPos = $("#call_script").prop("selectionStart");
  var v = $("#call_script").val();
  var textBefore = v.substring(0, cursorPos);
  var textAfter = v.substring(cursorPos, v.length);

  $("#call_script").val(textBefore + $("#opt_field_list").val() + textAfter);
  return false;
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
