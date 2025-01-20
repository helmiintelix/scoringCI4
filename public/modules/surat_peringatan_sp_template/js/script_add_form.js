function first_load() {
  $(".chosen-select").chosen();
}
setTimeout(first_load, 300);
function sanitizeInput(selector) {
  $(selector).keyup(function () {
    var curval = $(this)
      .val()
      .replace(/[^0-9]/g, "");
    $(this).val(curval);
  });
}

sanitizeInput("#txt-letter-dpd-from");
sanitizeInput("#txt-letter-dpd-to");

function addField() {
  // Pastikan editor TinyMCE sudah aktif
  if (typeof tinyMCE.activeEditor !== "undefined" && tinyMCE.activeEditor) {
    var selectedValue = $("#opt_field_list").val();
    if (selectedValue) {
      // Sisipkan konten ke dalam editor TinyMCE
      tinyMCE.activeEditor.execCommand(
        "mceInsertContent",
        false,
        selectedValue
      );
    } else {
      console.error("Selected value is empty or undefined.");
    }
  } else {
    console.error("TinyMCE editor is not active or initialized.");
  }
  return false; // Mencegah perilaku default dari tombol (misalnya, mengirimkan formulir)
}

try {
  tinymce.remove();
} catch (error) {}

tinymce.init({
  selector: "#txt-letter-content",
  license_key: "gpl",
  toolbar: "undo redo | blocks fontfamily fontsize",
  tinycomments_mode: "embedded",
  tinycomments_author: "Author name",
  promotion: false,
  branding: false,
});
