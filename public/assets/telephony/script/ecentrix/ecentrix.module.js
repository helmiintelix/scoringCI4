// variables 
var TELEPHONY_CONNECTOR			= null;
var TELEPHONY_CONTROLLER		= null;
var TELEPHONY_CURRENT_STATUS	= null;
var TELEPHONY_CALLER_ID			= null;
var TELEPHONY_IS_UNPARKING		= false;
var TELEPHONY_CONFERENCE_ID		= null;
var TELEPHONY_CONFERENCE_MEMBER = new Array();
var TELEPHONY_START_TIME		= null;
var TELEPHONY_TIMER_ID			= null;
var TELEPHONY_IS_TIMER_RUNNING	= false;
var TELEPHONY_LOG				= null;
// load flash
var loadFlash = function (server, port, policy, agent, ip, hosted, monitoring){
	if (FlashDetect.installed && FlashDetect.versionAtLeast(11)){
		var moduleFlashVars = {
			host: server,
			port: port,
			policy: policy,
			agent_id: agent,
			ip_address: ip,
			hosted: hosted,
			performance_monitoring: monitoring
		};
		var moduleParams = {
			menu: "false",
			scale: "noScale",
			allowFullscreen: "false",
			allowScriptAccess: "always",
			bgcolor: "#FFFFFF"
		};
		var moduleAttributes = {
			id:"module"
		};

		swfobject.embedSWF(
				"assets/swf/agentdesktop-2.0.10.swf", 
				"controller", 
				"1", "1", 
				"11.0.0", 
				"swf/expressInstall.swf", 
				moduleFlashVars, 
				moduleParams, 
				moduleAttributes,
				function (e){
					if (e.success){
						TELEPHONY_CONTROLLER = document.getElementById("module");
						clientAppIsLoaded();
					}
					else {
						alert("Cannot load eCentriX module");
					}
				}
			);
	}
	else {
		needFlashClient();
	}
};
// load applet
var loadApplet = function (server, port, policy, agent, ip, hosted, monitoring){
	if (navigator.javaEnabled() && deployJava.versionCheck("1.7.*")){
		var attributes = {
				name: "eCentriX",
				id: "eCentriX",
				codebase: "jar",
				code: "AgentDesktopController.class",
				archive: "desktop-2.0.4.jar",
				"class": "applet",
				mayscript: true,
				scriptable: true,
				width: 0,
				height: 0
			};
		var parameters = {
				agent_id: agent,
				ip_address: ip,
				hosted: hosted,
				host: server,
				port: port,
				timeout: 10,
				performance_monitoring: monitoring,
				js_object: true,
				log: true
			};

		var startApplet = '<' + 'applet ';
		// if ($.browser.msie){
			// startApplet = '<' + 'object ' + 'classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" type="application/x-java-applet" ';
		// }
		// var params = '';
		// var endApplet = '<' + '/' + 'applet' + '>';
		// if ($.browser.msie){
			// endApplet = '<' + '/' + 'object' + '>';
		// }
		// if ($.browser.msie){
			// for (var attribute in attributes) {
				// if (attribute != "width" && attribute != "height" && attribute != "id"){
					// var value = attributes[attribute];
					// if (attribute != "name" && attribute != "archive"){
						// delete attributes[attribute];
					// }
					// parameters[attribute] = value;
				// }
			// }
		// }

		// for (var attribute in attributes) {
			// startApplet += (' ' +attribute+ '="' +attributes[attribute] + '"');
		// }
	
		// if (parameters != 'undefined' && parameters != null) {
			// var codebaseParam = false;
			// for (var parameter in parameters) {
				// params += '<param name="' + parameter + '" value="' + 
					// parameters[parameter] + '"/>';
			// }
		// }

		// startApplet += '>';

		// $("body").append(startApplet + '\n' + params + '\n' + endApplet);
		setTimeout(function(){
			TELEPHONY_CONTROLLER = $("#eCentriX")[0];
			clientAppIsLoaded();
		}, 500);
	}
	else {
		needJRE();
	}
};

// load
var loadTelephonyModule = function (connector, server, port, policy, agent, ip, hosted, sip, rtmp, level, monitoring){
	
	TELEPHONY_CONNECTOR				= connector;
	TELEPHONY_LOG					= log4javascript.getLogger();
	var TELEPHONY_LOG_APPENDER		= new log4javascript.BrowserConsoleAppender();
	var TELEPHONY_LOG_LAYOUT		= new log4javascript.PatternLayout("%d{HH:mm:ss,SSS} %-5p - %m%n");
	TELEPHONY_LOG_APPENDER.setLayout(TELEPHONY_LOG_LAYOUT);
	TELEPHONY_LOG_APPENDER.setThreshold(level);
	TELEPHONY_LOG.addAppender(TELEPHONY_LOG_APPENDER);

	if (monitoring === undefined){
		monitoring = false;
	}

	if (TELEPHONY_CONNECTOR==="applet"){
		// load applet
		loadApplet(server, port, policy, agent, ip, hosted, monitoring);
	}
	else if (TELEPHONY_CONNECTOR==="flash"){
		// load flash
		loadFlash(server, port, policy, agent, ip, hosted, monitoring);
	}
};
// ------------------------------------------------------------------------
var checkIfLoginAvailable = function(){
	var recheck = false;
	if (TELEPHONY_CONTROLLER != null && TELEPHONY_CONTROLLER !== undefined){
		if ((TELEPHONY_CONNECTOR == "applet") || typeof TELEPHONY_CONTROLLER["login"] == "function"){
			$("#loader").remove();
			$("#telephony").show();
//			console.log(TELEPHONY_CONTROLLER.initiate());
			TELEPHONY_CONTROLLER.initiate();
			TELEPHONY_CONTROLLER.login();
		}
		else {
			recheck = true;
		}
	}
	else {
		recheck = true;
	}
	if (recheck){
		setTimeout(function(){
			if (TELEPHONY_CONNECTOR == "applet"){
				TELEPHONY_CONTROLLER = $("#eCentriX")[0];
			}
			checkIfLoginAvailable();
		}, 200);
	}
};
var clientAppIsLoaded = function(){
	
	$("#bar").width("400%");
	checkIfLoginAvailable();

	// register
	/* window.onbeforeunload = function(){
		alert('unload');
		if (TELEPHONY_CONTROLLER!=null && TELEPHONY_CONTROLLER!==undefined){
			alert('telephony_control !null && undified');
			if (typeof TELEPHONY_CONTROLLER["logout"] == "function"){
				TELEPHONY_CONTROLLER.logout();
				alert('logut');
			}
		}
		alert(TELEPHONY_CONTROLLER);
	}; */
	$(window).unload(function(){
		if (TELEPHONY_CONTROLLER!=null && TELEPHONY_CONTROLLER!==undefined){
			if (typeof TELEPHONY_CONTROLLER["logout"] == "function"){
				TELEPHONY_CONTROLLER.logout();
			}
		}
	});
	/* $(window).unload(function(){
		alert('unload');
		if (TELEPHONY_CONTROLLER!=null && TELEPHONY_CONTROLLER!==undefined){
			alert('telephony_control !null && undified');
			if (typeof TELEPHONY_CONTROLLER["logout"] == "function"){
				TELEPHONY_CONTROLLER.logout();
				alert('logut');
			}
		}
		alert(TELEPHONY_CONTROLLER);
	}); */
};
var clientAppProgress = function(percentage){
	var progress = 390 + (parseInt(percentage, 10) / 10);
	$("#bar").width(progress + "%");
};
var flashDebug = function(message){
	TELEPHONY_LOG.debug("[FLASH] " + message);
};
var flashInfo = function(message){
	TELEPHONY_LOG.info( "[FLASH] " + message);
};
var flashWarn = function(message){
	TELEPHONY_LOG.warn( "[FLASH] " + message);
};
var flashError = function(message){
	TELEPHONY_LOG.error("[FLASH] " + message);
};
// ------------------------------------------------------------------------
/**
 * Called by connection controller to show total queue (performance monitoring)
 * @param {String} val
 */
