var arr_id = "";
	 
jQuery(function($) {
	var grid_selector = "#zipcode_unassigned_mapping-list-grid-table";
	var pager_selector = "#zipcode_unassigned_mapping-list-grid-pager";	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "settings/unassigned_zipcode_mapping_list";

	jQuery(grid_selector).jqGrid({
		data: ci_controller,
		datatype: "json",
		height: 340,
		width: null,
		colNames:['ZIPCODE'],
		colModel:[
			{name:'zip_code', index:'zip_code', width:null, cellattr: function (rowId, tv, rawObject, cm, rdata) { 
      	return 'style="white-space: normal;' 
			}},
			
		], 
		sortname: 'zip_code',
		viewrecords : true,
		rowNum:10,
		rowList:[10,20,30],
		pager : pager_selector,
		altRows: true,
		caption:"ZIPCODE",
		multiselect: true,
		toolbar: [true, "bottom"], 
		loadComplete : function() {
			var table = this;
			setTimeout(function(){
				updateActionIcons(table);
				updatePagerIcons(table);
				enableTooltips(table);
			}, 0);
		},

		autowidth: false,
		showButtonPanel: true,
		shrinkToFit: false
		
	});
	$("#t_zipcode_unassigned_mapping-list-grid-table").css("height", "40px").css("width","150px").css("padding", "5px").css("background", "#e7e7e7");
	$("#t_zipcode_unassigned_mapping-list-grid-table").append(
		"<button class='btn btn-sm btn-success' id='btn-unassigned-assigned'>Assign Area</button>&nbsp;"
		+"<button class='btn btn-sm btn-primary' id='btn-unassigned-Print'>Print</button>"
		);
	$("#btn-unassigned-assigned").click(function() {
	
		var buttons = {
			"success" :
			 {
				"label" : "<i class='icon-ok'></i> Save",
				"className" : "btn-sm btn-success",
				"callback": function() {
									
					var options = {
						url : GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_zipcode_area_mapping_add/",
						type: "post",
						beforeSubmit: jqformValidate,
						success: showFormResponse,
						dataType: 'json',
					}; 
					$('form').ajaxSubmit(options);
				}
			},
			"button" :
			{
				"label" : "Close",
				"className" : "btn-sm"
			}
		}
	
		showCommonDialog(900, 500, 'ADD ZIPCODE AREA MAPPING', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/zipcode_area_mapping_add_form/', buttons);	
	});

	$("#btn-unassigned-Print").click(function() {
		 var printer = $("#printer").val();
                $.ajax({
                    url : "cetak_langsung.php",
                    type: "POST",
                    data : "nama_printer="+printer,
                    success: function(data, textStatus, jqXHR)
                    {
                        alert('Data Sudah Dicetak Ke Printer : '+printer)
                    }
                });
		
			
	});

	$("#checkAll").change(function(){

 	if (! $('input:checkbox').is('checked')) {
      		$('input:checkbox').attr('checked','checked');
		
 	 } else {
    	  $('input:checkbox').removeAttr('checked');
  	}       
	});




})
