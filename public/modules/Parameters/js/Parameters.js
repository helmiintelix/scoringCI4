jQuery(function ($) {
  var updateParameterSetting = function (param, param_id, column, value) {
    $.post(
      GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/parameters/update_parameter/",
      {
        param: param,
        param_id: param_id,
        column: column,
        value: value,
      },
      function (responseText) {
        if (responseText.success) {
          showInfo("Parameter updated successfully");
        } else {
          showWarning("Failed to update parameter");
        }
      },
      "json"
    );
  };

  // Handle checkbox changes
  $(".btn_parameter").on("change", function () {
    var param = this.dataset.param;
    var param_id = this.dataset.paramId;
    var column = this.dataset.column;
    var value = $(this).is(":checked") ? "YES" : "NO";

    console.log(param, param_id, column, value, "checkbox changed");
    updateParameterSetting(param, param_id, column, value);
  });

  // Handle dropdown changes
  $(".btn_parameter_dropdown").on("change", function () {
    var param = this.dataset.param;
    var param_id = this.dataset.paramId;
    var column = this.dataset.column;
    var value = $(this).val();

    console.log(param, param_id, column, value, "dropdown changed");

    if (column === "value_content") {
      if (value === "MAPPING") {
        $("#" + param + "_" + param_id + "_map_reference")
          .removeAttr("disabled")
          .removeClass("disabled");
      } else {
        $("#" + param + "_" + param_id + "_map_reference")
          .attr("disabled", "disabled")
          .addClass("disabled")
          .val("");
      }
    }

    updateParameterSetting(param, param_id, column, value);
  });

  // Save button
  $("#saveForm").on("click", function () {
    var $btn = $(this)
      .prop("disabled", true)
      .html('<i class="icon-refresh icon-spin"></i> Saving...');

    $.post(
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "scoring/parameters/update_parameter_commit/",
      {},
      function (response) {
        if (response.success) {
          showInfo(response.message || "Data saved successfully.");
        } else {
          showWarning(response.message || "Failed to save data.");
        }
      },
      "json"
    ).always(function () {
      $btn
        .prop("disabled", false)
        .html('<i class="icon-ok bigger-110"></i> Save');
    });
  });

  // Reset button
  $("#resetForm").on("click", function () {
    if (confirm("Are you sure you want to reset all parameters?")) {
      $("input:checkbox:checked").each(function (index) {
        console.log(index + " checkbox will be unchecked");
        $(this).prop("checked", false).trigger("change");
      });

      $("select.btn_parameter_dropdown").each(function () {
        if ($(this).prop("selectedIndex") !== 0) {
          $(this).prop("selectedIndex", 0).trigger("change");
        }
      });

      showInfo("Reset parameter berhasil");
    }
  });
});
