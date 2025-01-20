var selr;
var selected_data;
var AllData = new Array();

function getData() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "user_and_group/user_management_temp/user_management_list_temp" +
      classification,
    type: "get",
    success: function (msg) {
      AllData = msg.data.data;
      if (msg.data.length == 0) {
        $("#list_approval")
          .attr("class", "row")
          .html('<i class="text-center text-muted">[EMPTY]</i>');
      } else {
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
    if (typeof val.item !== "undefined") {
      // the variable is defined
      val = val.item;
    }
    html += '<div class="col">';
    html += ' <div class="card">';
    html += ' <div style="background-color:#ededed" class="p-2">';
    if (val.notes.toUpperCase() == "ADD") {
      html +=
        '<span class="badge badge-sm text-bg-danger float-end ">NEW</span>';
    } else {
      html +=
        '<span class="badge badge-sm text-bg-warning float-end ">UPDATE</span>';
    }

    if (val.type_collection.toUpperCase() == "TELECOLL") {
      html +=
        '<span class="badge badge-sm text-bg-success float-end  me-1">TELECOLL</span><br></br>';
    } else if (val.type_collection.toUpperCase() == "FIELDCOLL") {
      html +=
        '<span class="badge badge-sm text-bg-primary float-end  me-1">FIELDCOLL</span><br></br>';
    } else {
      html += '<span class="badge badge-smfloat-end me-1"></span><br></br>';
    }

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
      '<i class="card-subtitle mb-2 text-muted " style="font-size: 10px;">' +
      val.group_id +
      "</i>";
    html += "<br>";
    html += '<div class="btn-group" role="group" aria-label="Basic example">';
    html +=
      '<button type="button" class="btn btn-sm btn-outline-primary" onClick="viewDetailApproval(\'' +
      val.id +
      "','" +
      val.notes.toUpperCase() +
      '\')" id="btn-approve"><i class="bi bi-eye"></i>View</button>';
    html +=
      '<button type="button" class="btn btn-sm btn-outline-success" onClick="doApproveUser(\'' +
      val.id +
      "','" +
      val.name +
      '\')" id="btn-approve"><i class="bi bi-check2"></i>Approve</button>';
    html +=
      '<button type="button" class="btn btn-sm btn-outline-danger" onClick="doRejectUser(\'' +
      val.id +
      "','" +
      val.name +
      '\')" id="btn-reject"><i class="bi bi-x-lg"></i>Reject</button>';
    html += "</div>";
    html += "</div>";
    html += '<div class="card-footer text-center">';
    if (val.notes.toUpperCase() == "ADD") {
      html +=
        '<i><small class="text-muted ">from: ' +
        val.created_by_name +
        "</small></i>";
      html += '<span class="text-muted "> | </span>';
      html +=
        '<small class="text-muted" id="show_time_' +
        val.id +
        '">' +
        timeDif(val.created_time) +
        "</small>";
      html +=
        '<input type="hidden"  class="time_notification" show_time = "show_time_' +
        val.id +
        '"  value="' +
        val.created_time +
        '"/>';
    } else {
      html +=
        '<i><small class="text-muted ">from: ' +
        val.updated_by_name +
        "</small></i>";
      html += '<span class="text-muted "> | </span>';
      html +=
        '<small class="text-muted" id="show_time_' +
        val.id +
        '">' +
        timeDif(val.updated_time) +
        "</small>";
      html +=
        '<input type="hidden"  class="time_notification" show_time = "show_time_' +
        val.id +
        '"  value="' +
        val.updated_time +
        '"/>';
    }
    html += "</div>";
    html += "</div>";
    html += "</div>";
  });

  $("#list_approval").html(html);
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

function cari(val) {
  var fuseOptions = {
    threshold: 0.2,
    keys: [
      // "id",
      "name",
      "group_id",
      "type_collection",
      "flagNotes",
    ],
  };
  if (val == "") {
    renderApprovalList(AllData);
  } else {
    const fusex = new Fuse(AllData, fuseOptions);
    let hasil = fusex.search(val);
    renderApprovalList(hasil);
  }
}

$("#search_approval").keyup(function () {
  let item = $(this).val();
  console.log("item", item);
  cari(item);
});

var viewDetailApproval = (id, flag) => {
  let html_flag = "";
  let width = 800;
  if (flag == "ADD") {
    html_flag = '<span class="badge badge-sm text-bg-danger">NEW</span>';
    width = 800;
  } else {
    html_flag = '<span class="badge badge-sm text-bg-warning ">UPDATE</span>';
    width = 1000;
  }

  var buttons = {
    approve: {
      label: "APPROVE",
      className: "btn-sm btn-success",
      callback: function () {
        submitApproval(id, "APPROVE");
      },
    },
    reject: {
      label: "REJECT",
      className: "btn-sm btn-danger",
      callback: function () {
        submitApproval(id, "REJECT");
      },
    },
    button: {
      label: "Close",
      className: "btn-sm ",
    },
  };

  showCommonDialog(
    width,
    width,
    html_flag + " | APPROVAL USER",
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "user_and_group/user_management_temp/user_view_detail_form_approval?id=" +
      id +
      "&flag=" +
      flag,
    buttons
  );
};

var submitApproval = (id, status) => {
  if (status == "APPROVE") {
    $.get(
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "index.php/user_and_group/user_management_temp/save_user_edit_temp/",
      { id: id, status: status },
      function (data) {
        showFormResponse(data);
      },
      "json"
    );
  } else if (status == "REJECT") {
    $.get(
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "user_and_group/user_management_temp/delete_user/",
      { id_user: id, note: $("#notes").val(), status: status },
      function (data) {
        showFormResponse(data);
      },
      "json"
    );
  }
};

function doApproveUser(id, name) {
  bootbox.confirm({
    title: '<span class="badge bg-success">APPROVE</span>',
    message: "Are you sure you want to Approve " + name + "?",
    buttons: {
      confirm: {
        label: "APPROVE",
        className: "btn-sm btn-outline-success",
      },
      cancel: {
        label: "CLOSE",
        className: "btn-sm btn-outline-secondary",
      },
    },
    callback: function (result) {
      if (result) {
        submitApproval(id, "APPROVE");
      }
    },
  });
}

function doRejectUser(id, name) {
  bootbox.confirm({
    title: '<span class="badge bg-danger">REJECT</span>',
    message: "Are you sure you want to Reject " + name + "?",
    buttons: {
      confirm: {
        label: "REJECT",
        className: "btn-sm btn-outline-danger",
      },
      cancel: {
        label: "CLOSE",
        className: "btn-sm btn-outline-secondary",
      },
    },
    callback: function (result) {
      if (result) {
        submitApproval(id, "REJECT");
      }
    },
  });
}

jQuery(function ($) {
  getData(); // untuk menampilkan data di table nya
});
