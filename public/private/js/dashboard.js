var TELEPHONY_CONTROLLER_DASHBOARD	= null;
var TELEPHONY_CONTROLLER_DASHBOARD_AGENT	= null;
var TELEPHONY_CONTROLLER_DASHBOARD_TRUNK	= null;
var GLOBAL_OVERVIEW_MONITORING_VARS = new Array();
GLOBAL_OVERVIEW_MONITORING_VARS["flash_data"] = GLOBAL_MAIN['BASE_URL'] + "dashboard/get_flash/";


var sla = function(){
	$.ajax({
		type: "POST",
		url: "main_dashboard/sla/",
		dataType: "json",
		data: "context="+$("#optGroupOverview").val(),
		success: function(msg){
			totalCall(msg.total_call);
			callLess(msg.less);
			callMore(msg.more);
			abandoneRinging(msg.abandoneRinging);
			abandoneQueue(msg.abandoneQueue);
			abandoneTransfer(msg.abandoneTransfer);
			percent(msg.percent + ' %');
		}
	});		
}
var scripts = [
		GLOBAL_MAIN["BASE_URL"]+"assets/js/flash_detect-1.0.4.min.js",
		GLOBAL_MAIN["BASE_URL"]+"assets/js/swfobject.js",
		GLOBAL_MAIN["BASE_URL"]+"assets/js/log4javascript.js",
	];	
	


var loadDataDashboard = function (){
	if (FlashDetect.installed && FlashDetect.versionAtLeast(11)){
		$.get(GLOBAL_OVERVIEW_MONITORING_VARS["flash_data"] + "?type=PERFORMANCE&group="+$("#optGroupOverview").val(), function (data){
			var moduleParams = {
				menu: "false",
				scale: "noScale",
				allowFullscreen: "false",
				allowScriptAccess: "always",
				bgcolor: "#FFFFFF"
			};
			var moduleAttributes = {
				id:"moduleDashboard"
			};
	
			swfobject.embedSWF(
				data.app, 
				"controllerDashboard", 
				"1", "1", 
				"11.0.0", 
				"swf/expressInstall.swf", 
				data.vars, 
				moduleParams, 
				moduleAttributes,
				function (e){
					if (e.success){
						TELEPHONY_CONTROLLER_DASHBOARD = document.getElementById("moduleDashboard");
						console.log(TELEPHONY_CONTROLLER_DASHBOARD);
					}
					else {
						alert("Cannot load eCentriX module");
					}
				}
			);
		}, "json");
	}else {
		needFlashClient();
	}
};
var loadDataDashboardAgent = function (){
	if (FlashDetect.installed && FlashDetect.versionAtLeast(11)){
		$.get(GLOBAL_OVERVIEW_MONITORING_VARS["flash_data"] + "?type=AGENT&group="+$("#optGroup").val(), function (data){
			var moduleParams = {
				menu: "false",
				scale: "noScale",
				allowFullscreen: "false",
				allowScriptAccess: "always",
				bgcolor: "#FFFFFF"
			};
			var moduleAttributes = {
				id:"moduleDashboardAgent"
			};
	
			swfobject.embedSWF(
				data.app, 
				"controllerDashboardAgent", 
				"1", "1", 
				"11.0.0", 
				"swf/expressInstall.swf", 
				data.vars, 
				moduleParams, 
				moduleAttributes,
				function (e){
					if (e.success){
						TELEPHONY_CONTROLLER_DASHBOARD_AGENT = document.getElementById("moduleDashboardAgent");
						console.log(TELEPHONY_CONTROLLER_DASHBOARD_AGENT);
					}
					else {
						alert("Cannot load eCentriX module");
					}
				}
			);
		}, "json");
	}
	else {
		needFlashClient();
	}
};

var loadDataDashboardTrunk = function (group){
	if (FlashDetect.installed && FlashDetect.versionAtLeast(11)){
		$.get(GLOBAL_OVERVIEW_MONITORING_VARS["flash_data"] + "?type=TRUNK&group="+group, function (data){
			var moduleParams = {
				menu: "false",
				scale: "noScale",
				allowFullscreen: "false",
				allowScriptAccess: "always",
				bgcolor: "#FFFFFF"
			};
			var moduleAttributes = {
				id:"moduleDashboardAgent"
			};
	
			swfobject.embedSWF(
				data.app, 
				"connection_container", 
				"1", "1", 
				"11.0.0", 
				"swf/expressInstall.swf", 
				data.vars, 
				moduleParams, 
				moduleAttributes,
				function (e){
					if (e.success){
						TELEPHONY_CONTROLLER_DASHBOARD_TRUNK = document.getElementById("connection_module");
						console.log(TELEPHONY_CONTROLLER_DASHBOARD_TRUNK);
					}
					else {
						alert("Cannot load eCentriX module");
					}
				}
			);
		}, "json");
	}
	else {
		needFlashClient();
	}
};

var loadTelephonyModule = function (){
	// load flash
	//loadDataDashboard();
	
};	

