function isActive(elm) {
  if ($(elm)[0].checked) {
    $("#status").val("1").change();
    //$("label[for='flexSwitchCheckChecked']").text('Active');
  } else {
    $("#status").val("0").change();
    //$("label[for='flexSwitchCheckChecked']").text('Not Active');
  }
}
var ACTION = GLOBAL_MAIN_VARS["LANGUAGE"]["button"];
var LABEL = GLOBAL_MAIN_VARS["LANGUAGE"]["label"];
document.getElementById("id_reason").innerHTML = LABEL["description"];
document.getElementById("type_tagging").innerHTML = LABEL["type"];
