// File: public/modules/preview/js/preview_scheme.js

var selectedRow = "";
var selectedData = "";
var gridOptions;

function deselect() {
  if (gridOptions && gridOptions.api) {
    gridOptions.api.deselectAll();
  }
}

// Transform data untuk AG Grid dengan grouping
function transformDataForGrid(rawData) {
  console.log("Transforming data...", rawData.length, "records");

  const transformedData = [];
  let currentScheme = "";

  rawData.forEach((row, index) => {
    if (currentScheme !== row.scheme_name) {
      currentScheme = row.scheme_name;

      // Add main scheme row
      transformedData.push({
        id: row.scheme_id,
        scheme_id: row.scheme_id,
        scheme_name: row.scheme_name,
        score_value: row.score_value,
        score_value2: row.score_value2,
        group_by: row.group_by,
        method: row.method,
        parameter_group: row.parameter_group,
        parameter_selected: row.parameter_selected,
        parameter_function: row.parameter_function,
        parameter_value: row.parameter_value,
        value_content: row.value_content,
        map_reference: row.map_reference,
        rowType: "main",
      });
    } else {
      // Add sub parameter row
      const paramFunction =
        row.value_content === "MAPPING" ? "MAPPING" : row.parameter_function;
      const paramValue =
        row.value_content === "MAPPING"
          ? `Reference: ${row.map_reference}`
          : row.parameter_value;

      transformedData.push({
        id: `${row.scheme_id}_sub_${index}`,
        scheme_id: row.scheme_id,
        scheme_name: "", // Empty for sub rows
        score_value: "",
        score_value2: "",
        group_by: "",
        method: "",
        parameter_group: row.parameter_group,
        parameter_selected: row.parameter_selected,
        parameter_function: paramFunction,
        parameter_value: paramValue,
        value_content: row.value_content,
        map_reference: row.map_reference,
        rowType: "sub",
        parentSchemeId: row.scheme_id,
      });
    }
  });

  console.log("Data transformed:", transformedData.length, "rows");
  return transformedData;
}

// Grid Options
gridOptions = {
  columnDefs: [
    {
      headerName: "Scheme Name",
      field: "scheme_name",
      width: 200,
      pinned: "left",
      cellRenderer: function (params) {
        if (params.data.rowType === "main" && params.value) {
          return `<strong style="color: #495057;">${params.value}</strong>`;
        }
        return params.value || "";
      },
    },
    {
      headerName: "Score 1",
      field: "score_value",
      width: 100,
      cellRenderer: function (params) {
        if (params.value) {
          return `<span class="badge bg-success" style="font-size: 0.8em;">${params.value}</span>`;
        }
        return "";
      },
    },
    {
      headerName: "Score 2",
      field: "score_value2",
      width: 100,
      cellRenderer: function (params) {
        if (params.value) {
          return `<span class="badge bg-info" style="font-size: 0.8em;">${params.value}</span>`;
        }
        return "";
      },
    },
    {
      headerName: "Group By",
      field: "group_by",
      width: 150,
    },
    {
      headerName: "Method",
      field: "method",
      width: 100,
      cellRenderer: function (params) {
        if (params.value) {
          return `<span class="badge bg-secondary" style="font-size: 0.8em;">${params.value}</span>`;
        }
        return "";
      },
    },
    {
      headerName: "Parameter Group",
      field: "parameter_group",
      width: 150,
      cellRenderer: function (params) {
        if (params.value) {
          return `<span class="badge bg-light text-dark" style="font-size: 0.8em;">${params.value}</span>`;
        }
        return "";
      },
    },
    {
      headerName: "Parameter Selected",
      field: "parameter_selected",
      width: 180,
    },
    {
      headerName: "Function",
      field: "parameter_function",
      width: 120,
      cellRenderer: function (params) {
        if (params.value) {
          return `<code style="font-size: 0.8em;">${params.value}</code>`;
        }
        return "";
      },
    },
    {
      headerName: "Parameter Value",
      field: "parameter_value",
      width: 300,
      cellRenderer: function (params) {
        if (params.value) {
          return `<small style="color: #6c757d;">${params.value}</small>`;
        }
        return "";
      },
    },
  ],

  // Default column definitions
  defaultColDef: {
    sortable: true,
    filter: "agSetColumnFilter",
    floatingFilter: true,
    resizable: true,
  },

  rowSelection: "single", // single selection seperti contoh
  animateRows: true,
  paginationAutoPageSize: true,
  pagination: true,

  // Row styling
  getRowStyle: function (params) {
    if (params.data.rowType === "main") {
      return {
        "background-color": "#f8f9fa",
        "font-weight": "bold",
        "border-top": "2px solid #dee2e6",
      };
    } else {
      return {
        "background-color": "#fefefe",
        "padding-left": "20px",
      };
    }
  },

  // Event handler untuk cell click
  onCellClicked: (params) => {
    console.log("cell was clicked", params);
    selectedRow = params.data.id;
    selectedData = params.data;
    console.log("Selected row ID:", selectedRow);
    console.log("Selected data:", selectedData);
  },

  onGridReady: function (params) {
    console.log("Grid is ready!");
    params.api.sizeColumnsToFit();
  },
};

