var loadData = function () {
  $.ajax({
    type: "GET",
    url:
      "new_matrix_report/report_template/getDataRecord/?" +
      $("#myReport").serialize(),
    async: false,
    dataType: "json",
    success: function (msg) {
      console.log(msg);
      $("#recordData").nextAll().remove();
      $("#recordData").after(msg.dataRow);
    },
  });
};

$(document).ready(function () {
  loadData();
  $("#submitButton").click(function () {
    loadData();
  });
  $("#btnSaveToExcel").click(function () {
    location.href =
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "new_matrix_report/report_template/getDataRecordDownload/?" +
      $("#myReport").serialize();
  });
  initDateRangePicker("#start");
  initDateRangePicker("#end");
});
function initDateRangePicker(selector) {
  $(selector).daterangepicker(
    {
      singleDatePicker: true,
      showDropdowns: true,
      autoApply: true,
      autoUpdateInput: false,
      locale: {
        format: "YYYY-MM-DD",
        separator: " - ",
        applyLabel: "Apply",
        cancelLabel: "Cancel",
        fromLabel: "From",
        toLabel: "To",
        customRangeLabel: "Custom",
        weekLabel: "W",
        daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
        monthNames: [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "July",
          "August",
          "September",
          "October",
          "November",
          "December",
        ],
        firstDay: 1,
      },
    },
    function (start, end, label) {
      $(selector).val(start.format("YYYY-MM-DD"));
    }
  );
}