var setTotalCallQueue = function (val){
	TELEPHONY_LOG.debug("setTotalCallQueue [val: "+ val +"]");
	// do something here
}
/**
 * Called by connection controller to show total answered call (performance monitoring)
 * @param {String} val
 */
var setTotalAnsweredCall = function (val){
	TELEPHONY_LOG.debug("setTotalAnsweredCall [val: "+ val +"]");
	// do something here
}
/**
 * Called by connection controller to show total abandoned call (performance monitoring)
 * @param {String} val
 */
var setTotalAbandonedCall = function (val){
	TELEPHONY_LOG.debug("setTotalAbandonedCall [val: "+ val +"]");
	// do something here
}
/**
 * Called by connection controller to show service level (performance monitoring)
 * @param {String} val
 */
var setServiceLevel = function (val){
	TELEPHONY_LOG.debug("setServiceLevel [val: "+ val +"]");
	// do something here
}
/**
 * Called by connection controller to show total agent idle (performance monitoring)
 * @param {String} val
 */
var setTotalAgentIdle = function (val){
	TELEPHONY_LOG.debug("setTotalAgentIdle [val: "+ val +"]");
	// do something here
}
/**
 * Called by connection controller to show total agent not active (performance monitoring)
 * @param {String} val
 */
var setTotalAgentNotActive = function (val){
	TELEPHONY_LOG.debug("setTotalAgentNotActive [val: "+ val +"]");
	// do something here
}
/**
 * Called by connection controller to show total agent talking (performance monitoring)
 * @param {String} val
 */
var setTotalAgentTalking = function (val){
	TELEPHONY_LOG.debug("setTotalAgentTalking [val: "+ val +"]");
	// do something here
}

// ------------------------------------------------------------------------
/**
 * Called by connection controller to show connection status
 * @param {String} status
 */
var showConnectionStatus = function (status){
	$("#telephony span#telephony_notes").html(status);
};
/**
 * Called by applet to show agent status 
 * @param {String} action 
 * @param {String} result
 * @param {String} reason
 */
var receiveMessage = function (action, result, reason){
	
	TELEPHONY_LOG.info("receiveMessage [action: "+ action +", result: "+ result + ", reason: "+ reason +"]");
	try {
		var status = $.trim(action.substring(6, action.length));
		if (result=="SUCCESS"){
			if (($.trim(reason)).length>0 && status!="RELEASED"){
				$("#telephony span#agent_status").html(status + " ("+ reason +")");
			}
			else{
				$("#telephony span#agent_status").html(status);
			}
			if (TELEPHONY_CURRENT_STATUS=="LOGIN" || TELEPHONY_CURRENT_STATUS=="ON_HOOK"){
				$("#telephony span#call_duration").html("&nbsp;");
				$("#telephony span#call_status").html("&nbsp;");
				$("#telephony span#telephony_notes").html("&nbsp;");
			}
			TELEPHONY_CURRENT_STATUS = status;
		}
		else{
			$("#telephony span#agent_status").html("ERROR");
			$("#telephony span#telephony_notes").html(reason);
		}
		if (status=="LOGIN"){
			if (reason!==undefined && reason.length>0 && !isNaN(reason)){
				TELEPHONY_LOG.debug("extension [number: "+ reason + "]");
				var TELEPHONY_EXTENSION_NUMBER = reason;
				//Save Extension
				$.ajax({
					type: "POST",
					url: GLOBAL_MAIN['BASE_URL']+"dashboard/save_extension/",
					async: false,
					dataType: "json",
					data: { extension_id: TELEPHONY_EXTENSION_NUMBER },
					success: function(msg){
					 	console.log(msg);
					},
					error: function(err){
						console.log(err);
					}
				});
			}
		}
		else if (status=="AUTO_IN"){
			$("#telephony span#call_duration").html("&nbsp;");
			$("#telephony span#call_status").html("&nbsp;");
			$("#telephony span#telephony_notes").html("&nbsp;");
			$.ajax({
				type: "GET",
				url: "replace_session/unsetSessionRecording?",
				success: function(msg){
				
				}
			}); 
		}
		else if (status=="RELEASED"){
			showWarning("We have received message from eCentriX: \"AGENT_RELEASED (" + reason + ")\". \nIt means, previous call that was being redirected to you has failed. \nLogout first and then check your softphone. \nIf needed, restart your softphone to make it run well", 10000);
		}
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'receiveMessage' function with param [action: "+ action +", result: "+ result + ", reason: "+ reason +"] because of exception",
				ex
			);
	}
};
/**
 * Called by applet to inform incoming call
 * @param {String} callerId
 * @param {String} number
 * @param {String} customerData
 * @param {String} recordingId
 * @param {String} recordingFile
 */
var callAlert = function (callerId, number, customerData, recordingId, recordingFile){
	TELEPHONY_LOG.info("callAlert [callerId: "+ callerId +", number: "+ number + ", customerData: "+ customerData + ", recordingId: "+ recordingId + ", recordingFile: "+ recordingFile + "]");		
	callActiveState();
	$("#telephony button#dial").parent().show();
	try {
		TELEPHONY_CALLER_ID = callerId;
		// reset
		TELEPHONY_IS_UNPARKING		= false;
		// remove park and conference related button 
		$("#telephony button.join, #telephony button.leave, #telephony button.kick, #telephony button.unpark").parent().hide("fast", function(){
			$(this).remove();
		});
		
		GLOBAL_MAIN_VARS["anumber"] = number;
		$.ajax({
			type: "GET",
			dataType: 'json',
			url: "home/checkPhone/?anumber="+number,
			success: function(msg){
				//alert(msg.customer_id);
				if(msg.num_rows == '1'){
					$.ajax({
						type: "GET",
						url: "replace_session/?customer_id="+msg.customer_id+"&ticket_id=",
						success: function(msg){
							$("#screen-title").text("| Cockpit");
							$("#main-wrapper").hide().load("cockpit?from=customer").fadeIn('500');
						}
					}); 
				}
				else if(msg.num_rows == '0'){
					$("#screen-title").text("| Ticket");
					$("#main-wrapper").load("ticket"); 
					$.ajax({
						type: "GET",
						url: "replace_session/currentNumber?current_number="+number,
						success: function(msg){							
							popUp('popup/popup/?from='+''+'&src='+''+'&message='+''+'&inbound_source=noncustomer'+'&id='+''+'&data='+''+'&inbound_type=TELP','Create Ticket Non Customer','90%','60%');				
						}
					});
					
				}
			//	$("#screen-title").text("| Cockpit");
			//	$("#main-wrapper").hide().load("cockpit?from=customer").fadeIn('500');
			}
		});
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'callAlert' function with param [callerId: "+ callerId +", number: "+ number + ", customerData: "+ customerData + ", recordingId: "+ recordingId + ", recordingFile: "+ recordingFile + "] because of exception",
				ex
			);
	}
};
var clearSession = function (){
	
	TELEPHONY_LOG.info("clearssesion");
	try {
		$.ajax({
				type: "GET",
				url: "replace_session/unsetSessionRecording?",
				success: function(msg){
				
				}
			});
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error clear session",
				ex
			);
	}
}
/**
 * Called by applet to inform call is being originated
 * @param {String} callerId
 * @param {String} number
 */
