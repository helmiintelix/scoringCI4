var loadDataClassParameterSetup=function(team_id){
	$.ajax({
		type: "POST",
		url: GLOBAL_MAIN_VARS["SITE_URL"] + "team_management/team_work/get_team_setup",
		async: false,
		dataType: "json",
		data: {team_id:team_id},
		success: function(msg){
			
			if(msg.success==false){
				$("p.error").html(data['error']);
				$("p.error").slideDown("fast");
			}
			
			if(msg.success==true){
				
				$.each(msg.data,function(index,text){
					$('#team_name').val(text.team_name);
					$('#coord_list').val(text.team_leader);
					if (text.supervisor == 0) {
						text.supervisor = '';
					}
					$('#spv_list').val(text.supervisor);
					$('#description').val(text.description);
					//RANGE
					
					$.each(text.agent_list.split('|'),function(idx,val){
						// console.log(val);
						$('#from_agent option[value='+val+']').attr('selected','selected');
						// $('#from_agent_fc option[value='+val+']').attr('selected','selected');
					});
					move_list_items('from_agent','tom_agent');
					// move_list_items('from_agent_fc','tom_agent_fc');
				});
			}
		},
		error: function(){
			alert("Failed: " + GLOBAL_MAIN_VARS["SITE_URL"] +  "admin_main/get_class_parameter_setup");
		}
	}); 
}

var move_list_items=function(sourceid, destinationid){
	$("#"+sourceid+"	option:selected").appendTo("#"+destinationid);
}

//this will move all selected items from source list to destination list
var move_list_items_all=function(sourceid, destinationid){
	$("#"+sourceid+" option").appendTo("#"+destinationid);
}
$(document).ready(function(){
	loadDataClassParameterSetup(team_id);
	
	$("#moveleft_agent").click(function(){
		move_list_items('tom_agent','from_agent');
	});

	$("#moveright_agent").click(function(){
		move_list_items('from_agent','tom_agent');
	});
	
	$("#moveleft_agent_fc").click(function(){
		move_list_items('tom_agent_fc','from_agent_fc');
	});

	$("#moveright_agent_fc").click(function(){
		move_list_items('from_agent_fc','tom_agent_fc');
	});

	
});