<?
session_start();

$_CONFIG["asterisk.ip_address"] = $_SERVER["SERVER_ADDR"];
$_CONFIG["asterisk.ip_address"] = "192.168.0.37";
$_CONFIG["asterisk.port"] = "8088";
$_CONFIG["asterisk.username"] = "supervisor";
$_CONFIG["asterisk.secret"] = "supervisor1234";

/**
 * Send HTTP request to Asterisk and store the cookie
 */
function open_asterisk_url ($url){
	$str = "";
	$opts = array(
	  'http'=>array(
		'method'=>"GET",
		'header'=>"Accept-language: en\r\n" .
				  "Cookie: ".@$_SESSION["asterisk"]."\r\n"
	  )
	);
	$context = stream_context_create($opts);

	$fd = @fopen($url, "rb", false, $context);
	while($data = @fread($fd, 1024)) {
		$str .= $data;
	}
	$meta_data = @stream_get_meta_data($fd);
	if (sizeof($meta_data)>0 && is_array(@$meta_data["wrapper_data"])){
		foreach ($meta_data["wrapper_data"] as $value){
			if (substr($value,0,strlen("Set-Cookie"))==="Set-Cookie"){
				$cookie = trim(str_replace("Set-Cookie:", "", $value));
				$_SESSION["asterisk"] = $cookie;
				break;
			}
		}
	}
	@fclose($fd);

	return $str;
}
/**
 * Login to Asterisk
 * http://www.voip-info.org/wiki/view/Asterisk+Manager+API+Action+Login
 */
function login($config){
	$result = true;
	$url  = "http://".$config["asterisk.ip_address"].":".$config["asterisk.port"]."/asterisk/rawman?";
	$url .= "action=login&username=".$config["asterisk.username"]."&secret=".$config["asterisk.secret"];
	$str = open_asterisk_url($url);
	if (preg_match("/Response:\s+Error/i", $str)){
		$result = false;
		preg_match_all("/Message:\s+([^\r\n]+)\r\n/i", $str, $matches);
		if (strlen(trim(@$matches[1][0]))>0){
			$result = trim(@$matches[1][0]);
		}	
	}
	return $result;
}
/**
 * Logout from Asterisk
 * http://www.voip-info.org/wiki/view/Asterisk+Manager+API+Action+Logoff
 */
function logoff($config){
	$url  = "http://".$config["asterisk.ip_address"].":".$config["asterisk.port"]."/asterisk/rawman?action=logoff";
	open_asterisk_url($url);
}
/**
 * Get SIP peer information
 * http://www.voip-info.org/wiki/view/Asterisk+Manager+API+Action+SIPshowPeer
 */
function sip_show_peer ($config, $peer){
	$result = array();
	$result["response"] = true;
	$result["authorized"] = true;
	$url  = "http://".$config["asterisk.ip_address"].":".$config["asterisk.port"]."/asterisk/rawman?";
	$url .= "action=SIPShowPeer&peer=".$peer;
	$str = open_asterisk_url($url);
	if (preg_match("/Response:\s+Success/i", $str)){
		preg_match_all("/RegExpire:\s+([^\r\n]+)\r\n|Address-IP:\s+([^\r\n]+)\r\n/i", $str, $matches);
		if (strlen(trim(@$matches[1][0]))>0){
			$expire = intval(@$matches[1][0],10);
			$address = trim(@$matches[2][1]);
			if ($expire<0 && $address==="(null)"){
				$result["response"] = false;
				$result["reason"] = "Peer ".$peer." is offline.";
			}
			else {
				$result["address"] = trim($address);
			}
		}
	}
	else {
		$result["response"] = false;
		preg_match_all("/Message:\s+([^\r\n]+)\r\n/i", $str, $matches);
		if (strlen(trim(@$matches[1][0]))>0){
			$result["reason"] = trim(@$matches[1][0]);
			if ($result["reason"]==="Permission denied"){
				$result["authorized"] = false;
			}
		}
	}
	return $result;
}
/**
 * Spying or whispering channel
 * http://www.voip-info.org/wiki/view/Asterisk+Manager+API+Action+Originate
 * http://www.voip-info.org/wiki/view/Asterisk+cmd+ChanSpy
 * http://www.asterisk.org/docs/asterisk/trunk/applications/chanspy
 */
