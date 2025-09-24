var selr = "";
var selected_data = "";

function deselect() {
  gridOptions.api.deselectAll();
}

function getData() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/parameters/get_parameters_list",
    type: "get",
    success: function (msg) {
      gridOptions.api.setColumnDefs(msg.data.header);
      gridOptions.api.setRowData(msg.data.data);
    },
    dataType: "json",
  });
}

// Custom cell renderers
function includeCellRenderer(params) {
  if (params.value === "YES") {
    return '<input type="checkbox" checked disabled class="ace ace-switch ace-switch-2"><span class="lbl"></span>';
  } else {
    return '<input type="checkbox" disabled class="ace ace-switch ace-switch-2"><span class="lbl"></span>';
  }
}

function summedCellRenderer(params) {
  if (params.data.is_sum === "EXC") {
    return "&nbsp;";
  }
  if (params.value === "YES") {
    return '<input type="checkbox" checked disabled class="ace ace-switch ace-switch-2"><span class="lbl"></span>';
  } else {
    return '<input type="checkbox" disabled class="ace ace-switch ace-switch-2"><span class="lbl"></span>';
  }
}

function valueContentCellRenderer(params) {
  // You'll need to get the dropdown options from your controller
  return (
    '<select class="form-control-itx" disabled><option>' +
    params.value +
    "</option></select>"
  );
}

// Grid Options
var gridOptions = {
  columnDefs: [
    {
      field: "name",
      headerName: "Parameters",
      width: 200,
      checkboxSelection: true,
      headerCheckboxSelection: true,
    },
    {
      field: "is_include",
      headerName: "Include",
      width: 120,
      cellRenderer: includeCellRenderer,
    },
    {
      field: "is_sum",
      headerName: "Summed",
      width: 120,
      cellRenderer: summedCellRenderer,
    },
    {
      field: "value_content",
      headerName: "Value Content",
      width: 180,
      cellRenderer: valueContentCellRenderer,
    },
    {
      field: "is_primary",
      headerName: "Primary",
      width: 100,
      hide: true, // Hidden like in original PHP
    },
  ],

  // Default column properties
  defaultColDef: {
    sortable: true,
    filter: "agSetColumnFilter",
    floatingFilter: true,
    resizable: true,
  },

  rowSelection: "multiple",
  animateRows: true,
  paginationAutoPageSize: true,
  pagination: true,

  // Event handler
  onCellClicked: (params) => {
    console.log("cell was clicked", params);
    selr = params.data.id;
    selected_data = params.data;
    console.log("click>>>>", selr);
    console.log("data>>", selected_data);
  },

  onRowSelected: (params) => {
    if (params.node.selected) {
      selr = params.data.id;
      selected_data = params.data;
    }
  },
};

// Initialize grid
var eGridDiv = document.getElementById("myGrid");
new agGrid.Grid(eGridDiv, gridOptions);

// Button Actions
var showFormResponse = function (responseText, statusText) {
  if (responseText.success) {
    showInfo("Data already saved");
    getData();
  } else {
    showInfo(responseText.message);
    return false;
  }
};

$("#btn-edit").click(function () {
  if (selr) {
    console.log("sel>>", selr);
    var buttons = {
      success: {
        label: "<i class='icon-ok'></i> Save",
        className: "btn-sm btn-success",
        callback: function () {
          var options = {
            url:
              GLOBAL_MAIN_VARS["SITE_URL"] +
              "scoring/parameters/save_parameter_edit",
            type: "post",
            success: showFormResponse,
            dataType: "json",
          };
          $("form").ajaxSubmit(options);
        },
      },
      button: {
        label: "Close",
        className: "btn-sm",
      },
    };
    showCommonDialog(
      900,
      500,
      "Edit Parameter",
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "scoring/parameters/parameter_edit_form?id=" +
        selr,
      buttons
    );
  } else {
    showWarning("Silakan pilih data");
  }
});

$("#btn-add").click(function () {
  var buttons = {
    success: {
      label: "<i class='icon-ok'></i> Save",
      className: "btn-sm btn-success",
      callback: function () {
        var options = {
          url:
            GLOBAL_MAIN_VARS["SITE_URL"] +
            "scoring/parameters/save_parameter_add",
          type: "post",
          success: showFormResponse,
          dataType: "json",
        };
        if (checkValidate() == false) {
          return false;
        }
        $("form").ajaxSubmit(options);
      },
    },
    button: {
      label: "Close",
      className: "btn-sm",
    },
  };

  showCommonDialog(
    900,
    500,
    "ADD PARAMETER",
    GLOBAL_MAIN_VARS["SITE_URL"] + "scoring/parameters/parameter_add_form",
    buttons
  );
});

$("#btn-del").click(function () {
  if (selr) {
    console.log(selr);
    bootbox.confirm(
      "Are you sure you want to delete " + selected_data.name + "?",
      function (result) {
        if (result) {
          $.post(
            GLOBAL_MAIN_VARS["SITE_URL"] +
              "scoring/parameters/remove_parameter/",
            { id: selr },
            function (data) {
              if (data.success == true) {
                showInfo(data.message);
                getData();
              } else {
                showInfo(data.message);
                return false;
              }
            },
            "json"
          );
        }
      }
    );
  } else {
    alert("Please select a row.");
  }
});

$("#btn-export-excel").click(function () {
  gridOptions.api.exportDataAsExcel();
});

// Function to handle inline parameter updates (similar to your original onclick functions)
function update_parameter_setting_checkbox(type, id, field, checkbox) {
  var value = checkbox.is(":checked") ? "YES" : "NO";
  $.post(
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "scoring/parameters/update_parameter_setting",
    {
      type: type,
      id: id,
      field: field,
      value: value,
    },
    function (data) {
      if (data.success) {
        getData(); // Refresh grid
        showInfo("Parameter updated successfully");
      } else {
        showInfo("Failed to update parameter");
      }
    },
    "json"
  );
}

function update_parameter_setting(type, id, field, value) {
  $.post(
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "scoring/parameters/update_parameter_setting",
    {
      type: type,
      id: id,
      field: field,
      value: value,
    },
    function (data) {
      if (data.success) {
        getData(); // Refresh grid
        showInfo("Parameter updated successfully");
      } else {
        showInfo("Failed to update parameter");
      }
    },
    "json"
  );
}

$(document).ready(function () {
  getData(); // Load initial data
});
