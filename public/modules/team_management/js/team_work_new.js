var selr;
var selected_data;
var AllData = new Array();
var AllDataUser = new Array();

function getData() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "team_management/team_work/get_team_work_list_ava",
    dataType: "json",
    type: "get",
    success: function (msg) {
      AllData = msg.data.data;
      if (AllData.length == 0) {
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
    // console.log("test",val)
    html += '<div class="col">';
    html += ' <div class="card">';
    if (val.is_active == "0") {
      html += ' <div  class="p-2 bg-secondary">'; //#acf3a7
    } else {
      html += ' <div  class="p-2 bg-success">'; //#acf3a7
    }

    // if (val.notes.toUpperCase() == 'ADD') {
    //   html += '<span class="badge badge-sm text-bg-danger float-end ">NEW</span>';
    // } else {
    //   html += '<span class="badge badge-sm text-bg-warning float-end ">UPDATE</span>';
    // }

    // if (val.type_collection.toUpperCase() == 'TELECOLL') {
    //   html += '<span class="badge badge-sm text-bg-success float-end  me-1">TELECOLL</span><br></br>';
    // } else if (val.type_collection.toUpperCase() == 'FIELDCOLL') {
    //   html += '<span class="badge badge-sm text-bg-primary float-end  me-1">FIELDCOLL</span><br></br>';
    // } else {
    //   html += '<span class="badge badge-smfloat-end me-1"></span><br></br>';
    // }

    html += "<center>";
    html +=
      '<b class="card-title text-center">' +
      val.team_name.toUpperCase() +
      "</b>";
    // html += '<img src="" id="pic_profile" onClick="selectImage()" class="rounded-circle img-thumbnail" style="width:100px;height:100px;display:block;cursor:pointer"> ';
    html += "</center>";
    html += "</div>";

    html += '<div class="card-body text-center">';
    html += "<center>";
    html += '<div class="user-group" style="width:100px;display:flex;">';
    var color = new Array();
    color = {
      1: "primary",
      2: "success",
      3: "danger",
      4: "warning",
      5: "info",
      6: "dark",
    };

    var jumlah = 0;
    var jumlahshow = 0;
    let arrUser = val.agent_list != null ? val.agent_list.split("|") : [];
    $.each(arrUser, function (i, val) {
      if (i > 3) {
        jumlah = i + 1;
      } else if (i == 0) {
        // html += '<div class="border border-white bg-'+color[Math.floor(Math.random() * 6)+1]+' rounded-circle" style="font-size:10px;width:27px;height:27px;position:relative;margin-left:-5px;">';
        // html += '<center><div style="display:flex;color:#ffffffe6;align-items:center;justify-content:center;">';
        // // html += '<span onmouseenter="onmouse(\''+val+'\')" title="'+user[val]['name']+'" style="margin-top:6px;">'+user[val]['avatar']+'</span>';
        // html += '</div></center>';
        // html += '</div>';
      } else {
        html +=
          '<div class="border border-white bg-' +
          color[Math.floor(Math.random() * 6) + 1] +
          ' rounded-circle" style="font-size:10px;width:27px;height:27px;position:relative;margin-left:-5px;">';
        html +=
          '<center><div style="display:flex;color:#ffffffe6;align-items:center;justify-content:center;">';
        html +=
          "<span onmouseenter=\"onmouse('" +
          val +
          '\')" title="' +
          user[val]["name"] +
          '" style="margin-top:6px;">' +
          user[val]["avatar"] +
          "</span>";
        html += "</div></center>";
        html += "</div>";
      }

      jumlahshow++;

      // console.log(jumlahshow);
      // onmouseleave="outmouse(this)"
    });

    if (jumlah > 3) {
      html +=
        '<div class="border border-white bg-secondary rounded-circle" style="font-size:10px;width:27px;height:27px;position:relative;margin-left:-5px;">';
      html +=
        '<div style="display:flex;color:#ffffffe6;align-items:center;justify-content:center;">';
      html +=
        "<span onmouseenter=\"onmousealluser('" +
        val.agent_list +
        '\')" title="' +
        jumlah +
        '" style="margin-top:6px;">+' +
        jumlah +
        "</span>";
      html += "</div>";
      html += "</div>";
    } else {
      if (jumlahshow == 1) {
      } else {
        html +=
          '<div class="border border-white bg-secondary rounded-circle" style="font-size:10px;width:27px;height:27px;position:relative;margin-left:-5px;">';
        html +=
          '<div style="display:flex;color:#ffffffe6;align-items:center;justify-content:center;">';
        html +=
          "<span onmouseenter=\"onmousealluser('" +
          val.agent_list +
          '\')" title="VIEW ALL" style="margin-top:6px;">...</span>';
        html += "</div>";
        html += "</div>";
      }
    }
    console.log("jumlah" + jumlah);
    html += "</div>";
    html += "</center>";
    html += "<br>";
    html +=
      '<i class="card-subtitle mb-2 text-muted "> <small class="text-muted ">TEAM LEADER</small></i>';
    html += '<span class="text-muted "> | </span>';
    html += '<i class="text-muted ">' + val.team_leader + "</i>";

    html += "<br>";
    html +=
      '<i class="card-subtitle mb-2 text-muted "> <small class="text-muted ">SUPERVISOR</small></i>';
    html += '<span class="text-muted "> | </span>';
    html += '<i class="text-muted ">' + val.supervisor + "</i>";
    html += "<br>";

    html += '<div class="btn-group" role="group" aria-label="Basic example">';
    // html += '<button type="button" class="btn btn-sm btn-outline-primary" onClick="viewDetail(\'' + val.team_id + '\',\'' + val.team_name.toUpperCase() + '\')" id="btn-approve"><i class="bi bi-eye"></i>View</button>';
    html +=
      '<button type="button" class="btn btn-sm btn-outline-success" onClick="doEditTeam(\'' +
      val.team_id +
      '\')" id="btn-approve"><i class="bi bi-check2"></i>Edit</button>';
    html +=
      '<button type="button" class="btn btn-sm btn-outline-danger" onClick="doDeleteTeam(\'' +
      val.team_id +
      "','" +
      val.team_name.toUpperCase() +
      '\')" id="btn-reject"><i class="bi bi-x-lg"></i>Disable</button>';
    html += "</div>";
    html += "</div>";
    html += '<div class="card-footer text-center">';
    // if (val.notes.toUpperCase() == 'ADD') {
    html += '<i><small class="text-muted ">' + val.created_by + "</small></i>";
    html += '<span class="text-muted "> | </span>';
    html +=
      '<small class="text-muted" id="show_time_' +
      val.team_id +
      '">' +
      timeDif(val.created_time) +
      "</small>";
    html +=
      '<input type="hidden"  class="time_notification" show_time = "show_time_' +
      val.team_id +
      '"  value="' +
      val.created_time +
      '"/>';
    // } else {
    //   html += '<i><small class="text-muted ">from: ' + val.updated_by_name + '</small></i>';
    //   html += '<span class="text-muted "> | </span>';
    //   html += '<small class="text-muted" id="show_time_' + val.id + '">' + timeDif(val.updated_time) + '</small>';
    //   html += '<input type="hidden"  class="time_notification" show_time = "show_time_' + val.id + '"  value="' + val.updated_time + '"/>';
    // }
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
    // getDataUser();
  } else {
    showInfo(responseText.message);
    return false;
  }
};

