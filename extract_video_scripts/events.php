<html>
<head>
<style>
body
{
  padding: 20px;
  background-color: #fcfcfc;
}
.center-div
{
 
  width: 500px;
  background-color: #ccc;
  border-radius: 3px;
}
</style>
<body>

<?php

include('function.php');

$event_id = $_GET['event_id'];

$sql = "SELECT * FROM `events` WHERE `ID` = ".$event_id.";";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	
$row = $result->fetch_assoc();
$array['artist_IG_profil'] = get_artist($row['artist'])['IG_profil'];
$array['location_IG_profil'] = get_location($row['location'])['IG_profil'];
$array['location_IG_location'] = get_location($row['location'])['IG_location'];
	
}

//print_r($array);

$user_id = file_get_contents('http://localhost/ig/new/decode_username.php?username='.$array['artist_IG_profil']);
scrap_IG_profil($user_id,$event_id);

sleep(1);

$user_id = file_get_contents('http://localhost/ig/new/decode_username.php?username='.$array['location_IG_profil']);
scrap_IG_profil($user_id,$event_id);

sleep(1);

scrap_IG_location($array['location_IG_location'],$event_id);


?>

</body>
</html>