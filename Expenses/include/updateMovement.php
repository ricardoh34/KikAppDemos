<?php 
require_once 'db.php'; // The mysql database connection script


$id= $_POST['idMov'];
$idCat= $_POST['idCat'];
$moveType= $_POST['move_type'];
$value_new= $_POST['value'];
$description= $_POST['description'];
$date=$_POST['dates'];
$user= isset($_POST['id'])?$_POST['id']:0;

if($moveType=="Income") {
   $Type_auxiliar=0;
}
else {
   $Type_auxiliar=1;
}

$query2="SELECT value,moveType FROM movements WHERE idMov='$id' AND userId='$user'";
$result_1 = $mysqli->query($query2) or die($mysqli->error.__LINE__);
$result_1 = $result_1->fetch_assoc();

$tot= $result_1["value"];
$tipo= $result_1["moveType"];

$query="UPDATE movements SET moveType='$Type_auxiliar', value= '$value_new', description='$description', dates='$date', idCat='$idCat' WHERE idMov='$id' AND userId='$user'";
$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
$result = $mysqli->affected_rows;

//UPDATE
if($tipo==$Type_auxiliar) {
   if($Type_auxiliar==0){
   	$query="UPDATE account SET money=(money+'$value_new'-'$tot'), date='$date' WHERE userID='$user'";
   }
   else {
   	$query="UPDATE account SET money=(money-'$value_new'+'$tot'), date='$date' WHERE userID='$user'";
   }
}
else {
   if($Type_auxiliar==0){
      $query="UPDATE account SET money=(money+'$value_new'+'$tot'), date='$date' WHERE userID='$user'";
   }
   else {
      $query="UPDATE account SET money=(money-'$value_new'-'$tot'), date='$date' WHERE userID='$user'";
   }   
}


$result2 = $mysqli->query($query) or die($mysqli->error.__LINE__);
$result2 = $mysqli->affected_rows;

$json_response = json_encode($result);
echo '{"result":"ok"}';
?>