var eventDate = $("#txt-holiday-date").val();

var datepickerConfig = {
  singleDatePicker: true,
  showDropdowns: true,
  minYear: 1901,
  locale: {
    format: "YYYY-MM-DD",
    cancelLabel: "Close",
    applyLabel: "OK",
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
  },
  showCustomRangeLabel: false,
  autoApply: true,
  alwaysShowCalendars: true,
};

// Set startDate based on eventDate availability, using current date as fallback
if (eventDate) {
  datepickerConfig.startDate = eventDate;
}

$("#txt-holiday-date").daterangepicker(datepickerConfig);
