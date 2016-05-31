<?php 
require_once 'db.php'; // The mysql database connection script

$id= isset($_GET['id'])?$_GET['id']:0;

$query= "SELECT idMov,value, description, idCat, dates, CASE WHEN moveType = 0 THEN 'Income' ELSE 'Outcome' END AS moveType FROM movements WHERE userId='$id' ORDER BY idMov DESC";

$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

$arr = array();
if($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
      $arr[] = $row; 
	}
}
echo $json_response = json_encode($arr);
?>
