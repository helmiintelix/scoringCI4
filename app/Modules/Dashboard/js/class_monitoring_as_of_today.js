function get_data_monitoring_class() {
	$('#content_class_monitoring').html('');
	$.ajax({
		url: GLOBAL_MAIN_VARS["SITE_URL"] + "dashboard/get_class_monitoring_as_of_today",
		data: { bucket_name: $('#bucket_name').val(), class_id: $('#class_id').val() },
		type: 'post',
		async: false,
		dataType: 'json',
		success: function (msg) {
			// console.log(msg);
			var content = '';
			if (msg.length == 0) {
				content = "<td colspan='18'><center><i>[ empty ]</i></center></td>";
			}
			$.each(msg, function (i, val) {
				// console.log(val['team_name']);
				content += "<tr>";
				content += "<td>" + val['class_id'] + "</td>";
				content += "<td>" + val['class_name'] + "</td>";
				content += "<td>" + val['bucket_name'] + "</td>";
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
				content += "<tr>";
			});
			$('#content_class_monitoring').html(content);


		}
	});
}
// alert('test');
$(document).ready(function () {
	get_data_monitoring_class();
	var interval = 60 * 5; //detik
	setInterval(function () {
		get_data_monitoring_class();
		console.log('get monitoring..');
	}, interval * 1000);
})