<?
	$connector			= "flash"; // options: applet, flash

	$sipServer			= null;
	$rtmpServer			= null;
	$ctiHost			= "10.7.12.211";
	$ctiPort			= "1080";						// CTI port for AGENT_DESKTOP 
	$ctiTimeout			= 30;							// timeout connection
	$isHosted			= false;
	$policyPort			= "1043";

	// javascript log level
	$jsLogLevel			= "ALL";			// ALL, ERROR, WARN, INFO, TRACE, DEBUG

	$agentId			= (@$_GET["agent_id"]?@$_GET["agent_id"]:"nita");
	$ipAddress			= (@$_GET["ip_address"]?@$_GET["ip_address"]:$_SERVER["REMOTE_ADDR"]);
?>
<style>
	/**
	 * start of could be removed
	 * ------------------------------------------------------------------------
	 */
	html{
		font-size: 12px; 
		line-height: 13px;
		margin: 0;
		padding: 0;
		/**/
		height:100%;
	}
	body {
		font-family: tahoma, verdana, arial, helvetica, sans-serif;
		color: #000000;
		margin: 0;
		padding: 0;
		min-width: 950px;
	}
	/**
	 * ------------------------------------------------------------------------
	 * end of could be removed
	 */
	html, body {
	   *height:100%;
	}
	body {
		margin-bottom: 40px;
	}
	#loader {
		font-family: tahoma, verdana, arial, helvetica, sans-serif;
		font-size: 12px; 
		min-width: 950px;
		margin: 0;
		position: fixed;
		top: auto;
		left: 0;
		bottom: 0px;
		width: 100%;
		text-align: left;
		background: #f0f0f0;
		height: 40px;
		z-index: 2;
		/*
		alpha(opacity=80); 
		opacity: 0.8; 
		*/
		/**/
		*min-height:100%;
		*position:absolute;
		*width:100%;
		*height:40px;
		*border-top: 1px solid #cccccc;	
	}
	#loader span{
		position: relative;
		top: 13px;
		left: 20px;
		float: left;
		*float: none;
	}
	#loader #progress{
		border: 1px solid #959595;
		width: 500px;
		height: 10px;
		background: white;
		position: relative;
		border-radius: 5px;
		top: 15px;
		left: 30px;
		float: left;
		*left: 80px;
		*top: 0px;
	}
	#loader #progress #bar{
		width: 0%;
		height: 100%;
		background: red;
	}
	#telephony {
		font-family: tahoma, verdana, arial, helvetica, sans-serif;
		font-size: 12px; 
		min-width: 950px;
		margin: 0;
		position: fixed;
		top: auto;
		left: 0;
		bottom: 0px;
		width: 100%;
		text-align: left;
		border-top: 1px solid #cccccc;
		background: #f0f0f0;
		height: 40px;
		z-index: 1;
		/**/
		*min-height:100%;
		*position:absolute;
		*width:100%;
		*height:20px;
		*padding: 0;
	}
	#telephony .wrapper {
		padding: 5px 10px 5px 10px;
		border-right: 1px solid #cccccc;
		float: left;
		text-align: center;
		height: 39px;
		/**/
		*height:20px;
	}
	#telephony .menu {
		padding: 5px 7px 5px 5px;
		background: #f0f0f0;
		border: 1px solid #cccccc;
		border-bottom: none;
		text-align: center;
		position: fixed;
		top: auto;
		left: 0;
		bottom: 41px;
		display: none;
		clear: both;
		*position: absolute;
		*bottom: 38px;
	}
	#telephony .menu button{
		padding: 0px 4px 2px 4px;
		display: block;
		text-align: center;
		width: 100px;
	}
	#telephony .menu input{
		padding: 0px 4px 2px 4px;
		display: block;
		text-align: center;
		width: 50px;
		clear: right;
		height: 19px;
	}
	#telephony .menu div.outer {
		*float: none;
		*margin-bottom: -11px;
	}
	#telephony button, #telephony input{
		font-size: 12px; 
		color: #ffffff;
		border: none;
		text-align: center;
		margin: 0;
		border: 0;
		zoom: 1;
		padding: 4px;
	}
	#telephony button{
		cursor: pointer; cursor: hand;
	}
	#telephony input{
		padding: 5px;
		background-color: #4f4f4f;
		/**/
		*height: 21px;
	}
	#telephony input#a_number {
		*width: 160px;
		*margin: -1px 0 -1px -2px;
		*height: 23px;
	}
	#telephony div.outer{
		background-color: white;
		border: 1px solid #cccccc;
		float: left;
		padding: 1px;
		margin-left: 2px;
	}
	#transfer_dialog {
		display: none;
	}
	#transfer_dialog label {
		width: 120px;
	}
	#transfer_dialog input {
		margin-top: 5px;
		padding: 5px;
		width: 250px;
	}
	#api, #controller{
		display: show;
	}
</style>
<link media="all" type="text/css" href="style/jquery/flick/jquery-ui.css" rel="stylesheet"/>
<div id="loader">
	<span>loading...</span><div id="progress"><div id="bar">&nbsp;</div></div>
