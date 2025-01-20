var GLOBAL_MAIN_VARS = new Array ();
var GLOBAL_SESSION_VARS = new Array ();

GLOBAL_MAIN_VARS["auto_dial"] = false;

var Data;

var initData = function()
{
	Data = {	
		//Auto Dial
		"IsAutoDial"							: "F",
		"DialingMode"							: "",
		"AgentId"									: GLOBAL_SESSION_VARS["USER_ID"], 
		
		//Assigned by Telephony Callback
		"RecordingDirPath"				: "",
		"CallerId"								: "",
		
		//Save Status
		"SaveStatus" 							: false,
		
		//Customer Data
		"CdCustomer"						: "",
		"CustomerType"						: "",
		"IsMultiContract"					: "",
		
		"AavHistoryId"			: "",
		"CallHistoryId"						: "",
		
		//Re-AAV
		"IsReAav"									: "",
		
		//Loop
		"LoopNumber" 							: 0,
		
		//Time
		"ProcessTimeStart" 				: null,
		"ProcessTimeEnd" 					: null,
		"DialTime" 								: null,
		"SaveTime" 								: null,
		
		//Call Activity
		"DialedPhoneNumber" 			: "",
		"ContactPerson" 					: "",
		"CallResult" 							: "",
		"AgentNote" 							: ""
	}
}

var loadMain = function(title, link)
{
	$("#page-title").fadeOut(function () {
		$("#page-title").text("Customer Profile").fadeIn();
	})
	
	//$("#main-wrapper").html(GLOBAL_MAIN_VARS["progress_indicator"]).load("customer", function() {
		$("#main-wrapper").slideDown("slow");
  //})
}

var loadMenu = function(title, controller, panel)
{
	$("#page-title").fadeOut(function () {
		$("#page-title").text(title).fadeIn();
	})
	
	$("#customer-wrapper > div").hide();
	$("#" + panel).html(GLOBAL_MAIN_VARS["progress_indicator"]).load("customer/" + controller);
	$("#" + panel).slideDown("slow");
}


var loadMenuCockpit = function(title, link)
{
	$("#page-title").show(function () {
		$("#page-title").text(title).fadeIn();
	})
	
	$("#main-wrapper").show("fast", function() {
    $("#main-wrapper").html(GLOBAL_MAIN_VARS["progress_indicator"]).load(link, function() {
    	$("#main-wrapper").slideDown("slow");
    })
  })
}

var loadMenuBack = function(title, file)
{
	$("#page-title").fadeOut(function () {
		$("#page-title").text(title).fadeIn();
	})
	
	$("#customer-wrapper > div").hide();
	 $("#main-wrapper").html(GLOBAL_MAIN_VARS["progress_indicator"]).load("customer/" + file);
	 $("#main-wrapper").slideDown("slow");
}
/*========== HANYA UNTUK KEBUTUHAN SIMULASI - START ==========*/
/**
* loadQueue
* descripotion: berfungsi untuk load data berdasarkan queue dari tabel acs_call_queue yang flag_opened=0
*/
var loadQueue = function()
{
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "customer/get_queue/";
	$.ajax({ 
		type: "POST",
		url: ci_controller,
		async: false,
		dataType: "json",
		success: function(msg)
		{
			if(msg.success==true)
			{ 
				//updateQueue(msg.data[0].contract_number);
				//loadCustomerData(msg.data[0].account_number, msg.data[0].customer_type);
				loadCustomerData(msg.data[0].contract_number, msg.data[0].customer_type);
				//$("#panelProfile").html(GLOBAL_MAIN_VARS["progress_indicator"]).load("customer/profile");
			}
			else
			{
				//	
			}
		},
		error: function(){
			showWarning("Failed: " + ci_controller, 1500);
		}
	});
}

/**
* updateQueue
* description : update tabel acs_call_queue set flag_opened = 1
* update_loop: true/false (increment)
*/
var updateQueue = function(cd_customer, wl_status, update_loop)
{
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "customer/update_queue/";
	$.ajax({
		type:"POST",
		url: ci_controller,
 	 	async: false,
 		dataType: "json",
 		data :{cd_customer: cd_customer, wl_status: wl_status, update_loop: update_loop},
 		success: function(msg)
 		{
 			if(msg.success==true)
 			{
 				// action if true
 			}
 			else
 			{
 				// action if false
 			}
	 	},
	 	error: function()
	 	{
	 		showWarning("Failed: " + ci_controller, 1500);
	 	}
	});
}
/*========== HANYA UNTUK KEBUTUHAN SIMULASI - END ==========*/

