function loadScoreSettingEditForm2(schemeId, paramGroup, paramSelected) {
  $.post(
    GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/preview/active_parameter",
    {
      scheme_id: schemeId,
      parameter: paramGroup,
      id_parameter: paramSelected,
    },
    function (response) {
      if (response.success) {
        loadMenu("setting", "scoring/setting/" + schemeId);
      } else {
        showWarning("Failed to activate parameter: " + response.message);
      }
    },
    "json"
  ).fail(function () {
    showWarning("An error occurred while processing the data");
  });
}

function loadScoreSettingDeleteForm(schemeId, schemeName) {
  $("#deleteConfirmMessage").text(
    'Are you sure you want to delete the scheme "' + schemeName + '"?'
  );
  $("#deleteSchemeId").val(schemeId);
  $("#deleteConfirmModal").modal("show");
}

$("#confirmDeleteBtn").on("click", function () {
  var schemeId = $("#deleteSchemeId").val();

  $.post(
    GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/preview/delete_scheme",
    {
      scheme_id: schemeId,
    },
    function (responseText) {
      if (responseText.success) {
        showInfo("Scheme has been deleted successfully");
        $("button[onClick*='" + schemeId + "']")
          .closest("tr")
          .remove();
        $("#deleteConfirmModal").modal("hide");
      } else {
        showWarning("Failed to delete scheme: " + responseText.message);
      }
    },
    "json"
  ).fail(function () {
    showWarning("An error occurred while deleting the scheme");
  });
});