</div>
<div id="telephony">
	<div class="menu" id="action">
		<button style="background-color: #0066FF;" class="action" onclick="triggerTransferCall()">transfer</button><br/>
		<button style="background-color: #0066FF;" class="action" onclick="returnCallToIVR('ecentrix-inbound','3000')">ivr 3000</button><br/>
		<button style="background-color: #0066FF;" class="action" onclick="parkCall()">park</button><br/>
		<!--<button style="background-color: #0066FF;" class="action" onclick="callJoinConference()">conference</button><br/>-->
		<button style="background-color: #0066FF;" class="action" onclick="createRoom('1001')">create room</button><br/>
		<button style="background-color: #0066FF;" class="action" onclick="callJoinConference('40')">conference</button><br/>
		<input id="dtmf" type="text" size="5" onKeyUp="validateDTMF(this);" onMouseUp="validateDTMF(this);"/>
		<button style="background-color: #0066FF; width: 44px;" class="action" onclick="sendDTMF(dtmf.value)">dtmf</button><br/>
	</div>
	<div class="menu" id="aux">
		<button style="background-color: #b02ee0;" class="aux" onclick="aux('sholat')">sholat</button><br/>
		<button style="background-color: #b02ee0;" class="aux" onclick="aux('makan')">makan</button><br/>
		<button style="background-color: #b02ee0;" class="aux" onclick="aux('briefing')">briefing</button><br/>
		<button style="background-color: #b02ee0;" class="aux" onclick="aux('toilet')">toilet</button><br/>
	</div>
	<div class="wrapper">
		<button style="background-color: #bce02e; color: #000000;" onclick="auto_conference()">bertiga</button>
		<button style="background-color: #bce02e; color: #000000;" onclick="autoIn()">idle</button>
		<button style="background-color: #e0642e;" onclick="acw()">acw</button>
		<button style="background-color: #b02ee0;">aux</button>
		<button style="background-color: #2e97e0;" onclick="outbound()">outbound</button>
	</div>
	<div class="wrapper">
		<b>agent status:</b><br/>
		<span id="agent_status">&nbsp;</span>
	</div>
	<div class="wrapper">
		<input type="text" size="24" id="a_number" value="< type a number in here >" onKeyUp="validateNumericInput(this);" onMouseUp="validateNumericInput(this);"/>
		<!--<button style="background-color: #00A600;" onclick="originateCall(a_number.value,'Tes','20000','L(30000:1000)','02133442358')">dial</button>-->
		<button id="dial" style="background-color: #00A600;" onclick="originateCall(a_number.value)">dial</button>
		<button style="background-color: #e0262e;" onclick="disconnectCall()">disconnect</button>
		<button id="bridge" style="background-color: #FF5B0D;" onclick="bridgeCall()">bridge</button>
		<button style="background-color: #0066FF;">action</button>
	</div>
	<div class="wrapper">
		<b>call status:</b><br/>
		<span id="call_status">&nbsp;</span>
	</div>
	<div class="wrapper">
		<b>duration:</b><br/>
		<span id="call_duration">&nbsp;</span>
	</div>
	<div class="wrapper notes" style="border: none; height: 28px; *height: 36px; text-align: left; vertical-align: top; overflow: auto; padding: none;">
		<span id="telephony_notes" style="margin: 0;">
			&nbsp;
		</span>
	</div>
	<div id="transfer_dialog" title="Transfer Call Target">
		<form>
			<label for="agent_target">Agent ID / Extension</label>
			<input type="text" name="agent_target" id="agent_target" class="ui-widget-content"/>
		</form>
	</div>
	<br/>
</div>
<div id="api"></div>
<div id="controller"></div>
<script type='text/javascript'>
	var scripts = [
			"script/jquery/jquery-1.4.4.min.js",
			"script/jquery/jquery-ui-1.8.14-dialog.min.js",
			"script/jquery/jquery.corner.js",
			"script/flash_detect-1.0.4.min.js",
			"script/swfobject.js",
			"script/log4javascript.js",
			"script/ecentrix/ecentrix.module.js"
		];
	<?
	if (strcmp($connector, "applet")==0){
		echo "scripts.push(\"script/deployJava.js\");";
	}
	?>
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
			loadScript(scripts[counter], function (){
				counter++;
				var progress = (counter / total) * 100;
				document.getElementById("bar").style.width = progress + "%";
				loadNextScript();
			});
		}
		else{
			if (typeof loadTelephonyModule == "function"){
				loadTelephonyModule("<?=$connector?>", "<?=$ctiHost?>", "<?=$ctiPort?>", "<?=$policyPort?>", "<?=$agentId?>", "<?=$ipAddress?>", "<?=$isHosted?>", "<?=$sipServer?>", "<?=$rtmpServer?>",  log4javascript.Level.<?=$jsLogLevel?>);
			}
		}
	};
	window.onload = loadNextScript;
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
</script>
<script type="text/javascript">
	// could be removed
	var showWarning = function (text){
		//alert(text);
	};
</script>
<script type="text/javascript">
</script>
