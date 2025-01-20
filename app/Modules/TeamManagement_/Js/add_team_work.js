var move_list_items=function(sourceid, destinationid){
	$("#"+sourceid+"	option:selected").appendTo("#"+destinationid);
}

//this will move all selected items from source list to destination list
var move_list_items_all=function(sourceid, destinationid){
	$("#"+sourceid+" option").appendTo("#"+destinationid);
}
$(document).ready(function(){
	// loadDataClass(GLOBAL_MAIN_VARS['class_formation_priority']);
	// loadDataClassParameterSetup(GLOBAL_MAIN_VARS['class_formation_priority']);
	
	$("#moveleft_agent").click(function(){
		move_list_items('tom_agent_fc','from_agent_fc');
	});

	$("#moveright_agent").click(function(){
		move_list_items('from_agent_fc','tom_agent_fc');
	});
	
	$("#moveleft_agent_tele").click(function(){
		move_list_items('tom_agent','from_agent');
	});

	$("#moveright_agent_tele").click(function(){
		move_list_items('from_agent','tom_agent');
	});

	
});

