var arr_id = "";

jQuery(function($) {
	var grid_selector = "#call-list-grid-table";
	var pager_selector = "#call-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/call_history";

	jQuery(grid_selector).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','Call Date','Call To', 'Talk With','Other Name','Call Result','PTP Date','PTP Amount','CREATED_BY'],
		colModel:[
			{name:'id',index:'a.id', width:60, hidden:true},
			{name:'created_time', index:'date(a.created_time)', width:200,searchoptions:
				{
					dataInit: function(el) {
						$(el).datepicker({
							changeYear: true,
							changeMonth: true,
							showButtonPanel: true,
							//dateFormat: 'yy-mm-dd',
							format: 'yyyy-mm-dd',
						}).on("changeDate",function(e){
                            setTimeout(function () {
                                $(grid_selector)[0].triggerToolbar();
                            }, 100);
							
						});
					}
				}
			},
			{name:'customer_no', index:'a.customer_no', width:180},
			{name:'account_no', index:'a.account_no', width:180},
			{name:'other_name', index:'a.other_name', width:180},
			{name:'call_status', index:'a.call_status', width:180},
			{name:'ptp_date', index:'a.ptp_date', width:180},
			{name:'ptp_amount', index:'a.ptp_amount', width:180},
			{name:'created_by', index:'a.created_by', width:180},
		], 
		sortname: 'created_time',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		caption:"CALL History",
		multiselect: false,
		onSelectRow: function(data){
			var arr = $(this).getRowData(data);
			arr_id = arr.id;
		},
		toolbar: [true, "top"], 
		loadComplete : function() {
			var table = this;
			setTimeout(function(){
				updateActionIcons(table);
				updatePagerIcons(table);
				enableTooltips(table);
			}, 0);
		},
		autowidth: false,
		shrinkToFit: false
	});
	
jQuery(grid_selector).jqGrid('filterToolbar',{defaultSearch:true,stringResult:true});		
	//navButtons
	jQuery(grid_selector).jqGrid('navGrid', pager_selector,
		{ 	//navbar options
			edit: false,
			editicon : 'icon-pencil blue',
			add: false,
			addicon : 'icon-plus-sign purple',
			del: false,
			delicon : 'icon-trash red',
			search: true,
			searchicon : 'icon-search orange',
			refresh: true,
			refreshicon : 'icon-refresh green',
			view: false,
			viewicon : 'icon-zoom-in grey',
		},
		{
			//search form
			recreateForm: true,
			afterShowSearch: function(e){
				var form = $(e[0]);
				form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
				style_search_form(form);
			},
			afterRedraw: function(){
				style_search_filters($(this));
			}
			,
			multipleSearch: true,
			/**
			multipleGroup:true,
			showQuery: true
			*/
		}
	)
	
	
	//visit
	var grid_selector_visit = "#visit-list-grid-table";
	var pager_selector_visit = "#visit-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/visit_history";

	jQuery(grid_selector_visit).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','Visit Date','Visit To', 'Meet With','Other Name','Visit Result','PTP Date','PTP Amount','CREATED_BY'],
		colModel:[
			{name:'id',index:'a.id', width:60, hidden:true},
			{name:'created_time', index:'date(a.created_time)', width:200,searchoptions:
				{
					dataInit: function(el) {
						$(el).datepicker({
							changeYear: true,
							changeMonth: true,
							showButtonPanel: true,
							//dateFormat: 'yy-mm-dd',
							format: 'yyyy-mm-dd',
						}).on("changeDate",function(e){
                            setTimeout(function () {
                                $(grid_selector_visit)[0].triggerToolbar();
                            }, 100);
							
						});
					}
				}
			},
			{name:'customer_no', index:'a.customer_no', width:180},
			{name:'account_no', index:'a.account_no', width:180},
			{name:'other_name', index:'a.other_name', width:180},
			{name:'call_status', index:'a.call_status', width:180},
			{name:'ptp_date', index:'a.ptp_date', width:180},
			{name:'ptp_amount', index:'a.ptp_amount', width:180},
			{name:'created_by', index:'a.created_by', width:180},
		], 
		sortname: 'created_time',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector_visit,
		altRows: true,
		caption:"VISIT History",
		multiselect: false,
		onSelectRow: function(data){
			var arr = $(this).getRowData(data);
			arr_id = arr.id;
		},
		toolbar: [true, "top"], 
		loadComplete : function() {
			var table = this;
			setTimeout(function(){
				updateActionIcons(table);
				updatePagerIcons(table);
				enableTooltips(table);
			}, 0);
		},
		autowidth: false,
		shrinkToFit: false
	});
	