/**
*	call account feeder (socket)
*/
var sendAccountFeeder = function(agent_id, group_id){
	$.ajax({
		type: "POST",
		//url: GLOBAL_MAIN_VARS["ASSET_URL"] + "apps/predial/client.php",
		url: GLOBAL_MAIN_VARS["SITE_URL"] + "assets/php/account_feeder/acctfeed2.php",
		async: false,
		dataType: "html",
		data: { agent_id:agent_id, group_id:group_id},
		success: function(msg){	 
			var res = $.parseJSON(msg.replace('#!/usr/local/bin/php -q',''));
			if(res.success == false){
				//clearDataCustomer()
				//reset
				$("#cd_title_borrower").text("[No customer]");
		 		$("#name").text("");
		 		$("#cd_customer").text("[none]");
		 		Data.CdCustomer = null;
		 		Data.CustomerType = null;
		 		$("#customer_type").text("");
		 		Data.IsMultiContract = "";
		 		Data.IsReAav = "";
		 		
		 		$('#customerPhoneGroup').html("");
		 		$("#panelProfile").slideUp();
				$("#panelDial").slideUp();
				//
				
				//Break Status
				//workedTimeCounter();
				$("#working_status").val("BREAK");
	      //$("#btnBreak").text("Idle");
	      GLOBAL_MAIN_VARS['break'] = true;
	      //workedTimeCounter();
	      
	      aux("Break"); // telepony break
	      
	      $("#btnBreak").removeAttr("disabled").css("background-color", "#FF8080");
	      $("#btnGetAccount").removeAttr("disabled");
	      
				showWarning(res.message, 1500);
				alert(res.message);
				//$("#btnGetAccount").show();
		 	} else if(res.success==true){
		 		Data.AavHistoryId = uuid();
				loadCustomerData(res.data.cd_customer, "P"); // load data customer berdasarkan account feeder
	
		 		//workedTimeCounter();
		 		$("#working_status").val("WORK");
		 		$("#agent_break_reason").val("");
				$("#btnBreak").text("Save and Break");
				GLOBAL_MAIN_VARS['break'] = false;
				GLOBAL_MAIN_VARS['flag'] = res.data.Type;
				GLOBAL_MAIN_VARS['class_id'] = res.data.ClassID;
				Data.ClassID = res.data.ClassID;
				Data.Cycle = $.trim(res.data.cycle);
				Data.QueueStatus = res.data.QueStatus;
				
				//Check Dialing Mode
				checkDialingMode();
				vDialingMode =  GLOBAL_MAIN_VARS['dialing_mode_id'];
				$("#DialModeID").val(vDialingMode);
				Data.DialModeID=vDialingMode;
				
				if(vDialingMode == "SEMI_AUTO"){
					dialPriorityPhone();
				}
				
				$("#btnAavForm").attr("disabled", "disabled").addClass("disabled");
			}else{
				//alert("error");
				showWarning("Account Feeder not responding!", 1500);
				$("#btnGetAccount").show();
			}
		},
		error: function(){
			showWarning("Account Feeder socket failed!", 1500);
			$("#btnGetAccount").show();
		}
	});
}

