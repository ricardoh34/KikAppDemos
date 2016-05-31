<?php 
require_once 'db.php'; // The mysql database connection script

$id= isset($_POST['id'])?$_POST['id']:0;

$query_income="SELECT * FROM movements WHERE userID='$id' AND moveType='0' ";

$query_outcome="SELECT * FROM movements WHERE userID='$id' AND moveType='1' ";


$result_income = $mysqli->query($query_income) or die($mysqli->error.__LINE__);
$result_outcome = $mysqli->query($query_outcome) or die($mysqli->error.__LINE__);


$arr_income = array();
if($result_income->num_rows > 0) {
	while($row_income = $result_income->fetch_assoc()) {
		$arr_income[] = $row_income;	
	}
}

$arr_outcome = array();
if($result_outcome->num_rows > 0) {
	while($row_outcome = $result_outcome->fetch_assoc()) {
		$arr_outcome[] = $row_outcome;	
	}
}


echo $json_response = json_encode($arr_income);
echo $json_response = json_encode($arr_outcome);
?>
