function isActive(elm) {
  if ($(elm)[0].checked) {
    $("#opt-active-flag").val("1").change();
    //$("label[for='flexSwitchCheckChecked']").text('Active');
  } else {
    $("#opt-active-flag").val("0").change();
    //$("label[for='flexSwitchCheckChecked']").text('Not Active');
  }
}
$("#txt-radius").keyup(function () {
  var curchr = this.value.replaceAll(" ", "").length;
  var curval = $(this)
    .val()
    .replace(/[^0-9]/g, "");

  $("#txt-radius").val(curval);
});
