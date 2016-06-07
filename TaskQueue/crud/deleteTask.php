<?php 
require_once '../includes/db.php'; // The mysql database connection script

if(isset($_GET['taskID']) && isset($_GET['action'])){

	$taskID = $_GET['taskID'];
	
	if($_GET['action'] == 'update'){
		
		//save it as done	
		$query="update tasks set status='3' where id='$taskID'";
			
	}else if($_GET['action'] == 'delete'){
			
		//remove it from db
		$query="delete from tasks where id='$taskID'";
	}
	
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	$result = $mysqli->affected_rows;
	$response = array('response' => $result);
	echo json_encode($response);
	
}
?>