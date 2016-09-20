<?php

/**
* Rest client services.
* @author KikApp PHP Generator
* @version Beta 1 build 0.1.39
*/

require 'RestClient/HttpClient.class.php';

header('Content-Type: application/json;');

$url= "http://demo.kikapptools.com/CRMManager/crud/getCompanies.php";

$data = array();
$merge = array("CompanyId"=>"id","CompanyImage"=>"image","CompanyName"=>"name","CompanyAddress"=>"address");

$searchValue = isset($_GET['Searchtext'])?$_GET['Searchtext']:"";
$start = isset($_GET['start'])?$_GET['start']:"";
$count = isset($_GET['count'])?$_GET['count']:"";

$client = HttpClient::getInstance();
$response = $client->getURL("GET", $url, $data);

if ($response == null)
	$response = '{}';

$response = json_decode($response, true);
$client->paginate($start, $count, $response);

$result = array();
$client->renameJsonKeys($response, $parent=null, $merge, $result);

$searchKey   = array(0=>"Name");
$result = $client->search($result, $searchKey, $searchValue);

echo json_encode($result,JSON_UNESCAPED_UNICODE);

?>
