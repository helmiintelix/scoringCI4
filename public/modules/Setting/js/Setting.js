document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".date-picker").forEach(function (el) {
    new Datepicker(el, {
      format: "yyyy-mm-dd",
      autohide: true,
    });
  });
});

$("#saveForm").click(function () {
  if (!validateForm()) return;

  $.ajax({
    type: "POST",
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/setting/save_setting/",
    data: $("#frmScoringSettings").serialize(),
    dataType: "json",
    success: function (msg) {
      if (msg.success) {
        showInfo("Data has been successfully saved.");
        loadMenu("preview", "scoring/preview/preview");
      } else {
        showWarning(msg.message || "An error occurred while saving.");
      }
    },
    error: function (xhr) {
      console.error("Raw response:", xhr.responseText);
      showWarning("Server error: " + xhr.status);
    },
  });
});

function validateForm() {
  if ($("#score_label").val().trim() === "") {
    showWarning("Label/Title is required!");
    return false;
  }
  return true;
}

$("#uploadForm").click(function (e) {
  e.preventDefault();

  $.ajax({
    type: "GET",
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/setting/upload_file_form/",
    success: function (response) {
      if ($("#uploadModal").length === 0) {
        $("body").append(`
          <div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title">Upload File</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="uploadModalBody"></div>
              </div>
            </div>
          </div>
        `);
      }

      $("#uploadModalBody").html(DOMPurify.sanitize(response));
      $("#uploadModal").modal("show");
    },
    error: function (xhr, status, error) {
      showWarning("Failed to load upload form: " + error);
    },
  });
});

window.isUploading = false;
window.uploadFormBound = window.uploadFormBound || false;

if (!window.uploadFormBound) {
  $(document).on("submit", "#frm_upload", function (e) {
    e.preventDefault();

    if (window.isUploading) {
      showInfo("Upload is in progress, please wait...");
      return;
    }

    let fileInput = $("#userfile")[0]?.files[0];
    if (!fileInput) {
      showInfo("Please select a file to upload!");
      return;
    }

    window.isUploading = true;

    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn
      .prop("disabled", true)
      .html('<i class="bi bi-spinner bi-spin"></i> Uploading...');

    let formData = new FormData(this);

    $.ajax({
      type: "POST",
      url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/setting/save_file/",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (msg) {
        if (msg.success === true) {
          showInfo("File uploaded successfully.");
          $("#uploadModal").modal("hide");
          loadMenu("Preview", "scoring/preview/preview", "preview");
        } else {
          showWarning(msg.message || "An error occurred during upload.");
        }
      },
      error: function (xhr, status, error) {
        showWarning("Request failed: " + error);
      },
      complete: function () {
        window.isUploading = false;
        submitBtn.prop("disabled", false).html(originalText);
      },
    });
  });

  window.uploadFormBound = true;
}

$(document)
  .off("hidden.bs.modal", "#uploadModal")
  .on("hidden.bs.modal", "#uploadModal", function () {
    $("#frm_upload")[0]?.reset();
    window.isUploading = false;
    const submitBtn = $("#frm_upload").find('button[type="submit"]');
    submitBtn
      .prop("disabled", false)
      .html('<i class="bi bi-upload"></i> Upload');
  });
