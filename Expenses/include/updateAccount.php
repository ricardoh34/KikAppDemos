<?php 
require_once 'db.php'; // The mysql database connection script


$id= isset($_POST['id'])?$_POST['id']:0;
$money= $_POST['money'];
$date= $_POST['dates'];


$query="UPDATE account SET money=(money+'$money'), date='$date' WHERE userID='$id'";

$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
$result = $mysqli->affected_rows;
$json_response = json_encode($result);


echo '{"result":"ok"}';
?>