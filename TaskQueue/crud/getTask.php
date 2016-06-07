<?php 

require_once '../includes/db.php'; // The mysql database connection script

if(isset($_GET['id'])){
	$whereCondition = 'id='.$_GET['id'];
}else if(isset($_GET['status'])){
	$whereCondition = 'status='.$_GET['status'];
}else{
	//not deleted tasks
	$whereCondition = 'status!=3';
}


if(isset($_GET['period']) && intval($_GET['period'])!=0){
	if(intval($_GET['period'])==1){
		$dateConition = " AND created_at = DATE(NOW()) ";
	}else if(intval($_GET['period'])>=0){
		$dateConition = " AND created_at BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL ".$_GET["period"]." DAY) ";
	}else{
		$dateConition = " AND created_at BETWEEN  DATE(NOW()- INTERVAL abs(".$_GET["period"].") DAY) AND NOW() ";
	}	
}else{
	$dateConition = "";
}

$query="select id, task, status, created_at, description from tasks where ".$whereCondition.$dateConition." order by created_at asc";
$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

$arr = array();
if($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$arr[] = $row;	
	}
}

# JSON-encode the response
echo json_encode($arr);


?>