var callOriginated = function (callerId, number){
	TELEPHONY_LOG.info("callOriginated [callerId: "+ callerId +", number: "+ number +"]");
	callActiveState();
	$("#telephony button#dial").parent().show();
	try {
		TELEPHONY_CALLER_ID = callerId;
		// update status
		$("#telephony span#call_status").html("ORIGINATE");
		$("#telephony span#telephony_notes").html("Originating call to " + number);
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'callOriginated' function with param [callerId: "+ callerId +", number: "+ number + "] because of exception",
				ex
			);
	}
	$("#telephony span#telephony_notes").html("&nbsp;");
};
/**
 * Called by applet to inform that originating call has been failed
 * @param {String} callerId
 * @param {String} number
 * @param {String} reason
 */
var callOriginateFailed = function (callerId, number, reason){
	TELEPHONY_LOG.info("callOriginateFailed [callerId: "+ callerId +", number: "+ number +", reason: "+ reason +"]");
	initialState();
	try {
		// reset TELEPHONY_CALLER_ID
		TELEPHONY_CALLER_ID = null;
		// update status
		$("#telephony span#call_status").html("ERROR");
		$("#telephony span#telephony_notes").html(reason);
		// show notification
		showWarning(reason);
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'callOriginateFailed' function with param [callerId: "+ callerId +", number: "+ number + ", reason: "+ reason +"] because of exception",
				ex
			);
	}
};
/**
 * Called by applet to inform call is already connected / accepted
 * @param {String} callerId
 * @param {String} number
 * @param {String} recordingId
 * @param {String} recordingFile
 */
var callConnected = function (callerId, number, recordingId, recordingFile){
	callActiveState();
	TELEPHONY_LOG.info("callConnected [callerId: "+ callerId +", number: "+ number +", recordingId: "+ recordingId +", recordingFile: "+ recordingFile + "]");
	try {
		TELEPHONY_CALLER_ID = callerId;
		// update status message
		$("#telephony span#call_status").html("CONNECTED");
		$("#telephony span#telephony_notes").html("Call ("+ number +") is connected");
		$("#telephony input#a_number").val(number);
		$("#telephony input#a_number").attr("readonly",true);
		// start call duration
		startTimer();
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'callConnected' function with param [callerId: "+ callerId +", number: "+ number + ", recordingId: "+ recordingId +", recordingFile: "+ recordingFile + "] because of exception",
				ex
			);
	}
};
/**
 * Called by applet to inform call has diconnected
 * @param {String} callerId
 * @param {String} number
 */
var callDisconnected = function (callerId, number){
	TELEPHONY_LOG.info("callDisconnected [callerId: "+ callerId +", number: "+ number +"]");
	initialState();
	try {
		// reset 
		TELEPHONY_CALLER_ID = null;
		// stop call duration
		stopTimer();
		// update status message
		$("#telephony span#call_status").html("DISCONNECTED");
		$("#telephony input#a_number").removeAttr("readonly");
		$("#telephony input#a_number").val("");
		// remove (related) unpark button
		if ($("#telephony button.unpark[data=\""+ callerId +"\"]").length>0){
			$("#telephony button.unpark[data=\""+ callerId +"\"]").parent().hide("fast", function(){
				$(this).remove();
				// reset
				resetNotesSize();
				resetActionMenuPosition();
			});
		}
		// remove related (agent) join button
		if ($("#telephony button.join[data=\""+ callerId +"\"]").length>0){
			$("#telephony button.join[data=\""+ callerId +"\"]").parent().hide("fast", function(){
				$(this).remove();
				// reset
				resetNotesSize();
				resetActionMenuPosition();
			});
		}
		// remove related (agent) leave button
		if ($("#telephony button.leave[data=\""+ callerId +"\"]").length>0){
			$("#telephony button.leave[data=\""+ callerId +"\"]").parent().hide("fast", function(){
				$(this).remove();
				// reset
				resetNotesSize();
				resetActionMenuPosition();
			});
		}
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'callDisconnected' function with param [callerId: "+ callerId +", number: "+ number + "] because of exception",
				ex
			);
	}
	$("#telephony span#telephony_notes").html("&nbsp;");
};
/**
 * Called by applet to inform that could'n disconnect call
 * @param {String} callerId
 * @param {String} number
 * @param {String} reason
 */
var callDisconnectFailed = function (callerId, number, reason){
	TELEPHONY_LOG.info("callDisconnectFailed [callerId: "+ callerId +", number: "+ number +", reason: "+ reason +"]");
	initialState();
	try {
		// reset 
		TELEPHONY_CALLER_ID = null;
		// update status
		//$("#telephony span#call_status").html("Error while disconnecting call to " + number + " (" + reason + ")");
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'callDisconnectFailed' function with param [callerId: "+ callerId +", number: "+ number + ", reason: "+ reason +"] because of exception",
				ex
			);
	}
	$("#telephony span#telephony_notes").html("&nbsp;");
	$("#telephony input#a_number").removeAttr("readonly");
	$("#telephony input#a_number").val("");
};
/**
 * Called by applet to inform call has been transfered
 * @param {String} callerId
 * @param {String} agentId
 * @param {String} extension
 */
var callTransfered = function (callerId, agentId, extension){
	TELEPHONY_LOG.info("callTransferred [callerId: "+ callerId +", agentId: "+ agentId + ", extension: " + extension + "]");
	initialState();
	try {
		// reset 
		TELEPHONY_CALLER_ID = null;
		// stop call duration
		stopTimer();
		// update status message
		$("#telephony span#call_status").html("TRANSFERRED");
		$("#telephony input#a_number").removeAttr("readonly");
		$("#telephony input#a_number").val("");
		// show notification
		showWarning("Call has been transfered to " + agentId + "("+ extension +")");
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'callTransfered' function with param [callerId: "+ callerId +", agentId: "+ agentId + ", extension: " + extension + "] because of exception",
				ex
			);
	}
};
/**
 * Called by applet to inform call has been transferred
 * @param {String} callerId
 * @param {String} agentId
 * @param {String} extension
 */
var callTransferred = function (callerId, agentId, extension){
	callTransfered(callerId, agentId, extension);
}
/**
 * Called by applet to inform that couldn't transfer call
 * @param {String} callerId
 * @param {String} target
 * @param {String} reason
 */
var callTransferFailed = function (callerId, target, reason){
	TELEPHONY_LOG.info("callTransferFailed [callerId: "+ callerId +", target: "+ target +", reason: "+ reason +"]");
	try {
		// show warning
		showWarning("Cannot transfer call to agent / extension '"+ target +"'. "+ reason +"");
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'callTransferFailed' function with param [callerId: "+ callerId +", target: "+ target + ", reason: "+ reason +"] because of exception",
				ex
			);
	}
};
/**
 * Called by applet to inform call has been returned to IVR
 * @param {String} callerId
 * @param {String} context
 * @param {String} extension
 */
var callReturnedToIVR = function (callerId, context, extension){
	initialState();
	try {
		// reset 
		TELEPHONY_CALLER_ID = null;
		// stop call duration
		stopTimer();
		// update status message
		$("#telephony span#call_status").html("Call disconnected");
		// log
		TELEPHONY_LOG.debug("Call has been returned to IVR " + context + "/" + extension);
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'callReturnedToIVR' function with param [callerId: "+ callerId +", context: "+ context + ", extension: " + extension + "] because of exception",
				ex
			);
	}
};
/**
 * Called by applet to inform that couldn't return call to IVR
 * @param {String} callerId
 * @param {String} target
 * @param {String} reason
 */