jQuery(grid_selector_visit).jqGrid('filterToolbar',{defaultSearch:true,stringResult:true});		
	//navButtons
	jQuery(grid_selector_visit).jqGrid('navGrid', pager_selector_visit,
		{ 	//navbar options
			edit: false,
			editicon : 'icon-pencil blue',
			add: false,
			addicon : 'icon-plus-sign purple',
			del: false,
			delicon : 'icon-trash red',
			search: true,
			searchicon : 'icon-search orange',
			refresh: true,
			refreshicon : 'icon-refresh green',
			view: false,
			viewicon : 'icon-zoom-in grey',
		},
		{
			//search form
			recreateForm: true,
			afterShowSearch: function(e){
				var form = $(e[0]);
				form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
				style_search_form(form);
			},
			afterRedraw: function(){
				style_search_filters($(this));
			}
			,
			multipleSearch: true,
			/**
			multipleGroup:true,
			showQuery: true
			*/
		}
	)
	
	
	//scoring
	var grid_selector_visit = "#scoring-list-grid-table";
	var pager_selector_visit = "#scoring-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/scoring_list";

	jQuery(grid_selector_visit).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ID','Visit Date','Visit To', 'Meet With','Other Name','Visit Result','PTP Date','PTP Amount','CREATED_BY'],
		colModel:[
			{name:'id',index:'a.id', width:60, hidden:true},
			{name:'created_time', index:'date(a.created_time)', width:200,searchoptions:
				{
					dataInit: function(el) {
						$(el).datepicker({
							changeYear: true,
							changeMonth: true,
							showButtonPanel: true,
							//dateFormat: 'yy-mm-dd',
							format: 'yyyy-mm-dd',
						}).on("changeDate",function(e){
                            setTimeout(function () {
                                $(grid_selector_visit)[0].triggerToolbar();
                            }, 100);
							
						});
					}
				}
			},
			{name:'customer_no', index:'a.customer_no', width:180},
			{name:'account_no', index:'a.account_no', width:180},
			{name:'other_name', index:'a.other_name', width:180},
			{name:'call_status', index:'a.call_status', width:180},
			{name:'ptp_date', index:'a.ptp_date', width:180},
			{name:'ptp_amount', index:'a.ptp_amount', width:180},
			{name:'created_by', index:'a.created_by', width:180},
		], 
		sortname: 'created_time',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector_visit,
		altRows: true,
		caption:"VISIT History",
		multiselect: false,
		onSelectRow: function(data){
			var arr = $(this).getRowData(data);
			arr_id = arr.id;
		},
		toolbar: [true, "top"], 
		loadComplete : function() {
			var table = this;
			setTimeout(function(){
				updateActionIcons(table);
				updatePagerIcons(table);
				enableTooltips(table);
			}, 0);
		},
		autowidth: false,
		shrinkToFit: false
	});
	
jQuery(grid_selector_visit).jqGrid('filterToolbar',{defaultSearch:true,stringResult:true});		
	//navButtons
	jQuery(grid_selector_visit).jqGrid('navGrid', pager_selector_visit,
		{ 	//navbar options
			edit: false,
			editicon : 'icon-pencil blue',
			add: false,
			addicon : 'icon-plus-sign purple',
			del: false,
			delicon : 'icon-trash red',
			search: true,
			searchicon : 'icon-search orange',
			refresh: true,
			refreshicon : 'icon-refresh green',
			view: false,
			viewicon : 'icon-zoom-in grey',
		},
		{
			//search form
			recreateForm: true,
			afterShowSearch: function(e){
				var form = $(e[0]);
				form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
				style_search_form(form);
			},
			afterRedraw: function(){
				style_search_filters($(this));
			}
			,
			multipleSearch: true,
			/**
			multipleGroup:true,
			showQuery: true
			*/
		}
	)
	
	//payment
	var grid_selector_visit = "#payment-list-grid-table";
	var pager_selector_visit = "#payment-list-grid-pager";
	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/payment_history";

	jQuery(grid_selector_visit).jqGrid({
		url: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['Payment Date','Installment No', 'Payment Amount','Payment Channel'],
		colModel:[
			{name:'CM_DTE_LST_PYMT', index:'date(a.CM_DTE_LST_PYMT)', width:200,searchoptions:
				{
					dataInit: function(el) {
						$(el).datepicker({
							changeYear: true,
							changeMonth: true,
							showButtonPanel: true,
							//dateFormat: 'yy-mm-dd',
							format: 'yyyy-mm-dd',
						}).on("changeDate",function(e){
                            setTimeout(function () {
                                $(grid_selector_visit)[0].triggerToolbar();
                            }, 100);
							
						});
					}
				}
			},
			{name:'CM_CARD_NMBR', index:'a.CM_CARD_NMBR', width:180},
			{name:'CM_LST_PYMT_AMNT', index:'a.CM_LST_PYMT_AMNT', width:180},
			{name:'SEQNO', index:'a.SEQNO', width:180},
		], 
		sortname: 'CM_DTE_LST_PYMT',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector_visit,
		altRows: true,
		caption:"Payment History",
		multiselect: false,
		onSelectRow: function(data){
			var arr = $(this).getRowData(data);
			arr_id = arr.CM_CARD_NMBR;
		},
		toolbar: [true, "top"], 
		loadComplete : function() {
			var table = this;
			setTimeout(function(){
				updateActionIcons(table);
				updatePagerIcons(table);
				enableTooltips(table);
			}, 0);
		},
		autowidth: false,
		shrinkToFit: false
	});
	
jQuery(grid_selector_visit).jqGrid('filterToolbar',{defaultSearch:true,stringResult:true});		
	//navButtons
	jQuery(grid_selector_visit).jqGrid('navGrid', pager_selector_visit,
		{ 	//navbar options
			edit: false,
			editicon : 'icon-pencil blue',
			add: false,
			addicon : 'icon-plus-sign purple',
			del: false,
			delicon : 'icon-trash red',
			search: true,
			searchicon : 'icon-search orange',
			refresh: true,
			refreshicon : 'icon-refresh green',
			view: false,
			viewicon : 'icon-zoom-in grey',
		},
		{
			//search form
			recreateForm: true,
			afterShowSearch: function(e){
				var form = $(e[0]);
				form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
				style_search_form(form);
			},
			afterRedraw: function(){
				style_search_filters($(this));
			}
			,
			multipleSearch: true,
			/**
			multipleGroup:true,
			showQuery: true
			*/
		}
	)
	
	
})