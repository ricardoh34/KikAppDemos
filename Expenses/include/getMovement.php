<?php 
require_once 'db.php'; // The mysql database connection script

$id= $_GET['idMov'];
$user= isset($_GET['id'])?$_GET['id']:0;

$query="SELECT idMov,value, description, idCat, dates, CASE WHEN moveType = 0 THEN 'Income' ELSE 'Outcome' END AS moveType
                  FROM movements WHERE idMov='$id' AND userId='$user'";

$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

if($result->num_rows > 0) {
	while($row_income = $result->fetch_assoc()) {
		$arr_income = $row_income;	
	}
}

$json_response = json_encode($arr_income);
echo $json_response;
?>