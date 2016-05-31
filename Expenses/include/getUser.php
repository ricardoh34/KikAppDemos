<?php 
require_once 'db.php'; // The mysql database connection script

$user= isset($_GET['id'])?$_GET['id']:0;

$query="SELECT username FROM users WHERE id='$user'";

$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
$result = $result->fetch_assoc();

$username = $result["username"];

echo '{"username": "'. $username .'"}';
?>