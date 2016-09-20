<?php

/**
* Rest client services.
* @author KikApp PHP Generator
* @version Beta 1 build 0.1.39
*/

require 'RestClient/HttpClient.class.php';

header('Content-Type: application/json;');

$get = array_change_key_case($_GET, CASE_LOWER);
$id = $get['id'];

$url= "http://demo.kikapptools.com/CRMManager/crud/getCompanies.php";

$data = array("companyId"=>$id);
$merge = array("companyId"=>"id","CompanyImage"=>"image","CompanyName"=>"name","CompanyAddress"=>"address","CompanyPhone"=>"phone");

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

echo json_encode($result,JSON_UNESCAPED_UNICODE);

?>