/**
* loadCustomerData
* description: berfungsi untuk menampilkan data customer berdasarkan parameter nomor kontrak
*/
var loadCustomerData = function(cd_customer, customer_type)
{	
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "customer/get_customer_data/";
	//Get Customer Profil and Contract Detail Data
	$.ajax({
		type: "POST",
		url: ci_controller,
		async: false,
		dataType: "json",
		data: { cd_customer: cd_customer, customer_type: customer_type},
		success: function(msg) {
		 	if(msg.success==true) {
		 		//Save session
		 		GLOBAL_MAIN_VARS['cd_customer'] = cd_customer;
		 		
		 		$("#cd_title_borrower").text(msg.data.cd_title_borrower);
		 		$("#name").text(msg.data.name);
		 		
		 		//Account Number / cd_customer
		 		$("#cd_customer").text(msg.data.cd_customer);
		 		Data.CdCustomer = msg.data.cd_customer;
		 		
		 		//Customer Type
		 		Data.CustomerType = msg.data.customer_type;
		 		if(msg.data.customer_type == "P")
		 			$("#customer_type").text("Personal");
		 		
		 		if(msg.data.customer_type == "C")
		 			$("#customer_type").text("Company");
		 			
		 		//Multi Contract Flag
		 		Data.IsMultiContract = msg.data.flag_multigroup;
		 		if(msg.data.flag_multigroup == "Y")
		 			$("#flag_multi_contract").text("Multi Contract");
		 			
		 		//Re AAV Flag
		 		Data.IsReAav = msg.data.flag_re_aav;
		 		if(msg.data.flag_re_aav== "Y")
		 			$("#flag_re_aav").text("Re-AAV");
		 		
		 		$("#panelProfile").html(GLOBAL_MAIN_VARS["progress_indicator"]).load("customer/profile", function() {
					$("#panelProfile").slideDown("slow");
					$("#panelDial").slideDown("slow");
			  })
			  
			  loadPhoneDialSelect();
			  loadContactPersonSelect();
			  loadCallResultSelect();
			  $("#txtAgentNote").val("");
			  //loadOtherForm();
			  loadAavForm();
			  
			  Data.ProcessTimeStart = jsCurrentDate();
			}
			else {
				//clear_form_elements(".post");
				showWarning(msg.message, 1500);
			}
		}, 
		error: function() {
			showWarning("Failed: " + ci_controller, 1500);
		}
	});
}

