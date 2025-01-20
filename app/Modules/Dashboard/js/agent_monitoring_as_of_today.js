function get_data_monitoring_agent(){
	$('#content_agent_monitoring').html('');
	$.ajax({
		url:site_url+"/dashboard/get_agent_monitoring_as_of_today",
		data:{agent_id:$('#agent_id').val()},
		type: 'post',
		async:false,
		dataType : 'json',
		success: function(msg){
			// console.log(msg);
			var content ='';
			if(msg.length==0){
				content = "<td colspan='24'><center><i>[ empty ]</i></center></td>";
			}
			
			$.each(msg,function(i,val){
				// console.log(val['team_name']);
				content +="<tr>";
					content +="<td>"+val['agent_id']+"</td>";
					content +="<td>"+val['name']+"</td>";
					content +="<td>"+val['last_logout']+"</td>";
					content +="<td>"+val['first_login']+"</td>";
					content +="<td>"+val['break']+"</td>";
					content +="<td>"+val['pray']+"</td>";
					content +="<td>"+val['coaching']+"</td>";
					content +="<td>"+val['training']+"</td>";
					content +="<td>"+val['meeting']+"</td>";
					content +="<td>"+val['not_work_time']+"</td>";
					content +="<td>"+val['average_talktime']+"</td>";
					content +="<td>"+val['average_acw']+"</td>";
					content +="<td>"+val['account_assigned']+"</td>";
					content +="<td>"+val['contact']+"</td>";
					content +="<td>"+val['not_contact']+"</td>";
					content +="<td>"+val['appointment']+"</td>";
					content +="<td>"+val['no_status']+"</td>";
					content +="<td>"+val['ptp']+"</td>";
					content +="<td>"+val['no_ptp']+"</td>";
					content +="<td>"+val['special_case']+"</td>";
					content +="<td>"+val['contact_from_aact_assigned']+" %</td>";
					content +="<td>"+val['not_contact_from_acct_assigned']+" %</td>";
					content +="<td>"+val['ptp_from_contact']+" %</td>";
					content +="<td>"+val['ptp_from_acct_assigned']+" %</td>";
				content +="<tr>";
			});
				
				// if(nilai%2==0)
				// {
					// $('#content_agent_monitoring_2').html(content);
					// // document.write(nilai+" adalah bilangan genap <br>" );
				// } else
				// {
					// // document.write(nilai+" adalah bilangan ganjil <br>" );
					$('#content_agent_monitoring').html(content);
				// }
					
		}
	});
}
// alert('test');

$(document).ready(function() {
	get_data_monitoring_agent();
	var interval = 60*3; //detik
	var nilai=1;
	setInterval(function(){ 
		get_data_monitoring_agent(); 
		nilai++;
		// console.log('get monitoring..');
	}, interval*1000);
});
	