var callReturnFailed = function (callerId, context, extension, reason){
	TELEPHONY_LOG.info("callReturnFailed [callerId: "+ callerId +", context: "+ context + ", extension: "+ context + ", reason: "+ reason +"]");
	try {
		// show warning
		showWarning("Cannot return call to IVR " + context + "/" + extension + ". " + reason);
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'callReturnFailed' function with param [callerId: "+ callerId +", context: "+ context + ", extension: "+ context + ", reason: "+ reason +"] because of exception",
				ex
			);
	}
};
/**
 * Called by applet to inform call is already parked
 * @param {String} callerId
 * @param {String} number
 * @param {String} parkId
 */
var callParked = function (callerId, number, parkId){
	TELEPHONY_LOG.info("callParked [callerId: "+ callerId +", number: "+ number +", parkId: "+ parkId +"]");
	initialState();
	try {
		var parkMember = $("#telephony button.unpark").length;
		if (parkMember==2){
			$("#telephony button#bridge").parent().show();
		}
		else {
			$("#telephony button#bridge").parent().hide();
		}
		// change unpark button text and bind click event
		$("#telephony button.unpark[data=\""+callerId+"\"]").text("unpark ("+ number +")").click(function(){
			unparkCall(callerId, parkId);
		});
		// reset
		resetNotesSize();
		resetActionMenuPosition();
		TELEPHONY_CALLER_ID = null;
		// stop call duration
		stopTimer();
		// update status message
		$("#telephony span#call_status").html("PARKED");
		$("#telephony span#telephony_notes").html("Call ("+ number +") is parked");
		$("#telephony input#a_number").removeAttr("readonly");
		$("#telephony input#a_number").val("");
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'callParked' function with param [callerId: "+ callerId +", number: "+ number + ", parkId: "+ parkId +"] because of exception",
				ex
			);
	}
};
/**
 * Called by applet to inform that couldn't park call
 * @param {String} callerId
 * @param {String} parkId
 * @param {String} reason
 */
var callParkFailed = function (callerId, parkId, reason){
	TELEPHONY_LOG.info("callParkFailed [callerId: "+ callerId +", parkId: "+ parkId +", reason: "+ reason +"]");
	initialState();
	try {
		// remove unpark button 
		$("#telephony button.unpark[data=\""+ callerId +"\"]").parent().hide("fast", function(){
			$(this).remove();
			// reset
			resetNotesSize();
			resetActionMenuPosition();
		});
		// show warning
		showWarning("Cannot park call to park ID '"+ parkId +"'. "+ reason +"");
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'callParkFailed' function with param [callerId: "+ callerId +", parkId: "+ parkId + ", reason: "+ reason +"] because of exception",
				ex
			);
	}
};
/**
 * Called by applet to inform call is already unparked (re-connected)
 * @param {String} callerId
 * @param {String} number
 * @param {String} parkId
 */
var callUnparked = function (callerId, number, parkId){
	TELEPHONY_LOG.info("callUnparked [callerId: "+ callerId +", number: "+ number +", parkId: "+ parkId +"]");
	callActiveState();
	try {
		// remove unpark button 
		$("#telephony button.unpark[data=\""+ callerId +"\"]").parent().hide("fast", function(){
			$(this).remove();
			// reset
			resetNotesSize();
			resetActionMenuPosition();
		});
		// set caller id
		TELEPHONY_CALLER_ID = callerId;
		// reset
		TELEPHONY_IS_UNPARKING = false;
		// update status message
		$("#telephony span#call_status").html("UNPARKED");
		$("#telephony span#telephony_notes").html("Call ("+ number +") is unparked (re-connected)");
		$("#telephony input#a_number").val(number);
		$("#telephony input#a_number").attr("readonly",true);
		// start call duration
		startTimer();
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'callUnparked' function with param [callerId: "+ callerId +", number: "+ number +", parkId: "+ parkId +"] because of exception",
				ex
			);
	}
};
/**
 * Called by applet to inform that couldn't unpark call
 * @param {String} callerId
 * @param {String} parkId
 * @param {String} reason
 */
var callUnparkFailed = function (callerId, parkId, reason){
	TELEPHONY_LOG.info("callUnparkFailed [callerId: "+ callerId +", parkId: "+ parkId +", reason: "+ reason +"]");
	initialState();
	try {
		// remove unpark button 
		$("#telephony button.unpark[data=\""+ callerId +"\"]").parent().hide("fast", function(){
			$(this).remove();
			// reset
			resetNotesSize();
			resetActionMenuPosition();
		});
		// show warning
		showWarning("Cannot unpark call from park ID '"+ parkId +"'. "+ reason +"");
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'callUnparkFailed' function with param [callerId: "+ callerId +", parkId: "+ parkId + ", reason: "+ reason +"] because of exception",
				ex
			);
	}
};
/**
 * Called by applet to inform call has diconnected on park (or abandoned while unparking)
 * @param {String} callerId
 * @param {String} number
 */
var callAbandonedOnPark = function (callerId, number){
	TELEPHONY_LOG.info("callAbandonedOnPark [callerId: "+ callerId +", number: "+ number +"]");
	// remove (related) unpark button
	if ($("#telephony button.unpark[data=\""+ callerId +"\"]").length>0){
		$("#telephony button.unpark[data=\""+ callerId +"\"]").parent().hide("fast", function(){
			$(this).remove();
			// reset
			resetNotesSize();
			resetActionMenuPosition();
		});
	}
};
/**
 * Called by applet to inform conference ID
 * @param {String} value
 */
var conferenceId = function (value){
	TELEPHONY_LOG.info("conferenceId [value: "+ value +"]");
	try {
		TELEPHONY_CONFERENCE_ID = value;
		// show conference button
		$("#telephony #action button:contains('conference')").parent().show();
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'conferenceId' function with param [value: "+ value +"] because of exception",
				ex
			);
	}
};
/**
 * Called by applet to inform conference status
 * @param {String} conferenceId
 * @param {String} isActive
 * @param {String} member
 */
var conferenceStatus = function (conferenceId, isActive, member){
	TELEPHONY_LOG.info("conferenceStatus [conferenceId: "+ conferenceId +", isActive: "+ isActive +", member: "+ member +"]");
	// do nothing
};
/**
 * Called by applet to inform call has joined conference
 * @param {String} callerId
 * @param {String} number
 * @param {String} conferenceId
 */
var callJoined = function (callerId, number, conferenceId){
	TELEPHONY_LOG.info("callJoined [callerId: "+ callerId +", number: "+ number +", conferenceId: "+ conferenceId +"]");
	initialState();
	try {
		// register
		TELEPHONY_CONFERENCE_MEMBER.push(callerId);
		// check if join conference button does not exist
		if ($("#telephony button.join").length==0){
			// create join conference button (for agent)
			var agentJoinConferenceButton = "<button style='background-color: #00C161;' onclick='agentJoinConference(\""+ conferenceId +"\")' class='join' data='"+ conferenceId +"'>join ("+ conferenceId +")</button>";
			// append to wrapper
			$("#telephony .wrapper:eq(2)").append(agentJoinConferenceButton);
			// wrap with corner
			$("#telephony button.join[data=\""+ conferenceId +"\"]").show();
			// if (!$.browser.msie){
				// $("#telephony button.join[data=\""+ conferenceId +"\"]").corner("round 2px").parent().corner("round 2px");
			// }

			// create leave conference button (for agent, hide)
			var agentLeaveConferenceButton = "<button style='background-color: #00C161;' onclick='agentLeaveConference(\""+ conferenceId +"\")' class='leave' data='"+ conferenceId +"'>leave ("+ conferenceId +")</button>";
			// insert after 'park' button before 'a_number'
			$("#telephony .wrapper:eq(2)").append(agentLeaveConferenceButton);
			// wrap with corner
			$("#telephony button.leave[data=\""+ conferenceId +"\"]").show();
			// if (!$.browser.msie){
				// $("#telephony button.leave[data=\""+ conferenceId +"\"]").corner("round 2px").parent().corner("round 2px");
			// }
			$("#telephony button.leave[data=\""+ conferenceId +"\"]").parent().hide();
		}
		// check if kick conference button does not exist
		if ($("#telephony button.kick").length==0){
			// create close conference button
			var closeConferenceButton = "<button style='background-color: #00C161;' onclick='closeConference(\""+ conferenceId +"\")' class='kick' data='"+ conferenceId +"'>close ("+ conferenceId +")</button>";
			// append to wrapper
			$("#telephony .wrapper:eq(2)").append(closeConferenceButton);
			// wrap with corner
			$("#telephony button.kick[data=\""+ conferenceId +"\"]").show();
			// if (!$.browser.msie){
				// $("#telephony button.kick[data=\""+ conferenceId +"\"]").corner("round 2px").parent().corner("round 2px");
			// }
		}
		// reset 
		TELEPHONY_CALLER_ID = null;
		// stop call duration
		stopTimer();
		// update status message
		$("#telephony span#call_status").html("JOINED (conference)");
		$("#telephony input#a_number").removeAttr("readonly");
		$("#telephony input#a_number").val("");
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'callJoined' function with param [callerId: "+ callerId +", number: "+ number + ", conferenceId: "+ conferenceId +"] because of exception",
				ex
			);
	}
	$("#telephony span#telephony_notes").html("&nbsp;");
};
/**
 * Called by applet to inform that couldn't join call to conference
 * @param {String} callerId
 * @param {String} conferenceId
 * @param {String} reason
 */