/**
* loadPhoneDialSelect
* description:
*/
var loadPhoneDialSelect = function(){
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "customer/get_customer_phone_data/";
	
	$('#customerPhoneGroup').html("");
	
	$.ajax({
		type: "POST",
		url: ci_controller,
		async: false,
		dataType: "json",
		success: function(msg){
			if(msg.success==true){
				//$('#customerPhoneSelect').empty();
				
				if($.trim(msg.data.phone_mobile) != '') {
					radioString = 
						'<div class="radio">' +
						'	<label>' +
						'		<input id="phone_mobile" name="customerPhoneSelect" type="radio" class="ace" value=" ' + msg.data.phone_mobile + '" />' +
						'		<span class="lbl"> Mobile Phone #1 : ' + msg.data.phone_mobile + '</span>' +
						'	</label>' +
						'</div>';
					$('#customerPhoneGroup').append(radioString);
				}
				
				if($.trim(msg.data.phone_mobile2) != '') {
					radioString = 
						'<div class="radio">' +
						'	<label>' +
						'		<input id="phone_mobile2" name="customerPhoneSelect" type="radio" class="ace" value=" ' + msg.data.phone_mobile2 + '" />' +
						'		<span class="lbl"> Mobile Phone #2 : ' + msg.data.phone_mobile2 + '</span>' +
						'	</label>' +
						'</div>';
					$('#customerPhoneGroup').append(radioString);
				}
				
				if($.trim(msg.data.phone1_resi) != '') {
					radioString = 
						'<div class="radio">' +
						'	<label>' +
						'		<input id="phone1_resi" name="customerPhoneSelect" type="radio" class="ace" value=" ' + msg.data.phone1_resi + '" />' +
						'		<span class="lbl"> Home Phone #1 : ' + msg.data.phone1_resi + '</span>' +
						'	</label>' +
						'</div>';
					$('#customerPhoneGroup').append(radioString);
				}
				
				if($.trim(msg.data.phone1_resi2) != '') {
					radioString = 
						'<div class="radio">' +
						'	<label>' +
						'		<input id="phone1_resi2" name="customerPhoneSelect" type="radio" class="ace" value=" ' + msg.data.phone1_resi2 + '" />' +
						'		<span class="lbl"> Home Phone #2 : ' + msg.data.phone1_resi2 + '</span>' +
						'	</label>' +
						'</div>';
					$('#customerPhoneGroup').append(radioString);
				}
				
				if($.trim(msg.data.phone1_co) != '') {
					radioString = 
						'<div class="radio">' +
						'	<label>' +
						'		<input id="phone1_co" name="customerPhoneSelect" type="radio" class="ace" value="' + msg.data.phone1_area_co + " " + msg.data.phone1_co + '" />' +
						'		<span class="lbl"> Company Phone #1 : ' +  msg.data.phone1_area_co + ' ' + msg.data.phone1_co + '</span>' +
						'	</label>' +
						'</div>';
					$('#customerPhoneGroup').append(radioString);
				}
				
				if($.trim(msg.data.phone2_co) != '') {
					radioString = 
						'<div class="radio">' +
						'	<label>' +
						'		<input id="phone2_co" name="customerPhoneSelect" type="radio" class="ace" value="' +  msg.data.phone2_area_co + " " + msg.data.phone2_co + '" />' +
						'		<span class="lbl"> Company Phone #2 : ' +  msg.data.phone2_area_co + ' ' + msg.data.phone2_co + '</span>' +
						'	</label>' +
						'</div>';
					$('#customerPhoneGroup').append(radioString);
				}
				
				if($.trim(msg.data.phone_mobile1_co) != '') {
					radioString = 
						'<div class="radio">' +
						'	<label>' +
						'		<input id="phone_mobile1_co" name="customerPhoneSelect" type="radio" class="ace" value=" ' + msg.data.phone_mobile1_co + '" />' +
						'		<span class="lbl"> Co. Mobile Phone #1 : ' + msg.data.phone_mobile1_co + '</span>' +
						'	</label>' +
						'</div>';
					$('#customerPhoneGroup').append(radioString);
				}
				
				if($.trim(msg.data.phone_mobile1_co2) != '') {
					radioString = 
						'<div class="radio">' +
						'	<label>' +
						'		<input id="phone_mobile1_co2" name="customerPhoneSelect" type="radio" class="ace" value=" ' + msg.data.phone_mobile1_co2 + '" />' +
						'		<span class="lbl"> Co. Mobile Phone #2 : ' + msg.data.phone_mobile1_co2 + '</span>' +
						'	</label>' +
						'</div>';
					$('#customerPhoneGroup').append(radioString);
				}
				
				if($.trim(msg.data.phone1_busi) != '') {
					radioString = 
						'<div class="radio">' +
						'	<label>' +
						'		<input id="phone1_busi" name="customerPhoneSelect" type="radio" class="ace" value="' + msg.data.phone1_area_busi + " " + msg.data.phone1_busi + '" />' +
						'		<span class="lbl"> Business Phone #1 : ' + msg.data.phone1_area_busi + ' ' + msg.data.phone1_busi + '</span>' +
						'	</label>' +
						'</div>';
					$('#customerPhoneGroup').append(radioString);
				}
				
				if($.trim(msg.data.phone2_busi) != '') {
					radioString = 
						'<div class="radio">' +
						'	<label>' +
						'		<input id="phone2_busi" name="customerPhoneSelect" type="radio" class="ace" value="' + msg.data.phone2_area_busi + " " + msg.data.phone2_busi + '" />' +
						'		<span class="lbl"> Business Phone #2 : ' + msg.data.phone1_area_busi + ' ' + msg.data.phone2_busi + '</span>' +
						'	</label>' +
						'</div>';
					$('#customerPhoneGroup').append(radioString);
				}
				
				if($.trim(msg.data.phone_mobile1_busi) != '') {
					radioString = 
						'<div class="radio">' +
						'	<label>' +
						'		<input id="phone_mobile1_busi" name="customerPhoneSelect" type="radio" class="ace" value=" ' + msg.data.phone_mobile1_busi + '" />' +
						'		<span class="lbl"> Bus. Mobile Phone #1 : ' + msg.data.phone_mobile1_busi + '</span>' +
						'	</label>' +
						'</div>';
					$('#customerPhoneGroup').append(radioString);
				}
				
				if($.trim(msg.data.phone_mobile2_busi) != '') {
					radioString = 
						'<div class="radio">' +
						'	<label>' +
						'		<input id="phone_mobile2_busi" name="customerPhoneSelect" type="radio" class="ace" value=" ' + msg.data.phone_mobile2_busi + '" />' +
						'		<span class="lbl"> Bus. Mobile Phone #2 : ' + msg.data.phone_mobile2_busi + '</span>' +
						'	</label>' +
						'</div>';
					$('#customerPhoneGroup').append(radioString);
				}
			}else{
				alert(msg.message);
			}
		},
		error: function(){
			showWarning("Failed : " + ci_controller, 1500);
		}
	});
}

