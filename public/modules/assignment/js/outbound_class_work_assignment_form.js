var loadAgentAssignment = function (class_id) {
    $.ajax({
        type: "POST",
        url: GLOBAL_MAIN_VARS["SITE_URL"] + "assignment/assignment/get_class_work_assignment_by",
        async: false,
        dataType: "json",
        data: { class_id: class_id },
        success: function (msg) {

            //if(msg.success==false)
            //{
            //$("p.error").html(data['error']);
            //$("p.error").slideDown("fast");
            //}
            if (msg.success == true) {
                //$("#label_class_name").html("<strong>"+msg.data[0].name+"</strong>");
                //$("#class_id").val(msg.data[0].class_mst_id);
                //loadOutboundSelect();
                //alert(msg.data[0].outbound_team);
                $.each(msg.data, function (val, text) {
                    //alert(text.outbound_team);
                    $('#outbound_select option[value=' + text.outbound_team + ']').attr('selected', 'selected');
                });
            }

        },
        error: function () {
            alert("load Data Agent fail connection: please contact your administrator");
        }

    });
};
/**
* get_class_information
*/
var loadClassInformation = function (class_id) {
    $.ajax({
        type: "POST",
        url: GLOBAL_MAIN_VARS["SITE_URL"] + "assignment/assignment/get_class_information",
        async: false,
        dataType: "json",
        data: { class_id: class_id },
        success: function (msg) {

            if (msg.success == false) {
                $("p.error").html(data['error']);
                $("p.error").slideDown("fast");
            }
            if (msg.success == true) {

                $("#label_class_name").html("<strong>" + msg.data.name + "</strong>");
                $("#class_id").val(msg.data.class_id);
                // $("#group_outbound_select").val(msg.data.group_telephony);

            }

        },
        error: function () {
            alert("load Data Agent fail connection: please contact your administrator");
        }

    });
}
var loadOutboundSelect = function (class_id) {
    $.ajax({
        type: "POST",
        url: GLOBAL_MAIN_VARS["SITE_URL"] + "assignment/assignment/get_outbound_team_class_work_assignment/",
        async: false,
        dataType: "json",
        data: { class_id: class_id },
        success: function (msg) {
            if (msg.success == true) {

                $.each(msg.data, function (val, text) {
                    $('#outbound_select').append(
                        $('<option></option>').val(text.id).html(text.outbound_team_name)
                    );
                });
            } else if (msg.success == false) {
                warningDialog(300, 100, "Warning!", "Tidak Ada Team yang idle!");
            }
        },
        error: function () {
            alert("dialing select fail connection: please contact your administrator");
        }
    });

}

$(document).ready(function () {
    loadOutboundSelect(GLOBAL_MAIN_VARS['class_id']);
    loadClassInformation(GLOBAL_MAIN_VARS['class_id']);
    loadAgentAssignment($("#class_id").val());
    /* $("input:radio[@name=inb_Call]").click(function() { 
           var checkedvalue = $(this).val(); 
               if(checkedvalue==1){
                    
                    $(":radio[value='0']").removeAttr("checked"); 
               }else{
                       $(":radio[value='1']").removeAttr("checked"); 
               }
       }); */
});