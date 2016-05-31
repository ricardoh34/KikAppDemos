<?php 
require_once 'db.php'; // The mysql database connection script

$id= isset($_POST['id'])?$_POST['id']:0;

$query5= "SELECT SUM(value) as total_income_month FROM movements WHERE userID='$id' 
                  AND moveType=0 AND (dates between  DATE_FORMAT(NOW() ,'%Y-%m-01') AND NOW() )";

$result_income_month = $mysqli->query($query5) or die($mysqli->error.__LINE__);

$result_income_month = $result_income_month->fetch_assoc();
$value_income_month = $result_income_month["total_income_month"];

$query4= "SELECT SUM(value) as total_outcome_month FROM movements WHERE userID= '$id'
                  AND moveType=1 AND (dates between  DATE_FORMAT(NOW() ,'%Y-%m-01') AND NOW() )";
                  
$result_outcome_month = $mysqli->query($query4) or die($mysqli->error.__LINE__);

$result_outcome_month = $result_outcome_month->fetch_assoc();
$value_outcome_month = $result_outcome_month["total_outcome_month"];

echo '{"value_income_month":'.(int)$value_income_month. ',"value_outcome_month":'.(int)$value_outcome_month. '}';
?>