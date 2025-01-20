jQuery(function ($) {
  $("#opt-param").chosen({
    width: "400px",
    search_contains: "true",
  });
  $("#opt-param").val($("[name=<?=$field_name ?>]").val().split(","));
  $("#opt-param").trigger("chosen:updated");
});
