<?php 
// The mysql database connection script
require_once '../includes/db.php'; 

if(isset($_POST['task'])){

	$task = $_POST['task'];
	$taskID = $_POST['taskID'];
	$description = $_POST['description'];
	$created = $_POST['created_at'];
	
	if($taskID==0){
		$query="INSERT INTO tasks(task, status, created_at, description)  VALUES ('$task', 0, '$created','$description')";
	}
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	$result = $mysqli->affected_rows;
	$response = array('response' => $result);
	
	echo json_encode($response);
	
}
?>