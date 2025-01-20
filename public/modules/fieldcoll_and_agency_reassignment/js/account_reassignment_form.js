$(document).ready(function () {
  if ($("#optGroupAssignment").val("")) {
    $("#showTeam").hide();
    $("#showFieldCollector").hide();
    $("#showAgency").hide();
  }
});
$("#optGroupAssignment").on("change", function () {
  var optGroupAssignment = $("#optGroupAssignment").val();
  if (optGroupAssignment == "TEAM") {
    $("#showTeam").show();
    $("#showFieldCollector").hide();
    $("#showAgency").hide();
  } else if (optGroupAssignment == "FC") {
    $("#showTeam").hide();
    $("#showFieldCollector").show();
    $("#showAgency").hide();
  } else if (optGroupAssignment == "AGENCY") {
    $("#showTeam").hide();
    $("#showFieldCollector").hide();
    $("#showAgency").show();
  } else {
    $("#showTeam").hide();
    $("#showFieldCollector").hide();
    $("#showAgency").hide();
  }
});

$("#assignmentType").on("change", function () {
  var assignmentType = $("#assignmentType").val();
  if (assignmentType == "permanen") {
    $("#showDate").hide();
    $("#showDate").val("");
  } else if (assignmentType == "temporer") {
    $("#showDate").show();
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
    $(this).val(picker.startDate.format("YYYY-MM-DD"));
  });
}

$(function () {
  initializeSingleDatePicker("#fromDate");
  initializeSingleDatePicker("#toDate");
});

$(document).ready(function () {
  // Inisialisasi plugin Chosen
  function first_load() {
    $(".chosen-select").chosen();
  }
  setTimeout(first_load, 300);
});
