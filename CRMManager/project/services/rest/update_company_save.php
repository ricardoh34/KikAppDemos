<?php

/**
* Rest client services.
* @author KikApp PHP Generator
* @version Beta 1 build 0.1.39
*/

require 'RestClient/HttpClient.class.php';

header('Content-Type: application/json;');

$url= "http://demo.kikapptools.com/CRMManager/crud/updateCompany.php";

$inputJSON = file_get_contents('php://input');
$input= json_decode( $inputJSON, TRUE ); //convert JSON into array

$data = array("id"=>$input["id"],"name"=>$input["name"],"address"=>$input["address"],"phone"=>$input["phone"],"image"=>$input["image"]);
$merge = array();

$searchValue = isset($_GET['Searchtext'])?$_GET['Searchtext']:"";
$start = isset($_GET['start'])?$_GET['start']:"";
$count = isset($_GET['count'])?$_GET['count']:"";

$client = HttpClient::getInstance();
$response = $client->getURL("POST", $url, $data);

if ($response == null)
	$response = '{}';

$response = json_decode($response, true);
$client->paginate($start, $count, $response);

$result = array();
$client->renameJsonKeys($response, $parent=null, $merge, $result);

echo json_encode($result,JSON_UNESCAPED_UNICODE);

?>
