$(document).ready(() => {
  if (is_save == "1") {
    $(".btn-save-AddNewPhone, #newhp1 , #newhp2 , #newhp3 ").attr(
      "disabled",
      true
    );
  } else {
    $(".btn-save-AddNewPhone").attr("disabled", false);
  }
});
