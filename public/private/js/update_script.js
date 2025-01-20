/**
* jquery main program already paid
*/

var loadparameterby = function(parameter_id){
	$.ajax({
		type: "POST",
		url: GLOBAL_MAIN_VARS["SITE_URL"] +"agent_script/get_agent_script_by",
		async: false,
		data : {parameter_id:parameter_id},
		dataType: "json",
		success: function(msg){
				
			if(msg.success==false)
			{
				 
			}
			if(msg.success==true)
			{
				 $("#script_id").val(msg.data[0].id);
				 $("#subject").val(msg.data[0].subject); 
				 $("#script_content").val(msg.data[0].script); 
			}
		},
		error: function(){
				alert("load parameter fail connection: please response your administrator");
		}

	});
}

$(document).ready(function(){
	loadparameterby(GLOBAL_MAIN_VARS['script_id']);
});