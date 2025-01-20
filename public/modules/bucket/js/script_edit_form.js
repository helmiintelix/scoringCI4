$(document).ready(function () {
  // Sembunyikan elemen yang diperlukan saat halaman dimuat
  $(
    "#hide, #hide2, #any_amount_keep_promise, #any_amount_acceptable_promise"
  ).hide();

  // Inisialisasi plugin Chosen
  function first_load() {
    $(".chosen-select").chosen();
  }
  setTimeout(first_load, 300);

  // Tampilkan elemen tambahan yang sesuai dengan nilai awal opsi
  $("#amount_to_be_paid_to_keep_promise, #min_amount_acceptable_promise").each(
    function () {
      var optionValue = $(this).val();
      if (optionValue === "Any Amount") {
        if ($(this).attr("id") === "amount_to_be_paid_to_keep_promise") {
          $("#any_amount_keep_promise").show();
        } else {
          $("#any_amount_acceptable_promise").show();
        }
      }
    }
  );

  // Tambahkan event listener untuk menangani perubahan nilai opsi
  $("#amount_to_be_paid_to_keep_promise, #min_amount_acceptable_promise").on(
    "change",
    function () {
      var optionValue = $(this).val();
      if (optionValue === "Any Amount") {
        if ($(this).attr("id") === "amount_to_be_paid_to_keep_promise") {
          $("#any_amount_keep_promise").show();
        } else {
          $("#any_amount_acceptable_promise").show();
        }
      } else {
        if ($(this).attr("id") === "amount_to_be_paid_to_keep_promise") {
          $("#any_amount_keep_promise").hide().removeAttr("value");
        } else {
          $("#any_amount_acceptable_promise").hide().removeAttr("value");
        }
      }
    }
  );

  // Hapus karakter selain angka saat input angka
  $(".number").keyup(function () {
    var currentLength = this.value.replaceAll(" ", "").length;
    var currentValue = $(this)
      .val()
      .replace(/[^0-9]/g, "");
    $(this).val(currentValue);
  });
});