var callJoinFailed = function (callerId, conferenceId, reason){
	TELEPHONY_LOG.info("callJoinFailed [callerId: "+ callerId +", conferenceId: "+ conferenceId +", reason: "+ reason +"]");
	initialState();
	try {
		// show warning
		showWarning("Cannot join call to conference '"+ conferenceId +"'. "+ reason +"");
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'callJoinFailed' function with param [callerId: "+ callerId +", conferenceId: "+ conferenceId + ", reason: "+ reason +"] because of exception",
				ex
			);
	}
};
/**
 * Called by applet to inform agent is already joined to conference
 * @param {String} callerId
 * @param {String} number
 */
var agentJoined = function (callerId, agentId, conferenceId){
	TELEPHONY_LOG.info("agentJoined [callerId: "+ callerId +", agentId: "+ agentId +", conferenceId: "+ conferenceId +"]");
	callActiveState();
	try {
		TELEPHONY_CALLER_ID = callerId;
		// update status message
		$("#telephony span#call_status").html("JOINED (conference)");
		$("#telephony span#telephony_notes").html("You have joined conference");
		$("#telephony input#a_number").val(conferenceId);
		$("#telephony input#a_number").attr("readonly",true);
		// start call duration
		startTimer();
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'agentJoined' function with param [callerId: "+ callerId +", agentId: "+ agentId +", conferenceId: "+ conferenceId +"] because of exception",
				ex
			);
	}
};
/**
 * Called by applet to inform that agent couldn't join call to conference
 * @param {String} agentId
 * @param {String} conferenceId
 * @param {String} reason
 */
var agentJoinFailed = function (agentId, conferenceId, reason){
	TELEPHONY_LOG.info("agentJoinFailed [agentId: "+ agentId +", conferenceId: "+ conferenceId +", reason: "+ reason +"]");
	try {
		// show warning
		showWarning("Cannot join call to conference '"+ conferenceId +"'. "+ reason +"");
		// show join button
		$("#telephony button.join[data=\""+ conferenceId +"\"]").parent().show();
		// show leave button
		$("#telephony button.leave[data=\""+ conferenceId +"\"]").parent().fast();
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'agentJoinFailed' function with param [agentId: "+ agentId +", conferenceId: "+ conferenceId + ", reason: "+ reason +"] because of exception",
				ex
			);
	}
};
/**
 * Called by applet right after receive broadcast message
 * @param {String} message
 * @param {String} from
 */
var receiveBroadcastMessage = function (message, from){
	TELEPHONY_LOG.info("receiveBroadcastMessage [message: "+ message +", from: "+ from +"]");
	try {
		// convert message to JSON (if so)
		message = $.secureEvalJSON(message);
		// TODO: do something here
	}
	catch (ex){
		TELEPHONY_LOG.error(
				"Error while running 'receiveBroadcastMessage' function with param [message: "+ message +", from: "+ from +"] because of exception",
				ex
			);
	}
};

// functions or methods that called by web element / another script
// ------------------------------------------------------------------------
/**
 * Return the connection controller
 */
var getController = function (){
	return TELEPHONY_CONTROLLER;
};
/**
 * Change agent status into AUTO_IN / IDLE
 */
var autoIn = function (){
	TELEPHONY_LOG.debug("autoIn [currentstatus: "+ TELEPHONY_CURRENT_STATUS +"]");
	try {
		if (TELEPHONY_CURRENT_STATUS!="AUTO_IN" && TELEPHONY_CURRENT_STATUS!="RINGING" && TELEPHONY_CURRENT_STATUS!="RESERVED"){
			if (TELEPHONY_CALLER_ID==null){
				$("#telephony span#call_duration").html("&nbsp;");
				$("#telephony span#call_status").html("&nbsp;");
				$("#telephony span#telephony_notes").html("&nbsp;");
				getController().autoIn();
			}
			else{
				showWarning("Cannot change your status because you're online right now");
			}
		}
	}
	catch (ex){
		TELEPHONY_LOG.error("Error while running 'autoIn' function because of exception", ex);
	}
};
/**
 * Change agent status into AUX
 * @param {String} reason (optional)
 */
var aux = function (reason){
	TELEPHONY_LOG.debug("aux [currentstatus: "+ TELEPHONY_CURRENT_STATUS +", reason: "+ (reason!==undefined?reason:"") +"]");
	try {
		//if (TELEPHONY_CURRENT_STATUS!="AUX"){
			if (TELEPHONY_CALLER_ID==null){
				$("#telephony span#call_duration").html("&nbsp;");
				$("#telephony span#call_status").html("&nbsp;");
				$("#telephony span#telephony_notes").html("&nbsp;");
				if (reason!==undefined){
					getController().aux(reason);
				}
				else{
					getController().aux(null);
				}
			}
			else{
				showWarning("Cannot change your status because you're online right now");
			}
		//}
	}
	catch (ex){
		TELEPHONY_LOG.error("Error while running 'aux' function [reason: "+ (reason!==undefined?reason:"") +"] because of exception", ex);
	}
};
/**
 * Change agent status into ACW
 * @param {String} reason (optional)
 */
var acw = function (reason){
	TELEPHONY_LOG.debug("acw [currentstatus: "+ TELEPHONY_CURRENT_STATUS +", reason: "+ (reason!==undefined?reason:"") +"]");
	try {
		if (TELEPHONY_CURRENT_STATUS!="ACW"){
			if (TELEPHONY_CALLER_ID==null){
				$("#telephony span#call_duration").html("&nbsp;");
				$("#telephony span#call_status").html("&nbsp;");
				$("#telephony span#telephony_notes").html("&nbsp;");
				if (reason!==undefined){
					getController().acw(reason);
				}
				else {
					getController().acw(null);
				}
			}
			else{
				showWarning("Cannot change your status because you're online right now");
			}
		}
	}
	catch (ex){
		TELEPHONY_LOG.error("Error while running 'acw' function [reason: "+ (reason!==undefined?reason:"") +"] because of exception", ex);
	}
};
/**
 * Change agent status into OUTBOUND
 */