// Function to initialize grid
function initializeGrid() {
  console.log("Initializing AG Grid...");

  // Check if data exists
  if (typeof window.SCHEME_DATA === "undefined" || !window.SCHEME_DATA) {
    console.error("SCHEME_DATA not found!");
    return;
  }

  // Transform data
  const gridData = transformDataForGrid(window.SCHEME_DATA);

  // Set row data
  gridOptions.rowData = gridData;

  // Get grid container
  var eGridDiv = document.getElementById("myGrid");
  if (!eGridDiv) {
    console.error("Grid container #myGrid not found!");
    return;
  }

  // Create grid instance
  try {
    new agGrid.Grid(eGridDiv, gridOptions);
    console.log(
      "AG Grid initialized successfully with",
      gridData.length,
      "rows"
    );
  } catch (error) {
    console.error("Error initializing AG Grid:", error);
  }
}

// Button event handlers
$(document).ready(function () {
  // Add button
  $("#btn-add").click(function () {
    console.log("Add button clicked");
    alert("Add new scheme functionality - to be implemented");
    // Implement your add logic here
  });

  // Edit button
  $("#btn-edit").click(function () {
    if (selectedRow && selectedData) {
      console.log("Edit clicked for:", selectedRow);

      // Only allow edit for main scheme rows
      if (selectedData.rowType === "main") {
        console.log("Editing scheme:", selectedData.scheme_name);
        alert(
          "Edit scheme: " +
            selectedData.scheme_name +
            "\nScheme ID: " +
            selectedData.scheme_id
        );

        // Call your actual edit function here
        // loadScoreSettingEditForm2(selectedData.scheme_id, selectedData.parameter_group, selectedData.parameter_selected);
      } else {
        alert("Please select a main scheme row to edit");
      }
    } else {
      alert("Please select a row to edit");
    }
  });

  // Delete button
  $("#btn-del").click(function () {
    if (selectedRow && selectedData) {
      // Only allow delete for main scheme rows
      if (selectedData.rowType === "main") {
        var confirmMsg =
          "Are you sure you want to delete scheme: " +
          selectedData.scheme_name +
          "?";

        if (confirm(confirmMsg)) {
          console.log("Deleting scheme:", selectedData.scheme_id);
          alert("Delete confirmed for: " + selectedData.scheme_name);

          // Call your actual delete function here
          // loadScoreSettingDeleteForm(selectedData.scheme_id, selectedData.scheme_name);
        }
      } else {
        alert("Please select a main scheme row to delete");
      }
    } else {
      alert("Please select a row to delete");
    }
  });

  // Export Excel button
  $("#btn-export-excel").click(function () {
    console.log("Export Excel clicked");
    if (gridOptions && gridOptions.api) {
      gridOptions.api.exportDataAsExcel({
        fileName: "scoring_scheme_data.xlsx",
      });
    } else {
      alert("Grid not ready for export");
    }
  });
});

// Utility functions
function refreshGrid() {
  if (gridOptions && gridOptions.api) {
    // Re-fetch and refresh data
    console.log("Refreshing grid data...");
    // You can reload data here if needed
  }
}

function getSelectedData() {
  return selectedData;
}

function getSelectedRowId() {
  return selectedRow;
}