function cari(val) {
  var fuseOptions = {
    // isCaseSensitive: false,
    // includeScore: false,
    // shouldSort: true,
    // includeMatches: false,
    // findAllMatches: false,
    // minMatchCharLength: 1,
    // location: 0,
    threshold: 0.2,
    // distance: 100,
    // useExtendedSearch: false,
    // ignoreLocation: false,
    // ignoreFieldNorm: false,
    // fieldNormWeight: 1,
    keys: [
      // "id",
      "team_name",
      "team_leader",
      "supervisor",
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

var viewDetail = (id, flag) => {
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
    html_flag + " | Detail Team",
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "user_and_group/user_view_detail_form_approval/" +
      id +
      "/" +
      flag,
    buttons
  );
};

var submitApproval = (id, status) => {
  if (status == "APPROVE") {
    $.post(
      GLOBAL_MAIN_VARS["SITE_URL"] +
        "index.php/user_and_group/save_user_edit_temp/",
      { id: id, status: status },
      function (data) {
        showFormResponse(data);
      },
      "json"
    );
  } else if (status == "REJECT") {
    $.post(
      GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/delete_user/",
      { id_user: id, note: $("#notes").val(), status: status },
      function (data) {
        showFormResponse(data);
      },
      "json"
    );
  }
};

var doEditTeam = (id) => {
  var buttons = {
    approve: {
      label: "APPROVE",
      className: "btn-sm btn-success",
      callback: function () {
        var options = {
          url:
            GLOBAL_MAIN_VARS["SITE_URL"] +
            "team_management/team_work/edit_team",
          type: "post",
          beforeSubmit: jqformValidate,
          success: showFormResponse,
          dataType: "json",
        };
        $("#tom_agent option").prop("selected", true);
        if (jqformValidate() == false) {
          return false;
        }
        $("form").ajaxSubmit(options).delay(3000);
      },
    },
    button: {
      label: "Close",
      className: "btn-sm ",
    },
  };

  showCommonDialog(
    800,
    500,
    "Edit Team",
    GLOBAL_MAIN_VARS["SITE_URL"] +
      "team_management/team_work/edit_team_form/?team_id=" +
      id,
    buttons
  );
};

function doDeleteTeam(id, name) {
  bootbox.confirm(
    "Are you sure you want to deactive " + name + " this team?",
    function (result) {
      if (result) {
        $.post(
          GLOBAL_MAIN_VARS["SITE_URL"] + "team_management/team_work/del_team",
          { team_id: id },
          function (data) {
            if (data.success == true) {
              showInfo(data.message);
              $(grid_selector).trigger("reloadGrid");
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
  // bootbox.confirm({
  //   title: '<span class="badge bg-danger">Disable</span>',
  //   message: "Are you sure you want to disable " + id + "?",
  //   buttons: {
  //     confirm: {
  //       label: 'DISABLE',
  //       className: 'btn-sm btn-outline-danger'
  //     },
  //     cancel: {
  //       label: 'CLOSE',
  //       className: 'btn-sm btn-outline-secondary'
  //     }
  //   },
  //   callback: function (result) {
  //     if (result) {
  //       submitApproval(id, 'REJECT');
  //     }
  //   }
  // });
}

$(document).ready(function () {
  // gridOptions.api.hideOverlay();
  console.log("ready");
  getData(); // untuk menampilkan data di table nya
  //   getDataUser();
});