var outbound = function (){
	TELEPHONY_LOG.debug("outbound [currentstatus: "+ TELEPHONY_CURRENT_STATUS +"]");
	try {
		if (TELEPHONY_CURRENT_STATUS!="OUTBOUND"){
			if (TELEPHONY_CALLER_ID==null){
				$("#telephony span#call_duration").html("&nbsp;");
				$("#telephony span#call_status").html("&nbsp;");
				$("#telephony span#telephony_notes").html("&nbsp;");
				getController().outbound();
			}
			else{
				showWarning("Cannot change your status because you're online right now");
			}
		}
	}
	catch (ex){
		TELEPHONY_LOG.error("Error while running 'outbound' function because of exception", ex);
	}
};
/**
 * Dial out
 * @param {String} number
 * @param {String} customerData (optional)
 * @param {String} timeout (optional)
 * @param {String} dialOptions (optional, Asterisk Dial Out)
 * @param {String} originator (optional)
 */
var originateCall = function (number, customerData, timeout, dialOptions, originator){
	number = $.trim(number);
	if (!isNaN(number)&& number.length>0 && number !== ""){
		TELEPHONY_LOG.debug("originateCall [number: "+ number +", customerData: "+ (customerData!==undefined?customerData:"") +", timeout: "+ (timeout!==undefined?timeout:"") +", dialOptions: "+ (dialOptions!==undefined?dialOptions:"") +", originator: "+ (originator!==undefined?originator:"") + "]");
		try {
			if (number!==undefined){
				if (customerData === undefined){
					customerData = null;
				}
				if (timeout === undefined){
					timeout = null;
				}
				if (dialOptions === undefined){
					dialOptions = null;
				}
				if (originator === undefined){
					originator = null;
				}
				// reset status
				TELEPHONY_CURRENT_STATUS = null;
				if ($.trim(number).length>0 && TELEPHONY_CALLER_ID==null){
					getController().originateCall(number, customerData, timeout, dialOptions, originator);
				}
			}
		}
		catch (ex){
			TELEPHONY_LOG.error("Error while running 'originateCall' function [number: "+ number +", customerData: "+ (customerData!==undefined?customerData:"") +"] because of exception", ex);
		}
	}
	else{
		showWarning("Invalid telephone number: " + number + ". Please use numeric input only and complete it with area code", 6000);
		TELEPHONY_LOG.error("Invalid telephone number: " + number);
	}
};
/**
 * Park Call
 */
var parkCall = function (){
	if (TELEPHONY_CALLER_ID!=null){
		if ($("#telephony button.unpark[data=\""+ TELEPHONY_CALLER_ID +"\"]").length<=0){
			// create 'unpark' button
			var unparkCallButton = "<button style='background-color: #00C161;' class='unpark' data='"+ TELEPHONY_CALLER_ID +"'>parking...</button>";

			// insert after 'park' button before 'a_number'
			$("#telephony #a_number").parent().before(unparkCallButton);
			// hide 'park' button
			//$("#telephony button.park").parent().hide();
			// wrap with corner
			$("#telephony button.unpark[data=\""+ TELEPHONY_CALLER_ID +"\"]").show();
			// if (!$.browser.msie){
				// $("#telephony button.unpark[data=\""+ TELEPHONY_CALLER_ID +"\"]").corner("round 2px").parent().corner("round 2px");
			// }
			// reset
			resetNotesSize();
			resetActionMenuPosition();
			// park call
			try {
				getController().parkCall(TELEPHONY_CALLER_ID);
			}
			catch (ex){
				TELEPHONY_LOG.error("Error while running 'parkCall' function [callerId: "+ TELEPHONY_CALLER_ID +"] because of exception", ex);
			}
		}
		else {
			TELEPHONY_LOG.warn("Call " + TELEPHONY_CALLER_ID + " is already parked");
		}
	}
	else{
		showWarning("Cannot park non-active call");
		TELEPHONY_LOG.warn("Cannot park non-active call");
	}
};
/**
 * Unpark call
 * @param {String} callerId
 * @param {String} parkId
 */
var unparkCall = function (callerId, parkId){
	if (TELEPHONY_IS_UNPARKING){
		showWarning("Cannot unpark call because the other unpark process is active");
		TELEPHONY_LOG.warn("Cannot unpark call because the other unpark process is active");
		return;
	}
	if (TELEPHONY_CALLER_ID!=null){
		showWarning("Cannot unpark call because there is an active call");
		TELEPHONY_LOG.warn("Cannot unpark call because there is an active call");
		return;
	}
	if (callerId!==undefined && parkId!==undefined){
		// change unpark button text
		$("#telephony button.unpark[data=\""+ callerId +"\"]").text("unparking...");
		// unpark call
		try {
			getController().unparkCall(callerId, parkId);
			// set flag
			TELEPHONY_IS_UNPARKING = true;
		}
		catch (ex){
			TELEPHONY_LOG.error("Error while running 'unparkCall' function [callerId: "+ callerId +", parkId: "+ parkId +"] because of exception", ex);
		}
	}
	else{
		showWarning("Cannot unpark because one or both CALL_ID and PARK_ID is undefined");
		TELEPHONY_LOG.warn("Cannot unpark because one or both CALL_ID and PARK_ID is undefined");
	}
};
/**
 * Create room (get conference ID)
 */
var createRoom = function (){
	// hide conference button
	$("#telephony #action button:contains('conference')").parent().hide();
	// reset
	TELEPHONY_CONFERENCE_ID		= null;
	TELEPHONY_CONFERENCE_MEMBER = new Array();
	// get conference ID
	getController().getConferenceId();
};
/**
 * Call join conference
 * @param {String} timeLimit
 */
var callJoinConference = function (timeLimit){
	if (TELEPHONY_CONFERENCE_ID!=null){
		// check if any active call
		if (TELEPHONY_CALLER_ID!=null){
			try {
				if (timeLimit === undefined){
					timeLimit = null;
				}
				// join conference
				getController().callJoinConference(TELEPHONY_CALLER_ID, TELEPHONY_CONFERENCE_ID, timeLimit);
			}
			catch (ex){
				TELEPHONY_LOG.error("Error while running 'callJoinConference' function [callerId: "+ TELEPHONY_CALLER_ID +", conferenceId: "+ TELEPHONY_CONFERENCE_ID +"] because of exception", ex);
			}
		}
		else {
			showWarning("Call cannot be joined to conference because call is not active");
			TELEPHONY_LOG.warn("Call cannot be joined to conference because call is not active");
		}
	}
	else{
		showWarning("Call cannot be joined to conference because CONFERENCE ID is undefined");
		TELEPHONY_LOG.warn("Call cannot be joined to conference because CONFERENCE ID is undefined");
	}
};
/**
 * Agent join conference
 * @param {String} conferenceId
 */
var agentJoinConference = function(conferenceId){
	// check if any active call
	if (TELEPHONY_CALLER_ID==null){
		try{
			// join conference
			getController().agentJoinConference(conferenceId);
			// hide join button
			$("#telephony button.join[data=\""+ conferenceId +"\"]").parent().hide();
			// show leave button
			$("#telephony button.leave[data=\""+ conferenceId +"\"]").parent().show();
		}
		catch (ex){
			// show join button
			$("#telephony button.join[data=\""+ conferenceId +"\"]").parent().show();
			// hide leave button
			$("#telephony button.leave[data=\""+ conferenceId +"\"]").parent().hide();
			// log
			TELEPHONY_LOG.error("Error while running 'agentJoinConference' function [conferenceId: "+ conferenceId +"] because of exception", ex);
		}
	}
	else{
		showWarning("Cannot join conference because call is active");
		TELEPHONY_LOG.warn("Cannot join conference because call is active");
	}
};
/**
 * Agent leave conference
 * @param {String} conferenceId
 */