var counter = 0;
var total = scripts.length;
var loadScript = function (url, callback){
	var script = document.createElement("script")
	script.type = "text/javascript";
	if (script.readyState){  //IE
		script.onreadystatechange = function(){
			if (script.readyState == "loaded" || script.readyState == "complete"){
				script.onreadystatechange = null;
				callback();
			}
		};
	} 
	else {  //Others
		script.onload = callback;
	}
	script.src = url;
	document.getElementsByTagName("head")[0].appendChild(script);
};
var loadNextScript = function (){
	if (counter>=0 && counter<total){
		console.log(scripts[counter]);
		loadScript(scripts[counter], function (){
			counter++;
			var progress = (counter / total) * 100;
			loadNextScript();
		});
	}
	else{
		if (typeof loadTelephonyModule == "function"){
			loadTelephonyModule();
		}
	}
};
$(document).ready(function(){
	loadNextScript();
});	
var needFlashClient = function (msg){
	var result = confirm("Sorry, you need to enable and install latest flash player to see this content. Do you want to install it now?");
	if (result){
		window.location = "http://www.adobe.com/go/getflashplayer";
	}
	else {
		window.close();
	}
};
var needJRE = function (msg){
	var result = confirm("Sorry, you need to enable and install latest Java Runtime Engine (JRE) to see this content. Do you want to install it now?");
	if (result){
		window.location = "http://www.java.com/getjava/";
	}
	else {
		window.close();
	}
};

	var totalCall = function(val){
		$('#dataTotalCall').html(val);
	}

	var callLess = function(val){
		console.log('callLess = '+val);	
		$('#dataAnswerless20').html(val);		
	}

	var callMore = function(val){
		console.log('callMore = '+val);	
		$('#dataAnswermore20').html(val);			
	}

	var abandoneRinging = function(val){
		console.log('abandoneRinging = '+val);		
		$('#dataAbandonRinging').html(val);	
	}

	var abandoneQueue = function(val){
		console.log('abandoneQueue = '+val);	
		$('#dataAbandonQueue').html(val);			
	}

	var abandoneTransfer = function(val){
		console.log('abandoneTransfer = '+val);
		$('#dataAbandonTransfer').html(val);			
	}

	var percent = function(val){
		console.log('percent = '+val);	
		$('#percentTelephony, #percentTelephonySLA').html(val);
		$('#divPercentTelephony').removeAttr('data-percent');
		$('#divPercentTelephony').attr('data-percent','10');
	}

	var setTotalCallQueue = function (val){
		console.log('setTotalCallQueue = '+val);	
		$('#totalQueueCall').html(val);
	}

	var setTotalAnsweredCall = function (val){
		console.log('setTotalAnsweredCall = '+val);
		$('#totalAnswerCall').html(val);
	}
	var setTotalAbandonedCall = function (val){
		console.log('setTotalAbandonedCall = '+val);
		$('#totalAbandoneCall').html(val);
	}
	var setServiceLevel = function (val){
		console.log('setServiceLevel = '+val);
		$('#totalServiceLevel, #totalServiceLevelPercent').html(val);
	}
	var setTotalAgentIdle = function (val){
		console.log('setTotalAgentIdle = '+val);
		$('#totalAgentIdle').html(val);
		sla();
	}
	var setTotalAgentNotActive = function (val){
		console.log('setTotalAgentNotActive = '+val);
		$('#totalAgentNotActive').html(val);
	}
	var setTotalAgentTalking = function (val){
		console.log('setTotalAgentTalking = '+val);
		$('#totalAgentTalking').html(val);
	}
	var saveExcel = function (file,param){
		window.location = GLOBAL_MAIN["BASE_URL"]+file+param+'&ipAddress='+GLOBAL_MAIN["SERVER"];
	};
	

	var viewDetail = function(titles,widths,heights,id) {
		
		bootbox.dialog({

						message: '<div class="row"><div class="col-sm-12"><div class="widget-box"><div class="widget-header widget-header-flat widget-header-small"><h5><i class="icon-signal"></i>'+titles+'</h5></div><div class="widget-body"><div class="widget-main"><div id="'+id+'"></div></div><!-- /widget-main --></div><!--/widget-body--></div><!-- /widget-box --></div><!-- /span --></div>',
						title: titles,
						show: true,
						backdrop: true,
						closeButton: true,
						animate: true,
						/*buttons: 			
						{
							"success" :
							 {
								"label" : "<i class='icon-ok'></i> Success yah!",
								"className" : "btn-sm btn-success",
								"callback": function() {
									//Example.show("great success");
								}
							},
							"danger" :
							{
								"label" : "Danger!",
								"className" : "btn-sm btn-danger",
								"callback": function() {
									//Example.show("uh oh, look out!");
								}
							}, 
							"click" :
							{
								"label" : "Click ME!",
								"className" : "btn-sm btn-primary",
								"callback": function() {
									//Example.show("Primary button");
								}
							}, 
							"button" :
							{
								"label" : "Just a button...",
								"className" : "btn-sm"
							}
						}*/
					});
					
			$('#'+id).highcharts({
            chart: {
                type: 'spline',
                animation: Highcharts.svg, // don't animate in old IE
                marginRight: 10,
                events: {
                    load: function() {
    
                        // set up the updating of the chart each second
                        var series = this.series[0];
                        setInterval(function() {
                            var x = (new Date()).getTime(), // current time
                                y = Math.random();
                            series.addPoint([x, y], true, true);
                        }, 1000);
                    }
                }
            },
            title: {
                text: 'Live random data'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: 'Value'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+
                        Highcharts.numberFormat(this.y, 2);
                }
            },
            legend: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            series: [{
                name: 'Random data',
                data: (function() {
                    // generate an array of random data
                    var data = [],
                        time = (new Date()).getTime(),
                        i;
    
                    for (i = -19; i <= 0; i++) {
                        data.push({
                            x: time + i * 1000,
                            y: Math.random()
                        });
                    }
                    return data;
                })()
            }]
        });
	//				$('.modal-body').children().load('');
	};
