jQuery(function($) {
	var grid_selector = "#time-for-looping-list-grid-table";
	var pager_selector = "#time-for-looping-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/time_for_looping_list";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: "100%",
		colNames:['ID', 'NO.', 'BUCKET', 'NO ANSWER', 'BUSY'],
		colModel:[
			{name:'id',index:'id', width:60, hidden:true},
			{name:'list_number', index:'list_number', width:60, align:'right', sorttype:"int"},
			{name:'bucket',index:'bucket', width:260},
			{name:'no_answer',index:'no_answer', width:100},
			{name:'busy',index:'busy', width:100}
		], 

		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		//multiselect: true,
		toolbar: [true, "top"], 
		loadComplete : function() {
			var table = this;
			setTimeout(function(){
				updateActionIcons(table);
				updatePagerIcons(table);
				enableTooltips(table);
			}, 0);
		},
		autowidth: true,
		shrinkToFit: false
	});
})