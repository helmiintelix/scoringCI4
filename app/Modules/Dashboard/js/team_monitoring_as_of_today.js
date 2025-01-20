function get_data_monitoring() {
	$('#content_team_monitoring').html('');
	$.ajax({
		url: GLOBAL_MAIN_VARS["SITE_URL"] + "/dashboard/get_team_monitoring_as_of_today",
		data: { team_id: $('#team_id').val() },
		type: 'post',
		dataType: 'json',
		success: function (msg) {
			console.log(msg);
			var content = '';
			if (msg.length == 0) {
				content = "<td colspan='18'><center><i>[ empty ]</i></center></td>";
			}

			$.each(msg, function (i, val) {
				// console.log(val['team_name']);
				content += "<tr style='text-align: center;'>";
				// content += "<td>" + val['team_id'] + "</td>";
				content += "<td>" + val['team_name'] + "</td>";
				content += "<td>" + val['total_agent'] + "</td>";
				content += "<td>" + val['average_talktime'] + "</td>";
				content += "<td>" + val['average_acw'] + "</td>";
				content += "<td>" + val['total_called'] + "</td>";
				content += "<td>" + val['contact'] + "</td>";
				content += "<td>" + val['not_contact'] + "</td>";
				content += "<td>" + val['appointment'] + "</td>";
				content += "<td>" + val['ptp'] + "</td>";
				content += "<td>" + val['no_ptp'] + "</td>";
				content += "<td>" + val['special_case'] + "</td>";
				content += "<td>" + val['no_status'] + "</td>";
				content += "<td>" + val['contact_from_data_called'] + " %</td>";
				content += "<td>" + val['not_contact_from_data_called'] + " %</td>";
				content += "<td>" + val['ptp_from_contact'] + " %</td>";
				content += "<td>" + val['ptp_from_data_called'] + " %</td>";
				content += "<td>" + val['no_status_from_contact'] + " %</td>";
				content += "</tr>";
			});
			$('#content_team_monitoring').html(content);


		}
	});
}
// alert('test');
get_data_monitoring();
var interval = 60 * 5; //detik
setInterval(function () {
	get_data_monitoring();
	console.log('get monitoring..');
}, interval * 1000);


function download_report() {
	location.href = GLOBAL_MAIN_VARS["SITE_URL"] + "/dashboard/download_team_of_today?dteReport=" + yesterday;
}