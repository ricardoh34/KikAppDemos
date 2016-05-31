<?php 
require_once 'db.php'; // The mysql database connection script


$id= isset($_POST['id'])?$_POST['id']:0;
$moveType= $_POST['moveType'];
$value= $_POST['value'];
$description= $_POST['description'];
$idCat= $_POST['idCat'];
$date= $_POST['dates'];


$query="INSERT INTO movements(value, moveType, description, userId, idCat, dates) VALUES ('$value', '$moveType', '$description', '$id','$idCat', '$date')";

$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
$result = $mysqli->affected_rows;

//UPDATE
if($moveType==0){
	$query="UPDATE account SET money=(money+'$value'), date='$date' WHERE userID='$id'";
}
else {
	$query="UPDATE account SET money=(money-'$value'), date='$date' WHERE userID='$id'";
}


$result2 = $mysqli->query($query) or die($mysqli->error.__LINE__);
$result2 = $mysqli->affected_rows;




echo '{"result":"ok"}';

?>