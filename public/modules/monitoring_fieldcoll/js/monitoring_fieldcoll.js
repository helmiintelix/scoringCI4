var selr;
var selected_data;
var AllData = new Array();

function getData() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "monitoring/petugas/get_fc_monitoring_list" +
      classification,
    type: "get",
    success: function (msg) {
      AllData = msg.data.data;
      if (msg.data.length == 0) {
        $("#list_petugas")
          .attr("class", "row")
          .html('<i class="text-center text-muted">[EMPTY]</i>');
      } else {
        console.log(AllData);
        renderApprovalList(AllData);
      }
    },
    dataType: "json",
  });
}

var renderApprovalList = (data) => {
  console.log("data", data);
  var html = "";
  $.each(data, function (i, val) {
    html += '<div class="col">';
    html += ' <div class="card">';
    html += ' <div style="background-color:#ededed" class="p-2">';
    html += "<center>";
    html +=
      '<img src="' +
      val.image +
      '" id="pic_profile" onClick="selectImage()" class="rounded-circle img-thumbnail" style="width:100px;height:100px;display:block;cursor:pointer"> ';
    html += "</center>";
    html += "</div>";

    html += '<div class="card-body text-center">';
    html += '<b class="card-title text-center">' + val.name + "</b>";
    html += "<br>";
    html +=
      '<p class="card-text" style="font-size: 13px; margin-bottom:0;">' +
      val.user_id +
      "</p>";
    html +=
      '<i class="card-text" style="font-size: 13px;">' +
      val.first_login +
      " - " +
      val.last_logout +
      "</i>";
    html += "<br>";
    html += '<div class="row">';
    html += '<div class="border border-3 col-6 text-center">';
    html += '<span class="card-text">' + val.account_no + "</span>";
    html += '<p class="card-text">Assignment</p>';
    html += "</div>";
    html += '<div class="border border-3 col-6 text-center">';
    html += '<span class="card-text">' + val.dikunjungi + "</span>";
    html += '<p class="card-text">Visited</p>';
    html += "</div>";
    html += "</div>";
    html += '<div class="row">';
    html += '<div class="border border-3 col-6 text-center">';
    html += '<span class="card-text">' + val.total_janji_bayar + "</span>";
    html += '<p class="card-text">PTP</p>';
    html += "</div>";
    html += '<div class="border border-3 col-6 text-center">';
    html += '<span class="card-text">' + val.janji_bayar + "</span>";
    html += '<p class="card-text">PTP Amount</p>';
    html += "</div>";
    html += "</div>";
    html += '<div class="row">';
    html += '<div class="border border-3 col-6 text-center">';
    html += '<span class="card-text">' + val.total_payment + "</span>";
    html += '<p class="card-text">Paid</p>';
    html += "</div>";
    html += '<div class="border border-3 col-6 text-center">';
    html += '<span class="card-text">' + val.payment + "</span>";
    html += '<p class="card-text">Paid Amount</p>';
    html += "</div>";
    html += "</div>";
    html += "</div>";
    html += '<div class="card-footer text-center">';
    html += val.tracking;
    html += "</div>";
    html += "</div>";
    html += "</div>";
  });

  $("#list_petugas").html(html);
};

//Button Actions
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

$("#search_approval").keyup(function () {
  let item = $(this).val();
  console.log("item", item);
  cari(item);
});

var popupdatadetail = function (kategori, idcwx, tanggal_data, userid) {
  switch (kategori) {
    case "jumlah_assignment":
      var buttons = {
        button: {
          label: "Close",
          className: "btn-sm",
        },
      };
      console.log(userid);
      showCommonDialog(
        1200,
        300,
        "List Bucket per Collector",
        GLOBAL_MAIN_VARS["SITE_URL"] +
          "assignment/assignment/unassign_dc_list?userid=" +
          userid,
        buttons
      );
      break;
    case "jumlah_dikunjungi":
      var buttons = {
        button: {
          label: "Close",
          className: "btn-sm",
        },
      };

      showCommonDialog(
        1200,
        300,
        "List Visit per Collector",
        GLOBAL_MAIN_VARS["SITE_URL"] +
          "assignment/assignment/visit_dc_list?userid=" +
          userid,
        buttons
      );
      break;
    case "jumlah_janji_bayar":
      var buttons = {
        button: {
          label: "Close",
          className: "btn-sm",
        },
      };

      showCommonDialog(
        1200,
        300,
        "List PTP per Collector",
        GLOBAL_MAIN_VARS["SITE_URL"] +
          "assignment/assignment/ptp_dc_list?userid=" +
          userid,
        buttons
      );
      break;
    case "jumlah_payment":
      var buttons = {
        button: {
          label: "Close",
          className: "btn-sm",
        },
      };

      showCommonDialog(
        1200,
        300,
        "Jumlah Pembayaran per Collector",
        GLOBAL_MAIN_VARS["SITE_URL"] +
          "assignment/assignment/ptp_amount_dc_list?userid=" +
          userid,
        buttons
      );
      break;
  }
};
var TrackingAgent = function (user_id) {
  //alert(user_id);
  var buttons = {
    button: {
      label: "Close",
      className: "btn-sm",
    },
  };

  showCommonDialog(
    1000,
    1000,
    "TRACKING",
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "monitoring/petugas/tracking_history?user_id=" +
      user_id,
    buttons
  );
};
jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