/**
* loadContactPersonSelect
* description:
*/
var loadContactPersonSelect = function()
{
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "master_data/aav_option_list/"
	$.ajax(
	{
		type		: "POST",
		url			: ci_controller,
		data		: {charvalue: "801"},
		async		: false,
		dataType: "json",
		success	: function(msg){
			if(msg.success == true)
			{
				$('#optContactPerson option').remove();
				$('#optContactPerson').append($('<option></option>').val("").html("[select contact person]"));
				$.each(msg.data, function(val,text)
				{	
					$('#optContactPerson').append($('<option></option>').val(text.id).html(text.value));
				});
				
				$('#optContactPerson').attr("disabled", "disabled");
			}
			else
				{
				showWarning("Failed: " + ci_controller, 1500);
			}
		},
		error: function()
		{
			showWarning("Failed: " + ci_controller, 1500);
		}
	});
}

/**
* loadCallResultSelect
* description: loading data call result dari tabel ke select
*/
var loadCallResultSelect = function()
{
	var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "master_data/option_list/"
	$.ajax(
	{
		type		: "POST",
		url			: ci_controller,
		data		: {reference: "CALL_RESULT"},
		async		: false,
		dataType: "json",
		success	: function(msg){
			if(msg.success == true)
			{
				$('#optCallResult option').remove();
				$('#optCallResult').append($('<option></option>').val("").html("[select call result]"));
				$.each(msg.data, function(val,text)
				{	
					$('#optCallResult').append($('<option></option>').val(text.id).html(text.value));
				});
				
				$('#optCallResult').attr("disabled", "disabled")
			}
			else
				{
				showWarning("Failed: " + ci_controller, 1500);
			}
		},
		error: function()
		{
			showWarning("Failed: " + ci_controller, 1500);
		}
	});
}

var loadOtherForm = function(){
	$("#panelOtherForm").html(GLOBAL_MAIN_VARS["progress_indicator"]).load("customer/other_form/");
}

var loadAavForm = function(){
	//$("#btnAavForm").removeAttr("disabled");
	//$("#btnAavForm").removeClass("disabled").removeClass("btn-purple").addClass("btn-green");
	$("#panelAavForm").html(GLOBAL_MAIN_VARS["progress_indicator"]).load("customer/aav_form/");
}

/**
*	Phone Dial - Hanya berkaitan dengan telephony
*/
var dialPhone = function(dialed_number) 
{
	showInfo("Dialing " + dialed_number + "...", 3000);
	clean_dialed_number = dialed_number;
	
	// remove 62 when number length > 10 digit
	phoneNumberLength = clean_dialed_number.length;
	countryCode = clean_dialed_number.substring(0,2);
	
	if(phoneNumberLength > "10" && countryCode == "62")
	{
		zeroLead = 0;
		clean_dialed_number = clean_dialed_number.substring(2);
		clean_dialed_number = zero + clean_dialed_number;
	}

	// add 021 if 1st string not 0
	if(clean_dialed_number.substring(0, 1) != 0)
	{
		clean_dialed_number = "021" + clean_dialed_number;
	}

	//remove space
	while(clean_dialed_number.indexOf(' ') != -1)
	{
		clean_dialed_number = clean_dialed_number.replace(/[^0-9]|^(-)/i,'');
	}

	//remove dash
	while(clean_dialed_number.indexOf('-') != -1)
	{
		clean_dialed_number = clean_dialed_number.replace(/[^0-9]|^-| /i,'');
	}
	/*
	if(clean_dialed_number.substr(0, 4)=="0210")
	{
		dialed_number =	clean_dialed_number.substr(3);
	}
	*/
	
	//If not predial then dial
	if(Data.IsAutoDial == "F") {
		outbound();
		
		setTimeout(function(){
			originateCall(clean_dialed_number, Data.CdCustomer, GLOBAL_MAIN_VARS['auto_disconnect']);
		}, 200);
	};
}

