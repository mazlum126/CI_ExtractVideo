
<?php

// $location_id = 'the_location_id_of_the_venue_instagram';
$location_id = '218435836';
$session_id = '30561969329%3A1qo3IHMa4WxrQd%3A6';

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
 print_r("output_len : ")

 $len = count($output)

 print_r($len);

 print_r($output);
 
 ?>