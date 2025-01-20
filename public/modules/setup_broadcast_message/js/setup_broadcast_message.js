$('#saveMsg').click(function () {
    $.ajax({
        type: "POST",
        url: GLOBAL_MAIN_VARS["SITE_URL"] +
            "settings/broadcast_message_setup/save_broadcast_message_setup",
        data: {
            message: $('#broadcastMsg').val(),
            csrf_security: $('#csrf_token').val()
        },
        dataType: "json",
        success: function (msg) {
            if (msg.newCsrfToken) {
                $("#csrf_token").val(msg.newCsrfToken);
            }
            if (msg.success) {
                $("#scroll-text").html($("#broadcastMsg").val());
                showInfo(msg.message);
                if (msg.notification_id) {
                    sendNotification(msg.notification_id);
                }
            } else {
                showInfo(msg.message);
                return false;
            }
        },
    });
})