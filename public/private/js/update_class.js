var loadClassById = function(class_id){
    $.ajax({
            type: "POST",
            url:  GLOBAL_MAIN_VARS["SITE_URL"] +"/add_class/get_class",
            async: false,
            data: {class_id:class_id},
            dataType: "json",
            success: function(msg){
                
                if(msg.success==false)
                {
                   
                }
                if(msg.success==true)
                {
                   
                    $("#class_id").val(msg.data.class_id);
					$("#class_name").val(msg.data.name);
					
					if(msg.data.class_type == "NON_REGULAR" ){
						$("#class_type").click();
					}
					
                    $('#is_active option[value='+msg.data.is_active+']').prop('selected','selected');
                    
                }
                
            },
            error: function(){
                alert("load data class fail connection: please contact your administrator");
            }

    });
}

$(document).ready(function(){
	
	loadClassById(GLOBAL_MAIN_VARS['class_id']);
	
});