//Phone Button Click
var phoneButtonClick = function()
{
	//Data.CallerId						= GLOBAL_MAIN_VARS['TELEPHONY_CALLER_ID'];
	Data.CallHistoryId			= uuid();
	Data.DialTime						= jsCurrentDate();
	Data.DialedPhoneNumber	= $('input:radio[name=customerPhoneSelect]:checked').val();
	$("#aav_phone_number").val(Data.DialedPhoneNumber);
	Data.ContactPerson			= "";
	Data.CallResult					= "";
	Data.AgentNote					= "";
	
	dialPhone(Data.DialedPhoneNumber);
	
	
	/*
	

	$("#DialedPhoneType").val(button.val()); //Test
	Data.DialedPhoneType = button.val().substr(7);
	
	if(Data.DialedPhoneNumber == "") {
		Data.DialedPhoneNumber = GLOBAL_MAIN_VARS['active_call_no'];
	}
	else {
		//$(button).not("#btn_new").addClass("dialed");
		$("button[name='dial']").each(function() {
			//$(this).attr("disabled", "disabled").css("background-color", "#C0C0C0"); //tri s=disable
		});
		$(button).not("#btn_new").removeClass("btn-primary").addClass("btn-danger");
		
		$(button).not("#btn_new").attr("disabled", "disabled");
		
		//$("#btnSaveAndNext").attr("disabled", "disabled");
		dialPhone(Data.DialedPhoneNumber);
		
		//If multi contract, update tmp_multi_contract_call_result.call_id
		if (Data.isMultiContract=="Y" && Data.Units > 1) {
			$.ajax({
				type:"POST",
				url: GLOBAL_MAIN_VARS["SITE_URL"] + "/agent_main/update_temp_multi_contract_call_id/",
		 	 	async: false,
		 		dataType: "json",
		 		data: {main_collection_id: Data.CollectionID, group_id: Data.GroupNumber, class_id: Data.ClassID},
		 		success: function(msg) {
		 			if(msg.success==true) {
		 				// action if true
		 			}
		 			else {
		 				// action if false
		 			}
			 	},
			 	error: function() {
			 		alert("Update call id failed");
			 	}
			});
		}
	}
	
	button.removeClass("btn-primary").addClass("btn-danger");
	
	//Hapus Appointment
	$.ajax({
		type:"POST",
		url: GLOBAL_MAIN_VARS["SITE_URL"] + "/agent_main/delete_appointment/",
 	 	async: false,
 		dataType: "json",
 		data :{contract_number:Data.ContractNumber},
 		success: function(msg) {
 			if(msg.success==true) {
 				// action if true
 			}
 			else {
 				// action if false
 			}
	 	},
	 	error: function() {
	 		alert("Failed: delete_appointment");
	 	}
	});
	
	//Update Phone Dial Counter
	$.ajax({
		type:"POST",
		url: GLOBAL_MAIN_VARS["SITE_URL"] + "/agent_main/update_phone_dial_counter/",
 	 	async: false,
 		dataType: "json",
 		data :{contract_number:Data.ContractNumber},
 		success: function(msg) {
 			if(msg.success==true) {
 				// action if true
 			}
 			else {
 				// action if false
 			}
	 	},
	 	error: function() {
	 		alert("Failed: update_phone_dial_counter");
	 	}
	});
	
	//Insert data call_history_id_handling dan contract_number_handling for Silent Monitoring Purpose
	$.ajax({
		type:"POST",
		url: GLOBAL_MAIN_VARS["SITE_URL"] + "/agent_main/update_user_handling_data/",
 	 	async: false,
 		dataType: "json",
 		data :{contract_number:Data.ContractNumber, call_history_id:Data.CallID},
 		success: function(msg) {
 			if(msg.success==true) {
 				// action if true
 			}
 			else {
 				// action if false
 			}
	 	},
	 	error: function() {
	 		alert("Failed: update_user_handling_data");
	 	}
	});
	*/
	
	$("#btnAavForm").removeAttr("disabled").removeClass("disabled");
	$("#optContactPerson, #optCallResult, #txtAgentNote").removeAttr("disabled");
	
	return false;
}

