<?php 
require_once 'db.php'; // The mysql database connection script

$id= $_POST['idMov'];
$moveType= $_POST['move_type'];
$value_new= $_POST['value'];
$date=$_POST['dates'];
$user= isset($_POST['id'])?$_POST['id']:0;

if($moveType=="Income") {
   $Type_auxiliar=0;
}
else {
   $Type_auxiliar=1;
}

//UPDATE ACCOUNT
if($Type_auxiliar==0){
   $query2="UPDATE account SET money=(money-'$value_new'), date='$date' WHERE userID='$user'";
}
else {
   $query2="UPDATE account SET money=(money+'$value_new'), date='$date' WHERE userID='$user'";
}

$result2 = $mysqli->query($query2) or die($mysqli->error.__LINE__);
$result2 = $mysqli->affected_rows;

$query="DELETE FROM movements WHERE idMov='$id' AND userId='$user'";
$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
$result = $mysqli->affected_rows;

$arr = array('response'=>'');

echo '{"result":"ok"}';
?>
