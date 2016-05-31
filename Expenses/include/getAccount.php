<?php 
require_once 'db.php'; // The mysql database connection script

$id= isset($_POST['id'])?$_POST['id']:0;
$query="SELECT * from account WHERE userID='$id'";
$result = $mysqli->query($query) or die($mysqli->error.__LINE__);


if($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$arr = $row;	
	}
}

echo $json_response = json_encode($arr);
?> 