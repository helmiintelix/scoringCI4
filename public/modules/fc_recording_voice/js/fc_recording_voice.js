var selr;
var selected_data;
var TOKEN_VALID = false;
var agent_id, ext, number, start_time, end_time, create_time;

function actionRenderer(params) {
  const link = params.data.action;
  return link;
}

function deselect() {
  gridOptions.api.deselectAll();
}

function getData() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "recordingfc/recording_list/get_recording_list" +
      classification,
    type: "get",
    success: function (msg) {
      var columnDefs = msg.data.header;

      columnDefs.forEach(function (column) {
        if (column.field === "action") {
          column.cellRenderer = actionRenderer;
        }
        if (column.field == "id") {
          column.hide = true;
        }
      });
      gridOptions.api.setGridOption("columnDefs", msg.data.header);
      gridOptions.api.setGridOption("rowData", msg.data.data);
    },
    dataType: "json",
  });
}
var gridOptions = {
  columnDefs: [
    { field: "" },
    { field: "" },
    { field: "" },
    { field: "" },
    { field: "" },
    { field: "" },
    { field: "" },
    { field: "" },
    { field: "" },
    { field: "" },
    { field: "" },
    { field: "" },
    { field: "" },
    { field: "" },
  ],

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

  onCellClicked: (params) => {
    console.log("cell was clicked", params);
    selr = params.data.id;
    selected_data = params.data;
    agent_id = params.data.agent_id;
    ext = params.data.extension_id;
    number = params.data.number;
    start_time = params.data.start_time;
    end_time = params.data.end_time;
    create_time = params.data.create_time;
  },
};
var eGridDiv = document.getElementById("myGridFrv");
new agGrid.Grid(eGridDiv, gridOptions);

var showFormResponse = function (responseText, statusText) {
  if (responseText.success) {
    showInfo(responseText.message);
    getData();
    if (responseText.notification_id) {
      sendNotification(responseText.notification_id);
    }
  } else {
    showInfo(responseText.message);
    return false;
  }
};
function get_path_recording(id) {
  $("#panel-body-recording").fadeOut(200);
  var html = "";

  html += "AGENT : " + agent_id;
  html += "<br>";
  html += "EXT : " + ext;
  html += "<br>";
  html += "NUMBER : " + number;
  html += "<br>";
  html += "CALL TIME : " + create_time;
  html += "<br>";
  $("#panel-body-recording").html(html).fadeIn(200);

  let res = process_file_recording(id);

  $("#player_recording").attr("src", "");
  $("#panel-body-recording").html(html);
  $("#player_recording").attr("src", res.data.file_path);
}
function get_path_download(id) {
  $.ajax({
    type: "GET",
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "recordingfc/recording_list/get_path/",
    data: { id: id },
    async: false,
    dataType: "json",
    success: function (msg) {
      if (msg.success == true) {
        //$("#player_recording").attr('src',msg.recording.file_path);
        //location.href = msg.recording.file_path;
        window.open(msg.data.file_path, "_blank");
      } else {
      }
    },
    error: function () {},
  });
}
function process_file_recording(id) {
  var res;
  $.ajax({
    type: "GET",
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "recordingfc/recording_list/get_path/",
    data: { id: id },
    async: false,
    dataType: "json",
    success: function (msg) {
      res = msg;
    },
    error: function () {},
  });
  return res;
}

jQuery(function ($) {
  getData();
});