var agentLeaveConference = function(conferenceId){
	// check if any active call
	if (TELEPHONY_CALLER_ID!=null){
		try{
			// leave conference
			getController().leaveConference(TELEPHONY_CALLER_ID, conferenceId);
			// show join button
			$("#telephony button.join[data=\""+ conferenceId +"\"]").parent().show();
			// hide leave button
			$("#telephony button.leave[data=\""+ conferenceId +"\"]").parent().hide();
		}
		catch (ex){
			TELEPHONY_LOG.error("Error while running 'agentLeaveConference' function [conferenceId: "+ conferenceId +"] because of exception", ex);
		}
	}
	else{
		showWarning("Cannot join conference because call is active");
		TELEPHONY_LOG.warn("Cannot join conference because call is active");
	}
};
/**
 * Close conference
 * @param {String} conferenceId
 */
var closeConference = function (conferenceId){
	try {
		for (var i in TELEPHONY_CONFERENCE_MEMBER){
			// get caller id
			var callerId = TELEPHONY_CONFERENCE_MEMBER[i];
			// leave conference
			getController().leaveConference(callerId, conferenceId);
			// log
			TELEPHONY_LOG.info(callerId + " is about to leave conference ("+ conferenceId +")");
		}
		// remove conference related button 
		$("#telephony button.join, #telephony button.leave, #telephony button.kick").parent().hide("fast", function(){
			$(this).remove();
		});
		// hide conference button
		$("#telephony #action button:contains('conference')").parent().hide();
		// reset
		TELEPHONY_CONFERENCE_MEMBER = new Array();
		TELEPHONY_CONFERENCE_ID = null;
	}
	catch (ex){
		TELEPHONY_LOG.error("Error while running 'closeConference' function [conferenceId: "+ TELEPHONY_CONFERENCE_ID +"] because of exception", ex);
	}
	$("#telephony button.kick").parent().remove();
};
/**
 * Bridge 2 parked calls
 */
var bridgeCall = function (){
	try{
		var parkMember = $("#telephony button.unpark").length;
		if (parkMember==2){
			var callerId1 = null;
			var callerId2 = null;
			$("#telephony button.unpark").each(function(idx, obj){
				if (idx==0){
					callerId1 = $(obj).attr("data");
				}
				else if (idx==1) {
					callerId2 = $(obj).attr("data");
				}
				// remove unpark button 
				$(obj).parent().hide("fast", function(){
					$(this).remove();
					// reset
					resetNotesSize();
					resetActionMenuPosition();
				});
			});
			// log
			TELEPHONY_LOG.info(callerId1 + " and "+ callerId2 +" will be bridged");
			// bridge call
			getController().bridgeCall(callerId1, callerId2);
		}
		else {
			TELEPHONY_LOG.warn("Current parked call member = " + parkMember);
		}
		$("#telephony button#bridge").parent().hide();
	}
	catch (ex){
		TELEPHONY_LOG.error("Error while running 'bridgeCall' function because of exception", ex);
	}
};
/**
 * Try to transfer call
 */
var triggerTransferCall = function (){
	TELEPHONY_LOG.debug("Try to transfer Call [callerId: " + TELEPHONY_CALLER_ID + "]");
	if (TELEPHONY_CALLER_ID!=null){
		// $("#transfer_dialog").dialog("open");
	}
	else{
		showWarning("Cannot transfer non-active call");
		TELEPHONY_LOG.warn("Cannot transfer non-active call");
	}
};
/**
 * Pass transfer call request to CTI
 */
var transferCall = function (){
	TELEPHONY_LOG.debug("transferCall [callerId: " + TELEPHONY_CALLER_ID + "]");
	if (TELEPHONY_CALLER_ID!=null){
		var target = $.trim($("input[name='agent_target']").val());
		TELEPHONY_LOG.info("Transfer call "+ TELEPHONY_CALLER_ID +" to agent (or extension) " + target);
		if (target.length>0){
			try {
				getController().transferCall(TELEPHONY_CALLER_ID, target);
			}
			catch (ex){
				TELEPHONY_LOG.error("Error while running 'transferCall' function [callerId: "+ TELEPHONY_CALLER_ID +", target: "+ target +"] because of exception", ex);
			}
		}
		else{
			TELEPHONY_LOG.warn("Cannot transfer call to undefined agent / extension");
		}
	}
	else{
		showWarning("Cannot transfer non-active call");
		TELEPHONY_LOG.warn("Cannot transfer non-active call");
	}
};
/**
 * Return call to IVR
 * @param {String} context
 * @param {String} extension
 */
var returnCallToIVR = function (context, extension){
	context = $.trim(context);
	extension = $.trim(extension);
	if (TELEPHONY_CALLER_ID!=null){
		if (context.length>0 && extension.length>0 && !isNaN(extension)){
			TELEPHONY_LOG.debug("returnCallToIVR [callerId: " + TELEPHONY_CALLER_ID + ", context: "+ context +", extension: "+ extension +"]");
			try {
				getController().returnCallToIVR(TELEPHONY_CALLER_ID, context, extension);
			}
			catch (ex){
				TELEPHONY_LOG.error("Error while running 'returnCallToIVR' function [callerId: " + TELEPHONY_CALLER_ID + ", context: "+ context +", extension: "+ extension +"] because of exception", ex);
			}
		}
		else{
			TELEPHONY_LOG.error("Incomplete or invalid parameter: returnCallToIVR [callerId: " + TELEPHONY_CALLER_ID + ", context: "+ context +", extension: "+ extension +"]");
		}
	}
	else{
		TELEPHONY_LOG.warn("Cannot return non-active call to IVR");
	}
};
/**
 * Send DTMF
 * @param {String} digits
 */
var sendDTMF = function (digits){
	TELEPHONY_LOG.debug("sendDTMF [callerId: " + TELEPHONY_CALLER_ID + ", digits: "+ digits +"]");
	if (TELEPHONY_CALLER_ID!=null){
		try {
			getController().sendDTMF(TELEPHONY_CALLER_ID, digits);
		}
		catch (ex){
			TELEPHONY_LOG.error("Error while running 'sendDTMF' function [callerId: " + TELEPHONY_CALLER_ID + ", digits: "+ digits +"]] because of exception", ex);
		}
	}
	else{
		TELEPHONY_LOG.warn("Cannot send DTMF for non-active call");
	}
};
/**
 * Try to disconnect call
 */
var disconnectCall = function (){
	TELEPHONY_LOG.debug("disconnectCall [callerId: " + TELEPHONY_CALLER_ID + "]");
	try {
		getController().disconnectCall(TELEPHONY_CALLER_ID);
		if (TELEPHONY_CALLER_ID != null){
			TELEPHONY_CALLER_ID = null;
		}
	}
	catch (ex){
		TELEPHONY_LOG.error("Error while running 'disconnectCall' function [callerId: "+ TELEPHONY_CALLER_ID +"] because of exception", ex);
	}
};
/**
 * Send broadcast message
 */
var sendBroadcastMessage = function (message, to){
	TELEPHONY_LOG.debug("sendBroadcastMessage [message: " + message + ", to: "+ to +"]");
	try {
		// serialize 
		message = $.toJSON(message);
		// broadcast message
		getController().broadcastMessage(message, to);
	}
	catch (ex){
		TELEPHONY_LOG.error("Error while running 'sendBroadcastMessage' function [message: " + message + ", to: "+ to +"] because of exception", ex);
	}
};
// common functions
// ------------------------------------------------------------------------
/**
 * Show call duration
 */
