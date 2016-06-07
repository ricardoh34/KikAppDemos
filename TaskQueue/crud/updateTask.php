<?php 
require_once '../includes/db.php'; // The mysql database connection script

if(isset($_POST['taskID'])){
	
	$taskID = $_POST['taskID'];
	$task 	= $_POST['task'];
	$description = $_POST['description']; 
	$created = $_POST['created_at'];
	
	$query="update tasks set task='$task', created_at='$created', description='$description' where id='$taskID'";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	
	$result = $mysqli->affected_rows;
	$response = array('response' => $result);
	
	echo json_encode($response);
}
?>