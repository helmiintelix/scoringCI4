function isActive(elm){
    if($(elm)[0].checked){
        $("#opt-active-flag").val('1').change();
        // $("label[for='flexSwitchCheckChecked']").text('Active');
    }else{
        $("#opt-active-flag").val('0').change();
        // $("label[for='flexSwitchCheckChecked']").text('Not Active');
    }
}

$("#phone").keyup(function () {
    var curchr = this.value.replaceAll(" ", "").length;
    var curval = $(this)
      .val()
      .replace(/[^0-9]/g, "");
  
    $("#phone").val(curval);
  });
  
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
    }
    return true;
}

var eventDate = $("#expired_date").val();
console.log("eventDate");
console.log(eventDate);

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

$("#expired_date").daterangepicker(datepickerConfig);

$('#list_agent').select2({
    placeholder: "Select options",
    allowClear: true,
    dropdownParent: $('#newModal')
});