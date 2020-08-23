<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$DBname = 'domix_scrap';
           
$conn = new mysqli($servername, $username, $password, $DBname);

$session_id = '256722665%3A5Dc4qJ8JzQxkP1%3A0';



function get_location($ID) {
	
$sql = 'SELECT * FROM `locations` WHERE `ID` ='.$ID;
$result = $GLOBALS['conn']->query($sql);
if ($result->num_rows > 0) {

$row = $result->fetch_assoc();

return $row;
}

}

function get_artist($ID) {
	
$sql = 'SELECT * FROM `artists` WHERE `ID` ='.$ID;
$result = $GLOBALS['conn']->query($sql);
if ($result->num_rows > 0) {

$row = $result->fetch_assoc();
	
return $row;
}

}

function scrap_IG_location($location_id,$event_id) {
	
	
	

$url = 'https://stevesie.com/cloud/api/v1/endpoints/bf081b41-5f87-47c9-8116-d20713ae10b8/executions';

$params = '{
    "inputs": {
        "location_id": "'.$location_id .'",
        "session_id": "'.$GLOBALS['session_id'].'"
    },
    "proxy": {
      "type": "custom",
      "url": "lum-customer-domix-zone-zone4:i7axramu62v9@zproxy.lum-superproxy.io:22225"
    },
    "format": "json"
}';


$defaults = array(
CURLOPT_URL => $url,
CURLOPT_POSTFIELDS => $params,
CURLOPT_RETURNTRANSFER => 1,
CURLOPT_HTTPHEADER => array('Token: 4ff70330-44d4-433b-b411-550c77900263',
'Content-Type: application/json')
);

$ch = curl_init();
curl_setopt_array($ch, $defaults);

 $output = curl_exec($ch); 

$json = json_decode($output);



$count = count($json->object->response->response_json->data->reels_media[0]->items);

$i=0; $ii=0; $users_list = Array();
while ($i < $count) {
	
	if ($json->object->response->response_json->data->reels_media[0]->items[$i]->is_video ==1 && $json->object->response->response_json->data->reels_media[0]->items[$i]->video_duration >4 && $json->object->response->response_json->data->reels_media[0]->items[$i]->has_audio ==1) { 
	
		$users_list[] = $json->object->response->response_json->data->reels_media[0]->items[$i]->owner->id;
		$ii++;
	
	}
		
	$i++;
}


if (count($users_list) > 0) { $i=0;

$users_list = array_unique($users_list);

$count = count($users_list);

while ($i < $count) {
	
	if (isset($users_list[$i])) {
		
		scrap_IG_profil($users_list[$i],$event_id);
	
//	$url = 'http://localhost/ig/scrap_profil.php?event_id='.$event_id.'&user_id='.$users_list[$i];

	}

// file_get_contents($url); 

sleep(1);

$i++;

}

}
	
	
	
}

function scrap_IG_profil($user_id,$event_id) {
$url = 'https://stevesie.com/cloud/api/v1/endpoints/30ad53bd-6fe9-4485-9d81-b713597d28d8/executions';

$params = '{
    "inputs": {
        "user_id": "'.$user_id .'",
        "session_id": "'.$GLOBALS['session_id'].'"
    },
        "proxy": {
      "type": "custom",
      "url": "lum-customer-domix-zone-zone4:i7axramu62v9@zproxy.lum-superproxy.io:22225"
    },
    "format": "json"
}';

$defaults = array(
CURLOPT_URL => $url,
CURLOPT_POSTFIELDS => $params,
CURLOPT_RETURNTRANSFER => 1,
CURLOPT_HTTPHEADER => array('Token: 4ff70330-44d4-433b-b411-550c77900263',
'Content-Type: application/json')
);

$ch = curl_init();
curl_setopt_array($ch, $defaults);

$output = curl_exec($ch); 

$json = json_decode($output); 

// print_r($json->object->response->response_json->items); die;

$count = count($json->object->response->response_json->items);

$i=0; $ii=0;
while ($i < $count) {
	
	if (isset($json->object->response->response_json->items[$i]->video_dash_manifest) && $json->object->response->response_json->items[$i]->has_audio ==1 && $json->object->response->response_json->items[$i]->video_duration >4) { 
	
		$url = $json->object->response->response_json->items[$i]->video_versions[0]->url;
		$width = $json->object->response->response_json->items[$i]->video_versions[0]->width;
		$height = $json->object->response->response_json->items[$i]->video_versions[0]->height;
		$user_id = $json->object->response->response_json->items[$i]->user->pk;
		$username = $json->object->response->response_json->items[$i]->user->username;
		$profile_pic_url = $json->object->response->response_json->items[$i]->user->profile_pic_url;
		$device_timestamp = $json->object->response->response_json->items[$i]->device_timestamp;
		
$sql = "INSERT INTO `stories` (`ID`, `event_ID`, `url`, `width`, `height`, `user_id`, `username`, `profile_pic_url`, `device_timestamp`) VALUES (NULL, '".$event_id."', '".$url."', '".$width."', '".$height."', '".$user_id."', '".$username."', '".$profile_pic_url."', '".$device_timestamp."');";
$result = $GLOBALS['conn']->query($sql); 
		
		$ii++;
	}
	
	$i++;
}

}

?>