var content_before = "";
function get_data_monitoring_bucket() {
  $.ajax({
    url:
      GLOBAL_MAIN_VARS["SITE_URL"] +
      "dashboard/bucket_monitoring_as_of_today/get_bucket_monitoring_as_of_today" +
      classification,
    data: {
      bucket_name: $("#bucket_name").val(),
      // product: $("#product").val(),
    },
    type: "get",
    dataType: "json",
    success: function (msg) {
      console.log(msg);
      if (msg.data.status == true) {
        var content = "";
        console.log("true");
        $("#content_bucket_monitoring").html("");
        if (msg.data.rows === 0) {
          content = "<td colspan='18'><center><i>[ empty ]</i></center></td>";
        }
        $.each(msg.data.rows, function (i, val) {
          content += "<tr>";
          content += "<td>" + val["bucket_id"] + "</td>";
          content += "<td>" + val["bucket_name"] + "</td>";
          content += "<td>" + val["product"] + "</td>";
          // content +="<td>"+val['total_agent']+"</td>";
          content += "<td>" + val["average_talktime"] + "</td>";
          content += "<td>" + val["average_acw"] + "</td>";
          content += "<td>" + val["total_called"] + "</td>";
          content += "<td>" + val["contact"] + "</td>";
          content += "<td>" + val["not_contact"] + "</td>";
          content += "<td>" + val["appointment"] + "</td>";
          content += "<td>" + val["ptp"] + "</td>";
          content += "<td>" + val["no_ptp"] + "</td>";
          content += "<td>" + val["special_case"] + "</td>";
          content += "<td>" + val["no_status"] + "</td>";
          content += "<td>" + val["contact_from_data_called"] + " %</td>";
          content += "<td>" + val["not_contact_from_data_called"] + " %</td>";
          content += "<td>" + val["ptp_from_contact"] + " %</td>";
          content += "<td>" + val["ptp_from_data_called"] + " %</td>";
          content += "<td>" + val["no_status_from_contact"] + " %</td>";
          content += "<tr>";
        });
        $("#content_bucket_monitoring").html(content);
        content_before = content;
      } else {
        $("#content_bucket_monitoring").html(content_before);
      }
    },
  });
}
get_data_monitoring_bucket();

var interval = 60 * 5; //detik
setInterval(function () {
  get_data_monitoring_bucket();
  console.log("get monitoring..");
}, interval * 1000);