var saveAav = function() 
{
	if(!Data.SaveStatus)
	{
		Data.SaveTime= jsCurrentDate();
		
		Data.AgentNote = $("#txtAgentNote").val();
		
		if(Data.DialedPhoneNumber == "" || Data.ContactPerson == "" || Data.CallResult == "")
		{	
	 		if(Data.DialedPhoneNumber == "")
	 		{
		 		showWarning("Silahkan lakukan dial terlebih dahulu!", 1500);
		 		
		 		return false;
			}
			else
			{
				if(Data.ContactPerson == "")
				{
			 		$('#optContactPerson').addClass('frm_error');
			 		showWarning("Silahkan isi Contact Person!", 1500);
			 		
			 		return false;
				}
				else
				{
			 		if(Data.CallResult == "")
			 		{
				 		$('#optCallResult').addClass('frm_error');
				 		showWarning("Silahkan isi Hasil Telepon!", 1500);
				 		
				 		return false;
					}
				}
			}
		}
		else
		{
	 		Data.ProcessTimeEnd = jsCurrentDate();
	 		
	 		var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "customer/save_call_history/";
	 		$.ajax({
				type: "POST",
				url: ci_controller,
				async: false,
				dataType: "json",
				data: Data,
				success: function(msg) {
					if(msg.success == true) {
						showInfo("Data berhasil disimpan.", 500);
						Data.SaveTime = jsCurrentDate();
	 		
						//found_history = msg.found;
						
						$("#optContactPerson, #optCallResult, #txtAgentNote").val("").removeAttr("disabled");
					}
					else {
					 	showWarning("Data gagal disimpan", 1500);
					}
				},
				error: function() {
					showWarning("Failed: " + ci_controller, 1500);
				}
			});
		}
		
		//alert("Belum save");
		Data.SaveStatus = true;
	}
	else
	{
		//alert("Sudah save");
	}
};

var nextAav = function(contract_number)
{
	saveAav();
	alert("Lanjuuut...");
}

var checkDialingMode = function(){
	$.ajax({
		type: "POST",
		url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/get_dialing_mode",
		async: false,
		dataType: "json",
		success: function(msg){	 
			 GLOBAL_MAIN_VARS['dialing_mode_id'] = msg.data;
		},
		error: function(){
			showWarning("Account Feeder socket failed!", 1500);
			$("#btnGetAccount").show();
		}
	});
}

