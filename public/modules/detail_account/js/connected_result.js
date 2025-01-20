$("#txt_ptp_date").daterangepicker({
  startDate: moment(), // Mengatur startDate dari tanggal saat ini
  minDate: moment(), // Mengatur minDate ke tanggal saat ini
  endDate: endDate,
  maxDate: endDate,
  singleDatePicker: true,
  autoUpdateInput: false,
  locale: {
    cancelLabel: "Close",
  },
  autoApply: true,
});

$("#txt_ptp_date").on("apply.daterangepicker", function (ev, picker) {
  $(this).val(picker.startDate.format("YYYY-MM-DD"));
});
