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


$servername = 'localhost';
$username = 'root';
$password = '';
$DBname = 'domix_scrap';


$conn = new mysqli($servername, $username, $password, $DBname);


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



if (isset($_POST['page'])) {
	if ($_POST['page'] == 'new_artist') {
		
		$name = $GLOBALS['conn']->real_escape_string($_POST['name']);
		$IG_profil = $GLOBALS['conn']->real_escape_string($_POST['IG_profil']);
				
$sql = "INSERT INTO `artists` (`name`, `IG_profil`) VALUES ('$name', '$IG_profil');";
	
	if(mysqli_query($GLOBALS['conn'], $sql)){
    echo "New artist inserted successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($GLOBALS['conn']);
}

	
	}
}


if (isset($_POST['page'])) {
	if ($_POST['page'] == 'new_location') {
		
		$name = $GLOBALS['conn']->real_escape_string($_POST['name']);
		$IG_profil = $GLOBALS['conn']->real_escape_string($_POST['IG_profil']);
		$IG_location = $GLOBALS['conn']->real_escape_string($_POST['IG_location']);
				
$sql = "INSERT INTO `locations` (`name`, `IG_profil`,  `IG_location`) VALUES ('$name', '$IG_profil',  '$IG_location');";
	
	if(mysqli_query($GLOBALS['conn'], $sql)){
    echo "New location inserted successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($GLOBALS['conn']);
}

	
	}
}

if (isset($_POST['page'])) {
	if ($_POST['page'] == 'new_event') {
		
		$artist = $GLOBALS['conn']->real_escape_string($_POST['artist']);
		$location = $GLOBALS['conn']->real_escape_string($_POST['location']);
		$date = $GLOBALS['conn']->real_escape_string($_POST['date']);
		
		$sql = "INSERT INTO `events` (`artist`, `location`, `date`) VALUES ('$artist', '$location', '$date');";
	
	if(mysqli_query($GLOBALS['conn'], $sql)){
    echo "New event inserted successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($GLOBALS['conn']);
}

	
		
		
	}
}


?>

<div class="center-div">
<h2>Events</h2>
<select name="events">
<option value="0">Select event</option>
<?php

$sql = "SELECT * FROM `events` ORDER BY `events`.`date` ASC";
$result = $conn->query($sql);
$i=0;
if ($result->num_rows > 0) {
	
    while($row = $result->fetch_assoc()) {
		
		echo '<option>';
		echo get_artist($row['artist'])['name'];
		echo ' @ '.get_location($row['location'])['name'];
		echo ' - '.$row['date'].'</option>';		
	}
	
}

?>
</select>

</div>

<div class="center-div">
<h2>New event</h2>
<form action="" method="POST">

<input type="hidden" name="page" value="new_event">

<select name="artist">
<option value="0">Select artist</option>
<?php

$sql = "SELECT * FROM `artists` ORDER BY `artists`.`name` ASC";
$result = $conn->query($sql);
$i=0;
if ($result->num_rows > 0) {
	
    while($row = $result->fetch_assoc()) {
		
		echo '<option value="'.$row['ID'].'">';
		
		echo ''.$row['name'].'<br>';
		
		echo '</option>';
		
	}
}


?>
</select>
<br>
<select name="location">
<option value="0">Select location</option>
<?php

$sql = "SELECT * FROM `locations` ORDER BY `locations`.`name` ASC";
$result = $conn->query($sql);
$i=0;
if ($result->num_rows > 0) {
	
    while($row = $result->fetch_assoc()) {
		
		echo '<option value="'.$row['ID'].'">';
		
		echo ''.$row['name'].'<br>';
		
		echo '</option>';
		
	}
}


?>
</select><br>
<input type="date" name="date" required="required"><br>
<input type="submit">
</form>

</div>

<div class="center-div">
<h2>New artist</h2>
<form action="" method="POST">
<input type="hidden" name="page" value="new_artist">
<input type="text" name="name" placeholder="name" required="required"><br>
<input type="text" name="IG_profil" placeholder="IG_profil" required="required"><br>
<input type="submit">
</div>

</form>

<div class="center-div">
<h2>New location</h2>
<form action="" method="POST">
<input type="hidden" name="page" value="new_location">
<input type="text" name="name" placeholder="name" required="required"><br>
<input type="text" name="IG_profil" placeholder="IG_profil" required="required"><br>
<input type="text" name="IG_location" placeholder="IG_location" required="required"><br>
<input type="submit">
</div>
</body>
</html>