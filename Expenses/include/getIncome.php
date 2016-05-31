<?php 
require_once 'db.php'; // The mysql database connection script

$id= isset($_GET['id'])?$_GET['id']:0;


$query="SELECT * from account WHERE userID='$id'";
$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
$result = $result->fetch_assoc();

$account = $result["money"];


$query2= "SELECT SUM(value) as total_income FROM movements WHERE userID='$id' AND moveType=0";
$result_income = $mysqli->query($query2) or die($mysqli->error.__LINE__);

$result_income = $result_income->fetch_assoc();
$value_income = $result_income["total_income"];

$query3= "SELECT SUM(value) as total_outcome FROM movements WHERE userID='$id' AND moveType=1";
$result_outcome = $mysqli->query($query3) or die($mysqli->error.__LINE__);

$result_outcome = $result_outcome->fetch_assoc();
$value_outcome = $result_outcome["total_outcome"];
//By date
$query5= "SELECT SUM(value) as total_income_month FROM movements WHERE userID='$id' AND moveType=0 AND (dates between  DATE_FORMAT(NOW() ,'%Y-%m-01') AND NOW() )";
$result_income_month = $mysqli->query($query5) or die($mysqli->error.__LINE__);

$result_income_month = $result_income_month->fetch_assoc();
$value_income_month = $result_income_month["total_income_month"];

$query4= "SELECT SUM(value) as total_outcome_month FROM movements WHERE userID='$id' AND moveType=1 AND (dates between  DATE_FORMAT(NOW() ,'%Y-%m-01') AND NOW() )";
$result_outcome_month = $mysqli->query($query4) or die($mysqli->error.__LINE__);

$result_outcome_month = $result_outcome_month->fetch_assoc();
$value_outcome_month = $result_outcome_month["total_outcome_month"];

//obtener el mes actual asi le asigno al label el mes actual que no se puede poner en el principal

$mons = array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 
               7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");

$date = getdate();
$month = $date['mon'];

$month_name = $mons[$month];

switch ($mons[$month]) {
   case "January" : $label_actual_month= "General view - January";
                  break;
   case "February" : $label_actual_month= "General view - February";
                  break;
   case "March" : $label_actual_month= "General view - March";
                  break;
   case "April" : $label_actual_month= "General view - April";
                  break;
   case "May" : $label_actual_month= "General view - May";
                  break;
   case "June" : $label_actual_month= "General view - June";
                  break;
   case "July" : $label_actual_month= "General view - July";
                  break;
   case "August" : $label_actual_month= "General view - August";
                  break;
   case "September" : $label_actual_month= "General view - September";
                  break;
   case "October" : $label_actual_month= "General view - October";
                  break;
   case "November" : $label_actual_month= "General view - November";
                  break;

   case "December" : $label_actual_month= "General view - December";
                  break;   
   default: break;                                                                                                                                             
}
if($account==NULL) {$account=0;}
if($value_income==NULL) {$value_income=0;}
if($value_outcome==NULL) {$value_outcome=0;}
if($value_income_month==NULL) {$value_income_month=0;}
if($value_outcome_month==NULL) {$value_outcome_month=0;}

echo '{"label_actual_month":' . "\"" . $label_actual_month ."\"". ',"account":'.$account . ',"value_income":'.$value_income. ',"value_outcome":'.$value_outcome. ',"value_income_month":'.(int)$value_income_month. ', "value_outcome_month":'.(int)$value_outcome_month. '}';
 
?>