function chan_spy ($config, $source_peer, $destination_peer, $option = "", $act = "spying"){
	$result = array();
	$result["response"] = true;
	$result["authorized"] = true;
	$url  = "http://".$config["asterisk.ip_address"].":".$config["asterisk.port"]."/asterisk/rawman?";
	$url .= "action=Originate&channel=SIP/".$source_peer."&";
	$url .= "application=ChanSpy&data=SIP/".$destination_peer.",".$option."&";
	$url .= "callerid=\"".$act."+(".$destination_peer.")\"<".$destination_peer.">&";
	$url .= "async=true&timeout=10000";
	$str = open_asterisk_url($url);
	if (preg_match("/Response:\s+Error/i", $str)){
		$result["response"] = false;
		preg_match_all("/Message:\s+([^\r\n]+)\r\n/i", $str, $matches);
		if (strlen(trim(@$matches[1][0]))>0){
			$result["reason"] = trim(@$matches[1][0]);
			if ($result["reason"]==="Permission denied"){
				$result["authorized"] = false;
			}
		}
	}
	return $result;
}

// initiate
$result = array("response" => "ERROR"); // non optimistic
$peer = array();
$status = array();
$local = $_SERVER["REMOTE_ADDR"];
// get passing parameter
$peer["source"] = intval(trim(isset($_GET["source_peer"])?@$_GET["source_peer"]:@$_POST["source_peer"]), 10);
$peer["destination"] = intval(trim(isset($_GET["destination_peer"])?@$_GET["destination_peer"]:@$_POST["destination_peer"]), 10);
$option = isset($_GET["option"])?@$_GET["option"]:(isset($_POST["option"])?@$_POST["option"]:"");
$act = isset($_GET["act"])?@$_GET["act"]:(isset($_POST["act"])?@$_POST["act"]:"spying");

// check source peer number
if ($peer["source"]<=0){
	$result["reason"] = "Invalid or empty source peer";
	echo json_encode($result);
	exit;
}
// check destination peer number
if ($peer["destination"]<=0){
	$result["reason"] = "Invalid or empty destination peer";
	echo json_encode($result);
	exit;
}
// must not equal
if ($peer["source"]===$peer["destination"]){
	$result["reason"] = "Source peer must not equal with destination peer";
	echo json_encode($result);
	exit;
}
// get status of peer (both source and destination)
foreach (array("source", "destination") as $origin){
	$status[$origin] = sip_show_peer($_CONFIG, $peer[$origin]);
	if ($status[$origin]["authorized"]===false){
		if (($login = login($_CONFIG))===true){
			$status[$origin] = sip_show_peer($_CONFIG, $peer[$origin]);
		}
		else {
			$result["reason"] = $login;
			echo json_encode($result);
			logoff($_CONFIG);
			exit;
		}
	}
}
// check status of source peer
if ($status["source"]["response"]===false || $status["destination"]["response"]===false){
	if ($status["source"]["response"]===false){
		$result["reason"] = $status["source"]["reason"];
	}
	$result["reason"] = isset($result["reason"])?$result["reason"]." ":"";
	if ($status["destination"]["response"]===false){
		$result["reason"] .= $status["destination"]["reason"];
	}
	echo json_encode($result);
	logoff($_CONFIG);
	exit;
}
// check ip address of source peer
if ($status["source"]["address"]!==$local){
	$result["reason"] = "Unmatched address";
	echo json_encode($result);
	logoff($_CONFIG);
	exit;
}
// start spy
$spy = chan_spy ($_CONFIG, $peer["source"], $peer["destination"], $option, $act);
if ($spy["authorized"]===false){
	if (($login = login($_CONFIG))===true){
		$spy = chan_spy ($_CONFIG, $peer["source"], $peer["destination"], $option, $act);
	}
	else {
		$result["reason"] = $login;
		echo json_encode($result);
		logoff($_CONFIG);
		exit;
	}
}
if ($spy["response"]===true){
	$result["response"] = "SUCCESS";
}
else {
	$result["reason"] = $spy["reason"];
}
logoff($_CONFIG);
echo json_encode($result);
?>
