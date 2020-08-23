<?php

include('function.php');


if (isset($_GET['username']) ) {
	
	$user = $_GET['username'];


$url = 'https://stevesie.com/cloud/api/v1/endpoints/ea462d24-fa4f-4b36-ab01-1c908b021a8b/executions';



$params = '{
    "inputs": {
        "username": "'.$user.'",
        "session_id": "'.$session_id.'"
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

print_r($json->object->response->response_json->graphql->user->id);

}
?>