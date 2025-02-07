<?php

include_once(dirname(__FILE__).'/util/class.database.php');
include_once(dirname(__FILE__). "/util/encrypt_decrypt.php");
include_once(dirname(__FILE__). "/util/common.php");
require_once (dirname(__FILE__). "/util/KLogger.php");

$logfile = dirname(__FILE__)."/logs/agent_tracking_".date('Ymd').".log";
$tz = new DateTimeZone('Asia/Jakarta');
date_default_timezone_set('Asia/Jakarta');

$log = new KLogger ($logfile , KLogger::DEBUG);
$dateGenerate = date('Y-m-d');

$log->LogInfo(" [START] EOD Process...\n\n");
$db = Database::getInstance();
try {
    $conn = $db->getConnection();
} catch(Exception $e) {
    $log->LogError("Connect failed: %s\n", $e);
    exit();
}


$sql = "";

// Function to calculate distance using Haversine formula
function haversineGreatCircleDistance($longitudeFrom, $latitudeFrom, $longitudeTo, $latitudeTo) {
    $earthRadius = 6378137 ; // Earth radius in meters

    $dLat = deg2rad($latitudeTo - $latitudeFrom);
    $dLon = deg2rad($longitudeTo - $longitudeFrom);
  
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) *
         sin($dLon / 2) * sin($dLon / 2);
  
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return round($earthRadius * $c); // Distance in meters
}

//function get_distance_by_google_direction_api($lat1,$lon1,$lat2,$lon2,$agentId,$created_time,$distance,$google_distance,$timeDifference) {
function get_distance_by_google_direction_api($lon1,$lat1,$lon2,$lat2) {
    global $log;
    
    $api_key = "AIzaSyAdqI_WCC0s08lbJNCVq3AOIS_XYlP97S4"; // Replace with actual API key
    $origin = $lat1 . "," . $lon1;
    $destination = $lat2 . "," . $lon2;
    
    $url = "https://maps.googleapis.com/maps/api/directions/json?origin=" . $origin . "&destination=" . $destination . "&key=" . $api_key;
    //$log->LogInfo($url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_PROXY, 'cnproxy.cimbniaga.co.id:443');
    
    $result = curl_exec($ch);
    curl_close($ch);
    //$log->LogInfo($result);
    $response = json_decode($result, true);
    if ($response['status'] == 'OK') {
        $distance["calculated_distance"] = $response['routes'][0]['legs'][0]['distance']['value'] ; 
        $distance["duration"] = $response['routes'][0]['legs'][0]['duration']['value'] ; 
        $distance["start_address"] = $response['routes'][0]['legs'][0]['start_address'] ; 
        $distance["end_address"] = $response['routes'][0]['legs'][0]["end_address"] ; 
        $distance["status"] = $response['status'] ; 
        // Log the distance calculation
        //$log->LogInfo("Agent ID: " . $agentId . " - Google Maps Distance: " . $calculated_distance . " m");
        
        return $distance;
    } else {
        $log->LogError("Google Directions API Error: " . $response['status']);
       
        $distance["status"] = $response['status'] ; 
       
        return $distance;
    }



}

function insertLog($data, $response, $service){
    global $conn;

    $sql = "INSERT INTO mobcoll_gmap_api_log (`id` ,`agent_id` ,`longitude` ,`latitude` ,`created_time`,`service` ,`status` ,`data` ,`source`,`insert_time`) VALUES 
    (UUID(), '".$data["agent_id"]."', '".$data["longitude"]."', '".$data["latitude"]."', '".$data["created_time"]."', '".$service."', '".$response["status"]."', '".json_encode($response)."', 'job', NOW())";
    echo $sql."\n";
    $result	= $conn->query($sql);
   
    return $result;
}


