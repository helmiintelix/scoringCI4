$("#assigned_agent_list").empty();
$("#showTeam").hide();
$("#showFildColl").hide();
$("#showAgency").hide();
$("#showAgencyCollector").hide();
$("#btn-team-add").on("click", function (e) {
  e.preventDefault();
  $.ajax({
    url: "assignment/class_assignment/get_list_agent",
    data: {
      team_id: $("#opt-team-list").val(),
    },
    type: "get",
    success: function (msg) {
      try {
        var optArray = [];
        var optArray2 = [];
        var options1 = document.getElementById("assigned_agent_list");

        $.each(msg, function (val, text) {
          console.log("test val");
          console.log(val);
          if ($("#tr_" + val).length == 0) {
            $("#assigned_agent_list").append(
              $("<option></option>").val(val).html(text)
            );
            agentAdd(val);
          }
        });

        // Menghapus opsi yang dipilih dari #opt-team-list
        $("#opt-team-list option:selected").remove();
        $(".chosen-select").trigger("chosen:updated");

        // Mengumpulkan nilai dan teks dari assigned_agent_list
        for (var i = 0; i < options1.length; i++) {
          if (options1[i].value != "") {
            optArray.push(options1[i].value);
            optArray2.push(options1[i].text);
          }
        }

        // Menghindari duplikasi
        var uniqueOptions = {};
        for (var i = 0; i < optArray.length; i++) {
          uniqueOptions[optArray[i]] = optArray2[i];
        }

        $("#assigned_agent_list option").remove();
        for (var key in uniqueOptions) {
          $(
            "<option value='" + key + "'>" + uniqueOptions[key] + "</option>"
          ).appendTo("#assigned_agent_list");
        }
      } catch (e) {
        console.log(e);
      }
    },
    error: function () {
      console.log("connection failed");
    },
  });
});
$("#btn-team-remove").on("click", function (e) {
  e.preventDefault();
  $.ajax({
    url: "assignment/class_assignment/get_list_agent",
    data: {
      team_id: $("#opt-team-list").val(),
    },
    type: "get",
    success: function (msg) {
      try {
        $.each(msg, function (val, text) {
          $('#assigned_agent_list option[value="' + val + '"]').remove();
          agentDelete(val);
        });
      } catch (e) {
        console.log(e);
      }
    },
    error: function () {
      console.log("conenction failed");
    },
  });
});
$("#opt-assigned-to-list").on("change", function () {
  if ($("#opt-assigned-to-list").val() == "1") {
    $("#showTeam").show();
    $("#showFildColl").hide();
    $("#showAgency").hide();
    $("#showAgencyCollector").hide();
  } else if ($("#opt-assigned-to-list").val() == "2") {
    $("#showTeam").hide();
    $("#showFildColl").show();
    $("#showAgency").hide();
    $("#showAgencyCollector").hide();
    $.ajax({
      url: "assignment/class_assignment/getCollectorListFieldColl",
      data: {
        id: class_id,
      },
      type: "get",
      success: function (data) {
        $("#opt-collector-list").empty();
        $.each(data, function (index, item) {
          $("#opt-collector-list").append(
            '<option value="' + item.value + '">' + item.item + "</option>"
          );
        });
        $("#opt-collector-list").trigger("chosen:updated");
      },
    });
  } else if ($("#opt-assigned-to-list").val() == "3") {
    $("#showTeam").hide();
    $("#showFildColl").hide();
    $("#showAgency").show();
    $("#showAgencyCollector").hide();
  } else {
    $("#showTeam").hide();
    $("#showFildColl").hide();
    $("#showAgency").hide();
    $("#showAgencyCollector").hide();
  }
});

function agentAdd(id) {
  //console.log("Add agent",id);
  var arAGent = $("#txt_assigned_agent").val().split("|");
  console.log("arAGent", arAGent);

  if (arAGent[0] == "") {
    arAGent[0] = id;
  } else {
    arAGent.push(id);
  }

  $("#txt_assigned_agent").val(arAGent.join("|"));

  $.ajax({
    url: "assignment/class_assignment/get_agent_detail",
    data: {
      agent_id: id,
    },
    type: "get",
    dataType: "json",
    success: function (val) {
      try {
        count_agent++;
        var markup =
          '<tr id="tr_' +
          val.id +
          '"><td>' +
          count_agent +
          "</td><td class='agent_id_assigned' >" +
          val.id +
          "</td><td>" +
          val.name +
          "</td><td>" +
          val.group_id +
          "</td><td style='display:none'><input type='number' min='0' max='100' class='ass_weight' value='0'/>" +
          "</td><td><button onclick=\"agentDelete('" +
          val.id +
          '\')" class="btn btn-danger btn-sm"> delete</button></td></tr>';
        $("#agent_table_body").append(markup);
      } catch (e) {
        console.log(e);
      }
    },
    error: function () {
      console.log("connection failed");
    },
  });
  return false;
}

function agentDelete(id) {
  console.log("delete agent", id);
  count_agent--;
  var arAGent = $("#txt_assigned_agent").val().split("|");
  var newArAgent = arAGent.filter(function (value, index, arr) {
    return value != id;
  });

  console.log("arAGent", arAGent);
  console.log("newArAgent", newArAgent);

  $("#txt_assigned_agent").val(newArAgent.join("|"));
  $("#tr_" + id).remove();

  var table = document.getElementById("table_selected_user");

  for (var i = 1, row; (row = table.rows[i]); i++) {
    //iterate through rows
    //rows would be accessed using the "row" variable assigned in the for loop
    row.cells[0].innerText = i;
  }

  return false;
}
$("#btn-collector-assign").on("click", function () {
  console.log($("#opt-collector-list option:selected").val());
  console.log("2-" + $("#opt-collector-list").val());
  agentAdd($("#opt-collector-list option:selected").val());
  /*
        if($('#opt-collector-list option:selected').val() == 'REQ_USR'){
        	$('#btn-collector-assign').attr('disabled','disabled');
        }
        else if($('#opt-collector-list option:selected').val() == 'LAST_AGENT'){
        	$('#btn-collector-assign').attr('disabled','disabled');
        }
        */
  $("#assigned_agent_list").append(
    $("<option></option>")
      .val($("#opt-collector-list").val())
      .html($("#opt-collector-list option:selected").text())
  );
  $("#opt-collector-list option:selected").remove();

  $(".chosen-select").trigger("chosen:updated");

  return false;
});
$(".ass_weight").hide();
$("#opt-ac-agent").on("change", function () {
  console.log($("#opt-ac-agent").val());
  if (
    $("#opt-ac-agent").val() == "ROUND_ROBIN" ||
    $("#opt-ac-agent").val() == "REVERSE_ROUND_ROBIN"
  ) {
    console.log("hide");
    $(".ass_weight").hide();
  } else {
    console.log("show");
    $(".ass_weight").show();
  }

  $("#LABEL_REVERSE_ROUND_ROBIN").hide();
  $("#LABEL_ROUND_ROBIN").hide();
  if ($("#opt-ac-agent").val() == "ROUND_ROBIN") {
    console.log("hide");
    $("#LABEL_ROUND_ROBIN").show();
  } else if ($("#opt-ac-agent").val() == "REVERSE_ROUND_ROBIN") {
    console.log("show");
    $("#LABEL_REVERSE_ROUND_ROBIN").show();
  }
});
$("#opt-ac-agent").change();
function first_load() {
  $(".chosen-select").chosen({
    width: "100%",
  });
}

setTimeout(first_load, 300);
