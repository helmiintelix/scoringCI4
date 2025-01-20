var scripts = [
		"apps/telephony/script/jquery/jquery.corner.js",
		"apps/telephony/script/flash_detect-1.0.4.min.js",
		"apps/telephony/script/swfobject.js",
		"apps/telephony/script/log4javascript.js",
		"apps/telephony/script/ecentrix/ecentrix.module.js"
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
	if (script.readyState){	//IE
		script.onreadystatechange = function(){
			if (script.readyState == "loaded" || script.readyState == "complete"){
				script.onreadystatechange = null;
				callback();
			}
		};
	} 
	else {	//Others
		script.onload = callback;
	}
	script.src = url;
	document.getElementsByTagName("head")[0].appendChild(script);
};
var loadNextScript = function (){
	if (counter>=0 && counter<total){
		loadScript(scripts[counter], function (){
			counter++;
			var progress = (counter / total) * 400;
			document.getElementById("bar").style.width = progress + "%";
			loadNextScript();
		});
	}
	else{
		if (typeof loadTelephonyModule == "function"){
			loadTelephonyModule("<?=$connector?>", "<?=$ctiHost?>", "<?=$ctiPort?>", "<?=$policyPort?>", "<?=$agentId?>", "<?=$ipAddress?>", "<?=$isHosted?>", "<?=$sipServer?>", "<?=$rtmpServer?>",	log4javascript.Level.<?=$jsLogLevel?>);
		}
	}
};
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

$(document).ready(function(){
	loadNextScript();
});	