function validasi_space(val, id) {
  if (val.indexOf(" ") >= 0) {
    showWarning("Dilarang menggunakan spasi!");
    document.getElementById(id).value = val.replace(/\s/g, "");
  }
}

function numbersOnly(evt, allowDecimal = false) {
  let charCode = evt.which ? evt.which : evt.keyCode;
  if (allowDecimal && charCode === 46) return true;
  return charCode <= 31 || (charCode >= 48 && charCode <= 57);
}

function initializeDataTable() {
  console.log("jQuery version:", $.fn.jquery);
  console.log("DataTable loaded:", $.fn.DataTable ? "yes" : "no");

  if (!$.fn.DataTable) {
    console.error("DataTables belum loaded, mencoba lagi...");
    return false;
  }

  var table = $("#score_tiering_table").DataTable({
    processing: true,
    serverSide: false,
    data: [],
    columns: [
      {
        data: "no_pinjaman",
      },
      {
        data: "nama_debitur",
      },
      {
        data: "product",
      },
      {
        data: "dpd",
      },
      {
        data: "ar_balance",
        className: "text-end",
      },
      {
        data: "tunggakan_cicilan",
        className: "text-end",
      },
      {
        data: "denda",
        className: "text-end",
      },
      {
        data: "penalty",
        className: "text-end",
      },
      {
        data: "total_billing",
        className: "text-end",
      },
      {
        data: "score",
        className: "text-end",
      },
      {
        data: "score2",
        className: "text-end",
      },
    ],
    responsive: true,
    paging: true,
    ordering: true,
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    order: [[0, "asc"]],
    drawCallback: function () {
      let api = this.api(); // Get the DataTables API instance
      let totalRecords = api.rows().count();
      $("#total-data").val(totalRecords);
      $("#total-data-hidden").val(totalRecords);
    },
  });

  return table;
}

$(document).ready(function () {
  var maxRetries = 10;
  var retryCount = 0;
  var retryInterval = 100;
  var table = null;

  function tryInitialize() {
    table = initializeDataTable();
    if (table) {
      console.log("DataTable berhasil diinisialisasi!");
    } else {
      retryCount++;
      if (retryCount < maxRetries) {
        console.log("Retry " + retryCount + " dari " + maxRetries);
        setTimeout(tryInitialize, retryInterval);
      } else {
        console.error(
          "DataTables gagal diload setelah " + maxRetries + " percobaan!"
        );
        alert(
          "Error: DataTables library gagal dimuat. Silakan refresh halaman."
        );
      }
    }
  }

  tryInitialize();

  $("#btn-calculate").on("click", function () {
    let start = $("#score-tiering-start").val();
    let end = $("#score-tiering-end").val();

    if (!start || !end) {
      showWarning("Score tiering tidak boleh kosong!");
      return false;
    }
    if (parseFloat(start) > parseFloat(end)) {
      showWarning("Score start tidak boleh lebih besar dari end!");
      return false;
    }

    const bucketMap = {
      "CA1 & CA2": {
        bucket: "BUCKET 1",
        oper: "equivalent",
      },
      CA3: {
        bucket: "BUCKET 2",
        oper: "equivalent",
      },
      EARLY: {
        bucket: "BUCKET 3",
        oper: "equivalent",
      },
      MID: {
        bucket: "BUCKET 4",
        oper: "equivalent",
      },
      NPL: {
        bucket: "BUCKET 5|BUCKET 6|BUCKET 7",
        oper: "in",
      },
      WO: {
        bucket: "REMEDIAL",
        oper: "equivalent",
      },
    };

    const selected = bucketMap[$("#opt_bucket").val()] || {
      bucket: "",
      oper: "equivalent",
    };

    $.ajax({
      url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/tiering/scoring_result",
      type: "POST",
      data: {
        score_start: start,
        score_end: end,
        score_type: $("#opt_type").val(),
        lob: $("#opt_lob").val(),
        cycle: $("#opt_cycle").val(),
        bucket: selected.bucket,
        operScoring: selected.oper,
      },
      dataType: "json",
      success: function (response) {
        // Cek apakah response adalah error
        if (response && response.type === "DivisionByZeroError") {
          showWarning(
            "Error: " +
              response.message +
              " - Tidak ada data yang sesuai dengan kriteria."
          );
          return;
        }

        if (table) {
          table.clear();
          if (response && response.length > 0) {
            table.rows.add(response);
          } else {
            showWarning(
              "Tidak ada data yang ditemukan dengan kriteria tersebut."
            );
          }
          table.draw();
        }
      },
      error: function (xhr, status, error) {
        let errorMessage = "Error loading data";

        // Parse error response
        try {
          let errorResponse = JSON.parse(xhr.responseText);
          if (errorResponse.message) {
            errorMessage = errorResponse.message;
          }
        } catch (e) {
          errorMessage += ": " + error;
        }

        showWarning(errorMessage);
      },
    });
  });

  $("#btn-save-form").on("click", function (e) {
    e.preventDefault();

    let passed = true;
    $(".mandatory").each(function () {
      let nm = $(this).attr("id") || $(this).attr("name") || "";

      if (!$(this).val()) {
        passed = false;
        let label = $(this).data("label") || $(this).attr("placeholder") || nm;
        showWarning("Field " + label + " wajib diisi!");
        $(this).focus();
        return false;
      }
    });

    if (!passed) return false;

    $.ajax({
      url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/tiering/save_tiering/",
      type: "post",
      data: $("#frmTieringSettings").serialize(),
      dataType: "json",
      success: function (response) {
        if (response.success) {
          showInfo(response.message || "Data berhasil disimpan.");
          loadMenu(
            "Preview Tiering",
            "scoring/tieringPreview",
            "tieringPreview"
          );
        } else {
          showWarning(response.message || "Gagal menyimpan data.");
        }
      },
      error: function (xhr, status, error) {
        showWarning("Terjadi kesalahan: " + error);
      },
    });
  });
});