var showCallDuration = function (){
	var now = new Date();
	var hours	= now.getHours();
	var minutes	= now.getMinutes();
	var seconds	= now.getSeconds();
	var previousHours	= TELEPHONY_START_TIME.getHours();
	var previousMinutes	= TELEPHONY_START_TIME.getMinutes();
	var previousSeconds	= TELEPHONY_START_TIME.getSeconds();

	if (seconds >= previousSeconds){
		seconds = seconds - previousSeconds;
	}
	else{
		seconds = (seconds + 60) - previousSeconds;
		minutes = minutes - 1;
	}
	if (minutes >= previousMinutes){
		minutes = minutes - previousMinutes;
	}
	else{
		minutes = (minutes + 60) - previousMinutes;
		hours = hours - 1;
	}
	if (hours >= previousHours){
		hours = hours - previousHours;
	}
	else{
		hours = (hours + 24) - previousHours;
	}
	var timeValue = ((hours < 10) ? "0" : "") + hours;
	timeValue += ((minutes < 10) ? ":0" : ":") + minutes;
	timeValue += ((seconds < 10) ? ":0" : ":") + seconds;
	$("#telephony span#call_duration").html(timeValue);

	TELEPHONY_TIMER_ID = setTimeout("showCallDuration()",1000);
	TELEPHONY_IS_TIMER_RUNNING = true;
};
/**
 * Stop call timer
 */
var stopTimer = function (){
	TELEPHONY_LOG.debug("stopTimer");		
	try {
		if (TELEPHONY_IS_TIMER_RUNNING){
			clearTimeout(TELEPHONY_TIMER_ID);
		}
		timerRunning = false;
	}
	catch (ex){
		TELEPHONY_LOG.error("Error while running 'stopTimer' function because of exception", ex);
	}
};
/**
 * Start call timer
 */
var startTimer = function (){
	stopTimer();
	TELEPHONY_LOG.debug("startTimer");	
	try {
		TELEPHONY_START_TIME = new Date();
		showCallDuration();
	}
	catch (ex){
		TELEPHONY_LOG.error("Error while running 'startTimer' function because of exception", ex);
	}
};
/**
 * Validate MSISDN
 */
var validateMsisdn = function (obj){
	var input = obj.value;
	var output = "";
	if (isNaN(input)){
		for (var i=0;i<input.length;i++){
			if (!isNaN(input.charAt(i)) && (input.charAt(i)!=' ')){
				output = output + input.charAt(i);
			}
		}
		obj.value = output;
	}
}
/**
 * Validate numeric input
 */
var validateNumericInput = function (obj){
	if (obj.value=="< type a number in here >"){
		obj.value= "";
	}
	else {
		validateMsisdn (obj);
		var input = obj.value;
		if (input.length==0){
			obj.value = "";
		}
	}
};
/**
 * Validate numeric input
 */
var validateDTMF = function (obj){
	var input = obj.value;
	var output = "";
	for (var i=0;i<input.length;i++){
		if (!isNaN(input.charAt(i)) || (input.charAt(i)=='#') || (input.charAt(i)=='*')){
			output = output + input.charAt(i);
		}
	}
	obj.value = output;
};
// UI
// ------------------------------------------------------------------------
// reset size for notes
var resetNotesSize = function(){
	// get height for telephony toolbar
	var toolbarHeight = $("#telephony").height();
	// get document width
	var documentWidth = $(document).width();
	// get duration offset
	var leftSiblingOffset = $("#telephony .wrapper:eq(4)").offset();
	// get duration width
	var leftSiblingWidth = $("#telephony .wrapper:eq(4)").width();
	// get max width
//	var maxWidth = documentWidth - (leftSiblingOffset.left + leftSiblingWidth + 41);
	// var maxWidth = documentWidth - (leftSiblingOffset.left + leftSiblingWidth + 41);
	// if ($.browser.msie){
		// maxWidth -= 1;
	// }
	// if (maxWidth<0){
		// maxWidth = 0;
	// }
	// set max width for notes
	// $("#telephony .wrapper:eq(5)").css({
		// "max-width": maxWidth,
		// "width" : maxWidth
	// });
};
// reset action menu position
var resetActionMenuPosition = function (){
	/*
	var actionButtonOffset = $("#telephony button:contains('action')").position();
	console.log('actionButtonOffset lef	t'+actionButtonOffset.left);
	$("#telephony #action.menu").css({
		"left": actionButtonOffset.left
	});
	*/
};
// create telephony menu handler
var createTelephonyMenuHandler = function (id, textButton){
	var hide = function(){
		$("#telephony #"+ id +".menu").hide();
	};
	var show = function(){
		resetActionMenuPosition();
			$("#telephony #"+ id +".menu").css({
					"display": "block",
					"zIndex": 9999999999999,
					"bottom": "25px"
				});
	};
	var isShown = function(){
		return $("#telephony #"+ id +".menu").is(":visible");
	};
	if (textButton===undefined){
		textButton = id;
	}
	$("#telephony button:contains('"+ textButton +"')")
	.hover(
		function(e){
			if (!isShown()){
				show();
			}
			$("#telephony .menu").not("#"+ id +"").hide();
		},
		function(){
			// do nothing
		}
	)
	.click(function(){
		if (isShown()){
			hide();
		}
		else{
			show();
		}
		$("#telephony .menu").not("#"+ id +"").hide();
	});
	var parent = $("#telephony button:contains('"+ textButton +"')").parent();
	parent.next().hover(function(){
		if (isShown()){
			hide();
		}
	});
	parent.prev().hover(function(){
		if (isShown()){
			hide();
		}
	});
	$("#telephony #"+ id +".menu").hover(function(){}, function(){
		if (isShown()){
			hide();
		}
	});
	$("#telephony button."+ id +"").click(function(){
		if (isShown()){
			hide();
		}
	});
};
// initial state
var initialState = function (){
	// hide telephony menu
	//$("#telephony .menu").hide();
	// show dial button
	$("#telephony button#dial").parent().show();
	$("#telephony button#bridge").parent().hide();
	// hide disconnect button
	$("#telephony button:contains('disconnect')").parent().hide();
	// hide action button
	$("#telephony button:contains('action')").parent().hide();
	// reset
	resetNotesSize();
	resetActionMenuPosition();
};
// call active state
var callActiveState = function (){
	// hide dial button
	$("#telephony button#dial").parent().hide();
	// show disconnect button
	$("#telephony button:contains('disconnect')").parent().show();
	// show action button
	$("#telephony button:contains('action')").parent().show();
	// reset
	resetNotesSize();
	resetActionMenuPosition();
};// ------------------------------------------------------------------------
// called when DOM is ready
$(document).ready(function(){
	// wrap with a cornered panel
	$("#telephony button, #telephony input").each(function (){
		
		//$(this).wrap("<div class='outer'></div>");
		// if (!$.browser.msie){
			// $(this).corner("round 2px").parent().corner("round 2px");
		// }
	});
	// prevent dashed border on button while clicked
	$("#telephony button").click(function (){
		this.blur();
	});
	// prevent double click for auto in button
	$("#telephony button").dblclick(function (event){
		showWarning('Do single click only, double click is forbidden!!!');
		event.preventDefault();
		return false;
	});
	initialState();
	resetNotesSize();
	resetActionMenuPosition();
	// hide conference button
	$("#telephony #action button:contains('conference')").parent().hide();

	// aux button and menu handling
	var auxButtonOffset = $("#telephony button:contains('aux')").offset();
	$("#telephony #aux.menu").css({
		"left": auxButtonOffset.left+155
	});
	createTelephonyMenuHandler("aux");

	// action button and menu handling
	createTelephonyMenuHandler("action");

	// hide menu if mouse over #telephony
	$("#telephony").hover(
		function(){
			// do nothing
		},
		function(){
			$("#telephony .menu").hide();
		}
	);
	/* $("#telephony #aux.menu").css({
		"display": "block",
		"zIndex": 9999999999999,
		"bottom": "25px"
	}); */
});
// called when window resized
$(window).resize(function(){
	resetNotesSize();
});