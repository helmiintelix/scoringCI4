function initializeDataTable() {
  console.log("jQuery version:", $.fn.jquery);
  console.log("DataTable loaded:", $.fn.DataTable ? "yes" : "no");

  if (!$.fn.DataTable) {
    console.error("DataTables belum loaded, mencoba lagi...");
    return false;
  }

  var table = $("#cycleTable").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url:
        GLOBAL_MAIN_VARS["SITE_URL"] +
        "scoring/setting_cycle/setting_cycle_list",
      type: "POST",
      data: function (d) {
        d.search_by = $("#searchBy").val();
        d.keyword = $("#searchValue").val();
      },
    },
    columns: [
      {
        data: 0,
        visible: false,
      },
      {
        data: 1,
      },
      {
        data: 2,
      },
      {
        data: 3,
      },
      {
        data: 4,
      },
      {
        data: 5,
      },
      {
        data: 6,
      },
    ],
    columnDefs: [
      {
        targets: [2, 3, 4, 5, 6],
        orderable: false,
      },
    ],
    responsive: true,
    paging: true,
    ordering: true,
    order: [],
  });

  // Highlight row on click
  $("#cycleTable tbody").on("click", "tr", function () {
    if ($(this).hasClass("selected")) {
      $(this).removeClass("selected");
    } else {
      table.$("tr.selected").removeClass("selected");
      $(this).addClass("selected");
    }
  });

  // Filter
  $("#btnFilter").on("click", function () {
    table.ajax.reload();
  });

  // Reset
  $("#btnReset").on("click", function () {
    $("#searchBy").val("cycle_name");
    $("#searchValue").val("");
    table.ajax.reload();
  });

  // Add Cycle
  $("#btn-add").click(function () {
    $("#modalAddCycle").modal("show");
    $("#modalAddCycleContent").load(
      GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/setting_cycle/cycle_add_form",
      function () {
        $("#form_add_cycle").submit(function (e) {
          e.preventDefault();

          let from = parseInt($("#txt-cycle-from").val()) || 0;
          let to = parseInt($("#txt-cycle-to").val()) || 0;

          if (from > to) {
            showWarning(
              "Please check your input: From cannot be greater than To"
            );
            return false;
          }

          $.ajax({
            url:
              GLOBAL_MAIN_VARS["SITE_URL"] +
              "scoring/setting_cycle/save_cycle_add",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (resp) {
              if (resp.success) {
                showInfo("Success add cycle");
                $("#modalAddCycle").modal("hide");
                table.ajax.reload();
              } else {
                showWarning("Failed add cycle");
              }
            },
          });
        });
      }
    );
  });

  // Edit Cycle
  $("#btn-edit").click(function () {
    var data = table.row(".selected").data();
    if (data) {
      $("#modalAddCycle").modal("show");
      $("#modalAddCycleContent").load(
        GLOBAL_MAIN_VARS["SITE_URL"] +
          "scoring/setting_cycle/cycle_edit_form/" +
          data[0],
        function () {
          $("#form_edit_cycle").submit(function (e) {
            e.preventDefault();
            $.ajax({
              url:
                GLOBAL_MAIN_VARS["SITE_URL"] +
                "scoring/setting_cycle/save_cycle_edit",
              type: "POST",
              data: $(this).serialize(),
              dataType: "json",
              success: function (resp) {
                if (resp.success) {
                  showInfo("Success update cycle");
                  $("#modalAddCycle").modal("hide");
                  table.ajax.reload();
                } else {
                  showWarning("Failed update cycle");
                }
              },
            });
          });
        }
      );
    } else {
      showWarning("Please select a row first");
    }
  });

  // Delete Cycle
  $("#btn-delete").click(function () {
    var data = table.row(".selected").data();
    if (data) {
      $("#deleteCycleId").val(data[0]);
      $("#deleteMessage").text(
        'Are you sure to delete this cycle "' + data[1] + '" ?'
      );
      $("#modalDeleteCycle").modal("show");
    } else {
      showWarning("Please select a row first");
    }
  });

  // Confirm Delete
  $("#confirmDelete").click(function () {
    var id_user = $("#deleteCycleId").val();

    $.ajax({
      url: GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/setting_cycle/delete_cycle",
      type: "POST",
      data: {
        id_user: id_user,
      },
      dataType: "json",
      success: function (resp) {
        if (resp.success) {
          showInfo("Success delete cycle");
          $("#modalDeleteCycle").modal("hide");
          table.ajax.reload();
        } else {
          $("#modalDeleteCycle").modal("hide");
          showWarning(resp.message || "Failed delete cycle");
        }
      },
      error: function () {
        showWarning("Error while deleting cycle");
      },
    });
  });

  return true;
}

// loaded retry mechanism
$(document).ready(function () {
  var maxRetries = 10;
  var retryCount = 0;
  var retryInterval = 100;

  function tryInitialize() {
    if (initializeDataTable()) {
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
});