function cek_distance(){
    global $log,$conn, $dateGenerate;

   
    $sql = "SELECT UPPER(agent_id) agent_id, a.created_time,longitude,latitude,google_distance,distance,IFNULL(address ,'')address
    FROM fc_location_history a
    WHERE created_time > CONCAT('$dateGenerate',' 00:00:00') AND created_time < CONCAT('$dateGenerate',' 23:59:00') AND trx_type != 'TRACKING-A' ORDER BY UPPER(agent_id),a.created_time ";
    echo $sql."\n";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $currentAgentId = null;
        $lastLongitude = null;
        $lastLatitude = null;
        $totalDistance = 0;	
        
        $last_created_time = null;
        $i=0;
        while ($row = $result->fetch_assoc()) {
            //var_dump($row);
            $agentId = $row['agent_id'];
            $createdTime = $row['created_time'];
            $longitude = $row['longitude'];
            $latitude = $row['latitude'];
            $is_new_agent = false;
            // If it's a new agent, reset the distance calculation
            if ($currentAgentId !== $agentId) {
                if ($currentAgentId !== null) {
                    echo "Total distance for agent $currentAgentId: $totalDistance m\n";
                }
                $is_new_agent = true;
                $i=0;
                $currentAgentId = $agentId;
                $lastLongitude = null;
                $lastLatitude = null;
            
                $totalDistance = 0; // Reset distance for new agent
            }
            //calculate time difference from last point
            $timeDifference = null;
            if ($last_created_time !== null) {
                $timeDifference = strtotime($createdTime) - strtotime($last_created_time);
            }

            

            // Calculate distance from the last point
            if ($lastLongitude !== null && $lastLatitude !== null && !$is_new_agent && $row["google_distance"] == null)  {
                
                $distance = haversineGreatCircleDistance($lastLongitude, $lastLatitude, $longitude, $latitude);
                $resp_google =  get_distance_by_google_direction_api($lastLongitude, $lastLatitude, $longitude, $latitude) ;
                insertLog($row, $resp_google, "directions");
                // var_dump($resp_google);die;
                if($resp_google["status"]=="OK"){
                    $distance_by_google = $resp_google["calculated_distance"];
                    $totalDistance += $distance;
                    if($i==1){
                        $sql = "update fc_location_history set loc_address = if(isnull(loc_address),?,loc_address) where agent_id = ? and created_time=?";
                        try {
                            $stmt3 = $conn->prepare($sql);
                            $stmt3->bind_param("sss",$resp_google["start_address"],$agentId,$last_created_time);
                            $stmt3->execute();
                        }catch(Exception $e) {
                            $log->LogError("Error: $e");
                            $log->LogError("SQL: $sql");
                            exit();
                        }
                    }

                    $timeDifference = null;
                    if ($last_created_time !== null) {
                        $timeDifference = strtotime($createdTime) - strtotime($last_created_time);
                    }

                    $sql = "update fc_location_history set google_distance=?,distance=?,time_difference=?, loc_address = if(isnull(loc_address),?,loc_address) where agent_id = ? and created_time=?";
                    try {
                        $stmt3 = $conn->prepare($sql);
                        $stmt3->bind_param("ssssss",$distance_by_google,$distance,$timeDifference,$resp_google["end_address"],$agentId,$createdTime);
                        $stmt3->execute();
                    }catch(Exception $e) {
                        $log->LogError("Error: $e");
                        $log->LogError("SQL: $sql");
                        exit();
                    }
                    echo "distance for agent $currentAgentId: $distance m, distance_by_google $distance_by_google m at $createdTime\n";
                }
            }

            // Update last location
            $lastLongitude = $longitude;
            $lastLatitude = $latitude;
            $last_created_time = $createdTime;
            $i++;
        }

        // Output distance for the last agent after the loop
        if ($currentAgentId !== null) {
            echo "Total distance for agent $currentAgentId: $totalDistance km\n";
        }
    } else {
        echo "No location history found.";
    }
}

while(true){
    $jam = date('G');
    if($jam > 10){
            cek_distance();

            sleep(7200);
    }
}


?>