var dialPriorityPhone = function(){
	$.ajax({
		type: "POST",
		url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/get_phone_priority",
		async: false,
		dataType: "json",
		success: function(msg){	 
			phones = msg.data.split(",");
			
			for (i = 0; i < phones.length; i++) { 
			  //alert(phones[i]);
			  
			  if(phones[i] == "Mobile Phone #1") {
			  	if ($("#phone_mobile").length) {
			  		$("#phone_mobile").prop("checked", true)
			  	
			  		break;
			  	} else {
			  		continue;
			  	}
			  }
			  if(phones[i] == "Mobile Phone #2") {
			  	if ($("#phone_mobile2").length) {
			  		$("#phone_mobile2").prop("checked", true);
			  		break;
			  	} else {
			  		continue;
			  	}
			  }
			  
			  if(phones[i] == "Home Phone #1") {
			  	if ($("#phone1_resi").length) {
			  		$("#phone1_resi").prop("checked", true);
			  		break;
			  	} else {
			  		continue;
			  	}
			  }
			  
			  if(phones[i] == "Home Phone #2") {
			  	if ($("#phone1_resi2").length) {
			  		$("#phone1_resi2").prop("checked", true);
			  		break;
			  	} else {
			  		continue;
			  	}
			  }
			  
			  if(phones[i] == "Co. Mobile Phone #1") {
			  	if ($("#phone1_co").length) {
			  		$("#phone1_co").prop("checked", true);
			  		break;
			  	} else {
			  		continue;
			  	}
			  }
			  
			  if(phones[i] == "Co. Mobile Phone #2") {
			  	if ($("#phone2_co").length) {
			  		$("#phone2_co").prop("checked", true);
			  		break;
			  	} else {
			  		continue;
			  	}
			  }
			  
			  if(phones[i] == "Company Phone #1") {
			  	if ($("#phone_mobile1_co").length) {
			  		$("#phone_mobile1_co").prop("checked", true);
			  		break;
			  	} else {
			  		continue;
			  	}
			  }
			  
			  if(phones[i] == "Company Phone #2") {
			  	if ($("#phone_mobile1_co2").length) {
			  		$("#phone_mobile1_co2").prop("checked", true);
			  		break;
			  	} else {
			  		continue;
			  	}
			  }
			  
			  if(phones[i] == "Buss. Mobile Phone #1") {
			  	if ($("#phone1_busi").length) {
			  		$("#phone1_busi").prop("checked", true);
			  		break;
			  	} else {
			  		continue;
			  	}
			  }
			  
			  if(phones[i] == "Buss. Mobile Phone #2") {
			  	if ($("#phone2_busi").length) {
			  		$("#phone2_busi").prop("checked", true);
			  		break;
			  	} else {
			  		continue;
			  	}
			  }
			  
			  if(phones[i] == "Bussiness Phone #1") {
			  	if ($("#phone_mobile1_busi").length) {
			  		$("#phone_mobile1_busi").prop("checked", true);
			  		break;
			  	} else {
			  		continue;
			  	}
			  }
			  
			  if(phones[i] == "Bussiness Phone #2") {
			  	if ($("#phone_mobile2_busi").length) {
			  		$("#phone_mobile2_busi").prop("checked", true);
			  		break;
			  	} else {
			  		continue;
			  	}
			  }
			  
			  //if($.trim($("#txtCustomerHandphone").val())!="")
				//$("#btnDialCustomerHandphone").click();
			}
			
			$("#customerPhoneGroup").click();
			$("#btnDial").click();  
		},
		error: function(){
			showWarning("Account Feeder socket failed!", 1500);
			$("#btnGetAccount").show();
		}
	});
}
jQuery(function($)
{
	$('#menuMain > li').click(function() {
  	var list = $("#menuMain").find('li');
	  $(list.get()).each(function () {
	  	$(this).removeClass('active');
	  });
	  
    $(this).addClass("active");
  });
  
  $("#btnGetAccount").removeAttr("disabled");
  
  initData();
  
  //Agen Main Init
	loadMain();
	
	$("#btnGetAccount").click(function()
	{
		$(this).attr("disabled", "disabled");
		$("#customer-wrapper > div").hide();
		
		var list = $("#menuMain").find('li');
	  $(list.get()).each(function () {
	  	$(this).removeClass('active');
	  });
	  
	  $("#menuProfile").addClass("active");
		
		//loadQueue();/*Hanya untuk simulasi*/
		sendAccountFeeder(GLOBAL_SESSION_VARS["USER_ID"], GLOBAL_SESSION_VARS["GROUP_ID"]);
		//$(this).hide();
		//$(this).attr("disabled", "disabled");
	});
	
	$("#customerPhoneGroup").click(function()
	{
  	$("#btnDial").removeClass("disabled");
  });
	
	$("#btnDial").click(function()
	{
		phoneButtonClick();
	});
	
	$('#frmDialMenu').submit(function ()
	{
		//$("html, body").animate({ scrollTop: 310 }, 500);
		return false;
	});
	
	$("#optContactPerson").change(function()
	{
		Data.ContactPerson = $(this).val();
	});
	
	$("#optCallResult").change(function()
	{
		Data.CallResult = $(this).val();
	});
	
	$("#btnSave").click(function()
	{
		saveAav();
	});
	
	$("#btnSaveAndNext").click(function()
	{
		var queueStatus = "2";
		saveAav();
		//IF AAV complete then send Data to SOA
		//if(Data.CallResult == "COMPLETE") {
			var ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "customer/send_data_to_soa/"
			$.ajax(
			{
				type		: "POST",
				url			: ci_controller,
				data		: {cd_customer: GLOBAL_MAIN_VARS['cd_customer']},
				async		: false,
				dataType: "json",
				success	: function(msg){
					if(msg.success == true)
					{
						console.log("Berhasil");
					}
					else
						{
						showWarning("Failed: " + ci_controller);
					}
				},
				error: function()
				{
					showWarning("Failed: " + ci_controller);
				}
			});
			//If AAV Complete
			queueStatus = "50";
		//};
		
		updateQueue(GLOBAL_MAIN_VARS['cd_customer'], queueStatus, true);
		
		$("#btnGetAccount").click();
	});
	
	$("#btnAavForm").click(function()
	{
  	$("#panelAavForm").slideDown();
  });
	loadMenuBack('LIST DEBITUR', 'profile');
});