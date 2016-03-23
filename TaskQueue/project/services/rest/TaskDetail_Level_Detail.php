<?php
/**
* Rest client services.
* @author KikApp PHP Generator
* @version 1.0 
*/
$empty = array();

$id=""; 
$name=""; 
$date=""; 
$image=""; 
$status=""; 

$varDefault = array(
		"id"=>$id, 
		"name"=>$name, 
		"date"=>$date, 
		"image"=>$image, 
		"status"=>$status, 
							
	);

$result = array_merge(
            $empty, $varDefault
        );	

echo json_encode($result);
?>
