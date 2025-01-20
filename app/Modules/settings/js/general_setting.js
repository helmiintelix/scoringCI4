jQuery(function($) {
	var update_system_setting = function(id, value)
	{
		$.post(GLOBAL_MAIN_VARS["SITE_URL"] + "settings/update_system_setting/", { id: id, value: value }, function(responseText) {
			if(responseText.success) {
				showInfo("Data telah disimpan dan menunggu approval");
				$(grid_selector).trigger('reloadGrid');
			} else{
				showInfo("Gagal.");
			}
		}, "json");
	}	
})